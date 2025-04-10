<?php

use App\Http\Controllers\PostController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/posts', [PostController::class, 'index']);
Route::post('/createPost', [PostController::class, 'create']);
Route::get('/searchPost', [PostController::class, 'show']);
Route::get('/deletePost/{id}', [PostController::class, 'destroy']);
Route::get('/post/{id}', [PostController::class, 'getPostById']);