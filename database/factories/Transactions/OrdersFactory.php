<?php

namespace Database\Factories\Transactions;

use App\Models\Catalogs\Distributors;
use App\Models\Transactions\Orders;
use App\Models\Users\Users;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Orders>
 */
class OrdersFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        static $usedDistributorIds = [];
        $employee = Users::select('id')
            ->where('role_id', Users::ROLE_EMPLOYEE)
            ->inRandomOrder()
            ->first();
        $distributor = Distributors::select('id')
            ->whereNotIn('id', $usedDistributorIds)
            ->has('products')
            ->inRandomOrder()
            ->first();
        $usedDistributorIds[] = $distributor->id;
        $startDate = '2023-01-01 00:00:00';
        $endDate = '2024-12-31 23:59:59';
        return [
            'distributor_id' => $distributor->id,
            'user_id' => $employee->id,
            'order_number' => Orders::generateUniqueOrderNo('ORD-'),
            'date' => $this->faker->dateTimeBetween($startDate, $endDate),
            'status' => Orders::STATUS_PENDING,
            'note' => $this->faker->text(),
        ];
    }
}
