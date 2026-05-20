<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('equipment_checkouts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained();
            $table->foreignId('equipment_item_id')->constrained('equipment_items');
            $table->timestamp('checked_out_at');
            $table->timestamp('expected_return_at');
            $table->timestamp('returned_at')->nullable();
            $table->string('condition_out')->default('excellent');
            $table->string('condition_in')->nullable();
            $table->text('checkout_notes')->nullable();
            $table->text('return_notes')->nullable();
            $table->string('status')->default('active');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('equipment_checkouts');
    }
};
