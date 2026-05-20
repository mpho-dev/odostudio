@extends('layouts.guest')

@section('title', 'Connect — Odo Studio')

@section('description', 'Get in touch with Odo Studio for your next cinematic production. We are available for wedding photography, videography, and brand narratives worldwide.')
@section('keywords', 'contact odo studio, booking enquiry, wedding photography booking, videography services')

@section('content')
<div class="subpage-header-padding pb-24 px-6 md:px-12 max-w-7xl mx-auto min-h-screen flex flex-col items-center">
    <div class="w-full max-w-4xl grid md:grid-cols-2 gap-16 items-start">
        
        <!-- Contact Info Side -->
        <div class="reveal">
            <div class="section-label group mb-4">
                <span>The Connection</span>
            </div>
            <h1 class="section-title text-left mb-8">
                {!! $settings['cta_title'] ?? "Let's create <em>something</em>" !!}
            </h1>
            
            <div class="about-body mb-12">
                {!! $settings['cta_subtext'] ?? "Every great image starts with a conversation. Tell us about your vision. Whether it's the wedding you've been planning for years or the brand film that needs to exist. We'd love to hear from you." !!}
            </div>

            <div class="space-y-8">
                <div class="flex gap-5">
                    <div class="text-gold text-xl">✉</div>
                    <div>
                        <div class="text-[0.6rem] tracking-[0.2em] uppercase text-silver mb-1">Email</div>
                        <div class="text-white text-sm">hello@odocorp.co.za</div>
                    </div>
                </div>
                <div class="flex gap-5">
                    <div class="text-gold text-xl">📱</div>
                    <div>
                        <div class="text-[0.6rem] tracking-[0.2em] uppercase text-silver mb-1">WhatsApp</div>
                        <div class="text-white text-sm">+27 82 870 7275</div>
                    </div>
                </div>
                <div class="flex gap-5">
                    <div class="text-gold text-xl">📍</div>
                    <div>
                        <div class="text-[0.6rem] tracking-[0.2em] uppercase text-silver mb-1">Base</div>
                        <div class="text-white text-sm">Mpumalanga, South Africa · Available worldwide</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Form Side -->
        <div class="bg-charcoal p-8 md:p-10 border border-iron shadow-2xl reveal">
            <h2 class="font-serif text-2xl text-white mb-8">Booking Enquiry</h2>

            @if ($errors->any())
                <div class="mb-6 p-4 bg-red-900/50 border border-red-500 text-red-200 text-xs rounded">
                    @foreach ($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            @if (session('success'))
                <div class="mb-6 p-4 bg-gold border border-gold text-black text-sm font-medium rounded">
                    {{ session('success') }}
                </div>
            @endif

            <form method="POST" action="{{ route('contact.store') }}" class="space-y-6">
                @csrf

                <div class="grid grid-cols-2 gap-6">
                    <div>
                        <label for="name" class="block text-[0.65rem] uppercase tracking-widest text-silver mb-2">First Name</label>
                        <input id="name" type="text" name="name" value="{{ old('name') }}" required 
                               class="w-full bg-black border border-iron p-3 text-sm text-white focus:border-gold outline-none transition-colors" />
                    </div>
                    <div>
                        <label for="surname" class="block text-[0.65rem] uppercase tracking-widest text-silver mb-2">Last Name</label>
                        <input id="surname" type="text" name="surname" value="{{ old('surname') }}" required 
                               class="w-full bg-black border border-iron p-3 text-sm text-white focus:border-gold outline-none transition-colors" />
                    </div>
                </div>

                <div>
                    <label for="email" class="block text-[0.65rem] uppercase tracking-widest text-silver mb-2">Email Address</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required 
                           class="w-full bg-black border border-iron p-3 text-sm text-white focus:border-gold outline-none transition-colors" />
                </div>

                <div>
                    <label for="phone" class="block text-[0.65rem] uppercase tracking-widest text-silver mb-2">Phone Number</label>
                    <input id="phone" type="tel" name="phone" value="{{ old('phone') }}" required 
                           class="w-full bg-black border border-iron p-3 text-sm text-white focus:border-gold outline-none transition-colors" />
                </div>

                <div class="grid grid-cols-2 gap-6">
                    <div>
                        <label for="event_type" class="block text-[0.65rem] uppercase tracking-widest text-silver mb-2">Service</label>
                        <select id="event_type" name="event_type" class="w-full bg-black border border-iron p-3 text-sm text-white focus:border-gold outline-none transition-colors">
                            <option value="">Select a service</option>
                            
                            @if(isset($services) && $services->count() > 0)
                                <optgroup label="Cinematic Services" class="bg-charcoal text-silver italic">
                                    @foreach($services as $service)
                                        <option value="service_{{ $service->id }}" class="bg-black text-white not-italic" {{ old('event_type') == 'service_'.$service->id ? 'selected' : '' }}>
                                            {{ $service->icon }} {{ $service->title }}
                                        </option>
                                    @endforeach
                                </optgroup>
                            @endif

                            @if(isset($investmentTiers) && $investmentTiers->count() > 0)
                                <optgroup label="Investment Packages" class="bg-charcoal text-silver italic">
                                    @foreach($investmentTiers as $tier)
                                        <option value="tier_{{ $tier->id }}" class="bg-black text-white not-italic" {{ old('event_type') == 'tier_'.$tier->id ? 'selected' : '' }}>
                                            {{ $tier->name }} ({{ $tier->price_suffix }})
                                        </option>
                                    @endforeach
                                </optgroup>
                            @endif
                        </select>
                    </div>
                    <div>
                        <label for="event_date" class="block text-[0.65rem] uppercase tracking-widest text-silver mb-2">Event Date</label>
                        <input id="event_date" type="datetime-local" name="event_date" value="{{ old('event_date') }}" 
                               class="w-full bg-black border border-iron p-3 text-sm text-white focus:border-gold outline-none transition-colors" />
                    </div>
                </div>

                <div>
                    <label for="notes" class="block text-[0.65rem] uppercase tracking-widest text-silver mb-2">Your Vision</label>
                    <textarea id="notes" name="notes" rows="4" placeholder="Location, vibe, any details that matter..." 
                               class="w-full bg-black border border-iron p-3 text-sm text-white focus:border-gold outline-none transition-colors">{{ old('notes') }}</textarea>
                </div>

                <button type="submit" class="w-full bg-gold text-black py-4 text-[0.7rem] tracking-widest uppercase font-semibold hover:bg-white transition-colors">
                    Send Enquiry →
                </button>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Specific scripts for contact form if needed
</script>
@endpush
