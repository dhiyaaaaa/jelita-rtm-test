<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Laporan Audit</title>

    <style>
        /* General */
        body {
            font-family: 'Times New Roman', Times, serif;
            display: flex
        }

        p {
            margin: 0;
            padding: 0;
        }

        #container {
            height: 100%;

        }

        /* Title */
        #title {
            text-align: center;
            padding-top: 50px;
        }

        #title p {
            font-size: 22px;
            font-weight: bold;
            margin: 10px;
        }

        /* Image */
        #img {
            text-align: center;
            margin: 100px 0;
        }

        /* Lembaga */
        #lembaga {
            text-align: center;
        }

        #lembaga p {
            font-size: 16px;
            font-weight: bold;
            margin: 8px;
        }

        /* Footer */
        #footer {
            text-align: center;
            margin-top: 200px;
        }

        #footer p {
            font-size: 14px;
            font-weight: bold;
            margin: 8px;
        }
    </style>
</head>

<body>
    {{-- Page Cover --}}
    <div id="container">
        {{-- Title --}}
        <div id="title">
            <p>LAPORAN</p>
            <p>AUDIT INTERNAL MUTU AKADEMIK </p>
            <p>TAHUN {{ $tahun }}</p>
        </div>

        {{-- Image --}}
        <div id="img">
            <img src="{{ public_path('dist/img/logo_unsoed.png') }}" alt="Logo" style="width:180px; height:180px;">
        </div>

        <div id="lembaga">
            <p>PUSAT PENJAMINAN MUTU</p>
            <p>LEMBAGA PENGEMBANGAN PEMBELAJARAN DAN PENJAMINAN MUTU</p>
        </div>

        {{-- Footer --}}
        <div id="footer">
            <p>KEMENTERIAN PENDIDIKAN TINGGI, SAINS, DAN TEKNOLOGI</p>
            <p>UNIVERSITAS JENDERAL SOEDIRMAN</p>
            <p>PURWOKERTO</p>
            <p>{{ $tahun }}</p>
        </div>
    </div>
</body>

</html>
