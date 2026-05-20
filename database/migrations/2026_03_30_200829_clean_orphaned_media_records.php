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
        // Remove orphaned media records with the old 'storage/media/' prefix
        // These were created by a bug in the MediaFactory and have no files on disk
        DB::table('media')->where('file_path', 'like', 'storage/media/%')->delete();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Orphaned records cannot be restored — files never existed on disk
    }
};
