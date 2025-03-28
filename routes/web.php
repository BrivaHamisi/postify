<?php

use App\Http\Controllers\PostsController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('/login');
});

//Register Route
Route::get('/register', [UserController::class, 'showRegister']);
Route::post('/register', [UserController::class, 'register']);

//Login Route
Route::get('/login', [UserController::class, 'showLogin']);
Route::post('/login', [UserController::class, 'login']);

//Logout Route
Route::post('/logout', [UserController::class, 'logout']);

//Posts Route
Route::get('/posts', [PostsController::class, 'showPosts'])->middleware('auth');
Route::get('/posts/create', [PostsController::class, 'showPostForm'])->middleware('auth');
Route::post('/create-post', [PostsController::class, 'createPost'])->middleware('auth');
Route::delete('/posts/{id}', [PostsController::class, 'deletePost'])->middleware('auth');
Route::get('/posts/{id}/edit', [PostsController::class, 'editPost'])->middleware('auth');
Route::put('/update-post/{id}', [PostsController::class, 'updatePost'])->middleware('auth');
