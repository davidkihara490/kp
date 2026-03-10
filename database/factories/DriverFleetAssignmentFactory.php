<?php

namespace Database\Factories;

use App\Models\DriverFleetAssignment;
use App\Models\Fleet;
use App\Models\Driver;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\DriverFleetAssignment>
 */
class DriverFleetAssignmentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $statuses = ['active', 'completed', 'cancelled', 'suspended'];
        
        // Get or create a fleet
        $fleet = Fleet::inRandomOrder()->first() ?? Fleet::factory()->create();
        
        // Get or create a driver
        $driver = Driver::inRandomOrder()->first() ?? Driver::factory()->create();
        
        // Get or create a user for assigned_by
        $assigner = User::inRandomOrder()->first() ?? User::factory()->create();
        
        // Generate assignment dates
        $from = fake()->dateTimeBetween('-3 months', 'now');
        $to = fake()->optional(0.7)->dateTimeBetween($from, '+2 months');

        return [
            'fleet_id' => $fleet->id,
            'driver_id' => $driver->id,
            'from' => $from,
            'to' => $to,
            'status' => $to ? 'completed' : fake()->randomElement(['active', 'active', 'active', 'suspended']),
            'assigned_by' => $assigner->id,
        ];
    }

    /**
     * Indicate that the assignment is active.
     */
    public function active(): static
    {
        return $this->state(fn (array $attributes) => [
            'from' => fake()->dateTimeBetween('-1 month', 'now'),
            'to' => null,
            'status' => 'active',
        ]);
    }

    /**
     * Indicate that the assignment is completed.
     */
    public function completed(): static
    {
        $from = fake()->dateTimeBetween('-6 months', '-2 months');
        
        return $this->state(fn (array $attributes) => [
            'from' => $from,
            'to' => fake()->dateTimeBetween($from, '-1 day'),
            'status' => 'completed',
        ]);
    }

    /**
     * Indicate that the assignment is for a specific fleet.
     */
    public function forFleet(Fleet $fleet): static
    {
        return $this->state(fn (array $attributes) => [
            'fleet_id' => $fleet->id,
        ]);
    }

    /**
     * Indicate that the assignment is for a specific driver.
     */
    public function forDriver(Driver $driver): static
    {
        return $this->state(fn (array $attributes) => [
            'driver_id' => $driver->id,
        ]);
    }

    /**
     * Indicate that the assignment was made by a specific user.
     */
    public function assignedBy(User $user): static
    {
        return $this->state(fn (array $attributes) => [
            'assigned_by' => $user->id,
        ]);
    }

    /**
     * Indicate that this is a long-term assignment.
     */
    public function longTerm(): static
    {
        return $this->state(fn (array $attributes) => [
            'from' => fake()->dateTimeBetween('-1 year', '-6 months'),
            'to' => fake()->dateTimeBetween('+3 months', '+1 year'),
            'status' => 'active',
        ]);
    }

    /**
     * Indicate that this is a short-term assignment.
     */
    public function shortTerm(): static
    {
        return $this->state(fn (array $attributes) => [
            'from' => fake()->dateTimeBetween('-1 week', 'now'),
            'to' => fake()->dateTimeBetween('+1 day', '+1 week'),
            'status' => 'active',
        ]);
    }

    /**
     * Indicate that the assignment was suspended.
     */
    public function suspended(): static
    {
        return $this->state(fn (array $attributes) => [
            'from' => fake()->dateTimeBetween('-2 months', '-1 month'),
            'to' => fake()->dateTimeBetween('-1 month', 'now'),
            'status' => 'suspended',
        ]);
    }

    /**
     * Create a specific assignment for the test driver and fleet.
     */
    public function testAssignment(): static
    {
        $testDriver = Driver::where('email', 'driver_test@karibuparcels.com')->first();
        $testFleet = Fleet::where('registration_number', 'KCE 123A')->first();
        $admin = User::where('email', 'admin@karibuparcels.com')->first();
        
        return $this->state(fn (array $attributes) => [
            'fleet_id' => $testFleet?->id ?? Fleet::factory(),
            'driver_id' => $testDriver?->id ?? Driver::factory(),
            'from' => now()->subDays(30),
            'to' => null,
            'status' => 'active',
            'assigned_by' => $admin?->id ?? User::factory(),
        ]);
    }
}