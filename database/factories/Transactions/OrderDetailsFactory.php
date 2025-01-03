<?php

namespace Database\Factories\Transactions;

use App\Models\Transactions\OrderDetails;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<OrderDetails>
 */
class OrderDetailsFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'price' => $this->faker->randomFloat(2, 100, 1000),
            'quantity' => $this->faker->numberBetween(1, 100),
        ];
    }
}
