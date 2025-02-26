<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\UserProfileController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ExhibitionController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\ItemController;

// ホームページ
Route::get('/', [ProductController::class, 'index'])->name('home');

// ユーザー認証関連
Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', function () {
    Auth::logout();
    request()->session()->invalidate();
    return redirect('/login');
})->name('logout');

// 認証が必要なルート
Route::middleware(['auth'])->group(function () {
    // プロフィール関連
    Route::get('/mypage/profile', [UserProfileController::class, 'edit'])->name('profile.edit');
    Route::post('/profile/update', [UserProfileController::class, 'update'])->name('profile.update');
    Route::get('/profile', [UserProfileController::class, 'show'])->name('profile.show');

    // 出品関連
    Route::get('/exhibition', [ExhibitionController::class, 'create'])->name('exhibition')->middleware('auth');
    Route::get('/exhibition/create', [ExhibitionController::class, 'create'])->name('exhibition.create')->middleware('auth');
    Route::post('/exhibition/store', [ExhibitionController::class, 'store'])->name('exhibition.store')->middleware('auth');
});

// 商品詳細
Route::get('/item/{id}', [ItemController::class, 'show'])->name('item.show');

// 購入関連
Route::post('/purchase/{product}', [PurchaseController::class, 'purchase'])->name('purchase');
Route::get('/purchase/{id}', [PurchaseController::class, 'showPurchaseForm'])->name('purchase.form');
Route::get('/purchase/address/{id}', [PurchaseController::class, 'showAddressForm'])->name('purchase.address.form');
