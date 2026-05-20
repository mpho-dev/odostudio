@props([
    'id',
    'title' => 'Notice',
    'message' => '',
    'type' => 'info',
])

<div id="{{ $id }}" class="fixed inset-0 z-50 hidden" role="dialog" aria-modal="true">
    <div class="absolute inset-0 bg-black/80 backdrop-blur-sm transition-opacity" onclick="document.getElementById('{{ $id }}').classList.add('hidden')"></div>

    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-full max-w-md p-4">
        <div class="glass border border-white/10 rounded-xl p-8 shadow-2xl">
            <div class="flex items-center gap-4 text-[0.62rem] tracking-[0.4em] uppercase mb-4 {{ $type === 'error' ? 'text-red-400' : 'text-gold' }}">
                <span>{{ $type === 'error' ? 'Error' : 'Success' }}</span>
                <div class="h-px w-16 {{ $type === 'error' ? 'bg-red-400/30' : 'bg-gold/30' }}"></div>
            </div>

            <h2 class="font-serif text-2xl text-charcoal dark:text-white mb-3">{{ $title }}</h2>
            <p class="text-sm text-ash/70 dark:text-ash/60 mb-8 leading-relaxed">{{ $message }}</p>

            <div class="flex justify-end">
                <button type="button" onclick="document.getElementById('{{ $id }}').classList.add('hidden')" class="px-8 py-3 text-[0.6rem] tracking-[0.3em] uppercase font-bold {{ $type === 'error' ? 'bg-red-500/10 border border-red-500/30 text-red-400 hover:bg-red-500 hover:text-white' : 'bg-gold text-black hover:bg-white' }} transition-all duration-300">
                    Dismiss
                </button>
            </div>
        </div>
    </div>
</div>
