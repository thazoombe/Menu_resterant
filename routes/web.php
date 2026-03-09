<?php

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\MenuController;

Route::get('/', [MenuController::class, 'index']);

use App\Http\Controllers\Admin\MenuController as AdminMenuController;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Auth\CustomerAuthController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\UserProfileController;

Route::get('/register', [CustomerAuthController::class, 'showRegister']);
Route::post('/register', [CustomerAuthController::class, 'register']);
Route::get('/login', [CustomerAuthController::class, 'showLogin'])->name('login');
Route::post('/login', [CustomerAuthController::class, 'login']);
Route::post('/logout', [CustomerAuthController::class, 'logout']);

Route::middleware('auth')->group(function () {
    Route::get('/profile', [UserProfileController::class, 'index']);
    Route::post('/profile/update', [UserProfileController::class, 'update']);
    Route::post('/favorite/{id}', [FavoriteController::class, 'toggle']);
});

use App\Http\Controllers\Admin\ExportController;
use App\Http\Controllers\Admin\SettingController;

Route::get('/admin/login', [AuthController::class, 'showLogin'])->name('admin.login');
Route::post('/admin/login', [AuthController::class, 'login']);

Route::middleware('auth')->prefix('admin')->group(function () {
    Route::get('/dashboard', [AdminMenuController::class, 'dashboard']);
    Route::get('/export/orders', [ExportController::class, 'exportOrders']);
    Route::get('/export/expenses', [ExportController::class, 'exportExpenses']);
    Route::get('/export/orders/print', [ExportController::class, 'printOrders']);
    Route::get('/export/expenses/print', [ExportController::class, 'printExpenses']);
    Route::get('/export/menu', [ExportController::class, 'exportMenu']);
    Route::get('/export/menu/print', [ExportController::class, 'printMenu']);
    Route::get('/settings', [SettingController::class, 'index']);
    Route::post('/settings/update', [SettingController::class, 'update']);
    Route::post('/logout', [AuthController::class, 'logout']);
    
    Route::prefix('menu')->group(function () {
        Route::get('/', [AdminMenuController::class, 'index']);
        Route::get('/create', [AdminMenuController::class, 'create']);
        Route::post('/store', [AdminMenuController::class, 'store']);
        Route::get('/edit/{id}', [AdminMenuController::class, 'edit']);
        Route::post('/update/{id}', [AdminMenuController::class, 'update']);
        Route::post('/delete/{id}', [AdminMenuController::class, 'destroy']);
    });

    Route::post('/order/status/{id}', [AdminMenuController::class, 'updateOrderStatus']);

    Route::get('/expenses', [AdminMenuController::class, 'expenses']);
    Route::post('/expenses/store', [AdminMenuController::class, 'storeExpense']);
    Route::post('/expenses/delete/{id}', [AdminMenuController::class, 'deleteExpense']);
});

Route::post('/order/checkout', [MenuController::class, 'checkout']);