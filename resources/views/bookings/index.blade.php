@extends('layouts.app')

@section('title', 'Shoot Schedule — Odo Studio')

@section('header')
    {{ auth()->user()->hasRole('crew') ? 'My Call Sheets' : 'Shoot Schedule' }}
@endsection

@section('content')
<div class="space-y-8 animate-hero-in pb-24 md:pb-8">
    <div class="flex justify-between items-center reveal">
        <div>
            <h1 class="font-serif text-3xl text-charcoal dark:text-white leading-tight">
                @if (auth()->user()->hasRole('crew'))
                    My <em>Shoots</em>
                @else
                    Master <em>Schedule</em>
                @endif
            </h1>
            <p class="text-xs tracking-[0.3em] uppercase text-ash/60 dark:text-ash/60 mt-1 italic">Overview of active and past productions</p>
        </div>
    </div>

    @if ($bookings->isEmpty())
        <div class="py-32 text-center glass rounded-xl border border-dashed border-charcoal/20 dark:border-white/10 reveal">
            <p class="font-serif text-xl text-ash/60 dark:text-ash/40 italic opacity-40">No productions are currently scheduled.</p>
        </div>
    @else
        <!-- Mobile Card View -->
        <div class="block md:hidden space-y-4">
            @foreach ($bookings as $booking)
                <div class="glass p-6 rounded-xl border border-charcoal/10 dark:border-white/10 relative overflow-hidden group shadow-2xl">
                    <div class="absolute top-0 left-0 w-8 h-8 border-t border-l border-gold/40 group-hover:w-12 group-hover:h-12 transition-all duration-500"></div>
                    <div class="absolute bottom-0 right-0 w-8 h-8 border-b border-r border-gold/40 group-hover:w-12 group-hover:h-12 transition-all duration-500"></div>
                    <div class="flex justify-between items-start mb-4">
                        <div>
                            <p class="font-serif text-lg text-charcoal dark:text-white leading-tight">{{ $booking->bookingRequest->name }} {{ $booking->bookingRequest->surname }}</p>
                            <p class="text-[0.6rem] tracking-widest uppercase text-ash/60 dark:text-ash/40 mt-1 italic">{{ $booking->location }}</p>
                        </div>
                        <span class="px-3 py-1 rounded-full text-[0.55rem] tracking-[0.2em] uppercase font-bold shrink-0
                            @if ($booking->status === 'scheduled' || $booking->status === 'confirmed')
                                bg-gold/10 text-gold border border-gold/20
                            @elseif ($booking->status === 'cancelled')
                                bg-red-500/10 text-red-500 border border-red-500/20
                            @elseif ($booking->status === 'completed')
                                bg-green-500/10 text-green-500 border border-green-500/20
                            @else
                                bg-charcoal/5 dark:bg-white/5 text-ash/60 dark:text-silver border border-charcoal/10 dark:border-white/10
                            @endif
                        ">
                            {{ $booking->status }}
                        </span>
                    </div>
                    
                    <div class="space-y-3 mb-4">
                        <div>
                            <p class="text-[0.55rem] tracking-[0.3em] uppercase text-gold/60">Call Time</p>
                            <p class="text-sm text-charcoal/70 dark:text-silver">{{ $booking->event_date->format('M d, Y') }} at {{ $booking->event_date->format('H:i') }}</p>
                        </div>
                        <div>
                            <p class="text-[0.55rem] tracking-[0.3em] uppercase text-gold/60">Crew</p>
                            @forelse ($booking->crew as $crewMember)
                                <p class="text-sm text-charcoal/70 dark:text-silver">
                                    {{ $crewMember->name }}
                                    @if($crewMember->crew_specialty === 'both')
                                        <span class="text-xs text-gold ml-1">⚡ Dual Role</span>
                                    @endif
                                    <span class="text-[0.5rem] tracking-[0.2em] uppercase text-ash/50 italic">({{ $crewMember->pivot->role }})</span>
                                </p>
                            @empty
                                <p class="text-sm text-ash/60 italic">TBD</p>
                            @endforelse
                        </div>
                    </div>
                    
                    @if (auth()->user()->hasRole('manager'))
                        <div class="flex flex-wrap gap-2">
                            <a href="{{ route('invoices.create', $booking) }}" class="flex-1 text-center border border-charcoal/10 dark:border-white/10 hover:border-gold hover:text-gold text-charcoal/40 dark:text-ash/40 px-4 py-2 text-[0.55rem] tracking-[0.3em] uppercase font-bold transition-all">Billing</a>
                            
                            @if ($booking->status === 'pending' || $booking->status === 'scheduled')
                                <form action="{{ route('bookings.confirm', $booking) }}" method="POST" class="flex-1">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="w-full border border-charcoal/10 dark:border-white/10 hover:border-blue-500 hover:text-blue-500 text-charcoal/40 dark:text-ash/40 px-4 py-2 text-[0.55rem] tracking-[0.3em] uppercase font-bold transition-all">Confirm</button>
                                </form>
                            @endif

                            @if ($booking->status === 'scheduled' || $booking->status === 'confirmed')
                                <form action="{{ route('bookings.complete', $booking) }}" method="POST" class="flex-1">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="w-full border border-charcoal/10 dark:border-white/10 hover:border-green-500 hover:text-green-500 text-charcoal/40 dark:text-ash/40 px-4 py-2 text-[0.55rem] tracking-[0.3em] uppercase font-bold transition-all">Wrap</button>
                                </form>
                            @endif

                            <button type="button" onclick="document.getElementById('cancel-booking-mobile-{{ $booking->id }}').classList.remove('hidden')" class="w-full border border-charcoal/10 dark:border-white/10 hover:border-red-500 hover:text-red-500 text-charcoal/40 dark:text-ash/40 px-4 py-2 text-[0.55rem] tracking-[0.3em] uppercase font-bold transition-all">Kill</button>
                            <x-confirm-modal
                                id="cancel-booking-mobile-{{ $booking->id }}"
                                title="Cancel Production"
                                message="Terminate this production sequence? This action cannot be undone."
                                confirm-label="Terminate"
                                :route="route('bookings.cancel', $booking)"
                                method="PATCH"
                                variant="danger"
                            />

                            @if ($booking->status === 'cancelled' && auth()->user()->hasRole('admin'))
                                <form action="{{ route('bookings.restore-cancellation', $booking) }}" method="POST" class="flex-1">
                                    @csrf
                                    <button type="submit" class="w-full border border-charcoal/10 dark:border-white/10 hover:border-blue-500 hover:text-blue-500 text-charcoal/40 dark:text-ash/40 px-4 py-2 text-[0.55rem] tracking-[0.3em] uppercase font-bold transition-all">Restore</button>
                                </form>
                            @endif
                        </div>
                    @endif
                </div>
            @endforeach
        </div>
        
        <!-- Desktop Table View -->
        <div class="hidden md:block glass overflow-hidden rounded-xl border border-charcoal/10 dark:border-white/10 reveal">
            <table class="min-w-full divide-y divide-charcoal/10 dark:divide-white/5">
                <thead>
                    <tr class="bg-charcoal/5 dark:bg-white/5">
                        <th class="px-8 py-5 text-left text-[0.6rem] tracking-[0.4em] uppercase text-gold font-bold">Shoot / Client</th>
                        <th class="px-8 py-5 text-left text-[0.6rem] tracking-[0.4em] uppercase text-gold font-bold">Crew</th>
                        <th class="px-8 py-5 text-left text-[0.6rem] tracking-[0.4em] uppercase text-gold font-bold">Call Time</th>
                        <th class="px-8 py-5 text-left text-[0.6rem] tracking-[0.4em] uppercase text-gold font-bold">Status</th>
                        <th class="px-8 py-5 text-right text-[0.6rem] tracking-[0.4em] uppercase text-gold font-bold">Directives</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-charcoal/10 dark:divide-white/5">
                    @foreach ($bookings as $booking)
                        <tr class="hover:bg-gold/5 transition-colors group">
                            <td class="px-8 py-6">
                                <p class="font-serif text-lg text-charcoal dark:text-white leading-tight group-hover:text-gold transition-colors">{{ $booking->bookingRequest->name }} {{ $booking->bookingRequest->surname }}</p>
                                <p class="text-[0.6rem] tracking-widest uppercase text-ash/60 dark:text-ash/40 mt-1 italic">{{ $booking->location }}</p>
                            </td>
                            <td class="px-8 py-6">
                                <div class="space-y-2">
                                    @forelse ($booking->crew as $crewMember)
                                        <div class="flex items-center gap-2">
                                            <div class="w-1.5 h-1.5 rounded-full bg-gold/60"></div>
                                            <span class="text-sm text-charcoal/70 dark:text-silver">{{ $crewMember->name }}</span>
                                            @if($crewMember->crew_specialty === 'both')
                                                <span class="text-xs text-gold ml-1">⚡ Dual Role</span>
                                            @endif
                                            <span class="text-[0.5rem] tracking-[0.2em] uppercase text-ash/50 dark:text-ash/60 italic">{{ $crewMember->pivot->role }}</span>
                                        </div>
                                    @empty
                                        <p class="text-sm text-ash/60 dark:text-ash/50 italic">TBD</p>
                                    @endforelse
                                </div>
                            </td>
                            <td class="px-8 py-6">
                                <p class="text-sm text-charcoal/70 dark:text-silver">{{ $booking->event_date->format('M d, Y') }}</p>
                                <p class="text-[0.6rem] text-ash/60 dark:text-ash/30 mt-1 tracking-widest">{{ $booking->event_date->format('H:i') }}</p>
                            </td>
                            <td class="px-8 py-6">
                                <span class="px-3 py-1 rounded-full text-[0.55rem] tracking-[0.2em] uppercase font-bold 
                                    @if ($booking->status === 'scheduled' || $booking->status === 'confirmed')
                                        bg-gold/10 text-gold border border-gold/20
                                    @elseif ($booking->status === 'cancelled')
                                        bg-red-500/10 text-red-500 border border-red-500/20
                                    @elseif ($booking->status === 'completed')
                                        bg-green-500/10 text-green-500 border border-green-500/20
                                    @else
                                        bg-charcoal/5 dark:bg-white/5 text-ash/60 dark:text-silver border border-charcoal/10 dark:border-white/10
                                    @endif
                                ">
                                    {{ $booking->status }}
                                </span>
                            </td>
                            <td class="px-8 py-6 text-right space-x-2">
                                @if (auth()->user()->hasRole('manager'))
                                    <a href="{{ route('invoices.create', $booking) }}" class="inline-block border border-charcoal/10 dark:border-white/10 hover:border-gold hover:text-gold text-charcoal/40 dark:text-ash/40 px-4 py-2 text-[0.55rem] tracking-[0.3em] uppercase font-bold transition-all">Billing</a>
                                    
                                    @if ($booking->status === 'pending' || $booking->status === 'scheduled')
                                        <form action="{{ route('bookings.confirm', $booking) }}" method="POST" class="inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="border border-charcoal/10 dark:border-white/10 hover:border-blue-500 hover:text-blue-500 text-charcoal/40 dark:text-ash/40 px-4 py-2 text-[0.55rem] tracking-[0.3em] uppercase font-bold transition-all">Confirm</button>
                                        </form>
                                    @endif

                                    @if ($booking->status === 'scheduled' || $booking->status === 'confirmed')
                                        <form action="{{ route('bookings.complete', $booking) }}" method="POST" class="inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="border border-charcoal/10 dark:border-white/10 hover:border-green-500 hover:text-green-500 text-charcoal/40 dark:text-ash/40 px-4 py-2 text-[0.55rem] tracking-[0.3em] uppercase font-bold transition-all">Wrap</button>
                                        </form>
                                    @endif

                                    <button type="button" onclick="document.getElementById('cancel-booking-{{ $booking->id }}').classList.remove('hidden')" class="border border-charcoal/10 dark:border-white/10 hover:border-red-500 hover:text-red-500 text-charcoal/40 dark:text-ash/40 px-4 py-2 text-[0.55rem] tracking-[0.3em] uppercase font-bold transition-all">Kill</button>
                                    <x-confirm-modal
                                        id="cancel-booking-{{ $booking->id }}"
                                        title="Cancel Production"
                                        message="Terminate this production sequence? This action cannot be undone."
                                        confirm-label="Terminate"
                                        :route="route('bookings.cancel', $booking)"
                                        method="PATCH"
                                        variant="danger"
                                    />

                                    @if ($booking->status === 'cancelled' && auth()->user()->hasRole('admin'))
                                        <form action="{{ route('bookings.restore-cancellation', $booking) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit" class="border border-charcoal/10 dark:border-white/10 hover:border-blue-500 hover:text-blue-500 text-charcoal/40 dark:text-ash/40 px-4 py-2 text-[0.55rem] tracking-[0.3em] uppercase font-bold transition-all">Restore</button>
                                        </form>
                                    @endif
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        <div class="mt-8 pagination-container reveal pb-8">
            {{ $bookings->links() }}
        </div>
    @endif
</div>
@endsection
