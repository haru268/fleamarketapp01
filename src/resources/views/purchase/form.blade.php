@extends('layouts.main_layout')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/form.css') }}">
@endsection

@section('content')
<form action="#" method="POST" class="from-container">
    @csrf
    <div class="from-left">
        <div class="from-image-row">
            @if($product->image)
                <img src="{{ $product->image }}" alt="{{ $product->name }}" class="from-product-image">
            @else
                <div class="from-image-placeholder">商品画像</div>
            @endif
            <div class="from-info-top">
                <p class="from-product-name">{{ $product->name }}</p>
                <p class="from-product-price">¥{{ number_format($product->price) }}</p>
            </div>
        </div>

        <!-- 支払い方法：セレクトボックス形式 -->
        <div class="from-section from-payment">
            <label for="payment" class="from-label">支払い方法</label>
            <div class="from-select-wrapper">
                <select id="payment" name="payment" class="from-select">
                    <option value="" disabled selected>選択してください</option>
                    <option value="conv">コンビニ払い</option>
                    <option value="card">カード支払い</option>
                </select>
                <span class="from-select-arrow">▼</span>
            </div>
        </div>

        <!-- 配送先：住所表示＋「変更する」リンク -->
        <div class="from-section from-address-section">
            <label class="from-label">配送先</label>
            <div class="from-address-display">
    @if(isset($address) && $address)
        {{ optional($address)->postal_code }} 
        {{ optional($address)->address }} 
        {{ optional($address)->building }}
    @else
        未入力
    @endif
</div>

            @if(Auth::check())
                <a href="{{ route('purchase.address.form') }}" class="from-change-address-button">変更する</a>
            @else
                <a href="{{ route('register') }}" class="from-change-address-button">変更する</a>
            @endif
            <input type="hidden" name="address" id="address-input" value="">
        </div>
    </div>

    <div class="from-right">
        <div class="from-summary-box">
            <div class="from-summary-item">
                <span class="from-summary-label">商品代金</span>
                <span class="from-summary-value">¥{{ number_format($product->price) }}</span>
            </div>
            <div class="from-summary-divider"></div>
            <div class="from-summary-item">
                <span class="from-summary-label">支払い方法</span>
                <span class="from-summary-value">未選択</span>
            </div>
        </div>
        @if(Auth::check())
            <button type="submit" class="from-button">購入する</button>
        @else
            <a href="{{ route('register') }}" class="from-button">購入する</a>
        @endif
    </div>
</form>
@endsection

@section('scripts')
<script>
document.getElementById('payment').addEventListener('change', function(){
    var summaryValue = document.querySelector('.from-summary-item:nth-child(3) .from-summary-value');
    if(this.value !== ''){
        if(this.value === 'conv'){
            summaryValue.textContent = 'コンビニ払い';
        } else if(this.value === 'card'){
            summaryValue.textContent = 'カード支払い';
        }
    } else {
        summaryValue.textContent = '未選択';
    }
});
</script>
@endsection
