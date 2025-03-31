<?php

use App\Http\Controllers\PostsController;
use App\Http\Controllers\UserController;
use Faker\Guesser\Name;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('/login');
});

//Register Route
Route::get('/register', [UserController::class, 'showRegister']);
Route::post('/register', [UserController::class, 'register'])->name('register');

//Login Route
Route::get('/login', [UserController::class, 'showLogin']);
Route::post('/login', [UserController::class, 'login'])->name('login');

//Logout Route
Route::post('/logout', [UserController::class, 'logout'])->name('logout');

//Posts Route
Route::get('/posts', [PostsController::class, 'showPosts'])->middleware('auth');
Route::get('/posts/create', [PostsController::class, 'showPostForm'])->middleware('auth');
Route::post('/create-post', [PostsController::class, 'createPost'])->middleware('auth');
Route::delete('/posts/{id}', [PostsController::class, 'deletePost'])->middleware('auth');
Route::get('/posts/{id}/edit', [PostsController::class, 'editPost'])->middleware('auth');
Route::put('/update-post/{id}', [PostsController::class, 'updatePost'])->middleware('auth');

Route::post('/posts/{id}/like', [PostsController::class, 'likePost'])->name('posts.like')->middleware('auth');
Route::post('/posts/{id}/comment', [PostsController::class, 'addComment'])->name('posts.comment')->middleware('auth');
// Route::delete('/posts/{id}/comment/{commentId}', [PostsController::class, 'deleteComment'])->middleware('auth');
// Route::get('/posts/{id}/comments', [PostsController::class, 'showComments'])->middleware('auth');
// Route::get('/posts/{id}/likes', [PostsController::class, 'showLikes'])->middleware('auth');
// Route::post('/posts/{id}/unlike', [PostsController::class, 'unlikePost'])->middleware('auth');
