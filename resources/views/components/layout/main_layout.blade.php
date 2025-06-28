<!DOCTYPE html>
<html lang="en">

<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title . ' | JELITA' }}</title>

    <link rel="icon" href="{{ asset('dist/img/logo_jelita.png') }}" type="image/x-icon" />

    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <link rel="stylesheet" href="{{ asset('plugins/fontawesome-free/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/overlayScrollbars/css/OverlayScrollbars.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('dist/css/adminlte.min.css') }}">

    @yield('style')

    {{-- menmabahkan untuk livewire --}}
    @livewireStyles

    <style>
        /* Notifikasi */
        .blink {
            animation: blinker 1s linear infinite;
        }

        @keyframes blinker {
            50% {
                opacity: 0;
            }
        }

        .title-size {
            font-size: 24px;
        }

        /* Badge */
        .badge-custom-purple {
            background-color: #6610f2;
            color: white;
        }

        .badge-custom-fuchsia {
            background-color: #f012be;
            color: white;
        }

        .badge-custom-orange {
            background-color: #ff851b;
            color: white;
        }
    </style>
</head>

<body class="hold-transition sidebar-mini layout-fixed" style="font-size: 16px;">

    {{-- Sweet Alert --}}
    @include('sweetalert::alert') {{-- SweetAlert bawaan Laravel --}}

    <div class="wrapper d-flex flex-column min-vh-100">
        <nav class="main-header navbar navbar-expand navbar-white navbar-light fixed-top">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" data-widget="pushmenu" data-enable-remember="TRUE"
                        data-no-transition-after-reload="TRUE" href="#" role="button"><i
                            class="fas fa-bars"></i></a>
                </li>
            </ul>

            @include('components.layout.partials.navbar')
        </nav>
        <aside class="main-sidebar sidebar-light-primary elevation-4">
            <a href="#" class="brand-link text-center mx-auto">
                <img src="{{ asset('dist/img/logo_jelita.png') }}" alt="AdminLTE Logo" class="brand-image img-circle"
                    style="opacity: .8;">
                <span class="brand-text font-weight-light">JELITA</span>
            </a>

            <div class="sidebar">
                @include('components.layout.partials.sidebar')
                </div>
            </aside>

        <div class="content-wrapper flex-grow-1 pt-5">
            <section class="content">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-12 mt-4">
                            @yield('content')
                        </div>
                    </div>
                </div></section>
            </div>
        <footer class="main-footer">
            <div class="float-right d-none d-sm-block">
                <b>Version</b> 3.2.0
            </div>
            <strong>Copyright &copy; 2014-2021 <a href="https://adminlte.io">AdminLTE.io</a>.</strong> All rights
            reserved.
        </footer>

        <aside class="control-sidebar control-sidebar-dark">
            </aside>
        </div>
    <script src="{{ asset('plugins/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js') }}"></script>
    <script src="{{ asset('dist/js/adminlte.min.js') }}"></script>
    <script src="{{ asset('plugins/sweetalert2/sweetalert2.min.js') }}"></script>

    @yield('script')

    {{-- Livewire --}}
    @livewireScripts 
    @stack('scripts') {{-- Pastikan ada @stack('scripts') untuk script dari komponen Livewire --}}
</body>

</html>