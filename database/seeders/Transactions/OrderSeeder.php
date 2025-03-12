<?php

namespace Database\Seeders\Transactions;

use App\Models\Transactions\OrderDetails;
use App\Models\Transactions\Orders;
use Illuminate\Database\Seeder;
use function rand;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        Orders::factory()->count(5)->create()->each(function ($order) {
            $products = $order->distributor->products()->select('id')->get();
            $productCount = $products->count();
            $selectedProducts = $products->pluck('id')->shuffle()->take(rand(1, $productCount));
            $order->details()->createMany(
                $selectedProducts->map(function ($productId) use ($order) {
                    return OrderDetails::factory()->make([
                        'product_id' => $productId,
                        'order_id' => $order->id,
                    ])->toArray();
                })->toArray()
            );
        });
    }
}
