<?php

use App\Http\Controllers\API\AppointmentController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\PostController;
use App\Http\Controllers\Api\CommentController;
use App\Http\Controllers\Api\ForgotPasswordController;
use App\Http\Controllers\Api\PredictController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


    // User endpoints
    Route::get('/users', [UserController::class, 'index']);
    Route::get('/users/show/{user}', [UserController::class, 'show']);
    Route::get('/users/posts/{user}', [UserController::class, 'getUserPosts']);
    Route::post('/register', [UserController::class, 'store']);
    Route::post('/users/update/{user}', [UserController::class, 'update']);
    Route::delete('/users/delete/{user}', [UserController::class, 'destroy']);
    Route::post('/login', [UserController::class, 'login']);
    Route::post('/logout', [UserController::class, 'logout']);
    Route::get('/home', [UserController::class, 'showHome']);
    Route::get('/all-data', [UserController::class, 'getAllData']);
    Route::post('/change-password/{user}', [UserController::class, 'changePassword']);
    Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail']);
    Route::post('/reset-password', [ForgotPasswordController::class, 'reset']);

    Route::post('/predict', [PredictController::class, 'predict']);
    // post endpoints
    Route::get('/posts', [PostController::class, 'index']);
    Route::get('/posts/show/{post}', [PostController::class, 'show']);
    Route::post('/posts/create', [PostController::class, 'store']);
    Route::post('/posts/update/{post}', [PostController::class, 'update']);
    Route::delete('/posts/delete/{post}', [PostController::class, 'destroy']);
    Route::post('/posts/upvote/{post}', [PostController::class, 'upvote']);
    Route::post('/posts/downvote/{post}', [PostController::class, 'downvote']);
    Route::get('/posts/comments/{postId}', [PostController::class, 'showCommentsByPost']);
    Route::post('/posts/search', [PostController::class, 'search']);
    Route::get('/posts/commentsCount/{postId}', [PostController::class, 'getCommentsCount']);
    Route::get('/posts/upvotes/{postId}', [PostController::class, 'getUpvotes']);
    Route::get('/posts/downvotes/{postId}', [PostController::class, 'getDownvotes']);


    // comment endpoints
    Route::post('/comments/create', [CommentController::class, 'store']);
    Route::post('/comments/update/{comment}', [CommentController::class, 'update']);
    Route::delete('/comments/delete/{comment}', [CommentController::class, 'destroy']);
    Route::get('comments/post/{postId}', [CommentController::class, 'showCommentsByPost']);


    // appointment endpoints
    Route::get('clinics', [AppointmentController::class, 'getClinics']);
    Route::post('appointments', [AppointmentController::class, 'storeAppointment']); 
    Route::get('user/appointments/{userId}', [AppointmentController::class, 'getUserAppointments']);
