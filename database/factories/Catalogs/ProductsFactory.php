<?php

namespace Database\Factories\Catalogs;

use App\Models\Catalogs\Brands;
use App\Models\Catalogs\Categories;
use App\Models\Catalogs\Products;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Products>
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
        $startDate = '2022-01-01 00:00:00';
        $endDate = '2024-12-31 23:59:59';
        return [
            'category_id' => Categories::inRandomOrder()->first()->id,
            'brand_id' => Brands::inRandomOrder()->first()->id,
            'name' => $this->faker->unique()->word(3, true),
            'sku' => Products::generateUniqueOrderNo('RBK-'),
            'release_date' => $this->faker->dateTimeBetween($startDate, $endDate),
            'weight' => $this->faker->numberBetween(1, 100),
            'magnetic' => $this->faker->boolean(),
            'price' => null,
            'box_weight' => $this->faker->numberBetween(1, 100),
            'quantity' => $this->faker->numberBetween(1, 100),
            'status' => $this->faker->boolean(),
            'image' => null,
        ];
    }
}
