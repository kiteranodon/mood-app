@extends('layouts.app')

@section('title', 'ログイン | きょうのきもち')
@section('main-class', 'page-main--narrow')

@section('content')
    <div class="mx-auto flex max-w-md flex-col gap-8 py-4 sm:py-10">
        <div class="page-heading text-center">
            <p class="eyebrow">WELCOME BACK</p>
            <h1 class="page-title">おかえりなさい</h1>
            <p class="page-description">今日の気持ちを、焦らずゆっくり記録しましょう。</p>
        </div>

        @if ($errors->any())
            <div class="error-box" role="alert">
                <ul class="mt-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <section class="surface-card">
            <form class="form-stack" action="{{ url('/login') }}" method="POST">
                @csrf

                <div class="field-group">
                    <label class="field-label" for="username">ユーザーID</label>
                    <input class="text-field" type="text" id="username" name="username" value="{{ old('username') }}" autocomplete="username" required autofocus>
                </div>

                <div class="field-group">
                    <label class="field-label" for="password">パスワード</label>
                    <input class="text-field" type="password" id="password" name="password" autocomplete="current-password" required>
                </div>

                <button class="primary-button w-full" type="submit">ログインする</button>
            </form>
        </section>

        <p class="text-center text-sm text-leaf-700">
            はじめてご利用の方は
            <a class="font-semibold text-leaf-800 underline decoration-leaf-300 underline-offset-4 hover:text-leaf-900" href="{{ route('register') }}">サインアップ</a>
        </p>
    </div>
@endsection
