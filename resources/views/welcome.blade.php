@extends('layouts.app')

@section('title', 'きょうのきもち | 毎日の気分をやさしく記録')

@section('content')
    <div class="grid min-h-[65vh] items-center gap-10 py-8 lg:grid-cols-[1.1fr_0.9fr] lg:py-16">
        <div class="flex flex-col items-start gap-6">
            <p class="eyebrow">A SMALL NOTE FOR EVERY DAY</p>
            <h1 class="max-w-2xl text-4xl leading-tight font-semibold tracking-tight text-leaf-900 sm:text-5xl lg:text-6xl">
                今日の気持ちを、<br>そっと置いていく場所。
            </h1>
            <p class="max-w-xl text-base leading-8 text-leaf-800/75 sm:text-lg">
                気分を選んで、ひとこと添えるだけ。がんばりすぎずに続けられる、あなたのための小さな記録です。
            </p>
            <a class="primary-button px-8" href="{{ url('/login') }}">記録をはじめる</a>
        </div>

        <div class="surface-card relative overflow-hidden" aria-hidden="true">
            <div class="absolute -top-20 -right-16 size-56 rounded-full bg-leaf-100"></div>
            <div class="relative flex flex-col gap-5">
                <div class="flex items-center justify-between">
                    <span class="text-sm font-semibold text-leaf-700">10月12日</span>
                    <span class="mood-badge"><span class="text-lg">◡</span> 良い</span>
                </div>
                <div class="rounded-2xl bg-leaf-50 p-5 text-leaf-800/75">
                    <p class="leading-7">ゆっくり歩いたら、風が気持ちよかった。</p>
                </div>
                <div class="grid grid-cols-7 gap-2 pt-2">
                    @foreach ([2, 3, 4, 3, 4, 4, 5] as $score)
                        <div class="flex h-28 items-end rounded-full bg-leaf-50 p-1.5">
                            <div class="w-full rounded-full bg-leaf-300" style="height: {{ $score * 17 }}%"></div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endsection
