<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AnnualHolidayController;
use App\Http\Controllers\WorkScheduleController;
use App\Http\Controllers\BankAccountController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\AttendanceReportController;
use App\Http\Controllers\LeaveController;
use App\Http\Controllers\BillingController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RoleController;

// Route::get("/", function () {
//     return view("welcome");
// });

Route::middleware(['guest'])->group(function () {
    Route::get('/', [AuthController::class, 'login'])->name('login');
    Route::get('/login', [AuthController::class, 'login'])->name('login');
    Route::post('/login', [AuthController::class, 'postLogin'])->name('postLogin');
});

Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get("/dashboard", function () {
        return view("dashboard.index");
    })->name("dashboard");

    Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::resource('annual-holidays', AnnualHolidayController::class);
    Route::post('annual-holidays/fetch-data-table', [AnnualHolidayController::class, 'fetchDataTable'])->name('annual-holidays.fetchDataTable');
    Route::resource('work-schedules', WorkScheduleController::class);
    Route::post('work-schedules/fetch-data-table', [WorkScheduleController::class, 'fetchDataTable'])->name('work-schedules.fetchDataTable');
    Route::resource('bank-accounts', BankAccountController::class);
    Route::post('bank-accounts/fetch-data-table', [BankAccountController::class, 'fetchDataTable'])->name('bank-accounts.fetchDataTable');
    Route::resource('attendances', AttendanceController::class);
    Route::post('attendances/fetch-data-table', [AttendanceController::class, 'fetchDataTable'])->name('attendances.fetchDataTable');
    Route::get('attendance-reports', [AttendanceReportController::class, 'index'])->name('attendance-reports.index');
    Route::post('attendance-reports/fetch-data-table', [AttendanceReportController::class, 'fetchDataTable'])->name('attendance-reports.fetchDataTable');
    Route::resource('leaves', LeaveController::class);
    Route::post('leaves/fetch-data-table', [LeaveController::class, 'fetchDataTable'])->name('leaves.fetchDataTable');
    Route::post('leaves/{leave}/response', [LeaveController::class, 'response'])->name('leaves.response');
    Route::resource('billings', BillingController::class);
    Route::post('billings/fetch-data-table', [BillingController::class, 'fetchDataTable'])->name('billings.fetchDataTable');
    Route::resource('users', UserController::class);
    Route::post('users/fetch-data-table', [UserController::class, 'fetchDataTable'])->name('users.fetchDataTable');
    Route::resource('roles', RoleController::class);
    Route::post('roles/fetch-data-table', [RoleController::class, 'fetchDataTable'])->name('roles.fetchDataTable');
});
