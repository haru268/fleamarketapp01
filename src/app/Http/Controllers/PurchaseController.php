<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddressRequest;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PurchaseController extends Controller
{
    /*----------------------------------------------------------
    | ① 購入確定
    *---------------------------------------------------------*/
    public function purchase(Request $request, Product $product)
    {
        // 購入フラグ更新
        $product->buyer_id     = Auth::id();
        $product->is_sold      = true;
        $product->purchased_at = now();
        $product->save();

        return redirect()
            ->route('profile.show')
            ->with('success', '商品を購入しました。');
    }

    /* 購入画面（PG06） */
public function showPurchaseForm($id)
{
    $product  = Product::findOrFail($id);
    $address  = Auth::user()->userAddress()->first();   // ← ここだけ変更
    return view('purchase.form', compact('product', 'address'));
}

/* 住所変更フォーム */
public function showAddressForm()
{
    $address = Auth::user()->userAddress;               // ← 修正
    return view('purchase.address', compact('address'));
}

/* 住所保存 */
public function updateAddress(AddressRequest $request)
{
    Auth::user()->userAddress()->updateOrCreate([], $request->validated()); // ← 修正
    return redirect()->route('profile.show')
                     ->with('success', '送付先住所を更新しました');
}


}
