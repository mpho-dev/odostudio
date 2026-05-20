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
        // Add index for booking_requests status filtering
        Schema::table('booking_requests', function (Blueprint $table) {
            if (! Schema::hasIndex('booking_requests', 'booking_requests_status_index')) {
                $table->index('status');
            }
            if (! Schema::hasIndex('booking_requests', 'booking_requests_email_index')) {
                $table->index('email');
            }
            if (! Schema::hasIndex('booking_requests', 'booking_requests_phone_index')) {
                $table->index('phone');
            }
        });

        // Add composite index for photographer_id + status on bookings (common query pattern)
        Schema::table('bookings', function (Blueprint $table) {
            if (! Schema::hasIndex('bookings', 'bookings_photographer_id_status_index')) {
                $table->index(['photographer_id', 'status']);
            }
            if (! Schema::hasIndex('bookings', 'bookings_event_date_index')) {
                $table->index('event_date');
            }
        });

        // Add composite index for invoices (common filtering)
        Schema::table('invoices', function (Blueprint $table) {
            if (! Schema::hasIndex('invoices', 'invoices_created_by_status_index')) {
                $table->index(['created_by', 'status']);
            }
        });

        // Add index for media -> project queries (portfolio lookups)
        Schema::table('media', function (Blueprint $table) {
            if (! Schema::hasIndex('media', 'media_project_id_index')) {
                $table->index('project_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('booking_requests', function (Blueprint $table) {
            $table->dropIndexIfExists('booking_requests_status_index');
            $table->dropIndexIfExists('booking_requests_email_index');
            $table->dropIndexIfExists('booking_requests_phone_index');
        });

        Schema::table('bookings', function (Blueprint $table) {
            $table->dropIndexIfExists('bookings_photographer_id_status_index');
            $table->dropIndexIfExists('bookings_event_date_index');
        });

        Schema::table('invoices', function (Blueprint $table) {
            $table->dropIndexIfExists('invoices_created_by_status_index');
        });

        Schema::table('media', function (Blueprint $table) {
            $table->dropIndexIfExists('media_project_id_index');
        });
    }
};
