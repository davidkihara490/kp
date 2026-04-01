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
        Schema::create('pick_up_and_drop_off_points', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code');
            $table->foreignId('town_id')->nullable()->constrained();
            $table->string('building')->nullable();
            $table->string('room_number')->nullable();
            $table->string('address')->nullable();
            $table->enum('status', ['active', 'inactive', 'pending', 'suspended'])->default('active');
            $table->foreignId('partner_id')->nullable()->constrained();
            $table->string('contact_person')->nullable();
            $table->string('contact_email')->nullable();
            $table->string('contact_phone_number')->nullable();
            $table->time('opening_hours')->nullable();
            $table->json('operating_days')->nullable();
            $table->time('closing_hours')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pick_up_and_drop_off_points');
    }
};
