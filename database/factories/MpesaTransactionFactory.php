<?php

namespace Database\Factories;

use App\Models\MpesaTransaction;
use App\Models\User;
use App\Models\Payment;
use App\Models\Subscription;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\MpesaTransaction>
 */
class MpesaTransactionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $statuses = [
            MpesaTransaction::STATUS_PENDING,
            MpesaTransaction::STATUS_PROCESSING,
            MpesaTransaction::STATUS_COMPLETED,
            MpesaTransaction::STATUS_FAILED,
            MpesaTransaction::STATUS_CANCELLED,
            MpesaTransaction::STATUS_EXPIRED,
        ];
        
        $transactionTypes = [
            MpesaTransaction::TYPE_SUBSCRIPTION,
            MpesaTransaction::TYPE_SALE,
            MpesaTransaction::TYPE_EXPENSE,
            MpesaTransaction::TYPE_TOPUP,
            MpesaTransaction::TYPE_REFUND,
        ];
        
        $status = fake()->randomElement($statuses);
        $transactionType = fake()->randomElement($transactionTypes);
        $amount = fake()->randomFloat(2, 100, 50000);
        
        // Determine if transaction is successful
        $isSuccessful = $status === MpesaTransaction::STATUS_COMPLETED && fake()->boolean(80);
        $resultCode = $isSuccessful ? MpesaTransaction::RESULT_SUCCESS : $this->getRandomResultCode();
        
        // Generate M-Pesa receipt number (format: PGI22L4QK7)
        $mpesaReceipt = $status === MpesaTransaction::STATUS_COMPLETED 
            ? 'PGI' . strtoupper(fake()->bothify('??###??#')) . fake()->randomNumber(1)
            : null;
        
        // Generate transaction date
        $transactionDate = $status === MpesaTransaction::STATUS_COMPLETED 
            ? fake()->dateTimeBetween('-3 months', 'now')
            : null;
        
        // Set expiry date for pending transactions
        $expiresAt = $status === MpesaTransaction::STATUS_PENDING 
            ? now()->addMinutes(30)
            : null;
        
        // Get or create a user
        $user = User::inRandomOrder()->first() ?? User::factory()->create();
        
        return [
            'payable_id' => null, // Will be set by state
            'payable_type' => null, // Will be set by state
            'merchant_request_id' => fake()->uuid(),
            'checkout_request_id' => fake()->uuid(),
            'result_code' => $resultCode,
            'result_description' => $this->getResultDescription($resultCode),
            'response_code' => $resultCode,
            'response_description' => $this->getResultDescription($resultCode),
            'customer_message' => $this->getCustomerMessage($status),
            'mpesa_receipt_number' => $mpesaReceipt,
            'transaction_date' => $transactionDate,
            'amount' => $amount,
            'amount_paid' => $isSuccessful ? $amount : 0,
            'phone_number' => $this->generateKenyanPhone(),
            'payer_phone' => $this->generateKenyanPhone(),
            'account_reference' => $transactionType === MpesaTransaction::TYPE_SUBSCRIPTION 
                ? 'SUB-' . fake()->randomNumber(5)
                : 'PAY-' . fake()->randomNumber(5),
            'transaction_desc' => $this->getTransactionDescription($transactionType),
            'status' => $status,
            'user_id' => $user->id,
            'transaction_type' => $transactionType,
            'related_id' => null,
            'related_type' => null,
            'callback_data' => [
                'Body' => [
                    'stkCallback' => [
                        'MerchantRequestID' => fake()->uuid(),
                        'CheckoutRequestID' => fake()->uuid(),
                        'ResultCode' => $resultCode,
                        'ResultDesc' => $this->getResultDescription($resultCode),
                    ]
                ]
            ],
            'request_data' => [
                'BusinessShortCode' => '174379',
                'Password' => fake()->sha1(),
                'Timestamp' => now()->format('YmdHis'),
                'TransactionType' => 'CustomerPayBillOnline',
                'Amount' => $amount,
                'PartyA' => $this->generateKenyanPhone(),
                'PartyB' => '174379',
                'PhoneNumber' => $this->generateKenyanPhone(),
                'CallBackURL' => 'https://api.karibuparcels.com/mpesa/callback',
                'AccountReference' => $transactionType === MpesaTransaction::TYPE_SUBSCRIPTION ? 'SUB' : 'PAY',
                'TransactionDesc' => $this->getTransactionDescription($transactionType),
            ],
            'raw_response' => $isSuccessful ? [
                'MerchantRequestID' => fake()->uuid(),
                'CheckoutRequestID' => fake()->uuid(),
                'ResponseCode' => '0',
                'ResponseDescription' => 'Success. Request accepted for processing',
                'CustomerMessage' => 'Success. Request accepted for processing',
            ] : null,
            'error_message' => $status === MpesaTransaction::STATUS_FAILED 
                ? fake()->randomElement(['Insufficient balance', 'Network timeout', 'Invalid PIN'])
                : null,
            'retry_count' => $status === MpesaTransaction::STATUS_FAILED ? fake()->numberBetween(1, 3) : 0,
            'last_retry_at' => function (array $attributes) {
                return $attributes['retry_count'] > 0 ? fake()->dateTimeBetween('-2 days', '-1 hour') : null;
            },
            'expires_at' => $expiresAt,
            'completed_at' => $status === MpesaTransaction::STATUS_COMPLETED ? now() : null,
        ];
    }

    /**
     * Generate a Kenyan phone number
     */
    private function generateKenyanPhone(): string
    {
        $prefixes = ['0700', '0710', '0722', '0733', '0740', '0757', '0768', '0777', '0780', '0792', '0110'];
        $prefix = fake()->randomElement($prefixes);
        $suffix = str_pad(fake()->numberBetween(0, 999999), 6, '0', STR_PAD_LEFT);
        return $prefix . $suffix;
    }

    /**
     * Get random result code
     */
    private function getRandomResultCode(): string
    {
        return fake()->randomElement([
            MpesaTransaction::RESULT_SUCCESS,
            MpesaTransaction::RESULT_CANCELLED,
            MpesaTransaction::RESULT_TIMEOUT,
            MpesaTransaction::RESULT_INSUFFICIENT_FUNDS,
            '1031', // Invalid account
            '1033', // Transaction expired
            '1036', // Transaction failed
        ]);
    }

    /**
     * Get result description based on result code
     */
    private function getResultDescription(?string $resultCode): string
    {
        return match ($resultCode) {
            MpesaTransaction::RESULT_SUCCESS => 'The service request is processed successfully.',
            MpesaTransaction::RESULT_CANCELLED => 'Request cancelled by user',
            MpesaTransaction::RESULT_TIMEOUT => 'The transaction was timed out',
            MpesaTransaction::RESULT_INSUFFICIENT_FUNDS => 'The balance is insufficient for the transaction',
            '1031' => 'Invalid account number',
            '1033' => 'Transaction expired',
            '1036' => 'Transaction failed',
            default => 'Unknown error occurred',
        };
    }

    /**
     * Get customer message based on status
     */
    private function getCustomerMessage(string $status): string
    {
        return match ($status) {
            MpesaTransaction::STATUS_COMPLETED => 'Success. Transaction completed successfully',
            MpesaTransaction::STATUS_PENDING => 'Please enter your PIN to complete transaction',
            MpesaTransaction::STATUS_PROCESSING => 'Processing your request',
            MpesaTransaction::STATUS_FAILED => 'Transaction failed. Please try again',
            MpesaTransaction::STATUS_CANCELLED => 'Transaction cancelled by user',
            MpesaTransaction::STATUS_EXPIRED => 'Transaction expired',
            default => 'Unknown status',
        };
    }

    /**
     * Get transaction description based on type
     */
    private function getTransactionDescription(string $type): string
    {
        return match ($type) {
            MpesaTransaction::TYPE_SUBSCRIPTION => 'Subscription payment',
            MpesaTransaction::TYPE_SALE => 'Sale transaction',
            MpesaTransaction::TYPE_EXPENSE => 'Expense payment',
            MpesaTransaction::TYPE_TOPUP => 'Account topup',
            MpesaTransaction::TYPE_REFUND => 'Refund transaction',
            default => 'General transaction',
        };
    }

    /**
     * Indicate that the transaction is completed
     */
    public function completed(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => MpesaTransaction::STATUS_COMPLETED,
            'result_code' => MpesaTransaction::RESULT_SUCCESS,
            'result_description' => 'The service request is processed successfully.',
            'response_code' => MpesaTransaction::RESULT_SUCCESS,
            'customer_message' => 'Success. Transaction completed successfully',
            'mpesa_receipt_number' => 'PGI' . strtoupper(fake()->bothify('??###??#')) . fake()->randomNumber(1),
            'transaction_date' => now(),
            'amount_paid' => $attributes['amount'] ?? fake()->randomFloat(2, 100, 50000),
            'completed_at' => now(),
            'expires_at' => null,
            'retry_count' => 0,
        ]);
    }

    /**
     * Indicate that the transaction is pending
     */
    public function pending(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => MpesaTransaction::STATUS_PENDING,
            'result_code' => null,
            'result_description' => null,
            'response_code' => null,
            'mpesa_receipt_number' => null,
            'transaction_date' => null,
            'amount_paid' => 0,
            'expires_at' => now()->addMinutes(30),
            'completed_at' => null,
        ]);
    }

    /**
     * Indicate that the transaction is processing
     */
    public function processing(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => MpesaTransaction::STATUS_PROCESSING,
            'result_code' => null,
            'result_description' => null,
            'customer_message' => 'Processing your request',
            'mpesa_receipt_number' => null,
            'completed_at' => null,
        ]);
    }

    /**
     * Indicate that the transaction failed
     */
    public function failed(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => MpesaTransaction::STATUS_FAILED,
            'result_code' => fake()->randomElement([
                MpesaTransaction::RESULT_TIMEOUT,
                MpesaTransaction::RESULT_INSUFFICIENT_FUNDS,
                '1036',
            ]),
            'result_description' => $this->getResultDescription($attributes['result_code'] ?? null),
            'customer_message' => 'Transaction failed. Please try again',
            'mpesa_receipt_number' => null,
            'amount_paid' => 0,
            'error_message' => fake()->randomElement(['Insufficient balance', 'Network timeout', 'Invalid PIN']),
            'completed_at' => null,
        ]);
    }

    /**
     * Indicate that the transaction was cancelled by user
     */
    public function cancelled(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => MpesaTransaction::STATUS_CANCELLED,
            'result_code' => MpesaTransaction::RESULT_CANCELLED,
            'result_description' => 'Request cancelled by user',
            'customer_message' => 'Transaction cancelled by user',
            'mpesa_receipt_number' => null,
            'amount_paid' => 0,
            'completed_at' => null,
        ]);
    }

    /**
     * Indicate that the transaction expired
     */
    public function expired(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => MpesaTransaction::STATUS_EXPIRED,
            'result_code' => '1033',
            'result_description' => 'Transaction expired',
            'customer_message' => 'Transaction expired',
            'mpesa_receipt_number' => null,
            'amount_paid' => 0,
            'expires_at' => now()->subMinutes(5),
            'completed_at' => null,
        ]);
    }

    /**
     * Indicate the transaction type
     */
    public function ofType(string $type): static
    {
        return $this->state(fn (array $attributes) => [
            'transaction_type' => $type,
            'transaction_desc' => $this->getTransactionDescription($type),
            'account_reference' => $type === MpesaTransaction::TYPE_SUBSCRIPTION 
                ? 'SUB-' . fake()->randomNumber(5)
                : 'PAY-' . fake()->randomNumber(5),
        ]);
    }

    /**
     * Indicate subscription payment
     */
    public function subscription(): static
    {
        return $this->ofType(MpesaTransaction::TYPE_SUBSCRIPTION);
    }

    /**
     * Indicate sale payment
     */
    public function sale(): static
    {
        return $this->ofType(MpesaTransaction::TYPE_SALE);
    }

    /**
     * Indicate topup payment
     */
    public function topup(): static
    {
        return $this->ofType(MpesaTransaction::TYPE_TOPUP);
    }

    /**
     * Indicate refund
     */
    public function refund(): static
    {
        return $this->ofType(MpesaTransaction::TYPE_REFUND);
    }

    /**
     * Indicate that the transaction is for a specific user
     */
    public function forUser(User $user): static
    {
        return $this->state(fn (array $attributes) => [
            'user_id' => $user->id,
        ]);
    }

    /**
     * Indicate that the transaction is for a specific payment (payable)
     */
    public function forPayment(Payment $payment): static
    {
        return $this->state(fn (array $attributes) => [
            'payable_id' => $payment->id,
            'payable_type' => Payment::class,
            'amount' => $payment->amount,
            'amount_paid' => $payment->amount,
            'phone_number' => $payment->phone ?? $this->generateKenyanPhone(),
            'payer_phone' => $payment->phone ?? $this->generateKenyanPhone(),
            'transaction_type' => MpesaTransaction::TYPE_SALE,
            'transaction_desc' => 'Payment for parcel',
            'account_reference' => 'PARCEL-' . $payment->parcel_id,
        ])->completed();
    }

    /**
     * Indicate that the transaction is for a specific subscription
     */
    public function forSubscription($subscription): static
    {
        return $this->state(fn (array $attributes) => [
            'payable_id' => $subscription->id,
            'payable_type' => get_class($subscription),
            'transaction_type' => MpesaTransaction::TYPE_SUBSCRIPTION,
            'transaction_desc' => 'Subscription payment',
            'account_reference' => 'SUB-' . $subscription->id,
        ])->completed();
    }

    /**
     * Indicate that the transaction has a specific receipt number
     */
    public function withReceipt(string $receipt): static
    {
        return $this->state(fn (array $attributes) => [
            'mpesa_receipt_number' => $receipt,
        ])->completed();
    }

    /**
     * Indicate that the transaction has a specific phone number
     */
    public function withPhone(string $phone): static
    {
        return $this->state(fn (array $attributes) => [
            'phone_number' => $phone,
            'payer_phone' => $phone,
        ]);
    }

    /**
     * Create a specific test transaction
     */
    public function testTransaction(): static
    {
        $user = User::where('email', 'admin@karibuparcels.com')->first() ?? User::factory()->create();
        
        return $this->state(fn (array $attributes) => [
            'user_id' => $user->id,
            'merchant_request_id' => 'TEST-MERCHANT-123',
            'checkout_request_id' => 'TEST-CHECKOUT-456',
            'mpesa_receipt_number' => 'PGI22L4QK7',
            'phone_number' => '0712345678',
            'payer_phone' => '0712345678',
            'amount' => 1500.00,
            'amount_paid' => 1500.00,
            'account_reference' => 'TEST123',
            'transaction_type' => MpesaTransaction::TYPE_SALE,
            'status' => MpesaTransaction::STATUS_COMPLETED,
            'result_code' => MpesaTransaction::RESULT_SUCCESS,
            'transaction_date' => now(),
            'completed_at' => now(),
        ]);
    }
}