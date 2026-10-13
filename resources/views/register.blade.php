@extends('layouts.app')

@section('title', '新規ユーザー登録 | きょうのきもち')
@section('main-class', 'page-main--narrow')

@section('content')
    <div class="mx-auto flex max-w-md flex-col gap-8 py-4 sm:py-10">
        <div class="page-heading text-center">
            <p class="eyebrow">CREATE ACCOUNT</p>
            <h1 class="page-title">はじめまして</h1>
            <p class="page-description">毎日の気持ちを、あなたのペースで少しずつ残していきましょう。</p>
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
            <form class="form-stack" action="{{ route('register') }}" method="POST">
                @csrf

                <div class="field-group">
                    <label class="field-label" for="name">名前</label>
                    <input
                        class="text-field"
                        type="text"
                        id="name"
                        name="name"
                        value="{{ old('name') }}"
                        autocomplete="name"
                        required
                        autofocus
                    >
                </div>

                <div class="field-group">
                    <label class="field-label" for="email">メールアドレス</label>
                    <input
                        class="text-field"
                        type="email"
                        id="email"
                        name="email"
                        value="{{ old('email') }}"
                        autocomplete="email"
                        inputmode="email"
                        required
                    >
                </div>

                <div class="field-group">
                    <label class="field-label" for="username">ユーザーID</label>
                    <input
                        class="text-field"
                        type="text"
                        id="username"
                        name="username"
                        value="{{ old('username') }}"
                        autocomplete="username"
                        required
                    >
                    <p class="field-help">ログインするときに使用します。</p>
                </div>

                <div class="field-group">
                    <label class="field-label" for="password">パスワード</label>
                    <input
                        class="text-field"
                        type="password"
                        id="password"
                        name="password"
                        autocomplete="new-password"
                        required
                    >
                </div>

                <div class="field-group">
                    <label class="field-label" for="password_confirmation">パスワード確認</label>
                    <input
                        class="text-field"
                        type="password"
                        id="password_confirmation"
                        name="password_confirmation"
                        autocomplete="new-password"
                        required
                    >
                </div>

                <button class="primary-button w-full" type="submit">アカウントを作成する</button>
            </form>
        </section>

        <p class="text-center text-sm text-leaf-700">
            すでにアカウントをお持ちの方は
            <a class="font-semibold text-leaf-800 underline decoration-leaf-300 underline-offset-4 hover:text-leaf-900" href="{{ route('login') }}">ログイン</a>
        </p>
    </div>
@endsection
