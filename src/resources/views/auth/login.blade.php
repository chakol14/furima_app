@extends('layouts.auth')

@section('css')
<link rel="stylesheet" href="{{ asset('css/login.css') }}">
@endsection

@section('content')
<div class="auth-form">
    <h2>ログイン</h2>

    <form method="POST" action="{{ route('login') }}" novalidate>
        @csrf

        {{-- メールアドレス --}}
        <div>
            <label for="email">メールアドレス</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" autofocus>
            @error('email')
            <div class="error">{{ $message }}</div>
            @enderror
        </div>

        {{-- パスワード --}}
        <div>
            <label for="password">パスワード</label>
            <input id="password" type="password" name="password">
            @error('password')
            <div class="error">{{ $message }}</div>
            @enderror
        </div>

        {{-- ログインボタン --}}
        <div>
            <button type="submit">ログイン</button>
        </div>
    </form>
    {{-- 会員登録リンク --}}
    <div class="auth-register-link" style="margin-top: 1em;">
        <a href="{{ route('register') }}">会員登録はこちら</a>
    </div>
</div>
@endsection