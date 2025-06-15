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

            body, body * {
                font-family: "Times New Roman", Times, serif !important;
                font-size: 12pt;
                line-height: 1.5;
            }

            .center {
                text-align: center;
            }

            .institution {
                font-size: 14pt !important;
                font-weight: bold;
                text-transform: uppercase;
            }

            .header-right {
                text-align: left;
            }

            .address {
                font-size: 12pt;
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
                margin-bottom: 30px; /* Tambahkan margin bottom untuk jarak dengan tabel TTD */
                border: none !important;
            }

            table.info td {
                padding: 5px;
                vertical-align: top;
                border: none !important;
            }

            .ttd-table {
                width: 100%;
                text-align: center;
                margin-top: 40px; /* Tambahkan margin top untuk jarak dengan tabel di atasnya */
                font-size: 12pt; /* Samakan ukuran font dengan yang lain */
            }
            
            .ttd-table th, .ttd-table td {
                padding: 6px 8px;
                font-size: 12pt; /* Samakan ukuran font dengan yang lain */
            }
            
            .barcode-small {
                width: 70px;
                height: 70px;
                object-fit: contain;
            }
            
            .description-text {
                font-size: 12pt; /* Samakan ukuran font dengan yang lain */
                margin-bottom: 20px;
            }

        </style>
    </head>
    <body>
        <table width="100%">
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

        <p class="description-text">
            Telah dilaksanakan kegiatan Rapat Tinjauan Manajemen atas hasil {{ $rtmJadwal->jadwal_audit->jadwal }} yang dipimpin oleh {{ $rtmJadwal->pimpinan }} dan dihadiri oleh {{ $rtmJadwal->peserta }} peserta yang dilaksanakan pada:
        </p>

        <table class="info" border="1">
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

        <table class="ttd-table">
            <thead>
                <tr>
                    <th></th>
                    <th>Purwokerto, {{ \Carbon\Carbon::parse($rtmJadwal->tanggal)->translatedFormat('l, j F Y') }}</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>
                        @if($approvalRektor && $approvalRektor->user->jabatan->isNotEmpty())
                            {{ $approvalRektor->user->jabatan->first()->nama }}
                        @else
                            -
                        @endif
                    </td>
                    <td>
                        @if($approvalKetuaLp3m && $approvalKetuaLp3m->user->jabatan->isNotEmpty())
                            {{ $approvalKetuaLp3m->user->jabatan->first()->nama }}
                        @else
                            -
                        @endif
                    </td>
                </tr>
                <tr>
                    <td>
                        @if($barcodeRektorPath)
                            <img src="{{ $barcodeRektorPath }}" class="barcode-small" alt="Barcode Approval Rektor">
                            @if($approvalRektor && $approvalRektor->user)
                                <div class="user-name">{{ $approvalRektor->user->name }}</div>
                            @endif
                        @else
                            -
                        @endif
                    </td>
                    <td>
                        @if($barcodeKetuaLp3mPath)
                            <img src="{{ $barcodeKetuaLp3mPath }}" class="barcode-small" alt="Barcode Approval Lp3m">
                            @if($approvalKetuaLp3m && $approvalKetuaLp3m->user)
                                <div class="user-name">{{ $approvalKetuaLp3m->user->name }}</div>
                            @endif
                        @else
                            -
                        @endif
                    </td>
                </tr>
            </tbody>
        </table>
    </body>
</html>