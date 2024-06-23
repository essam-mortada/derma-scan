<?php

use App\Http\Controllers\adminController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\chatbotController;
use App\Http\Controllers\commentController;
use App\Http\Controllers\ForgotPasswordController;
use App\Http\Controllers\notificationController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\predictController;
use App\Http\Controllers\userController;
use Illuminate\Support\Facades\Route;
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////
Route::get('/result', function () {
    return view('prediction_result');
});
// In your routes/web.php file

Route::get('/', function () {
    return view('welcome');
})->name('welcome');
Route::group(['middleware' => 'auth'],function () {
    Route::get('/home', 'App\Http\Controllers\userController@showHome')->name('home');
// Catch-all route for undefined routes
Route::fallback(function () {
    return response()->view('not-found', [], 404);
});

    Route::post('/logout', 'App\Http\Controllers\userController@logout')->name('logout');

    Route::get('/community', 'App\Http\Controllers\userController@showCommunity')->name('community');

/////////////////////////////////////////////////////////////////////////////////////////////////
// authorized user
/////////////////////////////////////////////////////////////////////////////////////////////////
Route::get('/profile/show/{user}', [userController::class, 'show'])->name('users.show');
Route::get('/profile/{user}/edit', [userController::class, 'edit'])->name('users.edit');
Route::put('/profile/{user}', [userController::class, 'update'])->name('users.update');
Route::get('/change-password/profile/{user}', [UserController::class, 'showChangePasswordForm'])->name('password.change.form');
Route::post('/change-password/{user}', [UserController::class, 'changePassword'])->name('password.change');

Route::get('/predict', [predictController::class, 'showPredictView'])->name('predict.index');
Route::get('/predict.', [predictController::class, 'showPredictCursoul'])->name('predict.cursoul');
Route::get('/predict/form', [predictController::class, 'showPredictForm'])->name('predict.form');
Route::post('/predict/post', [predictController::class, 'predict'])->name('predict');

Route::get('/make-appointment', [AppointmentController::class, 'create'])->name('appointments.from');
Route::post('/make-appointment', [AppointmentController::class, 'store'])->name('appointments.store');

Route::get('/all-appointments/{userId}', [AppointmentController::class, 'userAppointments'])->name('appointments.user');


/////////////////////////////////////////////////////////////////////////////////////////////////
//admin
/////////////////////////////////////////////////////////////////////////////////////////////////

Route::get('/admin/users', [adminController::class, 'showUsers'])->name('admin.users');
Route::get('/users/search', [userController::class, 'search'])->name('users.search');
Route::get('/posts/search', [PostController::class, 'search'])->name('posts.search');
Route::get('/comments/search', [commentController::class, 'search'])->name('comments.search');

Route::get('/admin/{user}/edit', [adminController::class, 'edit'])->name('admin.edit');
Route::put('/admin/{user}', [adminController::class, 'update'])->name('admin.update');
Route::get('/change-password/{user}', [adminController::class, 'showChangePasswordForm'])->name('password.change.admin');

Route::get('/admin/doctors', [adminController::class, 'showDoctors'])->name('admin.doctor-requests');
Route::put('/users/{user}/approve', [adminController::class, 'approve'])->name('users.approve');
Route::put('/users/{user}/decline', [adminController::class, 'decline'])->name('users.decline');
Route::post('/user/{id}', 'App\Http\Controllers\adminController@deleteUser')->name('user.delete');
Route::get('/admin/posts', [adminController::class, 'showPosts'])->name('admin.posts');
Route::post('admin/posts/{post}', [adminController::class, 'deletePost'])->name('posts.delete');

Route::get('/admin/comments', [adminController::class, 'showComments'])->name('admin.comments');
Route::post('admin/comments/{comment}', [adminController::class, 'deleteComment'])->name('comments.delete');

Route::get('/add/admin', 'App\Http\Controllers\adminController@showAddAdminForm')->name('add-admin');
Route::post('/add/admin', 'App\Http\Controllers\adminController@addAdmin')->name('add-admin-post');


Route::get('/admin/clinics', [adminController::class, 'indexClinic'])->name('clinics.index');
Route::get('/admin/clinics/create', [adminController::class, 'createClinic'])->name('clinics.create');
Route::post('admin/clinics/create', [adminController::class, 'storeClinic'])->name('clinics.store');

Route::get('/admin/clinics/show/{clinic}', [adminController::class, 'showClinic'])->name('clinics.show');
Route::get('/admin/clinics/edit/{clinic}', [adminController::class, 'editClinic'])->name('clinics.edit');
Route::post('admin/clinics/update{clinic}', [adminController::class, 'updateClinic'])->name('clinics.update');
Route::delete('admin/clinics/destroy/{clinic}', [adminController::class, 'destroyClinic'])->name('clinics.destroy');


Route::get('/admin/appointments', [adminController::class, 'showAppointments'])->name('admin.appointments');
Route::post('admin/appointments/destroy/{appointment}', [adminController::class, 'deleteAppointment'])->name('appointments.delete');

///////////////////////////////////////////////////////////////////
///posts///
///////////////////////////////////////////////////////////////////

Route::get('/posts/create', [PostController::class, 'create'])->name('posts.create');
Route::post('/posts', [PostController::class, 'store'])->name('posts.store');

Route::get('/posts/{post}/edit', [PostController::class, 'edit'])->name('posts.edit');
Route::put('/posts/{post}', [PostController::class, 'update'])->name('posts.update');

Route::post('/posts/{post}', [PostController::class, 'destroy'])->name('posts.destroy');
Route::post('posts/{postId}/upvote', [PostController::class, 'upvote'])->name('posts.upvote');
Route::post('posts/{postId}/downvote', [PostController::class, 'downvote'])->name('posts.downvote');
Route::get('/posts/show/{post}', [PostController::class, 'show'])->name('posts.show');

////////////////////////////////////////////////////////////////
//comments
/////////////////////////////////////////////////////////////////

Route::post('/comment', [commentController::class, 'store'])->name('comment.store');
Route::post('/comment/{comment}', [commentController::class, 'destroy'])->name('comment.destroy');
Route::get('/comment/{comment}/edit', [commentController::class, 'edit'])->name('comment.edit');
Route::put('/comment/{comment}', [commentController::class, 'update'])->name('comment.update');

Route::get('/notifications', [notificationController::class, 'index'])->name('notifications.index');
Route::post('/notifications/read', [NotificationController::class, 'markAsRead'])->name('notifications.markAsRead');
////////////////////////////////////////////////////////////////////////////////////////////////////
//chatbot
////////////////////////////////////////////////////////////////////////////////////////////////////

Route::get('/chatbot', [chatbotController::class, 'index'])->name('chatbot');
});

//login and register
Route::get('/register', 'App\Http\Controllers\userController@showRegistrationForm')->name('register');
Route::post('/register', 'App\Http\Controllers\userController@register');

Route::get('/login', 'App\Http\Controllers\userController@showLoginForm')->name('login');
Route::post('/login', 'App\Http\Controllers\userController@login');


Route::get('password/reset', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
Route::post('password/email', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
Route::get('password/reset/{token}', [ForgotPasswordController::class, 'showResetForm'])->name('password.reset');
Route::post('password/reset', [ForgotPasswordController::class, 'reset'])->name('password.update');




