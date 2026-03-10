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
        Schema::create('privacy_policies', function (Blueprint $table) {
            $table->id();
             // Content fields
            $table->longText('content');              // Full privacy policy text
            $table->string('title')->nullable();       // e.g., "Privacy Policy v2.1"
            
            // Version control
            $table->string('version')->nullable();      // e.g., "1.0.0", "2024-03-15"
            $table->boolean('is_active')->default(false); // Current active version
            
            // Metadata
            $table->string('locale')->default('en');    // For multilingual support
            $table->foreignId('created_by')->nullable()->constrained('users');
            $table->foreignId('updated_by')->nullable()->constrained('users');
            
            // Effective date tracking
            $table->timestamp('effective_date')->nullable();
            $table->timestamp('expiry_date')->nullable(); // If policy expires
            
            // GDPR/CCPA specific fields
            $table->boolean('requires_consent')->default(true); // Requires user consent
            $table->json('data_categories')->nullable(); // Categories of data collected
            $table->json('processing_purposes')->nullable(); // Purposes of data processing
            $table->string('contact_email')->nullable(); // Data protection contact
            $table->string('data_controller')->nullable(); // Company/entity name
            
            // User acceptance tracking
            $table->boolean('requires_acceptance')->default(true);
            
            $table->timestamps();
            $table->softDeletes();
            
            // Indexes
            $table->index('is_active');
            $table->index('version');
            $table->index('locale');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('privacy_policies');
    }
};
