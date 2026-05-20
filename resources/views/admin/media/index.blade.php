@extends('layouts.app')

@section('header', 'Asset Library')

@section('content')
<div class="space-y-12 pb-12">
    <!-- Header -->
    <div class="flex flex-col md:flex-row justify-between items-end gap-6 reveal">
        <div>
            <div class="flex items-center gap-4 text-[0.62rem] tracking-[0.4em] uppercase text-gold mb-4">
                <span>Digital Assets</span>
                <div class="h-px w-16 bg-gold/30"></div>
            </div>
            <h1 class="font-serif text-4xl text-charcoal dark:text-white leading-tight">Asset <em>Library</em></h1>
            <p class="text-xs tracking-[0.3em] uppercase text-ash/60 dark:text-ash/60 mt-2 italic">Centralized repository for visual deliverables</p>
        </div>
        <div class="flex gap-3">
            <label class="flex items-center gap-2 cursor-pointer">
                <input type="checkbox" id="selectAllMedia" onchange="toggleSelectAll(this)" class="w-4 h-4 rounded border-charcoal/10 dark:border-white/10 text-gold focus:ring-gold focus:ring-offset-black bg-charcoal/5 dark:bg-black/50" />
                <span class="text-[0.6rem] tracking-[0.2em] uppercase text-silver">Select All</span>
            </label>
            <a href="{{ route('admin.collections.index') }}" class="group relative px-6 py-4 border border-charcoal/10 dark:border-white/10 text-[0.65rem] tracking-[0.3em] uppercase font-bold text-ash hover:border-gold hover:text-gold transition-all duration-300">
                Collections
            </a>
            <button onclick="document.getElementById('uploadModal').classList.remove('hidden')" class="group relative px-8 py-4 bg-gold text-black text-[0.65rem] tracking-[0.3em] uppercase font-bold hover:bg-white transition-all duration-500 overflow-hidden">
                <span class="relative z-10">+ Ingest Media</span>
                <div class="absolute inset-0 bg-white translate-x-[-100%] group-hover:translate-x-0 transition-transform duration-500"></div>
            </button>
        </div>
    </div>

    <div class="flex gap-8">
        <!-- Sidebar -->
        <aside class="w-64 shrink-0 space-y-8 animate-hero-in">
            <!-- Search -->
            <div>
                <form action="{{ route('admin.media.index') }}" method="GET">
                    @if($selectedCollection)
                    <input type="hidden" name="collection" value="{{ $selectedCollection }}" />
                    @endif
                    @if($selectedTag)
                    <input type="hidden" name="tag" value="{{ $selectedTag }}" />
                    @endif
                    @if($selectedType)
                    <input type="hidden" name="media_type" value="{{ $selectedType }}" />
                    @endif
                    <input type="text" name="search" value="{{ $search }}" placeholder="Search assets..." class="w-full bg-charcoal/5 dark:bg-black/50 border border-charcoal/10 dark:border-white/10 p-3 text-sm text-charcoal dark:text-white focus:border-gold outline-none transition-all duration-300 placeholder:text-silver/40" />
                    @if($search)
                    <a href="{{ route('admin.media.index') }}" class="mt-2 inline-block text-[0.6rem] tracking-[0.2em] uppercase text-gold hover:underline">Clear search</a>
                    @endif
                </form>
            </div>

            <!-- Media Type Filter -->
            <div>
                <p class="text-[0.62rem] tracking-[0.4em] uppercase text-silver/50 mb-3">Media Type</p>
                <div class="space-y-2">
                    <a href="{{ route('admin.media.index', array_filter(['collection' => $selectedCollection, 'tag' => $selectedTag])) }}" class="block text-sm text-ash hover:text-gold transition-colors {{ !$selectedType ? 'text-gold' : '' }}">
                        All Types
                    </a>
                    <a href="{{ route('admin.media.index', array_filter(array_merge(request()->all(), ['media_type' => 'image']))) }}" class="block text-sm text-ash hover:text-gold transition-colors {{ $selectedType === 'image' ? 'text-gold' : '' }}">
                        Stills
                    </a>
                    <a href="{{ route('admin.media.index', array_filter(array_merge(request()->all(), ['media_type' => 'video']))) }}" class="block text-sm text-ash hover:text-gold transition-colors {{ $selectedType === 'video' ? 'text-gold' : '' }}">
                        Video
                    </a>
                    <a href="{{ route('admin.media.index', array_filter(array_merge(request()->all(), ['media_type' => 'gif']))) }}" class="block text-sm text-ash hover:text-gold transition-colors {{ $selectedType === 'gif' ? 'text-gold' : '' }}">
                        Motion Graphics
                    </a>
                </div>
            </div>

            <!-- Collections -->
            <div>
                <div class="flex items-center justify-between mb-3">
                    <p class="text-[0.62rem] tracking-[0.4em] uppercase text-silver/50">Collections</p>
                    <a href="{{ route('admin.collections.index') }}" class="text-[0.55rem] tracking-[0.2em] uppercase text-gold/60 hover:text-gold transition-colors">Manage</a>
                </div>
                <div class="space-y-1">
                    <a href="{{ route('admin.media.index', array_filter(['tag' => $selectedTag, 'media_type' => $selectedType])) }}" class="flex items-center justify-between text-sm text-ash hover:text-gold transition-colors py-1 {{ !$selectedCollection ? 'text-gold' : '' }}">
                        <span>All Media</span>
                    </a>
                    @forelse($collections as $collection)
                    <a href="{{ route('admin.media.index', array_filter(array_merge(request()->all(), ['collection' => $collection->id]))) }}" class="flex items-center justify-between text-sm text-ash hover:text-gold transition-colors py-1 {{ $selectedCollection == $collection->id ? 'text-gold' : '' }}">
                        <span class="flex items-center gap-2">
                            @if($collection->color)
                            <span class="w-2.5 h-2.5 rounded-full shrink-0" style="background-color: {{ $collection->color }}"></span>
                            @else
                            <span class="w-2.5 h-2.5 rounded-full shrink-0 bg-gold/30"></span>
                            @endif
                            <span class="truncate">{{ $collection->name }}</span>
                        </span>
                        <span class="text-[0.6rem] text-silver/40">{{ $collection->media_count }}</span>
                    </a>
                    @empty
                    <p class="text-xs text-silver/30 italic">No collections yet</p>
                    @endforelse
                </div>
            </div>

            <!-- Tags -->
            <div>
                <p class="text-[0.62rem] tracking-[0.4em] uppercase text-silver/50 mb-3">Tags</p>
                <div class="flex flex-wrap gap-2">
                    <a href="{{ route('admin.media.index', array_filter(['collection' => $selectedCollection, 'media_type' => $selectedType])) }}" class="inline-block px-2.5 py-1 text-[0.55rem] tracking-[0.15em] uppercase border border-charcoal/10 dark:border-white/10 text-silver hover:border-gold hover:text-gold transition-colors {{ !$selectedTag ? 'border-gold/30 text-gold' : '' }}">
                        All
                    </a>
                    @forelse($tags as $tag)
                    <a href="{{ route('admin.media.index', array_filter(array_merge(request()->all(), ['tag' => $tag->id]))) }}" class="inline-block px-2.5 py-1 text-[0.55rem] tracking-[0.15em] uppercase border border-charcoal/10 dark:border-white/10 text-silver hover:border-gold hover:text-gold transition-colors {{ $selectedTag == $tag->id ? 'border-gold/30 text-gold' : '' }}">
                        {{ $tag->name }}
                        <span class="text-silver/40 ml-1">{{ $tag->media_count }}</span>
                    </a>
                    @empty
                    <p class="text-xs text-silver/30 italic">No tags yet</p>
                    @endforelse
                </div>
            </div>
        </aside>

        <!-- Main Content -->
        <div class="flex-1 min-w-0">
            @if($media->isEmpty())
            <div class="py-32 text-center glass rounded-xl border border-dashed border-charcoal/20 dark:border-white/10 reveal shadow-2xl relative overflow-hidden group">
                <div class="absolute top-0 left-0 w-8 h-8 border-t border-l border-gold/40 group-hover:w-12 group-hover:h-12 transition-all duration-500"></div>
                <div class="absolute bottom-0 right-0 w-8 h-8 border-b border-r border-gold/40 group-hover:w-12 group-hover:h-12 transition-all duration-500"></div>
                <p class="font-serif text-xl text-ash/60 dark:text-ash/40 italic opacity-40">No media found.</p>
                @if($search || $selectedCollection || $selectedTag || $selectedType)
                <a href="{{ route('admin.media.index') }}" class="mt-4 inline-block text-[0.6rem] tracking-widest uppercase text-gold hover:underline">Clear all filters</a>
                @else
                <button onclick="document.getElementById('uploadModal').classList.remove('hidden')" class="mt-4 inline-block text-[0.6rem] tracking-widest uppercase text-gold hover:underline">Ingest your first media asset</button>
                @endif
            </div>
            @else
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6 reveal">
                @foreach($media as $item)
                <div class="group relative aspect-square glass rounded-xl overflow-hidden border border-charcoal/10 dark:border-white/10 transition-all duration-500 hover:border-gold/30 hover:shadow-2xl hover:shadow-gold/5">
                    {{-- Bulk Select Checkbox --}}
                    <label class="absolute top-3 left-3 z-20 cursor-pointer opacity-0 group-hover:opacity-100 transition-opacity media-select-label">
                        <input type="checkbox" name="bulk_media_ids[]" value="{{ $item->id }}" class="media-checkbox sr-only peer" onchange="updateBulkSelection()" />
                        <div class="w-6 h-6 rounded border-2 border-white/50 bg-black/30 peer-checked:bg-gold peer-checked:border-gold transition-all flex items-center justify-center">
                            <svg class="w-4 h-4 text-black opacity-0 peer-checked:opacity-100" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
                            </svg>
                        </div>
                    </label>
                    @if(Storage::disk('public')->exists($item->file_path))
                    @if($item->media_type === 'image')
                    <img src="{{ media_url($item->file_path) }}" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105" alt="{{ $item->title }}" onerror="this.style.display='none'; this.nextElementSibling.style.display='grid';">
                    <div class="w-full h-full hidden bg-charcoal/50 grid place-items-center">
                        <svg class="w-10 h-10 text-ash/20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    @elseif($item->media_type === 'video')
                    <video src="{{ media_url($item->file_path) }}" class="w-full h-full object-cover" onerror="this.style.display='none'; this.nextElementSibling.style.display='grid';"></video>
                    <div class="w-full h-full hidden bg-charcoal/50 grid place-items-center">
                        <svg class="w-10 h-10 text-ash/20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <div class="absolute inset-0 flex items-center justify-center bg-black/20 group-hover:bg-black/40 transition-colors">
                        <svg class="w-12 h-12 text-white opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    @else
                    <img src="{{ media_url($item->file_path) }}" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105" alt="{{ $item->title }}" onerror="this.style.display='none'; this.nextElementSibling.style.display='grid';">
                    <div class="w-full h-full hidden bg-charcoal/50 grid place-items-center">
                        <svg class="w-10 h-10 text-ash/20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    @endif
                    @else
                    <div class="w-full h-full bg-charcoal/50 grid place-items-center">
                        <svg class="w-10 h-10 text-ash/20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    @endif

                    <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex flex-col justify-end p-6">
                        <p class="text-white font-serif text-lg leading-none mb-1">{{ $item->title ?? $item->file_name }}</p>
                        <p class="text-[0.5rem] tracking-[0.2em] uppercase text-gold mb-2">{{ $item->media_type }}</p>

                        @if($item->tags->isNotEmpty())
                        <div class="flex flex-wrap gap-1 mb-3">
                            @foreach($item->tags->take(3) as $tag)
                            <span class="px-1.5 py-0.5 text-[0.45rem] tracking-[0.15em] uppercase bg-gold/20 text-gold rounded">{{ $tag->name }}</span>
                            @endforeach
                            @if($item->tags->count() > 3)
                            <span class="px-1.5 py-0.5 text-[0.45rem] tracking-[0.15em] uppercase bg-white/10 text-silver rounded">+{{ $item->tags->count() - 3 }}</span>
                            @endif
                        </div>
                        @endif

                        <div class="flex gap-3">
                            <button type="button" onclick="document.getElementById('edit-media-{{ $item->id }}').classList.remove('hidden')" class="text-ash/60 hover:text-gold text-[0.55rem] tracking-[0.2em] uppercase transition-colors">
                                Edit
                            </button>
                            <button type="button" onclick="document.getElementById('delete-media-{{ $item->id }}').classList.remove('hidden')" class="text-red-400 hover:text-red-300 text-[0.55rem] tracking-[0.2em] uppercase transition-colors">
                                Delete
                            </button>
                        </div>
                    </div>
                </div>

                {{-- Edit Media Modal --}}
                <div id="edit-media-{{ $item->id }}" class="fixed inset-0 z-50 hidden" role="dialog" aria-modal="true">
                    <div class="absolute inset-0 bg-black/80 backdrop-blur-sm" onclick="document.getElementById('edit-media-{{ $item->id }}').classList.add('hidden')"></div>
                    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-full max-w-lg p-4">
                        <div class="glass border border-white/10 rounded-xl p-8 shadow-2xl">
                            <div class="flex items-center gap-4 text-[0.62rem] tracking-[0.4em] uppercase text-gold mb-4">
                                <span>Edit Asset</span>
                                <div class="h-px w-16 bg-gold/30"></div>
                            </div>

                            <form action="{{ route('media.update', $item) }}" method="POST">
                                @csrf
                                @method('PATCH')

                                <div class="mb-5">
                                    <label class="block text-[0.62rem] tracking-[0.3em] uppercase text-ash/60 dark:text-silver mb-2">Title</label>
                                    <input type="text" name="title" value="{{ old('title', $item->title) }}" class="w-full bg-charcoal/5 dark:bg-black/50 border border-charcoal/10 dark:border-white/10 p-3.5 text-sm text-charcoal dark:text-white focus:border-gold outline-none transition-all duration-300" />
                                </div>

                                <div class="mb-5">
                                    <label class="block text-[0.62rem] tracking-[0.3em] uppercase text-ash/60 dark:text-silver mb-2">Description</label>
                                    <textarea name="description" rows="3" class="w-full bg-charcoal/5 dark:bg-black/50 border border-charcoal/10 dark:border-white/10 p-3.5 text-sm text-charcoal dark:text-white focus:border-gold outline-none transition-all duration-300">{{ old('description', $item->description) }}</textarea>
                                </div>

                                <div class="mb-5">
                                    <label class="block text-[0.62rem] tracking-[0.3em] uppercase text-ash/60 dark:text-silver mb-2">Tags</label>
                                    <input type="text" name="tags" value="{{ $item->tags->pluck('name')->join(', ') }}" placeholder="wedding, couple, reception" class="w-full bg-charcoal/5 dark:bg-black/50 border border-charcoal/10 dark:border-white/10 p-3.5 text-sm text-charcoal dark:text-white focus:border-gold outline-none transition-all duration-300" />
                                    <p class="mt-1.5 text-xs text-silver/40">Separate tags with commas</p>
                                </div>

                                <div class="mb-8">
                                    <label class="block text-[0.62rem] tracking-[0.3em] uppercase text-ash/60 dark:text-silver mb-2">Collections</label>
                                    <div class="space-y-2 max-h-40 overflow-y-auto">
                                        @forelse($collections as $collection)
                                        <label class="flex items-center gap-3 cursor-pointer">
                                            <input type="checkbox" name="collections[]" value="{{ $collection->id }}" {{ $item->collections->contains($collection->id) ? 'checked' : '' }} class="w-4 h-4 rounded border-charcoal/10 dark:border-white/10 text-gold focus:ring-gold focus:ring-offset-black bg-charcoal/5 dark:bg-black/50" />
                                            <span class="flex items-center gap-2 text-sm text-ash">
                                                @if($collection->color)
                                                <span class="w-2.5 h-2.5 rounded-full" style="background-color: {{ $collection->color }}"></span>
                                                @endif
                                                {{ $collection->name }}
                                            </span>
                                        </label>
                                        @empty
                                        <p class="text-xs text-silver/30 italic">No collections available</p>
                                        @endforelse
                                    </div>
                                </div>

                                <div class="flex justify-end gap-4">
                                    <button type="button" onclick="document.getElementById('edit-media-{{ $item->id }}').classList.add('hidden')" class="px-6 py-3 text-[0.6rem] tracking-[0.3em] uppercase font-bold text-ash/60 hover:text-charcoal dark:hover:text-white transition-colors">
                                        Cancel
                                    </button>
                                    <button type="submit" class="group relative px-8 py-3 bg-gold text-black text-[0.6rem] tracking-[0.3em] uppercase font-bold hover:bg-white transition-all duration-500 overflow-hidden">
                                        <span class="relative z-10">Save Changes</span>
                                        <div class="absolute inset-0 bg-white translate-x-[-100%] group-hover:translate-x-0 transition-transform duration-500"></div>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <div class="pagination-container mt-8 reveal">
                {{ $media->links() }}
            </div>
            @endif
        </div>
    </div>
</div>

@foreach($media as $item)
<x-confirm-modal
    id="delete-media-{{ $item->id }}"
    title="Delete Asset"
    message="Permanently delete this asset from the library? This action cannot be undone."
    confirm-label="Delete Asset"
    :route="route('media.destroy', $item)"
    method="DELETE"
    variant="danger" />
@endforeach

{{-- Bulk Action Bar --}}
<div id="bulkActionBar" class="fixed bottom-6 left-1/2 -translate-x-1/2 z-40 hidden">
    <div class="glass flex items-center gap-4 bg-charcoal/95 backdrop-blur-md border border-gold/30 rounded-full px-6 py-3 shadow-2xl shadow-gold/10">
        <span class="text-[0.65rem] tracking-[0.2em] uppercase text-gold">
            <span id="bulkSelectedCount">0</span> selected
        </span>
        <div class="h-4 w-px bg-white/10"></div>
        <button type="button" onclick="document.getElementById('bulkCollectionModal').classList.remove('hidden')" class="flex items-center gap-2 text-[0.6rem] tracking-[0.15em] uppercase text-silver hover:text-gold transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
            </svg>
            Add to Collection
        </button>
        <button type="button" onclick="clearBulkSelection()" class="text-[0.6rem] tracking-[0.15em] uppercase text-silver/40 hover:text-white transition-colors">
            Clear
        </button>
    </div>
</div>

{{-- Bulk Add to Collection Modal --}}
<div id="bulkCollectionModal" class="fixed inset-0 z-50 hidden" role="dialog" aria-modal="true">
    <div class="absolute inset-0 bg-black/80 backdrop-blur-sm" onclick="document.getElementById('bulkCollectionModal').classList.add('hidden')"></div>
    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-full max-w-md p-4">
        <div class="glass border border-white/10 rounded-xl p-8 shadow-2xl">
            <div class="flex items-center gap-4 text-[0.62rem] tracking-[0.4em] uppercase text-gold mb-4">
                <span>Add to Collection</span>
                <div class="h-px w-16 bg-gold/30"></div>
            </div>
            <p class="text-sm text-ash/70 mb-6">Select a collection to add the <span id="bulkModalCount">0</span> selected items to.</p>

            <form id="bulkAddToCollectionForm" method="POST">
                @csrf
                <div class="mb-6 max-h-64 overflow-y-auto space-y-2">
                    @forelse($collections as $collection)
                    <label class="flex items-center gap-3 cursor-pointer p-3 rounded-lg hover:bg-white/5 transition-colors">
                        <input type="radio" name="collection_id" value="{{ $collection->id }}" required class="w-4 h-4 border-charcoal/10 dark:border-white/10 text-gold focus:ring-gold focus:ring-offset-black" />
                        <span class="flex items-center gap-2 text-sm text-ash">
                            @if($collection->color)
                            <span class="w-2.5 h-2.5 rounded-full shrink-0" style="background-color: {{ $collection->color }}"></span>
                            @else
                            <span class="w-2.5 h-2.5 rounded-full shrink-0 bg-gold/30"></span>
                            @endif
                            {{ $collection->name }}
                        </span>
                    </label>
                    @empty
                    <p class="text-sm text-silver/40 italic p-3">No collections available. <a href="{{ route('admin.collections.index') }}" class="text-gold hover:underline">Create one</a></p>
                    @endforelse
                </div>

                <div class="flex justify-end gap-4">
                    <button type="button" onclick="document.getElementById('bulkCollectionModal').classList.add('hidden')" class="px-6 py-3 text-[0.6rem] tracking-[0.3em] uppercase font-bold text-ash/60 hover:text-white transition-colors">
                        Cancel
                    </button>
                    <button type="submit" class="group relative px-8 py-3 bg-gold text-black text-[0.6rem] tracking-[0.3em] uppercase font-bold hover:bg-white transition-all duration-500 overflow-hidden">
                        <span class="relative z-10">Add to Collection</span>
                        <div class="absolute inset-0 bg-white translate-x-[-100%] group-hover:translate-x-0 transition-transform duration-500"></div>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Upload Modal -->
<div id="uploadModal" class="fixed inset-0 z-50 hidden" role="dialog" aria-modal="true">
    <div class="absolute inset-0 bg-black/80 backdrop-blur-sm transition-opacity" onclick="document.getElementById('uploadModal').classList.add('hidden')"></div>

    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-full max-w-lg p-4">
        <div class="glass bg-charcoal dark:bg-charcoal border border-charcoal/20 dark:border-white/10 rounded-xl p-8 shadow-2xl">
            <div class="flex items-center gap-4 text-[0.62rem] tracking-[0.4em] uppercase text-gold mb-4">
                <span>Media Ingestion</span>
                <div class="h-px w-16 bg-gold/30"></div>
            </div>
            <h2 class="font-serif text-2xl text-charcoal dark:text-white mb-2">Ingest Media</h2>
            <p class="text-[0.6rem] tracking-[0.2em] uppercase text-ash/60 dark:text-ash/40 mb-8 italic">Upload raw or processed content to the library</p>

            <form action="{{ route('media.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf

                <div>
                    <label class="block text-[0.6rem] tracking-[0.3em] uppercase text-gold font-bold mb-2">Asset File</label>
                    <input type="file" name="file" class="block w-full text-sm text-ash/60 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-[0.6rem] file:tracking-[0.2em] file:uppercase file:font-bold file:bg-gold/10 file:text-gold hover:file:bg-gold/20 transition-all" required>
                </div>

                <div>
                    <label class="block text-[0.6rem] tracking-[0.3em] uppercase text-gold font-bold mb-2">Slug / Working Title</label>
                    <input type="text" name="title" class="w-full bg-charcoal/5 dark:bg-white/5 border border-charcoal/10 dark:border-white/10 rounded p-3 text-charcoal dark:text-silver focus:border-gold outline-none transition-colors" placeholder="e.g. Campaign_Hero_Shot_01">
                </div>

                <div>
                    <label class="block text-[0.6rem] tracking-[0.3em] uppercase text-gold font-bold mb-2">Media Format</label>
                    <select name="media_type" class="w-full bg-charcoal/5 dark:bg-white/5 border border-charcoal/10 dark:border-white/10 rounded p-3 text-charcoal dark:text-silver focus:border-gold outline-none transition-colors">
                        <option value="image">Stills</option>
                        <option value="video">Video</option>
                        <option value="gif">Motion Graphics</option>
                    </select>
                </div>

                <div>
                    <label class="block text-[0.6rem] tracking-[0.3em] uppercase text-gold font-bold mb-2">Tags</label>
                    <input type="text" name="tags" class="w-full bg-charcoal/5 dark:bg-white/5 border border-charcoal/10 dark:border-white/10 rounded p-3 text-charcoal dark:text-silver focus:border-gold outline-none transition-colors" placeholder="wedding, couple, reception">
                    <p class="mt-1.5 text-xs text-silver/40">Separate tags with commas</p>
                </div>

                <div>
                    <label class="block text-[0.6rem] tracking-[0.3em] uppercase text-gold font-bold mb-2">Add to Collections</label>
                    <div class="space-y-2 max-h-32 overflow-y-auto">
                        @forelse($collections as $collection)
                        <label class="flex items-center gap-3 cursor-pointer">
                            <input type="checkbox" name="collections[]" value="{{ $collection->id }}" class="w-4 h-4 rounded border-charcoal/10 dark:border-white/10 text-gold focus:ring-gold focus:ring-offset-black bg-charcoal/5 dark:bg-black/50" />
                            <span class="flex items-center gap-2 text-sm text-ash">
                                @if($collection->color)
                                <span class="w-2.5 h-2.5 rounded-full" style="background-color: {{ $collection->color }}"></span>
                                @endif
                                {{ $collection->name }}
                            </span>
                        </label>
                        @empty
                        <p class="text-xs text-silver/30 italic">No collections yet. <a href="{{ route('admin.collections.index') }}" class="text-gold hover:underline">Create one</a></p>
                        @endforelse
                    </div>
                </div>

                <div class="flex justify-end gap-4 pt-4">
                    <button type="button" onclick="document.getElementById('uploadModal').classList.add('hidden')" class="px-6 py-3 text-[0.6rem] tracking-[0.3em] uppercase font-bold text-ash/60 hover:text-charcoal dark:hover:text-white transition-colors">Cancel</button>
                    <button type="submit" id="uploadBtn" class="group relative px-8 py-3 bg-gold text-charcoal text-[0.6rem] tracking-[0.3em] uppercase font-bold hover:bg-white transition-colors overflow-hidden">
                        <span class="relative z-10">Ingest to Library</span>
                        <div class="absolute inset-0 bg-white translate-x-[-100%] group-hover:translate-x-0 transition-transform duration-500"></div>
                    </button>
                </div>
            </form>

            <!-- Optimization Progress Overlay -->
            <div id="optimizationOverlay" class="hidden mt-6 p-4 bg-gold/5 border border-gold/20 rounded-lg">
                <div class="flex items-center justify-between mb-2">
                    <p id="optimizationLabel" class="text-[0.6rem] tracking-[0.3em] uppercase text-gold font-bold">Optimizing for Web...</p>
                    <p id="optimizationProgress" class="text-[0.6rem] text-gold">0%</p>
                </div>
                <div class="w-full bg-charcoal/20 rounded-full h-1 overflow-hidden">
                    <div id="optimizationBar" class="bg-gold h-full transition-all duration-300" style="width: 0%"></div>
                </div>
                <p id="optimizationNote" class="text-[0.55rem] text-ash/60 mt-2 italic">Client-side transcoding active. Please keep this tab open.</p>
            </div>
        </div>
    </div>
</div>

<!-- Optimization Confirm Modal -->
<div id="optimizationConfirmModal" class="fixed inset-0 z-50 hidden" role="dialog" aria-modal="true">
    <div class="absolute inset-0 bg-black/80 backdrop-blur-sm transition-opacity" onclick="closeOptimizationConfirm(false)"></div>
    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-full max-w-md p-4">
        <div class="glass bg-charcoal dark:bg-charcoal border border-charcoal/20 dark:border-white/10 rounded-xl p-8 shadow-2xl">
            <div class="flex items-center gap-4 text-[0.62rem] tracking-[0.4em] uppercase text-gold mb-4">
                <span>Video Optimization</span>
                <div class="h-px w-16 bg-gold/30"></div>
            </div>
            <h2 class="font-serif text-2xl text-charcoal dark:text-white mb-3">Optimize for Web?</h2>
            <p id="optimizationConfirmMessage" class="text-sm text-ash/70 dark:text-ash/60 mb-8 leading-relaxed"></p>
            <div class="flex justify-end gap-4">
                <button type="button" onclick="closeOptimizationConfirm(false)" class="px-6 py-3 text-[0.6rem] tracking-[0.3em] uppercase font-bold text-ash/60 hover:text-charcoal dark:hover:text-white transition-colors">
                    Skip
                </button>
                <button type="button" onclick="closeOptimizationConfirm(true)" class="group relative px-8 py-3 bg-gold text-charcoal text-[0.6rem] tracking-[0.3em] uppercase font-bold hover:bg-white transition-colors overflow-hidden">
                    <span class="relative z-10">Optimize</span>
                    <div class="absolute inset-0 bg-white translate-x-[-100%] group-hover:translate-x-0 transition-transform duration-500"></div>
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Optimization Failure Alert -->
<x-alert-modal
    id="optimizationFailedAlert"
    title="Optimization Failed"
    message="Client-side optimization failed. Uploading original file instead."
    type="error" />

@push('scripts')
<script>
    // --- Bulk Selection Functions ---
    function toggleSelectAll(checkbox) {
        const checkboxes = document.querySelectorAll('.media-checkbox');
        checkboxes.forEach(cb => {
            cb.checked = checkbox.checked;
        });
        updateBulkSelection();
    }

    function updateBulkSelection() {
        const checkboxes = document.querySelectorAll('.media-checkbox:checked');
        const count = checkboxes.length;
        const countEl = document.getElementById('bulkSelectedCount');
        const modalCountEl = document.getElementById('bulkModalCount');
        const actionBar = document.getElementById('bulkActionBar');
        const selectAllCheckbox = document.getElementById('selectAllMedia');

        countEl.textContent = count;
        modalCountEl.textContent = count;

        if (count > 0) {
            actionBar.classList.remove('hidden');
        } else {
            actionBar.classList.add('hidden');
        }

        // Update select all checkbox state
        const totalCheckboxes = document.querySelectorAll('.media-checkbox').length;
        const checkedCheckboxes = document.querySelectorAll('.media-checkbox:checked').length;

        if (selectAllCheckbox) {
            selectAllCheckbox.checked = checkedCheckboxes === totalCheckboxes && totalCheckboxes > 0;
            selectAllCheckbox.indeterminate = checkedCheckboxes > 0 && checkedCheckboxes < totalCheckboxes;
        }
    }

    function clearBulkSelection() {
        const checkboxes = document.querySelectorAll('.media-checkbox');
        checkboxes.forEach(cb => {
            cb.checked = false;
        });
        const selectAllCheckbox = document.getElementById('selectAllMedia');
        if (selectAllCheckbox) {
            selectAllCheckbox.checked = false;
            selectAllCheckbox.indeterminate = false;
        }
        updateBulkSelection();
    }

    // Handle bulk add to collection form submission
    document.getElementById('bulkAddToCollectionForm').addEventListener('submit', function(e) {
        e.preventDefault();

        const selectedIds = Array.from(document.querySelectorAll('.media-checkbox:checked')).map(cb => cb.value);

        if (selectedIds.length === 0) {
            alert('Please select at least one media item.');
            return;
        }

        const form = document.createElement('form');
        form.method = 'POST';
        form.action = "{{ route('media.bulk-add-to-collection') }}";

        // Add CSRF token
        const csrfInput = document.createElement('input');
        csrfInput.type = 'hidden';
        csrfInput.name = '_token';
        csrfInput.value = '{{ csrf_token() }}';
        form.appendChild(csrfInput);

        // Add collection ID
        const collectionInput = document.createElement('input');
        collectionInput.type = 'hidden';
        collectionInput.name = 'collection_id';
        collectionInput.value = this.querySelector('input[name="collection_id"]:checked').value;
        form.appendChild(collectionInput);

        // Add media IDs
        selectedIds.forEach(id => {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'media_ids[]';
            input.value = id;
            form.appendChild(input);
        });

        document.body.appendChild(form);
        form.submit();
    });

    // --- Image → WebP conversion via Canvas API (no dependencies) ---
    function convertImageToWebP(file, quality, maxDim) {
        return new Promise(function(resolve, reject) {
            var img = new Image();
            var url = URL.createObjectURL(file);

            img.onload = function() {
                URL.revokeObjectURL(url);

                var width = img.naturalWidth;
                var height = img.naturalHeight;

                if (width > maxDim || height > maxDim) {
                    var ratio = Math.min(maxDim / width, maxDim / height);
                    width = Math.round(width * ratio);
                    height = Math.round(height * ratio);
                }

                var canvas = document.createElement('canvas');
                canvas.width = width;
                canvas.height = height;

                var ctx = canvas.getContext('2d');
                ctx.drawImage(img, 0, 0, width, height);

                canvas.toBlob(
                    function(blob) {
                        if (!blob) {
                            reject(new Error('Canvas toBlob failed'));
                            return;
                        }

                        var newName = file.name.replace(/\.[^.]+$/, '') + '.webp';
                        var webpFile = new File([blob], newName, {
                            type: 'image/webp'
                        });

                        resolve({
                            file: webpFile,
                            originalSize: file.size,
                            newSize: blob.size
                        });
                    },
                    'image/webp',
                    quality
                );
            };

            img.onerror = function() {
                URL.revokeObjectURL(url);
                reject(new Error('Failed to load image'));
            };

            img.src = url;
        });
    }

    // --- UI helpers ---
    var overlay = document.getElementById('optimizationOverlay');
    var progressText = document.getElementById('optimizationProgress');
    var progressBar = document.getElementById('optimizationBar');
    var progressLabel = document.getElementById('optimizationLabel');
    var progressNote = document.getElementById('optimizationNote');
    var uploadBtn = document.getElementById('uploadBtn');

    function showProgress(label, note) {
        progressLabel.innerText = label;
        progressNote.innerText = note;
        progressText.innerText = '0%';
        progressBar.style.width = '0%';
        overlay.classList.remove('hidden');
    }

    function setProgress(percent) {
        progressText.innerText = percent + '%';
        progressBar.style.width = percent + '%';
    }

    function hideProgress() {
        overlay.classList.add('hidden');
    }

    function replaceFileInput(fileInput, newFile) {
        var dataTransfer = new DataTransfer();
        dataTransfer.items.add(newFile);
        fileInput.files = dataTransfer.files;
    }

    // --- Confirm modal helpers ---
    var optimizationResolve = null;

    window.showOptimizationConfirm = function(message) {
        document.getElementById('optimizationConfirmMessage').innerText = message;
        document.getElementById('optimizationConfirmModal').classList.remove('hidden');
        return new Promise(function(resolve) {
            optimizationResolve = resolve;
        });
    };

    window.closeOptimizationConfirm = function(confirmed) {
        document.getElementById('optimizationConfirmModal').classList.add('hidden');
        if (optimizationResolve) {
            optimizationResolve(confirmed);
            optimizationResolve = null;
        }
    };

    // --- Main form submit handler ---
    var uploadForm = document.querySelector('#uploadModal form');

    uploadForm.addEventListener('submit', async function(e) {
        var fileInput = uploadForm.querySelector('input[type="file"]');
        var mediaType = uploadForm.querySelector('select[name="media_type"]').value;
        var file = fileInput.files[0];

        if (!file) return;

        // --- IMAGE: Canvas → WebP ---
        if (mediaType === 'image' && !file.name.toLowerCase().endsWith('.webp')) {
            e.preventDefault();

            var sizeMB = (file.size / (1024 * 1024)).toFixed(2);
            var warning = sizeMB > 5 ?
                'This image is ' + sizeMB + 'MB. Converting to WebP will significantly reduce file size for faster web delivery. Proceed?' :
                'Converting this image to WebP will optimize it for web delivery. Proceed?';

            var confirmed = await window.showOptimizationConfirm(warning);
            if (!confirmed) {
                uploadForm.requestSubmit();
                return;
            }

            try {
                uploadBtn.disabled = true;
                uploadBtn.innerText = 'Converting...';
                showProgress('Converting to WebP...', 'Canvas API transcoding in progress. Please keep this tab open.');
                setProgress(20);

                var result = await convertImageToWebP(file, 0.8, 4096);
                setProgress(80);

                replaceFileInput(fileInput, result.file);
                setProgress(100);

                uploadBtn.innerText = 'Ingest to Library';
                hideProgress();

                uploadForm.submit();
            } catch (error) {
                console.error('Image conversion failed:', error);
                document.getElementById('optimizationFailedAlert').classList.remove('hidden');
                hideProgress();
                uploadBtn.disabled = false;
                uploadBtn.innerText = 'Ingest to Library';
            }

            return;
        }

        // --- VIDEO: ffmpeg.wasm → WebM (loaded on demand) ---
        if (mediaType === 'video' && !file.name.toLowerCase().endsWith('.webm')) {
            e.preventDefault();

            var sizeMB = (file.size / (1024 * 1024)).toFixed(2);
            var warning = sizeMB > 50 ?
                'This video is quite large (' + sizeMB + 'MB). Client-side optimization may take several minutes and consume significant CPU. Proceed?' :
                'We\'ll optimize this video (' + sizeMB + 'MB) for web performance before uploading. This happens in your browser. Proceed?';

            var confirmed = await window.showOptimizationConfirm(warning);
            if (!confirmed) {
                uploadForm.requestSubmit();
                return;
            }

            try {
                uploadBtn.disabled = true;
                uploadBtn.innerText = 'Loading transcoder...';
                showProgress('Transcoding to WebM...', 'Client-side video encoding active. Please keep this tab open.');

                var ffmpegModule = await import('https://unpkg.com/@ffmpeg/ffmpeg@0.12.7/dist/esm/index.js');
                var utilModule = await import('https://unpkg.com/@ffmpeg/util@0.12.1/dist/esm/index.js');

                var ffmpeg = new ffmpegModule.FFmpeg();
                var fetchFile = utilModule.fetchFile;
                var toBlobURL = utilModule.toBlobURL;

                if (!ffmpeg.loaded) {
                    uploadBtn.innerText = 'Loading codec...';
                    var baseURL = 'https://unpkg.com/@ffmpeg/core@0.12.6/dist/esm';
                    await ffmpeg.load({
                        coreURL: await toBlobURL(baseURL + '/ffmpeg-core.js', 'text/javascript'),
                        wasmURL: await toBlobURL(baseURL + '/ffmpeg-core.wasm', 'application/wasm'),
                    });
                }

                uploadBtn.innerText = 'Transcoding...';

                ffmpeg.on('progress', function(p) {
                    setProgress(Math.round(p.progress * 100));
                });

                await ffmpeg.writeFile('input_file', await fetchFile(file));
                await ffmpeg.exec(['-i', 'input_file', '-c:v', 'libvpx-vp9', '-crf', '30', '-b:v', '0', '-an', 'output.webm']);

                var data = await ffmpeg.readFile('output.webm');
                var webmBlob = new Blob([data.buffer], {
                    type: 'video/webm'
                });
                var optimizedFile = new File([webmBlob], file.name.replace(/\.[^.]+$/, '') + '.webm', {
                    type: 'video/webm'
                });

                replaceFileInput(fileInput, optimizedFile);

                uploadBtn.innerText = 'Ingest to Library';
                hideProgress();
                uploadForm.submit();
            } catch (error) {
                console.error('Video optimization failed:', error);
                document.getElementById('optimizationFailedAlert').classList.remove('hidden');
                hideProgress();
                uploadBtn.disabled = false;
                uploadBtn.innerText = 'Ingest to Library';
            }
        }
    });
</script>
@endpush
@endsection