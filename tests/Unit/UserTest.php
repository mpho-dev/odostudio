<?php

namespace Tests\Unit;

use App\Models\Booking;
use App\Models\Invoice;
use App\Models\Media;
use App\Models\NotificationPreference;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
        Role::firstOrCreate(['name' => 'manager', 'guard_name' => 'web']);
        Role::firstOrCreate(['name' => 'crew', 'guard_name' => 'web']);
    }

    #[Test]
    public function it_has_many_bookings_as_photographer(): void
    {
        $photographer = User::factory()->create();
        $booking1 = Booking::factory()->create(['photographer_id' => $photographer->id]);
        $booking2 = Booking::factory()->create(['photographer_id' => $photographer->id]);

        $this->assertCount(2, $photographer->bookings);
        $this->assertTrue($photographer->bookings->contains($booking1));
        $this->assertTrue($photographer->bookings->contains($booking2));
    }

    #[Test]
    public function it_has_many_invoices_as_creator(): void
    {
        $manager = User::factory()->create();
        $invoice1 = Invoice::factory()->create(['created_by' => $manager->id]);
        $invoice2 = Invoice::factory()->create(['created_by' => $manager->id]);

        $this->assertCount(2, $manager->invoices);
        $this->assertTrue($manager->invoices->contains($invoice1));
        $this->assertTrue($manager->invoices->contains($invoice2));
    }

    #[Test]
    public function it_has_many_media_uploads(): void
    {
        $user = User::factory()->create();
        $media1 = Media::factory()->create(['uploaded_by' => $user->id]);
        $media2 = Media::factory()->create(['uploaded_by' => $user->id]);

        $this->assertCount(2, $user->media);
        $this->assertTrue($user->media->contains($media1));
        $this->assertTrue($user->media->contains($media2));
    }

    #[Test]
    public function it_has_one_notification_preference(): void
    {
        $user = User::factory()->create();
        $preference = NotificationPreference::factory()->create(['user_id' => $user->id]);

        $this->assertInstanceOf(NotificationPreference::class, $user->notificationPreference);
        $this->assertEquals($preference->id, $user->notificationPreference->id);
    }

    #[Test]
    public function it_can_belong_to_many_crew_bookings(): void
    {
        $user = User::factory()->create();
        $booking1 = Booking::factory()->create();
        $booking2 = Booking::factory()->create();

        $user->crewBookings()->attach($booking1->id, ['role' => 'photographer']);
        $user->crewBookings()->attach($booking2->id, ['role' => 'videographer']);

        $this->assertCount(2, $user->crewBookings);
    }

    #[Test]
    public function crew_bookings_include_pivot_role(): void
    {
        $user = User::factory()->create();
        $booking = Booking::factory()->create();

        $user->crewBookings()->attach($booking->id, ['role' => 'photographer']);

        $crewBooking = $user->crewBookings->first();
        $this->assertEquals('photographer', $crewBooking->pivot->role);
    }

    #[Test]
    public function it_uses_spatie_roles(): void
    {
        $user = User::factory()->create();
        $user->assignRole('admin');

        $this->assertTrue($user->hasRole('admin'));
        $this->assertFalse($user->hasRole('manager'));
    }

    #[Test]
    public function it_can_have_multiple_roles(): void
    {
        $user = User::factory()->create();
        $user->assignRole(['admin', 'manager']);

        $this->assertTrue($user->hasRole('admin'));
        $this->assertTrue($user->hasRole('manager'));
    }

    #[Test]
    public function email_verified_at_is_cast_to_datetime(): void
    {
        $user = User::factory()->create([
            'email_verified_at' => '2026-01-15 10:00:00',
        ]);

        $this->assertInstanceOf(\Carbon\Carbon::class, $user->email_verified_at);
    }

    #[Test]
    public function password_is_hashed(): void
    {
        $user = User::factory()->create([
            'password' => 'plainpassword',
        ]);

        $this->assertNotEquals('plainpassword', $user->password);
        $this->assertTrue(password_verify('plainpassword', $user->password));
    }

    #[Test]
    public function must_change_password_is_cast_to_boolean(): void
    {
        $user = User::factory()->create(['must_change_password' => true]);

        $this->assertIsBool($user->must_change_password);
        $this->assertTrue($user->must_change_password);
    }

    #[Test]
    public function it_uses_soft_deletes(): void
    {
        $user = User::factory()->create();

        $user->delete();

        $this->assertSoftDeleted($user);
        $this->assertDatabaseHas('users', ['id' => $user->id]);
    }

    #[Test]
    public function fillable_attributes_can_be_mass_assigned(): void
    {
        $user = User::create([
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => 'password123',
            'must_change_password' => false,
            'crew_specialty' => 'photographer',
        ]);

        $this->assertEquals('John Doe', $user->name);
        $this->assertEquals('john@example.com', $user->email);
        $this->assertEquals('photographer', $user->crew_specialty);
    }

    #[Test]
    public function hidden_attributes_are_not_serialized(): void
    {
        $user = User::factory()->create();
        $serialized = $user->toArray();

        $this->assertArrayNotHasKey('password', $serialized);
        $this->assertArrayNotHasKey('remember_token', $serialized);
    }

    #[Test]
    public function it_implements_must_verify_email(): void
    {
        $user = User::factory()->create();

        $this->assertInstanceOf(\Illuminate\Contracts\Auth\MustVerifyEmail::class, $user);
    }
}
