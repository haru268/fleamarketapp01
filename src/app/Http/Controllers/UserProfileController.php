<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Trade;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserProfileController extends Controller
{
    
    public function edit()
    {
        $user = Auth::user();
        if (!$user) return redirect()->route('login');

        $profileImage = $user->profile_image
            ? 'storage/' . $user->profile_image
            : asset('images/default-user.png');

        return view('profile.edit', compact('user', 'profileImage'));
    }

    
    public function update(Request $request)
    {
        $user = $request->user();

        $user->name        = $request->input('username');
        $user->postal_code = $request->input('postal_code');
        $user->address     = $request->input('address');
        $user->building    = $request->input('building');

        if ($request->hasFile('profile_image')) {
            $path = $request->file('profile_image')->store('profile_images', 'public');
            $user->profile_image = $path;
        }

        $user->save();

        return redirect()->route('profile.show')
                         ->with('success', 'プロフィールが更新されました。');
    }

    public function show(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        if (!$user) return redirect()->route('login');

        $unreadTotal = Trade::where(fn($q) => $q
                                ->where('seller_id', $user->id)
                                ->orWhere('buyer_id',  $user->id))
                      ->where('status', 'progress')
                      ->withCount([
                          'messages as unread' => fn($q) => $q
                              ->where('user_id', '!=', $user->id)
                              ->where('is_read', false)
                      ])
                      ->get()
                      ->sum('unread');

       
        $page = $request->query('page', 'sell');  
        $purchasedProducts = collect();
        $exhibitedProducts = collect();
        $tradingTrades     = collect();
        $likedProducts     = $user->likedProducts;

        switch ($page) {
           
            case 'buy':
                $purchasedProducts = Product::where('buyer_id', $user->id)->get();
                break;

            
            case 'sell':
                $exhibitedProducts = $user->exhibitedProducts;
                break;

           
            case 'trade':
                $tradingTrades = Trade::where(fn($q) => $q
                                        ->where('seller_id', $user->id)
                                        ->orWhere('buyer_id',  $user->id))
                               ->where('status', 'progress')
                               ->with('product')
                               ->withCount([
                                   'messages as unread_count' => fn($q) => $q
                                       ->where('user_id', '!=', $user->id)
                                       ->where('is_read', false)
                               ])
                               ->latest('updated_at')
                               ->get();
                break;
        }

       
        return view('profile.show', [
            'user'              => $user,
            'page'              => $page,
            'exhibitedProducts' => $exhibitedProducts,
            'purchasedProducts' => $purchasedProducts,
            'tradingTrades'     => $tradingTrades,
            'likedProducts'     => $likedProducts,
            'unreadTotal'       => $unreadTotal,   
        ]);
    }
}
