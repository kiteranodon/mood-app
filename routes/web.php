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
    $moods = Mood::orderBy('date', 'asc')->get();

    $scores = [
        '最高' => 5,
        '良い' => 4,
        '普通' => 3,
        '悪い' => 2,
        '最悪' => 1,
    ];

    $dates = $moods->pluck('date');

    $values = $moods->map(function ($mood) use ($scores) {
        return $scores[$mood->mood];
    });

    return view('history', [
        'moods' => $moods,
        'dates' => $dates,
        'values' => $values,
    ]);
});
