@extends('layouts.app')

@section('title', 'Billing & Retainers — Odo Studio')

@section('header', 'Studio Ledger')

@section('content')
<div class="space-y-8 animate-hero-in pb-24 md:pb-8">
    <div class="flex justify-between items-center reveal">
        <div>
            <h2 class="font-serif text-3xl text-charcoal dark:text-white leading-tight">Billing <em>Records</em></h2>
            <p class="text-xs tracking-[0.3em] uppercase text-ash/60 dark:text-ash/60 mt-1 italic">Track retainers, balances, and settlements</p>
        </div>
        <a href="{{ route('bookings.index') }}" class="bg-charcoal/5 dark:bg-white/5 border border-charcoal/10 dark:border-white/10 text-charcoal/70 dark:text-silver px-6 py-3 text-[0.6rem] tracking-[0.3em] uppercase font-bold hover:bg-gold/10 hover:text-gold hover:border-gold transition-all italic">
            Create Invoice →
        </a>
    </div>

    @if ($invoices->isEmpty())
        <div class="py-32 text-center glass rounded-xl border border-dashed border-charcoal/20 dark:border-white/10 reveal">
            <p class="font-serif text-xl text-ash/60 dark:text-ash/40 italic opacity-40">The financial ledger is currently empty.</p>
        </div>
    @else
        <!-- Mobile Card View -->
        <div class="block md:hidden space-y-4">
            @foreach ($invoices as $invoice)
                <div class="glass p-6 rounded-xl border border-charcoal/10 dark:border-white/10 relative overflow-hidden group shadow-2xl">
                    <div class="absolute top-0 left-0 w-8 h-8 border-t border-l border-gold/40 group-hover:w-12 group-hover:h-12 transition-all duration-500"></div>
                    <div class="absolute bottom-0 right-0 w-8 h-8 border-b border-r border-gold/40 group-hover:w-12 group-hover:h-12 transition-all duration-500"></div>
                    <div class="flex justify-between items-start mb-4">
                        <div>
                            <p class="font-serif text-lg text-charcoal dark:text-white leading-tight">#{{ $invoice->invoice_number }}</p>
                            <p class="text-[0.6rem] tracking-widest uppercase text-ash/60 dark:text-ash/40 mt-1 italic">{{ $invoice->created_at->format('M d, Y') }}</p>
                        </div>
                        <span class="px-3 py-1 rounded-full text-[0.55rem] tracking-[0.2em] uppercase font-bold shrink-0
                            @if ($invoice->status === 'paid')
                                bg-green-500/10 text-green-500 border border-green-500/20
                            @elseif ($invoice->status === 'sent')
                                bg-blue-500/10 text-blue-500 border border-blue-500/20
                            @else
                                bg-gold/10 text-gold border border-gold/20
                            @endif
                        ">
                            {{ $invoice->status }}
                        </span>
                    </div>
                    
                    <div class="space-y-3 mb-4">
                        <div>
                            <p class="text-[0.55rem] tracking-[0.3em] uppercase text-gold/60">Client / Shoot</p>
                            <p class="text-sm text-charcoal/70 dark:text-silver">{{ $invoice->booking->bookingRequest->name }} {{ $invoice->booking->bookingRequest->surname }}</p>
                            <p class="text-[0.6rem] text-ash/60 dark:text-ash/30 tracking-widest">{{ $invoice->booking->location }}</p>
                        </div>
                        <div>
                            <p class="text-[0.55rem] tracking-[0.3em] uppercase text-gold/60">Amount</p>
                            <p class="font-serif text-xl text-charcoal dark:text-white">R{{ number_format($invoice->amount, 2) }}</p>
                        </div>
                    </div>
                    
                    <div class="flex flex-wrap gap-2">
                        <a href="{{ route('invoices.show', $invoice) }}" class="flex-1 text-center border border-charcoal/10 dark:border-white/10 hover:border-gold hover:text-gold text-charcoal/40 dark:text-ash/40 px-4 py-2 text-[0.55rem] tracking-[0.3em] uppercase font-bold transition-all">Review</a>
                        
                        <a href="{{ route('invoices.pdf', $invoice) }}" class="flex-1 text-center border border-charcoal/10 dark:border-white/10 hover:border-gold hover:text-gold text-charcoal/40 dark:text-ash/40 px-4 py-2 text-[0.55rem] tracking-[0.3em] uppercase font-bold transition-all">PDF</a>

                        @if ($invoice->status === 'draft')
                            <form action="{{ route('invoices.sent', $invoice) }}" method="POST" class="flex-1">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="w-full border border-charcoal/10 dark:border-white/10 hover:border-blue-500 hover:text-blue-500 text-charcoal/40 dark:text-ash/40 px-4 py-2 text-[0.55rem] tracking-[0.3em] uppercase font-bold transition-all">Mark Sent</button>
                            </form>
                        @endif

                        @if ($invoice->status !== 'paid')
                            <form action="{{ route('invoices.pay', $invoice) }}" method="POST" class="flex-1">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="w-full border border-charcoal/10 dark:border-white/10 hover:border-green-500 hover:text-green-500 text-charcoal/40 dark:text-ash/40 px-4 py-2 text-[0.55rem] tracking-[0.3em] uppercase font-bold transition-all">Mark Paid</button>
                            </form>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
        
        <!-- Desktop Table View -->
        <div class="hidden md:block glass overflow-hidden rounded-xl border border-charcoal/10 dark:border-white/10 reveal">
            <table class="min-w-full divide-y divide-charcoal/10 dark:divide-white/5">
                <thead>
                    <tr class="bg-charcoal/5 dark:bg-white/5">
                        <th class="px-8 py-5 text-left text-[0.6rem] tracking-[0.4em] uppercase text-gold font-bold">Invoice #</th>
                        <th class="px-8 py-5 text-left text-[0.6rem] tracking-[0.4em] uppercase text-gold font-bold">Client / Shoot</th>
                        <th class="px-8 py-5 text-left text-[0.6rem] tracking-[0.4em] uppercase text-gold font-bold">Amount</th>
                        <th class="px-8 py-5 text-left text-[0.6rem] tracking-[0.4em] uppercase text-gold font-bold">Payment Status</th>
                        <th class="px-8 py-5 text-right text-[0.6rem] tracking-[0.4em] uppercase text-gold font-bold">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-charcoal/10 dark:divide-white/5">
                    @foreach ($invoices as $invoice)
                        <tr class="hover:bg-gold/5 transition-colors group">
                            <td class="px-8 py-6">
                                <p class="font-serif text-lg text-charcoal dark:text-white leading-tight group-hover:text-gold transition-colors">#{{ $invoice->invoice_number }}</p>
                                <p class="text-[0.6rem] tracking-widest uppercase text-ash/60 dark:text-ash/40 mt-1 italic">{{ $invoice->created_at->format('M d, Y') }}</p>
                            </td>
                            <td class="px-8 py-6">
                                <p class="text-sm text-charcoal/70 dark:text-silver">{{ $invoice->booking->bookingRequest->name }} {{ $invoice->booking->bookingRequest->surname }}</p>
                                <p class="text-[0.6rem] text-ash/60 dark:text-ash/30 mt-1 tracking-widest">{{ $invoice->booking->location }}</p>
                            </td>
                            <td class="px-8 py-6">
                                <p class="font-serif text-lg text-charcoal dark:text-white">R{{ number_format($invoice->amount, 2) }}</p>
                            </td>
                            <td class="px-8 py-6">
                                <span class="px-3 py-1 rounded-full text-[0.55rem] tracking-[0.2em] uppercase font-bold 
                                    @if ($invoice->status === 'paid')
                                        bg-green-500/10 text-green-500 border border-green-500/20
                                    @elseif ($invoice->status === 'sent')
                                        bg-blue-500/10 text-blue-500 border border-blue-500/20
                                    @else
                                        bg-gold/10 text-gold border border-gold/20
                                    @endif
                                ">
                                    {{ $invoice->status }}
                                </span>
                            </td>
                            <td class="px-8 py-6 text-right space-x-2">
                                <a href="{{ route('invoices.show', $invoice) }}" class="inline-block border border-charcoal/10 dark:border-white/10 hover:border-gold hover:text-gold text-charcoal/40 dark:text-ash/40 px-4 py-2 text-[0.55rem] tracking-[0.3em] uppercase font-bold transition-all">Review</a>
                                
                                <a href="{{ route('invoices.pdf', $invoice) }}" class="inline-block border border-charcoal/10 dark:border-white/10 hover:border-gold hover:text-gold text-charcoal/40 dark:text-ash/40 px-4 py-2 text-[0.55rem] tracking-[0.3em] uppercase font-bold transition-all">PDF</a>

                                @if ($invoice->status === 'draft')
                                    <form action="{{ route('invoices.sent', $invoice) }}" method="POST" class="inline">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="border border-charcoal/10 dark:border-white/10 hover:border-blue-500 hover:text-blue-500 text-charcoal/40 dark:text-ash/40 px-4 py-2 text-[0.55rem] tracking-[0.3em] uppercase font-bold transition-all">Mark Sent</button>
                                    </form>
                                @endif

                                @if ($invoice->status !== 'paid')
                                    <form action="{{ route('invoices.pay', $invoice) }}" method="POST" class="inline">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="border border-charcoal/10 dark:border-white/10 hover:border-green-500 hover:text-green-500 text-charcoal/40 dark:text-ash/40 px-4 py-2 text-[0.55rem] tracking-[0.3em] uppercase font-bold transition-all">Mark Paid</button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        <div class="pagination-container mt-8 reveal">
            {{ $invoices->links() }}
        </div>
    @endif
</div>
@endsection
