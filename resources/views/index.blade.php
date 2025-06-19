@extends('layouts.auth')

@section('content')

@if(session('error'))
<script>
  document.addEventListener('DOMContentLoaded', function() {
    const notyf = new Notyf({
      position: {
        x: 'right',
        y: 'top'
      }
    });
    notyf.error("{{ session('error') }}");
  });
</script>
@endif

<main>
  <!-- Navbar -->
  <nav class="navbar navbar-expand-lg navbar-light bg-gray-100 mb-5 mt-1">
    <div class="container d-flex justify-content-end align-items-center">
      <a href="{{ route('login') }}" class="nav-link fw-bold">Login</a>
    </div>
  </nav>

  <!-- Section -->
  <section class="mt-5 bg-soft d-flex align-items-center">
    <div class="container">
      <div class="col-12 d-flex align-items-center justify-content-center">
        <div class="border-0 rounded border-light p-lg-3 w-100 fmxw-2000">
          <div class="mb-4 mt-md-0 text-center">
            <h1 class="mb-0 h3 fw-bold">Analisis Sentimen IKN</h1>
            <div class="small mb-4 text-muted" style="font-size: 0.8rem;">Tulis komentar Anda di bawah, lalu klik "Check" untuk melihat apakah komentar tersebut bernada positif atau negatif.</div>
          </div>
          <form action="{{ route('cekSentimen') }}" method="POST" class="mt-4">
            @csrf
            <div class="form-group mb-3">
              <div class="input-group">
                <input type="text" name="sentimen" class="form-control" id="sentimen" placeholder="Tulis komentar Anda di sini..." value="{{ old('sentimen') }}" required>
                <button type="submit" class="btn btn-gray-800">Check</button>
              </div>
            </div>
          </form>

          @if(session('hasil'))
          <div class="alert alert-primary mt-3 text-center">
            <strong>Hasil Analisis:</strong> {{ session('hasil') }}
          </div>
          @endif

        </div>
      </div>
    </div>
  </section>
</main>
@endsection