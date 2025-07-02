<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\ExhibitionController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\UserProfileController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public routes
|--------------------------------------------------------------------------
*/
Route::get('/', [ProductController::class, 'index'])->name('home');

Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);

Route::get('/login',  [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);

Route::post('/logout', function () {
    Auth::logout();
    request()->session()->invalidate();
    return redirect('/login');
})->name('logout');

Route::get('/item/{id}', [ItemController::class, 'show'])->name('item.show');

Route::get('/search', [ProductController::class, 'index'])->name('search');   // 検索

/*
|--------------------------------------------------------------------------
| Auth-protected routes
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {

    /* --- プロフィール --- */
    Route::get ('/profile',           [UserProfileController::class, 'show' ])->name('profile.show');
    Route::get ('/mypage/profile',    [UserProfileController::class, 'edit' ])->name('profile.edit');
    Route::post('/profile/update',    [UserProfileController::class, 'update'])->name('profile.update');

    /* --- 出品 --- */
    Route::get ('/sell',                [ExhibitionController::class, 'create'])->name('sell');
    Route::post('/exhibition/store',    [ExhibitionController::class, 'store' ])->name('exhibition.store');
    Route::get ('/sell/{id}',           [ExhibitionController::class, 'edit' ])->name('sell.edit');
    Route::put ('/exhibition/update/{id}', [ExhibitionController::class, 'update'])->name('exhibition.update');

    /* --- コメント／いいね --- */
    Route::post('/comment',       [CommentController::class, 'store' ])->name('comment.store');
    Route::post('/like/toggle',   [LikeController::class,    'toggle'])->name('like.toggle');

    /* --- 購入 --- */
    Route::get ('/purchase/{id}',      [PurchaseController::class, 'showPurchaseForm'])->name('purchase.form');
    Route::post('/purchase/{product}', [PurchaseController::class, 'purchase'        ])->name('purchase');

    /* --- 送付先住所（PG07） --- */
    Route::get ('/purchase/address/{item}', [PurchaseController::class, 'showAddressForm'])->name('purchase.address.form');
    Route::post('/purchase/address/{item}', [PurchaseController::class, 'updateAddress' ])->name('purchase.address.update');
});
