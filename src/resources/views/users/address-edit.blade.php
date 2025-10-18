@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/address-edit.css') }}">
@endsection

@section('content')
<div class="address-edit">
    <div class="address-edit__container">
        <h1 class="address-edit__title">住所の変更</h1>

        <form method="POST" action="{{ route('address.update', $product) }}" class="address-edit__form">
            @csrf

            <label class="address-edit__label" for="name">お名前</label>
            <input class="address-edit__input" id="name" name="name" type="text"
                value="{{ old('name', $user->name) }}" placeholder="フリマ 太郎">

            <label class="address-edit__label" for="postal_code">郵便番号</label>
            <input class="address-edit__input" id="postal_code" name="postal_code" type="text"
                value="{{ old('postal_code', $user->postal_code) }}" placeholder="123-4567">

            <label class="address-edit__label" for="address">住所</label>
            <input class="address-edit__input" id="address" name="address" type="text"
                value="{{ old('address', $user->address) }}" placeholder="東京都〇〇区〇〇 1-2-3">

            <label class="address-edit__label" for="building_name">建物名</label>
            <input class="address-edit__input" id="building_name" name="building_name" type="text"
                value="{{ old('building_name', $user->building_name) }}" placeholder="〇〇マンション 101号室">

            <button class="address-edit__submit" type="submit">更新する</button>
        </form>
    </div>
</div>
@endsection
