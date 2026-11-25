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
        Schema::table('parcels', function (Blueprint $table) {
            $table->json('booking_image_paths')->nullable();
        });
        Schema::create('parcel_booking_requests', function (Blueprint $table) {
            $table->id();
            $table->string('actor', 128);
            $table->uuid('request_id');
            $table->char('fingerprint', 64);
            $table->json('parcel_ids');
            $table->unique(['actor', 'request_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('parcel_booking_requests');
        Schema::table('parcels', fn(Blueprint $table) => $table->dropColumn('booking_image_paths'));
    }
};
