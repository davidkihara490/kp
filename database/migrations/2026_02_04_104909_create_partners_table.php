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
        Schema::create('partners', function (Blueprint $table) {
            $table->id();

            /* ==============================
             * Company Details
             * ============================== */
            $table->enum('partner_type', ['transport', 'pickup-dropoff']);

            $table->foreignId('owner_id')->nullable()->constrained('users')->onDelete('cascade');
            $table->foreignId('incharge_id')->nullable()->constrained('users')->onDelete('cascade');

            $table->string('company_name');
            $table->string('registration_number')->nullable();
            $table->string('registration_certificate_path')->nullable();
            $table->string('kra_pin')->nullable();
            $table->string('pin_certificate_path')->nullable();

            /* ==============================
             * Station / Points Details
             * ============================== */
            $table->unsignedInteger('points_count')->default(0);
            $table->boolean('points_have_phone')->default(false);
            $table->boolean('points_have_computer')->default(false);
            $table->boolean('points_have_internet')->default(false);
            $table->boolean('officers_knowledgeable')->default(false);
            $table->boolean('points_compliant')->default(false);
            $table->string('compliance_certificate_path')->nullable();

            /* ==============================
             * Operational Details
             * ============================== */
            $table->string('operating_hours')->nullable();
            $table->unsignedInteger('maximum_capacity_per_day')->nullable();
            $table->string('storage_facility_type')->nullable();
            $table->text('security_measures')->nullable();
            $table->string('insurance_coverage')->nullable();
            $table->text('additional_notes')->nullable();

            /* ==============================
             * Fleet Details
             * ============================== */
            $table->unsignedInteger('fleet_count')->default(0);
            $table->enum('fleet_ownership', ['owned', 'subcontracted', 'both'])->nullable();
            $table->boolean('fleet_insured')->default(false);
            $table->string('insurance_certificate_path')->nullable();
            $table->boolean('fleets_compliant')->default(false);
            $table->unsignedInteger('driver_count')->default(0);
            $table->boolean('drivers_compliant')->default(false);
            $table->string('drivers_certificate_path')->nullable();

            /* ==============================
             * Fleet Types
             * ============================== */
            $table->boolean('has_motorcycles')->default(false);
            $table->boolean('has_vans')->default(false);
            $table->boolean('has_trucks')->default(false);
            $table->boolean('has_refrigerated')->default(false);
            $table->string('other_fleet_types')->nullable();

            /* ==============================
             * Office & Booking Details
             * ============================== */
            $table->boolean('has_computer')->default(false);
            $table->boolean('has_internet')->default(false);
            $table->json('booking_emails')->nullable();
            $table->boolean('has_dedicated_allocator')->default(false);
            $table->string('allocator_name')->nullable();
            $table->string('allocator_phone')->nullable();

            /* ==============================
             * Capacity & Coverage
             * ============================== */
            $table->unsignedInteger('maximum_daily_capacity')->nullable();
            $table->unsignedInteger('maximum_distance')->nullable();
            $table->boolean('can_handle_fragile')->default(false);
            $table->boolean('can_handle_perishable')->default(false);
            $table->boolean('can_handle_valuables')->default(false);

            /* ==============================
             * Experience & Systems
             * ============================== */
            $table->unsignedInteger('years_in_operation')->nullable();
            $table->text('previous_courier_experience')->nullable();
            $table->decimal('insurance_coverage_amount', 15, 2)->nullable();
            $table->text('safety_measures')->nullable();
            $table->string('tracking_system')->nullable();

            /* ==============================
             * System Fields
             * ============================== */
            $table->string('onboarding_step')->default('pending');
            $table->string('verification_status')->default('pending');

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('partners');
    }
};
