<div class="space-y-10">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
        <!-- Step Number -->
        <div class="space-y-3">
            <label for="step_number" class="block text-[0.6rem] tracking-[0.4em] uppercase text-ash/60 dark:text-silver font-bold">Step Number</label>
            <input type="number" name="step_number" id="step_number" value="{{ old('step_number', $processStep->step_number ?? '') }}" required 
                   class="w-full bg-charcoal/5 dark:bg-black/40 border border-charcoal/10 dark:border-white/10 p-4 text-sm text-charcoal dark:text-white focus:border-gold outline-none transition-all"
                   placeholder="e.g. 1, 2, 3, 4">
            <p class="text-[0.55rem] tracking-widest uppercase text-ash/60 dark:text-ash/40 italic">A unique identifier for this step (1-4)</p>
            @error('step_number') <p class="text-red-500 text-[0.6rem] tracking-widest uppercase mt-2">{{ $message }}</p> @enderror
        </div>

        <!-- Display Order -->
        <div class="space-y-3">
            <label for="display_order" class="block text-[0.6rem] tracking-[0.4em] uppercase text-ash/60 dark:text-silver font-bold">Display Order</label>
            <input type="number" name="display_order" id="display_order" value="{{ old('display_order', $processStep->display_order ?? '') }}" 
                   class="w-full bg-charcoal/5 dark:bg-black/40 border border-charcoal/10 dark:border-white/10 p-4 text-sm text-charcoal dark:text-white focus:border-gold outline-none transition-all">
            <p class="text-[0.55rem] tracking-widest uppercase text-ash/60 dark:text-ash/40 italic">Order of appearance (optional)</p>
            @error('display_order') <p class="text-red-500 text-[0.6rem] tracking-widest uppercase mt-2">{{ $message }}</p> @enderror
        </div>
    </div>

    <!-- Title -->
    <div class="space-y-3">
        <label for="title" class="block text-[0.6rem] tracking-[0.4em] uppercase text-ash/60 dark:text-silver font-bold">Step Title</label>
        <input type="text" name="title" id="title" value="{{ old('title', $processStep->title ?? '') }}" required 
               class="w-full bg-charcoal/5 dark:bg-black/40 border border-charcoal/10 dark:border-white/10 p-4 text-sm text-charcoal dark:text-white focus:border-gold outline-none transition-all placeholder:italic"
               placeholder="e.g. Discovery Call">
        @error('title') <p class="text-red-500 text-[0.6rem] tracking-widest uppercase mt-2">{{ $message }}</p> @enderror
    </div>

    <!-- Description -->
    <div class="space-y-3">
        <label for="description" class="block text-[0.6rem] tracking-[0.4em] uppercase text-ash/60 dark:text-silver font-bold">Description</label>
        <textarea name="description" id="description" rows="4" required 
                  class="w-full bg-charcoal/5 dark:bg-black/40 border border-charcoal/10 dark:border-white/10 p-4 text-sm text-charcoal dark:text-white focus:border-gold outline-none transition-all placeholder:text-ash/40 dark:placeholder:text-silver/40 font-serif text-lg leading-relaxed italic"
                  placeholder="Describe this process step...">{{ old('description', $processStep->description ?? '') }}</textarea>
        @error('description') <p class="text-red-500 text-[0.6rem] tracking-widest uppercase mt-2">{{ $message }}</p> @enderror
    </div>
</div>
