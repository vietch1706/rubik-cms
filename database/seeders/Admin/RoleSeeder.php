<?php

namespace Database\Seeders\Admin;

use App\Models\Admin\Roles;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $roles = [
            [
                'name' => 'Admin',
                'code' => 'admin',
                'is_system' => Roles::IS_SYSTEM_YES,
            ],
            [
                'name' => 'Customer',
                'code' => 'customer',
                'is_system' => Roles::IS_SYSTEM_NO, Roles::IS_SYSTEM_NO,
            ],
            [
                'name' => 'Employee',
                'code' => 'employee',
                'is_system' => Roles::IS_SYSTEM_NO,
            ],
        ];
        foreach ($roles as $role) {
            $modelRole = new Roles();
            $modelRole->name = $role['name'];
            $modelRole->code = $role['code'];
            $modelRole->is_system = $role['is_system'];
            $modelRole->save();
        }
    }
}
