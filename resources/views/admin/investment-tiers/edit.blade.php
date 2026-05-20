@extends('layouts.app')

@section('header', 'Edit Investment Tier: ' . $tier->name)

@section('content')
<div class="max-w-4xl mx-auto space-y-8 animate-hero-in pb-24 md:pb-8">
    <div class="flex items-center gap-4">
        <a href="{{ route('investment-tiers.index') }}" class="text-xs tracking-[0.3em] uppercase text-silver hover:text-gold transition-colors italic">← Back to Investment Tiers</a>
    </div>

    <div class="glass p-10 md:p-12 relative overflow-hidden group">
        <div class="absolute top-0 right-0 w-24 h-px bg-gradient-to-l from-gold/40 to-transparent"></div>
        
        <form action="{{ route('investment-tiers.update', $tier) }}" method="POST" class="space-y-8">
            @csrf
            @method('PUT')
            @include('admin.investment-tiers.form')
            
            <div class="mt-12 pt-8 border-t border-charcoal/10 dark:border-white/10 flex justify-end">
                <button type="submit" class="bg-gold text-black px-10 py-4 text-xs tracking-widest uppercase font-bold hover:bg-white transition-all shadow-xl shadow-gold/10">
                    Update Tier →
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

