<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="icon" href="{{ asset('dist/img/caution.png') }}" type="image/x-icon" />
    <title>419 | Page Expired</title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ asset('plugins/fontawesome-free/css/all.min.css') }}">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset('dist/css/adminlte.min.css') }}">

    <style>
        @media screen and (max-width: 600px) {
            .error {
                text-align: center;

            }
        }
    </style>
</head>

<body>
    <!-- Main content -->
    <section class="content">
        <div class="error-page">
            <h2 class="headline text-warning"> 419</h2>

            <div class="error-content">
                <h3><i class="fas fa-exclamation-triangle text-warning"></i> Oops! Halaman expired.</h3>

                <p class="error" style="font-size: 18px;">
                    Halaman sudah expired.
                    Untuk sementara, Anda dapat <a href="#" onclick="window.history.back();">kembali ke halaman
                        sebelumnya</a> dan mencoba untuk refresh halaman.
                </p>

            </div>
            <!-- /.error-content -->
        </div>
        <!-- /.error-page -->
    </section>
</body>

</html>
