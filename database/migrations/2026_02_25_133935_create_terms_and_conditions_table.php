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
        Schema::create('terms_and_conditions', function (Blueprint $table) {
            $table->id();

            // Content fields
            $table->text('content');              // Full T&C text
            $table->string('title')->nullable();  // e.g., "Terms of Service v2.1"

            // Version control
            $table->string('version')->nullable(); // e.g., "1.0.0", "2024-03-15"
            $table->boolean('is_active')->default(false); // Current active version

            // Metadata
            $table->string('locale')->default('en'); // For multilingual support
            $table->foreignId('created_by')->nullable()->constrained('users');
            $table->foreignId('updated_by')->nullable()->constrained('users');

            // Effective date tracking
            $table->timestamp('effective_date')->nullable();
            $table->timestamp('expiry_date')->nullable(); // If terms expire

            // User acceptance tracking (optional but common)
            $table->boolean('requires_acceptance')->default(true);

            $table->timestamps();
            $table->softDeletes(); // If you want to keep history

            // Indexes
            $table->index('is_active');
            $table->index('version');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('terms_and_conditions');
    }
};
