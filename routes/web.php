<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\Dahboard\DashboardController;
use App\Http\Controllers\Profile\ProfileController;
use App\Http\Controllers\Progress\ProgressController;
use App\Http\Controllers\Workout\WorkoutController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('login');

Route::post('login', [AuthController::class, 'login'])->name('login.post');
Route::get('signup', [AuthController::class, 'signUp'])->name('signUp');
Route::post('signup.post', [AuthController::class, 'register'])->name('register');
Route::get('forgot-password', [AuthController::class, 'forgotPassword'])->name('forgotPassword');
Route::post('send-otp', [AuthController::class, 'sendOtp'])->name('sendOtp');
Route::get('reset-password/{token}', [AuthController::class, 'resetPassword'])->name('resetPassword');
Route::post('update-password', [AuthController::class, 'updatePassword'])->name('updatePassword');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::prefix('')->middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/workout', [WorkoutController::class, 'index'])->name('workout');

    Route::prefix('progress')->name('progress.')->group(function () {
        Route::get('/', [ProgressController::class, 'index'])->name('index');
        Route::post('/store', [ProgressController::class, 'store'])->name('store');
        Route::get('/track', [ProgressController::class, 'track'])->name('track');
        Route::get('/track/{progress}', [ProgressController::class, 'trackDetailById'])->name('trackDetailById');
        Route::get('/export', [ProgressController::class, 'export'])->name('export');
    });

    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/', [ProfileController::class, 'index'])->name('index');
        Route::get('/edit/{id}', [ProfileController::class, 'edit'])->name('edit');
        Route::post('/update', [ProfileController::class, 'update'])->name('update');
    });
});
