<?php

namespace Database\Seeders;

use App\Models\ProcessStep;
use Illuminate\Database\Seeder;

class ProcessStepSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $steps = [
            [
                'step_number' => 1,
                'title' => 'Discovery Call',
                'description' => 'A relaxed 30-min conversation to understand your vision, timeline, and aesthetic.',
                'display_order' => 1,
            ],
            [
                'step_number' => 2,
                'title' => 'Custom Proposal',
                'description' => 'A tailored package and creative brief built around your specific needs.',
                'display_order' => 2,
            ],
            [
                'step_number' => 3,
                'title' => 'The Shoot',
                'description' => 'On the day, you forget about the camera. I handle everything else.',
                'display_order' => 3,
            ],
            [
                'step_number' => 4,
                'title' => 'Delivery',
                'description' => 'Beautifully edited images or film delivered to your private gallery within 4–6 weeks.',
                'display_order' => 4,
            ],
        ];

        foreach ($steps as $step) {
            ProcessStep::firstOrCreate(
                ['step_number' => $step['step_number']],
                $step
            );
        }
    }
}
