@extends('layouts.app')

@section('title', 'Email Infrastructure — Odo Studio')

@section('header', 'Comm Infrastructure')

@section('content')
<div class="max-w-6xl mx-auto space-y-12 animate-hero-in">
    <!-- Header Section -->
    <div class="reveal">
        <div class="flex items-center gap-4 text-[0.62rem] tracking-[0.4em] uppercase text-gold mb-4">
            <span>Executive Controls</span>
            <div class="h-px w-16 bg-gold/30"></div>
        </div>
        <h1 class="font-serif text-4xl text-charcoal dark:text-white leading-tight">Automated <em>Correspondence</em></h1>
        <p class="text-xs tracking-[0.3em] uppercase text-ash/60 dark:text-ash/60 mt-2 italic">Configure the automated narrative for client communications</p>
    </div>

    <!-- Main Control Container -->
    <div class="glass relative overflow-hidden group border border-charcoal/10 dark:border-white/10 rounded-2xl shadow-2xl">
        <!-- Corner Accents -->
        <div class="absolute top-0 left-0 w-12 h-12 border-t border-l border-gold/20 group-hover:w-16 group-hover:h-16 transition-all duration-700 z-0"></div>
        <div class="absolute bottom-0 right-0 w-12 h-12 border-b border-r border-gold/20 group-hover:w-16 group-hover:h-16 transition-all duration-700 z-0"></div>

        <div class="grid grid-cols-1 lg:grid-cols-12 min-h-[600px]">
            <!-- Sidebar Navigation -->
            <div class="lg:col-span-3 border-r border-charcoal/10 dark:border-white/5 bg-black/20 backdrop-blur-sm p-6 space-y-8 relative z-10">
                    <h3 class="text-[0.55rem] tracking-[0.4em] uppercase text-gold font-bold mb-6 opacity-50 px-4">Infrastructure</h3>
                    <nav class="space-y-1">
                        <button type="button" onclick="switchTab('system')" id="tab-system" 
                                class="w-full flex items-center gap-3 px-4 py-3 text-[0.6rem] tracking-[0.2em] uppercase font-bold transition-all border-l-2 border-gold bg-gold/5 text-white group/btn text-left">
                            <div class="w-6 h-6 flex items-center justify-center shrink-0">
                                <svg class="w-4 h-4 text-gold" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37a1.724 1.724 0 002.572-1.065z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                            </div>
                            System Settings
                        </button>
                    </nav>
                </div>

                <div>
                    <h3 class="text-[0.55rem] tracking-[0.4em] uppercase text-gold font-bold mb-6 opacity-50 px-4 mt-8">Narratives</h3>
                    <nav class="space-y-1">
                        @foreach($templates as $template)
                            @php
                                $icon = match($template->slug) {
                                    'booking_request_admin' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />',
                                    'visitor_request_confirmation' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />',
                                    'invoice_sent' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />',
                                    'booking_confirmed' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />',
                                    default => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />'
                                };
                            @endphp
                            <button type="button" onclick="switchTab('{{ $template->slug }}')" id="tab-{{ $template->slug }}" 
                                    class="w-full flex items-center gap-3 px-4 py-3 text-[0.6rem] tracking-[0.2em] uppercase font-bold transition-all border-l-2 border-transparent text-ash/40 hover:text-white hover:bg-white/5 group/btn text-left">
                                <div class="w-6 h-6 flex items-center justify-center shrink-0">
                                    <svg class="w-4 h-4 text-current group-hover/btn:text-gold transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        {!! $icon !!}
                                    </svg>
                                </div>
                                <span class="truncate">{{ $template->name }}</span>
                            </button>
                        @endforeach
                    </nav>
                </div>

                <div class="absolute bottom-8 left-8 right-8">
                    <p class="text-[0.5rem] tracking-[0.2em] uppercase text-ash/30 leading-relaxed italic">Changes take effect across the entire communication grid immediately.</p>
                </div>
            </div>

            <!-- Content Area -->
            <div class="lg:col-span-9 p-8 md:p-12 relative z-10">
                <form method="POST" action="{{ route('admin.email-config.update') }}" id="emailConfigForm" class="h-full flex flex-col">
                    @csrf

                    <!-- System Settings Tab -->
                    <div id="content-system" class="tab-content space-y-10">
                        <div class="border-b border-white/5 pb-8">
                            <h2 class="font-serif text-2xl text-white mb-2">System <em>Architecture</em></h2>
                            <p class="text-[0.65rem] tracking-[0.2em] uppercase text-ash/40">Core dispatch and routing parameters</p>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                            <div class="space-y-4">
                                <label for="booking_request_email_from" class="block text-[0.6rem] tracking-[0.4em] uppercase text-gold font-bold">Booking From Address</label>
                                <input id="booking_request_email_from" type="email" name="booking_request_email_from" value="{{ old('booking_request_email_from', $emailFrom) }}" 
                                       class="w-full bg-black/40 border border-white/10 p-4 text-sm text-white focus:border-gold outline-none transition-all placeholder:text-ash/20" placeholder="dispatch@odo-studio.com" />
                                <p class="text-[0.5rem] tracking-widest text-ash/40 uppercase italic">Sender identity for initial leads</p>
                            </div>
                            <div class="space-y-4">
                                <label for="booking_request_email_to" class="block text-[0.6rem] tracking-[0.4em] uppercase text-gold font-bold">Booking Admin To</label>
                                <input id="booking_request_email_to" type="email" name="booking_request_email_to" value="{{ old('booking_request_email_to', $emailTo) }}" 
                                       class="w-full bg-black/40 border border-white/10 p-4 text-sm text-white focus:border-gold outline-none transition-all placeholder:text-ash/20" placeholder="studio@odo-studio.com" />
                                <p class="text-[0.5rem] tracking-widest text-ash/40 uppercase italic">Where notification alerts are delivered</p>
                            </div>
                            <div class="space-y-4">
                                <label for="invoice_sent_email_from" class="block text-[0.6rem] tracking-[0.4em] uppercase text-gold font-bold">Invoice From Address</label>
                                <input id="invoice_sent_email_from" type="email" name="invoice_sent_email_from" value="{{ old('invoice_sent_email_from', $invoiceEmailFrom) }}" 
                                       class="w-full bg-black/40 border border-white/10 p-4 text-sm text-white focus:border-gold outline-none transition-all placeholder:text-ash/20" placeholder="billing@odo-studio.com" />
                                <p class="text-[0.5rem] tracking-widest text-ash/40 uppercase italic">Dispatch address for financial settlements</p>
                            </div>
                        </div>
                    </div>

                    <!-- Dynamic Template Tabs -->
                    @foreach($templates as $template)
                    <div id="content-{{ $template->slug }}" class="tab-content space-y-10 hidden">
                        <div class="border-b border-white/5 pb-8 flex justify-between items-end">
                            <div>
                                <h2 class="font-serif text-2xl text-white mb-2">{{ $template->name }} <em>Narrative</em></h2>
                                <p class="text-[0.65rem] tracking-[0.2em] uppercase text-ash/40">{{ $template->description ?? 'Customizable automated response' }}</p>
                            </div>
                            <div class="flex gap-4">
                                <button type="button" onclick="previewEmail('{{ $template->slug }}')" 
                                        class="px-4 py-2 border border-gold/30 text-gold text-[0.55rem] tracking-[0.3em] uppercase font-bold hover:bg-gold hover:text-black transition-all italic">
                                    Preview
                                </button>
                                <button type="button" onclick="openTestEmailModal('{{ $template->slug }}')" 
                                        class="px-4 py-2 border border-gold/30 text-gold text-[0.55rem] tracking-[0.3em] uppercase font-bold hover:bg-gold hover:text-black transition-all italic">
                                    Test
                                </button>
                            </div>
                        </div>

                        <div class="space-y-8">
                            <div class="space-y-4">
                                <label class="block text-[0.6rem] tracking-[0.4em] uppercase text-gold font-bold">Subject Narrative</label>
                                <input type="text" name="templates[{{ $template->slug }}][subject]" value="{{ old('templates.' . $template->slug . '.subject', $template->subject) }}" 
                                       class="w-full bg-black/40 border border-white/10 p-5 text-sm text-white focus:border-gold outline-none transition-all font-serif text-lg italic" />
                            </div>

                            <div class="space-y-4">
                                <div class="flex justify-between items-end">
                                    <label class="block text-[0.6rem] tracking-[0.4em] uppercase text-gold font-bold">Body Template</label>
                                    <button type="button" onclick="toggleDocs('docs-{{ $template->slug }}')" 
                                            class="text-[0.55rem] tracking-[0.3em] uppercase text-ash/60 hover:text-gold transition-colors font-bold">Tags & Tokens ↓</button>
                                </div>
                                
                                <div id="docs-{{ $template->slug }}" class="hidden p-6 bg-black/40 border border-gold/10 rounded-xl animate-hero-in">
                                    <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                                        @foreach($template->placeholders as $tag => $desc)
                                            <div class="flex flex-col gap-1 border-b border-white/5 pb-2">
                                                <span class="text-white font-mono text-[0.6rem] tracking-wider">{{ $tag }}</span>
                                                <span class="text-ash/40 text-[0.5rem] uppercase tracking-widest">{{ $desc }}</span>
                                            </div>
                                        @endforeach
                                        <div class="flex flex-col gap-1 border-b border-white/5 pb-2"><span class="text-white font-mono text-[0.6rem]">{site_name}</span><span class="text-ash/40 text-[0.5rem] uppercase tracking-widest">Studio Identity</span></div>
                                        <div class="flex flex-col gap-1 border-b border-white/5 pb-2"><span class="text-white font-mono text-[0.6rem]">{year}</span><span class="text-ash/40 text-[0.5rem] uppercase tracking-widest">Temporal Year</span></div>
                                    </div>
                                </div>

                                <div class="bg-white rounded-xl overflow-hidden shadow-2xl border-4 border-charcoal">
                                    <div class="quill-editor-container h-[350px] text-black" data-target="template_{{ $template->slug }}_body"></div>
                                </div>
                                <input type="hidden" id="template_{{ $template->slug }}_body" name="templates[{{ $template->slug }}][body]" value="{{ old('templates.' . $template->slug . '.body', $template->body) }}">
                            </div>
                        </div>
                    </div>
                    @endforeach

                    <!-- Submit Button -->
                    <div class="mt-auto pt-10 flex justify-end">
                        <button type="submit" class="group relative px-12 py-5 bg-gold text-black text-[0.7rem] tracking-[0.4em] uppercase font-bold hover:bg-white transition-all duration-500 overflow-hidden shadow-2xl shadow-gold/20">
                            <span class="relative z-10">Seal Infrastructure Configurations →</span>
                            <div class="absolute inset-0 bg-white translate-x-[-100%] group-hover:translate-x-0 transition-transform duration-500"></div>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function switchTab(slug) {
        // Hide all content
        document.querySelectorAll('.tab-content').forEach(c => c.classList.add('hidden'));
        // Show target content
        document.getElementById('content-' + slug).classList.remove('hidden');

        // Reset all buttons
        document.querySelectorAll('[id^="tab-"]').forEach(t => {
            t.className = 'w-full flex items-center gap-4 px-4 py-4 text-[0.65rem] tracking-[0.2em] uppercase font-bold transition-all border-l-2 border-transparent text-ash/40 hover:text-white hover:bg-white/5 group/btn';
            const icon = t.querySelector('svg');
            if (icon) icon.className = 'w-4 h-4 text-current group-hover/btn:text-gold transition-colors';
        });

        // Set active button
        const activeBtn = document.getElementById('tab-' + slug);
        activeBtn.className = 'w-full flex items-center gap-4 px-4 py-4 text-[0.65rem] tracking-[0.2em] uppercase font-bold transition-all border-l-2 border-gold bg-gold/5 text-white group/btn';
        const activeIcon = activeBtn.querySelector('svg');
        if (activeIcon) activeIcon.className = 'w-4 h-4 text-gold';
    }

    function toggleDocs(id) {
        document.getElementById(id).classList.toggle('hidden');
    }

    async function previewEmail(slug) {
        try {
            const response = await fetch(`/admin/email-config/preview/${slug}`, {
                method: 'GET',
                headers: { 'Accept': 'application/json' }
            });

            const data = await response.json();
            
            const modal = `
                <div class="fixed inset-0 bg-black/95 backdrop-blur-md flex items-center justify-center z-50 p-4 animate-hero-in" id="previewModal">
                    <div class="bg-charcoal rounded-2xl max-w-5xl w-full h-[85vh] flex flex-col shadow-2xl border border-white/10 overflow-hidden relative">
                        <div class="px-8 py-6 border-b border-white/5 flex justify-between items-center bg-black/40">
                            <div class="space-y-1">
                                <h3 class="text-[0.6rem] font-bold text-gold uppercase tracking-[0.3em]">Cinematic Narrative Preview: ${slug}</h3>
                                <div class="text-sm text-white/90 italic font-serif">${data.subject}</div>
                            </div>
                            <button onclick="document.getElementById('previewModal').remove()" class="text-white/30 hover:text-gold transition-colors">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M6 18L18 6M6 6l12 12" stroke-width="1.5"></path></svg>
                            </button>
                        </div>
                        <div class="flex-1 bg-[#0a0a0a] relative">
                            <iframe srcdoc="${data.html.replace(/"/g, '&quot;')}" class="w-full h-full border-none"></iframe>
                        </div>
                        <div class="px-8 py-4 bg-black/40 border-t border-white/5 text-[0.55rem] tracking-[0.2em] uppercase text-ash/40 text-center">
                            Rendered via Odo Comm Engine
                        </div>
                    </div>
                </div>
            `;
            document.body.insertAdjacentHTML('beforeend', modal);
        } catch (e) {
            console.error(e);
        }
    }

    function openTestEmailModal(slug) {
        const modal = `
            <div class="fixed inset-0 bg-black/90 backdrop-blur-md flex items-center justify-center z-50 animate-hero-in" id="testEmailModal">
                <div class="glass border border-white/10 p-10 rounded-2xl max-w-md w-full mx-4 shadow-2xl relative">
                    <div class="absolute top-0 left-0 w-8 h-8 border-t border-l border-gold/40"></div>
                    <div class="absolute bottom-0 right-0 w-8 h-8 border-b border-r border-gold/40"></div>
                    
                    <h3 class="text-2xl font-serif text-white mb-2">Dispatch <em>Test</em></h3>
                    <p class="text-[0.6rem] tracking-[0.2em] uppercase text-ash/40 mb-8 italic">Verify the narrative delivery on a live recipient</p>
                    
                    <div class="space-y-6">
                        <div class="space-y-3">
                            <label class="block text-[0.6rem] tracking-[0.4em] uppercase text-gold font-bold">Recipient Identity</label>
                            <input type="email" id="testEmailAddress" class="w-full bg-black/40 border border-white/10 p-4 text-white focus:border-gold outline-none text-sm" placeholder="architect@mediaweb.com" required>
                        </div>
                        <div class="flex gap-4 pt-4">
                            <button onclick="sendTestEmail('${slug}')" class="flex-1 bg-gold text-black px-6 py-4 text-[0.6rem] tracking-widest uppercase font-bold hover:bg-white transition-all shadow-xl shadow-gold/10">Dispatch</button>
                            <button onclick="document.getElementById('testEmailModal').remove()" class="flex-1 border border-white/10 text-white px-6 py-4 text-[0.6rem] tracking-widest uppercase font-bold hover:bg-white/5 transition-all">Abort</button>
                        </div>
                    </div>
                </div>
            </div>
        `;
        document.body.insertAdjacentHTML('beforeend', modal);
    }

    async function sendTestEmail(slug) {
        const email = document.getElementById('testEmailAddress').value;
        if (!email) return;

        try {
            const response = await fetch('{{ route('admin.email-config.test') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ test_email: email, template_slug: slug })
            });

            if (response.ok) {
                document.getElementById('testEmailModal').remove();
            }
        } catch (e) {
            console.error(e);
        }
    }
</script>
@endpush

<style>
    .ql-toolbar.ql-snow { border: none !important; border-bottom: 1px solid rgba(0,0,0,0.1) !important; background: #fdfdfd; border-radius: 12px 12px 0 0; padding: 12px !important; }
    .ql-container.ql-snow { border: none !important; font-family: 'Inter', sans-serif !important; font-size: 15px !important; line-height: 1.6; }
    .ql-editor { min-height: 200px; padding: 24px !important; }
    .glass { background: rgba(0, 0, 0, 0.4); backdrop-filter: blur(20px); }
</style>
@endsection
