<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;


Route::get('/about', function () {
    return view('about');
});

Route::get('/', [UserController::class, 'showCorrectPage']);

Route::post('/register', [UserController::class, 'register']);

Route::post('/login', [UserController::class, 'login']);

Route::post('/logout', [UserController::class, 'logout']);