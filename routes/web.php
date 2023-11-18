<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;
use App\Http\Controllers\UserController;


Route::get('/about', function () {
    return view('about');
});

Route::get('/', [UserController::class, 'showCorrectPage'])->name('login');
Route::post('/register', [UserController::class, 'register'])->middleware('guest');
Route::post('/login', [UserController::class, 'login'])->middleware('guest');
Route::post('/logout', [UserController::class, 'logout'])->middleware('auth');
Route::get('/profile/{user:username}', [UserController::class, 'showProfile']);

Route::get('/create-post', [PostController::class, 'showCreateForm'])->middleware('auth');
Route::post('/create', [PostController::class, 'storeNewPost'])->middleware('auth');
Route::get('/post/{content}', [PostController::class, 'showSinglePost']);