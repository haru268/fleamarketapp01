<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class ProductController extends Controller
{
    public function index()
{
    // おすすめ商品の条件：自分が出品した商品は除外し、かつ is_recommended が true の商品を取得
    $recommendedProducts = Product::where('is_recommended', true)
                                  ->when(Auth::check(), function($query) {
                                      $query->where('user_id', '<>', Auth::id());
                                  })
                                  ->get();

    // マイリス：ログインユーザーの「いいね」した商品（likedProducts リレーションが定義されている前提）
    $likedProducts = Auth::check() ? Auth::user()->likedProducts()->get() : collect();

    return view('products.index', compact('recommendedProducts', 'likedProducts'));
}


}
