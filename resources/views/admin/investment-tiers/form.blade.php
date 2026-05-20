<div class="space-y-10">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
        <!-- Tier Label -->
        <div class="space-y-3">
            <label for="tier_label" class="block text-[0.6rem] tracking-[0.4em] uppercase text-ash/60 dark:text-silver font-bold">Tier Label</label>
            <input
                type="text"
                name="tier_label"
                id="tier_label"
                value="{{ old('tier_label', data_get($tier, 'tier_label', '')) }}"
                required
                class="w-full bg-charcoal/5 dark:bg-black/40 border border-charcoal/10 dark:border-white/10 p-4 text-sm text-charcoal dark:text-white focus:border-gold outline-none transition-all placeholder:italic"
                placeholder="e.g. Essential"
            >
            @error('tier_label') <p class="text-red-500 text-[0.6rem] tracking-widest uppercase mt-2">{{ $message }}</p> @enderror
        </div>

        <!-- Name -->
        <div class="space-y-3">
            <label for="name" class="block text-[0.6rem] tracking-[0.4em] uppercase text-ash/60 dark:text-silver font-bold">Collection Name</label>
            <input
                type="text"
                name="name"
                id="name"
                value="{{ old('name', data_get($tier, 'name', '')) }}"
                required
                class="w-full bg-charcoal/5 dark:bg-black/40 border border-charcoal/10 dark:border-white/10 p-4 text-sm text-charcoal dark:text-white focus:border-gold outline-none transition-all placeholder:italic"
                placeholder="e.g. The Classic"
            >
            @error('name') <p class="text-red-500 text-[0.6rem] tracking-widest uppercase mt-2">{{ $message }}</p> @enderror
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-10">
        <!-- Price -->
        <div class="space-y-3">
            <label for="price" class="block text-[0.6rem] tracking-[0.4em] uppercase text-ash/60 dark:text-silver font-bold">Investment (ZAR)</label>
            <div class="relative group">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gold/60 group-focus-within:text-gold transition-colors">
                    <span class="text-sm">R</span>
                </div>
                <input
                    type="number"
                    step="0.01"
                    name="price"
                    id="price"
                    value="{{ old('price', data_get($tier, 'price', '')) }}"
                    required
                    class="w-full bg-charcoal/5 dark:bg-black/40 border border-charcoal/10 dark:border-white/10 pl-10 pr-4 py-4 text-sm text-charcoal dark:text-white focus:border-gold outline-none transition-all"
                >
            </div>
            @error('price') <p class="text-red-500 text-[0.6rem] tracking-widest uppercase mt-2">{{ $message }}</p> @enderror
        </div>

        <!-- Price Suffix -->
        <div class="space-y-3">
            <label for="price_suffix" class="block text-[0.6rem] tracking-[0.4em] uppercase text-ash/60 dark:text-silver font-bold">Price Suffix</label>
            <input
                type="text"
                name="price_suffix"
                id="price_suffix"
                value="{{ old('price_suffix', data_get($tier, 'price_suffix', '/ event')) }}"
                class="w-full bg-charcoal/5 dark:bg-black/40 border border-charcoal/10 dark:border-white/10 p-4 text-sm text-charcoal dark:text-white focus:border-gold outline-none transition-all placeholder:italic"
                placeholder="/ event"
            >
            @error('price_suffix') <p class="text-red-500 text-[0.6rem] tracking-widest uppercase mt-2">{{ $message }}</p> @enderror
        </div>

        <!-- Order -->
        <div class="space-y-3">
            <label for="order" class="block text-[0.6rem] tracking-[0.4em] uppercase text-ash/60 dark:text-silver font-bold">Display Order</label>
            <input
                type="number"
                name="order"
                id="order"
                value="{{ old('order', data_get($tier, 'order', 0)) }}"
                class="w-full bg-charcoal/5 dark:bg-black/40 border border-charcoal/10 dark:border-white/10 p-4 text-sm text-charcoal dark:text-white focus:border-gold outline-none transition-all"
            >
            @error('order') <p class="text-red-500 text-[0.6rem] tracking-widest uppercase mt-2">{{ $message }}</p> @enderror
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
        <!-- Featured Toggle -->
        <div class="space-y-3">
            <label class="block text-[0.6rem] tracking-[0.4em] uppercase text-ash/60 dark:text-silver font-bold">Featured Tier</label>
            <div class="flex items-center gap-3">
                <input
                    type="checkbox"
                    name="is_featured"
                    id="is_featured"
                    value="1"
                    class="w-4 h-4 border border-charcoal/30 dark:border-white/30 rounded-sm bg-charcoal/40 text-gold focus:ring-gold"
                    {{ old('is_featured', data_get($tier, 'is_featured', false)) ? 'checked' : '' }}
                >
                <label for="is_featured" class="text-xs text-ash/70 dark:text-ash">Highlight this tier as the primary recommendation.</label>
            </div>
            @error('is_featured') <p class="text-red-500 text-[0.6rem] tracking-widest uppercase mt-2">{{ $message }}</p> @enderror
        </div>

        <!-- Badge Label -->
        <div class="space-y-3">
            <label for="badge_label" class="block text-[0.6rem] tracking-[0.4em] uppercase text-ash/60 dark:text-silver font-bold">Badge Label</label>
            <input
                type="text"
                name="badge_label"
                id="badge_label"
                value="{{ old('badge_label', data_get($tier, 'badge_label', '')) }}"
                class="w-full bg-charcoal/5 dark:bg-black/40 border border-charcoal/10 dark:border-white/10 p-4 text-sm text-charcoal dark:text-white focus:border-gold outline-none transition-all placeholder:italic"
                placeholder="e.g. Most Popular"
            >
            @error('badge_label') <p class="text-red-500 text-[0.6rem] tracking-widest uppercase mt-2">{{ $message }}</p> @enderror
        </div>
    </div>

    <!-- Features -->
    <div class="space-y-6">
        <label class="block text-[0.6rem] tracking-[0.4em] uppercase text-ash/60 dark:text-silver font-bold">Included Features</label>
        <div id="tier-features-container" class="space-y-4">
            @php
                $features = old('features', data_get($tier, 'features', ['']));
            @endphp
            @foreach($features as $index => $feature)
                <div class="flex gap-4 feature-item group reveal" style="animation-delay: {{ $index * 50 }}ms;">
                    <div class="flex-1 relative">
                        <input
                            type="text"
                            name="features[]"
                            value="{{ $feature }}"
                            class="w-full bg-charcoal/5 dark:bg-black/40 border border-charcoal/10 dark:border-white/10 p-4 text-sm text-charcoal dark:text-white focus:border-gold outline-none transition-all"
                            placeholder="e.g. 8 hours photography coverage"
                        >
                        <div class="absolute bottom-0 left-0 w-0 h-px bg-gold group-focus-within:w-full transition-all duration-500"></div>
                    </div>
                    <button
                        type="button"
                        onclick="this.parentElement.remove()"
                        class="px-4 text-red-500/40 hover:text-red-500 transition-colors text-xl"
                    >×</button>
                </div>
            @endforeach
        </div>
        <button
            type="button"
            onclick="addTierFeature()"
            class="inline-flex items-center gap-2 text-[0.6rem] tracking-[0.3em] uppercase text-gold hover:text-white transition-colors duration-300"
        >
            <span class="text-lg">+</span> Add Feature Line
        </button>
    </div>
</div>

<script>
    function addTierFeature() {
        const container = document.getElementById('tier-features-container');
        const div = document.createElement('div');
        div.className = 'flex gap-4 feature-item group reveal animate-hero-in';
        div.innerHTML = `
            <div class="flex-1 relative">
                <input
                    type="text"
                    name="features[]"
                    class="w-full bg-charcoal/5 dark:bg-black/40 border border-charcoal/10 dark:border-white/10 p-4 text-sm text-charcoal dark:text-white focus:border-gold outline-none transition-all"
                    placeholder="Define a feature..."
                >
                <div class="absolute bottom-0 left-0 w-0 h-px bg-gold group-focus-within:w-full transition-all duration-500"></div>
            </div>
            <button
                type="button"
                onclick="this.parentElement.remove()"
                class="px-4 text-red-500/40 hover:text-red-500 transition-colors text-xl"
            >×</button>
        `;
        container.appendChild(div);
    }
</script>

