@extends('layouts.main_layout')

@section('content')
<div class="max-w-xl mx-auto p-6">
    <h2 class="text-xl font-bold mb-4">メッセージを編集</h2>

    <form action="{{ route('trades.messages.update', [$trade, $message]) }}"
          method="POST" enctype="multipart/form-data" class="space-y-4">
        @csrf
        @method('PUT')

        <textarea name="body" rows="4"
                  class="w-full border rounded p-2"
        >{{ old('body', $message->body) }}</textarea>
        @error('body') <p class="text-red-500 text-sm">{{ $message }}</p>@enderror

        <div>
            <label class="block mb-1">画像を変更（任意）</label>
            <input type="file" name="image" accept="image/*">
            @if($message->image)
                <img src="{{ Storage::url($message->image) }}"
                     class="mt-2 h-24 object-cover rounded">
            @endif
            @error('image') <p class="text-red-500 text-sm">{{ $message }}</p>@enderror
        </div>

        <div class="flex items-center gap-4">
            <button type="submit"
                    class="px-4 py-2 bg-blue-600 text-white rounded">
                更新する
            </button>
            <a href="{{ route('trades.chat', $trade) }}"
               class="text-gray-600">キャンセル</a>
        </div>
    </form>
</div>
@endsection
