@extends('layouts.app')

@section('title', '今日の気分を記録 | きょうのきもち')
@section('main-class', 'page-main--narrow')

@section('content')
    <div class="flex flex-col gap-8">
        <div class="page-heading">
            <p class="eyebrow">TODAY</p>
            <h1 class="page-title">今日はどんな一日でしたか？</h1>
            <p class="page-description">うまく言葉にしなくても大丈夫。今の気分に近いものを、1つ選んでみましょう。</p>
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

        <section class="surface-card" aria-labelledby="record-heading">
            <form class="form-stack" action="{{ url('/moods') }}" method="POST">
                @csrf

                <div class="field-group">
                    <label class="field-label" for="date">記録する日</label>
                    <input class="text-field" type="date" id="date" name="date" value="{{ old('date', date('Y-m-d')) }}" required>
                </div>

                <fieldset class="field-group">
                    <legend id="record-heading" class="field-label">今の気分</legend>
                    <div class="mood-grid">
                        @foreach (['最高' => '◎', '良い' => '◡', '普通' => '○', '悪い' => '︵', '最悪' => '…'] as $feeling => $face)
                            <label class="mood-option">
                                <input type="radio" name="mood" value="{{ $feeling }}" {{ old('mood') === $feeling ? 'checked' : '' }} required>
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
                    <textarea class="text-field" id="comment" name="comment" rows="4" placeholder="今日あったことや、今感じていること">{{ old('comment') }}</textarea>
                    <p class="field-help">書きたくない日は、空欄のままで記録できます。</p>
                </div>

                <button class="primary-button w-full" type="submit">今日の気分を記録する</button>
            </form>
        </section>
    </div>
@endsection
