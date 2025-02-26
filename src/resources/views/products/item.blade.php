@extends('layouts.main_layout')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/item.css') }}">
@endsection

@section('content')
<div class="item-container">
    <!-- 左カラム：商品画像 -->
    <div class="item-left">
        @if($product->image)
            <img src="{{ $product->image }}" alt="{{ $product->name }}" class="item-image">
        @else
            <div class="item-image-placeholder">商品画像</div>
        @endif
    </div>

    <!-- 右カラム：商品詳細 -->
    <div class="item-right">
        <h1 class="item-title">{{ $product->name }}</h1>
        <p class="item-brand">{{ $product->brand ?? 'ブランド未登録' }}</p>
        <p class="item-price">
            ￥{{ number_format($product->price) }}<span class="item-tax">(税込)</span>
        </p>
        <!-- アイコン部分 -->
        <div class="item-icons">
            <span class="item-icon">☆</span>
            <span class="item-icon">💬</span>
        </div>
        <!-- 購入手続きボタン（アイコンの下に配置） -->
        <div class="item-purchase-wrap">
    <!-- ボタンをリンクとして実装 -->
    <a href="{{ route('purchase.form', ['id' => $product->id]) }}" class="item-purchase-button">
        購入手続きへ
    </a>
</div>
        <h2 class="item-section-title">商品説明</h2>
        <div class="item-description">
            {{ $product->description }}
        </div>
        <h2 class="item-section-title">商品の情報</h2>
        <ul class="item-info-list">
            <li>カテゴリー: {{ $product->categories ?? '未設定' }}</li>
            <li>商品の状態: {{ $product->condition ?? '未設定' }}</li>
        </ul>
        <h2 class="item-section-title">コメント</h2>
        <div class="item-comment">
            <span class="item-comment-user">admin</span>
            <p class="item-comment-body">商品のコメント</p>
        </div>
        <div class="item-comment-input-wrap">
            <label for="comment-input" class="item-comment-label">商品へのコメント</label>
            <textarea id="comment-input" class="item-comment-input" rows="2"></textarea>
            <button class="item-comment-submit">コメントを送信する</button>
        </div>
    </div>
</div>
@endsection
