@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/top.css') }}">
@endsection

@section('content')
<div class="products-top">
    <div class="products-top__tabs">
        <a href="{{ route('top.index', ['keyword' => request('keyword')]) }}"
            class="products-top__tab {{ $tab === 'recommend' ? 'products-top__tab--active' : '' }}">
            おすすめ
        </a>
        <a href="{{ route('top.index', ['page' => 'mylist', 'keyword' => request('keyword')]) }}"
            class="products-top__tab {{ $tab === 'mylist' ? 'products-top__tab--active' : '' }}">
            マイリスト
        </a>
    </div>

    <div class="products-top__list">
        @forelse ($products as $product)
        <a href="{{ route('item.show', $product) }}" class="products-top__card">
            <div class="products-top__image-wrapper">
                <img src="{{ $product->image_src }}" alt="{{ $product->name }}">
                @if($product->is_sold)
                <div class="products-top__sold">SOLD</div>
                @endif
            </div>
            <div class="products-top__name">{{ $product->name }}</div>
        </a>
        @empty
        <p>{{ $tab === 'mylist' ? 'マイリストに商品はまだありません。' : '商品が見つかりませんでした。' }}</p>
        @endforelse
    </div>
</div>
@endsection