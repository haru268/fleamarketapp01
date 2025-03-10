@extends('layouts.main_layout')

@section('content')
<div class="item-container">
    
    <div class="item-left">
        @if($product->image)
            <img src="{{ $product->image }}" alt="{{ $product->name }}" class="item-image">
        @else
            <div class="item-image-placeholder">商品画像</div>
        @endif
    </div>
    
    <div class="item-right">
        <h1 class="item-title">{{ $product->name }}</h1>
        <p class="item-brand">{{ $product->brand ?? 'ブランド未登録' }}</p>
        <p class="item-price">
            ￥{{ number_format($product->price) }}
            <span class="item-tax">(税込)</span>
        </p>

        <div class="item-icons">
            <div class="item-icon-wrapper">
                <!-- いいねボタンに data-product-id を追加 -->
                <span class="item-icon" id="likeIcon" data-product-id="{{ $product->id }}">
                    {{ Auth::check() && $product->likedBy(Auth::user()) ? '★' : '☆' }}
                </span>
                <span class="item-icon-count" id="likeCount">{{ $product->likes()->count() }}</span>
            </div>
            <div class="item-icon-wrapper">
                <span class="item-icon" id="commentIcon">💬</span>
                <span class="item-icon-count" id="commentCount">{{ $product->comments->count() }}</span>
            </div>
        </div>
        
        <div class="item-purchase-wrap">
            <a href="{{ route('purchase.form', ['id' => $product->id]) }}" class="item-purchase-button">
                購入手続きへ
            </a>
        </div>
        
        <h2 class="item-section-title">商品説明</h2>
        <div class="item-description">
            {{ $product->description }}
        </div>
        
        <h2 class="item-section-title">商品の情報</h2>
        <div class="item-info">
            <div class="item-info-row">
                <span class="item-info-label">カテゴリー</span>
                <div class="item-info-category">
                    @php
                        $categoriesArray = $product->categories ? explode(',', $product->categories) : [];
                    @endphp
                    @forelse($categoriesArray as $cat)
                        <span class="item-info-badge">{{ $cat }}</span>
                    @empty
                        <span class="item-info-badge">未設定</span>
                    @endforelse
                </div>
            </div>
            <div class="item-info-row">
                <span class="item-info-label">商品の状態</span>
                <span class="item-info-condition">
                    {{ $product->condition ?? '未設定' }}
                </span>
            </div>
        </div>
        
        @php
            $commentCount = $product->comments->count();
        @endphp
        <h2 class="item-section-title">コメント ({{ $commentCount }})</h2>
        <div class="item-comment-list">
            @forelse($product->comments as $comment)
                <div class="item-comment-box">
                    <div class="item-comment-header">
                        <div class="item-comment-avatar">
                            @if($comment->user && $comment->user->profile_image)
                                <img src="{{ asset('storage/' . $comment->user->profile_image) }}" alt="avatar">
                            @else
                                <img src="{{ asset('images/default-user.png') }}" alt="avatar">
                            @endif
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
        
        <h3 class="item-comment-title">商品へのコメント</h3>
        <textarea id="comment-input" class="item-comment-input" rows="3"></textarea>
        <button type="button" id="commentSubmit" class="item-comment-submit">コメントを送信する</button>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.getElementById('commentSubmit').addEventListener('click', function() {
    const commentBody = document.getElementById('comment-input').value;
    if (!commentBody.trim()) {
        alert('コメントを入力してください');
        return;
    }

    fetch("{{ route('comment.store') }}", {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': "{{ csrf_token() }}"
        },
        body: JSON.stringify({
            product_id: {{ $product->id }},
            body: commentBody
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.error) {
            alert(data.error);
            return;
        }
        const commentList = document.querySelector('.item-comment-list');
        const avatarUrl = data.avatar ? data.avatar : "{{ asset('images/default-user.png') }}";
        const newCommentHTML = `
            <div class="item-comment-box">
                <div class="item-comment-avatar">
                    <img src="${avatarUrl}" alt="avatar">
                </div>
                <div class="item-comment-content">
                    <div class="item-comment-user">${data.user}</div>
                    <div class="item-comment-body">${data.body}</div>
                </div>
            </div>
        `;
        commentList.insertAdjacentHTML('afterbegin', newCommentHTML);
        document.getElementById('comment-input').value = '';

        // コメント数更新
        const titleEl = document.querySelector('.item-section-title');
        if (titleEl && titleEl.textContent.includes('コメント (')) {
            const match = titleEl.textContent.match(/コメント\s*\((\d+)\)/);
            if (match) {
                const newCount = parseInt(match[1], 10) + 1;
                titleEl.textContent = `コメント (${newCount})`;
            }
        }
        const iconCountEl = document.querySelector('.item-comment-count');
        if (iconCountEl) {
            const newIconCount = parseInt(iconCountEl.textContent, 10) + 1;
            iconCountEl.textContent = newIconCount;
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('コメント投稿に失敗しました。');
    });
});

// Ajax でいいねの状態を切り替える
document.getElementById('likeIcon').addEventListener('click', function() {
    const likeIcon = this;
    const likeCountElem = document.getElementById('likeCount');
    const productId = likeIcon.getAttribute('data-product-id');
    fetch("{{ route('like.toggle') }}", {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': "{{ csrf_token() }}"
        },
        body: JSON.stringify({ product_id: productId })
    })
    .then(response => response.json())
    .then(data => {
        if(data.success) {
            if(data.liked) {
                likeIcon.textContent = '★';
                likeIcon.classList.add('liked');
            } else {
                likeIcon.textContent = '☆';
                likeIcon.classList.remove('liked');
            }
            likeCountElem.textContent = data.like_count;
        }
    })
    .catch(error => {
        console.error('Error:', error);
    });
});

document.getElementById('commentIcon').addEventListener('click', function() {
    document.getElementById('comment-input').focus();
});
</script>
@endsection
