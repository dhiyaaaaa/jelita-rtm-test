<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login | JELITA</title>
    <link rel="icon" href="{{ asset('dist/img/logo_jelita.jpg') }}" />
    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ asset('plugins/fontawesome-free/css/all.min.css') }}">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset('dist/css/adminlte.min.css') }}">
</head>

<body class="hold-transition login-page">
    <div class="login-box">
        <!-- /.login-logo -->
        <div class="card card-outline card-primary">
            <div class="card-header text-center">
                <a href="" class="h1"><b>JELITA</b></a>
            </div>
            <div class="card-body">
                <p class="login-box-msg">Sign in to start your session</p>

                <form action="{{ route('login.store') }}" method="post" id="login-form">
                    @csrf
                    @if (session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ session('error') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif
                    <div class="input-group mt-3">
                        <input type="email" class="form-control @error('email') is-invalid @enderror"
                            placeholder="Email" name="email">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-envelope"></span>
                            </div>
                        </div>
                    </div>
                    @error('email')
                        <span class="text-danger d-block" style="font-size: 14px">{{ $message }}</span>
                    @enderror

                    <div class="input-group mt-3">
                        <input type="password" class="form-control @error('password') is-invalid @enderror"
                            placeholder="Password" name="password">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-lock"></span>
                            </div>
                        </div>
                    </div>
                    @error('password')
                        <span class="text-danger d-block"
                            style="font-size: 14px">{{ $message }}</span>
                    @enderror

                    <div class="row mt-3">
                        <!-- /.col -->
                        <div class="col">
                            <button type="submit" id="login-button" class="btn btn-primary btn-block">Sign In</button>
                            <button id="login-button-loading" class="btn btn-primary btn-block d-none" type="button"
                                disabled>
                                <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                Loading...
                            </button>
                        </div>
                        <!-- /.col -->
                    </div>
                </form>

                {{-- Button Sign In SSO --}}
                <div class="row mt-3">
                    <!-- /.col -->
                    <div class="col">
                        <a href="{{ route('login.google') }}" class="btn btn-light btn-block border-dark">
                            <img src="{{ asset('dist/img/logo_unsoed.png') }}" alt="Unsoed"
                                style="width: 20px; height:20px">
                            SSO/Kori Unsoed</a>
                    </div>
                    <!-- /.col -->
                </div>
            </div>
            <!-- /.card-body -->
        </div>
        <!-- /.card -->
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
