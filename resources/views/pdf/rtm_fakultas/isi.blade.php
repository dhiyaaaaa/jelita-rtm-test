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
            font-size: 12px; 
            background-color: #fff;
            margin: 0;
            padding: 0;
            color: #333;
        }

        .container {
            width: 100%;
            padding: 10px;
            box-sizing: border-box;
        }

        h3, h4 {
            text-align: center;
            margin-bottom: 10px;
            font-weight: bold;
            font-size: 14px;
        }

        .table-wrapper {
            width: 100%;
            overflow-x: auto; 
            margin-bottom: 20px;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
            font-size: 12px;
            table-layout: fixed; 
        }

        .table th, .table td {
            padding: 5px; 
            text-align: left;
            border: 1px solid #bfbfbf;
            word-wrap: break-word;
            overflow-wrap: break-word;
            white-space: normal;
            vertical-align: top;
            min-width: 50px;
        }

        .table th {
            background-color: #f5f5f5;
            font-weight: bold;
            text-align: center;
        }
        .table-col-no { width: 5%; }
        .table-col-standar { width: 15%; }
        .table-col-kode { width: 20%; }
        .table-col-kriteria { width: 10%; }
        .table-col-catatan { width: 25%; }
        .table-col-tindak { width: 25%; }

        .tindak-lanjut ol {
            padding-left: 15px;
            margin: 3px 0; 
        }

        .tindak-lanjut li {
            margin-bottom: 3px; 
        }

        .page-break {
            page-break-after: always;
        }

        /* Untuk tabel dengan kolom berbeda */
        .table-kriteria .table-col-kode { width: 30%; }
        .table-kriteria .table-col-tindak { width: 30%; }
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
                <div class="table-wrapper">
                    <table class="table">
                        <thead>
                            <tr>
                                <th colspan="5" style="text-align: center; background-color:#f5f5f5; font-weight:bold; font-size: 1.1em;">
                                    Daftar Temuan dengan Kriteria {{ $kriteria }}
                                </th>
                            </tr>
                            <tr>
                                <th class="table-col-no">No</th>
                                <th class="table-col-standar">Standar</th>
                                <th class="table-col-kode">Kode & Instrumen</th>
                                <th class="table-col-catatan">Catatan Auditor</th>
                                <th class="table-col-tindak">Tindak Lanjut</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $no = 1; @endphp
                            @foreach ($grouped as $standarKey => $standarItems)
                                @php 
                                    $filteredItems = $standarItems->filter(function($item) use ($jawabanTindakLanjut) {
                                        return $jawabanTindakLanjut->where('form_id', $item->form->id)->isNotEmpty();
                                    });
                                    
                                    if($filteredItems->isEmpty()) continue;
                                    
                                    $rowspan = $filteredItems->groupBy('form.instrumen.kode')->count(); 
                                @endphp
                                @php $first = true; @endphp

                                @foreach ($filteredItems->groupBy('form.instrumen.kode') as $instrumen => $kelompok)
                                    <tr>
                                        @if ($first)
                                            <td class="table-col-no" rowspan="{{ $rowspan }}">{{ $no++ }}</td>
                                            <td class="table-col-standar" rowspan="{{ $rowspan }}">{{ $standarKey }}</td>
                                        @endif
                                        <td class="table-col-kode">{{ $kelompok->first()->form->instrumen->kode }} – {{ $kelompok->first()->form->instrumen->pernyataan }}</td>
                                        <td class="table-col-catatan">
                                            @if ($kelompok->first()->kriteria->slug === 'belum-memenuhi')
                                                @if ($kelompok->first()->form->ptk_form_deskripsi->isNotEmpty())
                                                    @foreach ($kelompok->first()->form->ptk_form_deskripsi as $deskripsi)
                                                        <p>{{ $deskripsi->deskripsi }}
                                                            ({{ $deskripsi->form->ptk_form->first()->kategori_temuan ?? '-' }})
                                                        </p>
                                                    @endforeach
                                                @elseif ($kelompok->first()->catatan)
                                                    <p>{{ $kelompok->first()->catatan }}</p>
                                                @else
                                                    <p>Tidak ada catatan</p>
                                                @endif
                                            @else
                                                @if ($kelompok->first()->form->laporan_form->isNotEmpty())
                                                    @foreach ($kelompok->first()->form->laporan_form as $kelebihan)
                                                        <p>{{ $kelebihan->kelebihan }}</p>
                                                    @endforeach
                                                @elseif ($kelompok->first()->catatan)
                                                    <p>{{ $kelompok->first()->catatan }}</p>
                                                @else
                                                    <p>Tidak ada catatan</p>
                                                @endif
                                            @endif
                                        </td>
                                        <td class="table-col-tindak tindak-lanjut">
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
                </div>
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
            <div class="table-wrapper">
                <table class="table table-kriteria">
                    <thead>
                        <tr>
                            <th colspan="5" style="text-align: center; background-color:#f5f5f5; font-weight:bold; font-size: 1.1em;">
                                Daftar Temuan Prodi
                            </th>
                        </tr>
                        <tr>
                            <th class="table-col-no">No</th>
                            <th class="table-col-kode">Kode & Instrumen</th>
                            <th class="table-col-kriteria">Kriteria</th>
                            <th class="table-col-catatan">Catatan Auditor</th>
                            <th class="table-col-tindak">Tindak Lanjut</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $no = 1; @endphp
                        @foreach ($grouped as $instrumenKey => $items)
                            @php 
                                $filteredItems = $items->filter(function($item) use ($jawabanTindakLanjut) {
                                    return $jawabanTindakLanjut
                                        ->where('form_id', $item->form_id)
                                        ->where('kriteria_id', $item->kriteria_id)
                                        ->isNotEmpty();
                                });
                                
                                if($filteredItems->isEmpty()) continue;
                                
                                $rowspan = $filteredItems->groupBy('kriteria.nama')->count(); 
                            @endphp
                            @php $first = true; @endphp
                            @foreach ($filteredItems->groupBy('kriteria.nama') as $kriteria => $kelompok)
                                <tr>
                                    @if ($first)
                                        <td class="table-col-no" rowspan="{{ $rowspan }}">{{ $no++ }}</td>
                                        <td class="table-col-kode" rowspan="{{ $rowspan }}">{{ $instrumenKey }}</td>
                                    @endif
                                    <td class="table-col-kriteria">{{ $kriteria ?? '-' }}</td>
                                    <td class="table-col-catatan">
                                        @foreach ($kelompok as $item)
                                            {{ $item->prodi->jenjang->nama }} {{ $item->prodi->nama }}:
                                            @if ($item->kriteria->nama === 'Belum Memenuhi')
                                                @if ($item->form->ptk_form_deskripsi->isNotEmpty())
                                                    @foreach ($item->form->ptk_form_deskripsi as $deskripsi)
                                                        <p>{{ $deskripsi->deskripsi }}
                                                            ({{ $deskripsi->form->ptk_form->first()->kategori_temuan ?? '-' }})
                                                        </p>
                                                    @endforeach
                                                @elseif ($item->catatan)
                                                    <p>{{ $item->catatan }}</p>
                                                @else
                                                    <p>Tidak ada catatan</p>
                                                @endif
                                            @elseif ($item->kriteria->nama === 'Memenuhi')
                                                @if ($item->catatan)
                                                    <p>{{ $item->catatan }}</p>
                                                @else
                                                    <p>Tidak ada catatan</p>
                                                @endif
                                            @elseif ($item->kriteria->nama === 'Melampaui')
                                                @if ($item->form->laporan_form->isNotEmpty())
                                                    @foreach ($item->form->laporan_form as $kelebihan)
                                                        <p>{{ $kelebihan->kelebihan }}</p>
                                                    @endforeach
                                                @elseif ($item->catatan)
                                                    <p>{{ $item->catatan }}</p>
                                                @else
                                                    <p>Tidak ada catatan</p>
                                                @endif
                                            @endif
                                            <br>
                                        @endforeach
                                    </td>
                                    <td class="table-col-tindak tindak-lanjut">
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
            </div>
        @endif
    </div>
</body>
</html>