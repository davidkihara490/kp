<?php

namespace Database\Factories;

use App\Models\ParcelHandlingAssistantPickUpAndDropOffPointAssignment;
use App\Models\ParcelHandlingAssistant;
use App\Models\PickUpAndDropOffPoint;
use App\Models\Partner;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ParcelHandlingAssistantPickUpAndDropOffPointAssignment>
 */
class ParcelHandlingAssistantPickUpAndDropOffPointAssignmentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $statuses = ['active', 'completed', 'transferred', 'suspended', 'pending'];
        
        // Get or create a PHA
        $pha = ParcelHandlingAssistant::inRandomOrder()->first() ?? ParcelHandlingAssistant::factory()->create();
        
        // Get or create a point
        $point = PickUpAndDropOffPoint::inRandomOrder()->first() ?? PickUpAndDropOffPoint::factory()->create();
        
        // Get the partner (either from PHA, point, or create new)
        $partner = $pha->partner ?? $point->partner ?? Partner::inRandomOrder()->first() ?? Partner::factory()->create();
        
        // Get or create a user for assigned_by
        $assigner = User::whereIn('user_type', ['admin', 'transport', 'pickup-dropoff'])->inRandomOrder()->first() 
            ?? User::factory()->create();
        
        // Generate assignment dates
        $from = fake()->dateTimeBetween('-6 months', 'now');
        $to = fake()->optional(0.4)->dateTimeBetween($from, '+3 months');

        return [
            'parcel_handling_assistant_id' => $pha->id,
            'pick_up_and_drop_off_point_id' => $point->id,
            'partner_id' => $partner->id,
            'assigned_by' => $assigner->id,
            'from' => $from,
            'to' => $to,
            'status' => $this->determineStatus($from, $to),
            'notes' => fake()->optional(0.3)->sentence(),
        ];
    }

    /**
     * Determine status based on dates
     */
    private function determineStatus($from, $to): string
    {
        $now = now();
        
        if ($to && $to < $now) {
            return 'completed';
        }
        
        if ($from > $now) {
            return 'pending';
        }
        
        return 'active';
    }

    /**
     * Indicate that the assignment is active.
     */
    public function active(): static
    {
        return $this->state(fn (array $attributes) => [
            'from' => fake()->dateTimeBetween('-3 months', '-1 day'),
            'to' => null,
            'status' => 'active',
        ]);
    }

    /**
     * Indicate that the assignment is pending (future assignment).
     */
    public function pending(): static
    {
        return $this->state(fn (array $attributes) => [
            'from' => fake()->dateTimeBetween('+1 day', '+1 week'),
            'to' => fake()->dateTimeBetween('+1 week', '+1 month'),
            'status' => 'pending',
        ]);
    }

    /**
     * Indicate that the assignment is completed.
     */
    public function completed(): static
    {
        $from = fake()->dateTimeBetween('-6 months', '-3 months');
        
        return $this->state(fn (array $attributes) => [
            'from' => $from,
            'to' => fake()->dateTimeBetween($from, '-1 day'),
            'status' => 'completed',
        ]);
    }

    /**
     * Indicate that the assignment is for a specific PHA.
     */
    public function forPHA(ParcelHandlingAssistant $pha): static
    {
        return $this->state(fn (array $attributes) => [
            'parcel_handling_assistant_id' => $pha->id,
            'partner_id' => $pha->partner_id ?? $attributes['partner_id'] ?? Partner::factory(),
        ]);
    }

    /**
     * Indicate that the assignment is for a specific point.
     */
    public function forPoint(PickUpAndDropOffPoint $point): static
    {
        return $this->state(fn (array $attributes) => [
            'pick_up_and_drop_off_point_id' => $point->id,
            'partner_id' => $point->partner_id ?? $attributes['partner_id'] ?? Partner::factory(),
        ]);
    }

    /**
     * Indicate that the assignment is for a specific partner.
     */
    public function forPartner(Partner $partner): static
    {
        return $this->state(fn (array $attributes) => [
            'partner_id' => $partner->id,
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
     * Indicate that this is a temporary assignment.
     */
    public function temporary(): static
    {
        return $this->state(fn (array $attributes) => [
            'from' => fake()->dateTimeBetween('-1 week', 'now'),
            'to' => fake()->dateTimeBetween('+1 week', '+2 weeks'),
            'status' => 'active',
        ]);
    }

    /**
     * Indicate that the PHA was transferred to another point.
     */
    public function transferred(): static
    {
        $from = fake()->dateTimeBetween('-4 months', '-2 months');
        
        return $this->state(fn (array $attributes) => [
            'from' => $from,
            'to' => fake()->dateTimeBetween($from, '-1 month'),
            'status' => 'transferred',
            'notes' => 'Transferred to another location: ' . fake()->randomElement(['CBD Branch', 'Westlands', 'Industrial Area', 'Airport']),
        ]);
    }

    /**
     * Indicate that the assignment was suspended.
     */
    public function suspended(): static
    {
        return $this->state(fn (array $attributes) => [
            'from' => fake()->dateTimeBetween('-3 months', '-2 months'),
            'to' => fake()->dateTimeBetween('-1 month', 'now'),
            'status' => 'suspended',
            'notes' => 'Suspended due to: ' . fake()->randomElement(['disciplinary issues', 'leave of absence', 'training', 'medical reasons']),
        ]);
    }

    /**
     * Create the specific warehouse assignment.
     */
    public function warehouseAssignment(): static
    {
        $warehousePoint = PickUpAndDropOffPoint::where('code', 'WH-001')->first();
        $warehousePHA = ParcelHandlingAssistant::where('email', 'warehouse.pha@karibuparcels.com')->first();
        $admin = User::where('email', 'admin@karibuparcels.com')->first();
        
        return $this->state(fn (array $attributes) => [
            'parcel_handling_assistant_id' => $warehousePHA?->id ?? ParcelHandlingAssistant::factory(),
            'pick_up_and_drop_off_point_id' => $warehousePoint?->id ?? PickUpAndDropOffPoint::factory(),
            'partner_id' => $warehousePoint?->partner_id ?? $warehousePHA?->partner_id ?? Partner::factory(),
            'assigned_by' => $admin?->id ?? User::factory(),
            'from' => now()->subMonths(3),
            'to' => null,
            'status' => 'active',
            'notes' => 'Permanent warehouse assignment - Central Sorting Facility',
        ]);
    }

    /**
     * Create assignment for a specific shift.
     */
    public function withShift(string $shift): static
    {
        $shifts = [
            'morning' => [
                'from' => '06:00:00',
                'to' => '14:00:00',
                'notes' => 'Morning shift'
            ],
            'afternoon' => [
                'from' => '14:00:00',
                'to' => '22:00:00',
                'notes' => 'Afternoon shift'
            ],
            'night' => [
                'from' => '22:00:00',
                'to' => '06:00:00',
                'notes' => 'Night shift'
            ],
        ];

        $shiftData = $shifts[$shift] ?? $shifts['morning'];
        
        return $this->state(fn (array $attributes) => [
            'from' => now()->setTimeFromTimeString($shiftData['from'])->subDays(rand(0, 30)),
            'to' => null,
            'status' => 'active',
            'notes' => $shiftData['notes'],
        ]);
    }
}