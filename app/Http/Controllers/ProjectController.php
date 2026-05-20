<?php

namespace App\Http\Controllers;

use App\Models\Collection;
use App\Models\Media;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProjectController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:admin']);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $projects = Project::with('hero')->orderBy('order')->get();

        return view('admin.projects.index', compact('projects'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $mediaItems = Media::latest()->get();
        $collections = Collection::with('media:id')->orderBy('name')->get();

        return view('admin.projects.create', compact('mediaItems', 'collections'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:projects,slug',
            'client' => 'nullable|string|max:255',
            'location' => 'nullable|string|max:255',
            'description' => 'required|string',
            'hero_media_id' => 'nullable|exists:media,id',
            'category' => 'nullable|string|max:100',
            'is_featured' => 'nullable|boolean',
            'order' => 'nullable|integer',
            'selected_media' => 'nullable|array',
            'selected_media.*' => 'exists:media,id',
        ]);

        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['title']);
        }

        $project = Project::create($validated);

        if (isset($request->selected_media)) {
            Media::whereIn('id', $request->selected_media)->update(['project_id' => $project->id]);
        }

        return redirect()->route('projects.index')->with('success', 'Project created successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Project $project)
    {
        $mediaItems = Media::latest()->get();
        $collections = Collection::with('media:id')->orderBy('name')->get();

        return view('admin.projects.edit', compact('project', 'mediaItems', 'collections'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Project $project)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:projects,slug,'.$project->id,
            'client' => 'nullable|string|max:255',
            'location' => 'nullable|string|max:255',
            'description' => 'required|string',
            'hero_media_id' => 'nullable|exists:media,id',
            'category' => 'nullable|string|max:100',
            'is_featured' => 'nullable|boolean',
            'order' => 'nullable|integer',
            'selected_media' => 'nullable|array',
            'selected_media.*' => 'exists:media,id',
        ]);

        $project->update($validated);

        // Reset previous assignments
        Media::where('project_id', $project->id)->update(['project_id' => null]);

        // Assign new media
        if (isset($request->selected_media)) {
            Media::whereIn('id', $request->selected_media)->update(['project_id' => $project->id]);
        }

        return redirect()->route('projects.index')->with('success', 'Project updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Project $project)
    {
        $project->delete();

        return redirect()->route('projects.index')->with('success', 'Project deleted.');
    }
}
