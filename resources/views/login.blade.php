<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ログイン</title>
</head>

<body>

    <h1>ログイン</h1>

    {{-- 入力エラーを表示 --}}
    @if ($errors->any())
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    @endif

    {{-- ログインフォーム --}}
    <form action="/login" method="POST">

        @csrf

        <div>
            <label for="username">ユーザーID</label>

            <input
                type="text"
                id="username"
                name="username"
                value="{{ old('username') }}"
                autocomplete="username"
                required
            >
        </div>

        <br>

        <div>
            <label for="password">パスワード</label>

            <input
                type="password"
                id="password"
                name="password"
                autocomplete="current-password"
                required
            >
        </div>

        <br>

        <button type="submit">
            ログイン
        </button>

    </form>

</body>
</html>
