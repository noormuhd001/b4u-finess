<?php

use App\Http\Controllers\API\ApiController;
use Illuminate\Support\Facades\Route;

Route::prefix('/')->group(function () {

    Route::post('/login',[ApiController::class,'login']);
    Route::get('/get-all-workouts', [ApiController::class, 'index']);
    Route::get('/get-dashboard-details', [ApiController::class, 'dashboardDetails']);
    Route::post('/store-workout', [ApiController::class, 'store']);
    Route::post('/destroy-workout/{id}', [ApiController::class, 'destroy']);
    Route::get('/get-all-users', [ApiController::class, 'getAllUsers']);
    Route::post('/user-update-status/{id}',[ApiController::class,'userUpdateStatus']);
});
