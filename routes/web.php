<?php

use Illuminate\Support\Facades\Route;

// আমাদের কন্ট্রোলারগুলো ইম্পোর্ট করি
use App\Http\Controllers\Tenant\MemberController;
use App\Http\Controllers\Tenant\MealController;
use App\Http\Controllers\Tenant\BazarController;
use App\Http\Controllers\Tenant\DepositController;
use App\Http\Controllers\Tenant\ReportController;
use App\Http\Controllers\SuperAdmin\TenantController;

// লারাভেলের ডিফল্ট Auth রাউট (লগইন, রেজিস্ট্রেশন)
// এটি চালানোর আগে আপনাকে 'laravel/ui' বা 'breeze' ইন্সটল করতে হবে
// Auth::routes();

// আপাতত আমরা শুধু লগইন করা ইউজারদের জন্য রাউট বানাচ্ছি
Route::get('/', function () {
    return view('welcome');
});

// টেন্যান্ট (Mess Admin) এর রাউট গ্রুপ
// SRS সেকশন ১৫ অনুযায়ী
Route::middleware(['auth', 'check.tenant'])->group(function () {
    
    // Member Management
    Route::resource('members', MemberController::class);
    // Route::post('members/import', [MemberController::class, 'importCsv'])->name('members.import');

    // Meal Management
    Route::post('meals/bulk-store', [MealController::class, 'bulkStore'])->name('meals.bulkStore');
    // Route::get('meals/daily-update', [MealController::class, 'dailyUpdateView'])->name('meals.dailyUpdateView');
    
    // Bazar Management
    Route::resource('bazars', BazarController::class);

    // Deposit Management
    Route::resource('deposits', DepositController::class);

    // Report Management
    Route::get('reports/overview/{month?}', [ReportController::class, 'overview'])->name('reports.overview');
    // Route::get('reports/member-statement/{member_id}/{month?}', [ReportController::class, 'memberStatement'])->name('reports.memberStatement');
    // Route::get('reports/export-excel', [ReportController::class, 'exportExcel'])->name('reports.exportExcel');

});

// সুপার অ্যাডমিন (Super Admin) এর রাউট গ্রুপ
// SRS সেকশন ১৫ অনুযায়ী
Route::middleware(['auth', 'check.tenant'])->prefix('superadmin')->name('superadmin.')->group(function () {
    
    // Super Admin রাউটগুলো এখানে হবে
    // Role check (can:super-admin) আমরা কন্ট্রোলারে বা আলাদা middleware দিয়ে করতে পারি
    
    Route::resource('tenants', TenantController::class);
    
});