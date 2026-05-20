@extends('layouts.app')

@section('header', 'Edit Collection')

@section('content')
<div class="space-y-12 pb-12">
    <div class="reveal">
        <div class="flex items-center gap-4 text-[0.62rem] tracking-[0.4em] uppercase text-gold mb-4">
            <span>Asset Organization</span>
            <div class="h-px w-16 bg-gold/30"></div>
        </div>
        <h1 class="font-serif text-4xl text-charcoal dark:text-white leading-tight">Edit Collection</h1>
        <p class="text-xs tracking-[0.3em] uppercase text-ash/60 dark:text-ash/60 mt-2 italic">{{ $collection->name }}</p>
    </div>

    <div class="max-w-2xl">
        <form action="{{ route('admin.collections.update', $collection) }}" method="POST" id="edit-collection-form">
            @csrf
            @method('PATCH')

            <div class="glass rounded-xl border border-charcoal/10 dark:border-white/10 p-8 space-y-6 shadow-2xl relative overflow-hidden group">
                <div class="absolute top-0 left-0 w-8 h-8 border-t border-l border-gold/40 group-hover:w-12 group-hover:h-12 transition-all duration-500"></div>
                <div class="absolute bottom-0 right-0 w-8 h-8 border-b border-r border-gold/40 group-hover:w-12 group-hover:h-12 transition-all duration-500"></div>
                <div>
                    <label class="block text-[0.62rem] tracking-[0.3em] uppercase text-ash/60 dark:text-silver mb-2">
                        Name <span class="text-gold">*</span>
                    </label>
                    <input type="text" name="name" value="{{ old('name', $collection->name) }}" required class="w-full bg-charcoal/5 dark:bg-black/50 border border-charcoal/10 dark:border-white/10 p-3.5 text-sm text-charcoal dark:text-white focus:border-gold outline-none transition-all duration-300" />
                </div>

                <div>
                    <label class="block text-[0.62rem] tracking-[0.3em] uppercase text-ash/60 dark:text-silver mb-2">
                        Description
                    </label>
                    <textarea name="description" rows="4" class="w-full bg-charcoal/5 dark:bg-black/50 border border-charcoal/10 dark:border-white/10 p-3.5 text-sm text-charcoal dark:text-white focus:border-gold outline-none transition-all duration-300">{{ old('description', $collection->description) }}</textarea>
                </div>

                <div>
                    <label class="block text-[0.62rem] tracking-[0.3em] uppercase text-ash/60 dark:text-silver mb-2">
                        Color
                    </label>
                    <div class="flex items-center gap-3">
                        <input type="color" id="collection-color-picker" value="{{ old('color', $collection->color ?? '#c9a84c') }}" class="w-10 h-10 rounded cursor-pointer border border-iron bg-transparent" />
                        <input type="text" name="color" id="collection-color-input" value="{{ old('color', $collection->color ?? '#c9a84c') }}" maxlength="7" class="flex-1 bg-charcoal/5 dark:bg-black/50 border border-charcoal/10 dark:border-white/10 p-3.5 text-sm text-charcoal dark:text-white focus:border-gold outline-none transition-all duration-300" />
                    </div>
                </div>

                @if($media->isNotEmpty())
                <div class="pt-4 border-t border-charcoal/10 dark:border-white/10">
                    <div class="flex items-center justify-between mb-3">
                        <label class="block text-[0.62rem] tracking-[0.3em] uppercase text-ash/60 dark:text-silver">
                            Collection Media
                        </label>
                        <button type="button" onclick="toggleMediaSelection()" class="text-[0.6rem] tracking-[0.2em] uppercase text-gold hover:text-white transition-colors">
                            Select Media
                        </button>
                    </div>
                    
                    <div id="media-selection-container" class="hidden">
                        <div class="grid grid-cols-6 gap-2 max-h-64 overflow-y-auto p-3 bg-charcoal/30 dark:bg-black/50 border border-charcoal/10 dark:border-white/10 rounded-lg">
                            @foreach($media as $item)
                            <label class="media-checkbox-label relative cursor-pointer group">
                                <input type="checkbox" name="media_ids[]" value="{{ $item->id }}" class="sr-only peer" 
                                    @if($collection->media->contains($item->id)) checked @endif
                                    onchange="updateSelectedMedia(this)" />
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
                            <span id="selected-count">{{ $collection->media->count() }}</span> media selected
                        </p>
                    </div>
                </div>
                @endif

                <div class="pt-4 border-t border-charcoal/10 dark:border-white/10 flex justify-end gap-4">
                    <a href="{{ route('admin.collections.index') }}" class="px-6 py-3 text-[0.6rem] tracking-[0.3em] uppercase font-bold text-ash/60 hover:text-charcoal dark:hover:text-white transition-colors">
                        Cancel
                    </a>
                    <button type="submit" class="group relative px-8 py-3 bg-gold text-black text-[0.6rem] tracking-[0.3em] uppercase font-bold hover:bg-white transition-all duration-500 overflow-hidden">
                        <span class="relative z-10">Save Changes</span>
                        <div class="absolute inset-0 bg-white translate-x-[-100%] group-hover:translate-x-0 transition-transform duration-500"></div>
                    </button>
                </div>
            </div>
        </form>
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
        const count = document.querySelectorAll('#edit-collection-form input[name="media_ids[]"]:checked').length;
        document.getElementById('selected-count').textContent = count;
    }
</script>
@endpush
@endsection
