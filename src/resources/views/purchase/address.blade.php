@extends('layouts.main_layout')

@section('styles')
    <!-- address.css を読み込む -->
    <link rel="stylesheet" href="{{ asset('css/address.css') }}">
@endsection

@section('content')
<div class="address-container">
    <h1 class="address-title">住所の変更</h1>

    <form action="#" method="POST" class="address-form">
        @csrf
        <!-- 郵便番号 -->
        <div class="address-form-group">
            <label for="postal_code" class="address-label">郵便番号</label>
            <input type="text" name="postal_code" id="postal_code" class="address-input">
        </div>
        
        <!-- 住所 -->
        <div class="address-form-group">
            <label for="address" class="address-label">住所</label>
            <input type="text" name="address" id="address" class="address-input">
        </div>

        <!-- 建物名 -->
        <div class="address-form-group">
            <label for="building" class="address-label">建物名</label>
            <input type="text" name="building" id="building" class="address-input">
        </div>

        <button type="submit" class="address-button">更新する</button>
    </form>
</div>
@endsection
