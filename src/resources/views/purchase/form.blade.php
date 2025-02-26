@extends('layouts.main_layout')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/form.css') }}">

@endsection

@section('content')
<div class="form-container">
    <h1 class="form-title">購入手続き</h1>

    <h2 class="form-section-title">商品情報</h2>
    <div class="form-summary">
        <div class="form-summary-item">
            <span class="form-summary-label">商品名</span>
            <span class="form-summary-value">{{ $product->name }}</span>
        </div>
        <div class="form-summary-item">
            <span class="form-summary-label">価格</span>
            <span class="form-summary-value">¥{{ number_format($product->price) }}</span>
        </div>
    </div>

    <h2 class="form-section-title">支払い方法</h2>
    <label for="payment" class="form-label">支払い方法</label>
    <select id="payment" name="payment" class="form-select">
        <option value="credit">クレジットカード</option>
        <option value="bank">銀行振込</option>
        <option value="cod">代金引換</option>
    </select>

    <h2 class="form-section-title">配送先</h2>
    <label for="address" class="form-label">住所</label>
    <textarea id="address" name="address" class="form-textarea"></textarea>
    <!-- 変更するリンク -->
<a href="{{ route('purchase.address.form', ['id' => $product->id]) }}" class="form-change-link">変更する</a>

    <button type="submit" class="form-button">購入する</button>
</div>

@endsection
