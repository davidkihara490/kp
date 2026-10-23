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
        Schema::create('partner_rates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('partner_id')->constrained();
            $table->foreignId('item_id')->constrained();
            $table->foreignId('source_zone_id')->constrained('zones');
            $table->foreignId('destination_zone_id')->constrained('zones');
            $table->decimal('cost', 16, 2)->default(0);
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('partner_rates');
    }
};
