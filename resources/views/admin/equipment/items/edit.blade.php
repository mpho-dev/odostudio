@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-charcoal">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="flex items-center gap-4 mb-8">
            <a href="{{ route('admin.equipment.items.index') }}" class="text-ash hover:text-white transition">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
            </a>
            <h1 class="font-serif text-2xl text-white">Edit Equipment</h1>
        </div>

        <div class="glass rounded-lg p-8">
            <form method="POST" action="{{ route('admin.equipment.items.update', $item) }}" enctype="multipart/form-data">
                @csrf
                @method('PATCH')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label class="block text-sm font-medium text-ash mb-2">Category</label>
                        <select name="category_id" required class="w-full bg-charcoal/80 border border-white/10 rounded px-4 py-2 text-white focus:border-gold outline-none transition-all">
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ $item->category_id == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-ash mb-2">Name</label>
                        <input type="text" name="name" value="{{ $item->name }}" required class="w-full bg-charcoal/80 border border-white/10 rounded px-4 py-2 text-white focus:border-gold outline-none transition-all">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-ash mb-2">Model</label>
                        <input type="text" name="model" value="{{ $item->model }}" class="w-full bg-charcoal/80 border border-white/10 rounded px-4 py-2 text-white focus:border-gold outline-none transition-all">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-ash mb-2">Serial Number</label>
                        <input type="text" name="serial_number" value="{{ $item->serial_number }}" class="w-full bg-charcoal/80 border border-white/10 rounded px-4 py-2 text-white focus:border-gold outline-none transition-all">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-ash mb-2">SKU</label>
                        <input type="text" name="sku" value="{{ $item->sku }}" class="w-full bg-charcoal/80 border border-white/10 rounded px-4 py-2 text-white focus:border-gold outline-none transition-all">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-ash mb-2">Storage Location</label>
                        <input type="text" name="storage_location" value="{{ $item->storage_location }}" class="w-full bg-charcoal/80 border border-white/10 rounded px-4 py-2 text-white focus:border-gold outline-none transition-all">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-ash mb-2">Status</label>
                        <select name="status" class="w-full bg-charcoal/80 border border-white/10 rounded px-4 py-2 text-white focus:border-gold outline-none transition-all">
                            <option value="available" {{ $item->status == 'available' ? 'selected' : '' }}>Available</option>
                            <option value="maintenance" {{ $item->status == 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                            <option value="retired" {{ $item->status == 'retired' ? 'selected' : '' }}>Retired</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-ash mb-2">Condition</label>
                        <select name="condition" class="w-full bg-charcoal/80 border border-white/10 rounded px-4 py-2 text-white focus:border-gold outline-none transition-all">
                            <option value="excellent" {{ $item->condition == 'excellent' ? 'selected' : '' }}>Excellent</option>
                            <option value="good" {{ $item->condition == 'good' ? 'selected' : '' }}>Good</option>
                            <option value="fair" {{ $item->condition == 'fair' ? 'selected' : '' }}>Fair</option>
                            <option value="poor" {{ $item->condition == 'poor' ? 'selected' : '' }}>Poor</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-ash mb-2">Purchase Price</label>
                        <input type="number" name="purchase_price" value="{{ $item->purchase_price }}" step="0.01" class="w-full bg-charcoal/80 border border-white/10 rounded px-4 py-2 text-white focus:border-gold outline-none transition-all">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-ash mb-2">Purchase Date</label>
                        <input type="date" name="purchase_date" value="{{ $item->purchase_date?->format('Y-m-d') }}" class="w-full bg-charcoal/80 border border-white/10 rounded px-4 py-2 text-white focus:border-gold outline-none transition-all">
                    </div>
                </div>

                <div class="mb-6">
                    <label class="block text-sm font-medium text-ash mb-2">Description</label>
                    <textarea name="description" rows="3" class="w-full bg-charcoal/80 border border-white/10 rounded px-4 py-2 text-white focus:border-gold outline-none transition-all">{{ $item->description }}</textarea>
                </div>

                <div class="mb-6">
                    <label class="block text-sm font-medium text-ash mb-2">Notes</label>
                    <textarea name="notes" rows="3" class="w-full bg-charcoal/80 border border-white/10 rounded px-4 py-2 text-white focus:border-gold outline-none transition-all">{{ $item->notes }}</textarea>
                </div>

                @if($item->images->count() > 0)
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-ash mb-2">Current Images</label>
                        <div class="grid grid-cols-4 gap-4">
                            @foreach($item->images as $image)
                                <div class="relative">
                                    <img src="{{ $image->image_path }}" alt="" class="w-full aspect-square object-cover rounded">
                                    <label class="absolute top-2 left-2 bg-black/60 px-2 py-1 rounded text-xs text-white cursor-pointer">
                                        <input type="checkbox" name="remove_images[]" value="{{ $image->id }}" class="mr-1"> Remove
                                    </label>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <div class="mb-6">
                    <label class="block text-sm font-medium text-ash mb-2">Add New Images</label>
                    <input type="file" name="images[]" multiple accept="image/*" class="w-full bg-charcoal/80 border border-white/10 rounded px-4 py-2 text-white focus:border-gold outline-none transition-all">
                </div>

                <div class="flex justify-end">
                    <button type="submit" class="bg-gold text-black px-6 py-2 rounded hover:bg-gold/80 transition">
                        Update Equipment
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
