<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>RTM</title>

        <style>
            /* General */
            body,
            div,
            p {
                margin: 0;
                padding: 0;
                font-family: Arial, sans-serif;
            }

            /* Cover */
            #cover {
                position: relative;
                width: 100%;
                height: 100%;
                background-image: url({{ public_path('dist/img/cover_with_footer.png') }});
                background-size: cover;
                background-position: center;
                background-repeat: no-repeat;
            }

            #title {
                position: absolute;
                top: 0;
                left: 0;
                z-index: 10;
                width: 470px;
                margin: 70px 0 0 100px;
            }

            .laporan {
                font-size: 50px;
                font-weight: bold;
            }

            .audit {
                font-size: 24px;
                font-weight: 600;
                margin: 10px 0;
            }

            .tahun {
                margin: 12px 0;
                background-color: #ba2552;
                color: white;
                width: 130px;
                padding: 10px;
                font-size: 20px;
                font-weight: bold;
            }

            .unit {
                font-size: 24px;
                font-weight: bold;
                margin: 15px 0;
            }

            #line {
                margin-top: 15px;
                width: 100%;
                height: 2px;
                background-color: #ba2552;
            }
        </style>

    <body>
        {{-- Cover --}}
        <div id="cover"></div>

        {{-- Title --}}
        <div id="title">
            <div>
                <p class="laporan">Laporan RTM</p>
            </div>
            <div>
                <p class="audit">{{ $audit }}</p>
            </div>
            <div style="text-align: center;">
                <p class="tahun">Tahun {{ $tahun }}</p>
            </div>
            <div id="line"></div>
            <div>
                @if($fakultas)
                    <p class="unit">Fakultas {{ $fakultas }}</p>
                @else
                    <p class="unit">{{ $unit }}</p>
                @endif
            </div>
        </div>
    </body>

</html>
