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
        Schema::table('parcels', function (Blueprint $table) {
            $table->enum('delivery_flow', [
                'final_destination',
                'warehouse',
            ])->nullable()->after('delivery_pick_up_drop_off_point_id');
            $table->unsignedBigInteger('warehouse_id')->nullable()->after('delivery_flow')->index('parcels_warehouse_id_foreign');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('parcels', function (Blueprint $table) {
            $table->dropColumn('delivery_flow');
            $table->dropColumn('warehouse_id');
        });
    }
};
