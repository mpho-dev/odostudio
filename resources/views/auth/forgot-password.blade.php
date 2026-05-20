@extends('layouts.guest')

@section('title', 'Forgotten Access — Odo Studio')

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
                <div class="text-[0.6rem] tracking-[0.4em] uppercase text-gold mb-4">Recovery Protocol</div>
                <h1 class="font-serif text-3xl text-charcoal dark:text-white mb-2 tracking-tight">Forgotten <em>Access</em></h1>
                <p class="text-[0.7rem] tracking-widest text-ash/60 dark:text-silver uppercase italic">Regain Entry to the Studio</p>
            </div>

            <div class="mb-8 text-sm text-ash/80 dark:text-silver/80 leading-relaxed text-center italic">
                Provide your identification below. If recognized by the system, instructions to reset your password will be dispatched immediately.
            </div>

            @if (session('status'))
                <div class="mb-8 p-4 bg-gold/10 border border-gold/30 text-gold text-xs tracking-widest uppercase text-center rounded animate-hero-in">
                    {{ session('status') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="mb-8 p-4 bg-red-900/40 border border-red-500/50 text-red-200 text-xs rounded animate-pulse">
                    @foreach ($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <form method="POST" action="{{ route('password.email') }}" class="space-y-6">
                @csrf

                <!-- Email Address -->
                <div class="space-y-2">
                    <label for="email" class="block text-[0.62rem] tracking-[0.3em] uppercase text-ash/60 dark:text-silver">Identification</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus 
                           placeholder="you@odocorp.co.za"
                           class="w-full bg-charcoal/5 dark:bg-black/50 border border-charcoal/10 dark:border-white/10 p-3.5 text-sm text-charcoal dark:text-white focus:border-gold outline-none transition-all duration-300" />
                </div>

                <!-- Actions -->
                <div class="pt-4">
                    <button type="submit" class="w-full bg-gold text-black py-4 text-[0.7rem] tracking-[0.3em] uppercase font-semibold hover:bg-white transition-all duration-500 shadow-[0_0_20px_rgba(201,168,76,0.15)] hover:shadow-[0_0_30px_rgba(255,255,255,0.2)]">
                        Dispatch Recovery Link →
                    </button>
                    
                    <div class="text-center mt-8">
                        <span class="text-[0.6rem] tracking-widest text-ash/60 dark:text-silver uppercase">Access recalled?</span>
                        <a href="{{ route('login') }}" class="ml-2 text-[0.6rem] tracking-widest uppercase text-gold border-b border-gold/30 hover:border-gold transition-all">Return to Sign In</a>
                    </div>
                </div>
            </form>
        </div>
        
        <div class="text-center mt-12 mb-6">
            <a href="{{ route('home') }}" class="text-[0.6rem] tracking-[0.5em] uppercase text-ash/40 dark:text-silver/40 hover:text-gold transition-colors">
                ← Return to Studio
            </a>
        </div>
    </div>
</div>
@endsection
