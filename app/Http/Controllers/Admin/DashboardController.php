<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Users\Trait\UsersTrait;
use Illuminate\Http\Request;
use function view;

class DashboardController extends Controller
{
    use UsersTrait;

    public function index()
    {
        //
        return view('users.dashboard');
    }
}
