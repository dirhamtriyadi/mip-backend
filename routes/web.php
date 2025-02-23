<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AnnualHolidayController;
use App\Http\Controllers\WorkScheduleController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\CustomerBillingController;
use App\Http\Controllers\CustomerBillingReportController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\AttendanceReportController;
use App\Http\Controllers\OfficerReportController;
use App\Http\Controllers\LeaveController;
use App\Http\Controllers\BankContoller;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\ProfileController;

// Route khusus guest
Route::middleware(['guest'])->group(function () {
    Route::get('/', [AuthController::class, 'login'])->name('login');
    Route::get('/login', [AuthController::class, 'login'])->name('login');
    Route::post('/login', [AuthController::class, 'postLogin'])->name('postLogin');
});

// Route khusu users yang sudah login
Route::middleware('auth')->group(function () {
    Route::get("/dashboard", function () {
        return view("dashboard.index");
    })->name("dashboard");

    // Route logout
    Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

    // Route hari libur
    Route::resource('annual-holidays', AnnualHolidayController::class);
    Route::post('annual-holidays/fetch-data-table', [AnnualHolidayController::class, 'fetchDataTable'])->name('annual-holidays.fetchDataTable');

    // Route jam kerja
    Route::resource('work-schedules', WorkScheduleController::class);
    Route::post('work-schedules/fetch-data-table', [WorkScheduleController::class, 'fetchDataTable'])->name('work-schedules.fetchDataTable');

    // Route customer
    Route::resource('customers', CustomerController::class);
    Route::post('customers/fetch-data-table', [CustomerController::class, 'fetchDataTable'])->name('customers.fetchDataTable');

    // Route absensi
    Route::resource('attendances', AttendanceController::class);
    Route::post('attendances/fetch-data-table', [AttendanceController::class, 'fetchDataTable'])->name('attendances.fetchDataTable');
    Route::get('attendance-reports', [AttendanceReportController::class, 'index'])->name('attendance-reports.index');
    Route::post('attendance-reports/fetch-data-table', [AttendanceReportController::class, 'fetchDataTable'])->name('attendance-reports.fetchDataTable');
    Route::get('attendance-reports/export', [AttendanceReportController::class, 'export'])->name('attendance-reports.export');
    Route::get('attendance-reports/export-by-user', [AttendanceReportController::class, 'exportByUser'])->name('attendance-reports.exportByUser');
    Route::get('attendance-reports/export-pdf', [AttendanceReportController::class, 'exportPdf'])->name('attendance-reports.exportPdf');

    // Route cuti
    Route::resource('leaves', LeaveController::class);
    Route::post('leaves/fetch-data-table', [LeaveController::class, 'fetchDataTable'])->name('leaves.fetchDataTable');
    Route::post('leaves/{leave}/response', [LeaveController::class, 'response'])->name('leaves.response');

    // Route bank
    Route::resource('banks', BankContoller::class);
    Route::post('banks/fetch-data-table', [BankContoller::class, 'fetchDataTable'])->name('banks.fetchDataTable');

    // Route tagihan
    Route::get('customer-billings/template-import', [CustomerBillingController::class, 'templateImport'])->name('customer-billings.templateImport');
    Route::resource('customer-billings', CustomerBillingController::class);
    Route::post('customer-billings/fetch-data-table', [CustomerBillingController::class, 'fetchDataTable'])->name('billing.fetchDataTable');
    Route::post('customer-billings/import', [CustomerBillingController::class, 'import'])->name('customer-billings.import');
    Route::post('customer-billings/mass-delete', [CustomerBillingController::class, 'massDelete'])->name('customer-billings.massDelete');
    Route::post('customer-billings/mass-select-officer', [CustomerBillingController::class, 'massSelectOfficer'])->name('customer-billings.massSelectOfficer');
    Route::get('customer-billing-reports', [CustomerBillingReportController::class, 'index'])->name('customer-billing-reports.index');
    Route::post('customer-billing-reports/fetch-data-table', [CustomerBillingReportController::class, 'fetchDataTable'])->name('customer-billing-reports.fetchDataTable');
    Route::get('customer-billing-reports/export', [CustomerBillingReportController::class, 'export'])->name('customer-billing-reports.export');

    // Route laporan petugas
    Route::get('officer-reports', [OfficerReportController::class, 'index'])->name('officer-reports.index');
    Route::post('officer-reports/fetch-data-table', [OfficerReportController::class, 'fetchDataTable'])->name('officer-reports.fetchDataTable');
    Route::get('officer-reports/export', [OfficerReportController::class, 'export'])->name('officer-reports.export');
    Route::get('officer-reports/export-by-user', [OfficerReportController::class, 'exportByUser'])->name('officer-reports.exportByUser');
    Route::get('officer-reports/export-pdf', [OfficerReportController::class, 'exportPdf'])->name('officer-reports.exportPdf');

    // Route user
    Route::resource('users', UserController::class);
    Route::post('users/fetch-data-table', [UserController::class, 'fetchDataTable'])->name('users.fetchDataTable');

    // Route roles
    Route::resource('roles', RoleController::class);
    Route::post('roles/fetch-data-table', [RoleController::class, 'fetchDataTable'])->name('roles.fetchDataTable');

    // Route profile
    Route::get('profile', [ProfileController::class, 'index'])->name('profile.index');
    Route::put('profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('profile/update-password', [ProfileController::class, 'updatePassword'])->name('profile.update-password');
});
