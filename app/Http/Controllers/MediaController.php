<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateMediaRequest;
use App\Models\Collection;
use App\Models\Media;
use App\Models\Project;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class MediaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except(['portfolio', 'showProject']);
    }

    public function index(Request $request)
    {
        $this->authorize('viewAny', Media::class);

        $media = Media::with(['tags', 'collections'])
            ->filter($request->only(['search', 'media_type', 'collection', 'tag']))
            ->latest()
            ->paginate(24)
            ->withQueryString();

        $collections = Collection::withCount('media')->orderBy('name')->get();
        $tags = Tag::withCount('media')->orderBy('name')->get();
        $selectedCollection = $request->input('collection');
        $selectedTag = $request->input('tag');
        $selectedType = $request->input('media_type');
        $search = $request->input('search');

        return view('admin.media.index', compact(
            'media',
            'collections',
            'tags',
            'selectedCollection',
            'selectedTag',
            'selectedType',
            'search'
        ));
    }

    public function portfolio(Request $request)
    {
        $category = $request->query('category');

        $projectsQuery = Project::with('hero')
            ->orderBy('is_featured', 'desc')
            ->orderBy('order')
            ->latest();

        if ($category) {
            $projectsQuery->where('category', $category);
        }

        $projects = $projectsQuery->get();
        $tags = Tag::withCount('media')->orderBy('name')->get();

        $categories = Project::whereNotNull('category')
            ->distinct()
            ->pluck('category')
            ->sort()
            ->values();

        return view('portfolio', compact('projects', 'tags', 'categories'));
    }

    public function showProject(Project $project)
    {
        $project->load(['media', 'hero']);

        return view('portfolio.show', compact('project'));
    }

    public function store(Request $request)
    {
        $this->authorize('create', Media::class);

        $validated = $request->validate([
            'file' => [
                'required',
                'file',
                'mimes:jpeg,png,jpg,gif,webp,webm,mpeg,mp4',
                'max:51200',
                function ($attribute, $value, $fail) {
                    $allowedImageMimes = ['image/jpeg', 'image/png', 'image/jpg', 'image/gif', 'image/webp'];
                    $allowedVideoMimes = ['video/webm', 'video/mpeg', 'video/mp4'];
                    $allowedMimes = array_merge($allowedImageMimes, $allowedVideoMimes);

                    $actualMime = $value->getMimeType();

                    if (! in_array($actualMime, $allowedMimes, true)) {
                        $fail('The file type is not allowed. Only images (JPEG, PNG, GIF, WebP) and videos (WebM, MPEG, MP4) are permitted.');
                    }

                    if (in_array($actualMime, $allowedImageMimes, true)) {
                        $imageInfo = @getimagesize($value->getPathname());

                        if ($imageInfo === false) {
                            $fail('Unable to validate image dimensions.');

                            return;
                        }

                        $width = $imageInfo[0];
                        $height = $imageInfo[1];

                        if ($width < 100 || $height < 100) {
                            $fail('Image must be at least 100x100 pixels.');
                        }

                        if ($width > 10000 || $height > 10000) {
                            $fail('Image must not exceed 10000x10000 pixels.');
                        }
                    }

                    if (in_array($actualMime, $allowedVideoMimes, true)) {
                        $fileSize = $value->getSize();

                        if ($fileSize > 1024) {
                            $isValidVideo = $this->validateVideoSignature($value->getPathname(), $actualMime);

                            if (! $isValidVideo) {
                                $fail('The file content does not match the expected video format.');
                            }
                        }
                    }
                },
            ],
            'title' => 'nullable|string|max:255',
            'media_type' => 'required|in:image,video,gif',
            'tags' => 'nullable|string|max:1000',
            'collections' => 'nullable|array',
            'collections.*' => 'exists:collections,id',
        ]);

        $file = $request->file('file');
        $mediaType = $validated['media_type'];

        $allowedImageMimes = ['image/jpeg', 'image/png', 'image/jpg', 'image/gif', 'image/webp'];
        $actualMime = $file->getMimeType();

        if (in_array($actualMime, $allowedImageMimes, true)) {
            $sanitizer = app(\App\Services\ImageSanitizer::class);
            $filePath = $sanitizer->sanitizeAndStore($file, 'media');
        } else {
            $filePath = $file->store('media', 'public');
        }

        $media = Media::create([
            'file_path' => $filePath,
            'file_name' => $file->getClientOriginalName(),
            'title' => $validated['title'] ?? null,
            'uploaded_by' => auth()->id(),
            'media_type' => $mediaType,
        ]);

        // Attach tags
        if (! empty($validated['tags'])) {
            $this->syncTags($media, $validated['tags']);
        }

        // Attach collections
        if (! empty($validated['collections'])) {
            $media->collections()->attach($validated['collections']);
        }

        Log::info('Media asset uploaded successfully.', [
            'media_id' => $media->id,
            'file_name' => $media->file_name,
            'media_type' => $media->media_type,
            'file_size' => $file->getSize(),
            'uploaded_by' => auth()->id(),
        ]);

        return back()->with('success', 'Media uploaded and optimized.');
    }

    public function update(UpdateMediaRequest $request, Media $media)
    {
        $validated = $request->validated();

        $media->update([
            'title' => $validated['title'] ?? null,
            'description' => $validated['description'] ?? null,
        ]);

        // Sync tags
        if (isset($validated['tags'])) {
            $this->syncTags($media, implode(',', $validated['tags']));
        }

        // Sync collections
        if (isset($validated['collections'])) {
            $media->collections()->sync($validated['collections']);
        }

        // Update project assignment
        if (array_key_exists('project_id', $validated)) {
            $media->update(['project_id' => $validated['project_id']]);
        }

        return redirect()->route('admin.media.index')
            ->with('success', 'Media updated.');
    }

    public function destroy(Media $media)
    {
        $this->authorize('delete', $media);

        $mediaId = $media->id;
        $mediaPath = $media->file_path;

        Storage::disk('public')->delete($media->file_path);

        $media->delete();

        Log::info('Media asset permanently deleted.', [
            'media_id' => $mediaId,
            'file_path' => $mediaPath,
            'deleted_by' => auth()->id(),
        ]);

        return back()->with('success', 'Media deleted.');
    }

    public function bulkAddToCollection(Request $request)
    {
        $this->authorize('update', Media::class);

        $validated = $request->validate([
            'collection_id' => 'required|exists:collections,id',
            'media_ids' => 'required|array',
            'media_ids.*' => 'exists:media,id',
        ]);

        $collection = Collection::findOrFail($validated['collection_id']);
        $this->authorize('update', $collection);

        $collection->media()->syncWithoutDetaching($validated['media_ids']);

        Log::info('Bulk media added to collection.', [
            'collection_id' => $collection->id,
            'media_count' => count($validated['media_ids']),
            'added_by' => auth()->id(),
        ]);

        return back()->with('success', count($validated['media_ids']).' item(s) added to '.$collection->name.'.');
    }

    private function validateVideoSignature(string $path, string $mimeType): bool
    {
        $handle = fopen($path, 'rb');

        if (! $handle) {
            return false;
        }

        $signature = fread($handle, 12);
        fclose($handle);

        $signatures = [
            'video/mp4' => [
                ["\x00\x00\x00\x18ftypmp4", "\x00\x00\x00\x1Cftypmp4", "\x00\x00\x00\x20ftypmp4", "\x00\x00\x00\x24ftypmp4"],
            ],
            'video/webm' => [
                ["\x1A\x45\xDF\xA3"],
            ],
            'video/mpeg' => [
                ["\x00\x00\x01\xBA", "\x00\x00\x01\xB3"],
            ],
        ];

        if (! isset($signatures[$mimeType])) {
            return false;
        }

        foreach ($signatures[$mimeType] as $patterns) {
            foreach ($patterns as $pattern) {
                if (str_starts_with($signature, $pattern)) {
                    return true;
                }
            }
        }

        return false;
    }

    private function syncTags(Media $media, string $tagString): void
    {
        $tagNames = array_filter(array_map('trim', explode(',', $tagString)));

        if (empty($tagNames)) {
            $media->tags()->sync([]);

            return;
        }

        $tagIds = [];

        foreach ($tagNames as $name) {
            $tag = Tag::firstOrCreate(
                ['name' => $name],
                ['slug' => Str::slug($name)]
            );
            $tagIds[] = $tag->id;
        }

        $media->tags()->sync($tagIds);
    }
}
