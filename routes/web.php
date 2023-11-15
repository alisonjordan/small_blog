<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

Route::get('/', function () {
    return view('homepage');
});


Route::get('/about', function () {
    return view('about');
});

Route::post('/register', [UserController::class, 'register']);