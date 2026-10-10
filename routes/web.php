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
    $record = new Mood();
    $record->date = $date;
    $record->mood = $mood;
    $record->save();
    return redirect('/moods');
});


