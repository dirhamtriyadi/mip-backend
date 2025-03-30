<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\AttendanceController;
use App\Http\Controllers\Api\V1\LeaveController;
use App\Http\Controllers\Api\V1\CustomerBillingController;
use App\Http\Controllers\Api\V1\BillingFollowupController;
use App\Http\Controllers\Api\V1\BillingStatusController;
use App\Http\Controllers\Api\V1\BillingReportController;
use App\Http\Controllers\Api\V1\CustomerBillingReportController;
use App\Http\Controllers\Api\V1\ProspectiveCustomerController;
use App\Http\Controllers\Api\V1\ProspectiveCustomerSurveyController;
use App\Http\Controllers\Api\V1\UserController;
use App\Http\Controllers\Api\V1\ProfileController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::prefix('v1')->group(function () {
    Route::post('/register', [AuthController::class, 'register'])->name('register');
    Route::post('/login', [AuthController::class, 'login'])->name('login');

    Route::middleware('auth:sanctum')->group(function () {
        // Attendance
        Route::get('attendances/work-schedules', [AttendanceController::class, 'workSchedules'])->name('attendances.work-schedules');
        Route::post('attendances/check-in', [AttendanceController::class, 'checkIn'])->name('attendances.check-in');
        Route::post('attendances/check-out', [AttendanceController::class, 'checkOut'])->name('attendances.check-out');
        Route::post('attendances/sick', [AttendanceController::class, 'sick'])->name('attendances.sick');
        Route::post('attendances/permit', [AttendanceController::class, 'permit'])->name('attendances.permit');

        // Leaves
        Route::post('leaves/submission', [LeaveController::class, 'submission'])->name('leaves.submission');
        Route::get('leaves', [LeaveController::class, 'index'])->name('leaves.index');

        // Customer Billing
        Route::get('customer-billings', [CustomerBillingController::class, 'index'])->name('customer-billings.index');
        Route::get('customer-billings/{id}', [CustomerBillingController::class, 'show'])->name('customer-billings.show');

        // Billing Followup
        Route::post('billing-followups', [BillingFollowupController::class, 'store'])->name('billing-followups.store');

        // Customer Billing Report
        Route::get('customer-billing-reports', [CustomerBillingReportController::class, 'index'])->name('customer-billing-reports.index');
        Route::get('customer-billing-reports/{id}', [CustomerBillingReportController::class, 'show'])->name('customer-billing-reports.show');
        Route::get('customer-billing-reports/export-pdf/by-user', [CustomerBillingReportController::class, 'exportPdfByUser'])->name('customer-billing-reports.export-pdf.by-user');
        Route::get('customer-billing-reports/export-pdf/by-customer', [CustomerBillingReportController::class, 'exportPdfByCustomer'])->name('customer-billing-reports.export-pdf.by-customer');

        // Prospective Customer
        Route::post('prospective-customers', [ProspectiveCustomerController::class, 'store'])->name('prospective-customers.store');

        // Prospective Customer Survey
        Route::get('prospective-customer-surveys', [ProspectiveCustomerSurveyController::class, 'index'])->name('prospective-customer-surveys.index');
        Route::get('prospective-customer-surveys/{id}', [ProspectiveCustomerSurveyController::class, 'show'])->name('prospective-customer-surveys.show');
        Route::put('prospective-customer-surveys/{id}', [ProspectiveCustomerSurveyController::class, 'update'])->name('prospective-customer-surveys.update');
        Route::get('prospective-customer-surveys/{id}/export-pdf-by-customer', [ProspectiveCustomerSurveyController::class, 'exportPdfByCustomer']);

        // User
        Route::apiResource('users', UserController::class);

        // Profile
        Route::get('profile', [ProfileController::class, 'index'])->name('profile');
        Route::put('profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::put('profile/update-password', [ProfileController::class, 'updatePassword'])->name('profile.update-password');

        // Auth
        Route::post('logout', [AuthController::class, 'logout'])->name('logout');
    });
});
