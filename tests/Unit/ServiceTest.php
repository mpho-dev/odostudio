<?php

namespace Tests\Unit;

use App\Models\Service;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class ServiceTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function it_has_fillable_attributes(): void
    {
        $service = Service::create([
            'title' => 'Wedding Photography',
            'description' => 'Professional wedding photography services',
            'icon' => 'camera',
            'starting_price' => 1500.00,
            'features' => ['Photo', 'Video', 'Edit'],
            'order' => 1,
        ]);

        $this->assertEquals('Wedding Photography', $service->title);
        $this->assertEquals('Professional wedding photography services', $service->description);
        $this->assertEquals('camera', $service->icon);
        $this->assertEquals(1, $service->order);
    }

    #[Test]
    public function it_can_have_a_null_description(): void
    {
        $service = Service::factory()->create(['description' => null]);

        $this->assertNull($service->description);
    }

    #[Test]
    public function it_can_have_an_order_for_sorting(): void
    {
        $service1 = Service::factory()->create(['order' => 3]);
        $service2 = Service::factory()->create(['order' => 1]);

        $this->assertEquals(3, $service1->order);
        $this->assertEquals(1, $service2->order);
    }

    #[Test]
    public function features_is_cast_to_array(): void
    {
        $service = Service::factory()->create([
            'features' => ['Feature 1', 'Feature 2'],
        ]);

        $this->assertIsArray($service->features);
        $this->assertContains('Feature 1', $service->features);
    }

    #[Test]
    public function starting_price_is_cast_to_decimal(): void
    {
        $service = Service::factory()->create([
            'starting_price' => 1234.567,
        ]);

        $this->assertEquals(1234.57, $service->starting_price);
    }
}
