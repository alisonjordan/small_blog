<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\FollowController;

//SITE RELATED ROUTES
Route::get('/about', function () {
    return view('about');
});

//ADMIN RELATED ROUTES
Route::get('/admin-only', function () {
    return view('about');    
})->middleware('can:visitAdminPages');


//USER RELATED ROUTES
Route::get('/', [UserController::class, 'showCorrectPage'])->name('login');
Route::post('/register', [UserController::class, 'register'])->middleware('guest');
Route::post('/login', [UserController::class, 'login'])->middleware('guest');
Route::post('/logout', [UserController::class, 'logout'])->middleware('auth');
Route::get('/profile/{user:username}', [UserController::class, 'showProfile']);
Route::get('/profile/{user:username}/followers', [UserController::class, 'showFollowers']);
Route::get('/profile/{user:username}/following', [UserController::class, 'showFollowing']);
Route::get('/manage-avatar', [UserController::class, 'showAvatarManageForm'])->middleware('auth');
Route::post('/manage-avatar', [UserController::class, 'storeAvatar'])->middleware('auth');

//FOLLOWS ROUTES
Route::post('/create-follow/{user:username}', [FollowController::class, 'storeFollow'])->middleware('auth');
Route::delete('/remove-follow/{user:username}', [FollowController::class, 'deleteFollow'])->middleware('auth');

//POST RELATED ROUTES
Route::get('/create-post', [PostController::class, 'showCreateForm'])->middleware('auth');
Route::post('/create', [PostController::class, 'storeNewPost'])->middleware('auth');
Route::get('/post/{content}', [PostController::class, 'showSinglePost']);
Route::delete('/post/{post}', [PostController::class, 'delete'])->middleware('can:delete,post');
Route::get('/post/{post}/edit', [PostController::class, 'showEditForm'])->middleware('can:update,post');
Route::put('/post/{post}', [PostController::class, 'update'])->middleware('can:update,post');
Route::get('/search/{term}', [PostController::class, 'search']);