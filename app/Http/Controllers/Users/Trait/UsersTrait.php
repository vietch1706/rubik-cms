<?php

namespace App\Http\Controllers\Users\Trait;

use App\Models\Users\Users;
use function auth;
use function view;

trait UsersTrait
{
    public function getLoginStatus()
    {
        #TODO: check user login sai redirect ve login
        $curAccount = auth()->user();
        if (empty($curAccount)) {
            return false;
        }
        return true;
    }

}
