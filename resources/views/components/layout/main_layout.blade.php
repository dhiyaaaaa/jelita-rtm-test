<!DOCTYPE html>
<html lang="en">

<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title . ' | JELITA' }}</title>

    <link rel="icon" href="{{ asset('dist/img/logo_jelita.png') }}" type="image/x-icon" />

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ asset('plugins/fontawesome-free/css/all.min.css') }}">
    <!-- overlayScrollbars -->
    <link rel="stylesheet" href="{{ asset('plugins/overlayScrollbars/css/OverlayScrollbars.min.css') }}">
    <!-- SweetAlert2 -->
    <link rel="stylesheet" href="{{ asset('plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css') }}">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset('dist/css/adminlte.min.css') }}">

    @yield('style')

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
    @include('sweetalert::alert')

    <div class="wrapper d-flex flex-column min-vh-100">
        <!-- Navbar -->
        <nav class="main-header navbar navbar-expand navbar-white navbar-light fixed-top">
            <!-- Left navbar links -->
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" data-widget="pushmenu" data-enable-remember="TRUE"
                        data-no-transition-after-reload="TRUE" href="#" role="button"><i
                            class="fas fa-bars"></i></a>
                </li>
            </ul>

            <!-- Right navbar links -->
            @include('components.layout.partials.navbar')
        </nav>
        <!-- /.navbar -->

        <!-- Main Sidebar Container -->
        <aside class="main-sidebar sidebar-light-primary elevation-4">
            <!-- Brand Logo -->
            <a href="#" class="brand-link text-center mx-auto">
                <img src="{{ asset('dist/img/logo_jelita.png') }}" alt="AdminLTE Logo" class="brand-image img-circle"
                    style="opacity: .8;">
                <span class="brand-text font-weight-light">JELITA</span>
            </a>

            <!-- Sidebar -->
            <div class="sidebar">
                <!-- Sidebar Menu -->
                @include('components.layout.partials.sidebar')
                <!-- /.sidebar-menu -->
            </div>
            <!-- /.sidebar -->
        </aside>

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper flex-grow-1 pt-5">
            <!-- Content Header (Page header) -->

            <!-- Main content -->
            <section class="content">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-12 mt-4">
                            @hasSection('content')
                                @yield('content')
                            @endif
                            {{ $slot ?? '' }}
                        </div>
                    </div>
                </div>
            </section>
            <!-- /.content -->
        </div>
        <!-- /.content-wrapper -->

        {{-- Footer --}}
        <footer class="main-footer" style="font-size: 12px">
            <div class="float-right d-none d-sm-block">
                <b>Version</b> 1.0
            </div>
            <span>Copyright &copy; 2024 <a href="https://lp3m-unsoed.id/" target="blank"> Lembaga Pengembangan
                    Pembelajaran dan Penjaminan Mutu</a> | UNSOED</span>
        </footer>



    </div>
    <!-- ./wrapper -->

    <!-- jQuery -->
    <script src="{{ asset('plugins/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('plugins/jquery-ui/jquery-ui.min.js') }}"></script>
    <!-- Bootstrap 4 -->
    <script src="{{ asset('plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <!-- overlayScrollbars -->
    <script src="{{ asset('plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js') }}"></script>
    <!-- SweetAlert2 -->
    <script src="{{ asset('plugins/sweetalert2/sweetalert2.min.js') }}"></script>
    <!-- AdminLTE App -->
    <script src="{{ asset('dist/js/adminlte.min.js') }}"></script>

    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    </script>

    @yield('script')
    
    {{-- Livewire --}}
    
    @livewireScripts 
    @stack('scripts')
</body>

</html>
