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
            <h1 class="font-serif text-2xl text-white">Add Equipment</h1>
        </div>

        <div class="glass rounded-lg p-8">
            <form method="POST" action="{{ route('admin.equipment.items.store') }}" enctype="multipart/form-data">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label class="block text-sm font-medium text-ash mb-2">Category</label>
                        <select name="category_id" required class="w-full bg-charcoal/80 border border-white/10 rounded px-4 py-2 text-white focus:border-gold outline-none transition-all">
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-ash mb-2">Name</label>
                        <input type="text" name="name" required class="w-full bg-charcoal/80 border border-white/10 rounded px-4 py-2 text-white focus:border-gold outline-none transition-all">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-ash mb-2">Model</label>
                        <input type="text" name="model" class="w-full bg-charcoal/80 border border-white/10 rounded px-4 py-2 text-white focus:border-gold outline-none transition-all">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-ash mb-2">Serial Number</label>
                        <input type="text" name="serial_number" class="w-full bg-charcoal/80 border border-white/10 rounded px-4 py-2 text-white focus:border-gold outline-none transition-all">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-ash mb-2">SKU</label>
                        <input type="text" name="sku" class="w-full bg-charcoal/80 border border-white/10 rounded px-4 py-2 text-white focus:border-gold outline-none transition-all">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-ash mb-2">Storage Location</label>
                        <input type="text" name="storage_location" class="w-full bg-charcoal/80 border border-white/10 rounded px-4 py-2 text-white focus:border-gold outline-none transition-all">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-ash mb-2">Condition</label>
                        <select name="condition" class="w-full bg-charcoal/80 border border-white/10 rounded px-4 py-2 text-white focus:border-gold outline-none transition-all">
                            <option value="excellent">Excellent</option>
                            <option value="good">Good</option>
                            <option value="fair">Fair</option>
                            <option value="poor">Poor</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-ash mb-2">Purchase Price</label>
                        <input type="number" name="purchase_price" step="0.01" class="w-full bg-charcoal/80 border border-white/10 rounded px-4 py-2 text-white focus:border-gold outline-none transition-all">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-ash mb-2">Purchase Date</label>
                        <input type="date" name="purchase_date" class="w-full bg-charcoal/80 border border-white/10 rounded px-4 py-2 text-white focus:border-gold outline-none transition-all">
                    </div>
                </div>

                <div class="mb-6">
                    <label class="block text-sm font-medium text-ash mb-2">Description</label>
                    <textarea name="description" rows="3" class="w-full bg-charcoal/80 border border-white/10 rounded px-4 py-2 text-white focus:border-gold outline-none transition-all"></textarea>
                </div>

                <div class="mb-6">
                    <label class="block text-sm font-medium text-ash mb-2">Images</label>
                    <input type="file" name="images[]" multiple accept="image/*" class="w-full bg-charcoal/80 border border-white/10 rounded px-4 py-2 text-white focus:border-gold outline-none transition-all">
                    <p class="text-xs text-ash/50 mt-1">First image will be set as primary</p>
                </div>

                <div class="flex justify-end">
                    <button type="submit" class="bg-gold text-black px-6 py-2 rounded hover:bg-gold/80 transition">
                        Add Equipment
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
