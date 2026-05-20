<?php

namespace Database\Seeders;

use App\Models\Booking;
use App\Models\BookingRequest;
use App\Models\Invoice;
use App\Models\Media;
use App\Models\User;
use Illuminate\Database\Seeder;

class TestDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create admin user
        $admin = User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@mediaweb.local',
        ]);
        $admin->assignRole('admin');

        // Create manager users
        $manager = User::factory()->create([
            'name' => 'Studio Manager',
            'email' => 'manager@mediaweb.local',
        ]);
        $manager->assignRole('manager');

        $managers = collect([$manager])->concat(User::factory(1)->create()->each(function ($user) {
            $user->update(['name' => 'Manager '.$user->id]);
            $user->assignRole('manager');
        }));

        // Create photographer users
        $photographer = User::factory()->create([
            'name' => 'Lead Photographer',
            'email' => 'photographer@mediaweb.local',
        ]);
        $photographer->assignRole('crew');

        $photographers = collect([$photographer])->concat(User::factory(4)->create()->each(function ($user) {
            $user->update(['name' => 'Photographer '.$user->id]);
            $user->assignRole('crew');
        }));

        // Create booking requests
        $bookingRequests = BookingRequest::factory(10)->create();

        // Create bookings from first 5 requests
        $bookingRequests->take(5)->each(function ($request) use ($photographers) {
            $booking = Booking::factory()->create([
                'booking_request_id' => $request->id,
                'photographer_id' => $photographers->random()->id,
                'status' => 'confirmed',
            ]);

            // Create invoices for confirmed bookings (50% chance)
            if (rand(1, 100) > 50) {
                Invoice::factory()
                    ->state(fn () => ['booking_id' => $booking->id])
                    ->create();
            }
        });

        // Create more bookings for testing different statuses
        BookingRequest::factory(5)->create()->each(function ($request) use ($photographers) {
            Booking::factory()
                ->pending()
                ->create([
                    'booking_request_id' => $request->id,
                    'photographer_id' => $photographers->random()->id,
                ]);
        });

        BookingRequest::factory(3)->cancelled()->create()->each(function ($request) use ($photographers) {
            Booking::factory()
                ->cancelled()
                ->create([
                    'booking_request_id' => $request->id,
                    'photographer_id' => $photographers->random()->id,
                ]);
        });

        // Create invoices with various statuses
        Invoice::factory(5)->draft()->create();
        Invoice::factory(5)->issued()->create();
        Invoice::factory(3)->paid()->create();

        // Create media (portfolio items)
        Media::factory(10)->image()->create([
            'uploaded_by' => $photographers->random()->id,
        ]);

        Media::factory(5)->video()->create([
            'uploaded_by' => $photographers->random()->id,
        ]);

        Media::factory(3)->gif()->create([
            'uploaded_by' => $photographers->random()->id,
        ]);

        // Create a test photographer portfolio
        $portfolioPhotographer = $photographers->first();
        Media::factory(8)->image()
            ->create([
                'uploaded_by' => $portfolioPhotographer->id,
                'title' => 'Portfolio Image',
            ]);

        Media::factory(2)->video()
            ->create([
                'uploaded_by' => $portfolioPhotographer->id,
                'title' => 'Performance Video',
            ]);

        $this->command->info('✓ Test data seeded successfully!');
        $this->command->info('Admin: admin@mediaweb.local');
        $this->command->info('Default password: password');
    }
}
