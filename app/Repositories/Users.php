<?php

namespace App\Repositories;

use App\Http\Resources\UsersResource;
use Illuminate\Support\Facades\Auth;
use function response;

class Users
{

    public function getUser()
    {
        $user = auth()->user();
        if (!$user) {
            return response()->json(['error' => 'Unauthenticated'], 401);
        }
        return new UsersResource($user);
    }

    public function login($data)
    {
        if (Auth::attempt($data)) {
            $user = Auth::user();
            $token = $user->createToken('Rubik Store')->plainTextToken;
            return response()->json(['token' => $token]);
        }
        return response()->json([
            'message' => 'Wrong email or password!'
        ], 401);
    }
}
