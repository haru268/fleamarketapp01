@extends('layouts.main_layout')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/index.css') }}">
@endsection

@section('content')
<div class="index-container">
    
    <div class="index-tab-container">
        <a id="index-tab-recommended"
           class="index-tab-button {{ request()->query('page') !== 'mylist' ? 'active' : '' }}"
           href="{{ route('home') }}">おすすめ</a>
        <a id="index-tab-mylist"
           class="index-tab-button {{ request()->query('page') === 'mylist' ? 'active' : '' }}"
           href="{{ route('home', ['page' => 'mylist']) }}">マイリス</a>
    </div>
    <div class="index-divider-fullwidth"></div>

    
    @if(request()->query('page') !== 'mylist')
    <div id="index-recommended-section" style="display: block;">
        <div class="index-recommended-grid">
            @if(isset($recommendedProducts) && $recommendedProducts->count() > 0)
                @foreach($recommendedProducts as $product)
                    <div class="index-product-card">
                        <div class="index-product-image-container">
                            <a href="{{ route('item.show', ['id' => $product->id]) }}">
                                <img src="{{ $product->image }}" alt="{{ $product->name }}" class="index-product-image">
                            </a>
                            @if($product->is_sold)
                                <div class="index-sold-overlay">Sold</div>
                            @endif
                        </div>
                        <div class="index-product-name">{{ $product->name }}</div>
                        
                    </div>
                @endforeach
            @else
                <p>現在、おすすめの商品はありません。</p>
            @endif
        </div>
    </div>
    @endif

    
    @if(request()->query('page') === 'mylist')
<div id="index-mylist-section" style="display: block;">
    @if(Auth::check())
        @if(isset($likedProducts) && $likedProducts->count() > 0)
            <div class="index-recommended-grid">
                @foreach($likedProducts as $product)
                    <div class="index-product-card">
                        <div class="index-product-image-container">
                            <a href="{{ route('item.show', ['id' => $product->id]) }}">
                                <img src="{{ $product->image }}" alt="{{ $product->name }}" class="index-product-image">
                            </a>
                            @if($product->is_sold)
                                <div class="index-sold-overlay">Sold</div>
                            @endif
                        </div>
                        <div class="index-product-name">{{ $product->name }}</div>
                    </div>
                @endforeach
            </div>
        @else
            <p>いいねした商品はありません。</p>
        @endif
    @else
        <p>マイリスはログインしたユーザーのみ表示されます。</p>
    @endif
</div>
@endif


</div>
@endsection
