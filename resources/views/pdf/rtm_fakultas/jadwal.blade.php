<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Berita Acara RTM</title>
    <style>
        @page {
            size: A4;
            margin: 2.5cm;
        }

        /* Deklarasi font-face untuk memastikan TNR digunakan */
        @font-face {
            font-family: 'Times New Roman';
            src: local('Times New Roman');
            font-weight: normal;
            font-style: normal;
        }

        /* Aturan dasar untuk seluruh dokumen */
        body, body * {
            font-family: "Times New Roman", Times, serif !important;
            font-size: 12pt;
            line-height: 1.5;
            color: #000000;
        }

        /* Reset untuk semua elemen */
        body, div, p, span, td, th, table, tr, h1, h2, h3, h4, h5, h6 {
            font-family: "Times New Roman", Times, serif !important;
        }

        .center {
            text-align: center;
            font-family: "Times New Roman", Times, serif !important;
        }

        .institution {
            font-family: "Times New Roman", Times, serif !important;
            font-size: 14pt;
            font-weight: bold;
            text-transform: uppercase;
        }

        .header-right {
            text-align: left;
            font-family: "Times New Roman", Times, serif !important;
        }

        .address {
            font-size: 12pt;
            font-family: "Times New Roman", Times, serif !important;
        }

        .line {
            border: none;
            border-top: 2px solid black;
            margin: 5px 0 20px 0;
        }

        table.info {
            width: 70%;
            margin: auto;
            border-collapse: collapse;
            margin-top: 20px;
            border: none !important;
            font-family: "Times New Roman", Times, serif !important;
        }

        table.info td {
            padding: 5px;
            vertical-align: top;
            border: none !important;
            font-family: "Times New Roman", Times, serif !important;
        }

        .barcode-small {
            width: 70px;
            height: 70px;
            object-fit: contain;
        }

        .ttd {
            width: 40%;
            float: right;
            margin-top: 50px;
            text-align: center;
            font-family: "Times New Roman", Times, serif !important;
        }

        /* Tambahan untuk memaksa TNR pada elemen spesifik */
        strong, u {
            font-family: "Times New Roman", Times, serif !important;
        }

        /* Untuk gambar barcode - pastikan tidak mempengaruhi font */
        img {
            font-family: "Times New Roman", Times, serif !important;
        }
    </style>
</head>
<body>
    <table width="100%" style="font-family: 'Times New Roman', Times, serif !important;">
        <tr>
            <td width="100px" align="center">
                <img src="{{ public_path('dist/img/logo_unsoed.png') }}" width="80px">
            </td>
            <td align="center">
                <div class="institution">
                    KEMENTERIAN PENDIDIKAN TINGGI, SAINS, DAN TEKNOLOGI<br>
                    UNIVERSITAS JENDERAL SOEDIRMAN
                </div>
                <div class="address">
                    Jl. HR Boenyamin No. 708, Purwokerto, Jawa Tengah, Indonesia
                </div>
            </td>
        </tr>
    </table>

    <hr class="line">

    <div class="center">
        <strong>BERITA ACARA</strong><br>
        <strong>RAPAT TINJAUAN MANAJEMEN</strong>
    </div>

    <p style="font-family: 'Times New Roman', Times, serif !important;">
        Telah dilaksanakan kegiatan Rapat Tinjauan Manajemen atas hasil {{ $rtmJadwal->jadwal_audit->jadwal }} yang dipimpin oleh {{ $rtmJadwal->pimpinan }} dan dihadiri oleh {{ $rtmJadwal->peserta }} peserta yang dilaksanakan pada:
    </p>

    <table class="info" border="1" style="font-family: 'Times New Roman', Times, serif !important;">
        <tr>
            <td>Hari/Tanggal</td>
            <td>: {{ \Carbon\Carbon::parse($rtmJadwal->tanggal)->translatedFormat('l, j F Y') }}</td>
        </tr>
        <tr>
            <td>Tempat</td>
            <td>: {{ $rtmJadwal->tempat }}</td>
        </tr>
        <tr>
            <td>Waktu</td>
            <td>: {{ $rtmJadwal->jam_mulai }} - {{ $rtmJadwal->jam_selesai }}</td>
        </tr>
    </table>

    <div class="ttd">
        Purwokerto, {{ \Carbon\Carbon::parse($rtmJadwal->tanggal)->translatedFormat('j F Y') }} <br>
        @if($barcodePath)
            <img src="{{ $barcodePath }}" class="barcode-small" alt="approval dekan">
        @endif
        <br><br>
        <strong><u>
            @if($approvalInfo && $approvalInfo->user)
                {{ $approvalInfo->user->name }} 
            @else
                -
            @endif 
        </u></strong>
    </div>
</body>
</html>