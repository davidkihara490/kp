<?php

namespace Database\Factories;

use App\Models\ParcelHandlingAssistant;
use App\Models\Partner;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ParcelHandlingAssistant>
 */
class ParcelHandlingAssistantFactory extends Factory
{
    public function definition(): array
    {
        $kenyanFirstNames = [
            'John', 'James', 'Peter', 'David', 'Daniel', 'Joseph', 'Samuel', 'Patrick',
            'Mary', 'Jane', 'Grace', 'Mercy', 'Faith', 'Lucy', 'Agnes', 'Margaret',
            'Brian', 'Kevin', 'Collins', 'Dennis', 'Evans', 'Felix', 'George',
            'Caroline', 'Catherine', 'Dorcas', 'Edith', 'Florence', 'Gladys', 'Hellen'
        ];
        
        $kenyanSecondNames = [
            'Kamau', 'Odhiambo', 'Otieno', 'Omondi', 'Kipchoge', 'Kiprop', 'Kiptoo',
            'Njoroge', 'Waweru', 'Mwangi', 'Maina', 'Kimani', 'Kariuki', 'Njenga',
            'Ochieng', 'Okoth', 'Kipchumba', 'Kipkemoi', 'Kiprotich'
        ];
        
        $kenyanLastNames = [
            'Ochieng', 'Okoth', 'Omondi', 'Otieno', 'Kipchumba', 'Kipkemoi', 'Kiprotich',
            'Maina', 'Kimani', 'Kariuki', 'Njenga', 'Wainaina', 'Macharia', 'Ndegwa',
            'Odhiambo', 'Onyango', 'Awuor', 'Achieng', 'Anyango'
        ];

        $roles = ['Loader', 'Unloader', 'Sorter', 'Checker', 'Supervisor', 'Clerk', 'Packer', 'Dispatcher'];
        $statuses = ['active', 'inactive', 'pending', 'suspended'];
        
        $firstName = fake()->randomElement($kenyanFirstNames);
        $secondName = fake()->optional(0.6)->randomElement($kenyanSecondNames);
        $lastName = fake()->randomElement($kenyanLastNames);

        return [
            'first_name' => $firstName,
            'second_name' => $secondName,
            'last_name' => $lastName,
            'phone_number' => $this->generateKenyanPhone(),
            'email' => strtolower($firstName . '.' . ($secondName ?? $lastName) . rand(1, 999) . '@pha.co.ke'),
            'role' => fake()->randomElement($roles),
            'id_number' => $this->generateKenyanId(),
            'status' => fake()->randomElement($statuses),
            'user_id' => null,
            'partner_id' => Partner::factory(),
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
        return (string) fake()->numberBetween(20000000, 39999999);
    }

    public function active(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'active',
        ]);
    }

    public function withRole(string $role): static
    {
        return $this->state(fn (array $attributes) => [
            'role' => $role,
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