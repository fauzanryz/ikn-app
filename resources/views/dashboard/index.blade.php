@extends('layouts.app')
@section('js')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/wordcloud@1.1.2/src/wordcloud2.min.js"></script>

<script>
    // Pie Chart Jumlah Sentimen
    const ctxPie = document.getElementById('sentimentPieChart').getContext('2d');
    const sentimentPieChart = new Chart(ctxPie, {
        type: 'pie',
        data: {
            labels: ['Positif', 'Negatif'],
            datasets: [{
                data: [{{ $positiveCount }}, {{ $negativeCount }}],
                backgroundColor: ['#272c41', '#f8bd7a']
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { position: 'bottom' }
            }
        }
    });

    // Fungsi konversi frekuensi objek ke array [[word, weight], ...]
    function convertFreqToList(freqObj) {
        const list = [];
        for (const [word, count] of Object.entries(freqObj)) {
            list.push([word, count]);
        }
        return list;
    }

    // Ambil data dari PHP (Laravel Blade)
    const positifFreq = convertFreqToList(@json($frekuensiPositif));
    const negatifFreq = convertFreqToList(@json($frekuensiNegatif));

    // Render Word Cloud Positif
    WordCloud(document.getElementById('wordCloudPositif'), {
        list: positifFreq,
        gridSize: 12,
        weightFactor: 8,
        color: '#272c41',
        backgroundColor: '#f8f9fa',
        rotateRatio: 0.5,
        rotationSteps: 2
    });

    // Render Word Cloud Negatif
    WordCloud(document.getElementById('wordCloudNegatif'), {
        list: negatifFreq,
        gridSize: 12,
        weightFactor: 8,
        color: '#f8bd7a',
        backgroundColor: '#272c41',
        rotateRatio: 0.5,
        rotationSteps: 2
    });
</script>


@endsection

@section('content')

<div class="row justify-content-lg-center mt-4">
  <div class="col-12 mb-4">
    <div class="card border-0 bg-yellow-100 shadow">
      <div class="card-header d-sm-flex flex-row align-items-center border-yellow-200 flex-0">
        <div class="d-block mb-4 mb-sm-0">
          <div>
            <h1 class="mt-4"><b>Selamat Datang di Analisis Sentimen IKN</b></h1>
          </div>
          <div class="small mt-2 mb-4"><span class="fw-normal me-2">Sistem ini dibangun untuk menganalisis persepsi publik terhadap perpindahan Ibu Kota ke IKN melalui data dari platform X menggunakan algoritma Naive Bayes Classifier.</span> <span class="fas fa-angle-up text-success"></span></div>
        </div>
      </div>
    </div>
  </div>
</div><!-- Row -->
<div class="row">
  <div class="col-12 col-sm-6 col-xl-4 mb-4">
    <div class="card border-0 shadow">
      <div class="card-header border-bottom">
        <h2 class="fs-5 fw-bold" style="margin-bottom: 0px;">Jumlah Sentimen</h2>
      </div>
      <div class="card-body">
        <div class="row d-block d-xxl-flex align-items-center">
          <div class="col-12 col-xxl-6 px-xxl-0 mb-3 mb-xxl-0">
            <canvas id="sentimentPieChart" width="250" height="25fi0"></canvas>
          </div>
        </div>
      </div>
    </div>
  </div><!-- Revenue -->
  <div class="col-12 col-sm-6 col-xl-4 mb-4">
    <div class="card border-0 shadow">
      <div class="card-header border-bottom">
        <h2 class="fs-5 fw-bold" style="margin-bottom: 0px;">Wordcloud Positif</h2>
      </div>
      <div class="card-body">
        <div class="row d-block d-xxl-flex align-items-center">
          <div class="col-12 col-xxl-6 px-xxl-0 mb-3 mb-xxl-0">
            <canvas id="wordCloudPositif" width="250" height="250"></canvas>
          </div>
        </div>
      </div>
    </div>
  </div><!-- Traffic -->
  <div class="col-12 col-sm-12 col-xl-4 mb-4">
    <div class="card border-0 shadow">
      <div class="card-header border-bottom">
        <h2 class="fs-5 fw-bold" style="margin-bottom: 0px;">Wordcloud Negatif</h2>
      </div>
      <div class="card-body">
        <div class="row d-block d-xxl-flex align-items-center">
          <div class="col-12 col-xxl-6 px-xxl-0 mb-3 mb-xxl-0">
            <canvas id="wordCloudNegatif" width="250" height="250"></canvas>
          </div>
        </div>
      </div>
    </div>
  </div>
</div><!-- Row -->

@endsection