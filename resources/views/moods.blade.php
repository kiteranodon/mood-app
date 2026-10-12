<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
    <form action="{{ route('logout') }}" method="POST">
        @csrf
        <button type="submit">ログアウト</button>
    </form>

    <a href="/moods/history">気分記録一覧</a>

    @if ($errors->any())
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    @endif

    <form action="/moods" method="post">
        @csrf
        日付<input type="date" name="date" value="{{ date('Y-m-d') }}"><br>
        <input type="radio" name="mood" value="最高">最高
        <input type="radio" name="mood" value="良い">良い
        <input type="radio" name="mood" value="普通">普通
        <input type="radio" name="mood" value="悪い">悪い
        <input type="radio" name="mood" value="最悪">最悪
        <br>
        <textarea name="comment" rows="3"></textarea><br>
        <button type="submit">記録する</button>
    </form>
</body>
</html>
