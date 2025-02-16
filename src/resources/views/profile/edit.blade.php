@extends('layouts.main_layout')

@section('content')
<div class="edit-container">
    <h1 class="edit-title">プロフィール設定</h1>
    
    <!-- バリデーションエラー表示 -->
    @if ($errors->any())
    <div class="alert alert-danger">
        @foreach ($errors->all() as $error)
            <p>{{ $error }}</p>
        @endforeach
    </div>
    @endif

    <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data" class="edit-form">
        @csrf

        <!-- プロフィール画像フィールド -->
        <div class="edit-profile-image-container">
            @if(auth()->user()->profile_image)
                <img id="profile-image-preview" 
                     src="{{ asset('storage/' . auth()->user()->profile_image) }}" 
                     alt="profile image" class="edit-profile-image">
            @else
                <img id="profile-image-preview" 
                     src="{{ asset('images/default-placeholder.png') }}" 
                     alt="default profile image" class="edit-profile-image">
            @endif

            <!-- 「画像を選択する」ボタンで input[type="file"] をクリック -->
            <button type="button" 
                    onclick="document.getElementById('edit-profile_image').click();" 
                    class="file-input-label">
                画像を選択する
            </button>

            <!-- 画像ファイルの input -->
            <input type="file" 
                   id="edit-profile_image" 
                   name="profile_image" 
                   class="edit-input" 
                   style="display:none;" 
                   accept="image/*">
        </div>

        <!-- ユーザー名フィールド -->
        <div class="edit-field">
            <label for="edit-username" class="edit-label">ユーザー名</label>
            <input type="text" 
                   id="edit-username" 
                   name="username" 
                   value="{{ old('username', auth()->user()->username) }}" 
                   class="edit-input">
        </div>

        <!-- 郵便番号フィールド -->
        <div class="edit-field">
            <label for="edit-postal_code" class="edit-label">郵便番号</label>
            <input type="text" 
                   id="edit-postal_code" 
                   name="postal_code" 
                   value="{{ old('postal_code', auth()->user()->postal_code) }}" 
                   class="edit-input">
        </div>

        <!-- 住所フィールド -->
        <div class="edit-field">
            <label for="edit-address" class="edit-label">住所</label>
            <input type="text" 
                   id="edit-address" 
                   name="address" 
                   value="{{ old('address', auth()->user()->address) }}" 
                   class="edit-input">
        </div>

        <!-- 建物名フィールド -->
        <div class="edit-field">
            <label for="edit-building" class="edit-label">建物名</label>
            <input type="text" 
                   id="edit-building" 
                   name="building" 
                   value="{{ old('building', auth()->user()->building) }}" 
                   class="edit-input">
        </div>

        <!-- 更新ボタン -->
        <button type="submit" class="edit-button">更新する</button>
    </form>
</div>
@endsection

@section('scripts')
<script>
    // 「edit-profile_image」入力でファイルが選択されたときにプレビューを更新する
    document.getElementById('edit-profile_image').addEventListener('change', function(e) {
        console.log('ファイルが選択されました');
        const file = e.target.files[0];
        if (!file) return;
        console.log('選択されたファイル:', file.name);

        const reader = new FileReader();
        reader.onload = function(event) {
            console.log('画像読み込み完了');
            document.getElementById('profile-image-preview').setAttribute('src', event.target.result);
        }
        reader.readAsDataURL(file);
    });
</script>
@endsection

