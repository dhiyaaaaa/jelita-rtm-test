<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Catatan RTM</title>
    <style>
        @font-face {
            font-family: 'Times New Roman';
            src: local('Times New Roman');
            font-weight: normal;
            font-style: normal;
        }
        
        @page {
            size: A4 portrait;
            margin: 20mm;
        }

        body {
            font-family: "Times New Roman", "Times", serif;
            font-size: 12px;
            background-color: #fff;
            margin: 0;
            padding: 0;
            color: #333;
        }

        .page-break {
            page-break-after: always;
            min-height: 100vh;
        }

        .catatan-container {
            width: 100%;
            padding: 20px;
            box-sizing: border-box;
            font-family: "Times New Roman", "Times", serif;
        }

        .catatan-judul {
            text-align: center;
            font-weight: bold;
            font-size: 14px;
            margin-bottom: 15px;
            padding-top: 10px;
            font-family: "Times New Roman", "Times", serif;
        }

        .catatan-isi {
            text-align: center;
            font-size: 12px;
            line-height: 1.5;
            margin: 0 auto;
            max-width: 80%;
            font-family: "Times New Roman", "Times", serif;
        }
    </style>
</head>
<body>
    @foreach ($rtmCatatan as $index => $catatan)
        <div class="catatan-container {{ !$loop->last ? 'page-break' : '' }}">
            <p class="catatan-judul">{{ strip_tags($catatan->judul) }}</p>
            <p class="catatan-isi">{{ strip_tags($catatan->isi) }}</p>
        </div>
    @endforeach
</body>
</html>