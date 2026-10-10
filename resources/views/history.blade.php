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
    <canvas id="moodChart"></canvas>

    @foreach ($moods as $mood)
        <p>{{ $mood->date }}の気分: {{ $mood->mood }}</p>
        <p>一言感想: {{ $mood->comment ?? 'なし' }}</p>
    @endforeach


    <script>
    const canvas = document.getElementById('moodChart');

    const dates = @json($dates);
    const values = @json($values);

    new Chart(canvas, {
        type: 'line',
        data: {
            labels: dates,
            datasets: [{
                label: '気分の変化',
                data: values
            }]
        }
    });
</script>
</body>
</html>
