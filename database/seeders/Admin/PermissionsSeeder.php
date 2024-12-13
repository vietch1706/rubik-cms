<?php

namespace Database\Seeders\Admin;

use Illuminate\Database\Seeder;

class PermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $permissions = [
            [
                'id' => 1,
                'name' => 'Order Approval',
                'code' => 'order-approval',
                'description' => 'Authorize order before processing'
            ],
            [
                'id' => 2,
                'name' => 'Employee Management',
                'code' => 'employee-management',
                'description' => 'Managing and maintaining employee data'
            ],
            [
                'id' => 3,
                'name' => 'Activate/Deactivate Customer Account',
                'code' => 'customer-account',
                'description' => 'Activating or deactivating customer accounts to control access and ensure security.'
            ],
            [
                'id' => 4,
                'name' => 'Permission Management',
                'code' => 'permission-management',
                'description' => 'Managing user access rights to ensure appropriate permissions within the system.'
            ],
            [
                'id' => 5,
                'name' => 'Distributors Management',
                'code' => 'distributors-management',
                'description' => 'Overseeing supplier information.'
            ],
            [
                'id' => 6,
                'name' => 'Sales Management',
                'code' => 'sales-management',
                'description' => 'Organize and attach new sale program'
            ],
            [
                'id' => 7,
                'name' => 'Product Management',
                'code' => 'product-management',
                'description' => 'Organize and attach new product. Check quantity of product.'
            ],
            [
                'id' => 8,
                'name' => 'Blog Management',
                'code' => 'blog-management',
                'description' => 'Organize and attach new blog.'
            ],
            [
                'id' => 9,
                'name' => 'Customer Management',
            ]

        ];
    }
}
