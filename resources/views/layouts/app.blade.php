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
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/wordcloud@1.1.2/src/wordcloud2.min.js"></script>
</head>

<body>
  <nav class="navbar navbar-dark navbar-theme-primary px-4 col-12 d-lg-none">
    <a class="navbar-brand me-lg-5" href="{{ asset('volt/index.html') }}">
      <img class="navbar-brand-dark" src="{{ asset('volt/assets/img/brand/logo.png') }}" alt="Volt logo" /> <img class="navbar-brand-light" src="{{ asset('volt/assets/img/brand/dark.svg') }}" alt="Volt logo" />
    </a>
    <div class="d-flex align-items-center">
      <button class="navbar-toggler d-lg-none collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#sidebarMenu" aria-controls="sidebarMenu" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
    </div>
  </nav>

  <nav id="sidebarMenu" class="sidebar d-lg-block bg-gray-800 text-white collapse" data-simplebar>
    <div class="sidebar-inner px-4 pt-3">
      <div class="user-card d-flex d-md-none align-items-center justify-content-between justify-content-md-center p-3">
        <div class="collapse-close d-md-none">
          <a href="#sidebarMenu" data-bs-toggle="collapse"
            data-bs-target="#sidebarMenu" aria-controls="sidebarMenu" aria-expanded="true"
            aria-label="Toggle navigation">
            <svg class="icon icon-xs" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
              <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
            </svg>
          </a>
        </div>
      </div>
      <ul class="nav flex-column pt-3 pt-md-0">
        <li class="nav-item">
          <div class="nav-link d-flex align-items-center">
            <span class="sidebar-icon">
              <img src="{{ asset('volt/assets/img/brand/logo.png') }}" height="30" width="20">
            </span>
            <span class="mt-1 sidebar-text" style="margin-left: 12px; font-weight: bold;">SENTIMEN IKN</span>
          </div>
        </li>

        <li role="separator" class="dropdown-divider mt-2 mb-3 border-gray-700"></li>

        <!-- Dashboard -->
        <li class="nav-item {{ Request::routeIs('dashboard') ? 'active' : '' }}">
          <a href="{{ route('dashboard') }}" class="nav-link">
            <span class="sidebar-icon">
              <svg class="icon icon-xs me-2" fill="currentColor" viewBox="0 0 20 20">
                <path d="M2 10a8 8 0 018-8v8h8a8 8 0 11-16 0z"></path>
                <path d="M12 2.252A8.014 8.014 0 0117.748 8H12V2.252z"></path>
              </svg>
            </span>
            <span class="sidebar-text">Dashboard</span>
          </a>
        </li>

        <!-- Dataset Dropdown -->
        <li class="nav-item">
          <span class="nav-link collapsed d-flex justify-content-between align-items-center" data-bs-toggle="collapse" data-bs-target="#submenu-dataset">
            <span>
              <span class="sidebar-icon">
                <svg class="icon icon-xs me-2" fill="currentColor" viewBox="0 0 20 20">
                  <path d="M4 3a2 2 0 00-2 2v1a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zM4 9a2 2 0 00-2 2v1a2 2 0 002 2h12a2 2 0 002-2v-1a2 2 0 00-2-2H4zM4 15a2 2 0 00-2 2v1a2 2 0 002 2h12a2 2 0 002-2v-1a2 2 0 00-2-2H4z"></path>
                </svg>
              </span>
              <span class="sidebar-text">Dataset</span>
            </span>
            <span class="link-arrow">
              <svg class="icon icon-sm" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M5.293 9.293a1 1 0 011.414 0L10 12.586l3.293-3.293a1 1 0 011.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path>
              </svg>
            </span>
          </span>
          <div class="multi-level collapse" role="list" id="submenu-dataset">
            <ul class="flex-column nav">
              <li class="nav-item {{ Request::routeIs('dataset.full') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('dataset.full') }}">
                  <span class="sidebar-text">Dataset Full</span>
                </a>
              </li>
              <li class="nav-item {{ Request::routeIs('dataset.fulltext') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('dataset.fulltext') }}">
                  <span class="sidebar-text">Full Text</span>
                </a>
              </li>
            </ul>
          </div>
        </li>

        <!-- Preprocessing Dropdown -->
        <li class="nav-item">
          <span class="nav-link collapsed d-flex justify-content-between align-items-center" data-bs-toggle="collapse" data-bs-target="#submenu-preprocessing">
            <span>
              <span class="sidebar-icon">
                <svg class="icon icon-xs me-2" fill="currentColor" viewBox="0 0 20 20">
                  <path d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2H3V4zm0 4h16v2H3V8zm0 4h10v2H3v-2z"></path>
                </svg>
              </span>
              <span class="sidebar-text">Preprocessing</span>
            </span>
            <span class="link-arrow">
              <svg class="icon icon-sm" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M5.293 9.293a1 1 0 011.414 0L10 12.586l3.293-3.293a1 1 0 011.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path>
              </svg>
            </span>
          </span>
          <div class="multi-level collapse" role="list" id="submenu-preprocessing">
            <ul class="flex-column nav">
              <li class="nav-item {{ Request::routeIs('preprocessing') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('preprocessing') }}">
                  <span class="sidebar-text">Preprocess</span>
                </a>
              </li>
              <li class="nav-item {{ Request::routeIs('preprocessing.dataclean') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('preprocessing.dataclean') }}">
                  <span class="sidebar-text">Data Clean</span>
                </a>
              </li>
            </ul>
          </div>
        </li>

        <!-- TF-IDF -->
        <li class="nav-item {{ Request::routeIs('naivebayes') ? 'active' : '' }}">
          <a href="{{ route('naivebayes') }}" class="nav-link">
            <span class="sidebar-icon">
              <svg class="icon icon-xs me-2" fill="currentColor" viewBox="0 0 24 24">
                <path d="M3 3h18v2H3V3zm2 4h14v2H5V7zm-2 4h18v2H3v-2zm2 4h14v2H5v-2zm-2 4h18v2H3v-2z" />
              </svg>
            </span>
            <span class="sidebar-text">Naive Bayes</span>
          </a>
        </li>

        <li role="separator" class="dropdown-divider mt-3 mb-3 border-gray-700"></li>

        <!-- Logout -->
        <li class="nav-item">
          <a href="#" class="nav-link d-flex align-items-center" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
            <span class="sidebar-icon">
              <svg class="icon icon-xs me-2" fill="currentColor" viewBox="0 0 24 24">
                <path d="M16 13v-2H7V8l-5 4 5 4v-3h9z"></path>
                <path d="M20 3h-8v2h8v14h-8v2h8c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2z"></path>
              </svg>
            </span>
            <span class="sidebar-text">Keluar</span>
          </a>

          <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
            @csrf
          </form>
        </li>
      </ul>
    </div>
  </nav>

  <main class="content">
    <nav class="navbar navbar-top navbar-expand navbar-dashboard navbar-dark ps-0 pe-2 pb-0">
      <div class="container-fluid px-0">
        <div class="d-flex justify-content-between w-100" id="navbarSupportedContent">
          <div class="d-flex align-items-center"><button id="sidebar-toggle" class="sidebar-toggle me-3 btn btn-icon-only d-none d-lg-inline-block align-items-center justify-content-center"><svg class="toggle-icon" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                <path fill-rule="evenodd" d="M3 5a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 10a1 1 0 011-1h6a1 1 0 110 2H4a1 1 0 01-1-1zM3 15a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd"></path>
              </svg></button>
          </div><!-- Navbar links -->
          <ul class="navbar-nav align-items-center">
          </ul>
        </div>
      </div>
    </nav>
    @yield('content')
    <!-- Footer -->
    <footer class="bg-white rounded shadow p-4 mb-4 mt-4">
      <div class="row">
        <div class="col-12 col-md-6 mb-4 mb-md-0">
          <p class="mb-0 text-center text-md-start">© 2025 All rights reserved.</p>
        </div>
        <div class="col-12 col-md-6 text-end">
          <p class="mb-0 text-center text-md-end">ikn.go.id | made with &lt;3</p>
        </div>
      </div>
    </footer>
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
  @yield('js')
</body>

</html>