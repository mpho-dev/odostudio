@extends('layouts.guest')

@section('title', 'Establish New Access — Odo Studio')

@section('content')
<div class="min-h-screen relative flex items-center justify-center overflow-hidden">
    <!-- Cinematic background -->
    <div class="absolute inset-0 bg-paper dark:bg-black theme-transition">
        <!-- Dark mode specific mesh -->
        <div class="absolute inset-0 bg-[radial-gradient(ellipse_60%_80%_at_70%_40%,_#1a1410_0%,_transparent_65%),_radial-gradient(ellipse_50%_60%_at_20%_70%,_#0e0c0a_0%,_transparent_60%),_linear-gradient(135deg,_#080808_0%,_#12100e_40%,_#1c1710_70%,_#080808_100%)] opacity-0 dark:opacity-100 transition-opacity duration-1000"></div>
        <!-- Light mode specific mesh -->
        <div class="absolute inset-0 bg-[radial-gradient(ellipse_60%_80%_at_70%_40%,_rgba(201,168,76,0.05)_0%,_transparent_65%),_radial-gradient(ellipse_50%_60%_at_20%_70%,_rgba(0,0,0,0.02)_0%,_transparent_60%)] opacity-100 dark:opacity-0 transition-opacity duration-1000"></div>
        
        <!-- Light leaks -->
        <div class="absolute inset-0 bg-[radial-gradient(ellipse_30%_60%_at_85%_20%,_rgba(201,168,76,0.05)_0%,_transparent_70%),_radial-gradient(ellipse_20%_40%_at_10%_80%,_rgba(201,168,76,0.03)_0%,_transparent_60%)]"></div>
        <div class="absolute inset-0 film-grain opacity-40"></div>
    </div>

    <!-- Anamorphic letterbox bars -->
    <div class="absolute top-0 left-0 right-0 h-16 bg-charcoal/90 dark:bg-black z-10"></div>
    <div class="absolute bottom-0 left-0 right-0 h-16 bg-charcoal/90 dark:bg-black z-10"></div>

    <div class="relative z-20 w-full max-w-lg px-6 animate-hero-in">
        <div class="glass p-10 md:p-12 border border-charcoal/10 dark:border-white/10 shadow-2xl relative overflow-hidden group">
            <!-- Decorative corners -->
            <div class="absolute top-0 left-0 w-8 h-8 border-t border-l border-gold/40 group-hover:w-12 group-hover:h-12 transition-all duration-500"></div>
            <div class="absolute bottom-0 right-0 w-8 h-8 border-b border-r border-gold/40 group-hover:w-12 group-hover:h-12 transition-all duration-500"></div>

            <div class="text-center mb-10">
                <div class="text-[0.6rem] tracking-[0.4em] uppercase text-gold mb-4">Security Protocol</div>
                <h1 class="font-serif text-3xl text-charcoal dark:text-white mb-2 tracking-tight">Establish <em>Access</em></h1>
                <p class="text-[0.7rem] tracking-widest text-ash/60 dark:text-silver uppercase italic">Define New Credentials</p>
            </div>

            @if ($errors->any())
                <div class="mb-8 p-4 bg-red-900/40 border border-red-500/50 text-red-200 text-xs rounded animate-pulse">
                    @foreach ($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <form method="POST" action="{{ route('password.store') }}" class="space-y-6">
                @csrf

                <!-- Password Reset Token -->
                <input type="hidden" name="token" value="{{ $request->route('token') }}">

                <!-- Email Address -->
                <div class="space-y-2">
                    <label for="email" class="block text-[0.62rem] tracking-[0.3em] uppercase text-ash/60 dark:text-silver">Identification</label>
                    <input id="email" type="email" name="email" value="{{ old('email', $request->email) }}" required autofocus autocomplete="email" 
                           placeholder="you@odocorp.co.za"
                           class="w-full bg-charcoal/5 dark:bg-black/50 border border-charcoal/10 dark:border-white/10 p-3.5 text-sm text-charcoal dark:text-white focus:border-gold outline-none transition-all duration-300" />
                </div>

                <!-- Password -->
                <div class="space-y-2" x-data="{ show: false }">
                    <label for="password" class="block text-[0.62rem] tracking-[0.3em] uppercase text-ash/60 dark:text-silver">New Password</label>
                    <div class="relative">
                        <input id="password" :type="show ? 'text' : 'password'" name="password" required autocomplete="new-password" 
                               placeholder="••••••••"
                               class="w-full bg-charcoal/5 dark:bg-black/50 border border-charcoal/10 dark:border-white/10 p-3.5 pr-12 text-sm text-charcoal dark:text-white focus:border-gold outline-none transition-all duration-300" />
                        <button type="button" @click="show = !show" class="absolute inset-y-0 right-0 flex items-center pr-4 text-ash/60 hover:text-gold transition-colors">
                            <svg x-show="!show" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                            <svg x-show="show" x-cloak class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"></path></svg>
                        </button>
                    </div>
                </div>

                <!-- Confirm Password -->
                <div class="space-y-2" x-data="{ show: false }">
                    <label for="password_confirmation" class="block text-[0.62rem] tracking-[0.3em] uppercase text-ash/60 dark:text-silver">Confirm Password</label>
                    <div class="relative">
                        <input id="password_confirmation" :type="show ? 'text' : 'password'" name="password_confirmation" required autocomplete="new-password" 
                               placeholder="••••••••"
                               class="w-full bg-charcoal/5 dark:bg-black/40 border border-charcoal/10 dark:border-white/10 p-3.5 pr-12 text-sm text-charcoal dark:text-white focus:border-gold outline-none transition-all duration-300" />
                        <button type="button" @click="show = !show" class="absolute inset-y-0 right-0 flex items-center pr-4 text-ash/60 hover:text-gold transition-colors">
                            <svg x-show="!show" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                            <svg x-show="show" x-cloak class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"></path></svg>
                        </button>
                    </div>
                </div>

                <!-- Actions -->
                <div class="pt-4">
                    <button type="submit" class="w-full bg-gold text-black py-4 text-[0.7rem] tracking-[0.3em] uppercase font-semibold hover:bg-white transition-all duration-500 shadow-[0_0_20px_rgba(201,168,76,0.15)] hover:shadow-[0_0_30px_rgba(255,255,255,0.2)]">
                        Reset Signature →
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
