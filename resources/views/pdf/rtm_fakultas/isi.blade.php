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

        /* Atur lebar kolom yang konsisten untuk semua tabel */
        .table th:nth-child(1), .table td:nth-child(1) {
            width: 5%;
            text-align: center;
        }

        /* Kolom Standar (hanya ada di tabel Fakultas) */
        .table th:nth-child(2), .table td:nth-child(2) {
            width: 15%;
        }

        /* Kolom Kode & Instrumen */
        .table th:nth-child(3), .table td:nth-child(3) {
            width: 25%;
        }

        /* Kolom Kriteria (hanya ada di tabel Prodi) */
        .table-kriteria th:nth-child(3), .table-kriteria td:nth-child(3) {
            width: 10%;
        }

        /* Kolom Catatan Auditor */
        .table th:nth-child(4), .table td:nth-child(4) {
            width: 25%;
        }

        /* Kolom Tindak Lanjut */
        .table th:nth-child(5), .table td:nth-child(5) {
            width: 30%;
        }

    </style>
</head>
<body>
    <div class="container">
        @php 
            $kriteriaGroup = $temuanFakultas->groupBy(function($item) {
                return $item->kriteria->nama ?? '-';
            });
        @endphp

        @foreach($kriteriaGroup as $kriteria => $items)
            @php
                $grouped = $items->groupBy(function($item) {
                    return $item->form->instrumen->standar->nama;
                });
                $no = 1;
                
                $hasData = false;
                foreach ($grouped as $standarKey => $standarItems) {
                    if ($jawabanTindakLanjut->whereIn('form_id', $standarItems->pluck('form.id'))->isNotEmpty()) {
                        $hasData = true;
                        break;
                    }
                }
            @endphp

            @if($hasData)
                <table class="table">
                    <thead>
                        <tr>
                            <th colspan="5" style="text-align: center; background-color:#f5f5f5; font-weight:bold; font-size: 1.1em;">
                                Daftar Temuan dengan Kriteria {{ $kriteria }}
                            </th>
                        </tr>
                        <tr>
                            <th>No</th>
                            <th>Standar</th>
                            <th>Kode & Instrumen</th>
                            <th>Catatan Auditor</th>
                            <th>Tindak Lanjut</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $no = 1; @endphp
                        @foreach ($grouped as $standarKey => $standarItems)
                            @php 
                                // Filter kelompok yang memiliki tindak lanjut
                                $filteredItems = $standarItems->filter(function($item) use ($jawabanTindakLanjut) {
                                    return $jawabanTindakLanjut->where('form_id', $item->form->id)->isNotEmpty();
                                });
                                
                                // Skip jika tidak ada yang memiliki tindak lanjut
                                if($filteredItems->isEmpty()) continue;
                                
                                $rowspan = $filteredItems->groupBy('form.instrumen.kode')->count(); 
                            @endphp
                            @php 
                                $first = true;
                            @endphp

                            @foreach ($filteredItems->groupBy('form.instrumen.kode') as $instrumen => $kelompok)
                                <tr>
                                    @if ($first)
                                        <td rowspan="{{ $rowspan }}">{{ $no++ }}</td>
                                        <td rowspan="{{ $rowspan }}">{{ $standarKey }}</td>
                                    @endif
                                    <td>{{ $kelompok->first()->form->instrumen->kode }} – {{ $kelompok->first()->form->instrumen->pernyataan }}</td>
                                    <td>
                                        @if ($kelompok->first()->kriteria->nama === 'Belum Memenuhi')
                                            @if ($kelompok->first()->form->ptk_form_deskripsi->isNotEmpty())
                                                @foreach ($kelompok->first()->form->ptk_form_deskripsi as $deskripsi)
                                                    <p class="text-muted">{{ $deskripsi->deskripsi }}
                                                        ({{ $deskripsi->form->ptk_form->first()->kategori_temuan ?? '-' }})
                                                    </p>
                                                @endforeach
                                            @elseif ($kelompok->first()->catatan)
                                                <p class="text-muted">{{ $kelompok->first()->catatan }}</p>
                                            @else
                                                <p class="text-muted">Tidak ada catatan</p>
                                            @endif
                                        @elseif ($kelompok->first()->kriteria->nama === 'Memenuhi')
                                            @if ($kelompok->first()->catatan)
                                                <p class="text-muted">{{ $kelompok->first()->catatan }}</p>
                                            @else
                                                <p class="text-muted">Tidak ada catatan</p>
                                            @endif
                                        @elseif ($kelompok->first()->kriteria->nama === 'Melampaui')
                                            @if ($kelompok->first()->form->laporan_form->isNotEmpty())
                                                @foreach ($kelompok->first()->form->laporan_form as $kelebihan)
                                                    <p class="text-muted">{{ $kelebihan->kelebihan }}</p>
                                                @endforeach
                                            @elseif ($kelompok->first()->catatan)
                                                <p class="text-muted">{{ $kelompok->first()->catatan }}</p>
                                            @else
                                                <p class="text-muted">Tidak ada catatan</p>
                                            @endif
                                        @endif
                                    </td>
                                    <td class="tindak-lanjut">
                                        <ol>
                                            @foreach ($jawabanTindakLanjut->where('form_id', $kelompok->first()->form->id) as $tindak)
                                                <li>
                                                    <strong>Tindakan:</strong> {{ $tindak->tindakan ?? '-' }}<br>
                                                    <strong>PIC:</strong> {{ $tindak->jabatan->nama}}
                                                        @foreach ($tindak->user->prodi as $prodi)
                                                            {{ $prodi->nama }}
                                                        @endforeach

                                                        @foreach ($tindak->user->fakultas as $fakultas)
                                                            {{ $fakultas->nama }}
                                                        @endforeach<br>
                                                    <strong>Target waktu:</strong> {{ $tindak->waktu }}
                                                </li>
                                            @endforeach
                                        </ol>
                                    </td>
                                </tr>
                                @php $first = false; @endphp
                            @endforeach
                        @endforeach
                    </tbody>
                </table>
            @endif
        @endforeach
        <div class="page-break"></div>

            @if($fakultas)
            @php
                $grouped = $temuanProdi->groupBy(function($item) {
                    return $item->form->instrumen->kode . ' - ' . $item->form->instrumen->pernyataan;
                });
                $no = 1;
            @endphp
            <table class="table table-kriteria">
                <thead>
                    <tr>
                        <th colspan="5" style="text-align: center; background-color:#f5f5f5; font-weight:bold; font-size: 1.1em;">
                            Daftar Temuan Prodi
                        </th>
                    </tr>
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
                    @foreach ($grouped as $instrumenKey => $items)
                        @php 
                            // Filter items yang memiliki tindak lanjut
                            $filteredItems = $items->filter(function($item) use ($jawabanTindakLanjut) {
                                return $jawabanTindakLanjut
                                    ->where('form_id', $item->form_id)
                                    ->where('kriteria_id', $item->kriteria_id)
                                    ->isNotEmpty();
                            });
                            
                            // Skip jika tidak ada yang memiliki tindak lanjut
                            if($filteredItems->isEmpty()) continue;
                            
                            $rowspan = $filteredItems->groupBy('kriteria.nama')->count(); 
                        @endphp
                        @php $first = true; @endphp
                        @foreach ($filteredItems->groupBy('kriteria.nama') as $kriteria => $kelompok)
                            <tr>
                                @if ($first)
                                    <td rowspan="{{ $rowspan }}">{{ $no++ }}</td>
                                    <td rowspan="{{ $rowspan }}">{{ $instrumenKey }}</td>
                                @endif
                                <td>{{ $kriteria ?? '-' }}</td>
                                <td>
                                    @foreach ($kelompok as $item)
                                        {{ $item->prodi->jenjang->nama }} {{ $item->prodi->nama }}:
                                        @if ($item->kriteria->nama === 'Belum Memenuhi')
                                            @if ($item->form->ptk_form_deskripsi->isNotEmpty())
                                                @foreach ($item->form->ptk_form_deskripsi as $deskripsi)
                                                    <p class="text-muted">{{ $deskripsi->deskripsi }}
                                                        ({{ $deskripsi->form->ptk_form->first()->kategori_temuan ?? '-' }})
                                                    </p>
                                                @endforeach
                                            @elseif ($item->catatan)
                                                <p class="text-muted">{{ $item->catatan }}</p>
                                            @else
                                                <p class="text-muted">Tidak ada catatan</p>
                                            @endif
                                        @elseif ($item->kriteria->nama === 'Memenuhi')
                                            @if ($item->catatan)
                                                <p class="text-muted">{{ $item->catatan }}</p>
                                            @else
                                                <p class="text-muted">Tidak ada catatan</p>
                                            @endif
                                        @elseif ($item->kriteria->nama === 'Melampaui')
                                            @if ($item->form->laporan_form->isNotEmpty())
                                                @foreach ($item->form->laporan_form as $kelebihan)
                                                    <p class="text-muted">{{ $kelebihan->kelebihan }}</p>
                                                @endforeach
                                            @elseif ($item->catatan)
                                                <p class="text-muted">{{ $item->catatan }}</p>
                                            @else
                                                <p class="text-muted">Tidak ada catatan</p>
                                            @endif
                                        @endif
                                        <br>
                                    @endforeach
                                </td>
                                <td class="tindak-lanjut">
                                    <ol>
                                        @foreach ($jawabanTindakLanjut->where('form_id', $kelompok->first()->form_id)
                                                                        ->where('kriteria_id', $kelompok->first()->kriteria_id) as $tindak)
                                            <li>
                                                <strong>Tindakan:</strong> {{ $tindak->tindakan ?? '-' }}<br>
                                                <strong>PIC:</strong> {{ $tindak->jabatan->nama}}
                                                    @foreach ($tindak->user->prodi as $prodi)
                                                        {{ $prodi->nama }}
                                                    @endforeach

                                                    @foreach ($tindak->user->fakultas as $fakultas)
                                                        {{ $fakultas->nama }}
                                                    @endforeach<br>
                                                <strong>Target waktu:</strong> {{ $tindak->waktu }}
                                            </li>
                                        @endforeach
                                    </ol>
                                </td>
                            </tr>
                            @php $first = false; @endphp
                        @endforeach
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
</body>
</html>