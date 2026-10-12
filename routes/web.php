<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Models\Mood;

Route::middleware('auth')->group(function () {
    Route::get('/', function () {
    return Auth::check()
        ? redirect('/moods')
        : redirect('/login');
    });

    Route::get('/moods', function () {
        $moods = Mood::where('user_id', Auth::id())->get();

        return view('moods', ['moods' => $moods]);
    })->middleware('auth');

    Route::post('/moods', function (Request $request) {

    // 入力内容をチェック
    $validated = $request->validate([
        'date' => ['required', 'date'],
        'mood' => ['required', 'in:最高,良い,普通,悪い,最悪'],
        'comment' => ['nullable', 'string'],
    ]);

        // 新しい気分記録を作成
        $record = new Mood();

        $record->date = $validated['date'];
        $record->mood = $validated['mood'];
        $record->comment = $validated['comment'] ?? null;

        // ログインユーザーのIDを設定
        $record->user_id = Auth::id();

        // データベースに保存
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
        $moods = Mood::where('user_id', Auth::id())
            ->whereYear('date', $year)
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

        // ログインユーザーの記録だけを取得
        $mood = Mood::where('user_id', Auth::id())
            ->findOrFail($id);

        return view('edit', ['mood' => $mood]);

    });


    // ========================================
    // 気分記録を更新する
    // ========================================

    Route::put('/moods/{id}', function (Request $request, $id) {

        // ① 更新する記録を取得
        $mood = Mood::where('user_id', Auth::id())
            ->findOrFail($id);
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
        $mood = Mood::where('user_id', Auth::id())
            ->findOrFail($id);

        // ② データベースから削除
        $mood->delete();

        // ③ 履歴画面に戻る
        return redirect('/moods/history');

    });
});

// ログイン画面を表示
Route::get('/login', function () {
    return view('login');
})->name('login');

// ログイン処理
Route::post('/login', function (Request $request) {

    // ① 入力内容をチェックする
    $credentials = $request->validate([
        'username' => ['required', 'string'],
        'password' => ['required', 'string'],
    ]);

    // ② IDとパスワードが正しいか確認する
    if (Auth::attempt($credentials)) {

        // ③ ログイン成功時にセッションIDを再生成する
        $request->session()->regenerate();

        // ④ 気分記録画面に移動する
        return redirect()->intended('/moods');
    }

    // ⑤ ログイン失敗時はエラーを表示する
    return back()->withErrors([
        'username' => 'ユーザーIDまたはパスワードが正しくありません。',
    ])->onlyInput('username');

});

// ログアウト処理
Route::post('/logout', function (Request $request) {

    // ログイン状態を解除する
    Auth::logout();

    // セッションを無効化する
    $request->session()->invalidate();

    // CSRFトークンを再生成する
    $request->session()->regenerateToken();

    // ログイン画面に戻る
    return redirect('/login');

})->name('logout');
