@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/profile.css') }}">
@endsection

@section('content')
<div class="profile">
    <div class="profile__header">
        <div class="profile__avatar"></div>
        <div class="profile__info">
            <h1 class="profile__name">{{ $user->name ?? 'ユーザー名' }}</h1>
            <a href="{{ route('user.settings') }}" class="profile__edit">プロフィールを編集</a>
        </div>
    </div>

    <div class="profile__tabs">
        <a href="{{ route('user.profile', ['tab' => 'buy']) }}"
            class="profile__tab {{ ($tab ?? 'buy') === 'buy' ? 'profile__tab--active' : '' }}">出品した商品</a>
        <a href="{{ route('user.profile', ['tab' => 'sell']) }}"
            class="profile__tab {{ ($tab ?? 'buy') === 'sell' ? 'profile__tab--active' : '' }}">購入した商品</a>
    </div>

    @if (($tab ?? 'buy') === 'sell')
    <div class="profile__products">
        @forelse ($purchasedProducts as $product)
        <a href="{{ route('item.show', $product) }}" class="profile__card">
            <div class="profile__card-image">
                <img src="{{ $product->image_src }}" alt="{{ $product->name }}">
                @if($product->is_sold)
                <div class="profile__sold">SOLD</div>
                @endif
            </div>
            <div class="profile__card-name">{{ $product->name }}</div>
        </a>
        @empty
        <p class="profile__empty">購入済みの商品はまだありません。</p>
        @endforelse
    </div>
    @else
    <div class="profile__products">
        @forelse ($sellProducts as $product)
        <a href="{{ route('item.show', $product) }}" class="profile__card">
            <div class="profile__card-image">
                <img src="{{ $product->image_src }}" alt="{{ $product->name }}">
                @if($product->is_sold)
                <div class="profile__sold">SOLD</div>
                @endif
            </div>
            <div class="profile__card-name">{{ $product->name }}</div>
        </a>
        @empty
        <p class="profile__empty">まだありません</p>
        @endforelse
    </div>
    @endif
</div>
@endsection