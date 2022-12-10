<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Order>
 */
class OrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        // Creación de datos aleatorios de pedidos
        $paymentMethod = array( "Credit", "Debit" );
        $sent = array_rand([0, 1]);
        return [
            'mailuser' => fake()->email(),
            'name' => fake()->name(),
            'surname' =>  fake()->lastName(),
            'address' => fake()->streetAddress(),
            'country' => fake()->country(),
            'zip' => fake()->postcode(),
            'paymentMethod' => $paymentMethod[array_rand($paymentMethod, 1)],
            'fullCardName' => fake()->name().' '.fake()->lastName(),
            'cardNumber' => fake()->creditCardNumber(),
            'cardExpiration' => fake()->date('Y'),
            'cvv' => rand(100,999),
            'orderTotalPrice' => rand(0 * 100 , 999 * 100) / 100,
            'productsOrdered' => "28, Camiseta 1, 3, 60.00",
            'sent' => $sent
        ];
    }
}
