<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class ServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $services = [
            [
                'title' => 'Wedding Photography',
                'description' => 'Full-day documentary coverage with editorial-style portraits. Every image reflects the unique texture of your love story — not a formula.',
                'icon' => '💍',
                'starting_price' => 8500.00,
                'features' => [
                    '4 hours coverage',
                    '100+ edited high-res images',
                    'Private online gallery',
                    'Print release included',
                ],
                'order' => 1,
            ],
            [
                'title' => 'Wedding Videography',
                'description' => 'Cinematic wedding films that feel like a short film — not a highlight reel. Shot on cinema-grade lenses with a dedicated audio crew.',
                'icon' => '🎬',
                'starting_price' => 12000.00,
                'features' => [
                    'Highlight film (2–4 min)',
                    'Full ceremony film',
                    'RAW audio capture',
                    'Drone aerials where permitted',
                ],
                'order' => 2,
            ],
            [
                'title' => 'Brand & Commercial',
                'description' => 'Photo and video production for brands that want to stop the scroll. Product, lifestyle, and campaign work with full creative direction available.',
                'icon' => '✦',
                'starting_price' => 5500.00,
                'features' => [
                    'Half-day & full-day rates',
                    'Creative direction included',
                    'Styled shoots',
                    'Licensing negotiable',
                ],
                'order' => 3,
            ],
            [
                'title' => 'Portraits & Headshots',
                'description' => 'Executive headshots, personal branding sessions, and editorial portraits. Relaxed, directed, always flattering.',
                'icon' => '👤',
                'starting_price' => 1500.00,
                'features' => [
                    '1-hour studio or location session',
                    '30 edited selects',
                    'Same-week delivery',
                ],
                'order' => 4,
            ],
            [
                'title' => 'Engagement Sessions',
                'description' => 'Pre-wedding shoots that double as rehearsal — for you to get comfortable in front of my lens before the big day.',
                'icon' => '🌿',
                'starting_price' => 2000.00,
                'features' => [
                    '1–2 hour golden-hour session',
                    '70+ edited images',
                    'Location scouting included',
                ],
                'order' => 5,
            ],
            [
                'title' => 'Events & Corporate',
                'description' => 'Conferences, product launches, galas, and parties. Fast turnaround, professional presence, no fuss.',
                'icon' => '📸',
                'starting_price' => 3600.00,
                'features' => [
                    'from 3 hours',
                    '24-hr turnaround available',
                    'Full-res delivery',
                ],
                'order' => 6,
            ],
        ];

        foreach ($services as $service) {
            \App\Models\Service::create($service);
        }
    }
}
