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
use App\Http\Controllers\CommentController;
use App\Http\Controllers\LikeController;


Route::get('/', [ProductController::class, 'index'])->name('home');


Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', function () {
    Auth::logout();
    request()->session()->invalidate();
    return redirect('/login');
})->name('logout');


Route::middleware(['auth'])->group(function () {
    
    Route::get('/mypage/profile', [UserProfileController::class, 'edit'])->name('profile.edit');
    Route::post('/profile/update', [UserProfileController::class, 'update'])->name('profile.update');
    Route::get('/profile', [UserProfileController::class, 'show'])->name('profile.show');

    
    Route::get('/sell', [ExhibitionController::class, 'create'])->name('sell')->middleware('auth');
    Route::post('/exhibition/store', [ExhibitionController::class, 'store'])->name('exhibition.store')->middleware('auth');
    Route::get('/sell/{id}', [ExhibitionController::class, 'edit'])->name('sell.edit')->middleware('auth');
    Route::put('/exhibition/update/{id}', [ExhibitionController::class, 'update'])
        ->name('exhibition.update')
        ->middleware('auth');

    Route::post('/comment/store', [CommentController::class, 'store'])->name('comment.store');
    Route::post('/comment', [CommentController::class, 'store'])->name('comment.store');
});


Route::get('/item/{id}', [ItemController::class, 'show'])->name('item.show');


Route::post('/purchase/{product}', [PurchaseController::class, 'purchase'])->name('purchase');
Route::get('/purchase/{id}', [PurchaseController::class, 'showPurchaseForm'])->name('purchase.form');

Route::get('/purchase/address', [PurchaseController::class, 'showAddressForm'])
    ->name('purchase.address.form');


Route::post('/purchase/address', [PurchaseController::class, 'updateAddress'])
    ->name('purchase.address.update');



Route::post('/comment', [CommentController::class, 'store'])->name('comment.store');

Route::post('/like/toggle', [LikeController::class, 'toggle'])->name('like.toggle');

Route::get('/search', [ProductController::class, 'index'])->name('home');

