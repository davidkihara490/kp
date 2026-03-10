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
        Schema::create('parcel_handling_assistant_employments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pha_id')
                ->constrained('parcel_handling_assistants')
                ->onDelete('cascade');
            $table->foreignId('partner_id')
                ->constrained('partners')
                ->onDelete('cascade');
            $table->date('from')->nullable();
            $table->date('to')->nullable();
            $table->enum('status', ['active', 'suspended', 'inactive', 'pending'])->default('active');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('parcel_handling_assistant_employments');
    }
};
