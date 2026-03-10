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
        Schema::create('fleets', function (Blueprint $table) {
            $table->id();

            $table->foreignId('partner_id')->constrained()->cascadeOnDelete();
            $table->string('registration_number')->unique();
            $table->string('make')->nullable();
            $table->string('model')->nullable();
            $table->string('vehicle_type')->nullable();
            $table->string('color')->nullable();
                       
            // Operational Status
            $table->string('status', )->nullable();
            $table->boolean('is_available')->default(true);
                    
            // Important Dates
            $table->date('registration_expiry')->nullable();
            $table->date('insurance_expiry')->nullable();
            $table->date('last_service_date')->nullable();
            $table->date('next_service_date')->nullable();
            
            // Basic Specifications
            $table->year('year')->nullable();
            $table->decimal('capacity', 10, 2)->nullable()->comment('Load capacity in kg');
            $table->enum('fuel_type', ['petrol', 'diesel', 'electric', 'hybrid'])->default('diesel');
            
            // Additional Info
            $table->text('notes')->nullable();
            
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fleets');
    }
};
