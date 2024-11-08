<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\AttendanceController;
use App\Http\Controllers\Api\V1\UserController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/v1/register', [AuthController::class, 'register'])->name('register');
Route::post('/v1/login', [AuthController::class, 'login'])->name('login');

Route::middleware('auth:sanctum')->group(function () {
    Route::prefix('v1')->group(function () {
        Route::get('attendances/work-schedules', [AttendanceController::class, 'workSchedules'])->name('attendances.work-schedules');
        Route::post('attendances/check-in', [AttendanceController::class, 'checkIn'])->name('attendances.check-in');
        Route::post('attendances/check-out', [AttendanceController::class, 'checkOut'])->name('attendances.check-out');
        Route::post('attendances/sick', [AttendanceController::class, 'sick'])->name('attendances.sick');
        Route::post('attendances/permit', [AttendanceController::class, 'permit'])->name('attendances.permit');
        Route::apiResource('users', UserController::class);
        Route::post('logout', [AuthController::class, 'logout'])->name('logout');
    });
});
