<?php

namespace Database\Factories\Users;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Users>
 */
class UsersFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $gender = $this->faker->randomElement(['male', 'female']);
        $isActivated = $this->faker->boolean();
        $users = [
            'role_id' => $this->faker->randomElement([2, 3]),
            'first_name' => $this->faker->firstName($gender),
            'last_name' => $this->faker->lastName(),
            'gender' => $gender == 'male' ? 0 : 1,
            'phone' => $this->faker->unique()->regexify('\+84(3|5|7|8|9)[0-9]{8}'),
            'email' => $this->faker->unique()->safeEmail(),
            'address' => $this->faker->address(),
            'password' => Hash::make('Viet@170602'),
            'is_activated' => $isActivated,
            'activated_at' => null,

        ];
        if ($users['is_activated']) {
            $users['activated_at'] = $this->faker->dateTimeBetween($startDate = '-1 years', $endDate = 'now', $timezone = null);
        }
        return $users;
    }
}
