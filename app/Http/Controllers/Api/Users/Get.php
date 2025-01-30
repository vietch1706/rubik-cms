<?php

namespace App\Http\Controllers\Api\Users;

class Get extends AbstractCustomers
{
    public function __invoke()
    {
        return $this->__users->getUser();
    }
}
