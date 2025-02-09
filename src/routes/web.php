<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\UserProfileController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ExhibitionController;

// ホームページ
Route::get('/', [ProductController::class, 'index'])->name('home');

// 登録画面の表示と処理
Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);

// ログイン画面の表示と処理
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);

// ログアウト処理
Route::post('/logout', function () {
    Auth::logout();
    request()->session()->invalidate();
    return redirect('/login');
})->name('logout');

// 認証が必要なルート
Route::middleware(['auth'])->group(function () {
    // プロフィール表示と編集
    Route::get('/mypage/profile', [UserProfileController::class, 'edit'])->name('profile.edit');
    Route::post('/profile/update', [UserProfileController::class, 'update'])->name('profile.update');

    // 出品関連のページ
    Route::get('/exhibition', [ExhibitionController::class, 'create'])->name('exhibition')->middleware('auth');
    Route::get('/exhibition/create', [ExhibitionController::class, 'create'])->name('exhibition.create')->middleware('auth');

    // プロフィール確認ページ
    Route::get('/mypage', [UserProfileController::class, 'show'])->name('profile.show');
});

Route::post('/exhibition/store', [ExhibitionController::class, 'store'])->name('exhibition.store')->middleware('auth');
