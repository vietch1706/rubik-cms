<?php

namespace App\Schema;

use App\Models\Users\Employees;

class EmployeeSchema
{
    private Employees $employees;

    public function __construct(
        Employees $employee,
    )
    {
        $this->employees = $employee;

    }

    public function convertData()
    {
        $user = $this->employees->users;
        return [
            'id' => $this->employees->id,
            'user_id' => $user->id,
            'role_id' => $user->role_id,
            'first_name' => $user->first_name,
            'last_name' => $user->last_name,
            'gender' => $user->textGender,
            'phone' => $user->phone,
            'email' => $user->email,
            'salary' => $this->employees->salary,
            'address' => $user->address,
            'avatar' => $user->avatar,
            'password' => $user->password,
            'is_activated' => $user->is_activated,
            'activated_at' => $user->activated_at,
            'deleted_at' => $user->deleted_at,
            'created_at' => $user->created_at,
            'updated_at' => $user->updated_at,
        ];
    }
}
