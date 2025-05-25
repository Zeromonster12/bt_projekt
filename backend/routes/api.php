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
    Route::middleware(['auth:sanctum', 'role:admin'])->get('/admin-route', function() {
        return response()->json(['message' => 'Admin access granted']);
    });
    Route::middleware(['auth:sanctum', 'role:editor'])->get('/editor-route', function() {
    return response()->json(['message' => 'Editor access granted']);
});
    Route::get('/user', [UserController::class, 'getUser']);
    Route::get('/years', [YearController::class, 'index']);
    Route::middleware(['auth:sanctum'])->group(function () {
        Route::put('/profile', [UserController::class, 'updateProfile']);
        Route::post('/upload-pfp', [ImageUploadController::class, 'uploadpfp']);
        Route::get('/profile', function (Request $request) {
            return response()->json([
                'name' => $request->user()->name,
                'email' => $request->user()->email,
                'role_id' => $request->user()->role_id,
            ]);
        });
        Route::post('/createPost', [PostController::class, 'create']);
        Route::get('/deletePost/{id}', [PostController::class, 'destroy']);
        Route::get('/newestPost', [PostController::class, 'newestPost']);
        Route::get('/fetchUsers', [UserController::class, 'fetchUsers']);
        Route::post('/updateUser', [UserController::class, 'updateUser']);
        Route::post('/createUser', [UserController::class, 'createUser']);
        Route::delete('/deleteUser/{id}', [UserController::class, 'deleteUser']);
        Route::post('/logout', [UserController::class, 'logout']);
        Route::post('/upload-image', [ImageUploadController::class, 'upload']);
        Route::post('/upload-file', [FileUploadController::class, 'upload']);
        
       
        Route::post('/years', [YearController::class, 'store']);
        Route::put('/years/{id}', [YearController::class, 'update']);
        Route::delete('/years/{id}', [YearController::class, 'destroy']);
    });

    Route::get('/download-file/{filename}', [FileUploadController::class, 'download'])->name('file.download');

    Route::post('/login', [UserController::class, 'login']);
    Route::get('/posts', [PostController::class, 'index']);
    Route::get('/searchPost', [PostController::class, 'show']);
    Route::get('/post/{id}', [PostController::class, 'getPostById']);
});