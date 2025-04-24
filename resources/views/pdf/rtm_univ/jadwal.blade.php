<!DOCTYPE html>
<html lang="en">
<head>
    <style>
        @page {
            size: A4 portrait;
            margin: 25mm;
        }

        body {
            font-family: "Times New Roman", serif;
            font-size: 16px;
            color: #000;
            line-height: 1.5;
            text-align: justify;
            margin: 0;
            padding: 0;
        }

        .container {
            width: 100%;
            padding: 30px;
            box-sizing: border-box;
        }

        h3 {
            text-align: center;
            font-weight: bold;
            font-size: 18px;
            margin-bottom: 15px;
        }

        .content {
            margin-bottom: 20px;
        }

        .table {
            margin-top: 10px;
            font-size: 16px;
            width: 100%;
            border-collapse: collapse;
        }

        .table td, .table th {
            padding: 8px 12px;
            border: 1px solid #000;
        }

        .table td:first-child {
            width: 30%;
            font-weight: bold;
            text-align: left;
            background-color: #f2f2f2;
        }

        .table td:nth-child(2) {
            width: 5%;
            text-align: center;
            font-weight: bold;
        }

        .table td:last-child {
            width: 65%;
            text-align: left;
        }
    </style>
</head>
<body>
    <div class="container">
        
        <h3>RTM  Universitas Jenderal Soedirman Tahun {{ \Carbon\Carbon::parse($rtmJadwal->tanggal)->translatedFormat('Y') }}</h3>

        <div class="content">
            Telah dilaksanakan Rapat Tinjauan Manajemen (RTM) sebagai tindak lanjut dari kegiatan audit penjaminan mutu internal yang dilakukan oleh Universitas Jenderal Soedirman untuk memaparkan hasil audit penjaminan mutu atas layanan bidang akademik di semua Program Studi, yang diselenggarakan pada {{ \Carbon\Carbon::parse($rtmJadwal->tanggal)->translatedFormat('l, j F Y') }}. Rapat ini dihadiri oleh sejumlah {{ $rtmJadwal->peserta }} peserta rapat.
        </div>
        

        <table class="table">
            <tr>
                <td>Agenda</td>
                <td>:</td>
                <td>{{ $rtmJadwal->agenda }}</td>
            </tr>
            <tr>
                <td>Tanggal</td>
                <td>:</td>
                <td>{{ \Carbon\Carbon::parse($rtmJadwal->tanggal)->translatedFormat('l, j F Y') }}</td>
            </tr>
            <tr>
                <td>Waktu</td>
                <td>:</td>
                <td>{{ \Carbon\Carbon::parse($rtmJadwal->jam_mulai)->format('H:i') }} - {{ \Carbon\Carbon::parse($rtmJadwal->jam_selesai)->format('H:i') }}</td>
            </tr>
            <tr>
                <td>Tempat</td>
                <td>:</td>
                <td>{{ $rtmJadwal->tempat }}</td>
            </tr>
            <tr>
                <td>Pimpinan Rapat</td>
                <td>:</td>
                <td>{{ $rtmJadwal->pimpinan }}</td>
            </tr>
            <tr>
                <td>Periode Audit</td>
                <td>:</td>
                <td>{{ $rtmJadwal->jadwal_audit->jadwal }}</td>
            </tr>
            <tr>
                <td>Jumlah Peserta Rapat</td>
                <td>:</td>
                <td>{{ $rtmJadwal->peserta }}</td>
            </tr>
        </table>
    </div>
</body>
</html>
