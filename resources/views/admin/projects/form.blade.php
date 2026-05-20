<div class="space-y-10">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
        <!-- Title -->
        <div class="space-y-3">
            <label for="title" class="block text-[0.6rem] tracking-[0.4em] uppercase text-ash/60 dark:text-silver font-bold">Project Narrative Title</label>
            <input type="text" name="title" id="title" value="{{ old('title', $project->title ?? '') }}" required 
                   class="w-full bg-charcoal/5 dark:bg-black/40 border border-charcoal/10 dark:border-white/10 p-4 text-sm text-charcoal dark:text-white focus:border-gold outline-none transition-all placeholder:italic"
                   placeholder="e.g. The Ethereal Wedding of James & Sarah">
            @error('title') <p class="text-red-500 text-[0.6rem] tracking-widest uppercase mt-2">{{ $message }}</p> @enderror
        </div>

        <!-- Slug -->
        <div class="space-y-3">
            <label for="slug" class="block text-[0.6rem] tracking-[0.4em] uppercase text-ash/60 dark:text-silver font-bold">URL Signature (Slug)</label>
            <input type="text" name="slug" id="slug" value="{{ old('slug', $project->slug ?? '') }}" 
                   class="w-full bg-charcoal/5 dark:bg-black/40 border border-charcoal/10 dark:border-white/10 p-4 text-sm text-charcoal dark:text-white focus:border-gold outline-none transition-all placeholder:italic"
                   placeholder="ethereal-wedding-james-sarah">
            @error('slug') <p class="text-red-500 text-[0.6rem] tracking-widest uppercase mt-2">{{ $message }}</p> @enderror
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-10">
        <!-- Client -->
        <div class="space-y-3">
            <label for="client" class="block text-[0.6rem] tracking-[0.4em] uppercase text-ash/60 dark:text-silver font-bold">Client Identity</label>
            <input type="text" name="client" id="client" value="{{ old('client', $project->client ?? '') }}" 
                   class="w-full bg-charcoal/5 dark:bg-black/40 border border-charcoal/10 dark:border-white/10 p-4 text-sm text-charcoal dark:text-white focus:border-gold outline-none transition-all placeholder:italic"
                   placeholder="Client Name or Brand">
        </div>

        <!-- Location -->
        <div class="space-y-3">
            <label for="location" class="block text-[0.6rem] tracking-[0.4em] uppercase text-ash/60 dark:text-silver font-bold">Production Venue</label>
            <input type="text" name="location" id="location" value="{{ old('location', $project->location ?? '') }}" 
                   class="w-full bg-charcoal/5 dark:bg-black/40 border border-charcoal/10 dark:border-white/10 p-4 text-sm text-charcoal dark:text-white focus:border-gold outline-none transition-all placeholder:italic"
                   placeholder="City, Province or Site">
        </div>

        <!-- Category -->
        <div class="space-y-3">
            <label for="category" class="block text-[0.6rem] tracking-[0.4em] uppercase text-ash/60 dark:text-silver font-bold">Artistic Category</label>
            <select name="category" id="category" 
                    class="w-full bg-charcoal/5 dark:bg-black/40 border border-charcoal/10 dark:border-white/10 p-4 text-sm text-charcoal dark:text-white focus:border-gold outline-none transition-all appearance-none">
                <option value="Wedding" {{ (old('category', $project->category ?? '') == 'Wedding') ? 'selected' : '' }}>Wedding Cinema</option>
                <option value="Commercial" {{ (old('category', $project->category ?? '') == 'Commercial') ? 'selected' : '' }}>Commercial Storytelling</option>
                <option value="Branding" {{ (old('category', $project->category ?? '') == 'Branding') ? 'selected' : '' }}>High-End Branding</option>
                <option value="Event" {{ (old('category', $project->category ?? '') == 'Event') ? 'selected' : '' }}>Candid Event Footage</option>
            </select>
        </div>
    </div>

    <!-- Description -->
    <div class="space-y-3">
        <label for="description" class="block text-[0.6rem] tracking-[0.4em] uppercase text-ash/60 dark:text-silver font-bold">Narrative Description</label>
        <textarea name="description" id="description" rows="5" required 
                  class="w-full bg-charcoal/5 dark:bg-black/40 border border-charcoal/10 dark:border-white/10 p-4 text-sm text-charcoal dark:text-white focus:border-gold outline-none transition-all placeholder:text-ash/40 dark:placeholder:text-silver/40 font-serif text-lg leading-relaxed italic"
                  placeholder="Tell the story of this project... Describe the atmosphere, the challenges, and the artistic choices made.">{{ old('description', $project->description ?? '') }}</textarea>
        @error('description') <p class="text-red-500 text-[0.6rem] tracking-widest uppercase mt-2">{{ $message }}</p> @enderror
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
        <!-- Hero Media ID -->
        <div class="space-y-3">
            <label for="hero_media_id" class="block text-[0.6rem] tracking-[0.4em] uppercase text-ash/60 dark:text-silver font-bold">Primary Hero Media</label>
            <select name="hero_media_id" id="hero_media_id" 
                    class="w-full bg-charcoal/5 dark:bg-black/40 border border-charcoal/10 dark:border-white/10 p-4 text-sm text-charcoal dark:text-white focus:border-gold outline-none transition-all appearance-none">
                <option value="">— Select Hero Asset —</option>
                @foreach($mediaItems as $media)
                    <option value="{{ $media->id }}" {{ (old('hero_media_id', $project->hero_media_id ?? '') == $media->id) ? 'selected' : '' }}>
                        {{ $media->title ?: $media->file_name }} ({{ $media->media_type }})
                    </option>
                @endforeach
            </select>
            <p class="text-[0.55rem] tracking-widest uppercase text-ash/60 dark:text-ash/40 italic">This asset will represent the project in the main gallery.</p>
        </div>

        <!-- Featured & Order -->
        <div class="flex items-end gap-10 pb-4">
            <div class="flex items-center">
                <label for="is_featured" class="inline-flex items-center group cursor-pointer">
                    <input type="hidden" name="is_featured" value="0">
                    <input id="is_featured" type="checkbox" name="is_featured" value="1" {{ old('is_featured', $project->is_featured ?? false) ? 'checked' : '' }}
                           class="w-5 h-5 bg-charcoal/10 dark:bg-black border-charcoal/10 dark:border-white/10 rounded text-gold focus:ring-gold focus:ring-offset-paper dark:focus:ring-offset-black">
                    <span class="ml-3 text-[0.62rem] tracking-[0.3em] uppercase text-ash/60 dark:text-silver hover:text-charcoal dark:hover:text-white transition-colors">Featured Status</span>
                </label>
            </div>
            
            <div class="flex-1 space-y-3">
                <label for="order" class="block text-[0.6rem] tracking-[0.4em] uppercase text-ash/60 dark:text-silver font-bold">Archive Position</label>
                <input type="number" name="order" id="order" value="{{ old('order', $project->order ?? 0) }}" 
                       class="w-full bg-charcoal/5 dark:bg-black/40 border border-charcoal/10 dark:border-white/10 p-2.5 text-sm text-charcoal dark:text-white focus:border-gold outline-none transition-all">
            </div>
        </div>
    </div>

    <!-- Media Selection -->
    <div class="space-y-6 pt-10 border-t border-charcoal/10 dark:border-white/10">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div>
                <label class="block text-[0.6rem] tracking-[0.4em] uppercase text-gold font-bold mb-1">Curate Perspective Assets</label>
                <span class="text-[0.6rem] tracking-[0.3em] uppercase text-ash/60 dark:text-ash/40 italic">Selected assets will be part of this project's gallery.</span>
            </div>
            @if(isset($collections) && $collections->count() > 0)
            <div class="w-full md:w-64">
                <select id="collection_selector" class="w-full bg-charcoal/5 dark:bg-black/40 border border-charcoal/10 dark:border-white/10 p-2 text-xs text-charcoal dark:text-white focus:border-gold outline-none transition-all appearance-none cursor-pointer">
                    <option value="">— Auto-select from Collection —</option>
                    @foreach($collections as $collection)
                        <option value="{{ $collection->id }}" data-media-ids="{{ json_encode($collection->media->pluck('id')) }}">
                            {{ $collection->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            @endif
        </div>
        
                @php
                    // Get selected media IDs for this project
                    $selectedMediaIds = isset($project) ? $project->media->pluck('id')->toArray() : [];
                @endphp
                
                <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4 max-h-96 overflow-y-auto p-4 bg-charcoal/5 dark:bg-black/20 custom-scrollbar border border-charcoal/10 dark:border-white/10 rounded-xl">
                    @forelse($mediaItems as $media)
                        @php
                            $fileExists = \Illuminate\Support\Facades\Storage::disk('public')->exists($media->file_path);
                            $isSelected = in_array($media->id, $selectedMediaIds);
                        @endphp
                        <label class="relative group cursor-pointer aspect-square rounded-lg overflow-hidden border-2 {{ $isSelected ? 'border-gold shadow-[0_0_15px_rgba(201,168,76,0.2)]' : 'border-charcoal/10 dark:border-white/10 opacity-40 hover:opacity-80' }} transition-all duration-300">
                            <input type="checkbox" name="selected_media[]" value="{{ $media->id }}" 
                                   class="absolute top-2 right-2 w-4 h-4 bg-charcoal/10 dark:bg-black border-charcoal/10 dark:border-white/10 rounded text-gold focus:ring-gold z-10"
                                   {{ $isSelected ? 'checked' : '' }}
                                   onchange="this.parentElement.className = this.checked ? 'relative group cursor-pointer aspect-square rounded-lg overflow-hidden border-2 border-gold shadow-[0_0_15px_rgba(201,168,76,0.2)] transition-all duration-300' : 'relative group cursor-pointer aspect-square rounded-lg overflow-hidden border-2 border-charcoal/10 dark:border-white/10 opacity-40 hover:opacity-80 transition-all duration-300'">
                            
                            @if($fileExists)
                                @if($media->media_type === 'video')
                                    <video src="{{ media_url($media->file_path) }}" class="w-full h-full object-cover"></video>
                                @else
                                    <img src="{{ media_url($media->file_path) }}" 
                                         class="w-full h-full object-cover"
                                         onerror="this.style.display='none'; this.nextElementSibling.style.display='grid';">
                                    <div class="w-full h-full hidden bg-charcoal grid place-items-center">
                                        <svg class="w-8 h-8 text-ash/30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                    </div>
                                @endif
                            @else
                                <div class="w-full h-full bg-charcoal grid place-items-center">
                                    <svg class="w-8 h-8 text-ash/30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                </div>
                            @endif
                    
                    <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity grid place-items-center p-2">
                        <span class="text-[0.5rem] tracking-widest uppercase text-white font-bold text-center line-clamp-2">{{ $media->title ?: $media->file_name }}</span>
                    </div>
                </label>
            @empty
                <div class="col-span-full py-8 text-center">
                    <p class="text-ash/60 italic">No media assets available. Upload media first.</p>
                </div>
            @endforelse
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const selector = document.getElementById('collection_selector');
    if (!selector) return;

    selector.addEventListener('change', function() {
        if (!this.value) return;
        
        const option = this.options[this.selectedIndex];
        try {
            const mediaIds = JSON.parse(option.getAttribute('data-media-ids') || '[]');
            
            // Check checkboxes if their value is in mediaIds
            const checkboxes = document.querySelectorAll('input[name="selected_media[]"]');
            checkboxes.forEach(cb => {
                const id = parseInt(cb.value);
                if (mediaIds.includes(id)) {
                    if (!cb.checked) {
                        cb.checked = true;
                        // Manually trigger the change event to update styles
                        cb.dispatchEvent(new Event('change'));
                    }
                }
            });
            

        } catch (e) {
            console.error('Failed to parse media IDs', e);
        }
    });
});
</script>
