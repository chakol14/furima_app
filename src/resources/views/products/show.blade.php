@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/show.css') }}">
@endsection

@section('content')
<div class="product-show">
    <div class="product-show__container">

        <div class="product-show__media">
            <img src="{{ $product->image_src }}" alt="{{ $product->name }}">
        </div>

        <div class="product-show__body">
            <h1 class="product-show__title">{{ $product->name }}</h1>
            <p class="product-show__brand">{{ $product->brand ?? 'ブランドなし' }}</p>
            <p class="product-show__price">¥{{ number_format((float) $product->price) }}（税込）</p>

            <div class="product-show__meta">
                <form action="{{ route('item.favorite', $product) }}" method="POST" class="product-show__favorite-form">
                    @csrf
                    <button type="submit" class="product-show__stat product-show__stat--favorite {{ $is_favorited ? 'is-favorited' : '' }}">
                        <span class="product-show__stat-icon">{{ $is_favorited ? '★' : '☆' }}</span>
                        <span class="product-show__stat-count">{{ $favorite_count }}</span>
                    </button>
                </form>
                <span class="product-show__stat">
                    <span class="product-show__stat-icon product-show__stat-icon--comment">💬</span>
                    <span class="product-show__stat-count">{{ count($comments) }}</span>
                </span>
            </div>

            <a href="{{ route('purchase', ['product' => $product->id]) }}" class="product-show__purchase">購入手続きへ</a>

            <section class="product-show__section">
                <h2>商品説明</h2>
                <div class="product-show__description">
                    <p>カラー：{{ $product->color ?? '未設定' }}</p>
                    <p>{{ $product->description ?? '商品の説明は登録されていません。' }}</p>
                </div>
            </section>

            <section class="product-show__section">
                <h2>商品の情報</h2>
                <div class="product-show__tags">
                    <span class="product-show__tag">{{ $product->category->name ?? '未分類' }}</span>
                </div>
                <p class="product-show__condition">商品の状態：{{ $product->condition ?? '未設定' }}</p>
            </section>

            @if(session('status'))
            <div class="product-show__alert">{{ session('status') }}</div>
            @endif

            @if($errors->has('comment'))
            <div class="product-show__alert product-show__alert--error">{{ $errors->first('comment') }}</div>
            @endif

            <section class="product-show__section">
                <h2>コメント ({{ count($comments) }})</h2>
                <div class="product-show__comments">
                    @forelse ($comments as $comment)
                    <div class="product-show__comment">
                        <div class="product-show__comment-avatar"></div>
                        <div class="product-show__comment-body">
                            <div class="product-show__comment-author">{{ $comment->user->name }}</div>
                            <p class="product-show__comment-text">{{ $comment->comment }}</p>
                        </div>
                    </div>
                    @empty
                    <p class="product-show__comment-empty">まだコメントはありません。</p>
                    @endforelse
                </div>

                <form class="product-show__comment-form" method="POST" action="{{ route('item.comment', $product) }}">
                    @csrf
                    <label for="comment" class="sr-only">商品のコメント</label>
                    <textarea id="comment" name="comment" rows="3" placeholder="商品の感想や質問を投稿しましょう">{{ old('comment') }}</textarea>
                    <button type="submit">コメントを送信する</button>
                </form>
            </section>
        </div>
    </div>
</div>
@endsection