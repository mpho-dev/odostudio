@extends('layouts.app')

@section('title', 'Booking Enquiries — Odo Studio')

@section('header', 'Incoming Enquiries')

@section('content')
<div class="space-y-8 animate-hero-in">
    <div class="flex justify-between items-center reveal">
        <div>
            <h1 class="font-serif text-3xl text-charcoal dark:text-white leading-tight">Incoming <em>Enquiries</em></h1>
            <p class="text-xs tracking-[0.3em] uppercase text-ash/60 dark:text-ash/60 mt-1 italic">Review incoming productions and requests</p>
        </div>
        <a href="{{ route('photographer.preferences') }}" class="inline-block border border-charcoal/10 dark:border-white/10 hover:border-gold hover:text-gold text-charcoal/40 dark:text-ash/40 px-6 py-3 text-[0.55rem] tracking-[0.3em] uppercase font-bold transition-all">
            Comm Settings
        </a>
    </div>

    @if ($bookingRequests->isEmpty())
        <div class="py-32 text-center glass rounded-xl border border-dashed border-charcoal/20 dark:border-white/10 reveal shadow-2xl relative overflow-hidden group">
            <div class="absolute top-0 left-0 w-8 h-8 border-t border-l border-gold/40 group-hover:w-12 group-hover:h-12 transition-all duration-500"></div>
            <div class="absolute bottom-0 right-0 w-8 h-8 border-b border-r border-gold/40 group-hover:w-12 group-hover:h-12 transition-all duration-500"></div>
            <p class="font-serif text-xl text-ash/60 dark:text-ash/40 italic opacity-40">No incoming enquiries at this time.</p>
        </div>
    @else
        <div class="glass overflow-hidden rounded-xl border border-charcoal/10 dark:border-white/10 reveal shadow-2xl relative overflow-hidden group">
            <div class="absolute top-0 left-0 w-8 h-8 border-t border-l border-gold/40 group-hover:w-12 group-hover:h-12 transition-all duration-500"></div>
            <div class="absolute bottom-0 right-0 w-8 h-8 border-b border-r border-gold/40 group-hover:w-12 group-hover:h-12 transition-all duration-500"></div>
            <table class="min-w-full divide-y divide-charcoal/10 dark:divide-white/5">
                <thead>
                    <tr class="bg-charcoal/5 dark:bg-white/5">
                        <th class="px-8 py-5 text-left text-[0.6rem] tracking-[0.4em] uppercase text-gold font-bold">Client Identity</th>
                        <th class="px-8 py-5 text-left text-[0.6rem] tracking-[0.4em] uppercase text-gold font-bold">Concept / Date</th>
                        <th class="px-8 py-5 text-left text-[0.6rem] tracking-[0.4em] uppercase text-gold font-bold">Status</th>
                        <th class="px-8 py-5 text-right text-[0.6rem] tracking-[0.4em] uppercase text-gold font-bold">Directives</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-charcoal/10 dark:divide-white/5">
                    @foreach ($bookingRequests as $request)
                        <tr class="hover:bg-gold/5 transition-colors group">
                            <td class="px-8 py-6">
                                <p class="font-serif text-lg text-charcoal dark:text-white leading-tight group-hover:text-gold transition-colors">{{ $request->name }} {{ $request->surname }}</p>
                                <p class="text-[0.6rem] tracking-widest uppercase text-ash/60 dark:text-ash/40 mt-1 italic">Received {{ $request->created_at->diffForHumans() }}</p>
                            </td>
                            <td class="px-8 py-6">
                                <p class="text-sm text-charcoal/70 dark:text-silver">{{ $request->event_type }}</p>
                                <p class="text-[0.6rem] text-ash/60 dark:text-ash/30 mt-1 tracking-widest">{{ $request->event_date?->format('M d, Y') ?? 'Date Pending' }}</p>
                            </td>
                            <td class="px-8 py-6">
                                @if($request->email_sent_at)
                                    <span class="px-3 py-1 rounded-full text-[0.55rem] tracking-[0.2em] uppercase font-bold bg-green-500/10 text-green-500 border border-green-500/20">
                                        Email Dispatched
                                    </span>
                                    <p class="text-[0.55rem] text-ash/60 dark:text-ash/40 mt-2 italic">{{ $request->email_sent_at->diffForHumans() }}</p>
                                @else
                                    <span class="px-3 py-1 rounded-full text-[0.55rem] tracking-[0.2em] uppercase font-bold bg-charcoal/5 dark:bg-white/5 text-ash/60 dark:text-silver border border-charcoal/10 dark:border-white/10">
                                        Received
                                    </span>
                                @endif
                            </td>
                            <td class="px-8 py-6 text-right">
                                <a href="{{ route('photographer.requests.show', $request) }}" class="inline-block border border-charcoal/10 dark:border-white/10 hover:border-gold hover:text-gold text-charcoal/40 dark:text-ash/40 px-6 py-3 text-[0.55rem] tracking-[0.3em] uppercase font-bold transition-all">Inspect</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        <div class="mt-8 pagination-container reveal">
            {{ $bookingRequests->links() }}
        </div>
    @endif
</div>
@endsection
