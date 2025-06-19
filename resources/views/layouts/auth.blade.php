<!DOCTYPE html>
<html lang="en">

<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <!-- Primary Meta Tags -->
  <title>Sentimen IKN</title>
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="title" content="Sentimen IKN">

  <!-- Favicon -->
  <link rel="apple-touch-icon" href="{{ asset('volt/assets/img/brand/logo.png') }}">
  <link rel="icon" type="image/png" href="{{ asset('volt/assets/img/brand/logo.png') }}">
  <link rel="icon" type="image/png" href="{{ asset('volt/assets/img/brand/logo.png') }}">
  <meta name="msapplication-TileColor" content="#ffffff">
  <meta name="theme-color" content="#ffffff">

  <link type="text/css" href="{{ asset('volt/vendor/sweetalert2/dist/sweetalert2.min.css') }}" rel="stylesheet">
  <link type="text/css" href="{{ asset('volt/vendor/notyf/notyf.min.css') }}" rel="stylesheet">
  <link type="text/css" href="{{ asset('volt/css/volt.css') }}" rel="stylesheet">
</head>

<body>

  <main>
    @yield('content')
  </main>

  <!-- Core Vendor JS -->
  <script src="{{ asset('volt/vendor/@popperjs/core/dist/umd/popper.min.js') }}"></script>
  <script src="{{ asset('volt/vendor/bootstrap/dist/js/bootstrap.min.js') }}"></script>
  <script src="{{ asset('volt/vendor/onscreen/dist/on-screen.umd.min.js') }}"></script>
  <script src="{{ asset('volt/vendor/nouislider/distribute/nouislider.min.js') }}"></script>
  <script src="{{ asset('volt/vendor/smooth-scroll/dist/smooth-scroll.polyfills.min.js') }}"></script>
  <script src="{{ asset('vendor/chartist/dist/chartist.min.js') }}"></script>
  <script src="{{ asset('vendor/chartist-plugin-tooltips/dist/chartist-plugin-tooltip.min.js') }}"></script>
  <script src="{{ asset('volt/vendor/vanillajs-datepicker/dist/js/datepicker.min.js') }}"></script>
  <script src="{{ asset('volt/vendor/sweetalert2/dist/sweetalert2.all.min.js') }}"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.27.0/moment.min.js"></script>
  <script src="{{ asset('volt/vendor/vanillajs-datepicker/dist/js/datepicker.min.js') }}"></script>
  <script src="{{ asset('volt/vendor/notyf/notyf.min.js') }}"></script>
  <script src="{{ asset('volt/vendor/simplebar/dist/simplebar.min.js') }}"></script>
  <script async defer="defer" src="https://buttons.github.io/buttons.js"></script>
  <script src="{{ asset('volt/assets/js/volt.js') }}"></script>
  <script defer src="https://static.cloudflareinsights.com/beacon.min.js/vcd15cbe7772f49c399c6a5babf22c1241717689176015" integrity="sha512-ZpsOmlRQV6y907TI0dKBHq9Md29nnaEIPlkf84rnaERnq6zvWvPUqr2ft8M1aS28oN72PdrCzSjY4U6VaAw1EQ==" data-cf-beacon='{"rayId":"93d1e4612dd0f924","version":"2025.4.0-1-g37f21b1","r":1,"token":"3a2c60bab7654724a0f7e5946db4ea5a","serverTiming":{"name":{"cfExtPri":true,"cfL4":true,"cfSpeedBrain":true,"cfCacheStatus":true}}}' crossorigin="anonymous"></script>

</body>

</html>