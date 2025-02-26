<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    public function index()
    {
        // ログインしている場合、現在のユーザー以外の出品商品を取得
        // ログインしていなければ、全商品を取得します
        $otherUsersProducts = Product::when(Auth::check(), function($query) {
            $query->where('user_id', '<>', Auth::id());
        })->get();

        // おすすめとして、他のユーザーの商品を表示する
        $recommendedProducts = $otherUsersProducts;

        // マイリス（いいねした商品）を取得（ログインしている場合）
        $likedProducts = Auth::check() ? Auth::user()->likedProducts()->get() : collect();

        return view('products.index', compact('recommendedProducts', 'likedProducts'));
    }
}
