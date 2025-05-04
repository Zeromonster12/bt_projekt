<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\YearController;

Route::middleware('auth:sanctum')->put('/profile', [UserController::class, 'updateProfile']);
Route::middleware('auth:sanctum')->get('/profile', function (Request $request) {
    return response()->json([
        'name' => $request->user()->name,
        'email' => $request->user()->email,
        'role_id' => $request->user()->role_id,
    ]);
});

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('/createPost', [PostController::class, 'create']);
    Route::get('/deletePost/{id}', [PostController::class, 'destroy']);
    Route::get('/newestPost', [PostController::class, 'newestPost']);
    Route::get('/fetchUsers', [UserController::class, 'fetchUsers']);
    Route::post('/updateUser', [UserController::class, 'updateUser']);
    Route::get('/years', [YearController::class, 'index']);
    Route::post('/years', [YearController::class, 'store']);
    Route::put('/years/{id}', [YearController::class, 'update']);
    Route::delete('/years/{id}', [YearController::class, 'destroy']);
    Route::post('/createUser', [UserController::class, 'createUser']);
    Route::delete('/deleteUser/{id}', [UserController::class, 'deleteUser']);
});


//TODO: LOGIN
Route::post('/login', [UserController::class, 'login']);
Route::get('/posts', [PostController::class, 'index']);
Route::get('/searchPost', [PostController::class, 'show']);
Route::get('/post/{id}', [PostController::class, 'getPostById']);
Route::get('/years', [YearController::class, 'index']);

// Testing
// Route::post('/createYearUser', [YearController::class, 'createYearUser']);