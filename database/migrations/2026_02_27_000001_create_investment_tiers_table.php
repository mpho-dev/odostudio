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
        Schema::create('investment_tiers', function (Blueprint $table) {
            $table->id();
            $table->string('tier_label');
            $table->string('name');
            $table->decimal('price', 10, 2);
            $table->string('price_suffix')->nullable();
            $table->boolean('is_featured')->default(false);
            $table->string('badge_label')->nullable();
            $table->json('features')->nullable();
            $table->unsignedInteger('order')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('investment_tiers');
    }
};
