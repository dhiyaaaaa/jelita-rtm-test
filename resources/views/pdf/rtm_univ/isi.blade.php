<!DOCTYPE html>

<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Laporan RTM</title>
  <style>
    body {
      padding: 16px;
      font-family: 'Times New Roman', serif;
      font-size: 12px;
    }
    table {
      width: 100%;
      border-collapse: collapse;
      border: 1px solid black;
      margin-bottom: 24px;
    }
    th, td {
      border: 1px solid black;
      padding: 4px 8px;
      vertical-align: middle;
    }
    th {
      font-weight: bold;
      text-align: center;
    }
    .header-logo {
      width: 60px;
      height: 60px;
      display: block;
      margin: 0 auto;
    }
    .header-title {
      font-weight: bold;
      font-size: 16px;
      text-align: center;
      vertical-align: middle;
    }
    .header-no {
      text-align: right;
      font-size: 12px;
      vertical-align: middle;
      padding-right: 8px;
    }
    .subheader {
      font-weight: bold;
      text-align: center;
      font-size: 14px;
      padding: 8px 0;
    }
    .bold-center {
      font-weight: bold;
      text-align: center;
    }
    .text-center {
      text-align: center;
    }
    .text-right {
      text-align: right;
    }
    .text-left {
      text-align: left;
    }
    .small-text {
      font-size: 12px;
    }
    .barcode-small {
        width: 40px;
        height: 40px;
        object-fit: contain;
    }
    .nowrap {
      white-space: nowrap;
    }
    .page-break {
        page-break-after: always;
    }
  </style>
</head>
<body>

@foreach ($rtmJadwal->rtm_rtl_univ as $rtlUniv)
    @php
        $jawabanRencana = $rtlUniv->rtm_rtl_form; 
        $approvalUpps = $approvalsUpps[$rtlUniv->id] ?? null;
        $barcodeUppsPath = $barcodeUppsPaths[$rtlUniv->id] ?? null;
    @endphp

    {{-- Header --}}
    <table>
        <thead>
        <tr>
            <th style="width: 60px;">
            <img src="{{ public_path('dist/img/logo_unsoed.png') }}" width="80px" class="header-logo" />
            </th>
            <th class="header-title">UNIVERSITAS JENDERAL SOEDIRMAN</th>
            <th class="header-no">No : KKA-SA-2020</th>
        </tr>
        <tr>
            <th colspan="3" class="subheader">
            BORANG AUDIT MUTU INTERNAL<br />
            PERMINTAAN TINDAKAN KOREKSI
            </th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td colspan="2" class="bold-center text-center nowrap"> UPPS-FAKULTAS: 
                @if ($rtlUniv->fakultas)
                    Fakultas {{ $rtlUniv->fakultas->nama }}
                @elseif ($rtlUniv->unit)
                    {{ $rtlUniv->unit->nama }}
                @else
                    -
                @endif
            </td>
            <td class="bold-center nowrap">Kriteria</td>
        </tr>
        <tr>
            <td colspan="2" class="bold-center text-center nowrap"></td>
            <td class="bold-center nowrap">Standar Mutu Akademik Unsoed</td>
        </tr>
        <tr>
            <td class="bold-center text-center nowrap">Tempat</td>
            <td class="bold-center text-center nowrap">Ruang Lingkup</td>
            <td class="bold-center text-center nowrap">Periode Audit</td>
        </tr>
        <tr>
            <td class="text-center">Purwokerto</td>
            <td class="text-center">Akademik</td>
            <td class="text-center">{{ $rtlUniv->jadwal_audit->jadwal }}</td>
        </tr>
        <tr>
            <td class="bold-center text-center nowrap">Pimpinan UPPS</td>
            <td class="bold-center text-center nowrap">REKTOR</td>
            <td></td>
        </tr>
        <tr>
            <td class="text-center">
                @if($approvalUpps && $approvalUpps->user)
                    {{ $approvalUpps->user->name }}
                @else
                    -
                @endif
            </td>
            <td class="text-center">
                @if($approvalRektor && $approvalRektor->user)
                    {{ $approvalRektor->user->name }}
                @else
                    -
                @endif
            </td>
            <td></td>
        </tr>
        </tbody>
    </table>

    <table>
        <tbody>
            @php 
                $standars = $jawabanRencana
                    ->pluck('form.instrumen.standar')
                    ->filter()
                    ->unique('id');
            @endphp
        <tr>
            <td class="bold-left nowrap" style="width: 140px;">Kriteria</td>
            <td>
                <ol> 
                    @foreach ($standars as $standar)
                        <li> {{ $standar->nama }} </li>
                    @endforeach
                </ol>
            </td>
        </tr>
        <tr>
            <td class="bold-left nowrap">Deskripsi Temuan</td>
            <td>
                <ol>
                    @foreach ($jawabanRencana as $jawaban)
                        @php
                            $form = $jawaban->form;
                            $instrumen = $form?->instrumen;
                            $deskripsiList = $form?->ptk_form_deskripsi->pluck('deskripsi')->filter()->all();
                            $catatanAuditor = $form?->jawaban_auditor->pluck('catatan')->filter()->all();
                            $kelebihan = $form?->laporan_form->pluck('kelebihan')->filter()->all();
                        @endphp
                        @if ($instrumen)
                            <li>
                                <strong>{{ $instrumen->kode ?? '-' }} – {{ $instrumen->pernyataan ?? '-' }}</strong>
                                @if (count($deskripsiList))
                                    <ul>
                                        @foreach ($deskripsiList as $deskripsi)
                                            <li>{{ strip_tags($deskripsi) }}</li>
                                        @endforeach
                                    </ul>
                                @elseif($kelebihan)
                                    <div><em>Catatan Auditor:</em> {{ $kelebihan }}</div>
                                @else
                                @elseif($catatanAuditor)
                                    <div><em>Catatan Auditor:</em> {{ $catatanAuditor }}</div>
                                @else
                                    <div><em>Tidak ada deskripsi atau catatan auditor.</em></div>
                                @endif
                            </li>
                        @endif
                    @endforeach
                </ol>
            </td>
        </tr>
        <tr>
            @php
                $rekomendasiList = $jawabanRencana->pluck('rekomendasi')->filter()->values();
            @endphp
            <td class="bold-left nowrap">Rekomendasi</td>
            <td>
                @if ($rekomendasiList->count())
                    <ol>
                        @foreach ($rekomendasiList as $rekom)
                           <li>{{ strip_tags($rekom) }}</li>
                        @endforeach
                    </ol>
                @else
                    <em>Tidak ada rekomendasi.</em>
                @endif
            </td>
        </tr>
        <tr>
            @php
                $koreksiList = $jawabanRencana->pluck('koreksi')->filter()->values();
            @endphp
            <td class="bold-left nowrap">Permintaan<br />Tindakan Koreksi<br />(PTK)</td>
            <td>
                @if ($koreksiList->count())
                    <ol>
                        @foreach ($koreksiList as $koreksi)
                            <li>{{ strip_tags($koreksi) }}</li>
                        @endforeach
                    </ol>
                @else
                    <em>Tidak ada permintaan tindakan koreksi.</em>
                @endif
            </td>
        </tr>
        </tbody>
    </table>

    <table>
        <thead>
        <tr>
            <th colspan="6" class="bold-center">Persetujuan</th>
        </tr>
        <tr>
            <th colspan="3" class="bold-center">Diserahkan Oleh</th>
            <th colspan="3" class="bold-center">Diterima Oleh</th>
        </tr>
        <tr>
            <td>Rektor</td>
            <td class="text-center">
                @if($approvalRektor && $approvalRektor->user)
                    {{ $approvalRektor->user->name }}
                @else
                    -
                @endif
            </td>
            <td class="text-center">
                @if($barcodeRektorPath)
                    <img src="{{ $barcodeRektorPath }}" class="barcode-small" alt="Barcode Approval Rektor" style="width: 80px; height: 80px;">
                @else
                    -
                @endif
            </td>
            <td class="text-center">Pimpinan UPPS</td>
            <td class="text-center">
                @if($approvalUpps && $approvalUpps->user)
                    {{ $approvalUpps->user->name }}
                @else
                    -
                @endif
            </td>
            <td class="text-center">
                @if($barcodeUppsPath)
                    <img src="{{ $barcodeUppsPath }}" class="barcode-small" alt="Barcode Approval Ketua UPPS" style="width: 80px; height: 80px;">
                @else
                    -
                @endif
            </td>
        </tr>
        <tr>
            <td class="bold-left nowrap">Tanggal</td>
            <td colspan="4" class="text-center">
                @if($approvalRektor)
                    {{ \Carbon\Carbon::parse($approvalRektor->updated_at)->translatedFormat('l, j F Y') }}
                @elseif($approvalUpps)
                    {{ \Carbon\Carbon::parse($approvalUpps->updated_at)->translatedFormat('l, j F Y') }}
                @endif
            </td>
        </tr>
        </tbody>
    </table>
    @if (!$loop->last)
        <div class="page-break"></div>
    @endif
@endforeach
</body>
</html>