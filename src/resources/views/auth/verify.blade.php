@extends('layouts.auth')

@section('css')
<link rel="stylesheet" href="{{ asset('css/verify.css') }}">
@endsection

@section('content')
<div class="verify">
    <p class="verify__message">
        登録していただいたメールアドレスに認証メールを送付しました。<br>
        メール認証を完了してください。
    </p>
    <a href="{{ route('user.settings') }}" class="verify__action-button">認証はこちらから</a>
    <a href="#" class="verify__resend-link">認証メールを再送する</a>
</div>
@endsection
