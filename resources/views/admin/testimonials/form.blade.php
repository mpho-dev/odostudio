<div class="space-y-10">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
        <!-- Client Name -->
        <div class="space-y-3">
            <label for="client_name" class="block text-[0.6rem] tracking-[0.4em] uppercase text-ash/60 dark:text-silver font-bold">Client Name</label>
            <input
                type="text"
                name="client_name"
                id="client_name"
                value="{{ old('client_name', data_get($testimonial, 'client_name', '')) }}"
                required
                class="w-full bg-charcoal/5 dark:bg-black/40 border border-charcoal/10 dark:border-white/10 p-4 text-sm text-charcoal dark:text-white focus:border-gold outline-none transition-all placeholder:italic"
                placeholder="e.g. Lungelo &amp; Noluthando M."
            >
            @error('client_name') <p class="text-red-500 text-[0.6rem] tracking-widest uppercase mt-2">{{ $message }}</p> @enderror
        </div>

        <!-- Client Initials -->
        <div class="space-y-3">
            <label for="client_initials" class="block text-[0.6rem] tracking-[0.4em] uppercase text-ash/60 dark:text-silver font-bold">Avatar Initials</label>
            <input
                type="text"
                name="client_initials"
                id="client_initials"
                value="{{ old('client_initials', data_get($testimonial, 'client_initials', '')) }}"
                class="w-full bg-charcoal/5 dark:bg-black/40 border border-charcoal/10 dark:border-white/10 p-4 text-sm text-charcoal dark:text-white focus:border-gold outline-none transition-all placeholder:italic"
                placeholder="Auto-derived if left blank"
            >
            @error('client_initials') <p class="text-red-500 text-[0.6rem] tracking-widest uppercase mt-2">{{ $message }}</p> @enderror
        </div>
    </div>

    <!-- Event Label -->
    <div class="space-y-3">
        <label for="event_label" class="block text-[0.6rem] tracking-[0.4em] uppercase text-ash/60 dark:text-silver font-bold">Event Label</label>
        <input
            type="text"
            name="event_label"
            id="event_label"
            value="{{ old('event_label', data_get($testimonial, 'event_label', '')) }}"
            class="w-full bg-charcoal/5 dark:bg-black/40 border border-charcoal/10 dark:border-white/10 p-4 text-sm text-charcoal dark:text-white focus:border-gold outline-none transition-all placeholder:italic"
            placeholder="e.g. Wedding · Mpumalanga"
        >
        @error('event_label') <p class="text-red-500 text-[0.6rem] tracking-widest uppercase mt-2">{{ $message }}</p> @enderror
    </div>

    <!-- Quote -->
    <div class="space-y-3">
        <label for="quote" class="block text-[0.6rem] tracking-[0.4em] uppercase text-ash/60 dark:text-silver font-bold">Client Narrative</label>
        <textarea
            name="quote"
            id="quote"
            rows="4"
            required
            class="w-full bg-charcoal/5 dark:bg-black/40 border border-charcoal/10 dark:border-white/10 p-4 text-sm text-charcoal dark:text-white focus:border-gold outline-none transition-all placeholder:text-ash/40 dark:placeholder:text-silver/40 font-serif text-lg leading-relaxed italic"
            placeholder="The story in their own words..."
        >{{ old('quote', data_get($testimonial, 'quote', '')) }}</textarea>
        @error('quote') <p class="text-red-500 text-[0.6rem] tracking-widest uppercase mt-2">{{ $message }}</p> @enderror
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-10">
        <!-- Rating -->
        <div class="space-y-3">
            <label for="rating" class="block text-[0.6rem] tracking-[0.4em] uppercase text-ash/60 dark:text-silver font-bold">Rating (Stars)</label>
            <input
                type="number"
                name="rating"
                id="rating"
                min="1"
                max="5"
                value="{{ old('rating', data_get($testimonial, 'rating', 5)) }}"
                required
                class="w-full bg-charcoal/5 dark:bg-black/40 border border-charcoal/10 dark:border-white/10 p-4 text-sm text-charcoal dark:text-white focus:border-gold outline-none transition-all"
            >
            @error('rating') <p class="text-red-500 text-[0.6rem] tracking-widest uppercase mt-2">{{ $message }}</p> @enderror
        </div>

        <!-- Order -->
        <div class="space-y-3">
            <label for="order" class="block text-[0.6rem] tracking-[0.4em] uppercase text-ash/60 dark:text-silver font-bold">Display Order</label>
            <input
                type="number"
                name="order"
                id="order"
                value="{{ old('order', data_get($testimonial, 'order', 0)) }}"
                class="w-full bg-charcoal/5 dark:bg-black/40 border border-charcoal/10 dark:border-white/10 p-4 text-sm text-charcoal dark:text-white focus:border-gold outline-none transition-all"
            >
            @error('order') <p class="text-red-500 text-[0.6rem] tracking-widest uppercase mt-2">{{ $message }}</p> @enderror
        </div>

        <!-- Featured Toggle -->
        <div class="space-y-3">
            <label class="block text-[0.6rem] tracking-[0.4em] uppercase text-ash/60 dark:text-silver font-bold">Featured</label>
            <div class="flex items-center gap-3">
                <input
                    type="checkbox"
                    name="is_featured"
                    id="is_featured"
                    value="1"
                    class="w-4 h-4 border border-charcoal/30 dark:border-white/30 rounded-sm bg-charcoal/40 text-gold focus:ring-gold"
                    {{ old('is_featured', data_get($testimonial, 'is_featured', false)) ? 'checked' : '' }}
                >
                <label for="is_featured" class="text-xs text-ash/70 dark:text-ash">Pin this to the hero row of testimonials.</label>
            </div>
            @error('is_featured') <p class="text-red-500 text-[0.6rem] tracking-widest uppercase mt-2">{{ $message }}</p> @enderror
        </div>
    </div>
</div>

