<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Update the role name from photographer_videographer to crew
        DB::table('roles')
            ->where('name', 'photographer_videographer')
            ->update(['name' => 'crew']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert back to photographer_videographer
        DB::table('roles')
            ->where('name', 'crew')
            ->update(['name' => 'photographer_videographer']);
    }
};
