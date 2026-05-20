<?php

namespace Tests\Unit;

use App\Models\Booking;
use App\Models\InvestmentTier;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class InvestmentTierTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function it_has_many_bookings(): void
    {
        $tier = InvestmentTier::factory()->create();
        $booking1 = Booking::factory()->create(['investment_tier_id' => $tier->id]);
        $booking2 = Booking::factory()->create(['investment_tier_id' => $tier->id]);

        $this->assertCount(2, $tier->bookings);
        $this->assertTrue($tier->bookings->contains($booking1));
        $this->assertTrue($tier->bookings->contains($booking2));
    }

    #[Test]
    public function price_is_cast_to_decimal_two_places(): void
    {
        $tier = InvestmentTier::factory()->create([
            'price' => 1234.567,
        ]);

        $this->assertEquals(1234.57, $tier->price);
    }

    #[Test]
    public function features_is_cast_to_array(): void
    {
        $tier = InvestmentTier::factory()->create([
            'features' => ['feature1', 'feature2', 'feature3'],
        ]);

        $this->assertIsArray($tier->features);
        $this->assertContains('feature1', $tier->features);
        $this->assertContains('feature2', $tier->features);
    }

    #[Test]
    public function is_featured_is_cast_to_boolean(): void
    {
        $tier = InvestmentTier::factory()->create([
            'is_featured' => true,
        ]);

        $this->assertIsBool($tier->is_featured);
        $this->assertTrue($tier->is_featured);
    }

    #[Test]
    public function it_can_store_null_features(): void
    {
        $tier = InvestmentTier::factory()->create([
            'features' => null,
        ]);

        $this->assertNull($tier->features);
    }

    #[Test]
    public function it_can_store_empty_features_array(): void
    {
        $tier = InvestmentTier::factory()->create([
            'features' => [],
        ]);

        $this->assertIsArray($tier->features);
        $this->assertEmpty($tier->features);
    }

    #[Test]
    public function it_can_have_order_for_sorting(): void
    {
        $tier = InvestmentTier::factory()->create([
            'order' => 5,
        ]);

        $this->assertEquals(5, $tier->order);
    }

    #[Test]
    public function it_has_fillable_attributes(): void
    {
        $tier = InvestmentTier::create([
            'tier_label' => 'Tier A',
            'name' => 'Premium Package',
            'price' => 1999.99,
            'price_suffix' => '/event',
            'is_featured' => true,
            'badge_label' => 'Popular',
            'features' => ['Photo', 'Video', 'Edit'],
            'order' => 1,
        ]);

        $this->assertEquals('Tier A', $tier->tier_label);
        $this->assertEquals('Premium Package', $tier->name);
        $this->assertEquals(1999.99, $tier->price);
        $this->assertEquals('/event', $tier->price_suffix);
        $this->assertTrue($tier->is_featured);
        $this->assertEquals('Popular', $tier->badge_label);
    }
}
