<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class AuthController extends Controller
{
    //
    public function login(Request $request)
{
    $credentials = $request->validate([
        'email' => 'required|email',
        'password' => 'required|string',
    ]);

    if (!$token = Auth::guard('api')->attempt($credentials)) {
        return response()->json([
            'exit' => false,
            'missatge' => 'Credencials incorrectes'
        ], 401);
    }

    return response()->json([
        'exit' => true,
        'token' => $token,
        'tipus' => 'bearer'
    ]);
}
}
