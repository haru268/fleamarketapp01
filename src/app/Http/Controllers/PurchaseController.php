<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PurchaseController extends Controller
{

    public function purchase(Request $request, Product $product)
    {

        $product->buyer_id = Auth::id();
        $product->is_sold = true;
        $product->purchased_at = now();
        $product->save();


        return redirect()->route('profile.show')->with('success', '商品を購入しました。');
    }


    public function showPurchaseForm($id)
    {
        $product = Product::findOrFail($id);
        $address = Auth::check() ? Auth::user()->fresh('userAddress')->address : null;
        return view('purchase.form', compact('product', 'address'));
    }

    public function showAddressForm()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }
        $address = Auth::user()->userAddress ?? null;
        return view('purchase.address', compact('address'));
    }

    public function updateAddress(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }
        
        $validated = $request->validate([
            'postal_code' => 'required',
            'address'     => 'required',
            'building'    => 'nullable',
        ]);

        $address = Auth::user()->userAddress ?? new \App\Models\Address();
        $address->user_id = Auth::id();
        $address->postal_code = $validated['postal_code'];
        $address->address = $validated['address'];
        $address->building = $validated['building'] ?? null;
        $address->save();


        return redirect()->route('purchase.form', ['id' => 6])->with('success', '住所が更新されました。');
    }
}
