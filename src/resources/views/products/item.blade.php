{{-- resources/views/products/item.blade.php --}}
@extends('layouts.main_layout')

@section('content')
<div class="item-container">

    {{-- ==== 左カラム：商品画像 ==== --}}
    <div class="item-left">
        @if ($product->image)
            <img src="{{ $product->image }}" alt="{{ $product->name }}" class="item-image">
        @else
            <div class="item-image-placeholder">商品画像</div>
        @endif
    </div>

    {{-- ==== 右カラム：詳細 ==== --}}
    <div class="item-right">
        <h1 class="item-title">{{ $product->name }}</h1>
        <p class="item-brand">{{ $product->brand ?? 'ブランド未登録' }}</p>
        <p class="item-price">
            ￥{{ number_format($product->price) }}
            <span class="item-tax">(税込)</span>
        </p>

        {{-- --- いいね & コメント数 --- --}}
        <div class="item-icons">
            <div class="item-icon-wrapper">
                <span class="item-icon"
                      id="likeIcon"
                      data-product-id="{{ $product->id }}">
                    {{ Auth::check() && $product->likedBy(Auth::user()) ? '★' : '☆' }}
                </span>
                <span class="item-icon-count"
                      id="likeCount">{{ $product->likes()->count() }}</span>
            </div>
            <div class="item-icon-wrapper">
                <span class="item-icon" id="commentIcon">💬</span>
                <span class="item-icon-count item-comment-count"
                      id="commentCount">{{ $product->comments->count() }}</span>
            </div>
        </div>

        <div class="item-purchase-wrap">
            <a href="{{ route('purchase.form', ['id' => $product->id]) }}"
               class="item-purchase-button">購入手続きへ</a>
        </div>

        {{-- --- 商品説明 --- --}}
        <h2 class="item-section-title">商品説明</h2>
        <div class="item-description">
            {{ $product->description }}
        </div>

        {{-- --- 商品情報 --- --}}
        <h2 class="item-section-title">商品の情報</h2>
        <div class="item-info">
            <div class="item-info-row">
                <span class="item-info-label">カテゴリー</span>
                <div class="item-info-category">
                    @php
                        $categoriesArray = $product->categories
                                        ? explode(',', $product->categories)
                                        : [];
                    @endphp
                    @forelse ($categoriesArray as $cat)
                        <span class="item-info-badge">{{ $cat }}</span>
                    @empty
                        <span class="item-info-badge">未設定</span>
                    @endforelse
                </div>
            </div>
            <div class="item-info-row">
                <span class="item-info-label">商品の状態</span>
                <span class="item-info-condition">{{ $product->condition ?? '未設定' }}</span>
            </div>
        </div>

        {{-- --- コメント一覧 --- --}}
        @php $commentCount = $product->comments->count(); @endphp
        <h2 class="item-section-title">コメント ({{ $commentCount }})</h2>

        <div class="item-comment-list">
            @forelse ($product->comments as $comment)
                <div class="item-comment-box">
                    <div class="item-comment-header">
                        <div class="item-comment-avatar">
                            <img src="{{ ($comment->user && $comment->user->profile_image)
                                         ? asset('storage/'.$comment->user->profile_image)
                                         : asset('images/default-user.png') }}"
                                 alt="avatar">
                        </div>
                        <div class="item-comment-user">
                            {{ $comment->user->name ?? 'admin' }}
                        </div>
                    </div>
                    <div class="item-comment-body">
                        {{ $comment->body }}
                    </div>
                </div>
            @empty
                <p class="item-comment-empty">まだコメントはありません。</p>
            @endforelse
        </div>

        {{-- --- コメント投稿フォーム --- --}}
        <h3 class="item-comment-title">商品へのコメント</h3>
        <textarea id="comment-input" class="item-comment-input" rows="3"></textarea>
        <button type="button" id="commentSubmit" class="item-comment-submit">
            コメントを送信する
        </button>
    </div>
</div>
@endsection


@push('scripts')
<script>
document.getElementById('commentSubmit').addEventListener('click', () => {
    const body = document.getElementById('comment-input').value.trim();
    if (!body) return alert('コメントを入力してください');

    fetch("{{ route('comments.store', $product) }}", {
        method : 'POST',
        headers: {
            'Content-Type' : 'application/json',
            'Accept'       : 'application/json',   // ←★ 追加ポイント
            'X-CSRF-TOKEN' : "{{ csrf_token() }}"
        },
        body: JSON.stringify({ body })
    })
    .then(r => r.json())              // ← JSON を確実に受け取れる
    .then(d => {
        if (!d.success) return alert(d.info ?? '投稿失敗');

        /* ----- コメントを DOM に追加 ----- */
        const list = document.querySelector('.item-comment-list');
        list.insertAdjacentHTML('afterbegin', `
            <div class="item-comment-box">
              <div class="item-comment-header">
                <div class="item-comment-avatar">
                  <img src="${d.avatar}" alt="avatar">
                </div>
                <div class="item-comment-user">${d.user}</div>
              </div>
              <div class="item-comment-body">${d.body}</div>
            </div>`);

        /* 入力欄クリア & 件数 +1 */
        document.getElementById('comment-input').value = '';
        const cnt    = document.getElementById('commentCount');
        cnt.textContent = +cnt.textContent + 1;
        document.querySelector('.item-section-title')
                .textContent = `コメント (${cnt.textContent})`;
    })
    .catch(() => alert('通信エラーが発生しました'));   // ← ここは例外時のみ
});
</script>
@endpush
