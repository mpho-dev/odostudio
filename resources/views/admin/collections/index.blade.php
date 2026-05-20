@extends('layouts.app')

@section('header', 'Collections')

@section('content')
<div class="space-y-12 pb-12">
    <div class="flex flex-col md:flex-row justify-between items-end gap-6 reveal">
        <div>
            <div class="flex items-center gap-4 text-[0.62rem] tracking-[0.4em] uppercase text-gold mb-4">
                <span>Asset Organization</span>
                <div class="h-px w-16 bg-gold/30"></div>
            </div>
            <h1 class="font-serif text-4xl text-charcoal dark:text-white leading-tight">Collections</h1>
            <p class="text-xs tracking-[0.3em] uppercase text-ash/60 dark:text-ash/60 mt-2 italic">Group media into virtual albums</p>
        </div>
        <button type="button" onclick="document.getElementById('create-collection-modal').classList.remove('hidden')" class="group relative px-8 py-4 bg-gold text-black text-[0.65rem] tracking-[0.3em] uppercase font-bold hover:bg-white transition-all duration-500 overflow-hidden">
            <span class="relative z-10">+ New Collection</span>
            <div class="absolute inset-0 bg-white translate-x-[-100%] group-hover:translate-x-0 transition-transform duration-500"></div>
        </button>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($collections as $collection)
            <div class="glass group relative p-8 rounded-xl border border-charcoal/10 dark:border-white/10 transition-all duration-500 hover:border-gold/30 hover:shadow-2xl hover:shadow-gold/5 flex flex-col h-full">
                <div class="flex justify-between items-start mb-5">
                    <div class="flex items-center gap-3">
                        @if($collection->color)
                            <div class="w-4 h-4 rounded-full" style="background-color: {{ $collection->color }}"></div>
                        @else
                            <div class="w-4 h-4 rounded-full bg-gold/30"></div>
                        @endif
                        <div>
                            <p class="font-serif text-xl text-charcoal dark:text-white leading-tight">{{ $collection->name }}</p>
                            @if($collection->user)
                                <p class="text-[0.6rem] tracking-[0.2em] uppercase text-silver/60 mt-1">Created by {{ $collection->user->name }}</p>
                            @endif
                        </div>
                    </div>
                </div>

                @if($collection->description)
                    <p class="text-sm text-charcoal/70 dark:text-ash/70 leading-relaxed mb-4 line-clamp-3">{{ $collection->description }}</p>
                @endif

                <div class="mt-auto pt-4 border-t border-charcoal/10 dark:border-white/10 flex items-center justify-between">
                    <span class="text-[0.6rem] tracking-[0.3em] uppercase text-silver/50">
                        {{ $collection->media_count }} {{ Str::plural('asset', $collection->media_count) }}
                    </span>
                    <div class="flex gap-3">
                        <a href="{{ route('admin.collections.edit', $collection) }}" class="text-[0.6rem] tracking-[0.3em] uppercase text-ash/60 hover:text-gold transition-colors">
                            Edit
                        </a>
                        <button type="button" onclick="document.getElementById('delete-collection-{{ $collection->id }}').classList.remove('hidden')" class="text-[0.6rem] tracking-[0.3em] uppercase text-red-500/40 hover:text-red-500 transition-colors">
                            Delete
                        </button>
                    </div>
                </div>
            </div>
            <x-confirm-modal
                id="delete-collection-{{ $collection->id }}"
                title="Delete Collection"
                message="Are you certain? This will remove all media associations from this collection (media files themselves will not be deleted)."
                confirm-label="Delete"
                :route="route('admin.collections.destroy', $collection)"
                method="DELETE"
                variant="danger"
            />
        @empty
            <div class="col-span-full py-24 text-center glass rounded-xl border-dashed border-white/10">
                <p class="font-serif text-xl italic text-silver/40">No collections yet. Create your first collection to start organizing media.</p>
            </div>
        @endforelse
    </div>
</div>

{{-- Create Collection Modal --}}
<div id="create-collection-modal" class="fixed inset-0 z-50 hidden" role="dialog" aria-modal="true">
    <div class="absolute inset-0 bg-black/80 backdrop-blur-sm" onclick="document.getElementById('create-collection-modal').classList.add('hidden')"></div>
    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-full max-w-2xl max-h-[90vh] overflow-y-auto p-4">
        <div class="glass border border-white/10 rounded-xl p-8 shadow-2xl">
            <div class="flex items-center gap-4 text-[0.62rem] tracking-[0.4em] uppercase text-gold mb-4">
                <span>New Collection</span>
                <div class="h-px w-16 bg-gold/30"></div>
            </div>

            <form action="{{ route('admin.collections.store') }}" method="POST" id="create-collection-form">
                @csrf

                <div class="mb-5">
                    <label class="block text-[0.62rem] tracking-[0.3em] uppercase text-ash/60 dark:text-silver mb-2">
                        Name <span class="text-gold">*</span>
                    </label>
                    <input type="text" name="name" required placeholder="e.g., Johnson Wedding 2026" class="w-full bg-charcoal/5 dark:bg-black/50 border border-charcoal/10 dark:border-white/10 p-3.5 text-sm text-charcoal dark:text-white focus:border-gold outline-none transition-all duration-300" />
                </div>

                <div class="mb-5">
                    <label class="block text-[0.62rem] tracking-[0.3em] uppercase text-ash/60 dark:text-silver mb-2">
                        Description
                    </label>
                    <textarea name="description" rows="3" placeholder="Brief description of this collection..." class="w-full bg-charcoal/5 dark:bg-black/50 border border-charcoal/10 dark:border-white/10 p-3.5 text-sm text-charcoal dark:text-white focus:border-gold outline-none transition-all duration-300"></textarea>
                </div>

                <div class="mb-5">
                    <label class="block text-[0.62rem] tracking-[0.3em] uppercase text-ash/60 dark:text-silver mb-2">
                        Color
                    </label>
                    <div class="flex items-center gap-3">
                        <input type="color" id="collection-color-picker" value="#c9a84c" class="w-10 h-10 rounded cursor-pointer border border-iron bg-transparent" />
                        <input type="text" name="color" id="collection-color-input" value="#c9a84c" maxlength="7" placeholder="#c9a84c" class="flex-1 bg-charcoal/5 dark:bg-black/50 border border-charcoal/10 dark:border-white/10 p-3.5 text-sm text-charcoal dark:text-white focus:border-gold outline-none transition-all duration-300" />
                    </div>
                </div>

                @if($media->isNotEmpty())
                <div class="mb-6">
                    <div class="flex items-center justify-between mb-3">
                        <label class="block text-[0.62rem] tracking-[0.3em] uppercase text-ash/60 dark:text-silver">
                            Add Media
                        </label>
                        <button type="button" onclick="toggleMediaSelection()" class="text-[0.6rem] tracking-[0.2em] uppercase text-gold hover:text-white transition-colors">
                            Select Media
                        </button>
                    </div>
                    
                    <div id="media-selection-container" class="hidden">
                        <div class="grid grid-cols-6 gap-2 max-h-64 overflow-y-auto p-3 bg-charcoal/30 dark:bg-black/50 border border-charcoal/10 dark:border-white/10 rounded-lg">
                            @foreach($media as $item)
                            <label class="media-checkbox-label relative cursor-pointer group">
                                <input type="checkbox" name="media_ids[]" value="{{ $item->id }}" class="sr-only peer" onchange="updateSelectedMedia(this)" />
                                <div class="aspect-square rounded overflow-hidden border-2 border-transparent peer-checked:border-gold transition-all">
                                    @if($item->file_path)
                                        <img src="{{ asset('storage/' . $item->file_path) }}" alt="{{ $item->title ?? 'Media' }}" class="w-full h-full object-cover" />
                                    @else
                                        <div class="w-full h-full bg-graphite flex items-center justify-center text-silver/30">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                        </div>
                                    @endif
                                </div>
                                <div class="absolute inset-0 bg-gold/20 opacity-0 peer-checked:opacity-100 transition-opacity pointer-events-none flex items-center justify-center">
                                    <svg class="w-5 h-5 text-gold" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                            </label>
                            @endforeach
                        </div>
                        <p class="text-[0.6rem] tracking-[0.1em] text-silver/50 mt-2 text-center">
                            <span id="selected-count">0</span> media selected
                        </p>
                    </div>
                </div>
                @endif

                <div class="flex justify-end gap-4">
                    <button type="button" onclick="document.getElementById('create-collection-modal').classList.add('hidden')" class="px-6 py-3 text-[0.6rem] tracking-[0.3em] uppercase font-bold text-ash/60 hover:text-charcoal dark:hover:text-white transition-colors">
                        Cancel
                    </button>
                    <button type="submit" class="group relative px-8 py-3 bg-gold text-black text-[0.6rem] tracking-[0.3em] uppercase font-bold hover:bg-white transition-all duration-500 overflow-hidden">
                        <span class="relative z-10">Create Collection</span>
                        <div class="absolute inset-0 bg-white translate-x-[-100%] group-hover:translate-x-0 transition-transform duration-500"></div>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    const colorPicker = document.getElementById('collection-color-picker');
    const colorInput = document.getElementById('collection-color-input');

    if (colorPicker && colorInput) {
        colorPicker.addEventListener('input', (e) => {
            colorInput.value = e.target.value;
        });
        colorInput.addEventListener('input', (e) => {
            colorPicker.value = e.target.value;
        });
    }

    function toggleMediaSelection() {
        const container = document.getElementById('media-selection-container');
        container.classList.toggle('hidden');
    }

    function updateSelectedMedia(checkbox) {
        const count = document.querySelectorAll('#create-collection-form input[name="media_ids[]"]:checked').length;
        document.getElementById('selected-count').textContent = count;
    }
</script>
@endpush
@endsection
