<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\City>
 */
class CityFactory extends Factory
{
    /**
     * @return array
     */
    public function definition(): array
    {
        return [
            'id' => fake()->randomDigitNotNull(),
            'name' => fake()->name(),
        ];
    }
}
