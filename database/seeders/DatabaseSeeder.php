<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Users\Users;
use Database\Seeders\Catalog\BrandSeeder;
use Database\Seeders\Catalog\CategorySeeder;
use Database\Seeders\Catalog\DistributorSeeder;
use Database\Seeders\Admin\RoleSeeder;
use Database\Seeders\Users\CustomerSeeder;
use Database\Seeders\Users\UserSeeder;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

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
        ]);
    }
}
