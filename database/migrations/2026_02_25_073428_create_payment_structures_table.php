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
        Schema::create('payment_structures', function (Blueprint $table) {
            $table->id();
            $table->enum('delivery_type', [
                'direct',          // pickup → dropoff
                'warehouse_split', // point → warehouse → point
            ]);
            $table->decimal('tax_percentage', 5, 2)->default(16.00);
            $table->decimal('pick_up_drop_off_partner_percentage', 5, 2)->default(20.00);
            $table->decimal('transport_partner_percentage', 5, 2)->default(30.00);
            $table->decimal('platform_percentage', 5, 2)->default(30.00);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_structures');
    }
};
