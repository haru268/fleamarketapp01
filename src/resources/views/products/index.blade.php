@extends('layouts.main_layout')

@section('styles')
    <!-- index.css を読み込み -->
    <link rel="stylesheet" href="{{ asset('css/index.css') }}">
@endsection

@section('content')
<div class="index-container">
    <!-- タブ選択セクション -->
    <div class="index-tab-container">
        <button id="index-tab-recommended" class="index-tab-button active" onclick="switchTab('recommended')">おすすめ</button>
        <button id="index-tab-mylist" class="index-tab-button" onclick="switchTab('mylist')">マイリス</button>
    </div>
    <div class="index-divider-fullwidth"></div>

    <!-- おすすめ商品のリスト（全商品表示） -->
    <div id="index-recommended-section" style="display: block;">
        @if(isset($recommendedProducts) && $recommendedProducts->count() > 0)
            @foreach($recommendedProducts as $product)
                <div class="index-product-card">
                    <div class="index-product-image-container">
                        <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="index-product-image">
                        @if($product->is_sold)
                            <div class="index-sold-overlay">Sold</div>
                        @endif
                    </div>
                    <div class="index-product-name">{{ $product->name }}</div>
                    <div class="index-product-price">{{ $product->price }}円</div>
                </div>
            @endforeach
        @else
            <p>現在、おすすめの商品はありません。</p>
        @endif
    </div>

    <!-- マイリス（いいねした商品のみ表示） -->
    <div id="index-mylist-section" style="display: none;">
        @if(Auth::check())
            @if(isset($likedProducts) && $likedProducts->count() > 0)
                @foreach($likedProducts as $product)
                    <div class="index-product-card">
                        <div class="index-product-image-container">
                            <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="index-product-image">

                            @if($product->is_sold)
                                <div class="index-sold-overlay">Sold</div>
                            @endif
                        </div>
                        <div class="index-product-name">{{ $product->name }}</div>
                        <div class="index-product-price">{{ $product->price }}円</div>
                    </div>
                @endforeach
            @else
                <p>いいねした商品はありません。</p>
            @endif
        @else
            <p>マイリスはログインしたユーザーのみ表示されます。</p>
        @endif
    </div>
</div>

<script>
function switchTab(tab) {
    if (tab === 'recommended') {
        document.getElementById('index-recommended-section').style.display = 'block';
        document.getElementById('index-mylist-section').style.display = 'none';
        document.getElementById('index-tab-recommended').classList.add('active');
        document.getElementById('index-tab-mylist').classList.remove('active');
    } else if (tab === 'mylist') {
        document.getElementById('index-recommended-section').style.display = 'none';
        document.getElementById('index-mylist-section').style.display = 'block';
        document.getElementById('index-tab-mylist').classList.add('active');
        document.getElementById('index-tab-recommended').classList.remove('active');
    }
}
</script>
@endsection
