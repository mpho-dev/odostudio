<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class SiteSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $settings = [
            'hero_eyebrow' => 'Photography & Videography',
            'hero_name' => 'Odo <em>Studio</em>',
            'hero_title' => '<p>Multidisciplinary Creative House</p>',
            'hero_scroll' => 'Mpumalanga · Gauteng · Worldwide',
            'about_eyebrow' => 'Odo Group — Media House',
            'about_narrative_eyebrow' => 'About',
            'about_title' => 'Stories told through<br><em>light & motion</em>',
            'about_body_1' => 'We are a Mpumalanga-based visual storytelling studio with almost a decade of
          experience crafting cinematic imagery for couples, brands, and creative visionaries.
          Our work lives at the intersection of documentary truth and deliberate artistry -every
          frame considered, every moment earned.',
            'about_body_2' => 'Whether shooting a destination wedding on the Coast or a brand campaign in the
          Karoo, we bring the same obsessive attention to light, emotion, and atmosphere.
          You won\'t get cookie-cutter. You\'ll get yours.',
            'stats_events' => '48+',
            'stats_years' => '7',
            'stats_provinces' => '5',
            'cta_title' => 'Let\'s create <em>something</em>',
            'cta_subtext' => '<p class="text-charcoal/60 dark:text-ash mb-10">Every great image starts with a conversation. Tell us about your vision. Whether it\'s the wedding you\'ve been planning for years or the brand film that needs to exist. We\'d love to hear from you.</p>',
            'hero_bg_image' => null,
            'about_portrait_image' => null,
            'home_button_enabled' => true,
        ];

        foreach ($settings as $key => $value) {
            \App\Models\SiteSetting::set($key, $value);
        }
    }
}
