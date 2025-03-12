<?php

namespace Database\Factories\Users;

use App\Models\Customers;
use Illuminate\Database\Eloquent\Factories\Factory;
use function fake;

/**
 * @extends Factory<Customers>
 */
class CustomersFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {

        $customers = [
            'identity_number' => $this->faker->unique()->regexify('(00[1-9]|0[1-9][0-9]|[1-8][0-9]{2}|9[0-6])[0-9]{9}'),
            'type' => fake()->randomElement([0, 1]),
        ];
        return $customers;
    }
}
