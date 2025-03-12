<?php

namespace Database\Seeders\Users;

use App\Models\Customer;
use App\Models\Employee;
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

        $adminEmployee = new Employees();
        $adminEmployee->user_id = $admin->id;
        $adminEmployee->salary = 100000;
        $adminEmployee->save();
        $users = Users::factory(100)->create();

        $users = $users->shuffle();

        $employeeUsers = $users->random(50);
        $customerUsers = $users->diff($employeeUsers);

        foreach ($employeeUsers as $user) {
            $user->role_id = Users::ROLE_EMPLOYEE;
            $user->save();
            $user->employee()->save(Employees::factory()->make());
        }

        // Assign the customer role and create customer records
        foreach ($customerUsers as $user) {
            $user->role_id = Users::ROLE_CUSTOMER;
            $user->save();
            $user->customer()->save(Customers::factory()->make());
        }
    }
}
