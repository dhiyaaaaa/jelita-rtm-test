<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Halaman Menu</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

    <style>
        html, body {
            height: 100%;
            margin: 0;
            overflow: hidden; /* Mencegah scroll global */
        }

        .full-page-layout {
            display: flex;
            flex-direction: column;
            height: 100%;
        }

        .navbar-fixed {
            flex: 0 0 auto;
            box-shadow: rgb(166, 168, 184) 0px 1px 20px;
            background: rgb(236, 239, 250);
            padding: 20px 40px;
            z-index: 10;
            display: flex;
            gap: 10px;
        }

        .navbar-fixed h5 {
            margin: 0;
            font-size: 20px;
            color: #2c4599;
            font-weight: 600;
        }

        .scrollable-content {
            flex: 1 1 auto;
            overflow-y: auto;
            padding: 40px 20px;
            background-color: #f5f6fa;
        }

        .main-footer {
            flex: 0 0 auto;
            font-size: 12px;
            text-align: center;
            background: #f8f9fa;
            padding: 10px;
            box-shadow: 0 -1px 10px rgba(0, 0, 0, 0.1);
        }

        .menu-card {
            background: linear-gradient(to bottom right, #6a85de, #314f9a);
            border-radius: 20px;
            padding: 30px 20px;
            color: white;
            text-align: center;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
            transition: 0.3s ease;
        }

        .menu-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 25px rgba(0, 0, 0, 0.2);
        }

        .menu-icon {
            font-size: 60px;
            margin-bottom: 20px;
        }

        .menu-card a.btn {
            background: #fff;
            color: #375ab1;
            padding: 8px 20px;
            border-radius: 8px;
            font-weight: bold;
            margin-top: 20px;
        }

        .menu-card a.btn:hover {
            background-color: #e6e6e6;
            color: #2c4599;
        }
    </style>
</head>
<body>

<div class="full-page-layout">
    <!-- Navbar -->
    <div class="navbar-fixed d-flex align-items-center">
        <img src="{{ asset('dist/img/logo_jelita.png') }}" alt="Logo JELITA" class="img-fluid" style="height: 40px; width: 40px;">
        <h5 class="mb-0 ml-3 font-weight-light" style="font-size: 20px;">JELITA</h5>
    </div>


    <!-- Scrollable content -->
    <div class="scrollable-content">
        <div class="container">
            <div class="text-center mb-4 mt-2">
                <h4><b>Pilih Menu JELITA</b></h4>
            </div>

            <div class="row justify-content-center">
                @foreach ($mainMenus as $mainMenu)
                    @php
                        $firstMenu = $mainMenu->menu->first();
                        $link = $firstMenu ? url($firstMenu->route) : '#';
                        $icons = ['fas fa-layer-group', 'fas fa-users', 'fas fa-database', 'fas fa-book'];
                        $icon = $icons[$loop->index % count($icons)];
                    @endphp

                    <div class="col-md-4 mb-4">
                        <div class="menu-card">
                            <div class="menu-icon">
                                <i class="{{ $icon }}"></i>
                            </div>
                            <h4>{{ $mainMenu->mainmenu }}</h4>
                            <h6>{{ $mainMenu->deskripsi }}</h6>
                            <a href="{{ $link }}" class="btn">Masuk</a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="main-footer">
        <div class="float-right d-none d-sm-block">
            <b>Version</b> 1.0
        </div>
        <span>Copyright &copy; 2024 
            <a href="https://lp3m-unsoed.id/" target="_blank">Lembaga Pengembangan Pembelajaran dan Penjaminan Mutu</a> | UNSOED
        </span>
    </footer>
</div>

</body>
</html>
