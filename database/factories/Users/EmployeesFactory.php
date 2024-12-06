<?php

namespace Database\Factories\Users;

use App\Models\Users\Users;
use Illuminate\Database\Eloquent\Factories\Factory;
use function fake;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Users>
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
            'user_id' => Users::factory(),
            'salary' => $this->faker->numberBetween($min = 1, $max = 10),
        ];
        return $employees;
    }
}
