<?php

namespace Database\Seeders\Catalogs;

use App\Models\Catalogs\Distributors;
use App\Models\Catalogs\Products;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        Products::factory(10)->create()->each(function ($product) {
            $distributors = Distributors::inRandomOrder()->take(3)->pluck('id');

            $distributors->each(function ($distributorId) use ($product) {
                DB::table('distributors_products')->insert([
                    'product_id' => $product->id,
                    'distributor_id' => $distributorId,
                    'price' => rand(1000, 5000) / 100,
                ]);
            });

            $minPrice = DB::table('distributors_products')
                ->where('product_id', $product->id)
                ->min('price');

            $product->update(['price' => $minPrice]);
        });
    }
}
