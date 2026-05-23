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
        Schema::table('payment_structures', function (Blueprint $table) {
            $table->decimal('platform_deduction', 5, 2)->default(20.00);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payment_structures', function (Blueprint $table) {
            $table->dropColumn('platform_deduction');
        });
    }
};
