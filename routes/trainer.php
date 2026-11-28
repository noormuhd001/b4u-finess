<?php

use App\Http\Controllers\Trainer\AuthController;
use App\Http\Controllers\Trainer\PortalController;
use Illuminate\Support\Facades\Route;

Route::prefix('trainer')->name('trainer.')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:6,1')->name('login.submit');

    Route::middleware(['auth:trainer', 'activeTrainer'])->group(function () {
        Route::get('/dashboard', [PortalController::class, 'dashboard'])->name('dashboard');
        Route::get('/members/{user}', [PortalController::class, 'showMember'])->name('members.show');
        Route::post('/members/{user}/daily-plan', [PortalController::class, 'saveDailyPlan'])->name('members.daily-plan');
        Route::patch('/password', [PortalController::class, 'updatePassword'])->name('password.update');
        Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    });
});
