@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/confirm.css') }}">
@endsection

@section('content')
<div class="product-confirm">
    <h1 class="product-confirm__title">商品の確認</h1>

    <form action="{{ route('products.store') }}" method="POST" class="product-confirm__form">
        @csrf
        <input type="hidden" name="name" value="{{ $product['name'] }}">
        <input type="hidden" name="price" value="{{ $product['price'] }}">
        <input type="hidden" name="description" value="{{ $product['description'] ?? '' }}">
        <input type="hidden" name="condition" value="{{ $product['condition'] ?? '' }}">
        <input type="hidden" name="brand" value="{{ $product['brand'] ?? '' }}">
        @if(!empty($product['categories']))
        @foreach($product['categories'] as $category)
        <input type="hidden" name="categories[]" value="{{ $category }}">
        @endforeach
        @endif

        <dl class="product-confirm__list">
            <div class="product-confirm__row">
                <dt>商品画像</dt>
                <dd>
                    @if(session('temp_image'))
                    <img src="{{ asset('storage/' . session('temp_image')) }}" alt="商品画像">
                    @else
                    <span>画像なし</span>
                    @endif
                </dd>
            </div>
            <div class="product-confirm__row">
                <dt>商品名</dt>
                <dd>{{ $product['name'] }}</dd>
            </div>
            <div class="product-confirm__row">
                <dt>ブランド名</dt>
                <dd>{{ $product['brand'] ?? '入力なし' }}</dd>
            </div>
            <div class="product-confirm__row">
                <dt>商品の説明</dt>
                <dd>{{ $product['description'] ?? '入力なし' }}</dd>
            </div>
            <div class="product-confirm__row">
                <dt>カテゴリー</dt>
                <dd>{{ !empty($product['categories']) ? implode(', ', $product['categories']) : '未選択' }}</dd>
            </div>
            <div class="product-confirm__row">
                <dt>商品の状態</dt>
                <dd>{{ $product['condition'] ?? '未選択' }}</dd>
            </div>
            <div class="product-confirm__row">
                <dt>販売価格</dt>
                <dd class="product-confirm__price">¥{{ number_format($product['price']) }}</dd>
            </div>
        </dl>

        <div class="product-confirm__actions">
            <button type="submit" class="product-confirm__submit">この内容で出品する</button>
            <a href="{{ route('products.create') }}" class="product-confirm__back">修正する</a>
        </div>
    </form>
</div>
@endsection
