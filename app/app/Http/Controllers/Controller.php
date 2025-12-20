<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

use App\Models\User;
use Illuminate\Support\Facades\Request;

abstract class Controller
{
    //
public function store(Request $request){
    $validator = Validator ::make($request->all(), [
        'name' => ['required', 'string', 'max:255'],
        'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
        'name' => ['required', 'string', 'min:6', 'confirmed'],
    ]);
    if($validator->fails()){
        return response()->json([
            'message' => 'Validation error',
            'errors' => $validator->errors(),

        ], 422);
    }

        if($validator->fails()){
        return response()->json([
            'status' => false,
            'message' => $validator->errors(),
            'errorCode' => 422
        ]);
    }
        $data = $validator->validated();


    $user = User::create([
        'name' => $data['name'],
        'email' => $data['email'],
        'password' =>Hash::make($data['password']),
    ]);
    
    $token = $user->createToken('api-token')->plainTextToken;

    return response()->json([
        'message' => 'User created',
        'token' => $token,
        'user' => $user,
    ], 200);
}

} 