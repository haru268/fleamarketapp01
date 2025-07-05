@extends('layouts.auth_layout')

@section('content')
<div class="register-form-container">
    <h1 class="register-title">会員登録</h1>
    <form action="{{ route('register') }}" method="post">
        @csrf

        <label class="register-label">
            ユーザー名
            @error('name')
                <span class="register-error">{{ $message }}</span>
            @enderror
        </label>
        <input type="text" name="username" class="register-input" value="{{ old('name') }}"  autofocus>

        <label class="register-label">
            メールアドレス
            @error('email')
                    <span class="register-error">{{ $message }}</span>
            @enderror
        </label>
        <input type="email" name="email" class="register-input" value="{{ old('email') }}">

        <label class="register-label">
            パスワード
            @error('password')
                <span class="register-error">{{ $message }}</span>
            @enderror
        </label>
        <input type="password" name="password" class="register-input">

        <label class="register-label">
            確認用パスワード
            @error('password_confirmation')
                <span class="register-error">{{ $message }}</span>
            @enderror
        </label>
        <input type="password" name="password_confirmation" class="register-input">

        <button type="submit" class="register-button">登録する</button>
    </form>
    <a href="{{ route('login') }}" class="register-login-link">ログインはこちら</a>
</div>
@endsection
