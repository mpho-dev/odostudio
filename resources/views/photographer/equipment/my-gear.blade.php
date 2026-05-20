@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-black">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <h1 class="font-serif text-2xl text-white mb-8">My Gear</h1>

        @if($current->count() > 0)
            <div class="glass rounded-lg p-6 mb-8">
                <h2 class="font-serif text-lg text-white mb-4">Currently Checked Out</h2>
                <div class="space-y-4">
                    @foreach($current as $item)
                        <div class="glass rounded p-4 flex justify-between items-center border border-white/10">
                            <div class="flex items-center gap-4">
                                @if($item->primaryImage)
                                    <img src="{{ $item->primaryImage->image_path }}" alt="" class="w-16 h-16 object-cover rounded">
                                @else
                                    <div class="w-16 h-16 bg-charcoal/80 rounded flex items-center justify-center">
                                        <svg class="w-8 h-8 text-white/20" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                    </div>
                                @endif
                                <div>
                                    <p class="text-white font-medium">{{ $item->name }}</p>
                                    <p class="text-sm text-ash">{{ $item->category->name }}</p>
                                    <p class="text-xs text-ash/60">Return by: {{ \Carbon\Carbon::parse($item->pivot->expected_return_at)->format('M d, Y') }}</p>
                                </div>
                            </div>
                            @php
                                $checkout = $item->checkouts->firstWhere('status', 'active');
                            @endphp
                            @if($checkout)
                                <form method="POST" action="{{ route('photographer.equipment.checkin', $checkout) }}">
                                    @csrf
                                    @method('PATCH')
                                    <select name="condition_in" required class="bg-charcoal/80 border border-white/10 rounded px-3 py-2 text-white text-sm mb-2">
                                        <option value="">Condition...</option>
                                        <option value="excellent">Excellent</option>
                                        <option value="good">Good</option>
                                        <option value="fair">Fair</option>
                                        <option value="poor">Poor</option>
                                        <option value="damaged">Damaged</option>
                                    </select>
                                    <button type="submit" class="block w-full bg-gold text-black px-4 py-2 rounded text-sm hover:bg-white transition-all duration-300">Check In</button>
                                </form>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        @else
            <div class="glass rounded-lg p-8 text-center mb-8">
                <p class="text-ash">No equipment currently checked out.</p>
            </div>
        @endif

        @if($history->count() > 0)
            <div class="glass rounded-lg p-6">
                <h2 class="font-serif text-lg text-white mb-4">Equipment History</h2>
                <div class="space-y-3">
                    @foreach($history as $item)
                        <div class="flex justify-between items-center glass rounded p-4 border border-white/10">
                            <div class="flex items-center gap-4">
                                @if($item->primaryImage)
                                    <img src="{{ $item->primaryImage->image_path }}" alt="" class="w-12 h-12 object-cover rounded">
                                @else
                                    <div class="w-12 h-12 bg-charcoal/80 rounded flex items-center justify-center">
                                        <svg class="w-6 h-6 text-white/20" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                    </div>
                                @endif
                                <div>
                                    <p class="text-white font-medium">{{ $item->name }}</p>
                                    <p class="text-sm text-ash">{{ $item->category->name }}</p>
                                </div>
                            </div>
                            <span class="text-xs text-ash">Returned {{ \Carbon\Carbon::parse($item->pivot->returned_at)->format('M d, Y') }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
