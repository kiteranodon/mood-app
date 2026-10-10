<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
    <h1>気分記録一覧</h1>
    <br>
    @foreach ($moods as $mood)
        <p>{{ $mood->date }}の気分: {{ $mood->mood }}</p>
    @endforeach

    <form action="/moods" method="post">
        @csrf
        日付<input type="date" name="date" value="{{ date('Y-m-d') }}"><br>
        <input type="radio" name="mood" value="最高">最高
        <input type="radio" name="mood" value="良い">良い
        <input type="radio" name="mood" value="普通">普通
        <input type="radio" name="mood" value="悪い">悪い
        <input type="radio" name="mood" value="最悪">最悪
        <br>
        <button type="submit">記録する</button>
    </form>
</body>
</html>
