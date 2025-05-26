<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\FileUploadController;
use App\Http\Controllers\ImageUploadController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\YearController;



Route::middleware(['web'])->group(function () {

    Route::middleware(['auth:sanctum', 'role:admin'])->group(function() {
        Route::get('/fetchUsers', [UserController::class, 'fetchUsers']);
        Route::post('/createUser', [UserController::class, 'createUser']);
        Route::post('/updateUser', [UserController::class, 'updateUser']);
        Route::delete('/deleteUser/{id}', [UserController::class, 'deleteUser']);

        Route::post('/years', [YearController::class, 'store']);
        Route::put('/years/{id}', [YearController::class, 'update']);
        Route::delete('/years/{id}', [YearController::class, 'destroy']);
    });

    Route::middleware(['auth:sanctum', 'role:admin,editor'])->group(function () {
        Route::post('/upload-image', [ImageUploadController::class, 'upload']);
        Route::post('/upload-file', [FileUploadController::class, 'upload']);
        Route::post('/createPost', [PostController::class, 'create']);
        Route::put('/posts/{id}', [PostController::class, 'update']);
        Route::get('/deletePost/{id}', [PostController::class, 'destroy']);
        Route::get('/newestPost', [PostController::class, 'newestPost']);
        Route::post('/logout', [UserController::class, 'logout']);
    });

    Route::middleware(['auth:sanctum'])->group(function () {
        Route::put('/profile', [UserController::class, 'updateProfile']);
        Route::post('/upload-pfp', [ImageUploadController::class, 'uploadpfp']);
        Route::get('/profile', [UserController::class, 'getProfile']);
        Route::get('/user', [UserController::class, 'getUser']);
    });
 
    Route::post('/login', [UserController::class, 'login']);
    Route::get('/posts', [PostController::class, 'index']);
    Route::get('/post/{id}', [PostController::class, 'getPostById']);
    Route::get('/searchPost', [PostController::class, 'show']);
    Route::get('/download-file/{filename}', [FileUploadController::class, 'download'])->name('file.download');
    Route::get('/years', [YearController::class, 'index']);
    
});