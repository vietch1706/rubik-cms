<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Database\Seeders\Admin\RoleSeeder;
use Database\Seeders\Catalogs\BrandSeeder;
use Database\Seeders\Catalogs\CategorySeeder;
use Database\Seeders\Catalogs\DistributorSeeder;
use Database\Seeders\Catalogs\ProductSeeder;
use Database\Seeders\Transactions\OrderSeeder;
use Database\Seeders\Users\UserSeeder;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            BrandSeeder::class,
            CategorySeeder::class,
            DistributorSeeder::class,
            RoleSeeder::class,
            UserSeeder::class,
            ProductSeeder::class,
            OrderSeeder::class
        ]);
    }
}
