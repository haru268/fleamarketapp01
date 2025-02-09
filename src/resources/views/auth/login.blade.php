@extends('layouts.auth_layout')

@section('title', 'ログイン - coachtechフリマ')

@section('content')
<div class="login-form-container">
    <h1 class="login-title">ログイン</h1>
    <form action="{{ route('login') }}" method="post">
        @csrf

        <label class="login-label">
            メールアドレス
            @error('email')
                <span class="login-error">{{ $message }}</span>
            @enderror
        </label>
        <input type="email" name="email" class="login-input" value="{{ old('email') }}">

        <label class="login-label">
            パスワード
            @error('password')
                <span class="login-error">{{ $message }}</span>
            @enderror
        </label>
        <input type="password" name="password" class="login-input">

        @if ($errors->has('login'))
            <div class="login-error">
                {{ $errors->first('login') }}
            </div>
        @endif

        <div class="button-and-link">
            <button type="submit" class="login-button">ログイン</button>
            <a href="{{ route('register') }}" class="login-link">会員登録はこちら</a>
        </div>
    </form>
</div>
@endsection
