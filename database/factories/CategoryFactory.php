<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Category>
 */
class CategoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'id' => str()->uuid(),
            'name' => fake()->name(),
            'description' => rand(0, 1) ? fake()->paragraph(1) : null,
            'is_active' => rand(0, 1),
        ];
    }
}
