@extends('layouts.main_layout')

@section('content')
<div class="show-container">
    <!-- プロフィール情報セクション -->
    <div class="show-profile-section">
        <img src="{{ $user->profile_image ? asset('storage/' . $user->profile_image) : asset('images/default-profile.png') }}" alt="プロフィール画像" class="show-profile-image">
        <span class="show-username">{{ $user->name }}</span>
        <a href="{{ route('profile.edit') }}" class="show-edit-profile-button">プロフィールを編集</a>
    </div>

    <!-- タブ選択 -->
    <div class="show-items-container">
        <div class="show-list-title show-active" id="show-exhibited" onclick="switchTab('exhibited')">出品した商品</div>
        <div class="show-list-title" id="show-purchased" onclick="switchTab('purchased')">購入した商品</div>
    </div>
    <div class="show-divider-fullwidth"></div>

    <!-- 出品した商品 -->
    <div id="show-exhibited-items" class="show-exhibited-grid">
        @forelse ($exhibitedProducts as $product)
            <div class="show-product-item">
                <img src="{{ $product->image }}" alt="{{ $product->name }}" class="show-product-image">
                <p class="show-product-name">{{ $product->name }}</p>
            </div>
        @empty
            <p>出品した商品はまだありません。</p>
        @endforelse
    </div>

    <!-- 購入した商品 -->
    <div id="show-purchased-items" class="show-purchased-grid" style="display: none;">
        @forelse ($purchasedProducts as $product)
            <div class="show-product-item">
                <img src="{{ $product->image }}" alt="{{ $product->name }}" class="show-product-image">
                <p class="show-product-name">{{ $product->name }}</p>
            </div>
        @empty
            <p>購入した商品はまだありません。</p>
        @endforelse
    </div>
</div>

<script>
function switchTab(tab) {
    if (tab === 'exhibited') {
        document.getElementById('show-exhibited-items').style.display = 'grid';
        document.getElementById('show-purchased-items').style.display = 'none';
        document.getElementById('show-exhibited').classList.add('show-active');
        document.getElementById('show-purchased').classList.remove('show-active');
    } else {
        document.getElementById('show-exhibited-items').style.display = 'none';
        document.getElementById('show-purchased-items').style.display = 'grid';
        document.getElementById('show-purchased').classList.add('show-active');
        document.getElementById('show-exhibited').classList.remove('show-active');
    }
}
</script>
@endsection
