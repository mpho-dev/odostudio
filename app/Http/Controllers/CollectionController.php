<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCollectionRequest;
use App\Http\Requests\UpdateCollectionRequest;
use App\Models\Collection;
use App\Models\Media;
use Illuminate\Support\Facades\Log;

class CollectionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $this->authorize('viewAny', Collection::class);

        $collections = Collection::withCount('media')
            ->with('user')
            ->orderBy('name')
            ->get();

        $media = Media::orderByDesc('created_at')->get();

        return view('admin.collections.index', compact('collections', 'media'));
    }

    public function store(StoreCollectionRequest $request)
    {
        $this->authorize('create', Collection::class);

        $collection = Collection::create([
            'name' => $request->validated('name'),
            'description' => $request->validated('description'),
            'color' => $request->validated('color'),
            'user_id' => auth()->id(),
        ]);

        $mediaIds = $request->validated('media_ids', []);
        if (! empty($mediaIds)) {
            $collection->media()->attach($mediaIds);
        }

        Log::info('Collection created.', [
            'collection_id' => $collection->id,
            'name' => $collection->name,
            'created_by' => auth()->id(),
        ]);

        return redirect()->route('admin.collections.index')
            ->with('success', 'Collection created.');
    }

    public function edit(Collection $collection)
    {
        $this->authorize('update', $collection);

        $media = Media::orderByDesc('created_at')->get();

        return view('admin.collections.edit', compact('collection', 'media'));
    }

    public function update(UpdateCollectionRequest $request, Collection $collection)
    {
        $this->authorize('update', $collection);

        $collection->update($request->only(['name', 'description', 'color']));

        $mediaIds = $request->validated('media_ids', []);
        $collection->media()->sync($mediaIds);

        Log::info('Collection updated.', [
            'collection_id' => $collection->id,
            'name' => $collection->name,
            'updated_by' => auth()->id(),
        ]);

        return redirect()->route('admin.collections.index')
            ->with('success', 'Collection updated.');
    }

    public function destroy(Collection $collection)
    {
        $this->authorize('delete', $collection);

        $collectionId = $collection->id;
        $collection->delete();

        Log::info('Collection deleted.', [
            'collection_id' => $collectionId,
            'deleted_by' => auth()->id(),
        ]);

        return redirect()->route('admin.collections.index')
            ->with('success', 'Collection deleted.');
    }
}
