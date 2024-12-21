<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use function view;

class DashboardController extends Controller
{
    public function index()
    {
        //
        return view('users.dashboard');
    }
}
