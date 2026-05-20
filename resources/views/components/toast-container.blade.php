{{--
Add to your layout (before closing body tag):
<x-toast-container />

Then dispatch events from anywhere:
<button @click="$dispatch('toast', { type: 'success', title: 'Saved!', message: 'Your changes have been saved.' })">
    Save
</button>
--}}

<div
    x-data="{
        toasts: [],
        addToast(event) {
            const id = 'toast-' + Date.now();
            this.toasts.push({
                id: id,
                type: event.detail.type || 'info',
                title: event.detail.title || '',
                message: event.detail.message || '',
                duration: event.detail.duration || 5000
            });
            setTimeout(() => {
                this.removeToast(id);
            }, event.detail.duration || 5000);
        },
        removeToast(id) {
            this.toasts = this.toasts.filter(t => t.id !== id);
        }
    }"
    x-on:toast.window="addToast($event)"
    class="toast-container"
>
    <template x-for="toast in toasts" :key="toast.id">
        <div
            x-show="true"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 translate-x-full"
            x-transition:enter-end="opacity-100 translate-x-0"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 translate-x-0"
            x-transition:leave-end="opacity-0 translate-x-full"
            :class="'toast toast-' + toast.type"
        >
            {{-- Icon --}}
            <div class="toast-icon">
                <template x-if="toast.type === 'success'">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                </template>
                <template x-if="toast.type === 'error'">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </template>
                <template x-if="toast.type === 'warning'">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </template>
                <template x-if="toast.type === 'info'">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </template>
            </div>

            {{-- Content --}}
            <div class="toast-content">
                <p x-show="toast.title" x-text="toast.title" class="toast-title"></p>
                <p x-show="toast.message" x-text="toast.message" class="toast-message"></p>
            </div>

            {{-- Close button --}}
            <button @click="removeToast(toast.id)" class="toast-close" aria-label="Close">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
    </template>
</div>
