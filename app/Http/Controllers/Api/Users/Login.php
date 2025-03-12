<?php

namespace App\Http\Controllers\Api\Users;

use App\Http\Requests\Auth\LoginRequest;

class Login extends AbstractCustomers
{
    public function __invoke(LoginRequest $request)
    {
        $data = $request->all();
        return $this->__users->login($data);
    }
}
