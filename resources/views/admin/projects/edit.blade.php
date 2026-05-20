@extends('layouts.app')

@section('header', 'Refine Case Study')

@section('content')
<div class="max-w-5xl mx-auto space-y-8 pb-24 md:pb-8">
    <div class="flex items-center justify-between">
        <a href="{{ route('projects.index') }}" class="text-xs tracking-[0.3em] uppercase text-silver hover:text-gold transition-colors italic">← Back to Archives</a>
        <div class="text-[0.6rem] tracking-widest uppercase text-gold">ID: {{ $project->id }}</div>
    </div>

    <div class="glass p-10 md:p-12 relative overflow-hidden group">
        <!-- Decorative corner -->
        <div class="absolute top-0 left-0 w-8 h-8 border-t border-l border-gold/40 font-serif"></div>
        
        <form action="{{ route('projects.update', $project) }}" method="POST">
            @csrf
            @method('PUT')
            @include('admin.projects.form')
            
            <div class="mt-12 pt-8 border-t border-white/5 flex justify-end">
                <button type="submit" class="bg-gold text-black px-10 py-4 text-xs tracking-widest uppercase font-bold hover:bg-white transition-all shadow-xl shadow-gold/10">
                    Submit Refinements →
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
