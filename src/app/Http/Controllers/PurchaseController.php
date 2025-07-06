<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddressRequest;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PurchaseController extends Controller
{
    /*------------------------------ ① 購入確定 ------------------------------*/
    public function purchase(Request $request, Product $product)
    {
        $product->buyer_id     = Auth::id();
        $product->is_sold      = true;
        $product->purchased_at = now();
        $product->save();

        return redirect()->route('profile.show')
                         ->with('success', '商品を購入しました。');
    }

    /*------------------------------ ② 購入画面（PG06） ------------------------------*/
    public function showPurchaseForm($id)
    {
        $product = Product::findOrFail($id);
        $address = Auth::user()->userAddress()->first();           // 1 : 1 の住所
        return view('purchase.form', compact('product', 'address'));
    }

    /*------------------------------ ③ 送付先住所入力（PG07） ------------------------------*/
    public function showAddressForm()
    {
        $address = Auth::user()->userAddress;                      // null 可
        return view('purchase.shipping', compact('address'));      // ★ shipping.blade.php
    }

    /*------------------------------ ④ 住所保存 ------------------------------*/
    public function updateAddress(AddressRequest $request)
    {
        Auth::user()->userAddress()->updateOrCreate([], $request->validated());

        // ここはテスト仕様で profile へ戻す
        return redirect()->route('profile.show')
                         ->with('success', '送付先住所を更新しました');
    }
}
