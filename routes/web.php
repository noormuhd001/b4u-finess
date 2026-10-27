<?php

use App\Http\Controllers\About\AboutUsController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Dahboard\DashboardController;
use App\Http\Controllers\Profile\UserProfileController;
use App\Http\Controllers\Progress\ProgressController;
use App\Http\Controllers\Settings\SettingController;
use App\Http\Controllers\Workout\WorkoutController;
use App\Models\MembershipPlan;
use Illuminate\Support\Facades\Route;

include 'api.php';
Route::get('/', function () {
    $membershipPlans = MembershipPlan::query()
        ->where('is_active', true)
        ->orderBy('price')
        ->get();

    return view('welcome', compact('membershipPlans'));
})->name('home');

Route::get('/login', function () {
    return view('auth.login');
})->name('login');

Route::get('/about-us', [AboutUsController::class, 'index'])->name('about-us');

Route::post('login', [AuthController::class, 'login'])->name('login.post');
Route::get('signup', [AuthController::class, 'signUp'])->name('signUp');
Route::post('signup.post', [AuthController::class, 'register'])->name('register');
Route::get('verify-registration', [AuthController::class, 'showRegistrationVerification'])->name('registration.verify');
Route::post('verify-registration', [AuthController::class, 'verifyRegistration'])->middleware('throttle:5,1')->name('registration.verify.submit');
Route::post('verify-registration/resend', [AuthController::class, 'resendRegistrationOtp'])->middleware('throttle:3,10')->name('registration.verify.resend');
Route::get('forgot-password', [AuthController::class, 'forgotPassword'])->name('forgotPassword');
Route::post('send-otp', [AuthController::class, 'sendOtp'])->name('sendOtp');
Route::get('reset-password/{token}', [AuthController::class, 'resetPassword'])->name('resetPassword');
Route::post('update-password', [AuthController::class, 'updatePassword'])->name('updatePassword');
Route::get('privacy-policy', [AuthController::class, 'privacyPolicy'])->name('privacyPolicy');
Route::get('terms-and-conditions', [AuthController::class, 'termsAndConditions'])->name('termsAndConditions');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

include 'admin.php';
Route::prefix('')->middleware('auth', 'activeUser', 'preventHistory')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/attendance', [AttendanceController::class, 'index'])->name('attendance.index');
    Route::post('/attendance/check-in', [AttendanceController::class, 'checkIn'])
        ->middleware('throttle:3,1')
        ->name('attendance.check-in');
    Route::get('/workout', [WorkoutController::class, 'index'])->name('workout');
    Route::get('/settings', [SettingController::class, 'index'])->name('settings');

    Route::prefix('settings')->name('settings.')->group(function () {
        Route::get('/', [SettingController::class, 'index'])->name('index');
        Route::post('/dark-mode', [SettingController::class, 'updateMode'])->name('darkmode');
    });

    Route::prefix('progress')->name('progress.')->group(function () {
        Route::get('/', [ProgressController::class, 'index'])->name('index');
        Route::post('/store', [ProgressController::class, 'store'])->name('store');
        Route::get('/track', [ProgressController::class, 'track'])->name('track');
        Route::get('/track/{progress}', [ProgressController::class, 'trackDetailById'])->name('trackDetailById');
        Route::get('/export', [ProgressController::class, 'export'])->name('export');
    });

    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/', [UserProfileController::class, 'index'])->name('index');
        Route::post('/update', [UserProfileController::class, 'update'])->name('update');
    });
});
