<?php

namespace Database\Factories;

use App\Models\Parcel;
use App\Models\User;
use App\Models\Partner;
use App\Models\PickUpAndDropOffPoint;
use App\Models\Town;
use App\Models\Driver;
use App\Models\ParcelHandlingAssistant;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Parcel>
 */
class ParcelFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $kenyanFirstNames = [
            'John', 'James', 'Peter', 'David', 'Daniel', 'Joseph', 'Samuel', 'Patrick',
            'Mary', 'Jane', 'Grace', 'Mercy', 'Faith', 'Lucy', 'Agnes', 'Margaret',
            'Kenneth', 'Brian', 'Kevin', 'Collins', 'Dennis', 'Evans', 'Felix', 'George'
        ];

        $kenyanLastNames = [
            'Ochieng', 'Okoth', 'Omondi', 'Otieno', 'Kipchumba', 'Kipkemoi', 'Kiprotich',
            'Maina', 'Kimani', 'Kariuki', 'Njenga', 'Wainaina', 'Macharia', 'Ndegwa',
            'Kamau', 'Odhiambo', 'Mwangi', 'Njoroge', 'Waweru', 'Mutua', 'Kilonzo'
        ];

        $parcelTypes = ['document', 'package', 'envelope', 'box', 'pallet', 'other'];
        $packageTypes = ['regular', 'fragile', 'perishable', 'valuable', 'hazardous', 'oversized'];
        $bookingTypes = ['instant', 'scheduled', 'bulk'];
        $paymentMethods = ['cash', 'mpesa', 'card', 'bank_transfer', 'wallet'];
        $paymentStatuses = ['pending', 'paid', 'failed', 'refunded', 'partially_paid'];
        $statuses = [
            'pending', 'accepted', 'assigned', 'in_transit', 'warehouse', 
            'arrived_at_destination', 'picked', 'delivered', 'failed', 'returned'
        ];

        $senderFirstName = fake()->randomElement($kenyanFirstNames);
        $senderLastName = fake()->randomElement($kenyanLastNames);
        
        $receiverFirstName = fake()->randomElement($kenyanFirstNames);
        $receiverLastName = fake()->randomElement($kenyanLastNames);

        $senderTown = Town::inRandomOrder()->first() ?? Town::factory()->create();
        $receiverTown = Town::inRandomOrder()->first() ?? Town::factory()->create();

        $weight = fake()->randomFloat(2, 0.1, 50);
        $length = fake()->randomFloat(2, 5, 100);
        $width = fake()->randomFloat(2, 5, 80);
        $height = fake()->randomFloat(2, 5, 60);
        
        $basePrice = fake()->randomFloat(2, 100, 5000);
        $weightCharge = $weight * fake()->randomFloat(2, 10, 50);
        $distanceCharge = fake()->randomFloat(2, 50, 2000);
        $specialHandlingCharge = fake()->boolean(20) ? fake()->randomFloat(2, 100, 1000) : 0;
        $insuranceCharge = fake()->boolean(30) ? fake()->randomFloat(2, 50, 500) : 0;
        $taxAmount = ($basePrice + $weightCharge + $distanceCharge + $specialHandlingCharge + $insuranceCharge) * 0.16;
        $discountAmount = fake()->boolean(10) ? fake()->randomFloat(2, 50, 500) : 0;
        
        $totalAmount = $basePrice + $weightCharge + $distanceCharge + $specialHandlingCharge + $insuranceCharge + $taxAmount - $discountAmount;

        return [
            'parcel_id' => $this->generateParcelNumber(),
            'customer_id' => User::inRandomOrder()->first()?->id ?? User::factory(),
            'booking_type' => fake()->randomElement($bookingTypes),

            // Sender Information
            'sender_name' => $senderFirstName . ' ' . $senderLastName,
            'sender_phone' => $this->generateKenyanPhone(),
            'sender_email' => strtolower($senderFirstName . '.' . $senderLastName . rand(1, 99) . '@example.com'),
            'sender_town_id' => $senderTown->id,
            'sender_notes' => fake()->optional(0.3)->sentence(),

            // Receiver Information
            'receiver_name' => $receiverFirstName . ' ' . $receiverLastName,
            'receiver_phone' => $this->generateKenyanPhone(),
            'receiver_email' => strtolower($receiverFirstName . '.' . $receiverLastName . rand(1, 99) . '@example.com'),
            'receiver_address' => fake()->streetAddress() . ', ' . $receiverTown->name,
            'receiver_town_id' => $receiverTown->id,
            'receiver_notes' => fake()->optional(0.3)->sentence(),

            // Pickup Information
            'pha_id' => ParcelHandlingAssistant::inRandomOrder()->first()?->id ?? ParcelHandlingAssistant::factory(),
            'sender_partner_id' => Partner::inRandomOrder()->first()?->id ?? Partner::factory(),
            'sender_pick_up_drop_off_point_id' => PickUpAndDropOffPoint::inRandomOrder()->first()?->id ?? PickUpAndDropOffPoint::factory(),
            'date' => fake()->dateTimeBetween('-1 month', '+1 week'),

            // Parcel Details
            'parcel_type' => fake()->randomElement($parcelTypes),
            'package_type' => fake()->randomElement($packageTypes),
            'weight' => $weight,
            'length' => $length,
            'width' => $width,
            'height' => $height,
            'dimension_unit' => 'cm',
            'weight_unit' => 'kg',
            'declared_value' => fake()->randomFloat(2, 500, 100000),
            'insurance_amount' => fake()->optional(0.3)->randomFloat(2, 1000, 50000) ?? 0,
            'insurance_required' => fake()->boolean(30),
            'content_description' => fake()->sentence(6),
            'special_instructions' => fake()->optional(0.2)->sentence(),

            // Delivery Information
            'delivery_partner_id' => Partner::inRandomOrder()->first()?->id ?? Partner::factory(),
            'delivery_pick_up_drop_off_point_id' => PickUpAndDropOffPoint::inRandomOrder()->first()?->id ?? PickUpAndDropOffPoint::factory(),

            // Pricing
            'base_price' => $basePrice,
            'weight_charge' => $weightCharge,
            'distance_charge' => $distanceCharge,
            'special_handling_charge' => $specialHandlingCharge,
            'insurance_charge' => $insuranceCharge,
            'tax_amount' => $taxAmount,
            'discount_amount' => $discountAmount,
            'total_amount' => $totalAmount,
            'payment_method' => fake()->randomElement($paymentMethods),
            'payment_status' => fake()->randomElement($paymentStatuses),
            'paid_at' => fake()->optional(0.7)->dateTimeBetween('-1 month', 'now'),

            'payout' => $totalAmount * 0.8,
            'transport_partner_id' => Partner::where('partner_type', 'transport')->inRandomOrder()->first()?->id ?? Partner::factory(),
            'driver_id' => Driver::inRandomOrder()->first()?->id ?? Driver::factory(),

            'current_status' => fake()->randomElement($statuses),
            'booking_source' => fake()->randomElement(['web', 'mobile', 'walk_in', 'api']),
        ];
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
     * Generate a unique parcel number
     */
    private function generateParcelNumber(): string
    {
        static $usedNumbers = [];
        
        $prefix = 'KP';
        $date = date('Ymd');
        $random = strtoupper(substr(md5(uniqid()), 0, 6));
        $number = $prefix . $date . $random;
        
        // Ensure uniqueness
        $attempts = 0;
        while (in_array($number, $usedNumbers) && $attempts < 100) {
            $random = strtoupper(substr(md5(uniqid() . rand()), 0, 6));
            $number = $prefix . $date . $random;
            $attempts++;
        }
        
        $usedNumbers[] = $number;
        return $number;
    }

    /**
     * Indicate that the parcel is pending
     */
    public function pending(): static
    {
        return $this->state(fn (array $attributes) => [
            'current_status' => 'pending',
            'payment_status' => 'pending',
        ]);
    }

    /**
     * Indicate that the parcel is accepted
     */
    public function accepted(): static
    {
        return $this->state(fn (array $attributes) => [
            'current_status' => 'accepted',
        ]);
    }

    /**
     * Indicate that the parcel is assigned to driver
     */
    public function assigned(): static
    {
        return $this->state(fn (array $attributes) => [
            'current_status' => 'assigned',
            'driver_id' => Driver::factory(),
            'transport_partner_id' => Partner::factory(),
        ]);
    }

    /**
     * Indicate that the parcel is in transit
     */
    public function inTransit(): static
    {
        return $this->state(fn (array $attributes) => [
            'current_status' => 'in_transit',
            'driver_id' => Driver::factory(),
            'transport_partner_id' => Partner::factory(),
        ]);
    }

    /**
     * Indicate that the parcel is at warehouse
     */
    public function atWarehouse(): static
    {
        return $this->state(fn (array $attributes) => [
            'current_status' => 'warehouse',
            'driver_id' => null,
        ]);
    }

    /**
     * Indicate that the parcel is delivered
     */
    public function delivered(): static
    {
        return $this->state(fn (array $attributes) => [
            'current_status' => 'delivered',
            'payment_status' => 'paid',
            'paid_at' => now()->subDays(rand(1, 5)),
        ]);
    }

    /**
     * Indicate that the parcel is paid
     */
    public function paid(): static
    {
        return $this->state(fn (array $attributes) => [
            'payment_status' => 'paid',
            'paid_at' => fake()->dateTimeBetween('-1 month', 'now'),
        ]);
    }

    /**
     * Indicate that the parcel requires insurance
     */
    public function insured(): static
    {
        return $this->state(fn (array $attributes) => [
            'insurance_required' => true,
            'insurance_amount' => fake()->randomFloat(2, 5000, 50000),
            'insurance_charge' => fake()->randomFloat(2, 100, 1000),
        ]);
    }

    /**
     * Indicate that the parcel is fragile
     */
    public function fragile(): static
    {
        return $this->state(fn (array $attributes) => [
            'package_type' => 'fragile',
            'special_instructions' => 'Handle with care - Fragile item',
            'special_handling_charge' => fake()->randomFloat(2, 100, 500),
        ]);
    }

    /**
     * Indicate that the parcel is perishable
     */
    public function perishable(): static
    {
        return $this->state(fn (array $attributes) => [
            'package_type' => 'perishable',
            'special_instructions' => 'Perishable goods - Deliver promptly',
            'special_handling_charge' => fake()->randomFloat(2, 200, 800),
        ]);
    }

    /**
     * Indicate that the parcel is valuable
     */
    public function valuable(): static
    {
        return $this->state(fn (array $attributes) => [
            'package_type' => 'valuable',
            'declared_value' => fake()->randomFloat(2, 50000, 500000),
            'insurance_required' => true,
            'special_instructions' => 'High value item - Signature required',
            'special_handling_charge' => fake()->randomFloat(2, 500, 2000),
        ]);
    }

    /**
     * Set specific sender and receiver towns
     */
    public function betweenTowns(string $senderTown, string $receiverTown): static
    {
        return $this->state(function (array $attributes) use ($senderTown, $receiverTown) {
            $sender = Town::where('name', $senderTown)->first() ?? Town::factory()->create(['name' => $senderTown]);
            $receiver = Town::where('name', $receiverTown)->first() ?? Town::factory()->create(['name' => $receiverTown]);
            
            return [
                'sender_town_id' => $sender->id,
                'receiver_town_id' => $receiver->id,
            ];
        });
    }

    /**
     * Create a parcel for a specific customer
     */
    public function forCustomer(User $customer): static
    {
        return $this->state(fn (array $attributes) => [
            'customer_id' => $customer->id,
        ]);
    }

    /**
     * Create a parcel for a specific driver
     */
    public function forDriver(Driver $driver): static
    {
        return $this->state(fn (array $attributes) => [
            'driver_id' => $driver->id,
            'transport_partner_id' => $driver->partner_id,
            'current_status' => 'assigned',
        ]);
    }

    /**
     * Create a parcel for a specific transport partner
     */
    public function forTransportPartner(Partner $partner): static
    {
        return $this->state(fn (array $attributes) => [
            'transport_partner_id' => $partner->id,
        ]);
    }

    /**
     * Create a parcel picked up from a specific point
     */
    public function pickedFrom(PickUpAndDropOffPoint $point): static
    {
        return $this->state(fn (array $attributes) => [
            'sender_pick_up_drop_off_point_id' => $point->id,
            'sender_partner_id' => $point->partner_id,
        ]);
    }

    /**
     * Create a parcel to be delivered to a specific point
     */
    public function deliverTo(PickUpAndDropOffPoint $point): static
    {
        return $this->state(fn (array $attributes) => [
            'delivery_pick_up_drop_off_point_id' => $point->id,
            'delivery_partner_id' => $point->partner_id,
        ]);
    }
}