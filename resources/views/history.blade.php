<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <title>Document</title>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>気分記録一覧</title>

    <!-- Chart.jsを読み込む -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <h1>気分記録一覧</h1>

    <h2>気分の推移</h2>
    <div>
        <a href="/moods/history?year={{ $previousMonth->year }}&month={{ $previousMonth->month }}">
        ◀ 前月
        </a>

        <span>{{ $year }}年{{ $month }}月</span>

        <a href="/moods/history?year={{ $nextMonth->year }}&month={{ $nextMonth->month }}">
        翌月 ▶
        </a>
    </div>
    <canvas id="moodChart"></canvas>

    @foreach ($moods as $mood)
        <p>{{ $mood->date }}の気分: {{ $mood->mood }}</p>
        <p>一言感想: {{ $mood->comment ?? 'なし' }}</p>
    @endforeach


    <script>
    // HTMLからグラフを表示する場所を取得
    const canvas = document.getElementById('moodChart');

    // Laravelから渡されたデータをJavaScriptで使えるようにする
    const dates = @json($dates);
    const values = @json($values);

    // グラフを作成
    new Chart(canvas, {
        type: 'line',

        data: {
            // 横軸：日付
            labels: dates,

            datasets: [{
                label: '気分の変化',
                data: values,
                borderColor: 'blue',
                backgroundColor: 'lightblue',
                tension: 0.3
            }]
        },

        options: {
            responsive: true,

            scales: {
                y: {
                    min: 1,
                    max: 5,

                    ticks: {
                        stepSize: 1,

                        callback: function(value) {
                            if (value === 5) {
                                return '最高';
                            }

                            if (value === 4) {
                                return '良い';
                            }

                            if (value === 3) {
                                return '普通';
                            }

                            if (value === 2) {
                                return '悪い';
                            }

                            if (value === 1) {
                                return '最悪';
                            }

                            return value;
                        }
                    }
                }
            }
        }
    });
</script>
</body>
</html>
