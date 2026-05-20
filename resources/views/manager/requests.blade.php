@extends('layouts.app')

@section('title', 'Casting & Enquiries — Odo Studio')

@section('header', 'Incoming Enquiries')

@section('content')
<div class="space-y-8 animate-hero-in pb-24 md:pb-8">
    <div class="flex justify-between items-center reveal">
        <div>
            <h1 class="font-serif text-3xl text-charcoal dark:text-white leading-tight">Incoming <em>Enquiries</em></h1>
            <p class="text-xs tracking-[0.3em] uppercase text-ash/60 dark:text-ash/60 mt-1 italic">Assess and greenlight new productions</p>
        </div>
    </div>

    @if ($requests->isEmpty())
        <div class="py-32 text-center glass rounded-xl border border-dashed border-charcoal/20 dark:border-white/10 reveal shadow-2xl relative overflow-hidden group">
            <div class="absolute top-0 left-0 w-8 h-8 border-t border-l border-gold/40 group-hover:w-12 group-hover:h-12 transition-all duration-500"></div>
            <div class="absolute bottom-0 right-0 w-8 h-8 border-b border-r border-gold/40 group-hover:w-12 group-hover:h-12 transition-all duration-500"></div>
            <p class="font-serif text-xl text-ash/60 dark:text-ash/40 italic opacity-40">No pending enquiries at this time.</p>
        </div>
    @else
        <!-- Mobile Card View -->
        <div class="block md:hidden space-y-4">
            @foreach ($requests as $request)
                <div class="glass p-6 rounded-xl border border-charcoal/10 dark:border-white/10 relative overflow-hidden group shadow-2xl">
                    <div class="absolute top-0 left-0 w-8 h-8 border-t border-l border-gold/40 group-hover:w-12 group-hover:h-12 transition-all duration-500"></div>
                    <div class="absolute bottom-0 right-0 w-8 h-8 border-b border-r border-gold/40 group-hover:w-12 group-hover:h-12 transition-all duration-500"></div>
                    <div class="flex justify-between items-start mb-4">
                        <div>
                            <p class="font-serif text-lg text-charcoal dark:text-white leading-tight">{{ $request->name }} {{ $request->surname }}</p>
                            <p class="text-[0.6rem] tracking-widest uppercase text-ash/60 dark:text-ash/40 mt-1 italic">Received {{ $request->created_at->diffForHumans() }}</p>
                        </div>
                    </div>
                    
                    <div class="space-y-3 mb-4">
                        <div>
                            <p class="text-[0.55rem] tracking-[0.3em] uppercase text-gold/60">Concept / Date</p>
                            <p class="text-sm text-charcoal/70 dark:text-silver">{{ $request->event_type }}</p>
                            <p class="text-[0.6rem] text-ash/60 dark:text-ash/30 tracking-widest">{{ $request->event_date?->format('M d, Y') ?? 'Date Pending' }}</p>
                            @if($request->notes)
                                <p class="text-[0.6rem] text-ash/50 dark:text-ash/40 mt-1 italic">{{ Str::limit($request->notes, 80) }}</p>
                            @endif
                        </div>
                        <div>
                            <p class="text-[0.55rem] tracking-[0.3em] uppercase text-gold/60">Contact</p>
                            <p class="text-sm text-charcoal/70 dark:text-silver">{{ $request->email }}</p>
                            <p class="text-[0.6rem] text-ash/60 dark:text-ash/30 tracking-widest">{{ $request->phone }}</p>
                        </div>
                        
                        <!-- Assigned Crew -->
                        @if($request->crew->count() > 0)
                            <div>
                                <p class="text-[0.55rem] tracking-[0.3em] uppercase text-gold/60">Assigned Crew</p>
                                <div class="flex flex-wrap gap-1 mt-1">
                                    @foreach($request->crew as $crewMember)
                                        <span class="px-2 py-1 bg-gold/10 text-gold text-[0.55rem] rounded">
                                            {{ $crewMember->name }} ({{ $crewMember->pivot->role }})
                                        </span>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>
                    
                    <div class="space-y-2">
                        <!-- Crew Assignment Form -->
                        <form action="{{ route('requests.crew.assign', $request) }}" method="POST" class="flex gap-2">
                            @csrf
                            <select name="user_id" class="flex-1 bg-charcoal/5 dark:bg-white/5 border border-charcoal/10 dark:border-white/10 text-charcoal dark:text-white text-xs p-2 rounded">
                                <option value="">Assign crew...</option>
                                @foreach($crewMembers as $photographer)
                                    <option value="{{ $photographer->id }}">
                                        {{ $photographer->name }} 
                                        @if($photographer->crew_specialty === 'both')
                                            (Photo/Video)
                                        @else
                                            ({{ ucfirst($photographer->crew_specialty) }})
                                        @endif
                                    </option>
                                @endforeach
                            </select>
                            <select name="role" class="w-24 bg-charcoal/5 dark:bg-white/5 border border-charcoal/10 dark:border-white/10 text-charcoal dark:text-white text-xs p-2 rounded">
                                <option value="photographer">Photo</option>
                                <option value="videographer">Video</option>
                            </select>
                            <button type="submit" class="px-3 py-2 bg-gold/20 text-gold text-xs rounded hover:bg-gold/30 transition-colors">+</button>
                        </form>
                        
                        <a href="{{ route('bookings.create', $request) }}" class="block w-full text-center border border-charcoal/10 dark:border-white/10 hover:border-gold hover:text-gold text-charcoal/40 dark:text-ash/40 px-6 py-3 text-[0.55rem] tracking-[0.3em] uppercase font-bold transition-all">Greenlight Shoot</a>
                    </div>
                </div>
            @endforeach
        </div>
        
        <!-- Desktop Table View -->
        <div class="hidden md:block glass overflow-hidden rounded-xl border border-charcoal/10 dark:border-white/10 reveal shadow-2xl relative overflow-hidden group">
            <div class="absolute top-0 left-0 w-8 h-8 border-t border-l border-gold/40 group-hover:w-12 group-hover:h-12 transition-all duration-500"></div>
            <div class="absolute bottom-0 right-0 w-8 h-8 border-b border-r border-gold/40 group-hover:w-12 group-hover:h-12 transition-all duration-500"></div>
            <table class="min-w-full divide-y divide-charcoal/10 dark:divide-white/5">
                <thead>
                    <tr class="bg-charcoal/5 dark:bg-white/5">
                        <th class="px-8 py-5 text-left text-[0.6rem] tracking-[0.4em] uppercase text-gold font-bold">Talent / Client</th>
                        <th class="px-8 py-5 text-left text-[0.6rem] tracking-[0.4em] uppercase text-gold font-bold">Concept / Date</th>
                        <th class="px-8 py-5 text-left text-[0.6rem] tracking-[0.4em] uppercase text-gold font-bold">Comms Channel</th>
                        <th class="px-8 py-5 text-left text-[0.6rem] tracking-[0.4em] uppercase text-gold font-bold">Crew Assignment</th>
                        <th class="px-8 py-5 text-right text-[0.6rem] tracking-[0.4em] uppercase text-gold font-bold">Directives</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-charcoal/10 dark:divide-white/5">
                    @foreach ($requests as $request)
                        <tr class="hover:bg-gold/5 transition-colors group">
                            <td class="px-8 py-6">
                                <p class="font-serif text-lg text-charcoal dark:text-white leading-tight group-hover:text-gold transition-colors">{{ $request->name }} {{ $request->surname }}</p>
                                <p class="text-[0.6rem] tracking-widest uppercase text-ash/60 dark:text-ash/40 mt-1 italic">Received {{ $request->created_at->diffForHumans() }}</p>
                            </td>
                            <td class="px-8 py-6">
                                <p class="text-sm text-charcoal/70 dark:text-silver">{{ $request->event_type }}</p>
                                <p class="text-[0.6rem] text-ash/60 dark:text-ash/30 mt-1 tracking-widest">{{ $request->event_date?->format('M d, Y') ?? 'Date Pending' }}</p>
                                @if($request->notes)
                                    <p class="text-[0.6rem] text-ash/50 dark:text-ash/40 mt-1 italic">{{ Str::limit($request->notes, 60) }}</p>
                                @endif
                            </td>
                            <td class="px-8 py-6">
                                <p class="text-sm text-charcoal/70 dark:text-silver">{{ $request->email }}</p>
                                <p class="text-[0.6rem] text-ash/60 dark:text-ash/30 mt-1 tracking-widest">{{ $request->phone }}</p>
                            </td>
                            <td class="px-8 py-6">
                                <!-- Current Crew -->
                                <div class="mb-2">
                                    @if($request->crew->count() > 0)
                                        <div class="flex flex-wrap gap-1">
                                            @foreach($request->crew as $crewMember)
                                                <span class="inline-flex items-center px-2 py-1 bg-gold/10 text-gold text-[0.55rem] rounded gap-1">
                                                    {{ $crewMember->name }}
                                                    <form action="{{ route('requests.crew.remove', [$request, $crewMember]) }}" method="POST" class="inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="text-gold/60 hover:text-gold">&times;</button>
                                                    </form>
                                                </span>
                                            @endforeach
                                        </div>
                                    @else
                                        <span class="text-[0.55rem] text-ash/40 italic">No crew assigned</span>
                                    @endif
                                </div>
                                
                                <!-- Assign Form -->
                                <form action="{{ route('requests.crew.assign', $request) }}" method="POST" class="flex gap-1">
                                    @csrf
                                    <select name="user_id" class="flex-1 bg-charcoal/5 dark:bg-white/5 border border-charcoal/10 dark:border-white/10 text-charcoal dark:text-white text-[0.55rem] p-1 rounded">
                                        <option value="">Assign...</option>
                                        @foreach($crewMembers as $photographer)
                                            <option value="{{ $photographer->id }}">
                                                {{ $photographer->name }} 
                                                @if($photographer->crew_specialty === 'both')
                                                    (Photo/Video)
                                                @else
                                                    ({{ ucfirst($photographer->crew_specialty) }})
                                                @endif
                                            </option>
                                        @endforeach
                                    </select>
                                    <select name="role" class="w-16 bg-charcoal/5 dark:bg-white/5 border border-charcoal/10 dark:border-white/10 text-charcoal dark:text-white text-[0.55rem] p-1 rounded">
                                        <option value="photographer">Photo</option>
                                        <option value="videographer">Video</option>
                                    </select>
                                    <button type="submit" class="px-2 py-1 bg-gold/20 text-gold text-[0.55rem] rounded hover:bg-gold/30 transition-colors">+</button>
                                </form>
                            </td>
                            <td class="px-8 py-6 text-right">
                                <a href="{{ route('bookings.create', $request) }}" class="inline-block border border-charcoal/10 dark:border-white/10 hover:border-gold hover:text-gold text-charcoal/40 dark:text-ash/40 px-6 py-3 text-[0.55rem] tracking-[0.3em] uppercase font-bold transition-all">Greenlight Shoot</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        <div class="pagination-container mt-8 reveal">
            {{ $requests->links() }}
        </div>
    @endif
</div>
@endsection
