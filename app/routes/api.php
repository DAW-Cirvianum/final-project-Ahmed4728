<?php
namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/hola-mon', function(){
    return 'Hola Mon!';
});

Route::get('/hola/{name}', function($name){
    return 'Hola '.$name.'!';
});

Route::get('register',[AuthController::class,'store'])
->name('register.user');
