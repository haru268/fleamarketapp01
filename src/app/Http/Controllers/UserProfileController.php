<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class UserProfileController extends Controller
{
    public function edit()
    {
        $user = Auth::user();
        if (!$user) {
            return redirect()->route('login');
        }

        $profileImage = $user->profile_image ? 'storage/' . $user->profile_image : 'path/to/default-placeholder.png';
        return view('profile.edit', ['user' => $user, 'profileImage' => $profileImage]);
    }

    public function update(Request $request)
{
    
    $user = auth()->user();
    $user->name = $request->input('username');
    $user->postal_code = $request->input('postal_code');
    $user->address = $request->input('address');
    $user->building = $request->input('building');

    
    if ($request->hasFile('profile_image')) {
        $path = $request->file('profile_image')->store('profile_images', 'public');
        $user->profile_image = $path;
    }

    $user->save();

    
    return redirect()->route('profile.show')->with('success', 'プロフィールが更新されました。');
}


    public function show(Request $request)
{
    $user = Auth::user();
    if (!$user) {
        return redirect()->route('login');
    }

    $page = $request->query('page');

    if ($page === 'buy') {
        $purchasedProducts = Product::where('buyer_id', $user->id)
            ->where('user_id', '<>', $user->id)
            ->get();
        $exhibitedProducts = collect();
    } elseif ($page === 'sell') {
        $exhibitedProducts = $user->exhibitedProducts;
        $purchasedProducts = collect();
    } else {
        $exhibitedProducts = $user->exhibitedProducts;
        $purchasedProducts = collect();
    }

    $likedProducts = $user->likedProducts; 

    return view('profile.show', [
        'user' => $user,
        'exhibitedProducts' => $exhibitedProducts,
        'purchasedProducts' => $purchasedProducts,
        'likedProducts' => $likedProducts, 
    ]);
}





}
