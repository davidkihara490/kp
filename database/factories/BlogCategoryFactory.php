<?php

namespace Database\Factories;

use App\Models\BlogCategory;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class BlogCategoryFactory extends Factory
{
    public function definition(): array
    {
        $name = $this->faker->unique()->words(
            $this->faker->numberBetween(1, 3),
            true
        );

        return [
            'name' => ucfirst($name),
            'slug' => Str::slug($name),
            'description' => $this->faker->boolean(70)
                ? $this->faker->paragraph($this->faker->numberBetween(1, 3))
                : null,
            'parent_id' => null, // Will be set in the seeder
            'order' => $this->faker->numberBetween(1, 100),
            'is_active' => $this->faker->boolean(85),
            'meta_title' => $this->faker->boolean(60)
                ? $this->faker->sentence($this->faker->numberBetween(3, 6))
                : null,
            'meta_description' => $this->faker->boolean(60)
                ? $this->faker->paragraph(1)
                : null,
            'meta_keywords' => $this->faker->boolean(50)
                ? $this->faker->words($this->faker->numberBetween(3, 8))
                : [],

            'featured_image' => $this->faker->boolean(40)
                ? $this->faker->imageUrl(1200, 600, 'nature', true, 'category')
                : null,
            'post_count' => $this->faker->numberBetween(10, 50),
            'created_at' => $this->faker->dateTimeBetween('-1 year', 'now'),
            'updated_at' => $this->faker->dateTimeBetween('-1 year', 'now'),
        ];
    }
    // Add to BlogCategoryFactory class
    public function inactive(): static
    {
        return $this->state(fn(array $attributes) => [
            'is_active' => false,
        ]);
    }

    public function active(): static
    {
        return $this->state(fn(array $attributes) => [
            'is_active' => true,
        ]);
    }

    public function withParent(): static
    {
        return $this->state(fn(array $attributes) => [
            'parent_id' => BlogCategory::factory(),
        ]);
    }
}
