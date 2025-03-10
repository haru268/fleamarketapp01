<?php

namespace App\Http\Controllers;

use App\Models\Address;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PurchaseController extends Controller
{
    public function showPurchaseForm($id)
{
    $product = Product::findOrFail($id);
    // fresh() を使って最新の住所情報を取得
    $address = Auth::check() ? Auth::user()->fresh('address')->address : null;
    return view('purchase.form', compact('product', 'address'));
}


    // 住所変更フォームを表示する
    public function showAddressForm()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }
        // ユーザーの住所情報があれば取得、なければ空の Address インスタンス
        $address = Auth::user()->address ?? null;
    return view('purchase.address', compact('address'));
    }

    // 住所更新処理
    public function updateAddress(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        // バリデーション
        $validated = $request->validate([
            'postal_code' => 'required',
            'address'     => 'required',
            'building'    => 'nullable',
        ]);

        // 住所が存在する場合は更新、なければ新規作成
        $address = Auth::user()->address ?? new Address();
        $address->user_id = Auth::id();
        $address->postal_code = $validated['postal_code'];
        $address->address = $validated['address'];
        $address->building = $validated['building'] ?? null;
        $address->save();

        // 更新後、購入フォームへリダイレクト（ここでは例として商品ID 6 に固定していますが、適宜変更してください）
        return redirect()->route('purchase.form', ['id' => 6])->with('success', '住所が更新されました。');
    }
}
