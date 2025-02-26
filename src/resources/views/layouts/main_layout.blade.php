<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'coachtechフリマ')</title>
    <link rel="stylesheet" href="{{ asset('css/main_header.css') }}">
    <link rel="stylesheet" href="{{ asset('css/edit.css') }}">
    <link rel="stylesheet" href="{{ asset('css/create.css') }}">
    <link rel="stylesheet" href="{{ asset('css/show.css') }}">
    <link rel="stylesheet" href="{{ asset('css/index.css') }}">
    <link rel="stylesheet" href="{{ asset('css/item.css') }}">
    <link rel="stylesheet" href="{{ asset('css/form.css') }}">
    <link rel="stylesheet" href="{{ asset('css/address.css') }}">
</head>
<body>
    <header class="main-header">
        <a href="{{ url('/') }}">
        <img src="{{ asset('images/logo.svg') }}" alt="ロゴ" class="main-logo">
        </a>
        <form action="{{ url('/search') }}" method="GET" class="main-search-form">
            <input type="text" name="query" placeholder="なにをお探しですか？" class="main-search-input">
        </form>
        <nav class="main-nav">
            <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="main-nav-link">ログアウト</a>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                @csrf
            </form>
            <a href="{{ route('profile.show') }}" class="main-nav-link">マイページ</a>
            <a href="{{ route('exhibition.create') }}" class="main-nav-link exhibit-link">出品</a>
        </nav>
    </header>

    <main class="main-content">
        @yield('content')
    </main>
    @yield('scripts')
</body>
</html>