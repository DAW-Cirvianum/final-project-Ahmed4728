<?php

use Illuminate\Support\Facades\Route;

// Controllers
use App\Http\Controllers\Api\CategoriaController;
use App\Http\Controllers\Api\ProducteController;
use App\Http\Controllers\Api\ClientController;
use App\Http\Controllers\Api\ComandaController;
use App\Http\Controllers\Api\DetallComandaController;
use App\Http\Controllers\Api\ProveidorController;
use App\Http\Controllers\Api\AuthController;

// Ruta de prova
Route::get('/', function () {
    return response()->json(['missatge' => 'API funcionant!']);
});

// --------------------
// AUTENTICACIÓ
// --------------------
Route::post('register', [AuthController::class, 'store']);
Route::post('login', [AuthController::class, 'login']);

Route::middleware('auth:api')->group(function () {
    Route::get('me', [AuthController::class, 'me']);
    Route::post('logout', [AuthController::class, 'logout']);
});

// --------------------
// RUTES PÚBLIQUES (GET)
// --------------------
Route::apiResource('categories', CategoriaController::class)
    ->only(['index', 'show']);

Route::apiResource('productes', ProducteController::class)
    ->only(['index', 'show']);

Route::apiResource('clients', ClientController::class)
    ->only(['index', 'show']);

Route::apiResource('proveidors', ProveidorController::class)
    ->only(['index', 'show']);

Route::apiResource('comandes', ComandaController::class)
    ->only(['index', 'show']);

Route::apiResource('detalls_comanda', DetallComandaController::class)
    ->only(['index', 'show']);

// --------------------
// RUTES PROTEGIDES (POST, PUT, DELETE)
// --------------------
Route::middleware('auth:api')->group(function () {

    Route::apiResource('categories', CategoriaController::class)
        ->except(['index', 'show']);

    Route::apiResource('productes', ProducteController::class)
        ->except(['index', 'show']);

    Route::apiResource('clients', ClientController::class)
        ->except(['index', 'show']);

    Route::apiResource('proveidors', ProveidorController::class)
        ->except(['index', 'show']);

    Route::apiResource('comandes', ComandaController::class)
        ->except(['index', 'show']);

    Route::apiResource('detalls_comanda', DetallComandaController::class)
        ->except(['index', 'show']);
});
