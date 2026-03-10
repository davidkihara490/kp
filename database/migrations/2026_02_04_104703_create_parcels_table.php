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
        Schema::create('parcels', function (Blueprint $table) {
            // Primary Key
            $table->id();

            // ==================== BASIC INFORMATION ====================
            $table->string('parcel_id')->unique();
            $table->unsignedBigInteger('customer_id')->nullable();
            $table->enum('booking_type', ['instant', 'scheduled', 'bulk'])->default('scheduled');

            // ==================== SENDER INFORMATION ====================
            $table->unsignedBigInteger('sender_id')->nullable();
            $table->unsignedBigInteger('pha_id')->nullable();
            $table->string('sender_name');
            $table->string('sender_phone');
            $table->string('sender_email')->nullable();
            $table->foreignId('sender_town_id')->nullable();
            $table->text('sender_notes')->nullable();

            // ==================== RECEIVER INFORMATION ====================
            $table->unsignedBigInteger('receiver_id')->nullable();
            $table->string('receiver_name');
            $table->string('receiver_phone');
            $table->string('receiver_email')->nullable();
            $table->text('receiver_address');
            $table->foreignId('receiver_town_id')->nullable();
            $table->text('receiver_notes')->nullable();

            // ==================== PICKUP INFORMATION ====================
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('sender_partner_id')->nullable();
            $table->unsignedBigInteger('sender_pick_up_drop_off_point_id')->nullable();
            $table->date('date')->nullable();

            // ==================== PARCEL DETAILS ====================
            $table->enum('parcel_type', ['document', 'package', 'envelope', 'box', 'pallet', 'other'])->default('package');
            $table->enum('package_type', ['regular', 'fragile', 'perishable', 'valuable', 'hazardous', 'oversized'])->default('regular');
            $table->decimal('weight', 8, 2)->nullable();
            $table->decimal('length', 8, 2)->nullable();
            $table->decimal('width', 8, 2)->nullable();
            $table->decimal('height', 8, 2)->nullable();
            $table->enum('dimension_unit', ['cm', 'inches'])->default('cm');
            $table->enum('weight_unit', ['kg', 'g', 'lb'])->default('kg');
            $table->decimal('declared_value', 12, 2)->default(0);
            $table->decimal('insurance_amount', 12, 2)->default(0);
            $table->boolean('insurance_required')->default(false);
            $table->text('content_description')->nullable();
            $table->text('special_instructions')->nullable();

            // ==================== DELIVERY INFORMATION ====================
            $table->unsignedBigInteger('delivery_partner_id')->nullable();
            $table->unsignedBigInteger('delivery_pick_up_drop_off_point_id')->nullable();

            // ==================== PRICING ====================
            $table->decimal('base_price', 12, 2)->default(0);
            $table->decimal('weight_charge', 12, 2)->default(0);
            $table->decimal('distance_charge', 12, 2)->default(0);
            $table->decimal('special_handling_charge', 12, 2)->default(0);
            $table->decimal('insurance_charge', 12, 2)->default(0);
            $table->decimal('tax_amount', 12, 2)->default(0);
            $table->decimal('discount_amount', 12, 2)->default(0);
            $table->decimal('total_amount', 12, 2)->default(0);
            $table->enum('payment_method', ['cash', 'mpesa', 'card', 'bank_transfer', 'wallet'])->nullable();
            $table->enum('payment_status', ['pending', 'paid', 'failed', 'refunded', 'partially_paid'])->default('pending');
            $table->timestamp('paid_at')->nullable();

            $table->string('current_status')->nullable();
            $table->string('booking_source')->nullable();

            $table->decimal('payout', 12, 2)->default(0);

            $table->unsignedBigInteger('transport_partner_id')->nullable();
            $table->unsignedBigInteger('driver_id')->nullable();

            // Timestamps & Soft Deletes
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('parcels');
    }
};
