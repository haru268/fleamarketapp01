@extends('layouts.main_layout')

@section('content')
<div class="show-container">
    
    <div class="show-profile-section">
        <img src="{{ $user->profile_image ? asset('storage/' . $user->profile_image) : asset('images/default-profile.png') }}" alt="" class="show-profile-image">
        <span class="show-username">{{ $user->name }}</span>
        <a href="{{ route('profile.edit') }}" class="show-edit-profile-button">プロフィールを編集</a>
    </div>

    
    <div class="mypage-tab-container">
    
    <a href="{{ route('profile.show', ['page' => 'buy']) }}"
       class="mypage-tab-button {{ request()->query('page') === 'buy' ? 'active' : '' }}">
        購入した商品
    </a>
    
    <a href="{{ route('profile.show', ['page' => 'sell']) }}"
       class="mypage-tab-button {{ request()->query('page') === 'sell' ? 'active' : '' }}">
        出品した商品
    </a>
</div>

    <div class="show-divider-fullwidth"></div>

    
    @if(request()->query('page') === 'buy')
        <div id="show-purchased-items" class="show-purchased-grid">
            @forelse ($purchasedProducts as $product)
                <div class="show-product-item">
                    <img src="{{ $product->image }}" alt="{{ $product->name }}" class="show-product-image">
                    <p class="show-product-name">{{ $product->name }}</p>
                </div>
            @empty
                <p>購入した商品はまだありません。</p>
            @endforelse
        </div>
    @else
        
<div id="show-exhibited-items" class="show-exhibited-grid">
    @forelse ($exhibitedProducts as $product)
        <div class="show-product-item">
    <a href="{{ route('sell.edit', ['id' => $product->id]) }}">
        <img src="{{ $product->image }}" alt="{{ $product->name }}" class="show-product-image">
    </a>
    <p class="show-product-name">{{ $product->name }}</p>
</div>

    @empty
        <p>出品した商品はまだありません。</p>
    @endforelse
</div>
    @endif
</div>
@endsection
