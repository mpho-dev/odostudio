@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-black">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="flex items-center gap-4 mb-8">
            <a href="{{ route('bookings.index') }}" class="text-ash hover:text-gold transition-colors">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
            </a>
            <div>
                <h1 class="font-serif text-2xl text-white">Assign Equipment</h1>
                <p class="text-ash">Booking #{{ $booking->id }} - {{ $booking->bookingRequest?->event_type }}</p>
            </div>
        </div>

        @if($assigned->count() > 0)
            <div class="glass rounded-lg p-6 mb-6">
                <h2 class="font-serif text-lg text-white mb-4">Currently Assigned</h2>
                <div class="space-y-3">
                    @foreach($assigned as $checkout)
                        <div class="flex justify-between items-center glass rounded p-4 border border-white/10">
                            <div>
                                <p class="text-white font-medium">{{ $checkout->equipmentItem->name }}</p>
                                <p class="text-sm text-ash">{{ $checkout->equipmentItem->category->name }} | Assigned to: {{ $checkout->user->name }}</p>
                            </div>
                            <form method="POST" action="{{ route('manager.equipment.remove', $checkout) }}">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-400 hover:text-red-300 text-sm">Remove</button>
                            </form>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        @if($suggested->count() > 0)
            <div class="glass rounded-lg p-6 mb-6">
                <h2 class="font-serif text-lg text-white mb-4">Suggested Equipment</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @foreach($suggested as $item)
                        <div class="glass rounded p-4 border border-white/10 flex justify-between items-center">
                            <div>
                                <p class="text-white font-medium">{{ $item->name }}</p>
                                <p class="text-sm text-ash">{{ $item->category->name }}</p>
                                <span class="text-xs px-2 py-1 rounded bg-gold/10 text-gold border border-gold/20 mt-1 inline-block">Available</span>
                            </div>
                            <form method="POST" action="{{ route('manager.equipment.store', $booking) }}">
                                @csrf
                                <input type="hidden" name="equipment_item_id" value="{{ $item->id }}">
                                <input type="hidden" name="user_id" value="{{ $booking->photographer_id ?? auth()->id() }}">
                                <button type="submit" class="bg-gold text-black px-3 py-1 rounded text-sm hover:bg-white transition-all duration-300">Assign</button>
                            </form>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <div class="glass rounded-lg p-6">
            <h2 class="font-serif text-lg text-white mb-4">All Available Equipment</h2>
            @foreach($available as $categoryName => $items)
                <div class="mb-6">
                    <h3 class="text-ash font-medium mb-3">{{ $categoryName }}</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        @foreach($items as $item)
                            <div class="glass rounded p-4 border border-white/10">
                                <div class="flex justify-between items-start mb-2">
                                    <p class="text-white font-medium">{{ $item->name }}</p>
                                    <span class="text-xs px-2 py-1 rounded bg-gold/10 text-gold border border-gold/20">Available</span>
                                </div>
                                <p class="text-sm text-ash mb-3">{{ $item->model ?? 'No model' }}</p>
                                <form method="POST" action="{{ route('manager.equipment.store', $booking) }}">
                                    @csrf
                                    <input type="hidden" name="equipment_item_id" value="{{ $item->id }}">
                                    <select name="user_id" class="w-full bg-charcoal/80 border border-white/10 rounded px-3 py-2 text-white text-sm mb-2">
                                        @foreach($booking->crew as $crewMember)
                                            <option value="{{ $crewMember->id }}">{{ $crewMember->name }} ({{ $crewMember->pivot->role }})</option>
                                        @endforeach
                                        @if($booking->photographer)
                                            <option value="{{ $booking->photographer->id }}" selected>{{ $booking->photographer->name }} (Photographer)</option>
                                        @endif
                                    </select>
                                    <button type="submit" class="w-full bg-gold text-black px-3 py-2 rounded text-sm hover:bg-white transition-all duration-300">Assign to Booking</button>
                                </form>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
@endsection
