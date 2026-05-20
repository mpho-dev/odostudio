<?php

namespace Tests\Unit;

use App\Models\Booking;
use App\Models\BookingRequest;
use App\Models\InvestmentTier;
use App\Models\Service;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class BookingRequestTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function it_has_one_booking(): void
    {
        $request = BookingRequest::factory()->create();
        $booking = Booking::factory()->create(['booking_request_id' => $request->id]);

        $this->assertInstanceOf(Booking::class, $request->booking);
        $this->assertEquals($booking->id, $request->booking->id);
    }

    #[Test]
    public function it_belongs_to_a_service(): void
    {
        $service = Service::factory()->create();
        $request = BookingRequest::factory()->create(['service_id' => $service->id]);

        $this->assertInstanceOf(Service::class, $request->service);
        $this->assertEquals($service->id, $request->service->id);
    }

    #[Test]
    public function it_belongs_to_an_investment_tier(): void
    {
        $tier = InvestmentTier::factory()->create();
        $request = BookingRequest::factory()->create(['investment_tier_id' => $tier->id]);

        $this->assertInstanceOf(InvestmentTier::class, $request->investmentTier);
        $this->assertEquals($tier->id, $request->investmentTier->id);
    }

    #[Test]
    public function it_can_have_many_crew_members(): void
    {
        $request = BookingRequest::factory()->create();
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();

        $request->crew()->attach($user1->id, ['role' => 'photographer']);
        $request->crew()->attach($user2->id, ['role' => 'videographer']);

        $this->assertCount(2, $request->crew);
        $this->assertTrue($request->crew->contains($user1));
        $this->assertTrue($request->crew->contains($user2));
    }

    #[Test]
    public function it_can_get_only_photographers_from_crew(): void
    {
        $request = BookingRequest::factory()->create();
        $photographer = User::factory()->create();
        $videographer = User::factory()->create();

        $request->crew()->attach($photographer->id, ['role' => 'photographer']);
        $request->crew()->attach($videographer->id, ['role' => 'videographer']);

        $photographers = $request->photographers;

        $this->assertCount(1, $photographers);
        $this->assertTrue($photographers->contains($photographer));
        $this->assertFalse($photographers->contains($videographer));
    }

    #[Test]
    public function it_can_get_only_videographers_from_crew(): void
    {
        $request = BookingRequest::factory()->create();
        $photographer = User::factory()->create();
        $videographer = User::factory()->create();

        $request->crew()->attach($photographer->id, ['role' => 'photographer']);
        $request->crew()->attach($videographer->id, ['role' => 'videographer']);

        $videographers = $request->videographers;

        $this->assertCount(1, $videographers);
        $this->assertTrue($videographers->contains($videographer));
        $this->assertFalse($videographers->contains($photographer));
    }

    #[Test]
    public function interest_name_returns_service_title_when_service_present(): void
    {
        $service = Service::factory()->create(['title' => 'Wedding Package']);
        $request = BookingRequest::factory()->create([
            'service_id' => $service->id,
            'investment_tier_id' => null,
            'event_type' => null,
        ]);

        $this->assertEquals('Wedding Package', $request->interest_name);
    }

    #[Test]
    public function interest_name_returns_investment_tier_name_when_tier_present_and_no_service(): void
    {
        $tier = InvestmentTier::factory()->create(['name' => 'Premium Tier']);
        $request = BookingRequest::factory()->create([
            'service_id' => null,
            'investment_tier_id' => $tier->id,
            'event_type' => null,
        ]);

        $this->assertEquals('Premium Tier', $request->interest_name);
    }

    #[Test]
    public function interest_name_returns_event_type_when_no_service_or_tier(): void
    {
        $request = BookingRequest::factory()->create([
            'service_id' => null,
            'investment_tier_id' => null,
            'event_type' => 'Corporate Event',
        ]);

        $this->assertEquals('Corporate Event', $request->interest_name);
    }

    #[Test]
    public function interest_name_returns_general_enquiry_when_no_info(): void
    {
        $request = BookingRequest::factory()->create([
            'service_id' => null,
            'investment_tier_id' => null,
            'event_type' => null,
        ]);

        $this->assertEquals('General Enquiry', $request->interest_name);
    }

    #[Test]
    public function service_takes_priority_over_tier_for_interest_name(): void
    {
        $service = Service::factory()->create(['title' => 'Service Title']);
        $tier = InvestmentTier::factory()->create(['name' => 'Tier Name']);
        $request = BookingRequest::factory()->create([
            'service_id' => $service->id,
            'investment_tier_id' => $tier->id,
        ]);

        $this->assertEquals('Service Title', $request->interest_name);
    }

    #[Test]
    public function event_date_is_cast_to_datetime(): void
    {
        $request = BookingRequest::factory()->create([
            'event_date' => '2026-07-20 10:00:00',
        ]);

        $this->assertInstanceOf(\Carbon\Carbon::class, $request->event_date);
        $this->assertEquals('2026-07-20', $request->event_date->format('Y-m-d'));
    }

    #[Test]
    public function email_sent_at_is_cast_to_datetime(): void
    {
        $request = BookingRequest::factory()->create([
            'email_sent_at' => '2026-07-20 10:00:00',
        ]);

        $this->assertInstanceOf(\Carbon\Carbon::class, $request->email_sent_at);
    }

    #[Test]
    public function crew_assignment_includes_role_in_pivot(): void
    {
        $request = BookingRequest::factory()->create();
        $user = User::factory()->create();

        $request->crew()->attach($user->id, ['role' => 'photographer']);

        $crewMember = $request->crew->first();
        $this->assertEquals('photographer', $crewMember->pivot->role);
    }

    #[Test]
    public function it_can_detach_crew_members(): void
    {
        $request = BookingRequest::factory()->create();
        $user = User::factory()->create();
        $request->crew()->attach($user->id, ['role' => 'photographer']);

        $request->crew()->detach($user->id);

        $this->assertCount(0, $request->fresh()->crew);
    }
}
