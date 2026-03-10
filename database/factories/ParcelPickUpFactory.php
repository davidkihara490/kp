<?php

namespace Database\Factories;

use App\Models\ParcelPickUp;
use App\Models\Parcel;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ParcelPickUp>
 */
class ParcelPickUpFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $parcel = Parcel::inRandomOrder()->first() ?? Parcel::factory()->create();
        
        $kenyanFirstNames = [
            'John', 'James', 'Peter', 'David', 'Daniel', 'Joseph', 'Samuel', 'Patrick',
            'Mary', 'Jane', 'Grace', 'Mercy', 'Faith', 'Lucy', 'Agnes', 'Margaret'
        ];

        $kenyanLastNames = [
            'Ochieng', 'Okoth', 'Omondi', 'Otieno', 'Kipchumba', 'Kipkemoi', 'Kiprotich',
            'Maina', 'Kimani', 'Kariuki', 'Njenga', 'Wainaina', 'Macharia', 'Ndegwa'
        ];

        $pickupPersonType = fake()->randomElement(['owner', 'other']);
        
        $firstName = fake()->randomElement($kenyanFirstNames);
        $lastName = fake()->randomElement($kenyanLastNames);
        
        $relationships = ['Spouse', 'Sibling', 'Parent', 'Child', 'Friend', 'Colleague', 'Neighbor'];
        
        $verifiedBy = User::inRandomOrder()->first() ?? User::factory()->create();
        $verifiedAt = fake()->optional(0.8)->dateTimeBetween('-2 days', 'now');

        return [
            'parcel_id' => $parcel->id,
            'pickup_person_type' => $pickupPersonType,
            'pickup_person_name' => $firstName . ' ' . $lastName,
            'pickup_person_phone' => $this->generateKenyanPhone(),
            'pickup_person_id_number' => $pickupPersonType === 'other' ? fake()->optional(0.7)->numberBetween(10000000, 39999999) : null,
            'pickup_person_relationship' => $pickupPersonType === 'other' ? fake()->randomElement($relationships) : null,
            'pickup_reason' => $pickupPersonType === 'other' ? fake()->optional(0.5)->sentence() : null,
            'verified_by' => $verifiedAt ? $verifiedBy->id : null,
            'verified_at' => $verifiedAt,
            'notes' => fake()->optional(0.3)->sentence(),
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
     * Indicate that the pickup is by the owner
     */
    public function byOwner(): static
    {
        return $this->state(fn (array $attributes) => [
            'pickup_person_type' => 'owner',
            'pickup_person_id_number' => null,
            'pickup_person_relationship' => null,
            'pickup_reason' => null,
        ]);
    }

    /**
     * Indicate that the pickup is by another person
     */
    public function byOther(): static
    {
        $relationships = ['Spouse', 'Sibling', 'Parent', 'Child', 'Friend', 'Colleague', 'Neighbor'];
        
        return $this->state(fn (array $attributes) => [
            'pickup_person_type' => 'other',
            'pickup_person_id_number' => fake()->numberBetween(10000000, 39999999),
            'pickup_person_relationship' => fake()->randomElement($relationships),
            'pickup_reason' => fake()->sentence(),
        ]);
    }

    /**
     * Indicate that the pickup is verified
     */
    public function verified(): static
    {
        $verifier = User::inRandomOrder()->first() ?? User::factory()->create();
        
        return $this->state(fn (array $attributes) => [
            'verified_by' => $verifier->id,
            'verified_at' => now()->subHours(rand(1, 48)),
        ]);
    }

    /**
     * Indicate that the pickup is unverified
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'verified_by' => null,
            'verified_at' => null,
        ]);
    }

    /**
     * For a specific parcel
     */
    public function forParcel(Parcel $parcel): static
    {
        return $this->state(fn (array $attributes) => [
            'parcel_id' => $parcel->id,
        ]);
    }

    /**
     * Verified by a specific user
     */
    public function verifiedBy(User $user): static
    {
        return $this->state(fn (array $attributes) => [
            'verified_by' => $user->id,
            'verified_at' => now(),
        ]);
    }

    /**
     * Create a pickup for a specific person
     */
    public function forPerson(string $name, string $phone): static
    {
        return $this->state(fn (array $attributes) => [
            'pickup_person_name' => $name,
            'pickup_person_phone' => $phone,
        ]);
    }

    /**
     * Create a pickup with ID verification
     */
    public function withIdVerification(): static
    {
        return $this->state(fn (array $attributes) => [
            'pickup_person_id_number' => fake()->numberBetween(10000000, 39999999),
            'notes' => 'ID verified at pickup',
        ]);
    }
}