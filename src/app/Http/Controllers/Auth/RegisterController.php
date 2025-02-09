<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest; // 事前に定義したバリデーションルールを使用
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    // 会員登録フォームを表示するメソッド
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    // 会員登録処理を行うメソッド
    public function register(RegisterRequest $request) 
    {

        dd($request->all());
        
        // User モデルを使ってデータベースに新しいユーザーを保存
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'username' => $request->username, // フォームで受け取ったユーザー名
            'password' => Hash::make($request->password), // パスワードはハッシュ化して保存
        ]);

        // 新しく登録されたユーザーで自動ログイン
        Auth::login($user);

        // 登録後、プロフィール編集ページにリダイレクト
        return redirect()->route('profile.show'); // リダイレクト先をプロフィール編集ページに設定
    }
}
