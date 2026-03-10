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
        Schema::create('mpesa_transactions', function (Blueprint $table) {
            $table->id();
            $table->nullableMorphs('payable'); // Creates payable_id and payable_type
            $table->nullableMorphs('related'); // Creates related_id and related_type
            $table->string('merchant_request_id')->nullable()->index();
            $table->string('checkout_request_id')->nullable()->unique()->index();
            $table->string('result_code')->nullable()->index();
            $table->text('result_description')->nullable();

            // Response fields from STK Push
            $table->string('response_code')->nullable()->index();
            $table->text('response_description')->nullable();
            $table->text('customer_message')->nullable();

            // Payment details from callback
            $table->string('mpesa_receipt_number')->nullable()->unique()->index();
            $table->string('transaction_date')->nullable();
            $table->decimal('amount', 10, 2)->default(0);
            $table->decimal('amount_paid', 10, 2)->nullable();

            // Phone numbers
            $table->string('phone_number'); // Original phone number from request
            $table->string('payer_phone')->nullable(); // Phone from callback (might differ)

            // Reference information
            $table->string('account_reference')->nullable()->index();
            $table->text('transaction_desc')->nullable();

            // Status tracking
            $table->enum('status', [
                'pending',      // STK Push sent, waiting for user
                'processing',   // User has entered PIN
                'completed',    // Payment successful
                'failed',       // Payment failed
                'cancelled',    // User cancelled
                'expired',      // STK Push expired
            ])->default('pending')->index();

            // Business logic fields
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->string('transaction_type')->default('subscription')->index(); // subscription, sale, expense, etc.
            // $table->foreignId('related_id')->nullable()->comment('ID of related record (sale_id, subscription_id, etc.)');
            // $table->string('related_type')->nullable()->comment('Type of related record');

            // Metadata
            $table->json('callback_data')->nullable()->comment('Raw callback data from M-Pesa');
            $table->json('request_data')->nullable()->comment('Original request data');
            $table->json('raw_response')->nullable()->comment('Raw response from STK Push');
            $table->text('error_message')->nullable();

            // Retry and timing fields
            $table->tinyInteger('retry_count')->default(0);
            $table->timestamp('last_retry_at')->nullable();
            $table->timestamp('expires_at')->nullable()->index();
            $table->timestamp('completed_at')->nullable();

            // Laravel timestamps
            $table->timestamps();
            $table->softDeletes();

            // Additional indexes for performance
            // $table->index(['status', 'created_at']);
            // $table->index(['status', 'expires_at']);
            // $table->index(['user_id', 'status']);
            // $table->index(['status']);
            // $table->index(['transaction_type', ']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mpesa_transactions');
    }
};
