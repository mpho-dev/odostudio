@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-charcoal">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="flex items-center justify-between mb-8">
            <div class="flex items-center gap-4">
                <a href="{{ route('admin.equipment.items.index') }}" class="text-ash hover:text-white transition">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                </a>
                <h1 class="text-2xl font-bold text-white">{{ $item->name }}</h1>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('admin.equipment.items.edit', $item) }}" class="bg-white/10 text-white px-4 py-2 rounded hover:bg-white/20 transition">Edit</a>
                <button type="button" onclick="document.getElementById('delete-equipment-{{ $item->id }}').classList.remove('hidden')" class="bg-red-500/20 text-red-400 px-4 py-2 rounded hover:bg-red-500/30 transition">Archive</button>
                <x-confirm-modal
                    id="delete-equipment-{{ $item->id }}"
                    title="Archive Equipment"
                    message="Archive this equipment? This action cannot be undone."
                    confirm-label="Archive"
                    :route="route('admin.equipment.items.destroy', $item)"
                    method="DELETE"
                    variant="danger"
                />
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="glass rounded-lg p-6">
                <h2 class="text-lg font-semibold text-white mb-4">Details</h2>
                <dl class="space-y-3">
                    <div class="flex justify-between">
                        <dt class="text-ash">Category</dt>
                        <dd class="text-white">{{ $item->category->name }}</dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-ash">Model</dt>
                        <dd class="text-white">{{ $item->model ?? 'N/A' }}</dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-ash">Serial Number</dt>
                        <dd class="text-white">{{ $item->serial_number ?? 'N/A' }}</dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-ash">SKU</dt>
                        <dd class="text-white">{{ $item->sku ?? 'N/A' }}</dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-ash">Status</dt>
                        <dd>
                            <span class="px-2 py-1 rounded text-sm {{ $item->status === 'available' ? 'bg-green-500/20 text-green-400' : ($item->status === 'checked_out' ? 'bg-yellow-500/20 text-yellow-400' : 'bg-red-500/20 text-red-400') }}">
                                {{ ucfirst(str_replace('_', ' ', $item->status)) }}
                            </span>
                        </dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-ash">Condition</dt>
                        <dd class="text-white capitalize">{{ $item->condition }}</dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-ash">Storage</dt>
                        <dd class="text-white">{{ $item->storage_location ?? 'N/A' }}</dd>
                    </div>
                </dl>
            </div>

            <div class="glass rounded-lg p-6">
                <h2 class="text-lg font-semibold text-white mb-4">Current Status</h2>
                @if($item->currentCheckout())
                    @php $checkout = $item->currentCheckout(); @endphp
                    <div class="space-y-3">
                        <div class="flex justify-between">
                            <dt class="text-ash">Checked out to</dt>
                            <dd class="text-white">{{ $checkout->user->name }}</dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-ash">For booking</dt>
                            <dd class="text-white">#{{ $checkout->booking->id }}</dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-ash">Expected return</dt>
                            <dd class="text-white {{ $checkout->isOverdue() ? 'text-red-400' : '' }}">{{ $checkout->expected_return_at->format('M d, Y') }}</dd>
                        </div>
                    </div>
                @else
                    <p class="text-ash">Equipment is available.</p>
                @endif
            </div>
        </div>

        @if($item->images->count() > 0)
            <div class="glass rounded-lg p-6 mt-6">
                <h2 class="text-lg font-semibold text-white mb-4">Images</h2>
                <div class="grid grid-cols-4 gap-4">
                    @foreach($item->images as $image)
                        <div class="aspect-square rounded overflow-hidden {{ $image->is_primary ? 'ring-2 ring-gold' : '' }}">
                            <img src="{{ $image->image_path }}" alt="" class="w-full h-full object-cover">
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        @if($item->checkouts->count() > 0)
            <div class="glass rounded-lg p-6 mt-6">
                <h2 class="text-lg font-semibold text-white mb-4">Recent Checkouts</h2>
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="border-b border-white/10">
                                <th class="pb-3 text-ash">User</th>
                                <th class="pb-3 text-ash">Booking</th>
                                <th class="pb-3 text-ash">Checked Out</th>
                                <th class="pb-3 text-ash">Returned</th>
                                <th class="pb-3 text-ash">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($item->checkouts as $checkout)
                                <tr class="border-b border-white/5">
                                    <td class="py-3 text-white">{{ $checkout->user->name }}</td>
                                    <td class="py-3 text-white">#{{ $checkout->booking->id }}</td>
                                    <td class="py-3 text-white">{{ $checkout->checked_out_at->format('M d, Y') }}</td>
                                    <td class="py-3 text-white">{{ $checkout->returned_at ? $checkout->returned_at->format('M d, Y') : '-' }}</td>
                                    <td class="py-3">
                                        <span class="px-2 py-1 rounded text-xs {{ $checkout->status === 'returned' ? 'bg-green-500/20 text-green-400' : ($checkout->isOverdue() ? 'bg-red-500/20 text-red-400' : 'bg-yellow-500/20 text-yellow-400') }}">
                                            {{ $checkout->isOverdue() ? 'Overdue' : ucfirst($checkout->status) }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
