<!-- Process Section Component -->
<section id="process" class="py-20 md:py-32 px-4 md:px-8 max-w-6xl mx-auto">
    <!-- Section Header -->
    <div class="section-label">Process</div>
    <h2 class="section-title">
        How we work <em class="italic text-cream">together</em>
    </h2>

    <!-- Process Track - 4 Column Grid -->
    <div class="process-track reveal">
        <!-- Connecting Line (Desktop Only) -->
        <div class="hidden lg:block absolute top-[28px] left-[12.5%] right-[12.5%] h-px bg-linear-to-r from-transparent via-iron to-transparent z-0"></div>

        <div class="grid grid-cols-2 lg:grid-cols-4 gap-8 lg:gap-0 relative z-10">
            @forelse($processSteps as $index => $step)
                <div class="process-step text-center px-5">
                    <!-- Step Circle -->
                    <div class="process-dot w-14 h-14 md:w-[56px] md:h-[56px] rounded-full border border-gold flex items-center justify-center bg-black mx-auto mb-5 font-serif text-xl font-light text-gold">
                        {{ str_pad($step->step_number, 2, '0', STR_PAD_LEFT) }}
                    </div>

                    <!-- Step Title -->
                    <h3 class="process-name font-serif text-white mb-2">
                        {{ $step->title }}
                    </h3>

                    <!-- Step Description -->
                    <p class="process-desc text-[0.72rem] leading-relaxed text-silver px-2">
                        {{ $step->description }}
                    </p>
                </div>
            @empty
                <div class="col-span-full text-center py-16">
                    <p class="font-serif text-lg italic text-silver/40">No process steps defined yet.</p>
                </div>
            @endforelse
        </div>
    </div>
</section>

