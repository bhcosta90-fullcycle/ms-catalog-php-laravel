<?php

namespace Database\Factories;

use BRCas\MV\Domain\Enum\CastMemberType;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CastMember>
 */
class CastMemberFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $types = [CastMemberType::ACTOR, CastMemberType::DIRECTOR];

        return [
            'id' => str()->uuid(),
            'name' => fake()->name(),
            'type' => $types[array_rand($types)],
            'is_active' => rand(0, 1),
        ];
    }
}
