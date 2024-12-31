<?php

namespace Database\Factories\Users;

use App\Models\Users;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Users>
 */
class EmployeesFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $employees = [
            'salary' => $this->faker->numberBetween($min = 1000, $max = 10000),
        ];
        return $employees;
    }
}
