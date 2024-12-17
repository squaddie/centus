<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\History>
 */
class HistoryFactory extends Factory
{
    /**
     * @return array
     */
    public function definition(): array
    {
        return [
            'u_id' => fake()->randomDigitNotNull,
            'city_id' => fake()->randomDigitNotNull,
            'value' => fake()->randomDigitNotNull,
            'type' => fake()->numberBetween(1, 2),
        ];
    }
}
