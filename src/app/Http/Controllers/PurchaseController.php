<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PurchaseController extends Controller
{
    /**
     * 指定された商品の購入処理を行います。
     */
    public function purchase(Request $request, Product $product)
    {
        // 現在ログイン中のユーザーのIDを、購入した商品の buyer_id にセット
        $product->buyer_id = Auth::id();
        $product->save();

        // 購入完了後、マイページなどにリダイレクト
        return redirect()->route('profile.show')->with('success', '商品を購入しました。');
    }

    /**
     * 購入画面を表示するメソッド
     */
    public function showPurchaseForm($id)
    {
        // 商品を取得（見つからなければ404）
        $product = Product::findOrFail($id);

        // 購入画面用の Blade テンプレートを表示 (例: resources/views/purchase/form.blade.php)
        return view('purchase.form', compact('product'));
    }

    /**
     * 住所変更画面を表示するメソッド
     */
    public function showAddressForm($id)
    {
        // 商品を取得（見つからなければ404）
        $product = Product::findOrFail($id);

        // 住所変更画面用の Blade テンプレートを表示 (例: resources/views/purchase/address.blade.php)
        return view('purchase.address', compact('product'));
    }
}
