<?php

namespace Database\Factories;

use App\Models\TermsAndCondition;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\TermsAndCondition>
 */
class TermsAndConditionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'content' => $this->faker->paragraphs(10, true),
            'title' => 'Terms of Service ' . $this->faker->year(),
            'version' => $this->faker->numerify('v#.#.#'),
            'is_active' => false,
            'locale' => 'en',
            'created_by' => 1,
            'updated_by' => 1,
            'effective_date' => $this->faker->optional()->dateTimeBetween('-1 year', '+1 year'),
            'expiry_date' => $this->faker->optional()->dateTimeBetween('+1 year', '+5 years'),
            'requires_acceptance' => true,
        ];
    }

    /**
     * Indicate that the terms are active.
     */
    public function active(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => true,
            'effective_date' => now(),
        ]);
    }

    /**
     * Indicate a specific locale.
     */
    public function locale(string $locale): static
    {
        return $this->state(fn (array $attributes) => [
            'locale' => $locale,
        ]);
    }
}