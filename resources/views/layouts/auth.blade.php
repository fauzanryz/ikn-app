<!DOCTYPE html>
<html lang="en">

<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <!-- Primary Meta Tags -->
  <title>Sentimen IKN</title>
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="title" content="Sentimen IKN">

  <!-- Favicon -->
  <link rel="apple-touch-icon" sizes="120x120" href="{{ asset('volt/assets/img/brand/logo.png') }}">
  <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('volt/assets/img/brand/logo.png') }}">
  <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('volt/assets/img/brand/logo.png') }}">
  <meta name="msapplication-TileColor" content="#ffffff">
  <meta name="theme-color" content="#ffffff">

  <link type="text/css" href="{{ asset('volt/vendor/sweetalert2/dist/sweetalert2.min.css') }}" rel="stylesheet">
  <link type="text/css" href="{{ asset('volt/vendor/notyf/notyf.min.css') }}" rel="stylesheet">
  <link type="text/css" href="{{ asset('volt/vendor/fullcalendar/main.min.css') }}" rel="stylesheet"><!-- Apex Charts -->
  <link type="text/css" href="{{ asset('volt/vendor/apexcharts/dist/apexcharts.css') }}" rel="stylesheet"><!-- Dropzone -->
  <link type="text/css" href="{{ asset('volt/vendor/dropzone/dist/min/dropzone.min.css') }}" rel="stylesheet"><!-- Choices  -->
  <link type="text/css" href="{{ asset('volt/vendor/choices.js/public/assets/styles/choices.min.css') }}" rel="stylesheet"><!-- Leaflet JS -->
  <link type="text/css" href="{{ asset('volt/vendor/leaflet/dist/leaflet.css') }}" rel="stylesheet"><!-- Volt CSS -->
  <link type="text/css" href="{{ asset('volt/css/volt.css') }}" rel="stylesheet">
</head>

<body>

  <main>
    @yield('content')
  </main>

  <script src="{{ asset('volt/vendor/@popperjs/core/dist/umd/popper.min.js') }}"></script>
  <script src="{{ asset('volt/vendor/bootstrap/dist/js/bootstrap.min.js') }}"></script><!-- Vendor JS -->
  <script src="{{ asset('volt/vendor/onscreen/dist/on-screen.umd.min.js') }}"></script><!-- Slider -->
  <script src="{{ asset('volt/vendor/nouislider/distribute/nouislider.min.js') }}"></script><!-- Smooth scroll -->
  <script src="{{ asset('volt/vendor/smooth-scroll/dist/smooth-scroll.polyfills.min.js') }}"></script><!-- Count up -->
  <script src="{{ asset('volt/vendor/countup.js/dist/countUp.umd.js') }}"></script><!-- Apex Charts -->
  <script src="{{ asset('volt/vendor/apexcharts/dist/apexcharts.min.js') }}"></script><!-- Datepicker -->
  <script src="{{ asset('volt/vendor/vanillajs-datepicker/dist/js/datepicker.min.js') }}"></script><!-- DataTables -->
  <script src="{{ asset('volt/vendor/simple-datatables/dist/umd/simple-datatables.js') }}"></script><!-- Sweet Alerts 2 -->
  <script src="{{ asset('volt/vendor/sweetalert2/dist/sweetalert2.min.js') }}"></script><!-- Moment JS -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.27.0/moment.min.js"></script><!-- Vanilla JS Datepicker -->
  <script src="{{ asset('volt/vendor/vanillajs-datepicker/dist/js/datepicker.min.js') }}"></script><!-- Full Calendar -->
  <script src="{{ asset('volt/vendor/fullcalendar/main.min.js') }}"></script><!-- Dropzone -->
  <script src="{{ asset('volt/vendor/dropzone/dist/min/dropzone.min.js') }}"></script><!-- Choices.js -->
  <script src="{{ asset('volt/vendor/choices.js/public/assets/scripts/choices.min.js') }}"></script><!-- Notyf -->
  <script src="{{ asset('volt/vendor/notyf/notyf.min.js') }}"></script><!-- Mapbox & Leaflet.js -->
  <script src="{{ asset('volt/vendor/leaflet/dist/leaflet.js') }}"></script><!-- SVG Map -->
  <script src="{{ asset('volt/vendor/svg-pan-zoom/dist/svg-pan-zoom.min.js') }}"></script>
  <script src="{{ asset('volt/vendor/svgmap/dist/svgMap.min.js') }}"></script><!-- Simplebar -->
  <script src="{{ asset('volt/vendor/simplebar/dist/simplebar.min.js') }}"></script><!-- Sortable Js -->
  <script src="{{ asset('volt/vendor/sortablejs/Sortable.min.js') }}"></script><!-- Github buttons -->
  <script async defer="defer" src="https://buttons.github.io/buttons.js"></script><!-- Volt JS -->
  <script src="{{ asset('') }}volt/assets/js/volt.js"></script>
  <script defer src="https://static.cloudflareinsights.com/beacon.min.js/vcd15cbe7772f49c399c6a5babf22c1241717689176015" integrity="sha512-ZpsOmlRQV6y907TI0dKBHq9Md29nnaEIPlkf84rnaERnq6zvWvPUqr2ft8M1aS28oN72PdrCzSjY4U6VaAw1EQ==" data-cf-beacon='{"rayId":"943313b0d9bf405f","version":"2025.4.0-1-g37f21b1","r":1,"token":"3a2c60bab7654724a0f7e5946db4ea5a","serverTiming":{"name":{"cfExtPri":true,"cfL4":true,"cfSpeedBrain":true,"cfCacheStatus":true}}}' crossorigin="anonymous"></script>

</body>

</html>