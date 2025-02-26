<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\ProfileRequest; // ProfileRequestを使用する

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
        $user = Auth::user();

        // バリデーションルールを適用
        $request->validate([
            'profile_image' => 'nullable|image|mimes:jpeg,png|max:2048', // 画像のバリデーション
        ]);

        if ($request->hasFile('profile_image')) {
            $path = $request->file('profile_image')->store('profile_images', 'public');
            $user->profile_image = $path;
        }

        $user->username = $request->username;
        $user->postal_code = $request->postal_code;
        $user->address = $request->address;
        $user->building = $request->building;
        $user->save();

        return redirect()->route('profile.edit')->with('success', 'プロフィールが更新されました。');
    }

    public function show()
{
    $user = Auth::user();
    if (!$user) {
        return redirect()->route('login');
    }

    // 出品した商品
    $exhibitedProducts = $user->exhibitedProducts;

    // 購入した商品（buyer_id を利用している場合）
    $purchasedProducts = $user->purchasedProducts()->where('user_id', '<>', $user->id)->get();

    return view('profile.show', [
        'user' => $user,
        'exhibitedProducts' => $exhibitedProducts,
        'purchasedProducts' => $purchasedProducts
    ]);
}

}
