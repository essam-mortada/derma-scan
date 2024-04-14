<?php

use App\Http\Controllers\commentController;
use App\Http\Controllers\PostController;
use Illuminate\Support\Facades\Route;
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////

// In your routes/web.php file

Route::get('/', function () {
    return view('welcome');
})->name('welcome');
Route::group(['middleware' => 'auth'],function () {
    Route::get('/home', 'App\Http\Controllers\userController@showHome')->name('home');

    Route::post('/logout', 'App\Http\Controllers\userController@logout')->name('logout');

    
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////



Route::post('/user/{id}', 'App\Http\Controllers\adminController@deleteUser')->name('user.delete');

Route::get('/add/admin', 'App\Http\Controllers\adminController@showAddAdminForm')->name('add-admin');
Route::post('/add/admin', 'App\Http\Controllers\adminController@addAdmin')->name('add-admin-post');

//posts




Route::get('/posts/create', [PostController::class, 'create'])->name('posts.create');
Route::post('/posts', [PostController::class, 'store'])->name('posts.store');

Route::get('/posts/{post}/edit', [PostController::class, 'edit'])->name('posts.edit');
Route::put('/posts/{post}', [PostController::class, 'update'])->name('posts.update');

Route::delete('/posts/{post}', [PostController::class, 'destroy'])->name('posts.destroy');

//comments
Route::post('/comment', [commentController::class, 'store'])->name('comment.store');
});

//user
Route::get('/register', 'App\Http\Controllers\userController@showRegistrationForm')->name('register');
Route::post('/register', 'App\Http\Controllers\userController@register');

Route::get('/login', 'App\Http\Controllers\userController@showLoginForm')->name('login');
Route::post('/login', 'App\Http\Controllers\userController@login');
