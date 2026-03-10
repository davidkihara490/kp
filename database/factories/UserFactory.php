<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    protected static ?string $password;

    public function definition(): array
    {
        $kenyanFirstNames = [
            'John',
            'James',
            'Peter',
            'David',
            'Daniel',
            'Joseph',
            'Samuel',
            'Patrick',
            'Mary',
            'Jane',
            'Grace',
            'Mercy',
            'Faith',
            'Lucy',
            'Agnes',
            'Margaret',
            'Kenneth',
            'Brian',
            'Kevin',
            'Collins',
            'Dennis',
            'Evans',
            'Felix',
            'George',
            'Caroline',
            'Catherine',
            'Dorcas',
            'Edith',
            'Florence',
            'Gladys',
            'Hellen'
        ];

        $kenyanSecondNames = [
            'Kamau',
            'Odhiambo',
            'Otieno',
            'Omondi',
            'Kipchoge',
            'Kiprop',
            'Kiptoo',
            'Muthoni',
            'Wanjiku',
            'Akinyi',
            'Atieno',
            'Achieng',
            'Jeruto',
            'Jepchirchir',
            'Njoroge',
            'Waweru',
            'Mwangi',
            'Njeri',
            'Wambui',
            'Wairimu',
            'Nduta'
        ];

        $kenyanLastNames = [
            'Ochieng',
            'Okoth',
            'Omondi',
            'Otieno',
            'Kipchumba',
            'Kipkemoi',
            'Kiprotich',
            'Wanjiru',
            'Wambui',
            'Njeri',
            'Mwende',
            'Akoth',
            'Auma',
            'Anyango',
            'Maina',
            'Kimani',
            'Kariuki',
            'Njenga',
            'Wainaina',
            'Macharia',
            'Ndegwa'
        ];

        $userTypes = ['transport', 'pickup-dropoff', 'driver', 'pha', 'admin'];
        $statuses = ['active', 'inactive', 'pending', 'suspended'];

        $firstName = fake()->randomElement($kenyanFirstNames);
        $secondName = fake()->optional(0.7)->randomElement($kenyanSecondNames);
        $lastName = fake()->randomElement($kenyanLastNames);

        return [
            'first_name' => $firstName,
            'second_name' => $secondName,
            'last_name' => $lastName,
            'user_name' => strtolower($firstName . ($secondName ? '.' . $secondName : '') . '.' . $lastName . rand(1, 99)),
            'user_type' => fake()->randomElement($userTypes),
            'email' => function (array $attributes) {
                $firstName = strtolower($attributes['first_name']);
                $lastName = strtolower($attributes['last_name']);
                return fake()->unique()->regexify($firstName . '\.' . $lastName . '[0-9]{1,3}@example\.com');
            },
            'phone_number' => $this->generateKenyanPhone(),
            'password' => static::$password ??= Hash::make('password'),
            'status' => fake()->randomElement($statuses),
            'terms_and_conditions' => fake()->boolean(80),
            'privacy_policy' => fake()->boolean(80),
            'login_attempts' => fake()->numberBetween(0, 5),
            'last_login_at' => fake()->optional(0.7)->dateTimeBetween('-1 year', 'now'),
            'last_login_ip' => fake()->optional(0.7)->ipv4(),
            'email_verified_at' => fake()->optional(0.8)->dateTimeBetween('-1 year', 'now'),
            'remember_token' => Str::random(10),
        ];
    }

    private function generateKenyanPhone(): string
    {
        $prefixes = [
            '0700',
            '0701',
            '0710',
            '0711',
            '0712',
            '0713',
            '0714',
            '0715',
            '0716',
            '0717',
            '0718',
            '0719',
            '0720',
            '0721',
            '0722',
            '0723',
            '0724',
            '0725',
            '0726',
            '0727',
            '0728',
            '0729',
            '0730',
            '0731',
            '0732',
            '0733',
            '0734',
            '0735',
            '0736',
            '0737',
            '0738',
            '0739',
            '0740',
            '0741',
            '0742',
            '0743',
            '0744',
            '0745',
            '0746',
            '0747',
            '0748',
            '0749',
            '0750',
            '0751',
            '0752',
            '0753',
            '0754',
            '0755',
            '0756',
            '0757',
            '0758',
            '0759',
            '0760',
            '0761',
            '0762',
            '0763',
            '0764',
            '0765',
            '0766',
            '0767',
            '0768',
            '0769',
            '0770',
            '0771',
            '0772',
            '0773',
            '0774',
            '0775',
            '0776',
            '0777',
            '0778',
            '0779',
            '0780',
            '0781',
            '0782',
            '0783',
            '0784',
            '0785',
            '0786',
            '0787',
            '0788',
            '0789',
            '0790',
            '0791',
            '0792',
            '0793',
            '0794',
            '0795',
            '0796',
            '0797',
            '0798',
            '0799',
            '0110',
            '0111',
            '0112',
            '0113',
            '0114',
            '0115'
        ];

        $prefix = fake()->randomElement($prefixes);
        $suffix = str_pad(fake()->numberBetween(0, 999999), 6, '0', STR_PAD_LEFT);

        return $prefix . $suffix;
    }

    public function unverified(): static
    {
        return $this->state(fn(array $attributes) => [
            'email_verified_at' => null,
        ]);
    }

    public function transportPartner(): static
    {
        return $this->state(fn(array $attributes) => [
            'user_type' => 'transport',
        ]);
    }

    public function pickupDropoffPartner(): static
    {
        return $this->state(fn(array $attributes) => [
            'user_type' => 'pickup-dropoff',
        ]);
    }

    public function driver(): static
    {
        return $this->state(fn(array $attributes) => [
            'user_type' => 'driver',
        ]);
    }

    public function pha(): static
    {
        return $this->state(fn(array $attributes) => [
            'user_type' => 'pha',
        ]);
    }

    public function admin(): static
    {
        return $this->state(fn(array $attributes) => [
            'user_type' => 'admin',
        ]);
    }

    public function active(): static
    {
        return $this->state(fn(array $attributes) => [
            'status' => 'active',
        ]);
    }
}
