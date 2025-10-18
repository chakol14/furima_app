<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ asset('css/auth.css') }}">
    <link rel="stylesheet" href="{{ asset('css/sanitize.css') }}">
    @yield('css')
    <title>FurimaApp</title>
</head>

<body>
    <header class="header">
        <div class="header__logo">
            <a href="">
                <img src="{{ asset('images/logo.svg') }}" alt="FurimaApp">
            </a>
        </div>
    </header>

    <main class="main">
        @yield('content')
    </main>
</body>

</html>