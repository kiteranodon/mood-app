@extends('layouts.app')

@section('title', '気分記録の編集 | きょうのきもち')
@section('main-class', 'page-main--narrow')

@section('content')
    <div class="flex flex-col gap-8">
        <div class="page-heading">
            <a class="w-fit text-sm font-semibold text-leaf-700 hover:text-leaf-900" href="{{ url('/moods/history') }}">← 履歴に戻る</a>
            <p class="eyebrow">EDIT RECORD</p>
            <h1 class="page-title">記録を整える</h1>
            <p class="page-description">あとから振り返って気づいたことも、自由に書き直せます。</p>
        </div>

        @if ($errors->any())
            <div class="error-box" role="alert">
                <p class="font-semibold">入力内容を確認してください。</p>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <section class="surface-card">
            <form class="form-stack" action="{{ url('/moods/'.$mood->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="field-group">
                    <label class="field-label" for="date">記録した日</label>
                    <input class="text-field" type="date" id="date" name="date" value="{{ old('date', \Carbon\Carbon::parse($mood->date)->format('Y-m-d')) }}" required>
                </div>

                <fieldset class="field-group">
                    <legend class="field-label">この日の気分</legend>
                    <div class="mood-grid">
                        @foreach (['最高' => '◎', '良い' => '◡', '普通' => '○', '悪い' => '︵', '最悪' => '…'] as $feeling => $face)
                            <label class="mood-option">
                                <input type="radio" name="mood" value="{{ $feeling }}" {{ old('mood', $mood->mood) === $feeling ? 'checked' : '' }} required>
                                <span class="mood-option__content">
                                    <span class="mood-option__face" aria-hidden="true">{{ $face }}</span>
                                    <span>{{ $feeling }}</span>
                                </span>
                            </label>
                        @endforeach
                    </div>
                </fieldset>

                <div class="field-group">
                    <label class="field-label" for="comment">ひとこと <span class="font-normal text-leaf-700/60">（任意）</span></label>
                    <textarea class="text-field" id="comment" name="comment" rows="5">{{ old('comment', $mood->comment) }}</textarea>
                </div>

                <div class="flex flex-col-reverse gap-3 sm:flex-row sm:justify-end">
                    <a class="secondary-button" href="{{ url('/moods/history') }}">キャンセル</a>
                    <button class="primary-button" type="submit">変更を保存する</button>
                </div>
            </form>
        </section>
    </div>
@endsection
