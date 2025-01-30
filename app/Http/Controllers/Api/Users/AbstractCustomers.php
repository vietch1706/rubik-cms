<?php

namespace App\Http\Controllers\Api\Users;

use App\Http\Controllers\Controller;
use App\Repositories\Users;

class AbstractCustomers extends Controller
{
    //
    protected Users $__users;

    public function __construct(Users $user)
    {
        $this->__users = $user;
    }
}
