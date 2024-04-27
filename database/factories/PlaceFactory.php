<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class PlaceFactory extends Factory
{
    public function definition(): array
    {
        return [
            'latitude'            => fake()->latitude(),
            'longitude'           => fake()->longitude(),
            'name'                => fake()->city() . ', ' . fake()->country(),
            'average_temperature' => fake()->randomFloat( 1, 0, 100 )
        ];
    }
}
