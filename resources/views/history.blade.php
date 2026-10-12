@extends('layouts.app')

@section('title', '気分の振り返り | きょうのきもち')

@push('head')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
@endpush

@section('content')
    <div class="flex flex-col gap-10">
        <div class="flex flex-col justify-between gap-5 sm:flex-row sm:items-end">
            <div class="page-heading">
                <p class="eyebrow">HISTORY</p>
                <h1 class="page-title">気分を振り返る</h1>
                <p class="page-description">良い日もそうでない日も、並べてみると自分のペースが見えてきます。</p>
            </div>
            <a class="secondary-button shrink-0" href="{{ url('/moods') }}">＋ 今日を記録する</a>
        </div>

        <section class="surface-card flex flex-col gap-6" aria-labelledby="chart-heading">
            <div class="flex flex-col gap-4">
                <div>
                    <p class="eyebrow">MONTHLY FLOW</p>
                    <h2 id="chart-heading" class="mt-1 text-xl font-semibold text-leaf-900">気分の流れ</h2>
                </div>

                <nav class="month-switcher" aria-label="表示する月を変更">
                    <a href="{{ url('/moods/history?year='.$previousMonth->year.'&month='.$previousMonth->month) }}">← 前月</a>
                    <strong>{{ $year }}年{{ $month }}月</strong>
                    <a href="{{ url('/moods/history?year='.$nextMonth->year.'&month='.$nextMonth->month) }}">翌月 →</a>
                </nav>
            </div>

            <div class="chart-wrap">
                <canvas id="moodChart" aria-label="{{ $year }}年{{ $month }}月の気分の推移グラフ" role="img"></canvas>
            </div>
        </section>

        <section class="flex flex-col gap-5" aria-labelledby="records-heading">
            <div class="flex items-end justify-between gap-4">
                <div>
                    <p class="eyebrow">RECORDS</p>
                    <h2 id="records-heading" class="mt-1 text-2xl font-semibold text-leaf-900">今月の記録</h2>
                </div>
                <span class="text-sm text-leaf-700">{{ $moods->count() }}件</span>
            </div>

            @if ($moods->isEmpty())
                <div class="empty-state">
                    <p class="text-lg font-semibold text-leaf-800">まだ記録がありません</p>
                    <p class="mt-2 text-sm leading-6">気が向いたときに、今日の気分をひとつ残してみましょう。</p>
                    <a class="primary-button mt-5" href="{{ url('/moods') }}">気分を記録する</a>
                </div>
            @else
                <div class="history-list">
                    @foreach ($moods->sortByDesc('date') as $mood)
                        <article class="history-item">
                            <div class="flex flex-col justify-between gap-4 sm:flex-row sm:items-start">
                                <div class="flex min-w-0 flex-col gap-3">
                                    <time class="text-sm font-semibold text-leaf-700" datetime="{{ \Carbon\Carbon::parse($mood->date)->format('Y-m-d') }}">
                                        {{ \Carbon\Carbon::parse($mood->date)->isoFormat('M月D日（ddd）') }}
                                    </time>
                                    <div>
                                        <span class="mood-badge">
                                            <span aria-hidden="true">{{ ['最高' => '◎', '良い' => '◡', '普通' => '○', '悪い' => '︵', '最悪' => '…'][$mood->mood] ?? '○' }}</span>
                                            {{ $mood->mood }}
                                        </span>
                                    </div>
                                    @if ($mood->comment)
                                        <p class="break-words leading-7 text-leaf-900/80">{{ $mood->comment }}</p>
                                    @else
                                        <p class="text-sm text-leaf-700/55">ひとことはありません</p>
                                    @endif
                                </div>

                                <div class="flex shrink-0 items-center gap-1 self-end sm:self-start">
                                    <a class="text-button" href="{{ url('/moods/'.$mood->id.'/edit') }}">編集</a>
                                    <form action="{{ url('/moods/'.$mood->id) }}" method="POST" onsubmit="return confirm('本当にこの記録を削除しますか？');">
                                        @csrf
                                        @method('DELETE')
                                        <button class="danger-button" type="submit">削除</button>
                                    </form>
                                </div>
                            </div>
                        </article>
                    @endforeach
                </div>
            @endif
        </section>
    </div>
@endsection

@push('scripts')
    <script>
        const canvas = document.getElementById('moodChart');
        const dates = @json($dates);
        const values = @json($values);
        const reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

        new Chart(canvas, {
            type: 'line',
            data: {
                labels: dates,
                datasets: [{
                    label: '気分の変化',
                    data: values,
                    borderColor: '#698533',
                    backgroundColor: 'rgba(199, 217, 141, 0.22)',
                    pointBackgroundColor: '#698533',
                    pointBorderColor: '#fcfcf6',
                    pointBorderWidth: 2,
                    pointRadius: 4,
                    pointHoverRadius: 5,
                    borderWidth: 2,
                    fill: true,
                    spanGaps: true,
                    tension: 0.35,
                }],
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                animation: reduceMotion ? false : { duration: 500 },
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: '#3c4928',
                        padding: 10,
                        displayColors: false,
                        callbacks: {
                            label(context) {
                                return ['最悪', '悪い', '普通', '良い', '最高'][context.parsed.y - 1] ?? '';
                            },
                        },
                    },
                },
                scales: {
                    x: {
                        grid: { display: false },
                        border: { display: false },
                        ticks: { color: '#698533', maxTicksLimit: 16 },
                    },
                    y: {
                        min: 1,
                        max: 5,
                        border: { display: false },
                        grid: { color: 'rgba(169, 196, 95, 0.18)' },
                        ticks: {
                            color: '#53682e',
                            stepSize: 1,
                            callback(value) {
                                return ['最悪', '悪い', '普通', '良い', '最高'][value - 1] ?? value;
                            },
                        },
                    },
                },
            },
        });
    </script>
@endpush
