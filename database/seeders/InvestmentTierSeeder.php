<?php

namespace Database\Seeders;

use App\Models\InvestmentTier;
use Illuminate\Database\Seeder;

class InvestmentTierSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tiers = [
            [
                'tier_label' => 'Essential',
                'name' => 'The Classic',
                'price' => 14500.00,
                'price_suffix' => '/ event',
                'is_featured' => false,
                'badge_label' => null,
                'features' => [
                    '8 hours photography coverage',
                    '2 photographers',
                    '350+ edited images',
                    'Private online gallery',
                    'Print release',
                    '4-week delivery',
                ],
                'order' => 1,
            ],
            [
                'tier_label' => 'Signature',
                'name' => 'Photo + Film',
                'price' => 26000.00,
                'price_suffix' => '/ event',
                'is_featured' => true,
                'badge_label' => 'Most Popular',
                'features' => [
                    '10 hours photo + video coverage',
                    '2 photographer + 1 videographer',
                    '500+ edited images',
                    'Cinematic highlight film (5 min)',
                    'Full ceremony film',
                    'Drone aerials',
                    'Engagement session included',
                ],
                'order' => 2,
            ],
            [
                'tier_label' => 'Prestige',
                'name' => 'Full Production',
                'price' => 38000.00,
                'price_suffix' => '/ event',
                'is_featured' => false,
                'badge_label' => null,
                'features' => [
                    'Unlimited hours, full day',
                    '2 photographers + 2 videographers',
                    '600+ edited images',
                    'Feature-length film (20+ min)',
                    'Drone + gimbal coverage',
                    'Next-day highlight reel',
                    'Fine art album included',
                    'Destination travel considered',
                ],
                'order' => 3,
            ],
        ];

        foreach ($tiers as $tier) {
            InvestmentTier::create($tier);
        }
    }
}
