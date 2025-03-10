@extends('layouts.main_layout')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/address.css') }}">
@endsection

@section('content')
<div class="address-container">
    <h1>住所の変更</h1>
    <form action="{{ route('purchase.address.update') }}" method="POST">
        @csrf
        <div class="address-form-group">
            <label for="postal_code" class="address-label">郵便番号</label>
            <input type="text" name="postal_code" id="postal_code" class="address-input" value="{{ old('postal_code', $address->postal_code) }}">
        </div>
        <div class="address-form-group">
            <label for="address" class="address-label">住所</label>
            <input type="text" name="address" id="address" class="address-input" value="{{ old('address', $address->address) }}">
        </div>
        <div class="address-form-group">
            <label for="building" class="address-label">建物名</label>
            <input type="text" name="building" id="building" class="address-input" value="{{ old('building', $address->building) }}">
        </div>
        <button type="submit" class="address-button">更新する</button>
    </form>
</div>
@endsection
