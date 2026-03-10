<?php

namespace Database\Factories;

use App\Models\Driver;
use App\Models\Partner;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Driver>
 */
class DriverFactory extends Factory
{
    public function definition(): array
    {
        $kenyanFirstNames = [
            'John', 'James', 'Peter', 'David', 'Daniel', 'Joseph', 'Samuel', 'Patrick',
            'Kenneth', 'Brian', 'Kevin', 'Collins', 'Dennis', 'Evans', 'Felix', 'George',
            'Simon', 'Michael', 'Stephen', 'Paul', 'Philip', 'Anthony', 'Robert', 'Charles',
            'Mary', 'Jane', 'Grace', 'Mercy', 'Faith', 'Lucy', 'Agnes', 'Margaret',
            'Caroline', 'Catherine', 'Dorcas', 'Edith', 'Florence', 'Gladys', 'Hellen'
        ];
        
        $kenyanSecondNames = [
            'Kamau', 'Odhiambo', 'Otieno', 'Omondi', 'Kipchoge', 'Kiprop', 'Kiptoo',
            'Njoroge', 'Waweru', 'Mwangi', 'Maina', 'Kimani', 'Kariuki', 'Njenga',
            'Ochieng', 'Okoth', 'Kipchumba', 'Kipkemoi', 'Kiprotich', 'Rotich', 'Kosgei'
        ];
        
        $kenyanLastNames = [
            'Ochieng', 'Okoth', 'Omondi', 'Otieno', 'Kipchumba', 'Kipkemoi', 'Kiprotich',
            'Maina', 'Kimani', 'Kariuki', 'Njenga', 'Wainaina', 'Macharia', 'Ndegwa',
            'Odhiambo', 'Onyango', 'Awuor', 'Achieng', 'Anyango', 'Akinyi', 'Atieno'
        ];

        $genders = ['male', 'female'];
        $licenseClasses = ['A', 'B', 'C', 'D', 'E', 'A1', 'B1', 'C1', 'D1', 'CE', 'DE'];
        $statuses = ['active', 'inactive', 'pending', 'suspended'];
        $banks = ['KCB', 'Equity Bank', 'Cooperative Bank', 'NCBA', 'Absa Kenya', 'Stanbic', 'DTB', 'Family Bank'];
        
        $firstName = fake()->randomElement($kenyanFirstNames);
        $secondName = fake()->optional(0.6)->randomElement($kenyanSecondNames);
        $lastName = fake()->randomElement($kenyanLastNames);
        
        $issueDate = fake()->optional(0.9)->dateTimeBetween('-15 years', '-1 year');
        $expiryDate = $issueDate ? fake()->optional(0.9)->dateTimeBetween($issueDate, '+3 years') : null;

        return [
            'user_id' => User::factory(),
            'partner_id' => Partner::factory(),
            'first_name' => $firstName,
            'second_name' => $secondName,
            'last_name' => $lastName,
            'email' => strtolower($firstName . '.' . ($secondName ?? $lastName) . rand(1, 999) . '@driver.co.ke'),
            'phone_number' => $this->generateKenyanPhone(),
            'alternate_phone_number' => fake()->optional(0.5)->phoneNumber(),
            'gender' => fake()->randomElement($genders),
            'id_number' => $this->generateKenyanId(),
            'driving_license_number' => $this->generateKenyanLicenseNumber(),
            'driving_license_issue_date' => $issueDate,
            'driving_license_expiry_date' => $expiryDate,
            'license_class' => fake()->randomElement($licenseClasses),
            'emergency_contact_name' => fake()->randomElement($kenyanFirstNames) . ' ' . fake()->randomElement($kenyanLastNames),
            'emergency_contact_phone' => $this->generateKenyanPhone(),
            'emergency_contact_relationship' => fake()->randomElement(['Spouse', 'Parent', 'Sibling', 'Friend', 'Relative']),
            'bank_name' => fake()->optional(0.8)->randomElement($banks),
            'bank_account_number' => fake()->optional(0.8)->regexify('[0-9]{10,14}'),
            'bank_account_name' => fake()->optional(0.8)->name(),
            'is_available' => fake()->boolean(80),
            'notes' => fake()->optional(0.3)->sentence(),
            'status' => fake()->randomElement($statuses),
        ];
    }

    private function generateKenyanPhone(): string
    {
        $prefixes = ['0700', '0701', '0710', '0711', '0712', '0713', '0714', '0715', '0716', '0717', '0718', '0719',
                    '0720', '0721', '0722', '0723', '0724', '0725', '0726', '0727', '0728', '0729',
                    '0730', '0731', '0732', '0733', '0734', '0735', '0736', '0737', '0738', '0739',
                    '0740', '0741', '0742', '0743', '0744', '0745', '0746', '0747', '0748', '0749',
                    '0750', '0751', '0752', '0753', '0754', '0755', '0756', '0757', '0758', '0759',
                    '0760', '0761', '0762', '0763', '0764', '0765', '0766', '0767', '0768', '0769',
                    '0770', '0771', '0772', '0773', '0774', '0775', '0776', '0777', '0778', '0779',
                    '0780', '0781', '0782', '0783', '0784', '0785', '0786', '0787', '0788', '0789',
                    '0790', '0791', '0792', '0793', '0794', '0795', '0796', '0797', '0798', '0799',
                    '0110', '0111', '0112', '0113', '0114', '0115'];
        
        $prefix = fake()->randomElement($prefixes);
        $suffix = str_pad(fake()->numberBetween(0, 999999), 6, '0', STR_PAD_LEFT);
        
        return $prefix . $suffix;
    }

    private function generateKenyanId(): string
    {
        $year = fake()->numberBetween(1950, 2005);
        $number = fake()->numberBetween(1000000, 39999999);
        return (string) $number;
    }

    private function generateKenyanLicenseNumber(): string
    {
        $letters = range('A', 'Z');
        $prefix = fake()->randomElement(['A', 'B', 'C', 'D', 'E']) . fake()->randomElement($letters);
        $numbers = fake()->numberBetween(1000000, 9999999);
        return $prefix . $numbers;
    }

    public function active(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'active',
        ]);
    }

    public function available(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_available' => true,
        ]);
    }

    public function withValidLicense(): static
    {
        $issueDate = fake()->dateTimeBetween('-5 years', '-1 year');
        $expiryDate = fake()->dateTimeBetween('+1 year', '+3 years');

        return $this->state(fn (array $attributes) => [
            'driving_license_issue_date' => $issueDate,
            'driving_license_expiry_date' => $expiryDate,
        ]);
    }

    public function withUser(User $user): static
    {
        return $this->state(fn (array $attributes) => [
            'user_id' => $user->id,
            'email' => $user->email,
            'first_name' => $user->first_name,
            'second_name' => $user->second_name,
            'last_name' => $user->last_name,
            'phone_number' => $user->phone_number,
        ]);
    }

    public function withPartner(Partner $partner): static
    {
        return $this->state(fn (array $attributes) => [
            'partner_id' => $partner->id,
        ]);
    }
}