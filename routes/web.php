<?php

use App\Http\Controllers\ProfileController; // Breeze-এর জন্য এটি যোগ করুন
use Illuminate\Support\Facades\Route;

// আমাদের কন্ট্রোলারগুলো
use App\Http\Controllers\Tenant\MemberController;
use App\Http\Controllers\Tenant\MealController;
use App\Http\Controllers\Tenant\BazarController;
use App\Http\Controllers\Tenant\DepositController;
use App\Http\Controllers\Tenant\ReportController;
use App\Http\Controllers\SuperAdmin\TenantController;

Route::get('/', function () {
    return view('welcome');
});

// Breeze-এর ডিফল্ট ড্যাশবোর্ড রাউট
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Breeze-এর প্রোফাইল রাউট
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


// --- আমাদের কাস্টম টেন্যান্ট (Mess Admin) রাউট গ্রুপ ---
// (SRS সেকশন ১৫ অনুযায়ী)
Route::middleware(['auth', 'check.tenant'])->group(function () {
    
    // Member Management
    Route::resource('members', MemberController::class);
    // Route::post('members/import', [MemberController::class, 'importCsv'])->name('members.import');

    // Meal Management
    Route::post('meals/bulk-store', [MealController::class, 'bulkStore'])->name('meals.bulkStore');
    Route::get('meals/bulk', [MealController::class, 'bulkStoreView'])->name('meals.bulkStoreView');
    // Route::get('meals/daily-update', [MealController::class, 'dailyUpdateView'])->name('meals.dailyUpdateView');
    
    // Bazar Management
    Route::resource('bazars', BazarController::class);

    // Deposit Management
    Route::resource('deposits', DepositController::class);

    // Report Management
    Route::get('reports/overview/{month?}', [ReportController::class, 'overview'])->name('reports.overview');
    
});

// --- আমাদের কাস্টম সুপার অ্যাডমিন (Super Admin) রাউট গ্রুপ ---
// (SRS সেকশন ১৫ অনুযায়ী)
Route::middleware(['auth', 'check.tenant'])->prefix('superadmin')->name('superadmin.')->group(function () {
    
    Route::resource('tenants', TenantController::class);
    
});


// এই লাইনটি লগইন/রেজিস্ট্রেশন রাউটগুলো লোড করার জন্য আবশ্যক
require __DIR__.'/auth.php';