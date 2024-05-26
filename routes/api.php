<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\PostController;
use App\Http\Controllers\Api\CommentController;
use App\Http\Controllers\Api\ForgotPasswordController;
use App\Http\Controllers\chatbotController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


    // User endpoints
    Route::get('/users', [UserController::class, 'index']);
    Route::get('/users/show/{user}', [UserController::class, 'show']);
    Route::post('/register', [UserController::class, 'store']);
    Route::post('/users/update/{user}', [UserController::class, 'update']);
    Route::delete('/users/delete/{user}', [UserController::class, 'destroy']);
    Route::post('/login', [UserController::class, 'login']);
    Route::get('/home', [UserController::class, 'showHome']);
    Route::get('/all-data', [UserController::class, 'getAllData']);
    Route::post('/change-password/{user}', [UserController::class, 'changePassword']);

    Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail']);
    Route::post('/reset-password', [ForgotPasswordController::class, 'reset']);

    // post endpoints
    Route::get('/posts', [PostController::class, 'index']);
    Route::get('/posts/show/{post}', [PostController::class, 'show']);
    Route::post('/posts/create', [PostController::class, 'store']);
    Route::post('/posts/update/{post}', [PostController::class, 'update']);
    Route::delete('/posts/delete/{post}', [PostController::class, 'destroy']);
    Route::post('/posts/upvote/{post}', [PostController::class, 'upvote']);
    Route::post('/posts/downvote/{post}', [PostController::class, 'downvote']);
    Route::get('/posts/comments/{postId}', [PostController::class, 'showCommentsByPost']);


    // comment endpoints
    Route::post('/comments/create', [CommentController::class, 'store']);
    Route::post('/comments/update/{comment}', [CommentController::class, 'update']);
    Route::post('/comments/delete/{comment}', [CommentController::class, 'destroy']);
    Route::get('comments/post/{postId}', [CommentController::class, 'showCommentsByPost']);


