<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <title>@yield('title') - {{ \Meta::get('title') }}</title>

    <!-- Favicon -->
    <link href="/assets/img/brand/favicon.png" rel="icon" type="image/png">

    {!! \Meta::tags() !!}
    {!! \Meta::tag('csrf-token', csrf_token()) !!}

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet">

    <!-- Icons -->
    <link href="{{ asset('assets/vendor/nucleo/css/nucleo.css') }}" rel="stylesheet">

    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css"
          integrity="sha384-50oBUHEmvpQ+1lW4y57PTFmhCaXp0ML5d60M1M7uH2+nqUivzIebhndOJK28anvf" crossorigin="anonymous">

    <!-- Argon CSS -->
    <link type="text/css" href="{{ asset('assets/css/argon.min.css') }}" rel="stylesheet">
</head>

<body>
@include('components.sidenav')

<div class="main-content" id="panel">
    @include('components.navbar')
    @include('components.header')

    <div class="container-fluid mt--6">
        @yield('content')

        @include('components.footer')
    </div>
</div>

<!-- Core -->
<script src="{{ asset('assets/vendor/jquery/dist/jquery.min.js') }}"></script>
<script src="{{ asset('assets/vendor/bootstrap/dist/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('assets/vendor/jquery.scrollbar/jquery.scrollbar.min.js') }}"></script>
<script src="{{ asset('assets/vendor/jquery-scroll-lock/dist/jquery-scrollLock.min.js') }}"></script>
<script src="{{ asset('assets/vendor/chart.js/dist/Chart.min.js') }}"></script>
<script src="{{ asset('assets/vendor/chart.js/dist/Chart.extension.js') }}"></script>
<script src="{{ asset('assets/vendor/jvectormap-next/jquery-jvectormap.min.js') }}"></script>
<script src="{{ asset('assets/js/vendor/jvectormap/jquery-jvectormap-world-mill.js') }}"></script>
<script src="{{ asset('assets/vendor/js-cookie/js.cookie.js') }}"></script>

<!-- Argon JS -->
<script src="{{ asset('assets/js/argon.min.js?v=1.1.0') }}"></script>
<script src="{{ asset('assets/js/demo.min.js') }}"></script>
</body>
</html>
