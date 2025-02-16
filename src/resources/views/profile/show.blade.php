@extends('layouts.main_layout')

@section('content')
<div class="show-container">
    <!-- プロフィール情報セクション -->
    <div class="show-profile-section">
        <img src="{{ $user->profile_image ? asset('storage/' . $user->profile_image) : asset('images/default-profile.png') }}" alt="プロフィール画像" class="show-profile-image">
        <span class="show-username">{{ $user->name }}</span>
        <a href="{{ route('profile.edit') }}" class="show-edit-profile-button">プロフィールを編集</a>
    </div>
    
    <!-- 商品一覧表示セクション -->
    <div class="show-items-container">
        <div class="show-list-title show-active" id="show-exhibited" onclick="switchTab('exhibited');">出品した商品</div>
        <div class="show-list-title" id="show-purchased" onclick="switchTab('purchased');">購入した商品</div>
    </div>
    <div class="show-divider-fullwidth"></div> <!-- 黒い線を追加 -->

    <!-- 出品した商品のリストを表示 -->
    <div id="show-exhibited-items" style="display: block;">
        @forelse ($user->exhibitedProducts as $product)
            <div class="product-item">
                <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="product-image">
                <p class="product-name">{{ $product->name }}</p>
            </div>
        @empty
            <p>出品した商品はまだありません。</p>
        @endforelse
    </div>

    <!-- 購入した商品のリストを表示 -->
    <div id="show-purchased-items" style="display: none;">
        <p>購入した商品の内容がここに表示されます。</p>
    </div>
</div>

<script>
function switchTab(tab) {
    if (tab === 'exhibited') {
        document.getElementById('show-exhibited-items').style.display = 'block';
        document.getElementById('show-purchased-items').style.display = 'none';
        document.getElementById('show-exhibited').classList.add('show-active');
        document.getElementById('show-purchased').classList.remove('show-active');
    } else {
        document.getElementById('show-exhibited-items').style.display = 'none';
        document.getElementById('show-purchased-items').style.display = 'block';
        document.getElementById('show-purchased').classList.add('show-active');
        document.getElementById('show-exhibited').classList.remove('show-active');
    }
}
</script>
@endsection
