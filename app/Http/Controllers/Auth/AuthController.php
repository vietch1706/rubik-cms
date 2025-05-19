<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use function redirect;
use function route;

class AuthController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        //
        if (Auth::check()) {
            return redirect()->intended(route('admin.dashboard'));
        }
        return view('auth.login');
    }

    public function login(LoginRequest $request)
    {
        if (Auth::attempt([
            'email' => $request->input("email"),
            'password' => $request->input("password"),
        ])) {
            $request->session()->regenerate();
            return redirect()->intended(route('admin.dashboard'))->with('success', 'Login Successfully!');
        }
        return redirect(route('admin.login'))->with('error', 'Wrong email or password!');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('admin.login')->with('success', 'Logout!');
    }
}
