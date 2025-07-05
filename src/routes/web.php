<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    Auth\RegisterController,
    Auth\LoginController,
    ProductController,
    UserProfileController,
    ExhibitionController,
    PurchaseController,
    CommentController,
    LikeController,
    ItemController
};

/* =======================================================
 |  公開（未ログインでもアクセス可）
 ====================================================== */
Route::get('/',                [ProductController::class, 'index'])->name('home');
Route::get('/item/{id}',       [ItemController::class,   'show'  ])->name('item.show');
Route::get('/search',          [ProductController::class, 'index'])->name('search');

Route::get ('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);

Route::get ('/login',    [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login',    [LoginController::class, 'login']);

/* =======================================================
 |  認証必須
 ====================================================== */
Route::middleware('auth')->group(function () {

    /* ---------- プロフィール ---------- */
    Route::get ('/profile',          [UserProfileController::class,'show'  ])->name('profile.show');
    Route::get ('/mypage/profile',   [UserProfileController::class,'edit'  ])->name('profile.edit');
    Route::post('/profile/update',   [UserProfileController::class,'update'])->name('profile.update');

    /* ---------- 出品 ---------- */
    Route::get ('/sell',                   [ExhibitionController::class,'create'])->name('sell');
    Route::post('/exhibition/store',       [ExhibitionController::class,'store' ])->name('exhibition.store');
    Route::get ('/sell/{id}',              [ExhibitionController::class,'edit'  ])->name('sell.edit')
        ->whereNumber('id');
    Route::put ('/exhibition/update/{id}', [ExhibitionController::class,'update'])->name('exhibition.update')
        ->whereNumber('id');

    /* ===================================================
     |  送付先住所（PG07） ※テストはここを呼びます
     |  固定パスを先に置くこと！
     =================================================== */
    Route::get ('/purchase/address', [PurchaseController::class,'showAddressForm' ])->name('purchase.address.form');
    Route::post('/purchase/address', [PurchaseController::class,'updateAddress'   ])->name('purchase.address.update');

    /* ---------- 購入フロー ---------- */
    Route::post('/purchase/{product}', [PurchaseController::class,'purchase'])
        ->whereNumber('product')
        ->name('purchase');

    Route::get ('/purchase/{id}', [PurchaseController::class,'showPurchaseForm'])
        ->whereNumber('id')
        ->name('purchase.form');

    /* ---------- コメント & いいね ---------- */
    Route::post('/comment',     [CommentController::class,'store' ])->name('comment.store');
    Route::post('/like/toggle', [LikeController::class,  'toggle'])->name('like.toggle');
});

/* =======================================================
 |  ログアウト
 ====================================================== */
Route::post('/logout', function () {
    Auth::logout();
    request()->session()->invalidate();
    return redirect('/login');
})->name('logout');
