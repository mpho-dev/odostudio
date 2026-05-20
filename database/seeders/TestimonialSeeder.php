<?php

namespace Database\Seeders;

use App\Models\Testimonial;
use Illuminate\Database\Seeder;

class TestimonialSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $testimonials = [
            [
                'client_name' => 'Lungelo & Noluthando M.',
                'client_initials' => 'L',
                'event_label' => 'Wedding · Mpumalanga',
                'rating' => 5,
                'quote' => "Odo Studio didn't just photograph our wedding, they felt it. Every image captures something we didn't even know was happening. Looking at them still makes me cry.",
                'is_featured' => true,
                'order' => 1,
            ],
            [
                'client_name' => 'Nqobile Nxumalo',
                'client_initials' => 'N',
                'event_label' => 'Managing Director · Nxumalo Green Attorneys',
                'rating' => 5,
                'quote' => 'Working with Odo Group on our rebrand campaign was seamless. The creative director interpreted our brief better than we could have articulated it. The results were extraordinary.',
                'is_featured' => false,
                'order' => 2,
            ],
            [
                'client_name' => 'Simphiwe \"Asjay\".',
                'client_initials' => 'S',
                'event_label' => 'Fashion Photoshoot · Secunda',
                'rating' => 5,
                'quote' => 'Thank you immensely for the all the images you\'ve selected. The work is nothing short of amazing!',
                'is_featured' => false,
                'order' => 3,
            ],
            [
                'client_name' => 'Marcus \"Marcus MC\" Shabalala.',
                'client_initials' => 'M',
                'event_label' => 'Portraits · Gauteng',
                'rating' => 5,
                'quote' => 'As someone who hates being \"pictures\", Odo made the engagement shoot feel like a walk in the park. The images look like a magazine spread.',
                'is_featured' => false,
                'order' => 4,
            ],
        ];

        foreach ($testimonials as $testimonial) {
            if (empty($testimonial['client_initials']) && ! empty($testimonial['client_name'])) {
                $testimonial['client_initials'] = mb_substr($testimonial['client_name'], 0, 1);
            }

            Testimonial::create($testimonial);
        }
    }
}
