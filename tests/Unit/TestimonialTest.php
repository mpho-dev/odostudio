<?php

namespace Tests\Unit;

use App\Models\Testimonial;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class TestimonialTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function it_has_fillable_attributes(): void
    {
        $testimonial = Testimonial::create([
            'client_name' => 'John Doe',
            'client_initials' => 'JD',
            'event_label' => 'Wedding',
            'quote' => 'Great service!',
            'rating' => 5,
            'is_featured' => true,
            'order' => 1,
        ]);

        $this->assertEquals('John Doe', $testimonial->client_name);
        $this->assertEquals('JD', $testimonial->client_initials);
        $this->assertEquals('Wedding', $testimonial->event_label);
        $this->assertEquals('Great service!', $testimonial->quote);
        $this->assertEquals(5, $testimonial->rating);
        $this->assertTrue($testimonial->is_featured);
        $this->assertEquals(1, $testimonial->order);
    }

    #[Test]
    public function rating_is_cast_to_integer(): void
    {
        $testimonial = Testimonial::factory()->create(['rating' => 4]);

        $this->assertIsInt($testimonial->rating);
        $this->assertEquals(4, $testimonial->rating);
    }

    #[Test]
    public function is_featured_is_cast_to_boolean(): void
    {
        $testimonial = Testimonial::factory()->create(['is_featured' => true]);

        $this->assertIsBool($testimonial->is_featured);
        $this->assertTrue($testimonial->is_featured);
    }

    #[Test]
    public function it_can_have_order_for_sorting(): void
    {
        $testimonial1 = Testimonial::factory()->create(['order' => 5]);
        $testimonial2 = Testimonial::factory()->create(['order' => 2]);

        $this->assertEquals(5, $testimonial1->order);
        $this->assertEquals(2, $testimonial2->order);
    }

    #[Test]
    public function it_can_store_rating_as_integer(): void
    {
        $testimonial = Testimonial::factory()->create(['rating' => 4]);

        $this->assertEquals(4, $testimonial->rating);
    }
}
