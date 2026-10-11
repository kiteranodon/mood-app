<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Models\Mood;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/moods', function () {
    $moods = Mood::all();

    return view('moods', ['moods' => $moods]);
});

Route::post('/moods', function (Request $request) {
    $date = $request->input('date');
    $mood = $request->input('mood');
    $comment = $request->input('comment');
    $record = new Mood();
    $record->date = $date;
    $record->mood = $mood;
    $record->comment = $comment;
    $record->save();
    return redirect('/moods');
});

Route::get('/moods/history', function () {

    // ① URLから表示する年月を取得する
    $year = request()->query('year', now()->year);
    $month = request()->query('month', now()->month);

    // ② 選択された年月の1日を作成する
    $date = \Carbon\Carbon::create(
        (int) $year,
        (int) $month,
        1
    );

    // ③ 前月と翌月を取得する
    $previousMonth = $date->copy()->subMonth();
    $nextMonth = $date->copy()->addMonth();

    // ④ 選択された月の気分記録だけを取得する
    $moods = Mood::whereYear('date', $year)
        ->whereMonth('date', $month)
        ->orderBy('date', 'asc')
        ->get();

    // ⑤ 気分を数字に変換するための対応表
    $scores = [
        '最高' => 5,
        '良い' => 4,
        '普通' => 3,
        '悪い' => 2,
        '最悪' => 1,
    ];

    // ⑥ 選択された月の日数を取得する
    $daysInMonth = $date->daysInMonth;

    // ⑦ グラフ用の配列を準備する
    $dates = [];
    $values = [];

    // ⑧ 1日から月末まで繰り返す
    for ($day = 1; $day <= $daysInMonth; $day++) {

        // 横軸に日付を追加する
        $dates[] = $day;

        // 検索する日付を作る
        $targetDate = $date->copy()
            ->day($day)
            ->format('Y-m-d');

        // その日の気分記録を探す
        $record = $moods->first(function ($mood) use ($targetDate) {
            return \Carbon\Carbon::parse($mood->date)
                ->format('Y-m-d') === $targetDate;
        });

        // 記録があれば数値、なければnull
        if ($record !== null) {
            $values[] = $scores[$record->mood];
        } else {
            $values[] = null;
        }
    }

    // ⑨ Bladeにデータを渡す
    return view('history', [
        'moods' => $moods,
        'dates' => $dates,
        'values' => $values,
        'year' => $year,
        'month' => $month,
        'previousMonth' => $previousMonth,
        'nextMonth' => $nextMonth,
    ]);
});

// ========================================
// 気分記録の編集画面を表示する
// ========================================

Route::get('/moods/{id}/edit', function ($id) {

    // IDを使って編集する記録を取得
    $mood = Mood::findOrFail($id);

    // 編集画面に記録を渡す
    return view('edit', [
        'mood' => $mood,
    ]);

});


// ========================================
// 気分記録を更新する
// ========================================

Route::put('/moods/{id}', function (Request $request, $id) {

    // ① 更新する記録を取得
    $mood = Mood::findOrFail($id);

    // ② 入力されたデータをチェック
    $validated = $request->validate([
        'date' => 'required|date',
        'mood' => 'required|in:最高,良い,普通,悪い,最悪',
        'comment' => 'nullable|string',
    ]);

    // ③ 記録の内容を変更
    $mood->date = $validated['date'];
    $mood->mood = $validated['mood'];
    $mood->comment = $validated['comment'] ?? null;

    // ④ データベースに保存
    $mood->save();

    // ⑤ 履歴画面に戻る
    return redirect('/moods/history');

});

// ========================================
// 気分記録を削除する
// ========================================

Route::delete('/moods/{id}', function ($id) {

    // ① 削除する記録を取得
    $mood = Mood::findOrFail($id);

    // ② データベースから削除
    $mood->delete();

    // ③ 履歴画面に戻る
    return redirect('/moods/history');

});
