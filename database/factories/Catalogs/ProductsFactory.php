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
        return [
            'category_id' => Categories::inRandomOrder()->first()->id,
            'brand_id' => Brands::inRandomOrder()->first()->id,
            'name' => $this->faker->word(),
            'slug' => $this->faker->slug(),
            'sku' => $this->faker->unique()->numberBetween(1000, 9999),
            'release_date' => $this->faker->date(),
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
