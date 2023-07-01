<?php

namespace Database\Factories;

use BRCas\MV\Domain\Enum\Rating;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Video>
 */
class VideoFactory extends Factory
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
            'title' => $this->faker->name(),
            'description' => $this->faker->sentence(10),
            'year_launched' => now->parse(now()->addYears(5))->format('Y'),
            'opened' => true,
            'rating' => Rating::L->value,
            'duration' => 1,
            'created_at' => now(),
        ];
    }
}
