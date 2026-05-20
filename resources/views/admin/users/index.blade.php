@extends('layouts.app')

@section('title', 'Specialist Management — Odo Studio')
@section('header', 'Personnel')

@section('content')
<div class="space-y-12 pb-12">
    <!-- Header -->
    <div class="flex flex-col md:flex-row justify-between items-end gap-6 reveal">
        <div>
            <div class="flex items-center gap-4 text-[0.62rem] tracking-[0.4em] uppercase text-gold mb-4">
                <span>Personnel</span>
                <div class="h-px w-16 bg-gold/30"></div>
            </div>
            <h1 class="font-serif text-4xl text-charcoal dark:text-white leading-tight">Specialist <em>Archives</em></h1>
            <p class="text-xs tracking-[0.3em] uppercase text-ash/60 dark:text-ash/60 mt-2 italic">Management of Personnel Identity & Permissions</p>
        </div>
        <a href="{{ route('admin.users.create') }}" class="group relative px-8 py-4 bg-gold text-black text-[0.65rem] tracking-[0.3em] uppercase font-bold hover:bg-white transition-all duration-500 overflow-hidden">
            <span class="relative z-10">+ Recruit Specialist</span>
            <div class="absolute inset-0 bg-white translate-x-[-100%] group-hover:translate-x-0 transition-transform duration-500"></div>
        </a>
    </div>

    @if($users->isEmpty())
        <div class="py-32 text-center glass rounded-xl border border-dashed border-charcoal/20 dark:border-white/10 reveal shadow-2xl relative overflow-hidden group">
            <div class="absolute top-0 left-0 w-8 h-8 border-t border-l border-gold/40 group-hover:w-12 group-hover:h-12 transition-all duration-500"></div>
            <div class="absolute bottom-0 right-0 w-8 h-8 border-b border-r border-gold/40 group-hover:w-12 group-hover:h-12 transition-all duration-500"></div>
            <p class="font-serif text-xl text-ash/60 dark:text-ash/40 italic opacity-40">No specialists in the archives.</p>
        </div>
    @else
        <!-- Mobile Card View -->
        <div class="block md:hidden space-y-4">
            @foreach($users as $user)
                <div class="glass p-6 rounded-xl border border-charcoal/10 dark:border-white/10 relative overflow-hidden group shadow-2xl">
                    <div class="absolute top-0 left-0 w-8 h-8 border-t border-l border-gold/40 group-hover:w-12 group-hover:h-12 transition-all duration-500"></div>
                    <div class="absolute bottom-0 right-0 w-8 h-8 border-b border-r border-gold/40 group-hover:w-12 group-hover:h-12 transition-all duration-500"></div>
                    <div class="flex items-start gap-4 mb-4">
                        <div class="w-10 h-10 bg-charcoal/5 dark:bg-white/5 rounded-full grid place-items-center font-serif text-xl text-gold border border-charcoal/10 dark:border-white/10 shrink-0">
                            {{ substr($user->name, 0, 1) }}
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="font-serif text-lg text-charcoal dark:text-white leading-tight">{{ $user->name }}</p>
                            <p class="text-[0.6rem] tracking-widest uppercase text-ash/40 mt-0.5 italic">Specialist Ref: #{{ 1000 + $user->id }}</p>
                        </div>
                    </div>
                    
                    <div class="space-y-3 mb-4">
                        <div>
                            <p class="text-[0.55rem] tracking-[0.3em] uppercase text-gold/60">Communication</p>
                            <p class="text-sm text-ash/80 dark:text-silver font-medium">{{ $user->email }}</p>
                        </div>
                        <div>
                            <p class="text-[0.55rem] tracking-[0.3em] uppercase text-gold/60">Sectors</p>
                            <div class="flex flex-wrap gap-2 mt-1">
                                @foreach($user->roles as $role)
                                    <span class="px-2.5 py-1 bg-gold/10 text-gold border border-gold/20 rounded-full text-[0.55rem] tracking-[0.15em] uppercase font-bold">
                                        {{ str_replace('_', ' ', $role->name) }}
                                    </span>
                                @endforeach
                            </div>
                            @if($user->hasRole('crew') && $user->crew_specialty)
                                <p class="text-[0.5rem] tracking-widest uppercase text-ash/60 mt-2">Crew: {{ str_replace('_', ' ', $user->crew_specialty) }}</p>
                            @endif
                        </div>
                    </div>
                    
                    <div class="flex flex-wrap gap-2">
                        <a href="{{ route('admin.users.edit', $user) }}" class="flex-1 text-center border border-charcoal/10 dark:border-white/10 hover:border-gold hover:text-gold text-charcoal/40 dark:text-ash/40 px-4 py-2 text-[0.55rem] tracking-[0.3em] uppercase font-bold transition-all">Synchronize</a>
                        
                        @if($user->id !== auth()->id())
                            <button type="button" onclick="document.getElementById('delete-user-mobile-{{ $user->id }}').classList.remove('hidden')" class="w-full border border-charcoal/10 dark:border-white/10 hover:border-red-500 hover:text-red-500 text-charcoal/40 dark:text-ash/40 px-4 py-2 text-[0.55rem] tracking-[0.3em] uppercase font-bold transition-all">Terminate</button>
                            <x-confirm-modal
                                id="delete-user-mobile-{{ $user->id }}"
                                title="Purge Identity"
                                message="Purge this identity from the collective? This action cannot be undone."
                                confirm-label="Terminate"
                                :route="route('admin.users.destroy', $user)"
                                method="DELETE"
                                variant="danger"
                            />
                        @else
                            <div class="flex-1 text-center bg-gold/5 dark:bg-transparent border border-gold/20 text-gold/60 px-4 py-2 text-[0.55rem] tracking-[0.3em] uppercase font-bold italic">Current</div>
                        @endif
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
                        <th class="px-8 py-5 text-left text-[0.6rem] tracking-[0.4em] uppercase text-gold font-bold">Identity</th>
                        <th class="px-8 py-5 text-left text-[0.6rem] tracking-[0.4em] uppercase text-gold font-bold">Communication</th>
                        <th class="px-8 py-5 text-left text-[0.6rem] tracking-[0.4em] uppercase text-gold font-bold">Sectors/Roles</th>
                        <th class="px-8 py-5 text-right text-[0.6rem] tracking-[0.4em] uppercase text-gold font-bold">Management</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-charcoal/10 dark:divide-white/5">
                    @foreach($users as $user)
                        <tr class="hover:bg-gold/5 transition-colors group">
                            <td class="px-8 py-6">
                                <div class="flex items-center gap-4">
                                    <div class="w-10 h-10 bg-charcoal/5 dark:bg-white/5 rounded-full grid place-items-center font-serif text-xl text-gold border border-charcoal/10 dark:border-white/10">
                                        {{ substr($user->name, 0, 1) }}
                                    </div>
                                    <div>
                                        <p class="font-serif text-lg text-charcoal dark:text-white leading-tight group-hover:text-gold transition-colors">{{ $user->name }}</p>
                                        <p class="text-[0.6rem] tracking-widest uppercase text-ash/40 mt-0.5 italic">Specialist Ref: #{{ 1000 + $user->id }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-8 py-6">
                                <span class="text-sm text-ash/80 dark:text-silver font-medium">{{ $user->email }}</span>
                            </td>
                            <td class="px-8 py-6">
                                <div class="space-y-2">
                                    <div class="flex flex-wrap gap-2">
                                        @foreach($user->roles as $role)
                                            <span class="px-2.5 py-1 bg-gold/10 text-gold border border-gold/20 rounded-full text-[0.55rem] tracking-[0.15em] uppercase font-bold">
                                                {{ str_replace('_', ' ', $role->name) }}
                                            </span>
                                        @endforeach
                                    </div>
                                    @if($user->hasRole('crew') && $user->crew_specialty)
                                        <div class="pt-1 border-t border-charcoal/10 dark:border-white/10">
                                            <span class="px-2 py-0.5 text-[0.5rem] tracking-widest uppercase text-ash/60 dark:text-ash/50 italic">
                                                Crew: {{ str_replace('_', ' ', $user->crew_specialty) }}
                                            </span>
                                        </div>
                                    @endif
                                </div>
                            </td>
                            <td class="px-8 py-6 text-right">
                                <div class="flex justify-end gap-3">
                                    <a href="{{ route('admin.users.edit', $user) }}" class="border border-charcoal/10 dark:border-white/10 hover:border-gold hover:text-gold text-charcoal/40 dark:text-ash/40 px-4 py-2 text-[0.55rem] tracking-[0.3em] uppercase font-bold transition-all">Synchronize</a>
                                    
                                    @if($user->id !== auth()->id())
                                    <button type="button" onclick="document.getElementById('delete-user-{{ $user->id }}').classList.remove('hidden')" class="border border-charcoal/10 dark:border-white/10 hover:border-red-500 hover:text-red-500 text-charcoal/40 dark:text-ash/40 px-4 py-2 text-[0.55rem] tracking-[0.3em] uppercase font-bold transition-all">Terminate</button>
                                    <x-confirm-modal
                                        id="delete-user-{{ $user->id }}"
                                        title="Purge Identity"
                                        message="Purge this identity from the collective? This action cannot be undone."
                                        confirm-label="Terminate"
                                        :route="route('admin.users.destroy', $user)"
                                        method="DELETE"
                                        variant="danger"
                                    />
                                    @else
                                    <span class="bg-gold/5 dark:bg-transparent border border-gold/20 dark:border-gold/10 text-gold/60 dark:text-gold/20 px-4 py-2 text-[0.55rem] tracking-[0.3em] uppercase font-bold italic cursor-not-allowed select-none">Current Perspective</span>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif

    <div class="mt-8 pagination-container reveal">
        {{ $users->links() }}
    </div>
</div>
@endsection
