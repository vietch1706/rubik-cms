<?php

namespace App\Schema;

use App\Models\Users\Customers;

class CustomerSchema
{
    private Customers $customers;

    public function __construct(
        Customers $customer,
    )
    {
        $this->customers = $customer;

    }

    public function convertData()
    {
        $user = $this->customers->users;
        return [
            'id' => $this->customers->id,
            'user_id' => $user->id,
            'first_name' => $user->first_name,
            'last_name' => $user->last_name,
            'gender' => $user->textGender,
            'phone' => $user->phone,
            'email' => $user->email,
            'identity_number' => $this->customers->identity_number,
            'type' => $this->customers->textType,
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
