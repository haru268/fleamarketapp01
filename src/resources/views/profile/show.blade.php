{{-- resources/views/profile/show.blade.php --}}
@extends('layouts.main_layout')

@section('content')
<div class="show-container">

    {{-- ───── プロフィールカード ───── --}}
    <div class="show-profile-section">
        {{-- プロフィール画像 --}}
        <img src="{{ $user->avatar_url }}"          
     class="show-profile-image"
     alt="">

        {{-- ▼ 名前＋星を縦にスタック ▼ --}}
        <div class="name-and-stars">
            <span class="show-username">{{ $user->name }}</span>

            @if ($user->avg_rating)
                <div class="rating-stars">
                    @for ($i = 1; $i <= 5; $i++)
                        <span class="star {{ $i <= round($user->avg_rating) ? 'on' : '' }}">★</span>
                    @endfor
                </div>
            @endif
        </div>

        {{-- プロフィール編集 --}}
        <a href="{{ route('profile.edit') }}" class="show-edit-profile-button">
            プロフィールを編集
        </a>
    </div>

    {{-- ───── タブ切替 ───── --}}
    @php($page = request()->query('page', 'sell')) {{-- デフォルト＝出品 --}}
    <div class="mypage-tab-container">
        <a href="{{ route('profile.show', ['page' => 'buy']) }}"
           class="mypage-tab-button {{ $page === 'buy' ? 'active' : '' }}">
            購入した商品
        </a>

        <a href="{{ route('profile.show', ['page' => 'sell']) }}"
           class="mypage-tab-button {{ $page === 'sell' ? 'active' : '' }}">
            出品した商品
        </a>

        {{-- 取引中タブ：未読合計があれば数字バッジ --}}
        <a href="{{ route('profile.show', ['page' => 'trade']) }}"
           class="mypage-tab-button {{ $page === 'trade' ? 'active' : '' }}">
            取引中の商品
            @if ($unreadTotal)
    <span class="badge-unread-all">{{ $unreadTotal }}</span>
@endif
        </a>
    </div>

    <div class="show-divider-fullwidth"></div>

    {{-- ───── 取引中タブ ───── --}}
    @if ($page === 'trade')
        <div id="show-trade-items" class="show-trade-grid">
            @forelse ($tradingTrades as $t)
                <a href="{{ route('trades.chat', $t) }}" class="mypage-card">
                    <img src="{{ $t->product->image }}" alt="{{ $t->product->name }}">

                    <span class="mypage-card-name">
                        {{ Str::limit($t->product->name, 20) }}
                    </span>

                    {{-- 取引ごとの未読バッジ --}}
                    @if ($t->unread_count)
                        <span class="badge-unread">{{ $t->unread_count }}</span>
                    @endif
                </a>
            @empty
                <p class="col-span-full text-center text-gray-500">
                    進行中の取引はありません。
                </p>
            @endforelse
        </div>

    {{-- ───── 購入タブ ───── --}}
    @elseif ($page === 'buy')
        <div id="show-purchased-items" class="show-purchased-grid">
            @forelse ($purchasedProducts as $product)
                <div class="show-product-item">
                    <img src="{{ $product->image }}" alt="{{ $product->name }}"
                         class="show-product-image">
                    <p class="show-product-name">{{ $product->name }}</p>
                </div>
            @empty
                <p>購入した商品はまだありません。</p>
            @endforelse
        </div>

    {{-- ───── 出品タブ（デフォルト） ───── --}}
    @else
        <div id="show-exhibited-items" class="show-exhibited-grid">
            @forelse ($exhibitedProducts as $product)
                <div class="show-product-item">
                    <a href="{{ route('sell.edit', ['id' => $product->id]) }}">
                        <img src="{{ $product->image }}" alt="{{ $product->name }}"
                             class="show-product-image">
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
