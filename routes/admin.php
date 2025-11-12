<?php

use App\Http\Controllers\Admin\DashboardController;
use Illuminate\Support\Facades\Route;

Route::middleware('Admin', 'preventHistory')->group(function () {
    Route::prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        // Route::get('/workout', [WorkoutController::class, 'index'])->name('workout');
        // Route::get('/about-us', [AboutUsController::class, 'index'])->name('about-us');
        // Route::get('/settings', [SettingController::class, 'index'])->name('settings');

        // Route::prefix('settings')->name('settings.')->group(function () {
        //     Route::get('/', [SettingController::class, 'index'])->name('index');
        //     Route::post('/dark-mode', [SettingController::class, 'updateMode'])->name('darkmode');
        // });

        // Route::prefix('progress')->name('progress.')->group(function () {
        //     Route::get('/', [ProgressController::class, 'index'])->name('index');
        //     Route::post('/store', [ProgressController::class, 'store'])->name('store');
        //     Route::get('/track', [ProgressController::class, 'track'])->name('track');
        //     Route::get('/track/{progress}', [ProgressController::class, 'trackDetailById'])->name('trackDetailById');
        //     Route::get('/export', [ProgressController::class, 'export'])->name('export');
        // });

        // Route::prefix('profile')->name('profile.')->group(function () {
        //     Route::get('/', [ProfileController::class, 'index'])->name('index');
        //     Route::get('/edit/{id}', [ProfileController::class, 'edit'])->name('edit');
        //     Route::post('/update', [ProfileController::class, 'update'])->name('update');
        // });
    });
});
