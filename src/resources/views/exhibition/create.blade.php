@extends('layouts.main_layout')

@section('content')
<div class="create-container">
    <h1 class="create-title">{{ isset($product) ? '商品編集' : '商品出品' }}</h1>
    
    <form action="{{ isset($product) ? route('exhibition.update', $product->id) : route('exhibition.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @if(isset($product))
            @method('PUT')
        @endif

        
        <div class="create-form-group">
            <label for="image" class="create-label">商品画像</label>
            <div class="create-file-upload-container">
                <label for="image" class="create-file-upload-btn">ファイルを選択</label>
                <input type="file" id="image" name="image" accept="image/*" {{ isset($product) ? '' : 'required' }}>
                <img id="imagePreview" src="{{ isset($product) ? $product->image : '#' }}" alt="" style="max-width: 100%; height: auto; margin-top: 10px;">
            </div>
        </div>

        <div class="create-form-group">
            <p class="create-detail-title">商品の詳細</p>
            <div class="create-detail-line"></div>
        </div>

        
        <div class="create-form-group">
            <label class="create-label">カテゴリー選択</label>
            <div class="create-category-buttons">
                @foreach(['ファッション', '家電', 'インテリア', 'レディース', 'メンズ', 'コスメ', '本', 'ゲーム', 'スポーツ', 'キッチン', 'ハンドメイド', 'アクセサリー', 'おもちゃ', 'ベビー・キッズ'] as $cat)
                    <div class="create-category-button">
                        <input type="checkbox" id="category_{{ strtolower($cat) }}" name="categories[]" value="{{ strtolower($cat) }}"
                               @if(isset($product) && in_array(strtolower($cat), explode(',', $product->categories))) checked @endif>
                        <label for="category_{{ strtolower($cat) }}">{{ $cat }}</label>
                    </div>
                @endforeach
            </div>
        </div>

        
        <div class="create-form-group">
            <label for="condition" class="create-label">商品の状態</label>
            <div class="create-select-container">
                <select id="condition" name="condition" class="create-select" required>
                    <option value="" disabled {{ !isset($product) ? 'selected' : '' }}>選択してください</option>
                    <option value="良好" {{ isset($product) && $product->condition === '良好' ? 'selected' : '' }}>良好</option>
                    <option value="目立った傷や汚れなし" {{ isset($product) && $product->condition === '目立った傷や汚れなし' ? 'selected' : '' }}>目立った傷や汚れなし</option>
                    <option value="やや傷や汚れあり" {{ isset($product) && $product->condition === 'やや傷や汚れあり' ? 'selected' : '' }}>やや傷や汚れあり</option>
                    <option value="状態が悪い" {{ isset($product) && $product->condition === '状態が悪い' ? 'selected' : '' }}>状態が悪い</option>
                </select>
            </div>
        </div>

        <div class="create-form-group">
            <p class="create-section-title">商品名と説明</p>
            <div class="create-detail-line"></div>
        </div>
    
        
        <div class="create-form-group">
            <label for="name" class="create-label">商品名</label>
            <input type="text" id="name" name="name" class="create-input" placeholder="" required
                   value="{{ isset($product) ? $product->name : '' }}">
        </div>

        
        <div class="create-form-group">
            <label for="brand" class="create-label">ブランド名</label>
            <input type="text" id="brand" name="brand" class="create-input" placeholder=""
                   value="{{ isset($product) ? $product->brand : '' }}">
        </div>

        
        <div class="create-form-group">
            <label for="description" class="create-label">商品説明</label>
            <textarea id="description" name="description" class="create-textarea" placeholder="" required>{{ isset($product) ? $product->description : '' }}</textarea>
        </div>

        
        <div class="create-form-group">
            <label for="price" class="create-label">価格</label>
            <div class="create-input-group">
                <span class="create-price-prefix">¥</span>
                <input type="number" id="price" name="price" class="create-price-input" placeholder="" required
                       value="{{ isset($product) ? $product->price : '' }}">
            </div>
        </div>

        
        <button type="submit" class="create-button">{{ isset($product) ? '更新する' : '出品する' }}</button>
    </form>
</div>
@endsection

@section('scripts')
<script>
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
