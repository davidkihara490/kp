<?php

namespace Database\Factories;

use App\Models\ParcelStatus;
use App\Models\Parcel;
use App\Models\User;
use App\Models\Driver;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ParcelStatus>
 */
class ParcelStatusFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $parcel = Parcel::inRandomOrder()->first() ?? Parcel::factory()->create();
        
        $statuses = [
            'pending', 'accepted', 'assigned', 'in_transit', 'warehouse', 
            'arrived_at_destination', 'picked', 'delivered', 'failed', 'returned'
        ];
        
        $changedByTypes = ['admin', 'partner', 'driver', 'system'];
        $changedByType = fake()->randomElement($changedByTypes);
        
        $changedBy = null;
        $driverId = null;
        
        if ($changedByType === 'driver') {
            $driverId = Driver::inRandomOrder()->first()?->id ?? Driver::factory()->create()->id;
        } else {
            $changedBy = User::inRandomOrder()->first()?->id ?? User::factory()->create()->id;
        }

        $otp = fake()->optional(0.3)->numberBetween(100000, 999999);
        $otpVerified = $otp ? fake()->boolean(70) : false;

        return [
            'parcel_id' => $parcel->id,
            'status' => fake()->randomElement($statuses),
            'changed_by' => $changedBy,
            'changed_by_type' => $changedByType,
            'notes' => fake()->optional(0.7)->sentence(),
            'otp' => $otp,
            'otp_verified' => $otpVerified,
            'otp_expires_at' => $otp ? now()->addHours(12) : null,
            'driver_id' => $driverId,
        ];
    }

    /**
     * Indicate a specific status
     */
    public function withStatus(string $status): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => $status,
        ]);
    }

    /**
     * Indicate that this is the first status (pending)
     */
    public function initial(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'pending',
            'changed_by_type' => 'system',
            'notes' => 'Parcel created',
            'otp' => null,
            'otp_verified' => false,
        ]);
    }

    /**
     * Indicate that this is a delivery status with OTP
     */
    public function delivery(): static
    {
        $otp = fake()->numberBetween(100000, 999999);
        
        return $this->state(fn (array $attributes) => [
            'status' => 'delivered',
            'changed_by_type' => 'driver',
            'otp' => $otp,
            'otp_verified' => true,
            'notes' => 'Parcel delivered successfully',
        ]);
    }

    /**
     * Indicate that the status was changed by a driver
     */
    public function byDriver(Driver $driver): static
    {
        return $this->state(fn (array $attributes) => [
            'changed_by_type' => 'driver',
            'changed_by' => null,
            'driver_id' => $driver->id,
        ]);
    }

    /**
     * Indicate that the status was changed by a user
     */
    public function byUser(User $user): static
    {
        return $this->state(fn (array $attributes) => [
            'changed_by_type' => $user->user_type ?? 'admin',
            'changed_by' => $user->id,
            'driver_id' => null,
        ]);
    }

    /**
     * Indicate that this is a failed delivery
     */
    public function failed(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'failed',
            'notes' => fake()->randomElement([
                'Recipient not available',
                'Wrong address',
                'Recipient refused package',
                'Failed to contact recipient',
                'Address not found'
            ]),
        ]);
    }

    /**
     * Indicate OTP verified
     */
    public function otpVerified(): static
    {
        return $this->state(fn (array $attributes) => [
            'otp_verified' => true,
            'otp' => fake()->numberBetween(100000, 999999),
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
}