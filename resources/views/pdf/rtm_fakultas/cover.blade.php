<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan RTM</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        @page {
            size: A4 portrait;
            margin: 20mm;
        }
        body {
            margin: 0;
            font-family: 'Arial', sans-serif;
            background: linear-gradient(135deg, #FFD700, #FFA500);
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .container {
            background: white;
            padding: 40px;
            width: 210mm;
            height: 297mm;
            text-align: center;
            border-radius: 10px;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
            position: relative;
        }
        .title {
            font-size: 2em;
            font-weight: 600;
            color: #333;
        }
        .sub-title {
            font-size: 1.2em;
            color: #555;
        }
        .year {
            background: #808000;
            color: white;
            padding: 8px 20px;
            font-weight: bold;
            display: inline-block;
            border-radius: 20px;
            margin-top: 10px;
        }
        .faculty {
            font-size: 1.5em;
            font-weight: bold;
            color: #555;
            margin-top: 15px;
            margin-bottom: 100px;
        }
        .circle-image {
            width: 300px;
            height: 300px;
            border-radius: 50%;
            border: 10px solid #808000;
            overflow: hidden;
            margin: 20px auto;
            margin-bottom: 250px;
        }
        .circle-image img {
            width: 250%;
            height: 250%;
            object-fit: cover;
        }
        .footer {
            position: absolute;
            bottom: 20px;
            left: 0;
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: flex-start;
            font-size: 0.9em;
            color: #444;
            padding: 20px 40px 20px 40px;
            box-sizing: border-box;
        }
        .logo {
            width: 60px;
            margin-right: 10px;
        }
        .footer-text {
            display: inline-block;
            margin-left: 10px;
            text-align: left;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="title">LAPORAN RTM</div>
        <div class="sub-title">RAPAT TINJAUAN MANAJEMEN</div>
        <div class="year">TAHUN {{ $tahun }}</div>
        @if ($fakultas)
            <div class="faculty">Fakultas {{ $fakultas }}</div>
        @elseif ($unit)
            <div class="faculty">{{ $unit }}</div>
        @endif
        
        <div class="circle-image">
            <img src="{{ public_path('dist/img/unsoed.png') }}" alt="Cover Image">
        </div>

        
        <div class="footer">
            <div class="footer-text">
                <strong>PUSAT PENJAMINAN MUTU</strong><br>
                <strong>DAN PENGEMBANGAN PEMBELAJARAN (LPMPP) </strong><br>
                <strong>UNIVERSITAS JENDERAL SOEDIRMAN</strong>
            </div>
        </div>
    </div>
</body>
</html>
