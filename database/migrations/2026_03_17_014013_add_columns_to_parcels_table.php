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
            // Who is transporting (Driver OR Partner)
            $table->unsignedBigInteger('transporter_id')->after('driver_id')->nullable();
            $table->string('transporter_type')->after('transporter_id')->nullable();

            // Who created it
            $table->unsignedBigInteger('creator_id')->after('transporter_type')->nullable();
            $table->string('creator_type')->after('creator_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('parcels', function (Blueprint $table) {
            //
        });
    }
};
