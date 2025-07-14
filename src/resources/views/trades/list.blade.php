@extends('layouts.main_layout')

@section('title', '取引一覧')

{{-- 👇 もし独自 CSS を当てたい場合はここで読み込めます --}}
@push('styles')
{{-- <link rel="stylesheet" href="{{ asset('css/list.css') }}"> --}}
@endpush

@section('content')
    <h1 class="text-xl font-bold mb-4">取引一覧</h1>

    @forelse ($trades as $trade)
        <a href="{{ route('trades.chat', $trade) }}"
           class="flex items-center justify-between p-3 mb-2 border rounded-md hover:bg-gray-50">

            {{-- 左側：商品画像 + 商品名 + 取引ステータス --}}
            <div class="flex items-center space-x-3">
                <img src="{{ $trade->product->image }}"
                     alt="{{ $trade->product->name }}"
                     class="w-16 h-16 object-cover rounded">

                <div>
                    <p class="font-semibold">{{ $trade->product->name }}</p>

                    <p class="text-sm text-gray-500">
                        {{-- 取引ステータス表示（完了のみ緑ラベル） --}}
                        @if ($trade->status === 'done')
                            <span class="text-green-600">取引完了</span>
                        @else
                            進行中
                        @endif
                    </p>
                </div>
            </div>

            {{-- 右側：未読バッジ --}}
            @if ($trade->unread_count > 0)
                <span class="px-2 py-1 text-xs font-semibold text-white bg-red-600 rounded-full">
                    {{ $trade->unread_count }}
                </span>
            @endif
        </a>
    @empty
        <p class="text-gray-500">現在取引中の商品はありません。</p>
    @endforelse
@endsection
