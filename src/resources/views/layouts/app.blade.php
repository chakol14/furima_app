<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('css/sanitize.css') }}">
    @yield('css')
    <title>FurimaApp</title>
</head>

<body>
    <header class="header">
        <div class="header__logo">
            {{-- ロゴクリックでトップページへ --}}
            <a href="{{ route('top.index') }}">
                <img src="{{ asset('images/logo.svg') }}" alt="FurimaApp">
            </a>
        </div>

        <div class="header__search">
            <form action="{{ route('top.index') }}" method="GET">
                <input type="text" class="header__search-input" placeholder="なにをお探しですか？" name="keyword" value="{{ request('keyword') }}">
            </form>
        </div>

        <div class="header__nav">
            @auth
            {{-- ログイン後 --}}
            <ul class="header__nav-list">
                <li class="header__nav-item">
                    <form action="{{ route('logout') }}" method="POST" class="header__logout-form">
                        @csrf
                        <button type="submit" class="header__nav-link header__nav-link--button">ログアウト</button>
                    </form>
                </li>
                <li class="header__nav-item">
                    <a href="{{ route('user.profile') }}" class="header__nav-link">マイページ</a>
                </li>
            </ul>
            @else
            {{-- ログイン前 --}}
            <ul class="header__nav-list">
                <li class="header__nav-item">
                    <a href="{{ route('login') }}" class="header__nav-link">ログイン</a>
                </li>
                <li class="header__nav-item">
                    <a href="{{ route('user.profile') }}" class="header__nav-link">マイページ</a>
                </li>
            </ul>
            @endauth

            <div class="header__nav-list">
                {{-- 出品ページへ --}}
                <a href="{{ route('products.create') }}" class="header__nav-link header__nav-link--primary">出品</a>
            </div>
        </div>
    </header>

    <main class="main">
        @yield('content')
    </main>

    @stack('scripts')
</body>

</html>