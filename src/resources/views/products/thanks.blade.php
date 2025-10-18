@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/thanks.css') }}">
@endsection

@section('content')
<div class="product-thanks">
    <div class="product-thanks__container">
        <div class="product-thanks__icon">
            <svg width="64" height="64" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M9 12L11 14L15 10M21 12C21 16.9706 16.9706 21 12 21C7.02944 21 3 16.9706 3 12C3 7.02944 7.02944 3 12 3C16.9706 3 21 7.02944 21 12Z" stroke="#4CAF50" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
            </svg>
        </div>

        <h1 class="product-thanks__title">出品が完了しました！</h1>

        <p class="product-thanks__message">
            商品の出品が正常に完了しました。<br>
            商品は他のユーザーに表示されるようになります。
        </p>

        <div class="product-thanks__actions">
            <a href="{{ route('top.index') }}" class="product-thanks__button product-thanks__button--primary">トップページに戻る</a>
            <a href="{{ route('user.profile', ['tab' => 'sell']) }}" class="product-thanks__button product-thanks__button--secondary">マイページを見る</a>
        </div>
    </div>
</div>
@endsection