<?php

use App\Http\Controllers\PostsController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\MpesaController;
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
Route::get('/posts', [PostsController::class, 'showPosts'])->name('posts.show')->middleware('auth');
Route::get('/posts/create', [PostsController::class, 'showPostForm'])->name('posts.create')->middleware('auth');
Route::post('/create-post', [PostsController::class, 'createPost'])->middleware('auth');
Route::delete('/posts/{id}', [PostsController::class, 'deletePost'])->name('posts.destroy')->middleware('auth');
Route::get('/posts/{id}/edit', [PostsController::class, 'editPost'])->name('posts.edit')->middleware('auth');
Route::put('/update-post/{id}', [PostsController::class, 'updatePost'])->middleware('auth');

Route::post('/posts/{id}/like', [PostsController::class, 'likePost'])->name('posts.like')->middleware('auth');
Route::post('/comments/{id}/like', [PostsController::class, 'likeComment'])->name('comments.like');
Route::post('/posts/{id}/comment', [PostsController::class, 'addComment'])->name('posts.comment')->middleware('auth');
Route::put('/comments/{id}', [PostsController::class, 'updateComment'])->name('comments.update');
Route::delete('/comments/{id}', [PostsController::class, 'destroyComment'])->name('comments.destroy');

//Mpesa Test .env
Route::get('/test-env', function () {
    return [
        'consumer_key' => env('MPESA_CONSUMER_KEY'),
        'shortcode' => env('MPESA_SHORTCODE'),
        'callback_url' => env('MPESA_CALLBACK_URL'),
    ];
});


Route::post('/mpesa/donate', [MpesaController::class, 'initiateDonation'])->name('mpesa.donate');
Route::post('/mpesa/callback', [MpesaController::class, 'callback'])->name('mpesa.callback');

// routes/web.php
Route::get('/donation/status/{checkoutRequestId}', [MpesaController::class, 'checkStatus'])->name('donation.status');