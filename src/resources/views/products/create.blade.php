@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/create.css') }}">
@endsection

@section('content')
<div class="product-create">
    <h1 class="product-create__title">商品の出品</h1>

    @if ($errors->any())
    <div class="product-create__errors">
        <ul>
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form class="product-create__form" action="{{ route('products.confirm') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <section class="product-create__section">
            <h2 class="product-create__heading">商品画像</h2>
            <label class="product-create__image-drop" for="image">
                <span>画像を選択する</span>
                <input id="image" type="file" name="image" accept="image/*">
            </label>
            <p class="product-create__note">※ 選択した画像は確認ページで表示されます</p>
            @error('image')
            <p class="product-create__error">{{ $message }}</p>
            @enderror
        </section>

        <section class="product-create__section">
            <h2 class="product-create__heading">商品の詳細</h2>
            @php
            $categories = ['ファッション','家電','インテリア','レジャー','ベビー','コスメ','食品','スポーツ','アウトドア','ハンドメイド','アクセサリー','おもちゃ','ビューティー'];
            $selectedCategories = old('categories', []);
            @endphp
            <div class="product-create__categories">
                @foreach ($categories as $category)
                <label class="product-create__category">
                    <input type="checkbox" name="categories[]" value="{{ $category }}" {{ in_array($category, $selectedCategories, true) ? 'checked' : '' }}>
                    <span>{{ $category }}</span>
                </label>
                @endforeach
            </div>
            @error('categories')
            <p class="product-create__error">{{ $message }}</p>
            @enderror
        </section>

        <section class="product-create__section">
            <label class="product-create__label" for="condition">商品の状態</label>
            <select id="condition" name="condition" class="product-create__select">
                <option value="" {{ old('condition') === null ? 'selected' : '' }}>選択してください</option>
                <option value="新品未使用" {{ old('condition') === '新品未使用' ? 'selected' : '' }}>新品未使用</option>
                <option value="未使用に近い" {{ old('condition') === '未使用に近い' ? 'selected' : '' }}>未使用に近い</option>
                <option value="目立った傷や汚れなし" {{ old('condition') === '目立った傷や汚れなし' ? 'selected' : '' }}>目立った傷や汚れなし</option>
                <option value="やや傷や汚れあり" {{ old('condition') === 'やや傷や汚れあり' ? 'selected' : '' }}>やや傷や汚れあり</option>
                <option value="傷や汚れあり" {{ old('condition') === '傷や汚れあり' ? 'selected' : '' }}>傷や汚れあり</option>
            </select>
            @error('condition')
            <p class="product-create__error">{{ $message }}</p>
            @enderror
        </section>

        <section class="product-create__section">
            <h2 class="product-create__heading">商品名と説明</h2>
            <label class="product-create__label" for="name">商品名</label>
            <input id="name" type="text" name="name" class="product-create__input" placeholder="例）ハンドバッグ" value="{{ old('name') }}">
            @error('name')
            <p class="product-create__error">{{ $message }}</p>
            @enderror

            <label class="product-create__label" for="brand">ブランド名</label>
            <input id="brand" type="text" name="brand" class="product-create__input" placeholder="ブランド名を入力" value="{{ old('brand') }}">
            @error('brand')
            <p class="product-create__error">{{ $message }}</p>
            @enderror

            <label class="product-create__label" for="description">商品の説明</label>
            <textarea id="description" name="description" class="product-create__textarea" rows="4" placeholder="商品の状態や付属品などを詳しく記載してください">{{ old('description') }}</textarea>
            @error('description')
            <p class="product-create__error">{{ $message }}</p>
            @enderror
        </section>

        <section class="product-create__section">
            <label class="product-create__label" for="price">販売価格</label>
            <div class="product-create__price">
                <span>¥</span>
                <input id="price" type="number" name="price" class="product-create__price-input" placeholder="0" value="{{ old('price') }}">
            </div>
            @error('price')
            <p class="product-create__error">{{ $message }}</p>
            @enderror
        </section>

        <div class="product-create__actions">
            <button type="submit" class="product-create__submit">出品する</button>
        </div>
    </form>
</div>

@endsection