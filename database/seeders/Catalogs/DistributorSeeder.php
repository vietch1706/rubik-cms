<?php

namespace Database\Seeders\Catalogs;

use App\Models\Catalogs\Distributors;
use Illuminate\Database\Seeder;

class DistributorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $distributors = [
            [
                'name' => 'Alibaba',
                'address' => 'No. 699 Wangshang Road, Hangzhou, China',
                'country' => 'China',
                'email' => 'contact@alibaba.com',
                'phone' => '861234567890', // Số điện thoại không có dấu '-'
            ],
            [
                'name' => 'AliExpress',
                'address' => 'Baiyang Road, Wujin, Changzhou, China',
                'country' => 'China',
                'email' => 'support@aliexpress.com',
                'phone' => '860987654321',
            ],
            [
                'name' => 'Amazon Global',
                'address' => '410 Terry Avenue North, Seattle, WA 98109-5210, USA',
                'country' => 'USA',
                'email' => 'customerservice@amazon.com',
                'phone' => '18001234567',
            ],
            [
                'name' => 'Cubezz',
                'address' => '123 Main St, Suite 300, San Francisco, CA 94105, USA',
                'country' => 'USA',
                'email' => 'info@cubezz.com',
                'phone' => '11234567890',
            ],
            [
                'name' => 'TheCubicle',
                'address' => '2859 Route 66, Suite 3, St. Louis, MO 63130, USA',
                'country' => 'USA',
                'email' => 'contact@thecubicle.com',
                'phone' => '12345678901',
            ],
            [
                'name' => 'SpeedCubeShop',
                'address' => '4400 NW 36th St Suite 15, Miami, FL 33166, USA',
                'country' => 'USA',
                'email' => 'support@speedcubeshop.com',
                'phone' => '13456789012',
            ],
            [
                'name' => 'Lightake',
                'address' => 'Fuzhou City, Fujian Province, China',
                'country' => 'China',
                'email' => 'sales@lightake.com',
                'phone' => '864567890123',
            ],
            [
                'name' => 'Cubespeed',
                'address' => '88 Xianheng Road, Yiwu City, Zhejiang, China',
                'country' => 'China',
                'email' => 'service@cubespeed.com',
                'phone' => '865678901234',
            ]
        ];
        foreach ($distributors as $distributor) {
            $modelDistributor = new Distributors();
            $modelDistributor->name = $distributor['name'];
            $modelDistributor->address = $distributor['address'];
            $modelDistributor->country = $distributor['country'];
            $modelDistributor->email = $distributor['email'];
            $modelDistributor->phone = $distributor['phone'];
            $modelDistributor->save();
        }
    }
}
