<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Products>
 */
class ProductsFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        // Estructura de generación de datos aleatorios para rellenar
        // la tabla de PRODUCTS a través del seeder
        return [
            'name' => fake()->words(5, true),
            'description' => fake()->sentence(120),
            'price' => fake()->randomNumber(2),
            'stock' => fake()->randomNumber(2),
        ];
    }
}
