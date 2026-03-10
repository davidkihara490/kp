<?php

namespace Database\Factories;

use App\Models\BlogTag;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class BlogTagFactory extends Factory
{
    protected $model = BlogTag::class;

    public function definition(): array
    {
        $name = $this->faker->unique()->words(
            $this->faker->numberBetween(1, 2),
            true
        );

        $hasDescription = $this->faker->boolean(70);
        $hasMeta = $this->faker->boolean(60);

        return [
            'name' => ucfirst($name),
            'slug' => Str::slug($name),
            'description' => $hasDescription 
                ? $this->faker->sentence($this->faker->numberBetween(5, 10))
                : null,
            'meta_title' => $hasMeta 
                ? $this->faker->sentence($this->faker->numberBetween(3, 6))
                : null,
            'meta_description' => $hasMeta 
                ? $this->faker->paragraph(1)
                : null,
            'post_count' => 0, // Will be updated by observers or manually
            'created_at' => $this->faker->dateTimeBetween('-1 year', 'now'),
            'updated_at' => $this->faker->dateTimeBetween('-1 year', 'now'),
        ];
    }

    public function withDescription(): static
    {
        return $this->state(fn (array $attributes) => [
            'description' => $this->faker->paragraph($this->faker->numberBetween(1, 3)),
        ]);
    }

    public function withMetaData(): static
    {
        return $this->state(fn (array $attributes) => [
            'meta_title' => $this->faker->sentence($this->faker->numberBetween(4, 8)),
            'meta_description' => $this->faker->paragraph(2),
        ]);
    }

    public function popular(): static
    {
        return $this->state(fn (array $attributes) => [
            'post_count' => $this->faker->numberBetween(10, 50),
        ]);
    }

    public function trending(): static
    {
        return $this->state(fn (array $attributes) => [
            'post_count' => $this->faker->numberBetween(5, 20),
        ]);
    }

    public function niche(): static
    {
        return $this->state(fn (array $attributes) => [
            'post_count' => $this->faker->numberBetween(1, 5),
        ]);
    }
}