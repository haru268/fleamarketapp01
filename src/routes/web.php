<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/* 既存コントローラ */
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\UserProfileController;
use App\Http\Controllers\ExhibitionController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\ItemController;

/* 取引関連 */
use App\Http\Controllers\TradeController;          // 一覧・完了・メッセージ CRUD
use App\Http\Controllers\TradeChatController;      // チャット画面（＊New）

/* ───────────────────────────────
|  公開ページ
└─────────────────────────────── */
Route::get('/',               [ProductController::class, 'index'])->name('home');
Route::get('/item/{id}',      [ItemController::class,   'show'])->whereNumber('id')->name('item.show');
Route::get('/search',         [ProductController::class, 'index'])->name('search');

Route::get ('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);

Route::get ('/login',   [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login',   [LoginController::class, 'login']);

/* ───────────────────────────────
|  認証必須ページ
└─────────────────────────────── */
Route::middleware('auth')->group(function () {

    /* ――― マイページ / プロフィール ――― */
    Route::get ('/profile',         [UserProfileController::class, 'show'])->name('profile.show');
    Route::get ('/mypage/profile',  [UserProfileController::class, 'edit'])->name('profile.edit');
    Route::post('/profile/update',  [UserProfileController::class, 'update'])->name('profile.update');

    /* ――― 出品 ――― */
    Route::get ('/sell',                   [ExhibitionController::class, 'create'])->name('sell');
    Route::post('/exhibition/store',       [ExhibitionController::class, 'store'])->name('exhibition.store');
    Route::get ('/sell/{id}',              [ExhibitionController::class, 'edit'])->whereNumber('id')->name('sell.edit');
    Route::put ('/exhibition/update/{id}', [ExhibitionController::class, 'update'])->whereNumber('id')->name('exhibition.update');

    /* ――― 住所変更 & 購入 ――― */
    Route::get ('/purchase/shipping', [PurchaseController::class, 'showAddressForm'])->name('purchase.shipping.form');
    Route::post('/purchase/shipping', [PurchaseController::class, 'updateAddress'])->name('purchase.shipping.update');

    Route::get ('/purchase/{id}',      [PurchaseController::class, 'showPurchaseForm'])->whereNumber('id')->name('purchase.form');
    Route::post('/purchase/{product}', [PurchaseController::class, 'purchase'])->whereNumber('product')->name('purchase');

    /* ――― コメント & いいね ――― */
    Route::post('/comment',     [CommentController::class, 'store'])->name('comment.store');
    Route::post('/like/toggle', [LikeController::class,   'toggle'])->name('like.toggle');

    /* ───── 取引関連 ───── */

    // 取引一覧
    Route::get('/trades', [TradeController::class, 'index'])->name('trades.index');

    Route::post('/products/{product}/comments',
    [CommentController::class, 'store']
)->name('comments.store');

    // チャット画面（ここを TradeChatController に差し替え）
    Route::get('/trades/{trade}', [TradeChatController::class, 'show'])
        ->whereNumber('trade')
        ->name('trades.chat');

    // メッセージ投稿
    Route::post('/trades/{trade}/messages', [TradeController::class, 'storeMessage'])
        ->whereNumber('trade')
        ->name('trades.messages.store');

    // 取引完了 & 評価送信
    Route::post('/trades/{trade}/complete', [TradeController::class, 'complete'])
        ->whereNumber('trade')
        ->name('trades.complete');

    // メッセージ編集・更新・削除
    Route::get   ('/trades/{trade}/messages/{message}/edit',   [TradeController::class, 'editMessage'])
        ->whereNumber('trade')->whereNumber('message')->name('trades.messages.edit');

    Route::put   ('/trades/{trade}/messages/{message}',        [TradeController::class, 'updateMessage'])
        ->whereNumber('trade')->whereNumber('message')->name('trades.messages.update');

    Route::delete('/trades/{trade}/messages/{message}',        [TradeController::class, 'destroyMessage'])
        ->whereNumber('trade')->whereNumber('message')->name('trades.messages.destroy');
});

/* ───────────────────────────────
|  ログアウト
└─────────────────────────────── */
Route::post('/logout', function () {
    Auth::logout();
    request()->session()->invalidate();
    return redirect('/login');
})->name('logout');
