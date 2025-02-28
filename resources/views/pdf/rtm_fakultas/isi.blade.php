<!DOCTYPE html>
<html lang="en">
<head>
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
            margin-bottom: 0; /* Hilangkan margin bawah */
        }

        .table th, .table td {
            padding: 10px;
            text-align: left;
            border: 1px solid #bfbfbf;
            word-wrap: break-word;
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
            padding-left: 15px;
        }

        .tindak-lanjut ol {
            padding-left: 15px;
            margin: 5px 0;
        }

        .page-break {
            page-break-after: always;
        }

        /* Atur lebar kolom */
        .table th:nth-child(1), .table td:nth-child(1) {
            width: 5%; /* No */
        }

        .table th:nth-child(2), .table td:nth-child(2) {
            width: 25%; /* Kode & Instrumen */
        }

        .table th:nth-child(3), .table td:nth-child(3) {
            width: 15%; /* Kriteria */
        }

        .table th:nth-child(4), .table td:nth-child(4) {
            width: 15%; /* Jabatan */
        }

        .table th:nth-child(5), .table td:nth-child(5) {
            width: 20%; /* Catatan Auditor */
        }

        .table th:nth-child(6), .table td:nth-child(6) {
            width: 20%; /* Tindak Lanjut */
        }
    </style>
</head>
<body>
    <div class="container">

        <!-- Daftar Temuan Fakultas -->
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
                                @foreach ($jawabanTindakLanjut->where('form_id', $item->form->id) as $index => $tindak)
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

        <!-- Daftar Temuan Prodi -->
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
                                @foreach ($jawabanTindakLanjut->where('form_id', $item->form->id)->where('kriteria_id', $item->kriteria->id) as $index => $tindak)
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
        @endif
    </div>
</body>
</html>