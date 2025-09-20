<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\Dahboard\DashboardController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('login');

Route::post('login', [AuthController::class, 'login'])->name('login.post');
Route::get('signup', [AuthController::class, 'signUp'])->name('signUp');
Route::post('signup.post',[AuthController::class,'register'])->name('register');

Route::prefix('')->middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
});
