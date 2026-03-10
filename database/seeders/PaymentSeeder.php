<?php

namespace Database\Seeders;

use App\Models\Payment;
use App\Models\Parcel;
use App\Models\User;
use Illuminate\Database\Seeder;

class PaymentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get parcels that need payments
        $parcels = Parcel::where('payment_status', '!=', 'paid')->get();

        if ($parcels->isEmpty()) {
            $parcels = Parcel::inRandomOrder()->limit(20)->get();
        }

        $users = User::whereIn('user_type', ['admin', 'partner'])->get();
        if ($users->isEmpty()) {
            $users = User::all();
        }

        $this->command->info('Creating payments...');

        // Create completed payments (70% of parcels)
        $completedCount = min(floor($parcels->count() * 0.7), 15);
        foreach ($parcels->take($completedCount) as $parcel) {
            $user = $users->random();

            Payment::factory()
                ->completed()
                ->forParcel($parcel)
                ->paidBy($user)
                ->create();

            // Update parcel payment status
            $parcel->update([
                'payment_status' => 'paid',
                'paid_at' => now(),
            ]);
        }

        // Create pending payments (20% of parcels)
        $pendingCount = min(floor($parcels->count() * 0.2), 8);
        $pendingParcels = $parcels->skip($completedCount)->take($pendingCount);

        foreach ($pendingParcels as $parcel) {
            $user = $users->random();

            Payment::factory()
                ->pending()
                ->forParcel($parcel)
                ->paidBy($user)
                ->create();
        }

        // Create M-Pesa payments
        $this->command->info('Creating M-Pesa payments...');
        for ($i = 0; $i < 100; $i++) {
            $parcel = $parcels->random();
            $user = $users->random();

            Payment::factory()
                ->mpesa()
                ->completed()
                ->forParcel($parcel)
                ->paidBy($user)
                ->create();

            $parcel->update([
                'payment_status' => 'paid',
                'paid_at' => now(),
            ]);
        }

        // Create cash payments
        $this->command->info('Creating cash payments...');
        for ($i = 0; $i < 8; $i++) {
            $parcel = $parcels->random();
            $user = $users->random();

            Payment::factory()
                ->cash()
                ->completed()
                ->forParcel($parcel)
                ->paidBy($user)
                ->create();

            $parcel->update([
                'payment_status' => 'paid',
                'paid_at' => now(),
            ]);
        }

        // Create failed payments
        $this->command->info('Creating failed payments...');
        for ($i = 0; $i < 5; $i++) {
            $parcel = $parcels->random();
            $user = $users->random();

            Payment::factory()
                ->failed()
                ->forParcel($parcel)
                ->paidBy($user)
                ->create();
        }

        // Create refunded payments
        $this->command->info('Creating refunded payments...');
        for ($i = 0; $i < 3; $i++) {
            $parcel = $parcels->random();
            $user = $users->random();

            Payment::factory()
                ->refunded()
                ->forParcel($parcel)
                ->paidBy($user)
                ->create();

            $parcel->update([
                'payment_status' => 'refunded',
            ]);
        }

        // Create test payment
        $testParcel = Parcel::where('parcel_id', 'like', '%TEST%')->first();
        if ($testParcel) {
            Payment::factory()
                ->testPayment()
                ->create();

            $this->command->info('Test payment created');
        }

        $this->command->info('Payments seeded successfully!');
    }


    // // Create a completed M-Pesa payment
    // Payment::factory()->mpesa()->completed()->create();

    // // Create a cash payment for a specific parcel
    // Payment::factory()
    //     ->cash()
    //     ->completed()
    //     ->forParcel($parcel)
    //     ->paidBy($user)
    //     ->create();

    // // Create a failed payment
    // Payment::factory()->failed()->create();

    // // Create multiple payments
    // Payment::factory()->count(5)->mpesa()->completed()->create();

}
