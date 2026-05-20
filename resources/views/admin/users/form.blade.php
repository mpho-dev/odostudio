@extends('layouts.app')

@section('title', (isset($user) ? 'Sychronize Specialist' : 'Recruit Specialist') . ' — Odo Studio')
@section('header', 'Personnel')

@section('content')
<div class="max-w-2xl mx-auto space-y-12 animate-hero-in pb-24 md:pb-8">
    <!-- Header -->
    <div class="reveal">
        <h1 class="font-serif text-3xl text-charcoal dark:text-white leading-tight">
            @if(isset($user))
                Synchronize <em>Specialist</em>
            @else
                Recruit <em>Specialist</em>
            @endif
        </h1>
        <p class="text-xs tracking-[0.3em] uppercase text-ash/60 dark:text-ash/60 mt-2 italic">Define identity and administrative boundaries</p>
    </div>

    @if ($errors->any())
        <div class="p-6 bg-red-500/10 border border-red-500/20 rounded-xl reveal">
            <h4 class="text-[0.6rem] tracking-[0.4rem] uppercase text-red-500 font-bold mb-3">Identity Clash</h4>
            <ul class="text-xs text-red-400 space-y-1 italic">
                @foreach ($errors->all() as $error)
                    <li>— {{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="glass p-10 md:p-12 border border-charcoal/10 dark:border-white/10 rounded-2xl relative overflow-hidden reveal">
        <!-- Decorative indicator -->
        <div class="absolute top-0 right-0 w-24 h-px bg-gradient-to-l from-gold/40 to-transparent"></div>

        <form method="POST" action="{{ isset($user) ? route('admin.users.update', $user) : route('admin.users.store') }}" class="space-y-8">
            @csrf
            @if(isset($user)) @method('PUT') @endif

            <div class="space-y-8">
                <!-- Identity Name -->
                <div class="space-y-3">
                    <label for="name" class="block text-[0.6rem] tracking-[0.4em] uppercase text-ash/60 dark:text-silver font-bold">Specialist Name</label>
                    <input id="name" type="text" name="name" value="{{ old('name', $user->name ?? '') }}" required 
                           class="w-full bg-charcoal/5 dark:bg-black/40 border border-charcoal/10 dark:border-white/10 p-4 text-sm text-charcoal dark:text-white focus:border-gold outline-none transition-all placeholder:text-ash/40 dark:placeholder:text-silver/40 font-serif text-lg italic" 
                           placeholder="Full Identity Name" />
                </div>

                <!-- Communication Channel -->
                <div class="space-y-3">
                    <label for="email" class="block text-[0.6rem] tracking-[0.4em] uppercase text-ash/60 dark:text-silver font-bold">Communication (Email)</label>
                    <input id="email" type="email" name="email" value="{{ old('email', $user->email ?? '') }}" required 
                           class="w-full bg-charcoal/5 dark:bg-black/40 border border-charcoal/10 dark:border-white/10 p-4 text-sm text-charcoal dark:text-white focus:border-gold outline-none transition-all placeholder:italic" 
                           placeholder="specialist@odostudio.com" />
                </div>

                <!-- Roles / Sectors -->
                <div class="space-y-4">
                    <label class="block text-[0.6rem] tracking-[0.4em] uppercase text-gold font-bold">Administrative Sectors</label>
                    <div class="grid grid-cols-1 gap-3">
                        @foreach($roles as $role)
                            <label class="flex items-center p-4 bg-charcoal/5 dark:bg-black/20 border border-charcoal/10 dark:border-white/10 rounded-xl cursor-pointer group transition-all hover:border-gold/30">
                                <input type="checkbox" name="roles[]" value="{{ $role->name }}" 
                                       @if(isset($user) && $user->hasRole($role->name)) checked @endif
                                       class="w-5 h-5 bg-charcoal/10 dark:bg-black border-charcoal/10 dark:border-white/10 rounded text-gold focus:ring-gold focus:ring-offset-black role-checkbox">
                                <span class="ml-4 flex flex-col">
                                    <span class="text-xs tracking-widest uppercase text-charcoal/80 dark:text-white font-bold group-hover:text-gold transition-colors">{{ str_replace('_', ' ', $role->name) }}</span>
                                    <span class="text-[0.6rem] text-ash/40 italic">Authorize access to {{ str_replace('_', ' ', $role->name) }} functionalities</span>
                                </span>
                            </label>
                        @endforeach
                    </div>
                    @error('roles') <p class="text-red-500 text-[0.6rem] tracking-widest uppercase mt-2">{{ $message }}</p> @enderror
                </div>

                <!-- Crew Specialty (conditionally shown) -->
                <div class="space-y-4" id="crew-specialty-section" style="display: {{ (isset($user) && $user->hasRole('crew')) || old('roles') && in_array('crew', old('roles') ?? []) ? 'block' : 'none' }};">
                    <label class="block text-[0.6rem] tracking-[0.4em] uppercase text-gold font-bold">Crew Specialization</label>
                    <p class="text-[0.55rem] tracking-widest uppercase text-ash/60 dark:text-ash/40 italic mb-3">Assign the primary discipline for this crew member</p>
                    <div class="grid grid-cols-1 gap-3">
                        <label class="flex items-center p-4 bg-charcoal/5 dark:bg-black/20 border border-charcoal/10 dark:border-white/10 rounded-xl cursor-pointer group transition-all hover:border-gold/30">
                            <input type="radio" name="crew_specialty" value="photographer" 
                                   @if(isset($user) && $user->crew_specialty === 'photographer') checked @endif
                                   {{ old('crew_specialty') === 'photographer' ? 'checked' : '' }}
                                   class="w-5 h-5 bg-charcoal/10 dark:bg-black border-charcoal/10 dark:border-white/10 rounded-full text-gold focus:ring-gold focus:ring-offset-black">
                            <span class="ml-4 flex flex-col">
                                <span class="text-xs tracking-widest uppercase text-charcoal/80 dark:text-white font-bold group-hover:text-gold transition-colors">Photographer</span>
                                <span class="text-[0.6rem] text-ash/40 italic">Specializes in photography & stills</span>
                            </span>
                        </label>

                        <label class="flex items-center p-4 bg-charcoal/5 dark:bg-black/20 border border-charcoal/10 dark:border-white/10 rounded-xl cursor-pointer group transition-all hover:border-gold/30">
                            <input type="radio" name="crew_specialty" value="videographer" 
                                   @if(isset($user) && $user->crew_specialty === 'videographer') checked @endif
                                   {{ old('crew_specialty') === 'videographer' ? 'checked' : '' }}
                                   class="w-5 h-5 bg-charcoal/10 dark:bg-black border-charcoal/10 dark:border-white/10 rounded-full text-gold focus:ring-gold focus:ring-offset-black">
                            <span class="ml-4 flex flex-col">
                                <span class="text-xs tracking-widest uppercase text-charcoal/80 dark:text-white font-bold group-hover:text-gold transition-colors">Videographer</span>
                                <span class="text-[0.6rem] text-ash/40 italic">Specializes in video & motion</span>
                            </span>
                        </label>

                        <label class="flex items-center p-4 bg-charcoal/5 dark:bg-black/20 border border-charcoal/10 dark:border-white/10 rounded-xl cursor-pointer group transition-all hover:border-gold/30">
                            <input type="radio" name="crew_specialty" value="both" 
                                   @if(isset($user) && $user->crew_specialty === 'both') checked @endif
                                   {{ old('crew_specialty') === 'both' ? 'checked' : '' }}
                                   class="w-5 h-5 bg-charcoal/10 dark:bg-black border-charcoal/10 dark:border-white/10 rounded-full text-gold focus:ring-gold focus:ring-offset-black">
                            <span class="ml-4 flex flex-col">
                                <span class="text-xs tracking-widest uppercase text-charcoal/80 dark:text-white font-bold group-hover:text-gold transition-colors">Photographer & Videographer</span>
                                <span class="text-[0.6rem] text-ash/40 italic">Works across both disciplines</span>
                            </span>
                        </label>
                    </div>
                    @error('crew_specialty') <p class="text-red-500 text-[0.6rem] tracking-widest uppercase mt-2">{{ $message }}</p> @enderror
                </div>

                @unless(isset($user))
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 pt-4">
                    <!-- Security Signature -->
                    <div class="space-y-3" x-data="{ show: false }">
                        <label for="password" class="block text-[0.6rem] tracking-[0.4em] uppercase text-ash/60 dark:text-silver font-bold">Password</label>
                        <div class="relative">
                            <input id="password" :type="show ? 'text' : 'password'" name="password" required 
                                   class="w-full bg-charcoal/5 dark:bg-black/40 border border-charcoal/10 dark:border-white/10 p-4 pr-12 text-sm text-charcoal dark:text-white focus:border-gold outline-none transition-all placeholder:italic" 
                                   placeholder="Min 8 characters" />
                            <button type="button" @click="show = !show" class="absolute inset-y-0 right-0 flex items-center pr-4 text-ash/60 hover:text-gold transition-colors">
                                <svg x-show="!show" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                <svg x-show="show" x-cloak class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"></path></svg>
                            </button>
                        </div>
                    </div>

                    <!-- Confirmation -->
                    <div class="space-y-3" x-data="{ show: false }">
                        <label for="password_confirmation" class="block text-[0.6rem] tracking-[0.4em] uppercase text-ash/60 dark:text-silver font-bold">Confirm Password</label>
                        <div class="relative">
                            <input id="password_confirmation" :type="show ? 'text' : 'password'" name="password_confirmation" required 
                                   class="w-full bg-charcoal/5 dark:bg-black/40 border border-charcoal/10 dark:border-white/10 p-4 pr-12 text-sm text-charcoal dark:text-white focus:border-gold outline-none transition-all placeholder:text-ash/40 dark:placeholder:text-silver/40" 
                                   placeholder="Repeat Password" />
                            <button type="button" @click="show = !show" class="absolute inset-y-0 right-0 flex items-center pr-4 text-ash/60 hover:text-gold transition-colors">
                                <svg x-show="!show" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                <svg x-show="show" x-cloak class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"></path></svg>
                            </button>
                        </div>
                    </div>
                </div>
                @endunless
            </div>

            <!-- Submit Button -->
            <div class="mt-12 pt-10 border-t border-charcoal/10 dark:border-white/10 flex justify-end gap-4">
                <a href="{{ route('admin.users.index') }}" class="px-8 py-4 text-[0.6rem] tracking-[0.4em] uppercase font-bold text-ash/40 hover:text-white transition-all">Abort Operation</a>
                <button type="submit" class="bg-gold text-black px-10 py-4 text-xs tracking-widest uppercase font-bold hover:bg-white transition-all shadow-xl shadow-gold/20">
                    {{ isset($user) ? 'Synchronize Identity →' : 'Finalize Recruitment →' }}
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const roleCheckboxes = document.querySelectorAll('.role-checkbox');
        const crewSpecialtySection = document.getElementById('crew-specialty-section');
        
        function updateCrewSpecialtyVisibility() {
            const isCrewRole = Array.from(roleCheckboxes).some(checkbox => {
                return checkbox.value === 'crew' && checkbox.checked;
            });
            
            if (crewSpecialtySection) {
                crewSpecialtySection.style.display = isCrewRole ? 'block' : 'none';
            }
        }
        
        roleCheckboxes.forEach(checkbox => {
            checkbox.addEventListener('change', updateCrewSpecialtyVisibility);
        });
    });
</script>
@endsection
