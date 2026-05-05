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
        Schema::create('parcel_payouts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('parcel_id')->constrained('parcels');
            $table->foreignId('partner_id')->constrained('partners');
            $table->enum('type', ['transport', 'pickup-dropoff']);
            $table->enum('destination', ['final', 'warehouse'])->nullable();
            $table->foreignId('destination_id')->nullable()->constrained('pick_up_and_drop_off_points');
            $table->foreignId('origin_id')->nullable()->constrained('pick_up_and_drop_off_points');
            $table->decimal('amount', 16, 4);
            $table->enum('status', ['pending', 'approved', 'complete', 'canceled']);
            $table->longText('cancelation_reason')->nullable();
            $table->timestamp('paid_out_on')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('parcel_payouts');
    }
};
