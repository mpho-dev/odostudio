@props([
    'title' => 'Confirm Action',
    'message' => 'Are you sure?',
    'confirmLabel' => 'Confirm',
    'route',
    'method' => 'DELETE',
    'variant' => 'danger',
])

<div x-data="{ show: false }">
    <div x-show="show" x-cloak class="fixed inset-0 z-50" role="dialog" aria-modal="true">
        <div class="absolute inset-0 bg-black/80 backdrop-blur-sm transition-opacity" @click="show = false"></div>

        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-full max-w-md p-4">
            <div class="glass border border-white/10 rounded-xl p-8 shadow-2xl">
                <div class="flex items-center gap-4 text-[0.62rem] tracking-[0.4em] uppercase text-gold mb-4">
                    <span>{{ $variant === 'danger' ? 'Destructive Action' : 'Confirmation' }}</span>
                    <div class="h-px w-16 bg-gold/30"></div>
                </div>

                <h2 class="font-serif text-2xl text-charcoal dark:text-white mb-3">{{ $title }}</h2>
                <p class="text-sm text-ash/70 dark:text-ash/60 mb-8 leading-relaxed">{{ $message }}</p>

                <form action="{{ $route }}" method="POST">
                    @csrf
                    @method($method)

                    <div class="flex justify-end gap-4">
                        <button type="button" @click="show = false" class="px-6 py-3 text-[0.6rem] tracking-[0.3em] uppercase font-bold text-ash/60 hover:text-charcoal dark:hover:text-white transition-colors">
                            Cancel
                        </button>

                        @if($variant === 'danger')
                            <button type="submit" class="group relative px-8 py-3 bg-red-500/10 border border-red-500/30 text-red-400 text-[0.6rem] tracking-[0.3em] uppercase font-bold hover:bg-red-500 hover:text-white transition-all duration-300 overflow-hidden">
                                <span class="relative z-10">{{ $confirmLabel }}</span>
                            </button>
                        @else
                            <button type="submit" class="group relative px-8 py-3 bg-gold text-black text-[0.6rem] tracking-[0.3em] uppercase font-bold hover:bg-white transition-all duration-500 overflow-hidden">
                                <span class="relative z-10">{{ $confirmLabel }}</span>
                                <div class="absolute inset-0 bg-white translate-x-[-100%] group-hover:translate-x-0 transition-transform duration-500"></div>
                            </button>
                        @endif
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{ $trigger ?? '' }}
</div>
