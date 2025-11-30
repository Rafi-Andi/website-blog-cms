<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\CommentLikeController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::prefix('auth')->group(function () {
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);
});

Route::middleware('auth:sanctum')->group(function () {
    Route::post('auth/logout', [AuthController::class, 'logout']); 

    Route::apiResource('category', CategoryController::class)->only(['index','store', 'update', 'destroy']);
    Route::apiResource('post', PostController::class);
    Route::get('myposts', [PostController::class, 'MyPosts']);
    Route::apiResource('comment', CommentController::class);

    Route::post('comment/{id}/like', [CommentLikeController::class, 'likeComment']);
    Route::delete('comment/{id}/unlike', [CommentLikeController::class, 'unlikeComment']);
});
