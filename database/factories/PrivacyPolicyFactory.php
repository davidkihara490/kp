<?php

namespace Database\Factories;

use App\Models\PrivacyPolicy;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PrivacyPolicy>
 */
class PrivacyPolicyFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'content' => $this->faker->paragraphs(15, true),
            'title' => 'Privacy Policy ' . $this->faker->year(),
            'version' => $this->faker->numerify('v#.#.#'),
            'is_active' => false,
            'locale' => 'en',
            'created_by' => 1,
            'updated_by' => 1,
            'effective_date' => $this->faker->optional()->dateTimeBetween('-1 year', '+1 year'),
            'expiry_date' => $this->faker->optional()->dateTimeBetween('+1 year', '+5 years'),
            'requires_consent' => true,
            'requires_acceptance' => true,
            'data_categories' => $this->faker->randomElements(
                ['Personal Information', 'Payment Data', 'Location Data', 'Device Info', 'Browsing History'],
                $this->faker->numberBetween(2, 4)
            ),
            'processing_purposes' => $this->faker->randomElements(
                ['Order Processing', 'Marketing', 'Analytics', 'Customer Support', 'Fraud Prevention'],
                $this->faker->numberBetween(2, 3)
            ),
            'contact_email' => $this->faker->companyEmail(),
            'data_controller' => $this->faker->company(),
        ];
    }

    /**
     * Indicate that the policy is active.
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

    /**
     * GDPR compliant policy.
     */
    public function gdpr(): static
    {
        return $this->state(fn (array $attributes) => [
            'requires_consent' => true,
            'data_categories' => [
                'Personal Identification Data',
                'Contact Information',
                'Financial Data',
                'Technical Data',
                'Usage Data'
            ],
            'processing_purposes' => [
                'Contract Performance',
                'Legal Obligation',
                'Legitimate Interests',
                'Consent Based'
            ],
            'contact_email' => 'dpo@' . $this->faker->domainName(),
        ]);
    }
}