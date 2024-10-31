<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login | JELITA</title>
    <link rel="icon" href="{{ asset('dist/img/logo_jelita.png') }}" />
    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ asset('plugins/fontawesome-free/css/all.min.css') }}">
    <!-- SweetAlert2 -->
    <link rel="stylesheet" href="{{ asset('plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css') }}">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset('dist/css/adminlte.min.css') }}">
    <!-- Login Style -->
    <link rel="stylesheet" href="{{ asset('dist/css/login.css') }}">
</head>

<body>
    {{-- Sweet Alert --}}
    @include('sweetalert::alert')

    <div id="container" class="min-vh-100" style="background-image: url('{{ asset('dist/img/bg.jpg') }}');">

        <!-- kiri -->
        <div id="kiri" class="col-8 h-100">
            {{-- Logo Unsoed --}}
            <div class="d-flex align-items-center fade-in no-select">
                <img src="{{ asset('dist/img/logo_unsoed.png') }}" alt="UNSOED" id="logo-unsoed">
                <p class="text-bold text-light" style="font-size: 3vw; margin-left:1.5vw;">Universitas Jenderal
                    Soedirman</p>
            </div>

            {{-- Typing JELITA --}}
            <div style="margin-top:3vw;">
                <p class="typing no-select">Jenderal Soedirman Internal</p>
                <p class="typing no-select">Quality Assurance</p>
            </div>

            {{-- Animasi --}}
            <div class="d-flex justify-content-end fade-in">
                <img src="{{ asset('dist/img/animasi_1.png') }}" alt="Animasi" class="shake" id="animasi-1">
            </div>
        </div>

        <!-- kanan -->
        <div id="kanan">

            {{-- Logo JELITA --}}
            <img src="{{ asset('dist/img/logo_jelita.png') }}" alt="Jelita" class="no-select" id="logo-jelita">

            <!-- Form Login -->
            <form action="{{ route('login.store') }}" method="post" id="login-form" class="w-100 px-4">
                @csrf
                <div class="div-wrapper w-100">
                    <input id="inputEmail" type="email" autocomplete="off" placeholder=" " required
                        class="input w-100" name="email">
                    <label for="inputEmail" class="label" style="font-weight: 400;">Email</label>
                    @error('email')
                        <span class="text-danger d-block" style="font-size: 16px">{{ $message }}</span>
                    @enderror
                </div>

                <div class="div-wrapper w-100 mt-4">
                    <input id="inputPassword" type="password" autocomplete="off" placeholder=" " required
                        class="input w-100" name="password">
                    <label for="inputPassword" class="label" style="font-weight: 400;">Password</label>
                    @error('password')
                        <span class="text-danger d-block" style="font-size: 16px">{{ $message }}</span>
                    @enderror
                </div>

                <div class="div-wrapper">
                    <button type="submit" id="login-button"
                        class="btn btn-primary btn-block mt-4 shadow-sm signin-btn">Sign In</button>
                    <button id="login-button-loading" class="btn btn-primary btn-block mt-5 shadow-sm d-none"
                        type="button" disabled>
                        <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                        Loading...
                    </button>
                </div>
            </form>

            {{-- SSO --}}
            <div class="w-100 px-4 mt-4 mb-5">
                <div class="div-wrapper">
                    <a href="{{ route('login.google') }}"
                        class="btn btn-light border-dark btn-block shadow-sm sso-btn">
                        <img src="{{ asset('dist/img/logo_unsoed.png') }}" alt="Unsoed"
                            style="width: 20px; height:20px">
                        Sign In dengan SSO</a>
                </div>
            </div>
        </div>

    </div>


    <!-- /.login-box -->

    <!-- jQuery -->
    <script src="{{ asset('plugins/jquery/jquery.min.js') }}"></script>
    <!-- AdminLTE App -->
    <script src="{{ asset('plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>

    {{-- Loading Button --}}
    <script>
        $(document).ready(function() {
            $('#login-form').on('submit', function(e) {
                $('#login-button').addClass('d-none');
                $('#login-button-loading').removeClass('d-none');
            });
        });
    </script>
</body>

</html>
