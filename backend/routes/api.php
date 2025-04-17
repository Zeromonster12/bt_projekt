<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\YearController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('/createPost', [PostController::class, 'create']);
    Route::get('/deletePost/{id}', [PostController::class, 'destroy']);
});


//TODO: LOGIN
Route::post('/login', [UserController::class, 'login'])->name('login');
Route::get('/posts', [PostController::class, 'index']);
Route::get('/searchPost', [PostController::class, 'show']);
Route::get('/post/{id}', [PostController::class, 'getPostById']);
Route::get('/years', [YearController::class, 'index']);
