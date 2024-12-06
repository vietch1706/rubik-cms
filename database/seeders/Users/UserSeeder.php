<?php

namespace Database\Seeders\Users;

use App\Models\Users\Customers;
use App\Models\Users\Employees;
use App\Models\Users\Users;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $admin = new Users();
        $admin->role_id = 1;
        $admin->first_name = 'Cu';
        $admin->last_name = 'Hoai Viet';
        $admin->gender = Users::GENDER_MALE;
        $admin->phone = '0123456789';
        $admin->email = 'admin@admin.com';
        $admin->password = Hash::make('admin');
        $admin->save();
        $users = Users::factory()->count(100)->create();
        $customers = Customers::factory(50)->recycle([$users])->create();
        $employees = Employees::factory(50)->recycle([$users])->create();
    }
}
