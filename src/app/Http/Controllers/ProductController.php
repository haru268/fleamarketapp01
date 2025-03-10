<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        // ヘッダーの検索フォームで送信される 'query' を取得
        $q = $request->input('query');
        $page = $request->query('page');

        if ($page === 'mylist' && Auth::check()) {
            // ログインユーザーがいいねした商品の一覧を部分一致検索で取得
            $likedProducts = Auth::user()->likedProducts()
                ->when($q, function($query, $q) {
                    return $query->where('name', 'LIKE', '%' . $q . '%');
                })
                ->get();
            return view('products.index', compact('likedProducts', 'q'));
        } else {
            // おすすめ商品の部分一致検索
            $recommendedProducts = Product::where('is_recommended', true)
                ->when(Auth::check(), function($query) {
                    $query->where('user_id', '<>', Auth::id());
                })
                ->when($q, function($query, $q) {
                    return $query->where('name', 'LIKE', '%' . $q . '%');
                })
                ->get();
            return view('products.index', compact('recommendedProducts', 'q'));
        }
    }
}
