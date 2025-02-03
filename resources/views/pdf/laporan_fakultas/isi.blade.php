<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>Laporan Audit</title>

        <style>
            /* General */
            p {
                margin: 0;
                padding: 0;
            }

            /* Kop */
            #kop-container {
                display: grid;
                grid-template-columns: 90px 1fr 1fr;
                gap: 10px;
                width: 100%;
            }

            #kop {
                width: 100%;
                border-spacing: 0;
                border: 0.1px solid black;
            }

            #kop td {
                padding: 5px;
                text-align: center;
                border: 0.1px solid black;
            }

            #kop td.bold {
                font-weight: bold;
            }

            #kop td.colspan {
                grid-column: span 3;
                text-align: center;
                font-weight: bold;
            }

            /* Deskripsi */
            #desc-container {
                margin: 20px 0 0 0;
                width: 50%;
            }

            #desc {
                width: 100%;
                border-spacing: 0;
                border: 0.1px solid black;
            }

            #desc .label {
                background-color: #c0d4ec;
                font-weight: bold;
            }

            #desc td {
                padding: 5px;
                border: 0.1px solid black;
            }

            /* Isi */
            #isi-container {
                margin: 20px 0 0 0;
                width: 100%;
            }

            #isi {
                width: 100%;
                border-spacing: 0;
                border: 0.1px solid black;
                table-layout: fixed;
            }

            #isi thead tr {
                background-color: #c0d4ec;
            }

            #isi thead th {
                padding: 5px;
                border: 0.1px solid black;
            }

            #isi tbody tr td {
                padding: 5px;
                border: 0.1px solid black;
                word-break: break-word;
                overflow: hidden;
                text-overflow: ellipsis;
                white-space: normal;
                font-size: 14px;
            }
        </style>
    </head>

    <body style="font-family: Arial, Helvetica, sans-serif; font-size: 14px">
        {{-- {{ dd($prodis) }} --}}
        @foreach ($faks as $fak)
            <div id="container">
                {{-- Kop --}}
                <div id="kop-container">
                    <!-- Table for the content -->
                    <table id="kop" style="overflow: wrap" autosize="1">
                        <tbody>
                            <tr>
                                <td>
                                    <img src="{{ public_path('dist/img/logo_unsoed.png') }}" alt="Logo"
                                        style="width:80px; height:80px;">
                                </td>
                                <td class="bold" style="font-size: 20px">UNIVERSITAS JENDERAL SOEDIRMAN</td>
                                <td>No : KKA-SA-2024 <br> Tgl pemberlakuan: 22 Juli 2024</td>
                            </tr>
                            <tr>
                                <td colspan="3" class="colspan" style="padding: 15px 0 15px 0">LAPORAN HASIL AUDIT
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                {{-- Deskripsi --}}
                <div id="desc-container">
                    <table id="desc" style="overflow: wrap" autosize="1">
                        <tbody>
                            <tr>
                                <td width="150px" class="label">Tanggal Audit</td>
                                <td>{{ \Carbon\Carbon::parse($fak->tgl_audit)->isoFormat('D MMMM YYYY') ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td width="150px" class="label">Prodi/Fakultas/Unit</td>
                                <td>Fakultas {{ $fak->nama }}</td>
                            </tr>
                            <tr>
                                <td width="150px" class="label">Auditan</td>
                                <td>
                                    @php
                                        if ($fak->auditan) {
                                            $fak->auditan = explode('|', $fak->auditan);
                                        }
                                    @endphp
                                    @if (is_array($fak->auditan) && count($fak->auditan) > 0)
                                        @foreach ($fak->auditan as $index => $auditan)
                                            <p>{{ $index + 1 }}. {{ $auditan }}</p>
                                        @endforeach
                                    @else
                                        <span>-</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td width="150px" class="label">Auditor</td>
                                <td>
                                    @php
                                        if ($fak->auditor) {
                                            $fak->auditor = explode('|', $fak->auditor);
                                        }
                                    @endphp
                                    @if (is_array($fak->auditor) && count($fak->auditor) > 0)
                                        @foreach ($fak->auditor as $index => $auditor)
                                            <p>{{ $index + 1 }}. {{ $auditor }}</p>
                                        @endforeach
                                    @else
                                        <span>-</span>
                                    @endif
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                {{-- Isi --}}
                <div id="isi-container">
                    <table id="isi" style="overflow: wrap" autosize="1">
                        <thead>
                            <tr>
                                <th width="5%">No</th>
                                <th width="20%">Pernyataan</th>
                                <th width="20%">Jawaban</th>
                                <th width="20%">Catatan Auditor</th>
                                <th width="15%">Kategori Temuan</th>
                                <th width="20%">Analisis Penyebab</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($fak->instrumen as $item)
                                <tr style="font-size: 14px;">
                                    <td style="text-align: center">{{ $loop->iteration }}</td>
                                    <td>{{ $item->pernyataan }}</td>
                                    <td>{{ $item->jawaban }}</td>
                                    <td>{{ $item->catatan }}</td>
                                    <td style="text-align: center;">
                                        @if ($item->kategori_temuan == 'observasi')
                                            <span>Observasi</span>
                                        @elseif($item->kategori_temuan == 'mayor')
                                            <span>KTS Mayor</span>
                                        @elseif($item->kategori_temuan == 'minor')
                                            <span>KTS Minor</span>
                                        @endif
                                    </td>
                                    <td>{{ $item->analisis }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" style="text-align: center">Belum ada isi</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

            </div>

            {{-- Pagebreak --}}
            <div style="page-break-after: always;"></div>
        @endforeach
        @foreach ($prodis as $prodi)
            <div id="container">
                {{-- Kop --}}
                <div id="kop-container">
                    <!-- Table for the content -->
                    <table id="kop" style="overflow: wrap" autosize="1">
                        <tbody>
                            <tr>
                                <td>
                                    <img src="{{ public_path('dist/img/logo_unsoed.png') }}" alt="Logo"
                                        style="width:80px; height:80px;">
                                </td>
                                <td class="bold" style="font-size: 20px">UNIVERSITAS JENDERAL SOEDIRMAN</td>
                                <td>No : KKA-SA-2024 <br> Tgl pemberlakuan: 22 Juli 2024</td>
                            </tr>
                            <tr>
                                <td colspan="3" class="colspan" style="padding: 15px 0 15px 0">LAPORAN HASIL AUDIT
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                {{-- Deskripsi --}}
                <div id="desc-container">
                    <table id="desc" style="overflow: wrap" autosize="1">
                        <tbody>
                            <tr>
                                <td width="150px" class="label">Tanggal Audit</td>
                                <td>{{ \Carbon\Carbon::parse($prodi->tgl_audit)->isoFormat('D MMMM YYYY') ?? '-' }}
                                </td>
                            </tr>
                            <tr>
                                <td width="150px" class="label">Prodi/Fakultas/Unit</td>
                                <td>Program Studi {{ $prodi->nama }} {{ $prodi->jenjang }}</td>
                            </tr>
                            <tr>
                                <td width="150px" class="label">Auditan</td>
                                <td>
                                    @php
                                        if ($prodi->auditan) {
                                            $prodi->auditan = explode('|', $prodi->auditan);
                                        }
                                    @endphp
                                    @if (is_array($prodi->auditan) && count($prodi->auditan) > 0)
                                        @foreach ($prodi->auditan as $index => $auditan)
                                            <p>{{ $index + 1 }}. {{ $auditan }}</p>
                                        @endforeach
                                    @else
                                        <span>-</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td width="150px" class="label">Auditor</td>
                                <td>
                                    @php
                                        if ($prodi->auditor) {
                                            $prodi->auditor = explode('|', $prodi->auditor);
                                        }
                                    @endphp
                                    @if (is_array($prodi->auditor) && count($prodi->auditor) > 0)
                                        @foreach ($prodi->auditor as $index => $auditor)
                                            <p>{{ $index + 1 }}. {{ $auditor }}</p>
                                        @endforeach
                                    @else
                                        <span>-</span>
                                    @endif
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                {{-- Isi --}}
                <div id="isi-container">
                    <table id="isi" style="overflow: wrap" autosize="1">
                        <thead>
                            <tr>
                                <th width="5%">No</th>
                                <th width="20%">Pernyataan</th>
                                <th width="20%">Jawaban</th>
                                <th width="20%">Catatan Auditor</th>
                                <th width="15%">Kategori Temuan</th>
                                <th width="20%">Analisis Penyebab</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($prodi->instrumen as $item)
                                <tr style="font-size: 14px;">
                                    <td style="text-align: center">{{ $loop->iteration }}</td>
                                    <td>{{ $item->pernyataan }}</td>
                                    <td>{{ $item->jawaban }}</td>
                                    <td>{{ $item->catatan }}</td>
                                    <td style="text-align: center;">
                                        @if ($item->kategori_temuan == 'observasi')
                                            <span>Observasi</span>
                                        @elseif($item->kategori_temuan == 'mayor')
                                            <span>KTS Mayor</span>
                                        @elseif($item->kategori_temuan == 'minor')
                                            <span>KTS Minor</span>
                                        @endif
                                    </td>
                                    <td>{{ $item->analisis }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" style="text-align: center">Belum ada isi</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

            </div>

            {{-- Pagebreak --}}
            <div style="page-break-after: always;"></div>
        @endforeach
    </body>

</html>
