<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddressRequest;
use App\Models\Product;
use App\Models\Trade;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PurchaseController extends Controller
{
    /*------------------------------------------------------------
     | ① 購入ボタン（＝取引開始）
     *-----------------------------------------------------------*/
    public function purchase(Request $request, Product $product)
    {
        // 1) 取引レコードを progress で生成 or 取得
        $trade = Trade::firstOrCreate(
            ['product_id' => $product->id],
            [
                'buyer_id'  => Auth::id(),
                'seller_id' => $product->user_id,
                'status'    => 'progress',
            ]
        );

        // 2) チャット画面へ遷移（商品はまだ未購入状態）
        return redirect()
            ->route('trades.chat', $trade)
            ->with('success', '取引チャットを開始しました。');
    }

    /*------------------------------------------------------------
     | ② 購入画面（PG06）
     *-----------------------------------------------------------*/
    public function showPurchaseForm($id)
    {
        $product = Product::findOrFail($id);
        $address = Auth::user()->userAddress()->first();   // 1:1
        return view('purchase.form', compact('product', 'address'));
    }

    /*------------------------------------------------------------
     | ③ 送付先住所入力（PG07）
     *-----------------------------------------------------------*/
    public function showAddressForm()
    {
        $address = Auth::user()->userAddress;              // null 可
        return view('purchase.shipping', compact('address'));
    }

    /*------------------------------------------------------------
     | ④ 住所保存
     *-----------------------------------------------------------*/
    public function updateAddress(AddressRequest $request)
    {
        Auth::user()->userAddress()
            ->updateOrCreate([], $request->validated());

        return redirect()
            ->route('profile.show')
            ->with('success', '送付先住所を更新しました');
    }
}
