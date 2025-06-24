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
    <!-- DataTables CSS (Jika Anda masih ingin menggunakannya untuk hal lain selain paginasi/search Livewire) -->
    <link rel="stylesheet" href="{{ asset('plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">

    @yield('style')
    @stack('styles')

    <style>
        .dropdown-item i {
            margin-right: 5px;
        }

        .dropdown-item:hover {
            background-color: #f8f9fa;
        }

        .btn-fixed-size {
            width: 120px;
            height: 38px;
            display: flex;
            align-items: center;
            justify-content: center;
            white-space: nowrap;
        }

        .dropdown .btn-fixed-size {
            width: 120px;
        }

        /* Gaya tambahan untuk modal Livewire/Alpine */
        [x-cloak] { display: none !important; } /* Sembunyikan elemen sebelum Alpine/Livewire siap */
    </style>
    @livewireStyles
</head>

<body class="hold-transition sidebar-mini layout-fixed" style="font-size: 16px;">

    @include('sweetalert::alert')

    <div class="wrapper d-flex flex-column min-vh-100">
        <!-- Navbar -->
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
                </div>
            </section>
        </div>

        <footer class="main-footer" style="font-size: 12px">
            <div class="float-right d-none d-sm-block">
                <b>Version</b> 1.0
            </div>
            <span>Copyright &copy; 2024 <a href="https://lp3m-unsoed.id/" target="blank"> Lembaga Pengembangan
                        Pembelajaran dan Penjaminan Mutu</a> | UNSOED</span>
        </footer>
    </div>

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
    <!-- DataTables JS -->
    <script src="{{ asset('plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>

    {{-- Penting: Hapus muatan Alpine.js dari CDN, Livewire sudah bundling Alpine --}}
    {{-- <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script> --}}

    {{-- Livewire Scripts (Wajib, setelah semua JS framework) --}}
    @livewireScripts

    @yield('script')
    @stack('scripts')
</body>

</html>
