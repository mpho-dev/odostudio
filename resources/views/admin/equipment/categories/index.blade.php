@extends('layouts.app')

@section('header', 'Equipment Categories')

@section('content')
<div class="space-y-12 pb-12">
    <!-- Header -->
    <div class="flex flex-col md:flex-row justify-between items-end gap-6 reveal">
        <div>
            <div class="flex items-center gap-4 text-[0.62rem] tracking-[0.4em] uppercase text-gold mb-4">
                <span>Classification</span>
                <div class="h-px w-16 bg-gold/30"></div>
            </div>
            <h1 class="font-serif text-4xl text-charcoal dark:text-white leading-tight">Equipment <em>Categories</em></h1>
            <p class="text-xs tracking-[0.3em] uppercase text-ash/60 dark:text-ash/60 mt-2 italic">Organize and classify your equipment inventory</p>
        </div>
        <a href="{{ route('admin.equipment.items.index') }}" class="group relative px-8 py-4 border border-charcoal/10 dark:border-white/10 text-charcoal/70 dark:text-silver text-[0.65rem] tracking-[0.3em] uppercase font-bold hover:border-gold hover:text-gold transition-all duration-500">
            <span class="relative z-10">← Back to Equipment</span>
        </a>
    </div>

    <!-- Add Category Form -->
    <div class="glass rounded-xl p-8 border border-charcoal/10 dark:border-white/10 reveal">
        <form method="POST" action="{{ route('admin.equipment.categories.store') }}" class="space-y-6">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="md:col-span-2">
                    <label class="block text-[0.6rem] tracking-[0.3em] uppercase text-gold font-bold mb-2">Category Name</label>
                    <input type="text" name="name" placeholder="e.g. Cameras, Lighting, Audio..." required class="w-full bg-charcoal/5 dark:bg-white/5 border border-charcoal/10 dark:border-white/10 rounded px-4 py-3 text-charcoal dark:text-silver focus:border-gold outline-none transition-colors">
                </div>
                <div>
                    <label class="block text-[0.6rem] tracking-[0.3em] uppercase text-gold font-bold mb-2">Icon (Optional)</label>
                    <input type="text" name="icon" placeholder="📷" class="w-full bg-charcoal/5 dark:bg-white/5 border border-charcoal/10 dark:border-white/10 rounded px-4 py-3 text-charcoal dark:text-silver focus:border-gold outline-none transition-colors">
                </div>
            </div>
            <div class="flex justify-end">
                <button type="submit" class="group relative px-8 py-4 bg-gold text-black text-[0.65rem] tracking-[0.3em] uppercase font-bold hover:bg-white transition-all duration-500 overflow-hidden">
                    <span class="relative z-10">+ Create Category</span>
                    <div class="absolute inset-0 bg-white translate-x-[-100%] group-hover:translate-x-0 transition-transform duration-500"></div>
                </button>
            </div>
        </form>
    </div>

    <!-- Categories Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($categories as $category)
            <div class="glass group relative p-8 rounded-xl border border-charcoal/10 dark:border-white/10 transition-all duration-500 hover:border-gold/30 hover:shadow-2xl hover:shadow-gold/5 reveal">
                <!-- Category Header -->
                <div class="flex justify-between items-start mb-6">
                    <div class="flex items-center gap-4">
                        @if($category->icon)
                            <div class="w-12 h-12 border border-gold/20 flex items-center justify-center text-2xl group-hover:border-gold/50 transition-colors">
                                <span class="opacity-80 group-hover:opacity-100 transition-opacity">
                                    {{ $category->icon }}
                                </span>
                            </div>
                        @else
                            <div class="w-12 h-12 border border-gold/20 flex items-center justify-center text-2xl group-hover:border-gold/50 transition-colors">
                                <span class="opacity-80 group-hover:opacity-100 transition-opacity">
                                    📦
                                </span>
                            </div>
                        @endif
                        <div>
                            <h3 class="font-serif text-2xl text-charcoal dark:text-white group-hover:text-gold transition-colors">{{ $category->name }}</h3>
                            <p class="text-[0.6rem] tracking-[0.2em] uppercase text-gold/70 mt-1">{{ $category->slug }}</p>
                        </div>
                    </div>
                    <span class="text-[0.6rem] tracking-[0.4em] uppercase text-gold/40 border border-gold/10 px-2 py-1">
                        {{ $category->items_count }} {{ $category->items_count == 1 ? 'Item' : 'Items' }}
                    </span>
                </div>

                <!-- Category Stats -->
                <div class="mb-6 pt-6 border-t border-charcoal/10 dark:border-white/10">
                    <div class="flex justify-between items-center">
                        <div>
                            <p class="text-[0.55rem] tracking-[0.3em] uppercase text-ash/40">Total Equipment</p>
                            <p class="font-serif text-xl text-charcoal dark:text-white">{{ $category->items_count }}</p>
                        </div>
                        @if($category->updated_at)
                            <div class="text-right">
                                <p class="text-[0.55rem] tracking-[0.3em] uppercase text-ash/40">Last Modified</p>
                                <p class="text-sm text-silver">{{ $category->updated_at->format('M d, Y') }}</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Actions -->
                <div class="flex gap-4 pt-4 border-t border-charcoal/10 dark:border-white/10">
                    <a href="{{ route('admin.equipment.items.index', ['category' => $category->id]) }}" class="flex-1 text-center py-2.5 border border-charcoal/10 dark:border-white/10 text-[0.6rem] tracking-[0.3em] uppercase hover:border-gold hover:text-gold transition-all text-charcoal/70 dark:text-silver font-bold">View Items</a>
                    
                    @if($category->items_count === 0)
                        <button type="button" onclick="document.getElementById('delete-category-{{ $category->id }}').classList.remove('hidden')" class="flex-1 text-center py-2.5 border border-red-500/20 text-[0.6rem] tracking-[0.3em] uppercase text-red-500/60 hover:bg-red-500/5 hover:text-red-500 transition-all font-bold">Delete</button>
                        <x-confirm-modal
                            id="delete-category-{{ $category->id }}"
                            title="Delete Category"
                            message="Delete this category? This action cannot be undone."
                            confirm-label="Delete"
                            :route="route('admin.equipment.categories.destroy', $category)"
                            method="DELETE"
                            variant="danger"
                        />
                    @else
                        <div class="flex-1 text-center py-2.5 border border-charcoal/10 dark:border-white/10 text-[0.6rem] tracking-[0.3em] uppercase text-ash/40 font-bold italic cursor-not-allowed select-none">Protected</div>
                    @endif
                </div>
            </div>
        @empty
            <div class="col-span-full py-24 text-center glass rounded-xl border-dashed border-white/10">
                <p class="font-serif text-xl italic text-silver/40">No categories have been created yet.</p>
                <p class="text-sm text-ash/60 mt-2">Create your first category to start organizing your equipment inventory.</p>
            </div>
        @endforelse
    </div>
</div>
@endsection
