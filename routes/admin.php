<?php

use App\Http\Controllers\Admin\BadgeManagementController;
use App\Http\Controllers\Admin\AttendanceManagementController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\MembershipPlanController;
use App\Http\Controllers\Admin\ProgressController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\UserManagementController;
use App\Http\Controllers\Admin\WorkoutManagementController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'activeUser', 'Admin', 'preventHistory'])->group(function () {
    Route::prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        Route::get('/attendance', [AttendanceManagementController::class, 'index'])->name('attendance.index');
        Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
        Route::post('/profile/update', [ProfileController::class, 'update'])->name('profile.update');
        // Route::get('/workout', [WorkoutController::class, 'index'])->name('workout');
        // Route::get('/about-us', [AboutUsController::class, 'index'])->name('about-us');
        // Route::get('/settings', [SettingController::class, 'index'])->name('settings');

        // Route::prefix('settings')->name('settings.')->group(function () {
        //     Route::get('/', [SettingController::class, 'index'])->name('index');
        //     Route::post('/dark-mode', [SettingController::class, 'updateMode'])->name('darkmode');
        // });

        Route::prefix('progress')->name('progress.')->group(function () {
            Route::put('/{progress}', [ProgressController::class, 'update'])->name('update');
            Route::put('/logs/{progressLog}', [ProgressController::class, 'updateLog'])->name('logs.update');
        });

        Route::prefix('user-management')->name('user-management.')->group(function () {
            Route::get('/index', [UserManagementController::class, 'index'])->name('index');
            Route::get('/{user}', [UserManagementController::class, 'show'])->name('show');
            Route::post('/index', [UserManagementController::class, 'store'])->name('store');
            Route::put('/{user}', [UserManagementController::class, 'update'])->name('update');
            Route::patch('/{user}/status', [UserManagementController::class, 'updateStatus'])->name('status');
            Route::patch('/{user}/payment', [UserManagementController::class, 'updatePaymentStatus'])->name('payment');
            Route::patch('/{user}/renew', [UserManagementController::class, 'renewMembership'])->name('renew');
        });

        Route::prefix('membership-plans')->name('membership-plans.')->controller(MembershipPlanController::class)->group(function () {
            Route::get('/', 'index')->name('index');
            Route::post('/', 'store')->name('store');
            Route::put('/{membershipPlan}', 'update')->name('update');
            Route::patch('/{membershipPlan}/status', 'updateStatus')->name('status');
        });

        Route::prefix('workouts')->name('workouts.')->controller(WorkoutManagementController::class)->group(function () {
            Route::get('/', 'index')->name('index');
            Route::post('/', 'store')->name('store');
            Route::put('/{workout}', 'update')->name('update');
        });

        Route::prefix('badges')->name('badges.')->controller(BadgeManagementController::class)->group(function () {
            Route::get('/', 'index')->name('index');
            Route::put('/{badge}', 'update')->name('update');
        });
    });
});
