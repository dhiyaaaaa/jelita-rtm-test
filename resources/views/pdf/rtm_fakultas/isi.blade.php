<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Temuan</title>
    <style>
        @page {
            size: A4 portrait;
            margin: 20mm;
        }

        body {
            font-family: "Times New Roman", serif;
            font-size: 14px;
            background-color: #fff;
            margin: 0;
            padding: 0;
            color: #333;
        }

        .container {
            width: 100%;
            padding: 20px;
            box-sizing: border-box;
        }

        h3, h4 {
            text-align: center;
            margin-bottom: 15px;
            font-weight: bold;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
            font-size: 14px;
            table-layout: fixed; /* Pastikan tabel tidak melebar */
        }

        .table th, .table td {
            padding: 8px;
            text-align: left;
            border: 1px solid #bfbfbf;
            word-wrap: break-word; 
            overflow-wrap: break-word;
            white-space: normal;
            vertical-align: top;
        }

        .table th {
            background-color: #f5f5f5;
            font-weight: bold;
            text-align: center;
        }

        .table td {
            vertical-align: top;
        }

        .tindak-lanjut {
            padding-left: 10px;
        }

        .tindak-lanjut ol {
            padding-left: 15px;
            margin: 5px 0;
        }

        .page-break {
            page-break-after: always;
        }

        /* Atur lebar kolom agar tidak terlalu lebar */
        .table th:nth-child(1), .table td:nth-child(1) {
            width: 5%;
            text-align: center;
        }

        .table th:nth-child(2), .table td:nth-child(2) {
            width: 20%;
        }

        .table th:nth-child(3), .table td:nth-child(3) {
            width: 15%;
        }

        .table th:nth-child(4), .table td:nth-child(4) {
            width: 15%;
        }

        .table th:nth-child(5), .table td:nth-child(5) {
            width: 25%;
        }

        .table th:nth-child(6), .table td:nth-child(6) {
            width: 20%;
        }

        /* Atur lebar kolom Tindak Lanjut agar tidak terlalu melebar */
        .table td:nth-child(5), .table td:nth-child(6) {
            max-width: 20%;
            word-wrap: break-word;
            overflow-wrap: break-word;
            word-break: break-word;
            white-space: normal;
        }

    </style>
</head>
<body>
    <div class="container">
        <h4>Daftar Temuan</h4>
        <table class="table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Kode & Instrumen</th>
                    <th>Kriteria</th>
                    <th>Jabatan</th>
                    <th>Catatan Auditor</th>
                    <th>Tindak Lanjut</th>
                </tr>
            </thead>
            <tbody>
                @php $no = 1; @endphp
                @foreach ($temuanFakultas as $item)
                    <tr>
                        <td class="text-center">{{ $no++ }}</td>
                        <td>{{ $item->form->instrumen->kode }} – {{ $item->form->instrumen->pernyataan }}</td>
                        <td>{{ $item->kriteria->nama ?? '-' }}</td>
                        <td>{{ $item->form->instrumen->jabatan->pluck('nama')->implode(', ') ?? '-' }}</td>
                        <td>{{ $item->catatan ?? '(Catatan auditor tidak tersedia)' }}</td>
                        <td class="tindak-lanjut">
                            <ol>
                                @foreach ($jawabanTindakLanjut->where('form_id', $item->form->id) as $tindak)
                                    <li>
                                        <strong>Tindakan:</strong> {{ $tindak->tindakan ?? '-' }}<br>
                                        <strong>PIC:</strong> {{ $tindak->pic ?? '-' }}<br>
                                        <strong>Waktu:</strong> {{ $tindak->waktu ?? '-' }}
                                    </li>
                                @endforeach
                            </ol>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="page-break"></div>

        @if($fakultas)
        <h4>Daftar Temuan Prodi</h4>
        <table class="table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Kode & Instrumen</th>
                    <th>Kriteria</th>
                    <th>Catatan Auditor</th>
                    <th>Tindak Lanjut</th>
                </tr>
            </thead>
            <tbody>
                @php $no = 1; @endphp
                @foreach ($temuanProdi as $item)
                    <tr>
                        <td class="text-center">{{ $no++ }}</td>
                        <td>{{ $item->form->instrumen->kode }} – {{ $item->form->instrumen->pernyataan }}</td>
                        <td>{{ $item->kriteria->nama ?? '-' }}</td>
                        <td>{{ $item->prodi->jenjang->nama }} {{ $item->prodi->nama }}: {{ $item->catatan ?? '(Tidak ada catatan)' }}</td>
                        <td class="tindak-lanjut">
                            <ol>
                                @foreach ($jawabanTindakLanjut->where('form_id', $item->form->id)->where('kriteria_id', $item->kriteria->id) as $tindak)
                                    <li>
                                        <strong>Tindakan:</strong> {!! nl2br(e(wordwrap($tindak->tindakan ?? '-', 50, "\n", true))) !!}<br>
                                        <strong>PIC:</strong> {{ $tindak->pic ?? '-' }}<br>
                                        <strong>Waktu:</strong> {{ $tindak->waktu ?? '-' }}
                                    </li>
                                @endforeach
                            </ol>
                        </td>                        
                    </tr>
                @endforeach
            </tbody>
        </table>
        @endif
    </div>
</body>
</html>
