<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

// কন্ট্রোলারগুলো
use App\Http\Controllers\Tenant\MemberController;
use App\Http\Controllers\Tenant\MealController;
use App\Http\Controllers\Tenant\BazarController;
use App\Http\Controllers\Tenant\DepositController;
use App\Http\Controllers\Tenant\ReportController;
use App\Http\Controllers\SuperAdmin\TenantController;
use App\Http\Controllers\SuperAdmin\RoleController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


// --- টেন্যান্ট (Mess Admin / Bazarman) রাউট গ্রুপ ---
Route::middleware(['auth', 'check.tenant'])->group(function () {

    // Members (Mess Admin)
    Route::resource('members', MemberController::class)->middleware([
        'can:members.view', // index, show
        'can:members.create', // create, store
        'can:members.edit', // edit, update
        'can:members.delete', // destroy
    ]);
// My Statement (সদস্যদের জন্য নতুন রাউট)
    Route::get('/my-statement', [MemberController::class, 'myStatement'])
         ->name('members.myStatement')
         ->middleware('can:reports.view'); // '
    // Meals (Mess Admin & Bazarman)

    // বাল্ক এন্ট্রি পেজ (আগেরটির নতুন নাম)
    Route::get('meals/bulk-entry', [MealController::class, 'bulkStoreView'])->name('meals.bulkEntry')->middleware('can:meals.view');
    Route::post('meals/bulk-store', [MealController::class, 'bulkStore'])->name('meals.bulkStore')->middleware('can:meals.manage');

    // মিলের CRUD (নতুন ডাটা টেবিলের জন্য)
    Route::resource('meals', MealController::class)->middleware([
        'can:meals.view', // index, show
        'can:meals.manage', // create, store, edit, update, destroy
    ]);

    // Bazar Management (Mess Admin & Bazarman)
    Route::resource('bazars', BazarController::class)->middleware([
        'can:bazars.view', // index, show
        'can:bazars.manage', // create, store, edit, update, destroy
    ]);
    // Deposits (Mess Admin)
    Route::resource('deposits', DepositController::class)->middleware([
        'can:deposits.view',
        'can:deposits.manage',
    ]);

    // Reports (Mess Admin & Member)
    Route::get('reports/overview/{month?}', [ReportController::class, 'overview'])->name('reports.overview')->middleware('can:reports.view');

});

// --- সুপার অ্যাডমিন (Super Admin) রাউট গ্রুপ ---
Route::middleware(['auth', 'can:tenants.manage'])->prefix('superadmin')->name('superadmin.')->group(function () {

    Route::resource('tenants', TenantController::class);
    Route::resource('roles', RoleController::class)->middleware('can:roles.manage');
});

require __DIR__.'/auth.php';
