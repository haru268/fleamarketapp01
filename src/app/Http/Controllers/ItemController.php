<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ItemController extends Controller
{
    // GET /item/{id}
    public function show(Request $request, $id)
    {
        // リクエスト内容を確認したい場合は dd() でダンプ
        // dd($request->all());

        // 指定されたIDの商品を取得（見つからなければ404）
        $product = Product::findOrFail($id);
        
        // 商品詳細ページ (resources/views/products/item.blade.php) を表示
        return view('products.item', compact('product'));
    }
}
