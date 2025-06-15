<!DOCTYPE html>
<html lang="en">
<head>
    <style>
        .catatan-judul {
            text-align: center;
            font-weight: bold;
        }
        .catatan-isi {
            text-align: center;
        }
        .page-break {
            page-break-after: always;
            min-height: 100vh;
        }
    </style>
</head>
<body>
    @foreach ($rtmCatatan as $index => $catatan)
        <div class="{{ !$loop->last ? 'page-break' : '' }}">
            <p class="catatan-judul">{{ strip_tags($catatan->judul) }}</p>
            <p class="catatan-isi">{{ strip_tags($catatan->isi) }}</p>
        </div>
    @endforeach
</body>
</html>