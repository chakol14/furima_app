@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/settings.css') }}">
@endsection

@section('content')
<div class="settings">
    <div class="settings__layout">
        <h1 class="settings__title">プロフィール設定</h1>

        @if (session('success'))
        <div class="settings__success">{{ session('success') }}</div>
        @endif

        <div class="settings__avatar">
            <div class="settings__avatar-image"></div>
            <button class="settings__avatar-button" type="button">画像を選択する</button>
        </div>

        <form class="settings__form" action="{{ route('user.settings.update') }}" method="POST">
            @csrf
            <label class="settings__label" for="name">名前</label>
            <input class="settings__input" id="name" name="name" type="text" placeholder="フリマ 太郎" value="{{ old('name', $user->name ?? '') }}">

            <label class="settings__label" for="postal_code">郵便番号</label>
            <input class="settings__input" id="postal_code" name="postal_code" type="text" placeholder="123-4567" value="{{ old('postal_code', $user->postal_code ?? '') }}">

            <label class="settings__label" for="address">住所</label>
            <input class="settings__input" id="address" name="address" type="text" placeholder="東京都〇〇区〇〇 1-2-3" value="{{ old('address', $user->address ?? '') }}">

            <label class="settings__label" for="building_name">建物名</label>
            <input class="settings__input" id="building_name" name="building_name" type="text" placeholder="〇〇マンション 101号室" value="{{ old('building_name', $user->building_name ?? '') }}">

            <button class="settings__submit" type="submit">更新する</button>
        </form>
    </div>
</div>
@endsection