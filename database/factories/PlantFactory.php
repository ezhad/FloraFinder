<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Plant>
 */
class PlantFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'common_name' => $this->faker->word(),
            'scientific_name' => $this->faker->word(), 
            'family' => $this->faker->word(),
            'habitat' => $this->faker->paragraph(),
            'lifespan' => $this->faker->word(),
            'care_tips' => $this->faker->paragraph(),
        ];
    }
}
