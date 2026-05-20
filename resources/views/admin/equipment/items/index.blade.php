@extends('layouts.app')

@section('header', 'Equipment Inventory')

@section('content')
<div class="space-y-12 pb-12">
    <!-- Header -->
    <div class="flex flex-col md:flex-row justify-between items-end gap-6 reveal">
        <div>
            <div class="flex items-center gap-4 text-[0.62rem] tracking-[0.4em] uppercase text-gold mb-4">
                <span>Asset Management</span>
                <div class="h-px w-16 bg-gold/30"></div>
            </div>
            <h1 class="font-serif text-4xl text-charcoal dark:text-white leading-tight">Equipment <em>Inventory</em></h1>
            <p class="text-xs tracking-[0.3em] uppercase text-ash/60 dark:text-ash/60 mt-2 italic">Manage and monitor your production equipment fleet</p>
        </div>
        <a href="{{ route('admin.equipment.items.create') }}" class="group relative px-8 py-4 bg-gold text-black text-[0.65rem] tracking-[0.3em] uppercase font-bold hover:bg-white transition-all duration-500 overflow-hidden">
            <span class="relative z-10">+ Add Equipment</span>
            <div class="absolute inset-0 bg-white translate-x-[-100%] group-hover:translate-x-0 transition-transform duration-500"></div>
        </a>
    </div>

    <!-- Filters -->
    <div class="glass rounded-xl p-8 border border-charcoal/10 dark:border-white/10 reveal shadow-2xl relative overflow-hidden group">
        <div class="absolute top-0 left-0 w-8 h-8 border-t border-l border-gold/40 group-hover:w-12 group-hover:h-12 transition-all duration-500"></div>
        <div class="absolute bottom-0 right-0 w-8 h-8 border-b border-r border-gold/40 group-hover:w-12 group-hover:h-12 transition-all duration-500"></div>
        <form method="GET" class="flex flex-wrap gap-4">
            <div class="flex-1 min-w-[200px]">
                <label class="block text-[0.6rem] tracking-[0.3em] uppercase text-gold font-bold mb-2">Search Equipment</label>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by name, serial, SKU..." class="w-full bg-charcoal/5 dark:bg-white/5 border border-charcoal/10 dark:border-white/10 rounded px-4 py-3 text-charcoal dark:text-silver focus:border-gold outline-none transition-colors">
            </div>
            <div class="min-w-[150px]">
                <label class="block text-[0.6rem] tracking-[0.3em] uppercase text-gold font-bold mb-2">Category</label>
                <select name="category" class="w-full bg-charcoal/5 dark:bg-white/5 border border-charcoal/10 dark:border-white/10 rounded px-4 py-3 text-charcoal dark:text-silver focus:border-gold outline-none transition-colors">
                    <option value="">All Categories</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="min-w-[150px]">
                <label class="block text-[0.6rem] tracking-[0.3em] uppercase text-gold font-bold mb-2">Status</label>
                <select name="status" class="w-full bg-charcoal/5 dark:bg-white/5 border border-charcoal/10 dark:border-white/10 rounded px-4 py-3 text-charcoal dark:text-silver focus:border-gold outline-none transition-colors">
                    <option value="">All Status</option>
                    <option value="available" {{ request('status') == 'available' ? 'selected' : '' }}>Available</option>
                    <option value="checked_out" {{ request('status') == 'checked_out' ? 'selected' : '' }}>Checked Out</option>
                    <option value="maintenance" {{ request('status') == 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                </select>
            </div>
            <div class="flex items-end">
                <button type="submit" class="px-6 py-3 bg-white/10 border border-white/20 text-white text-[0.6rem] tracking-[0.3em] uppercase font-bold hover:bg-white/20 transition-all">Filter Results</button>
            </div>
        </form>
    </div>

    <!-- Equipment Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($items as $item)
            <div class="glass group relative overflow-hidden rounded-xl border border-charcoal/10 dark:border-white/10 transition-all duration-500 hover:border-gold/30 hover:shadow-2xl hover:shadow-gold/5 flex flex-col h-full reveal shadow-2xl relative overflow-hidden">
                <div class="absolute top-0 left-0 w-8 h-8 border-t border-l border-gold/40 group-hover:w-12 group-hover:h-12 transition-all duration-500"></div>
                <div class="absolute bottom-0 right-0 w-8 h-8 border-b border-r border-gold/40 group-hover:w-12 group-hover:h-12 transition-all duration-500"></div>
                <!-- Equipment Image -->
                <div class="h-48 bg-charcoal dark:bg-charcoal overflow-hidden">
                    @if($item->primaryImage)
                        <img src="{{ $item->primaryImage->image_path }}" alt="{{ $item->name }}" class="w-full h-full object-cover opacity-70 group-hover:scale-105 transition-transform duration-700">
                    @else
                        <div class="w-full h-full bg-gradient-to-br from-charcoal to-graphite grid place-items-center">
                            <svg class="w-16 h-16 text-ash/30" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                            </svg>
                        </div>
                    @endif
                </div>

                <!-- Equipment Info -->
                <div class="p-6 flex-1 flex flex-col">
                    <div class="flex justify-between items-start mb-4">
                        <div class="flex-1">
                            <h3 class="font-serif text-xl text-charcoal dark:text-white mb-2 group-hover:text-gold transition-colors leading-tight">{{ $item->name }}</h3>
                            <p class="text-[0.65rem] tracking-[0.2em] uppercase text-gold/70">{{ $item->category->name }}</p>
                        </div>
                        <span class="text-xs px-3 py-1 rounded-full border {{ $item->status === 'available' ? 'bg-green-500/10 text-green-400 border-green-500/20' : ($item->status === 'checked_out' ? 'bg-yellow-500/10 text-yellow-400 border-yellow-500/20' : 'bg-red-500/10 text-red-400 border-red-500/20') }}">
                            {{ ucfirst(str_replace('_', ' ', $item->status)) }}
                        </span>
                    </div>
                    
                    <div class="mb-4">
                        @if($item->model)
                            <p class="text-sm text-ash/80 dark:text-silver mb-2">{{ $item->model }}</p>
                        @endif
                        @if($item->serial_number)
                            <p class="text-xs text-ash/60 dark:text-ash/40">Serial: {{ $item->serial_number }}</p>
                        @endif
                    </div>

                    <div class="mt-auto pt-4 border-t border-charcoal/10 dark:border-white/10">
                        <div class="flex justify-between items-center mb-4">
                            <div>
                                <p class="text-[0.55rem] tracking-[0.3em] uppercase text-ash/40">Usage History</p>
                                <p class="text-sm text-charcoal dark:text-silver font-medium">{{ $item->checkouts_count }} Checkouts</p>
                            </div>
                            @if($item->condition)
                                <div class="text-right">
                                    <p class="text-[0.55rem] tracking-[0.3em] uppercase text-ash/40">Condition</p>
                                    <p class="text-sm text-gold font-medium">{{ ucfirst($item->condition) }}</p>
                                </div>
                            @endif
                        </div>

                        <div class="flex gap-3">
                            <a href="{{ route('admin.equipment.items.show', $item) }}" class="flex-1 text-center py-2.5 border border-charcoal/10 dark:border-white/10 text-[0.6rem] tracking-[0.3em] uppercase hover:border-gold hover:text-gold transition-all text-charcoal/70 dark:text-silver font-bold">View Details</a>
                            <a href="{{ route('admin.equipment.items.edit', $item) }}" class="flex-1 text-center py-2.5 border border-charcoal/10 dark:border-white/10 text-[0.6rem] tracking-[0.3em] uppercase hover:border-gold hover:text-gold transition-all text-charcoal/70 dark:text-silver font-bold">Edit</a>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full py-24 text-center glass rounded-xl border-dashed border-white/10">
                <p class="font-serif text-xl italic text-silver/40">No equipment found in inventory.</p>
                <a href="{{ route('admin.equipment.items.create') }}" class="mt-4 inline-block text-[0.6rem] tracking-widest uppercase text-gold hover:underline">Add your first equipment item</a>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($items->hasPages())
        <div class="pagination-container mt-8 reveal">
            {{ $items->links() }}
        </div>
    @endif
</div>
@endsection
