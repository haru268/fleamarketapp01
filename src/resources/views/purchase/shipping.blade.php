{{-- resources/views/purchase/address.blade.php --}}
@extends('layouts.main_layout')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/address.css') }}">
@endsection

@section('content')
<div class="address-container max-w-2xl mx-auto p-6 bg-white rounded shadow">
    <h1 class="text-2xl font-bold mb-6">送付先住所の変更</h1>

    {{-- フラッシュメッセージ --}}
    @if (session('success'))
        <div class="mb-4 text-green-600">{{ session('success') }}</div>
    @endif

    {{-- ★ パラメータ無しで POST --}}
    <form method="POST" action="{{ route('purchase.shipping.update') }}">
        @csrf

        {{-- 郵便番号 --}}
        <div class="address-form-group mb-4">
            <label for="postal_code" class="address-label block font-semibold mb-1">郵便番号</label>
            <input
                type="text"
                id="postal_code"
                name="postal_code"
                class="address-input w-full border rounded p-2"
                value="{{ old('postal_code', $address->postal_code ?? '') }}"
                placeholder="123-4567">
            @error('postal_code')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- 住所 --}}
        <div class="address-form-group mb-4">
            <label for="address" class="address-label block font-semibold mb-1">住所</label>
            <input
                type="text"
                id="address"
                name="address"
                class="address-input w-full border rounded p-2"
                value="{{ old('address', $address->address ?? '') }}">
            @error('address')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- 建物名 --}}
        <div class="address-form-group mb-6">
            <label for="building" class="address-label block font-semibold mb-1">建物名</label>
            <input
                type="text"
                id="building"
                name="building"
                class="address-input w-full border rounded p-2"
                value="{{ old('building', $address->building ?? '') }}">
            @error('building')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="text-right">
            <button type="submit" class="address-button px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                更新する
            </button>
        </div>
    </form>
</div>
@endsection
