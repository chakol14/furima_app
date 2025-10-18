@extends('layouts.auth')

@section('css')
<link rel="stylesheet" href="{{ asset('css/register.css') }}">
@endsection

@section('content')
<div class="auth-form">
    <h2>会員登録</h2>

    <form method="POST" action="{{ route('register') }}" novalidate>
        @csrf

        {{-- ユーザー名 --}}
        <div>
            <label for="name">ユーザー名</label>
            <input id="name" type="text" name="name" value="{{ old('name') }}" autofocus>
            @error('name') <div class="error">{{ $message }}</div> @enderror
        </div>

        {{-- メールアドレス --}}
        <div>
            <label for="email">メールアドレス</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}">
            @error('email') <div class="error">{{ $message }}</div> @enderror
        </div>

        {{-- パスワード --}}
        <div>
            <label for="password">パスワード</label>
            <input id="password" type="password" name="password" autocomplete="new-password">
            @error('password') <div class="error">{{ $message }}</div> @enderror
        </div>

        {{-- 確認用パスワード --}}
        <div>
            <label for="password_confirmation">確認パスワード</label>
            <input id="password_confirmation" type="password" name="password_confirmation">
        </div>

        {{-- 送信ボタン --}}
        <div>
            <button type="submit">登録する</button>
        </div>
    </form>
    {{-- ログインリンク --}}
    <div class="auth-login-link" style="margin-top: 1em;">
        <a href="{{ route('login') }}">ログインはこちら</a>
    </div>
</div>
@endsection