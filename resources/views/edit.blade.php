<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>気分記録の編集</title>
</head>

<body>

    <h1>気分記録の編集</h1>

    {{-- 入力エラーの表示 --}}
    @if ($errors->any())
        <div>
            <p>入力内容を確認してください。</p>

            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- 編集フォーム --}}
    <form action="/moods/{{ $mood->id }}" method="POST">

        @csrf
        @method('PUT')

        {{-- 日付 --}}
        <div>
            <label for="date">日付</label>

            <input
                type="date"
                id="date"
                name="date"
                value="{{ old('date', \Carbon\Carbon::parse($mood->date)->format('Y-m-d')) }}"
                required
            >
        </div>

        <br>

        {{-- 気分 --}}
        <div>
            <p>気分を選択してください</p>

            @foreach (['最高', '良い', '普通', '悪い', '最悪'] as $feeling)

                <label>
                    <input
                        type="radio"
                        name="mood"
                        value="{{ $feeling }}"
                        {{ old('mood', $mood->mood) === $feeling ? 'checked' : '' }}
                        required
                    >

                    {{ $feeling }}
                </label>

            @endforeach
        </div>

        <br>

        {{-- コメント --}}
        <div>
            <label for="comment">コメント</label>

            <br>

            <textarea
                id="comment"
                name="comment"
                rows="5"
                cols="40"
            >{{ old('comment', $mood->comment) }}</textarea>
        </div>

        <br>

        {{-- 保存ボタン --}}
        <button type="submit">
            変更を保存
        </button>

    </form>

    <br>

    {{-- 履歴画面に戻る --}}
    <a href="/moods/history">
        履歴に戻る
    </a>

</body>

</html>
