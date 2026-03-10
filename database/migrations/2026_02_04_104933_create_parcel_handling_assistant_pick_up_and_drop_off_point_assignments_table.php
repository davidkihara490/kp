<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('parcel_handling_assistant_pick_up_and_drop_off_point_assignments', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('parcel_handling_assistant_id');
            $table->unsignedBigInteger('pick_up_and_drop_off_point_id');
            $table->unsignedBigInteger('partner_id');
            $table->unsignedBigInteger('assigned_by');

            $table->timestamp('from')->nullable();
            $table->timestamp('to')->nullable();
            $table->string('notes')->nullable();
            $table->string('status')->nullable();

            $table->timestamps();
            $table->softDeletes();

            // Short foreign key names (important)
            $table->foreign('parcel_handling_assistant_id', 'pha_fk')
                ->references('id')
                ->on('parcel_handling_assistants')
                ->cascadeOnDelete();

            $table->foreign('pick_up_and_drop_off_point_id', 'pd_point_fk')
                ->references('id')
                ->on('pick_up_and_drop_off_points')
                ->cascadeOnDelete();

            $table->foreign('partner_id', 'partner_fk')
                ->references('id')
                ->on('partners')
                ->cascadeOnDelete();

            $table->foreign('assigned_by', 'assigned_by_fk')
                ->references('id')
                ->on('users')
                ->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('parcel_handling_assistant_pick_up_and_drop_off_point_assignments');
    }
};