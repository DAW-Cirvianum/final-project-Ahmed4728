<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminUserController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;

require __DIR__.'/auth.php';

Route::get('/', function () {
    return redirect('/login');
});

Route::get('/login', [AuthenticatedSessionController::class, 'create'])
    ->middleware('guest')
    ->name('login');

Route::post('/login', [AuthenticatedSessionController::class, 'store'])
    ->middleware('guest');

Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])
    ->middleware('auth')
    ->name('logout');

Route::middleware(['auth', 'admin'])
    ->prefix('admin')
    ->group(function () {
        Route::get('/users', [AdminUserController::class, 'index']);
        Route::get('/users/{user}/edit', [AdminUserController::class, 'edit']);
        Route::put('/users/{user}', [AdminUserController::class, 'update']);
        Route::delete('/users/{user}', [AdminUserController::class, 'destroy']);
    });
