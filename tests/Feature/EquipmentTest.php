<?php

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\BookingRequest;
use App\Models\EquipmentCategory;
use App\Models\EquipmentCheckout;
use App\Models\EquipmentItem;
use App\Models\InvestmentTier;
use App\Models\Service;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EquipmentTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;
    protected User $manager;
    protected User $photographer;
    protected EquipmentCategory $category;
    protected EquipmentItem $item;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create();
        $this->admin->assignRole('admin');

        $this->manager = User::factory()->create();
        $this->manager->assignRole('manager');

        $this->photographer = User::factory()->create();
        $this->photographer->assignRole('crew');

        $this->category = EquipmentCategory::create([
            'name' => 'Cameras',
            'slug' => 'cameras',
        ]);

        $this->item = EquipmentItem::create([
            'category_id' => $this->category->id,
            'name' => 'Sony A7 IV',
            'serial_number' => 'SN12345678',
            'condition' => 'excellent',
            'status' => 'available',
        ]);
    }

    public function test_admin_can_view_equipment_index(): void
    {
        $response = $this->actingAs($this->admin)
            ->get(route('admin.equipment.items.index'));

        $response->assertStatus(200)
            ->assertSee('Sony A7 IV');
    }

    public function test_admin_can_create_equipment(): void
    {
        $response = $this->actingAs($this->admin)
            ->post(route('admin.equipment.items.store'), [
                'category_id' => $this->category->id,
                'name' => 'Canon R5',
                'model' => 'EOS R5',
                'serial_number' => 'SN87654321',
                'condition' => 'good',
            ]);

        $response->assertRedirect(route('admin.equipment.items.index'));
        $this->assertDatabaseHas('equipment_items', ['name' => 'Canon R5']);
    }

    public function test_admin_can_update_equipment(): void
    {
        $response = $this->actingAs($this->admin)
            ->patch(route('admin.equipment.items.update', $this->item), [
                'category_id' => $this->category->id,
                'name' => 'Sony A7 IV Updated',
                'condition' => 'good',
                'status' => 'available',
            ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('equipment_items', ['name' => 'Sony A7 IV Updated']);
    }

    public function test_admin_can_archive_equipment(): void
    {
        $response = $this->actingAs($this->admin)
            ->delete(route('admin.equipment.items.destroy', $this->item));

        $response->assertRedirect(route('admin.equipment.items.index'));
        $this->assertSoftDeleted('equipment_items', ['id' => $this->item->id]);
    }

    public function test_non_admin_cannot_access_equipment_management(): void
    {
        $response = $this->actingAs($this->manager)
            ->get(route('admin.equipment.items.index'));

        $response->assertForbidden();
    }

    public function test_admin_can_create_category(): void
    {
        $response = $this->actingAs($this->admin)
            ->post(route('admin.equipment.categories.store'), [
                'name' => 'Drones',
                'icon' => 'drone',
            ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('equipment_categories', ['name' => 'Drones']);
    }

    public function test_manager_can_assign_equipment_to_booking(): void
    {
        $service = Service::factory()->create();
        $investmentTier = InvestmentTier::factory()->create();

        $bookingRequest = BookingRequest::factory()->create([
            'service_id' => $service->id,
            'investment_tier_id' => $investmentTier->id,
        ]);

        $booking = Booking::factory()->create([
            'booking_request_id' => $bookingRequest->id,
            'investment_tier_id' => $investmentTier->id,
            'photographer_id' => $this->photographer->id,
            'event_date' => now()->addWeek(),
            'status' => 'confirmed',
        ]);

        // Attach photographer as crew so the controller's crew membership check passes.
        $booking->crew()->attach($this->photographer->id, ['role' => 'photographer']);

        $response = $this->actingAs($this->manager)
            ->post(route('manager.equipment.store', $booking), [
                'equipment_item_id' => $this->item->id,
                'user_id' => $this->photographer->id,
                'checkout_notes' => null,
            ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('equipment_checkouts', [
            'booking_id' => $booking->id,
            'equipment_item_id' => $this->item->id,
            'user_id' => $this->photographer->id,
        ]);

        $this->item->refresh();
        $this->assertEquals('checked_out', $this->item->status);
    }

    public function test_photographer_can_view_my_gear(): void
    {
        $booking = Booking::factory()->create([
            'photographer_id' => $this->photographer->id,
            'event_date' => now()->addWeek(),
        ]);

        EquipmentCheckout::create([
            'booking_id' => $booking->id,
            'user_id' => $this->photographer->id,
            'equipment_item_id' => $this->item->id,
            'checked_out_at' => now(),
            'expected_return_at' => now()->addWeek()->addDay(),
            'condition_out' => 'excellent',
            'status' => 'active',
        ]);

        $response = $this->actingAs($this->photographer)
            ->get(route('photographer.equipment.my-gear'));

        $response->assertStatus(200)
            ->assertSee('Sony A7 IV');
    }

    public function test_photographer_can_check_in_equipment(): void
    {
        $booking = Booking::factory()->create([
            'photographer_id' => $this->photographer->id,
            'event_date' => now()->subDay(),
        ]);

        $checkout = EquipmentCheckout::create([
            'booking_id' => $booking->id,
            'user_id' => $this->photographer->id,
            'equipment_item_id' => $this->item->id,
            'checked_out_at' => now()->subWeek(),
            'expected_return_at' => now()->subDay(),
            'condition_out' => 'excellent',
            'status' => 'active',
        ]);

        $this->item->update(['status' => 'checked_out']);

        $response = $this->actingAs($this->photographer)
            ->patch(route('photographer.equipment.checkin', $checkout), [
                'condition_in' => 'good',
            ]);

        $response->assertRedirect();

        $checkout->refresh();
        $this->assertEquals('returned', $checkout->status);
        $this->assertNotNull($checkout->returned_at);

        $this->item->refresh();
        $this->assertEquals('available', $this->item->status);
    }

    public function test_equipment_item_availability_check(): void
    {
        $booking = Booking::factory()->create([
            'event_date' => now()->addWeek(),
        ]);

        $this->assertTrue($this->item->isAvailableFor($booking->event_date));

        EquipmentCheckout::create([
            'booking_id' => $booking->id,
            'user_id' => $this->photographer->id,
            'equipment_item_id' => $this->item->id,
            'checked_out_at' => now(),
            'expected_return_at' => $booking->event_date->addDay(),
            'condition_out' => 'excellent',
            'status' => 'active',
        ]);

        $this->assertFalse($this->item->fresh()->isAvailableFor($booking->event_date));
    }

    public function test_equipment_checkout_overdue_detection(): void
    {
        $booking = Booking::factory()->create([
            'event_date' => now()->subWeek(),
        ]);

        $checkout = EquipmentCheckout::create([
            'booking_id' => $booking->id,
            'user_id' => $this->photographer->id,
            'equipment_item_id' => $this->item->id,
            'checked_out_at' => now()->subWeek(),
            'expected_return_at' => now()->subDay(),
            'condition_out' => 'excellent',
            'status' => 'active',
        ]);

        $this->assertTrue($checkout->isOverdue());

        $checkout->update(['status' => 'returned']);
        $this->assertFalse($checkout->fresh()->isOverdue());
    }
}
