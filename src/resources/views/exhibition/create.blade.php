@extends('layouts.main_layout')

@section('content')
<div class="create-container">
    <h1 class="create-title">商品出品</h1>
    
    <form action="{{ route('exhibition.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <!-- 商品画像アップロード -->
        <div class="create-form-group">
            <label for="image" class="create-label">商品画像</label>
            <div class="create-file-upload-container">
                <label for="image" class="create-file-upload-btn">ファイルを選択</label>
                <input type="file" id="image" name="image" accept="image/*" required>
                <!-- ここがプレビュー用の img -->
                <img id="imagePreview" src="#" alt="" style="max-width: 100%; height: auto; margin-top: 10px;">
            </div>
        </div>

        <div class="create-form-group">
            <p class="create-detail-title">商品の詳細</p>
            <div class="create-detail-line"></div>
        </div>

        <!-- 複数選択可能なカテゴリー（ボタン形式） -->
<div class="create-form-group">
    <label class="create-label">カテゴリー選択</label>
    <div class="create-category-buttons">
        @foreach(['ファッション', '家電', 'インテリア', 'レディース', 'メンズ', 'コスメ', '本', 'ゲーム', 'スポーツ', 'キッチン', 'ハンドメイド', 'アクセサリー', 'おもちゃ', 'ベビー・キッズ'] as $category)
            <div class="create-category-button">
                <!-- チェックボックス（非表示） -->
                <input 
                    type="checkbox" 
                    id="category_{{ strtolower($category) }}" 
                    name="categories[]" 
                    value="{{ strtolower($category) }}"
                >
                <!-- ラベル全体をボタンに -->
                <label for="category_{{ strtolower($category) }}">
                    {{ $category }}
                </label>
            </div>
        @endforeach
    </div>
</div>



        <!-- 商品の状態 -->
        <div class="create-form-group">
            <label for="condition" class="create-label">商品の状態</label>
            <div class="create-select-container">
                <select id="condition" name="condition" class="create-select" required>
                    <option value="" selected disabled>選択してください</option>
                    <option value="良好">良好</option>
                    <option value="目立った傷や汚れなし">目立った傷や汚れなし</option>
                    <option value="やや傷や汚れあり">やや傷や汚れあり</option>
                    <option value="状態が悪い">状態が悪い</option>
                </select>
            </div>
        </div>

        <div class="create-form-group">
            <p class="create-section-title">商品名と説明</p>
            <div class="create-detail-line"></div>
        </div>
    
        <!-- 商品名 -->
        <div class="create-form-group">
            <label for="name" class="create-label">商品名</label>
            <input type="text" id="name" name="name" class="create-input" placeholder="" required>
        </div>

        <!-- ブランド名 -->
        <div class="create-form-group">
            <label for="brand" class="create-label">ブランド名</label>
            <input type="text" id="brand" name="brand" class="create-input" placeholder="">
        </div>

        <!-- 商品説明 -->
        <div class="create-form-group">
            <label for="description" class="create-label">商品説明</label>
            <textarea id="description" name="description" class="create-textarea" placeholder="" required></textarea>
        </div>

        <!-- 価格 -->
        <div class="create-form-group">
            <label for="price" class="create-label">価格</label>
            <div class="create-input-group">
                <span class="create-price-prefix">¥</span>
                <input type="number" id="price" name="price" class="create-price-input" placeholder="" required>
            </div>
        </div>

        <!-- 出品ボタン -->
        <button type="submit" class="create-button">出品する</button>
    </form>
</div>
@endsection

@section('scripts')
<script>
    // 「image」入力でファイルが選択されたときにプレビューを更新する (純粋なJavaScript版)
    document.getElementById('image').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (!file) return;

        const reader = new FileReader();
        reader.onload = function(event) {
            document.getElementById('imagePreview').setAttribute('src', event.target.result);
        }
        reader.readAsDataURL(file);
    });
</script>
@endsection
