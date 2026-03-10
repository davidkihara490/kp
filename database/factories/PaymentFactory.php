<?php

namespace Database\Factories;

use App\Models\Payment;
use App\Models\Parcel;
use App\Models\User;
use App\Models\MpesaTransaction;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Payment>
 */
class PaymentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $paymentMethods = ['cash', 'mpesa', 'card', 'bank_transfer', 'wallet'];
        $statuses = ['pending', 'completed', 'failed', 'refunded'];
        
        $paymentMethod = fake()->randomElement($paymentMethods);
        $status = fake()->randomElement($statuses);
        
        // Get or create a parcel
        $parcel = Parcel::inRandomOrder()->first() ?? Parcel::factory()->create();
        
        // Get or create a user for paid_by
        $paidBy = User::inRandomOrder()->first() ?? User::factory()->create();
        
        $amount = $parcel->total_amount ?? fake()->randomFloat(2, 100, 50000);
        $paymentDate = fake()->dateTimeBetween('-3 months', 'now');
        
        $referenceNumber = $this->generateReferenceNumber($paymentMethod);
        
        $phone = $paymentMethod === 'mpesa' ? $this->generateKenyanPhone() : null;
        
        $mpesaTransactionId = null;
        if ($paymentMethod === 'mpesa' && $status === 'completed') {
            $mpesaTransactionId = MpesaTransaction::inRandomOrder()->first()?->id 
                ?? MpesaTransaction::factory()->create()->id;
        }

        return [
            'reference_number' => $referenceNumber,
            'parcel_id' => $parcel->id,
            'amount' => $amount,
            'payment_method' => $paymentMethod,
            'payment_date' => $paymentDate,
            'status' => $status,
            'phone' => $phone,
            'notes' => fake()->optional(0.3)->sentence(),
            'paid_by' => $paidBy->id,
            'mpesa_transaction_id' => $mpesaTransactionId,
        ];
    }

    /**
     * Generate a reference number based on payment method
     */
    private function generateReferenceNumber(string $paymentMethod): string
    {
        static $usedReferences = [];
        
        $prefix = match($paymentMethod) {
            'mpesa' => 'MP',
            'cash' => 'CS',
            'card' => 'CD',
            'bank_transfer' => 'BT',
            'wallet' => 'WL',
            default => 'PY',
        };
        
        $date = now()->format('Ymd');
        $random = strtoupper(substr(uniqid(), -6));
        $reference = $prefix . $date . $random;
        
        // Ensure uniqueness
        $attempts = 0;
        while (in_array($reference, $usedReferences) && $attempts < 100) {
            $random = strtoupper(substr(uniqid() . rand(100, 999), -6));
            $reference = $prefix . $date . $random;
            $attempts++;
        }
        
        $usedReferences[] = $reference;
        return $reference;
    }

    /**
     * Generate a Kenyan phone number
     */
    private function generateKenyanPhone(): string
    {
        $prefixes = ['0700', '0722', '0733', '0740', '0757', '0768', '0777', '0780', '0792', '0110'];
        $prefix = fake()->randomElement($prefixes);
        $suffix = str_pad(fake()->numberBetween(0, 999999), 6, '0', STR_PAD_LEFT);
        return $prefix . $suffix;
    }

    /**
     * Indicate that the payment is completed
     */
    public function completed(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'completed',
            'payment_date' => fake()->dateTimeBetween('-2 months', 'now'),
        ]);
    }

    /**
     * Indicate that the payment is pending
     */
    public function pending(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'pending',
            'payment_date' => null,
        ]);
    }

    /**
     * Indicate that the payment failed
     */
    public function failed(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'failed',
            'notes' => fake()->randomElement([
                'Insufficient funds',
                'Transaction timeout',
                'Cancelled by user',
                'Network error',
                'Invalid PIN'
            ]),
        ]);
    }

    /**
     * Indicate that the payment is refunded
     */
    public function refunded(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'refunded',
            'notes' => 'Payment refunded to customer',
        ]);
    }

    /**
     * Indicate that the payment is M-Pesa
     */
/**
 * Indicate that the payment is M-Pesa
 */
public function mpesa(): static
{
    // Get or create a user for the transaction
    $user = User::inRandomOrder()->first() ?? User::factory()->create();
    
    // Create M-Pesa transaction first
    $mpesaTransaction = MpesaTransaction::factory()
        ->completed()
        ->forUser($user)
        ->create([
            'amount' => $this->faker->randomFloat(2, 100, 50000),
        ]);
    
    return $this->state(fn (array $attributes) => [
        'payment_method' => 'mpesa',
        'reference_number' => $mpesaTransaction->mpesa_receipt_number,
        'phone' => $mpesaTransaction->phone_number,
        'mpesa_transaction_id' => $mpesaTransaction->id,
        'notes' => 'M-Pesa payment via STK Push',
    ]);
}

    /**
     * Indicate that the payment is cash
     */
    public function cash(): static
    {
        return $this->state(fn (array $attributes) => [
            'payment_method' => 'cash',
            'reference_number' => $this->generateReferenceNumber('cash'),
            'phone' => null,
            'mpesa_transaction_id' => null,
            'notes' => 'Cash payment at pickup point',
        ]);
    }

    /**
     * Indicate that the payment is card
     */
    public function card(): static
    {
        return $this->state(fn (array $attributes) => [
            'payment_method' => 'card',
            'reference_number' => $this->generateReferenceNumber('card'),
            'phone' => null,
            'mpesa_transaction_id' => null,
            'notes' => 'Card payment processed via POS',
        ]);
    }

    /**
     * Indicate that the payment is bank transfer
     */
    public function bankTransfer(): static
    {
        return $this->state(fn (array $attributes) => [
            'payment_method' => 'bank_transfer',
            'reference_number' => $this->generateReferenceNumber('bank_transfer'),
            'phone' => null,
            'mpesa_transaction_id' => null,
            'notes' => 'Bank transfer to company account',
        ]);
    }

    /**
     * Indicate that the payment is wallet
     */
    public function wallet(): static
    {
        return $this->state(fn (array $attributes) => [
            'payment_method' => 'wallet',
            'reference_number' => $this->generateReferenceNumber('wallet'),
            'phone' => null,
            'mpesa_transaction_id' => null,
            'notes' => 'Payment from wallet balance',
        ]);
    }

    /**
     * Indicate that the payment is for a specific parcel
     */
    public function forParcel(Parcel $parcel): static
    {
        return $this->state(fn (array $attributes) => [
            'parcel_id' => $parcel->id,
            'amount' => $parcel->total_amount ?? fake()->randomFloat(2, 100, 50000),
        ]);
    }

    /**
     * Indicate that the payment was made by a specific user
     */
    public function paidBy(User $user): static
    {
        return $this->state(fn (array $attributes) => [
            'paid_by' => $user->id,
        ]);
    }

    /**
     * Indicate that the payment has a specific M-Pesa transaction
     */
/**
 * Indicate that the payment has a specific M-Pesa transaction
 */
public function withMpesaTransaction(MpesaTransaction $transaction): static
{
    return $this->state(fn (array $attributes) => [
        'payment_method' => 'mpesa',
        'mpesa_transaction_id' => $transaction->id,
        'reference_number' => $transaction->mpesa_receipt_number ?? $this->generateReferenceNumber('mpesa'),
        'phone' => $transaction->phone_number ?? $this->generateKenyanPhone(),
    ]);
}

    /**
     * Indicate that the payment was made on a specific date
     */
    public function paidOn(string $date): static
    {
        return $this->state(fn (array $attributes) => [
            'payment_date' => $date,
        ]);
    }

    /**
     * Create a specific payment for testing
     */
    public function testPayment(): static
    {
        $testParcel = Parcel::where('parcel_id', 'like', '%TEST%')->first() ?? Parcel::factory()->create();
        $admin = User::where('email', 'admin@karibuparcels.com')->first() ?? User::factory()->admin()->create();
        
        return $this->state(fn (array $attributes) => [
            'parcel_id' => $testParcel->id,
            'amount' => 1500.00,
            'payment_method' => 'mpesa',
            'reference_number' => 'MP' . now()->format('Ymd') . 'TEST01',
            'phone' => '0712345678',
            'status' => 'completed',
            'paid_by' => $admin->id,
            'notes' => 'Test payment for testing purposes',
        ]);
    }
}