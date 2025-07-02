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

        return redirect()
            ->route('profile.show')
            ->with('success', '商品を購入しました。');
    }

    /*------------------------------ ② 購入画面（PG06） ------------------------------*/
    public function showPurchaseForm($id)
    {
        $product  = Product::findOrFail($id);
        $address  = Auth::user()->refresh()->userAddress ?? null;   // ★ ここを userAddress

        return view('purchase.form', compact('product', 'address'));
    }

    /*------------------------------ ③ 住所変更フォーム（PG07） ------------------------------*/
    public function showAddressForm(Product $item)
    {
        $address = Auth::user()->userAddress ?? null;
        return view('purchase.address', compact('item', 'address'));
    }

    /*------------------------------ ④ 住所保存 ------------------------------*/
    public function updateAddress(AddressRequest $request, Product $item)
    {
        Auth::user()->userAddress()->updateOrCreate([], $request->validated());

        return redirect()
            ->route('purchase.form', $item)
            ->with('success', '送付先住所を更新しました');
    }
}
