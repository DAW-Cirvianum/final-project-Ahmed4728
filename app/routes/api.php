<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Controllers
use App\Http\Controllers\Api\CategoriaController;
use App\Http\Controllers\Api\ProducteController;
use App\Http\Controllers\Api\ClientController;
use App\Http\Controllers\Api\ComandaController;
use App\Http\Controllers\Api\DetallComandaController;
use App\Http\Controllers\Api\ProveidorController;
use App\Http\Controllers\Api\AuthController;

// Ruta de prova inicial
Route::get('/', function() {
    return response()->json(['missatge' => 'API funcionant!']);
});

// Autenticació
Route::post('register', [AuthController::class, 'store']); // registrar usuari
// Route::post('login', [AuthController::class, 'login']); // més endavant

// Rutes API CRUD
Route::apiResource('categories', CategoriaController::class);
Route::apiResource('productes', ProducteController::class);
Route::apiResource('clients', ClientController::class);
Route::apiResource('comandes', ComandaController::class);
Route::apiResource('detalls_comanda', DetallComandaController::class);
Route::apiResource('proveidors', ProveidorController::class);
