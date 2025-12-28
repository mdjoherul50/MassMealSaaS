<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Tenant\HouseRentController;
use App\Http\Controllers\Tenant\BazarController;
use App\Http\Controllers\Tenant\DepositController;
use App\Http\Controllers\Tenant\HouseRentMainController;
use App\Http\Controllers\Tenant\MealController;
use App\Http\Controllers\Tenant\MemberController;
use App\Http\Controllers\Tenant\ReportController;
use App\Http\Controllers\SuperAdmin\RoleController;
use App\Http\Controllers\SuperAdmin\TenantController;
use App\Http\Controllers\SuperAdmin\PlanController;
use App\Http\Controllers\SuperAdmin\BrandingController as SuperAdminBrandingController;
use App\Http\Controllers\Tenant\BrandingController as TenantBrandingController;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\ChatController;

Route::get('/', function () {
    return view('welcome');
});

Route::post('/language/switch', [LanguageController::class, 'switch'])->name('language.switch');

Route::get('/dashboard', function () {
    $user = Auth::user();

    // Super Admin gets their own dashboard
    if ($user->role && $user->role->slug === 'super-admin') {
        return view('superadmin-dashboard');
    }

    // Tenant users (mess-admin, bazarman, member) get tenant dashboard
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::post('/profile/photo', [ProfileController::class, 'uploadPhoto'])->name('profile.photo.upload');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


// --- টেন্যান্ট (Mess Admin / Bazarman) রাউট গ্রুপ ---
Route::middleware(['auth', 'check.tenant'])->group(function () {

    // Members (Mess Admin)
    Route::resource('members', MemberController::class);
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
    Route::get('reports/overview/pdf', [ReportController::class, 'overviewPdf'])->name('reports.overview.pdf')->middleware('can:reports.view');

    Route::resource('house-rents', HouseRentController::class)->middleware([
        'can:houserent.view',
        'can:houserent.manage',
    ]);

    Route::get('/my-house-rent', [HouseRentController::class, 'myHouseRent'])
        ->name('house-rents.my')
        ->middleware('can:houserent.view');

    // House Rent Main (total rent + utilities)
    Route::resource('house-rent-mains', HouseRentMainController::class)->middleware([
        'can:houserent.manage',
    ]);

    Route::post('/house-rent-mains/{month}/sync', [HouseRentMainController::class, 'syncAssigned'])
        ->name('house-rent-mains.sync')
        ->middleware('can:houserent.manage');

    Route::post('/house-rent-mains/{month}/members', [HouseRentMainController::class, 'saveMemberRents'])
        ->name('house-rent-mains.members')
        ->middleware('can:houserent.manage');

    Route::post('/house-rent-mains/{month}/copy-previous', [HouseRentMainController::class, 'copyPreviousMonth'])
        ->name('house-rent-mains.copy-previous')
        ->middleware('can:houserent.manage');

    Route::post('/house-rent-mains/{month}/carry-forward', [HouseRentMainController::class, 'carryForward'])
        ->name('house-rent-mains.carry-forward')
        ->middleware('can:houserent.manage');

    Route::get('/branding/logo', [TenantBrandingController::class, 'edit'])->name('tenant.branding.logo.edit');
    Route::post('/branding/logo', [TenantBrandingController::class, 'update'])->name('tenant.branding.logo.update');
});

// --- সুপার অ্যাডমিন (Super Admin) রাউট গ্রুপ ---
Route::middleware(['auth', 'can:tenants.manage'])->prefix('superadmin')->name('superadmin.')->group(function () {

    Route::resource('tenants', TenantController::class);
    Route::post('tenants/{tenant}/change-plan', [TenantController::class, 'changePlan'])->name('tenants.change-plan');

    Route::resource('plans', PlanController::class);

    Route::resource('roles', RoleController::class)->middleware('can:roles.manage');

    Route::get('branding/logo', [SuperAdminBrandingController::class, 'edit'])->name('branding.logo.edit');
    Route::post('branding/logo', [SuperAdminBrandingController::class, 'update'])->name('branding.logo.update');
});

// --- Chat Routes ---
Route::middleware(['auth'])->prefix('chat')->name('chat.')->group(function () {
    Route::get('/', [ChatController::class, 'index'])->name('index');
    Route::get('/create-group', [ChatController::class, 'createGroupChat'])->name('create-group');
    Route::get('/members', [ChatController::class, 'members'])->name('members');
    Route::post('/conversations', [ChatController::class, 'store'])->name('store');
    Route::get('/conversations/{conversation}', [ChatController::class, 'show'])->name('show');
    Route::post('/conversations/{conversation}/messages', [ChatController::class, 'sendMessage'])->name('send-message');
    Route::get('/conversations/{conversation}/messages', [ChatController::class, 'getMessages'])->name('get-messages');
    Route::put('/messages/{message}', [ChatController::class, 'updateMessage'])->name('update-message');
    Route::delete('/messages/{message}', [ChatController::class, 'deleteMessage'])->name('delete-message');
    Route::get('/private/{recipient}', [ChatController::class, 'createPrivateChat'])->name('private');
    Route::get('/managers', [ChatController::class, 'messManagers'])->name('managers')->middleware('can:tenants.manage');

    // Conversation actions
    Route::post('/conversations/{conversation}/mute', [ChatController::class, 'muteConversation'])->name('mute');
    Route::post('/conversations/{conversation}/clear', [ChatController::class, 'clearChat'])->name('clear');
    Route::delete('/conversations/{conversation}', [ChatController::class, 'deleteConversation'])->name('delete');
    Route::post('/users/{user}/block', [ChatController::class, 'blockUser'])->name('block-user');
});

require __DIR__ . '/auth.php';
