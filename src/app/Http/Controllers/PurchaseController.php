<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddressRequest;
use App\Models\Product;
use App\Models\Trade;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PurchaseController extends Controller
{
    
    public function purchase(Request $request, Product $product)
{

    $product->update([
        'buyer_id'     => $request->user()->id,
        'is_sold'      => true,
        'purchased_at' => now(),
    ]);

   
    $trade = Trade::firstOrCreate(
        ['product_id' => $product->id],
        [
            'buyer_id'  => $request->user()->id,
            'seller_id' => $product->user_id,
            'status'    => 'progress',
        ]
    );

  
    return redirect()->route('trades.chat', $trade);
}


    
    public function showPurchaseForm($id)
    {
        $product = Product::findOrFail($id);
        $address = Auth::user()->userAddress()->first();   
        return view('purchase.form', compact('product', 'address'));
    }

 
    public function showAddressForm()
    {
        $address = Auth::user()->userAddress;           
        return view('purchase.shipping', compact('address'));
    }


    public function updateAddress(AddressRequest $request)
    {
        Auth::user()->userAddress()
            ->updateOrCreate([], $request->validated());

        return redirect()
            ->route('profile.show')
            ->with('success', '送付先住所を更新しました');
    }
}
