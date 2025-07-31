{{-- resources/views/trades/chat.blade.php ----------------------------------}}
@extends('layouts.main_layout')

@push('styles')
    {{-- サイドバーやチャット用のスタイル --}}
    <link rel="stylesheet" href="{{ asset('css/sidebar.css') }}">
@endpush

@section('content')
<div class="trade-wrapper">

    {{--──────── サイドバー ────────--}}
    <aside class="trade-sidebar">
        <h2 class="trade-sidebar__title">その他の取引</h2>
        @foreach ($sidebarTrades as $t)
            @php $active = $t->id === $trade->id; @endphp
            <a href="{{ route('trades.chat', $t) }}"
               class="trade-sidebar__item {{ $active ? 'is-active' : '' }}">
                <span class="trade-sidebar__name">{{ Str::limit($t->product->name, 18) }}</span>
                @if ($t->unread_count)
     <span class="badge-unread-side">{{ $t->unread_count }}</span>
 @endif
            </a>
        @endforeach
    </aside>

    {{--──────── メイン ────────--}}
    <section class="trade-main">

        {{-- ★ 相手評価状況でモーダル表示を決定 --}}
        @php
            $buyerRated  = $trade->ratings()->where('rater_id', $trade->buyer_id)->exists();
            $sellerRated = $trade->ratings()->where('rater_id', $trade->seller_id)->exists();

            /* 条件
               1) 買い手  … 自分が未評価
               2) 出品者  … 買い手が評価済 & 自分が未評価
            */
            $showRatingModal =
                (auth()->id() === $trade->buyer_id  && !$buyerRated) ||
                (auth()->id() === $trade->seller_id &&  $buyerRated && !$sellerRated);
        @endphp

        {{-- ① ヘッダー --}}
        <header class="trade-header">
            <div class="trade-header__user">
                <img src="{{ optional($otherUser)->avatar_url }}" class="user-avatar" alt="">
                <h2>「{{ optional($otherUser)->name ?? '（相手未定）' }}」 さんとの取引画面</h2>
            </div>

            {{-- 買い手だけに表示する “取引完了” ボタン --}}
            @if (auth()->id() === $trade->buyer_id && !$buyerRated && $trade->status === 'progress')
                <button id="openRatingModal" class="btn-complete">取引を完了する</button>
            @endif
        </header>

        {{-- ② 商品概要 --}}
        <div class="product-summary">
            <img src="{{ $trade->product->image }}" class="product-summary__img" alt="">
            <div>
                <p class="product-summary__name">{{ $trade->product->name }}</p>
                <p class="product-summary__price">￥{{ number_format($trade->product->price) }}</p>
            </div>
        </div>

        {{-- ③ メッセージ一覧 --}}
        <div class="msg-list" id="msgList">
            @foreach ($messages as $msg)
                @php $mine = $msg->user_id === auth()->id(); @endphp
                <div class="msg-item {{ $mine ? 'me' : 'other' }}">
                    <div class="msg-meta {{ $mine ? 'me' : '' }}">
                        <img src="{{ $msg->user->avatar_url }}" class="user-avatar-sm" alt="">
                        <span class="msg-name">{{ $msg->user->name }}</span>
                    </div>

                    {{-- 本文 & 画像（本文が空なら &nbsp; を入れて幅ゼロ回避） --}}
                    <div class="chat-bubble {{ $mine ? 'me' : '' }}">
                        {!! $msg->body !== '' ? nl2br(e($msg->body)) : '&nbsp;' !!}
                        @if ($msg->image)
                            <img src="{{ Storage::url($msg->image) }}" class="msg-img" alt="">
                        @endif
                    </div>

                    {{-- 自分のメッセージだけ編集・削除 --}}
                    @if ($mine)
                        <div class="msg-action-area">
                            <form action="{{ route('trades.messages.edit', [$trade,$msg]) }}" method="GET">
                                @csrf <button class="msg-action">編集</button>
                            </form>
                            <form action="{{ route('trades.messages.destroy', [$trade,$msg]) }}" method="POST">
                                @method('DELETE') @csrf <button class="msg-action">削除</button>
                            </form>
                        </div>
                    @endif
                </div>
            @endforeach
        </div>

        {{-- ④ 送信フォーム --}}
        {{-- バリデーションエラー --}}
        @if ($errors->any())
            <div class="alert-error">
                @foreach ($errors->all() as $err)
                    <p>{{ $err }}</p>
                @endforeach
            </div>
        @endif

        <form class="msg-form" action="{{ route('trades.messages.store', $trade) }}"
              method="POST" enctype="multipart/form-data">
            @csrf
            <textarea name="body" placeholder="取引メッセージを記入してください">{{ old('body') }}</textarea>

            <img id="preview" class="msg-img" style="display:none">

            <label class="btn-image">画像を追加
                <input type="file" name="image" id="imageInput" accept="image/*" hidden>
            </label>

            <button class="btn-send">
                <svg viewBox="0 0 24 24"><path d="M2.01 21 23 12 2.01 3 2 10l15 2-15 2z"/></svg>
            </button>
        </form>

    </section>
</div>

{{--──────── 評価モーダル ────────--}}
@if ($showRatingModal)
<div id="ratingModal"
     class="modal {{ auth()->id() === $trade->buyer_id ? 'hidden' : '' }}">
    <div class="modal-card">
        <h3 class="modal-title">取引が完了しました。</h3>
        <p class="modal-sub">今回の取引相手はどうでしたか？</p>

        <form method="POST" action="{{ route('trades.complete', $trade) }}">
            @csrf
            <div class="stars">
                @for ($i = 1; $i <= 5; $i++)
                    <input type="radio" id="star{{ $i }}" name="score" value="{{ $i }}" hidden>
                    <label for="star{{ $i }}">★</label>
                @endfor
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn-rating">送信する</button>
            </div>
        </form>
    </div>
</div>
@endif
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {


    const key = 'draft-{{ $trade->id }}';
    const ta  = document.querySelector('textarea[name="body"]');
    if (ta) {
        ta.value = localStorage.getItem(key) ?? '';
        ta.addEventListener('input', e => localStorage.setItem(key, e.target.value));
        document.querySelector('.msg-form')
                .addEventListener('submit', () => localStorage.removeItem(key));
    }

    const openBtn = document.getElementById('openRatingModal');
    const modal   = document.getElementById('ratingModal');
    if (openBtn && modal){
        openBtn.addEventListener('click', () => modal.classList.remove('hidden'));
    }


    const labels = document.querySelectorAll('.stars label');
    document.querySelectorAll('input[name="score"]').forEach(radio => {
        radio.addEventListener('change', e => {
            const val = parseInt(e.target.value, 10);
            labels.forEach((lb, i) => {
                lb.style.color = i < val ? '#facc15' : '#d1d5db';
            });
        });
    });

    
    const input  = document.getElementById('imageInput');   
    const prev   = document.getElementById('preview');     
    if (input && prev) {                                   
        input.addEventListener('change', e => {             
            const file = e.target.files[0];                
            if (file) {                                     
                prev.src = URL.createObjectURL(file);       
                prev.style.display = 'block';              
            } else {                                        
                prev.style.display = 'none';                
            }                                               
        });                                                
    }                                                      
});
</script>
@endpush
