@extends('layouts.app')

@section('content')


<div class="container px-2 pt-2 d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center">
 <h1 class="mb-1 mt-2">Naive Bayes Classifier</h1>
 <nav aria-label="breadcrumb" class="d-none d-md-inline-block">
  <ol class="breadcrumb breadcrumb-dark breadcrumb-transparent mb-0">
   <li class="breadcrumb-item">
    <a href="{{ url('/') }}">
     <svg class="icon icon-xxs" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
     </svg>
    </a>
   </li>
   <li class="breadcrumb-item active" aria-current="page">Naive Bayes Classifier</li>
  </ol>
 </nav>
</div>

{{-- Form Input Ukuran Data Uji dan Random State --}}
<div class="bg-white rounded shadow p-4 mb-4 mt-4">
 <form method="GET" action="{{ route('naivebayes') }}">
  <div class="row">
   <div class="col-md-4 mb-3">
    <label for="testSize" class="form-label">Ukuran Data Uji (ex: 0.25)</label>
    <input type="text" class="form-control" name="testSize" value="{{ request('testSize', 0.25) }}">
   </div>
   <div class="col-md-4 mb-3">
    <label for="randomState" class="form-label">Random State (ex: 42)</label>
    <input type="text" class="form-control" name="randomState" value="{{ request('randomState', 42) }}">
   </div>
   <div class="col-md-4" style="padding-top: 32px;">
    <button type="submit" class="btn btn-primary w-100">Terapkan</button>
   </div>
  </div>
 </form>
</div>

<div class="bg-white rounded shadow p-4 mb-3 mt-3">
 <h4>TF-IDF</h4>
 <hr class="mb-0">
 <div class="row">
  <div class="table-responsive">
   <table class="table table-centered table-nowrap mb-0 rounded">
    <thead>
     <tr>
      <th>Dokumen</th>
      <th>Term</th>
      <th>TF-IDF</th>
     </tr>
    </thead>
    <tbody>
     @if ($data->isEmpty())
     <tr>
      <td colspan="3" class="text-center">Tidak ada data tersedia.</td>
     </tr>
     @else
     @foreach ($data as $item)
     <tr>
      <td>{{ $item['doc_number'] }}</td>
      <td>{{ $item['term'] }}</td>
      <td>{{ number_format($item['tfidf'], 6) }}</td>
     </tr>
     @endforeach
     @endif
    </tbody>
   </table>
  </div>
  <div class="mt-3">
   {{ $data->links('pagination::bootstrap-5') }}
  </div>
 </div>
</div>

{{-- PRIOR PROBABILITY --}}
@if (isset($priorProb))
<div class="bg-white rounded shadow p-4 mb-4">
 <h4>Prior Probability</h4>
 <hr class="mb-0">
 <table class="table">
  <thead>
   <tr>
    <th>Kelas</th>
    <th>Probabilitas</th>
   </tr>
  </thead>
  <tbody>
   @foreach ($priorProb as $label => $prob)
   <tr>
    <td>{{ $label }}</td>
    <td>{{ number_format($prob, 6) }}</td>
   </tr>
   @endforeach
  </tbody>
 </table>
</div>
@endif

{{-- LIKELIHOOD --}}
@if (isset($likelihoodTable))
<div class="bg-white rounded shadow p-4 mb-4">
 <h4>Likelihood</h4>
 <hr class="mb-0">
 <div class="table-responsive">
  <table class="table">
   <thead>
    <tr>
     <th>Dokumen</th>
     <th>Term</th>
     <th>P(term|negatif)</th>
     <th>P(term|positif)</th>
    </tr>
   </thead>
   <tbody>
    @if ($likelihoodTable->isEmpty())
    <tr>
     <td colspan="4" class="text-center">Tidak ada data tersedia.</td>
    </tr>
    @else
    @foreach ($likelihoodTable as $row)
    <tr>
     <td>{{ $row['document'] }}</td>
     <td>{{ $row['term'] }}</td>
     <td>{{ $row['p_negatif'] }}</td>
     <td>{{ $row['p_positif'] }}</td>
    </tr>
    @endforeach
    @endif
   </tbody>
  </table>
 </div>
 {{ $likelihoodTable->links('pagination::bootstrap-5') }}
</div>
@endif

{{-- POSTERIOR PROBABILITY --}}
@if (isset($posteriorTable))
<div class="bg-white rounded shadow p-4 mb-4">
 <h4>Posterior Probability</h4>
 <hr class="mb-0">
 <div class="table-responsive">
  <table class="table">
   <thead>
    <tr>
     <th>P(negatif|x)</th>
     <th>P(positif|x)</th>
     <th>Prediksi</th>
     <th>Aktual</th>
     <th>Data Clean</th>
    </tr>
   </thead>
   <tbody>
    @if ($posteriorTable->isEmpty())
    <tr>
     <td colspan="5" class="text-center">Tidak ada data tersedia.</td>
    </tr>
    @else
    @foreach ($posteriorTable as $row)
    <tr>
     <td>{{ $row['p_negatif'] }}</td>
     <td>{{ $row['p_positif'] }}</td>
     <td>{{ $row['prediksi'] }}</td>
     <td>{{ $row['aktual'] }}</td>
     <td>{{ $row['data_clean'] }}</td>
    </tr>
    @endforeach
    @endif
   </tbody>
  </table>
 </div>
 {{ $posteriorTable->links('pagination::bootstrap-5') }}
</div>
@endif


{{-- CONFUSION MATRIX --}}
@if (isset($confMatrix) && !empty($confMatrix['matrix']))
<div class="bg-white rounded shadow p-4 mb-4">
 <h4>Confusion Matrix</h4>
 <hr class="mb-0">
 <table class="table table-sm text-center">
  <thead>
   <tr>
    <th>Aktual \ Prediksi</th>
    @foreach ($confMatrix['labels'] as $label)
    <th>{{ $label }}</th>
    @endforeach
   </tr>
  </thead>
  <tbody>
   @foreach ($confMatrix['labels'] as $actualLabel)
   <tr>
    <th>{{ $actualLabel }}</th>
    @foreach ($confMatrix['labels'] as $predLabel)
    <td>{{ $confMatrix['matrix'][$actualLabel][$predLabel] }}</td>
    @endforeach
   </tr>
   @endforeach
  </tbody>
 </table>
</div>
@endif

@if (isset($confMatrix) && !empty($confMatrix['matrix']))
<div class="bg-white rounded shadow p-4 mb-4">
 <h3>Evaluasi Kinerja Model</h3>
 <p><strong>Akurasi:</strong> {{ number_format($confMatrix['accuracy'] * 100, 2) }}%</p>
 @foreach ($confMatrix['labels'] as $label)
 <p>
  <strong>{{ ucfirst($label) }}</strong><br>
  Precision: {{ number_format($confMatrix['precision'][$label] * 100, 2) }}%<br>
  Recall: {{ number_format($confMatrix['recall'][$label] * 100, 2) }}%<br>
  F1-Score: {{ number_format($confMatrix['f1Score'][$label] * 100, 2) }}%
 </p>
 @endforeach
 <p><strong>Macro Avg:</strong><br>
  Precision: {{ number_format($confMatrix['macro']['precision'] * 100, 2) }}%<br>
  Recall: {{ number_format($confMatrix['macro']['recall'] * 100, 2) }}%<br>
  F1-Score: {{ number_format($confMatrix['macro']['f1'] * 100, 2) }}%
 </p>
</div>
@endif


@endsection