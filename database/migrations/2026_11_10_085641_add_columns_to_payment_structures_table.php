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
            $table->unsignedBigInteger('item_id')->nullable()->after('delivery_type');
            $table->enum('pick_up_drop_off_partner_type', ['fixed', 'percentage'])->default('fixed')->after('tax_percentage');
            $table->decimal('platform_fixed_amount', 5, 2)->default(0.00)->after('platform_percentage');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payment_structures', function (Blueprint $table) {
            $table->dropColumn('item_id');
            $table->dropColumn('pick_up_drop_off_partner_type');
            $table->dropColumn('platform_fixed_amount');
        });
    }
};
