@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/purchase.css') }}">
@endsection

@section('content')
<div class="purchase">
    <div class="purchase__container">
        <div class="purchase__main">
            <div class="purchase__product">
                <div class="purchase__image">
                    <img src="{{ $product->image_src }}" alt="{{ $product->name }}">
                </div>
                <div class="purchase__product-info">
                    <h1 class="purchase__product-name">{{ $product->name }}</h1>
                    <p class="purchase__product-price">¥{{ number_format((float) $product->price) }}</p>
                </div>
            </div>

            <section class="purchase__section">
                <h2>支払い方法</h2>
                <div class="purchase__form">
                    <select class="purchase__select" data-payment-select>
                        <option value="コンビニ支払い" {{ $payment_method === 'コンビニ支払い' ? 'selected' : '' }}>コンビニ支払い</option>
                        <option value="カード支払い" {{ $payment_method === 'カード支払い' ? 'selected' : '' }}>カード支払い</option>
                    </select>

                    <div class="purchase__address">
                        <div class="purchase__address-header">
                            <h3>配送先</h3>
                            <a href="{{ route('address.edit', $product) }}">変更する</a>
                        </div>
                        <p class="purchase__address-text">〒 {{ $profile['postal_code'] ?? '' }}</p>
                        <p class="purchase__address-text">{{ $profile['address'] ?? '' }}</p>
                        @if(!empty($profile['building_name']))
                        <p class="purchase__address-text">{{ $profile['building_name'] }}</p>
                        @endif
                    </div>
                </div>
            </section>
        </div>

        <aside class="purchase__sidebar">
            <div class="purchase__summary">
                <div class="purchase__summary-row">
                    <span>商品価格</span>
                    <strong>¥{{ number_format((float) $product->price) }}</strong>
                </div>
                <div class="purchase__summary-row">
                    <span>支払い方法</span>
                    <span data-payment-summary>{{ $payment_method }}</span>
                </div>
            </div>

            <form method="POST" action="{{ route('purchase.complete', $product) }}" class="purchase__cta">
                @csrf
                <input type="hidden" name="payment_method" value="{{ $payment_method }}" data-payment-hidden>
                <input type="hidden" name="postal_code" value="{{ $profile['postal_code'] ?? 'XXX-YYYY' }}">
                <input type="hidden" name="address" value="{{ $profile['address'] ?? '住所が登録されていません' }}">
                <input type="hidden" name="building_name" value="{{ $profile['building_name'] ?? '' }}">
                <button type="submit">購入する</button>
            </form>
        </aside>
    </div>
</div>
@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const select = document.querySelector('[data-payment-select]');
        const summary = document.querySelector('[data-payment-summary]');
        const hidden = document.querySelector('[data-payment-hidden]');

        if (!select || !summary || !hidden) {
            return;
        }

        const sync = (value) => {
            summary.textContent = value;
            hidden.value = value;
        };

        sync(select.value);

        select.addEventListener('change', (event) => {
            sync(event.target.value);
        });
    });
</script>
@endpush
@endsection
