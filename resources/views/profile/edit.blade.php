@extends('layouts.app')

@section('header', 'Identity & Security')

@section('content')
<div class="max-w-4xl mx-auto space-y-12 animate-hero-in pb-20">
    <!-- Header Section -->
    <div class="reveal">
        <h1 class="font-serif text-3xl text-charcoal dark:text-white leading-tight">Personal <em>Credentials</em></h1>
        <p class="text-xs tracking-[0.3em] uppercase text-ash/60 dark:text-ash/60 mt-2 italic">Maintain your digital footprint within the studio</p>
    </div>

    <!-- Profile Information -->
    <div class="glass p-10 md:p-12 relative overflow-hidden group border border-charcoal/10 dark:border-white/10 rounded-xl shadow-2xl">
        <div class="absolute top-0 left-0 w-8 h-8 border-t border-l border-gold/40 group-hover:w-12 group-hover:h-12 transition-all duration-500"></div>
        <div class="absolute bottom-0 right-0 w-8 h-8 border-b border-r border-gold/40 group-hover:w-12 group-hover:h-12 transition-all duration-500"></div>
        
        <div class="max-w-xl">
            <h2 class="text-[0.6rem] tracking-[0.4em] uppercase text-gold font-bold mb-8">Base Profile</h2>
            
            @if (session('status') === 'profile-updated')
                <div class="mb-8 p-4 bg-gold/10 border-l border-gold text-gold text-[0.65rem] tracking-widest uppercase animate-hero-in">
                    ✓ Identity Synchronized
                </div>
            @endif

            @if (session('error'))
                <div class="mb-8 p-4 bg-red-500/10 border-l border-red-500 text-red-500 text-[0.65rem] tracking-widest uppercase animate-hero-in">
                    ✕ {{ session('error') }}
                </div>
            @endif

            <form action="{{ route('profile.update') }}" method="POST" class="space-y-8">
                @csrf
                @method('PATCH')

                <div class="space-y-3">
                    <label for="name" class="block text-[0.6rem] tracking-[0.4em] uppercase text-ash/60 dark:text-silver font-bold">Studio Handle (Name)</label>
                    <input id="name" type="text" name="name" value="{{ old('name', auth()->user()->name) }}" required autofocus 
                           class="w-full bg-charcoal/5 dark:bg-black/40 border border-charcoal/10 dark:border-white/10 p-4 text-sm text-charcoal dark:text-white focus:border-gold outline-none transition-all placeholder:italic" />
                    @error('name')
                        <p class="text-red-500 text-[0.6rem] tracking-widest uppercase mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <div class="space-y-3">
                    <label for="email" class="block text-[0.6rem] tracking-[0.4em] uppercase text-ash/60 dark:text-silver font-bold">Communication Link (Email)</label>
                    <input id="email" type="email" name="email" value="{{ old('email', auth()->user()->email) }}" required 
                           class="w-full bg-charcoal/5 dark:bg-black/40 border border-charcoal/10 dark:border-white/10 p-4 text-sm text-charcoal dark:text-white focus:border-gold outline-none transition-all placeholder:italic" />
                    @error('email')
                        <p class="text-red-500 text-[0.6rem] tracking-widest uppercase mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <div class="pt-6">
                    <button type="submit" class="bg-gold text-black px-10 py-4 text-xs tracking-widest uppercase font-bold hover:bg-white transition-all shadow-xl shadow-gold/10">
                        Update Identity →
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Security Tier: Password Update -->
    <div class="glass p-10 md:p-12 relative overflow-hidden group border border-charcoal/10 dark:border-white/10 rounded-xl shadow-2xl">
        <div class="absolute top-0 left-0 w-8 h-8 border-t border-l border-gold/40 group-hover:w-12 group-hover:h-12 transition-all duration-500"></div>
        <div class="absolute bottom-0 right-0 w-8 h-8 border-b border-r border-gold/40 group-hover:w-12 group-hover:h-12 transition-all duration-500"></div>
        <div class="max-w-xl">
            <h2 class="text-[0.6rem] tracking-[0.4em] uppercase text-gold font-bold mb-8">Security Signature</h2>
            
            <p class="text-xs text-ash/80 dark:text-silver leading-relaxed mb-6 italic">Maintain a robust password to ensure your account's integrity.</p>

            @if (session('status') === 'password-updated')
                <div class="mb-8 p-4 bg-gold/10 border-l border-gold text-gold text-[0.65rem] tracking-widest uppercase animate-hero-in">
                    ✓ Signature Updated
                </div>
            @endif

            <form action="{{ route('password.update') }}" method="POST" class="space-y-6">
                @csrf
                @method('PUT')

                <div class="space-y-3" x-data="{ show: false }">
                    <label for="current_password" class="block text-[0.6rem] tracking-[0.4em] uppercase text-ash/60 dark:text-silver font-bold">Current Password</label>
                    <div class="relative">
                        <input id="current_password" :type="show ? 'text' : 'password'" name="current_password" required 
                               class="w-full bg-charcoal/5 dark:bg-black/40 border border-charcoal/10 dark:border-white/10 p-4 pr-12 text-sm text-charcoal dark:text-white focus:border-gold outline-none transition-all placeholder:italic" 
                               placeholder="••••••••" />
                        <button type="button" @click="show = !show" class="absolute inset-y-0 right-0 flex items-center pr-4 text-ash/60 hover:text-gold transition-colors">
                            <svg x-show="!show" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                            <svg x-show="show" x-cloak class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"></path></svg>
                        </button>
                    </div>
                    @error('current_password', 'updatePassword')
                        <p class="text-red-500 text-[0.6rem] tracking-widest uppercase mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-3" x-data="{ show: false }">
                        <label for="password" class="block text-[0.6rem] tracking-[0.4em] uppercase text-ash/60 dark:text-silver font-bold">New Password</label>
                        <div class="relative">
                            <input id="password" :type="show ? 'text' : 'password'" name="password" required 
                                   class="w-full bg-charcoal/5 dark:bg-black/40 border border-charcoal/10 dark:border-white/10 p-4 pr-12 text-sm text-charcoal dark:text-white focus:border-gold outline-none transition-all placeholder:italic" 
                                   placeholder="Min 8 characters" />
                            <button type="button" @click="show = !show" class="absolute inset-y-0 right-0 flex items-center pr-4 text-ash/60 hover:text-gold transition-colors">
                                <svg x-show="!show" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                <svg x-show="show" x-cloak class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"></path></svg>
                            </button>
                        </div>
                        @error('password', 'updatePassword')
                            <p class="text-red-500 text-[0.6rem] tracking-widest uppercase mt-2">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="space-y-3" x-data="{ show: false }">
                        <label for="password_confirmation" class="block text-[0.6rem] tracking-[0.4em] uppercase text-ash/60 dark:text-silver font-bold">Confirm New Password</label>
                        <div class="relative">
                            <input id="password_confirmation" :type="show ? 'text' : 'password'" name="password_confirmation" required 
                                   class="w-full bg-charcoal/5 dark:bg-black/40 border border-charcoal/10 dark:border-white/10 p-4 pr-12 text-sm text-charcoal dark:text-white focus:border-gold outline-none transition-all placeholder:italic" 
                                   placeholder="Repeat Password" />
                            <button type="button" @click="show = !show" class="absolute inset-y-0 right-0 flex items-center pr-4 text-ash/60 hover:text-gold transition-colors">
                                <svg x-show="!show" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                <svg x-show="show" x-cloak class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"></path></svg>
                            </button>
                        </div>
                    </div>
                </div>

                <div class="pt-4">
                    <button type="submit" class="bg-gray-800 dark:bg-charcoal/40 border border-gray-700 dark:border-white/10 text-white px-8 py-3 text-xs tracking-widest uppercase font-bold hover:bg-gold hover:text-black hover:border-gold transition-all shadow-lg">
                        Update Signature
                    </button>
                </div>
            </form>
        </div>
    </div>

    @unless(auth()->user()->hasRole('admin'))
    <!-- Security Tier -->
    <div class="glass p-10 md:p-12 relative overflow-hidden group border border-charcoal/10 dark:border-white/10 rounded-xl opacity-60 hover:opacity-100 transition-opacity shadow-2xl">
        <div class="absolute top-0 left-0 w-8 h-8 border-t border-l border-gold/40 group-hover:w-12 group-hover:h-12 transition-all duration-500"></div>
        <div class="absolute bottom-0 right-0 w-8 h-8 border-b border-r border-gold/40 group-hover:w-12 group-hover:h-12 transition-all duration-500"></div>
        <div class="max-w-xl">
            <h2 class="text-[0.6rem] tracking-[0.4em] uppercase text-red-500 font-bold mb-4">Termination Zone</h2>
            <p class="text-xs text-ash/80 dark:text-silver leading-relaxed mb-8 italic">Dissolving your account is a permanent action. All associated narratives will be purged from the archive.</p>
            
            <form action="{{ route('profile.destroy') }}" method="POST" x-data="{ showConfirm: false }">
                @csrf
                @method('DELETE')
                
                <div class="mb-6" x-data="{ show: false }">
                    <label for="password_deletion" class="block text-[0.62rem] tracking-[0.3em] uppercase text-ash/60 dark:text-silver mb-2">Confirm Password</label>
                    <div class="relative max-w-xs">
                        <input id="password_deletion" :type="show ? 'text' : 'password'" name="password" required 
                               class="w-full bg-charcoal/5 dark:bg-black/40 border border-charcoal/10 dark:border-white/10 p-3 pr-10 text-sm text-charcoal dark:text-white focus:border-red-500 outline-none transition-all placeholder:italic" 
                               placeholder="••••••••" />
                        <button type="button" @click="show = !show" class="absolute inset-y-0 right-0 flex items-center pr-3 text-ash/60 hover:text-red-500 transition-colors">
                            <svg x-show="!show" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                            <svg x-show="show" x-cloak class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"></path></svg>
                        </button>
                    </div>
                    @if($errors->userDeletion->has('password'))
                        <p class="text-red-500 text-[0.6rem] tracking-widest uppercase mt-2">{{ $errors->userDeletion->first('password') }}</p>
                    @endif
                </div>

                <button type="button" 
                        class="text-[0.65rem] tracking-[0.3em] uppercase text-red-500/60 border border-red-500/20 px-8 py-3 hover:bg-red-500 hover:text-white hover:border-red-500 transition-all font-bold" 
                        @click="showConfirm = true">
                    Terminate Account
                </button>

                <!-- Confirm Modal -->
                <div x-show="showConfirm" x-cloak class="fixed inset-0 z-50" role="dialog" aria-modal="true">
                    <div class="absolute inset-0 bg-black/80 backdrop-blur-sm transition-opacity" @click="showConfirm = false"></div>
                    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-full max-w-md p-4">
                        <div class="glass bg-charcoal dark:bg-charcoal border border-charcoal/20 dark:border-white/10 rounded-xl p-8 shadow-2xl">
                            <div class="flex items-center gap-4 text-[0.62rem] tracking-[0.4em] uppercase text-red-400 mb-4">
                                <span>Destructive Action</span>
                                <div class="h-px w-16 bg-red-400/30"></div>
                            </div>
                            <h2 class="font-serif text-2xl text-charcoal dark:text-white mb-3">Terminate Account</h2>
                            <p class="text-sm text-ash/70 dark:text-ash/60 mb-8 leading-relaxed">Are you absolutely certain you wish to terminate this account? All associated data will be permanently purged.</p>
                            <div class="flex justify-end gap-4">
                                <button type="button" @click="showConfirm = false" class="px-6 py-3 text-[0.6rem] tracking-[0.3em] uppercase font-bold text-ash/60 hover:text-charcoal dark:hover:text-white transition-colors">
                                    Cancel
                                </button>
                                <button type="submit" class="group relative px-8 py-3 bg-red-500/10 border border-red-500/30 text-red-400 text-[0.6rem] tracking-[0.3em] uppercase font-bold hover:bg-red-500 hover:text-white transition-all duration-300 overflow-hidden">
                                    <span class="relative z-10">Terminate</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
    @else
    <!-- Protected Tier for Admins -->
    <div class="glass p-10 md:p-12 relative overflow-hidden border border-charcoal/10 dark:border-white/10 rounded-xl shadow-2xl group">
        <div class="absolute top-0 left-0 w-8 h-8 border-t border-l border-gold/40 group-hover:w-12 group-hover:h-12 transition-all duration-500"></div>
        <div class="absolute bottom-0 right-0 w-8 h-8 border-b border-r border-gold/40 group-hover:w-12 group-hover:h-12 transition-all duration-500"></div>
        <div class="flex items-center gap-4 text-gold/40">
            <div class="w-10 h-10 border border-gold/20 flex items-center justify-center rounded-full text-lg">🛡️</div>
            <div>
                <p class="text-[0.6rem] tracking-[0.3em] uppercase font-bold text-gold">Protected Identity</p>
                <p class="text-xs text-ash/60 dark:text-ash/60 italic">Administrative accounts are anchored to the system for operational stability.</p>
            </div>
        </div>
    </div>
    @endunless
</div>
@endsection
