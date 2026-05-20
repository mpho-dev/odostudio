@php
    // Hero component: relies on $settings variable passed from the parent view
@endphp
<section class="hero relative">
  <div class="hero-bg film-grain">
      @if(isset($settings['hero_bg_image']))
      <div class="absolute inset-0 bg-cover bg-center opacity-40 blur-sm" style="background-image: url('{{ $settings['hero_bg_image'] }}');"></div>
      <div class="absolute inset-0 bg-cover bg-center opacity-80 mix-blend-screen"
          style="background-image: url('{{ $settings['hero_bg_image'] }}'); mask-image: linear-gradient(to bottom, black 0%, transparent 100%); -webkit-mask-image: linear-gradient(to bottom, black 0%, transparent 100%);"></div>
      <div class="absolute inset-0 bg-linear-to-r from-black via-black/80 to-transparent"></div>
      <div class="absolute inset-0 bg-linear-to-t from-black via-black/40 to-transparent"></div>
      @endif
  </div>

  <div class="hero-content">
      <div class="hero-eyebrow">{{ $settings['hero_eyebrow'] ?? 'Established 2018 · South Africa' }}</div>
      <h1 class="hero-name">
          {!! $settings['hero_name'] ?? 'ODO <em>STUDIO</em>' !!}
      </h1>
      <div class="hero-title"> 
          {!! $settings['hero_title'] ?? '<p>Cinematic Storytelling through Light & Motion</p>' !!}
      </div>

      <div class="hero-divider"></div>
      <div class="hero-scroll">{{ $settings['hero_scroll'] ?? 'Scroll to explore' }}</div>
  </div>
</section>
