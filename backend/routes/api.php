<?php

use App\Http\Controllers\PostController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::middleware(['auth:sanctum', 'role:Admin'])->group(function () {
    Route::post('/createPost', [PostController::class, 'create']);
    Route::get('/deletePost/{id}', [PostController::class, 'destroy']);
});

Route::middleware(['auth:sanctum', 'role:Editor'])->group(function () {
    Route::get('/posts', [PostController::class, 'index']);
    Route::get('/searchPost', [PostController::class, 'show']);
    Route::get('/post/{id}', [PostController::class, 'getPostById']);
});

//TODO: LOGIN
// Route::middleware(['auth:sanctum', 'role:Anonym'])->group(function () {
Route::get('/posts', [PostController::class, 'index']);
Route::get('/searchPost', [PostController::class, 'show']);
Route::get('/post/{id}', [PostController::class, 'getPostById']);
// });