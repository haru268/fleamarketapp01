<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    /** 会員登録フォーム */
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    /** 会員登録処理 */
    public function register(RegisterRequest $request)
    {
        // username → name へマッピング
        $user = User::create([
            'name'     => $request->username,          // ← ここがポイント
            'email'    => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // 自動ログイン
        Auth::login($user);

        return redirect()->route('profile.edit');     // 初回プロフィール編集へ
    }
}
