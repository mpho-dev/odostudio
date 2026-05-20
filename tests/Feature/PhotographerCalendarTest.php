<?php

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PhotographerCalendarTest extends TestCase
{
    use RefreshDatabase;

    public function test_photographer_can_fetch_calendar_events(): void
    {
        $photographer = User::factory()->create();
        $photographer->assignRole('crew');

        Booking::factory(2)->create([
            'photographer_id' => $photographer->id,
            'status' => 'confirmed',
        ]);

        $otherPhotographer = User::factory()->create();
        $otherPhotographer->assignRole('crew');
        Booking::factory(1)->create([
            'photographer_id' => $otherPhotographer->id,
            'status' => 'confirmed',
        ]);

        $response = $this->actingAs($photographer)->get(route('photographer.calendar.events'));

        $response->assertStatus(200);
        $response->assertJsonCount(2);
    }

    public function test_photographer_can_view_calendar_page(): void
    {
        $photographer = User::factory()->create();
        $photographer->assignRole('crew');

        $response = $this->actingAs($photographer)->get(route('photographer.calendar'));
        $response->assertStatus(200);
    }
}
