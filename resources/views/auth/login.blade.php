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
    <!-- Section -->
    <section class="vh-lg-100 mt-5 mt-lg-0 bg-soft d-flex align-items-center">
        <div class="container">
            <div class="col-12 d-flex align-items-center justify-content-center">
                <div class="bg-white shadow border-0 rounded border-light p-4 p-lg-5 w-100 fmxw-500">
                    <div class="mb-4 mt-md-0">
                        <h1 class="mb-0 h3" style="font-weight: bold;">Login</h1>
                        <div class="small mb-3" style="font-size: 0.7rem; margin-top: -1px;">Selamat datang, masukkan email dan password Anda!</div>
                    </div>
                    <form action="{{ route('login') }}" method="POST" class="mt-4">
                        @csrf
                        <div class="form-group mb-2">
                            <label for="email">Email</label>
                            <div class="input-group" style="margin-top: -5px;">
                                <span class="input-group-text" id="basic-addon1">
                                    <svg class="icon icon-xs text-gray-600" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"></path>
                                        <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"></path>
                                    </svg>
                                </span>
                                <input type="email" name="email" class="form-control" placeholder="example@gmail.com" id="email" autofocus required>
                            </div>
                        </div>
                        <div class="form-group mb-3">
                            <label for="password">Password</label>
                            <div class="input-group" style="margin-top: -5px;">
                                <span class="input-group-text" id="basic-addon2">
                                    <svg class="icon icon-xs text-gray-600" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                        <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"></path>
                                    </svg>
                                </span>
                                <input type="password" name="password" placeholder="Password" class="form-control" id="password" required>
                            </div>
                        </div>
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" name="remember" id="remember">
                            <label class="form-check-label" for="remember">
                                Remember Me
                            </label>
                        </div>
                        <div class="d-grid">
                            <button type="submit" class="btn btn-gray-800">Login</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
</main>
@endsection