<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#eff4d8">
    <title>@yield('title', 'きょうのきもち')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('head')
</head>
<body>
    <header class="site-header">
        <div class="site-header__inner">
            <a class="brand" href="{{ url('/') }}" aria-label="きょうのきもち ホーム">
                <span class="brand__mark" aria-hidden="true">◌</span>
                <span>きょうのきもち</span>
            </a>

            @auth
                <nav class="site-nav" aria-label="メインナビゲーション">
                    <a class="nav-link" href="{{ url('/moods') }}">記録する</a>
                    <a class="nav-link" href="{{ url('/moods/history') }}">振り返る</a>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button class="nav-button" type="submit">ログアウト</button>
                    </form>
                </nav>
            @else
                <a class="nav-link" href="{{ url('/login') }}">ログイン</a>
            @endauth
        </div>
    </header>

    <main class="page-main @yield('main-class')">
        @yield('content')
    </main>

    <footer class="site-footer">ひとことでも、空欄でも。今日の自分をそっと残そう。</footer>

    @stack('scripts')
</body>
</html>
