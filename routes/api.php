<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\AttendanceController;
use App\Http\Controllers\Api\V1\LeaveController;
use App\Http\Controllers\Api\V1\BillingController;
use App\Http\Controllers\Api\V1\BillingStatusController;
use App\Http\Controllers\Api\V1\BillingReportController;
use App\Http\Controllers\Api\V1\UserController;
use App\Http\Controllers\Api\V1\ProfileController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::prefix('v1')->group(function () {
    Route::post('/register', [AuthController::class, 'register'])->name('register');
    Route::post('/login', [AuthController::class, 'login'])->name('login');

    Route::middleware('auth:sanctum')->group(function () {
        Route::get('attendances/work-schedules', [AttendanceController::class, 'workSchedules'])->name('attendances.work-schedules');
        Route::post('attendances/check-in', [AttendanceController::class, 'checkIn'])->name('attendances.check-in');
        Route::post('attendances/check-out', [AttendanceController::class, 'checkOut'])->name('attendances.check-out');
        Route::post('attendances/sick', [AttendanceController::class, 'sick'])->name('attendances.sick');
        Route::post('attendances/permit', [AttendanceController::class, 'permit'])->name('attendances.permit');
        Route::post('leaves/submission', [LeaveController::class, 'submission'])->name('leaves.submission');
        Route::get('leaves', [LeaveController::class, 'index'])->name('leaves.index');
        Route::apiResource('billings', BillingController::class);
        Route::apiResource('billing-statuses', BillingStatusController::class);
        Route::get('billing-reports/export-pdf/by-user', [BillingReportController::class, 'exportPdfByUser'])->name('billing-reports.export-pdf.by-user');
        Route::get('billing-reports/export-pdf/by-customer', [BillingReportController::class, 'exportPdfByCustomer'])->name('billing-reports.export-pdf.by-customer');
        Route::apiResource('billing-reports', BillingReportController::class);
        Route::apiResource('users', UserController::class);
        Route::get('profile', [ProfileController::class, 'index'])->name('profile');
        Route::put('profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::put('profile/update-password', [ProfileController::class, 'updatePassword'])->name('profile.update-password');
        Route::post('logout', [AuthController::class, 'logout'])->name('logout');
    });
});
