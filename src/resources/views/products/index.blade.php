@extends('layouts.main_layout')

@section('styles')
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

    <!-- おすすめ商品のリスト -->
    <div id="index-recommended-section" class="index-recommended-grid" style="display: grid;">
        @if(isset($recommendedProducts) && $recommendedProducts->count() > 0)
            @foreach($recommendedProducts as $product)
                <div class="index-product-card">
                    <a href="{{ url('/item/' . $product->id) }}">
                    <div class="index-product-image-container">
                        <!-- ここでは $product->image をそのまま使用 -->
                        <img src="{{ $product->image }}" alt="{{ $product->name }}" class="index-product-image">
                        @if($product->is_sold)
                            <div class="index-sold-overlay">Sold</div>
                        @endif
                    </div>
                    </a>
                    <div class="index-product-name">{{ $product->name }}</div>
                </div>
            @endforeach
        @else
            <p>おすすめの商品はありません。</p>
        @endif
    </div>

    <!-- マイリス（いいねした商品のみ表示） -->
    <div id="index-mylist-section" class="index-recommended-grid" style="display: none;">
        @if(Auth::check())
            @if(isset($likedProducts) && $likedProducts->count() > 0)
                @foreach($likedProducts as $product)
                    <div class="index-product-card">
                        <a href="{{ url('/item/' . $product->id) }}">
                        <div class="index-product-image-container">
                            <img src="{{ $product->image }}" alt="{{ $product->name }}" class="index-product-image">
                            @if($product->is_sold)
                                <div class="index-sold-overlay">Sold</div>
                            @endif
                        </div>
                        </a>
                        <div class="index-product-name">{{ $product->name }}</div>
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
        document.getElementById('index-recommended-section').style.display = 'grid';
        document.getElementById('index-mylist-section').style.display = 'none';
        document.getElementById('index-tab-recommended').classList.add('active');
        document.getElementById('index-tab-mylist').classList.remove('active');
    } else if (tab === 'mylist') {
        document.getElementById('index-recommended-section').style.display = 'none';
        document.getElementById('index-mylist-section').style.display = 'grid';
        document.getElementById('index-tab-mylist').classList.add('active');
        document.getElementById('index-tab-recommended').classList.remove('active');
    }
}
</script>
@endsection
