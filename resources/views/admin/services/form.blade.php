<div class="space-y-10">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
        <!-- Title -->
        <div class="space-y-3">
            <label for="title" class="block text-[0.6rem] tracking-[0.4em] uppercase text-ash/60 dark:text-silver font-bold">Service Title</label>
            <input type="text" name="title" id="title" value="{{ old('title', $service->title ?? '') }}" required 
                   class="w-full bg-charcoal/5 dark:bg-black/40 border border-charcoal/10 dark:border-white/10 p-4 text-sm text-charcoal dark:text-white focus:border-gold outline-none transition-all placeholder:italic"
                   placeholder="e.g. Cinematic Wedding Story">
            @error('title') <p class="text-red-500 text-[0.6rem] tracking-widest uppercase mt-2">{{ $message }}</p> @enderror
        </div>

        <!-- Icon -->
        <div class="space-y-3">
            <label for="icon" class="block text-[0.6rem] tracking-[0.4em] uppercase text-ash/60 dark:text-silver font-bold">Visual Identifier (Emoji)</label>
            <input type="text" name="icon" id="icon" value="{{ old('icon', $service->icon ?? '') }}" placeholder="📷"
                   class="w-full bg-charcoal/5 dark:bg-black/40 border border-charcoal/10 dark:border-white/10 p-4 text-sm text-charcoal dark:text-white focus:border-gold outline-none transition-all placeholder:italic">
                   
            <div class="flex flex-wrap gap-2 pt-1 pb-1">
                @foreach(['📷', '🎥', '🎬', '🎞️', '🚁', '💍', '👗', '🥂', '👶', '🏢', '🎤', '🎧', '🎨', '✨', '📸', '📹'] as $emoji)
                    <button type="button" onclick="document.getElementById('icon').value = '{{ $emoji }}'" class="w-8 h-8 flex items-center justify-center text-lg bg-charcoal/10 dark:bg-white/5 border border-charcoal/10 dark:border-white/10 rounded hover:bg-gold/20 hover:border-gold/50 hover:scale-110 transition-all">
                        {{ $emoji }}
                    </button>
                @endforeach
            </div>

            <p class="text-[0.55rem] tracking-widest uppercase text-ash/60 dark:text-ash/40 italic">A subtle glyph to represent this service tier. Click an emoji above to automatically select it.</p>
            @error('icon') <p class="text-red-500 text-[0.6rem] tracking-widest uppercase mt-2">{{ $message }}</p> @enderror
        </div>
    </div>

    <!-- Description -->
    <div class="space-y-3">
        <label for="description" class="block text-[0.6rem] tracking-[0.4em] uppercase text-ash/60 dark:text-silver font-bold">Artistic Narrative</label>
        <textarea name="description" id="description" rows="4" required 
                  class="w-full bg-charcoal/5 dark:bg-black/40 border border-charcoal/10 dark:border-white/10 p-4 text-sm text-charcoal dark:text-white focus:border-gold outline-none transition-all placeholder:text-ash/40 dark:placeholder:text-silver/40 font-serif text-lg leading-relaxed italic"
                  placeholder="Describe the cinematic journey of this service...">{{ old('description', $service->description ?? '') }}</textarea>
        @error('description') <p class="text-red-500 text-[0.6rem] tracking-widest uppercase mt-2">{{ $message }}</p> @enderror
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
        <!-- Price -->
        <div class="space-y-3">
            <label for="starting_price" class="block text-[0.6rem] tracking-[0.4em] uppercase text-ash/60 dark:text-silver font-bold">Base Investment (ZAR)</label>
            <div class="relative group">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gold/60 group-focus-within:text-gold transition-colors">
                    <span class="text-sm">R</span>
                </div>
                <input type="number" step="0.01" name="starting_price" id="starting_price" value="{{ old('starting_price', $service->starting_price ?? '') }}" required 
                       class="w-full bg-charcoal/5 dark:bg-black/40 border border-charcoal/10 dark:border-white/10 pl-10 pr-4 py-4 text-sm text-charcoal dark:text-white focus:border-gold outline-none transition-all">
            </div>
            @error('starting_price') <p class="text-red-500 text-[0.6rem] tracking-widest uppercase mt-2">{{ $message }}</p> @enderror
        </div>

        <!-- Order -->
        <div class="space-y-3">
            <label for="order" class="block text-[0.6rem] tracking-[0.4em] uppercase text-ash/60 dark:text-silver font-bold">Curation Order</label>
            <input type="number" name="order" id="order" value="{{ old('order', $service->order ?? 0) }}" 
                   class="w-full bg-charcoal/5 dark:bg-black/40 border border-charcoal/10 dark:border-white/10 p-4 text-sm text-charcoal dark:text-white focus:border-gold outline-none transition-all">
            @error('order') <p class="text-red-500 text-[0.6rem] tracking-widest uppercase mt-2">{{ $message }}</p> @enderror
        </div>
    </div>

    <!-- Key Features -->
    <div class="space-y-6">
        <label class="block text-[0.6rem] tracking-[0.4em] uppercase text-ash/60 dark:text-silver font-bold">Signature Deliverables</label>
        <div id="features-container" class="space-y-4">
            @php
                $features = old('features', $service->features ?? ['']);
            @endphp
            @foreach($features as $index => $feature)
                <div class="flex gap-4 feature-item group reveal" style="animation-delay: {{ $index * 50 }}ms;">
                    <div class="flex-1 relative">
                        <input type="text" name="features[]" value="{{ $feature }}" 
                               class="w-full bg-charcoal/5 dark:bg-black/40 border border-charcoal/10 dark:border-white/10 p-4 text-sm text-charcoal dark:text-white focus:border-gold outline-none transition-all"
                               placeholder="e.g. 4K Master Grade Film">
                        <div class="absolute bottom-0 left-0 w-0 h-px bg-gold group-focus-within:w-full transition-all duration-500"></div>
                    </div>
                    <button type="button" onclick="this.parentElement.remove()" class="px-4 text-red-500/40 hover:text-red-500 transition-colors text-xl">×</button>
                </div>
            @endforeach
        </div>
        <button type="button" onclick="addFeature()" 
                class="inline-flex items-center gap-2 text-[0.6rem] tracking-[0.3em] uppercase text-gold hover:text-white transition-colors duration-300">
            <span class="text-lg">+</span> Add Feature Aspect
        </button>
    </div>
</div>

<script>
    function addFeature() {
        const container = document.getElementById('features-container');
        const div = document.createElement('div');
        div.className = 'flex gap-4 feature-item group reveal animate-hero-in';
        div.innerHTML = `
            <div class="flex-1 relative">
                <input type="text" name="features[]" 
                       class="w-full bg-charcoal/5 dark:bg-black/40 border border-charcoal/10 dark:border-white/10 p-4 text-sm text-charcoal dark:text-white focus:border-gold outline-none transition-all"
                       placeholder="Define a signature aspect...">
                <div class="absolute bottom-0 left-0 w-0 h-px bg-gold group-focus-within:w-full transition-all duration-500"></div>
            </div>
            <button type="button" onclick="this.parentElement.remove()" class="px-4 text-red-500/40 hover:text-red-500 transition-colors text-xl">×</button>
        `;
        container.appendChild(div);
    }
</script>
