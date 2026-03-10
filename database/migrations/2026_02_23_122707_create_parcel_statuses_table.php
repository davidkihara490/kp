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
        Schema::create('parcel_statuses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('parcel_id')->constrained()->cascadeOnDelete();
            $table->string('status'); 
            $table->unsignedBigInteger('changed_by')->nullable();
            $table->string('changed_by_type')->nullable();
            $table->text('notes')->nullable();      
            $table->integer('otp')->nullable();
            $table->boolean('otp_verified')->default(false);
            $table->timestamp('otp_expires_at')->nullable();
            $table->unsignedBigInteger('driver_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('parcel_statuses');
    }
};
