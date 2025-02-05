@php
    use Carbon\Carbon;
@endphp
@extends('components.layout.main_layout')

@section('content')
    <div class="card card-dark">
        <div class="card-header">
            <h3 class="card-title title-size">{{ $title }}</h3>
        </div>
        <!-- /.card-header -->
        <div class="card-body">
            <a href="{{ route('auditor.lapangan') }}" class="btn btn-outline-secondary mb-3">Kembali</a>

            {{-- Detail Jadwal --}}
            <table class="table mb-3">
                <tr>
                    <td class="text-bold" width="10%">Jadwal</td>
                    <td width="5%">:</td>
                    <td>Audit Lapangan {{ $jadwal->jadwal }}</td>
                </tr>
                <tr>
                    <td class="text-bold" width="10%">Periode</td>
                    <td width="5%">:</td>
                    <td>
                        {{ Carbon::parse($jadwal->tgl_mulai)->translatedFormat('j F Y') }} -
                        {{ Carbon::parse($jadwal->tgl_selesai)->translatedFormat('j F Y') }}
                    </td>
                </tr>
                <tr>
                    <td class="text-bold" width="10%">Status</td>
                    <td width="5%">:</td>
                    <td>
                        @if (!$jadwal->expired)
                            <span class="badge badge-success">Terbuka</span>
                        @else
                            <span class="badge badge-danger">Tertutup</span>
                        @endif
                    </td>
                </tr>
            </table>

            <table id="auditor" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th class="text-center">No</th>
                        <th class="text-center">Prodi/Fakultas/Unit</th>
                        <th class="text-center">Berita Acara</th>
                        <th class="text-center">Temuan Negatif</th>
                        <th class="text-center">Praktik Baik</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $no = 1;
                    @endphp
                    @foreach ($auditee as $item)
                        <tr>
                            {{-- No --}}
                            <td class="text-center">{{ $no++ }}</td>

                            {{-- Prodi/Fakultas/Unit --}}
                            <td>
                                @if ($item->type === 'prodi')
                                    {{ 'Program Studi ' . $item->nama . ' ' . $item->jenjang->nama }}
                                @elseif ($item->type === 'fakultas')
                                    {{ 'Fakultas ' . $item->nama }}
                                @else
                                    {{ $item->nama }}
                                @endif
                            </td>

                            {{-- Berita Acara --}}
                            <td class="text-center">
                                @if ($item->berita_acara->isNotEmpty())
                                    @php
                                        $berita_acara = $item->berita_acara->first();
                                    @endphp
                                    @if (!$expired)
                                        {{-- Edit Berita Acara --}}
                                        <div class="dropdown d-inline">
                                            <button class="btn btn-outline-warning dropdown-toggle" type="button"
                                                id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true"
                                                aria-expanded="false">
                                                Aksi
                                            </button>
                                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                <a class="dropdown-item"
                                                    href="{{ route('auditor.lapangan.berita_acara.edit', [
                                                        'beritaAcara' => $berita_acara->id,
                                                    ]) }}">Edit</a>
                                                <a href="{{ route('auditor.lapangan.berita_acara.delete', [
                                                    'beritaAcara' => $berita_acara->id,
                                                ]) }}"
                                                    class="dropdown-item" data-confirm-delete="true">Hapus</a>
                                            </div>
                                        </div>
                                    @endif

                                    @if (
                                        $berita_acara &&
                                            $berita_acara->auditor->isNotEmpty() &&
                                            optional($berita_acara->auditor->first()->pivot)->approve === false)
                                        @if (!$expired)
                                            <form
                                                action="{{ route('auditor.lapangan.berita_acara.approve', ['beritaAcara' => $berita_acara->id, 'auditor' => $auditor->id]) }}"
                                                method="post" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-outline-success">Approve</button>
                                            </form>
                                        @endif
                                    @elseif (
                                        $berita_acara &&
                                            $berita_acara->auditor->isNotEmpty() &&
                                            optional($berita_acara->auditor->first()->pivot)->approve === true)
                                        <button disabled="disabled" class="btn btn-secondary">Approved</button>
                                    @endif

                                    <form action="{{ route('download.berita-acara', $berita_acara->id) }}" method="post"
                                        class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-outline-dark">
                                            <i class="fa fa-download p-1"></i>
                                        </button>
                                    </form>
                                @else
                                    @if (!$expired)
                                        <a href="{{ route('auditor.lapangan.berita_acara.create', [
                                            'jadwalAudit' => $jadwal->id,
                                            'unit' => $item->id,
                                            'type' => $item->type,
                                        ]) }}"
                                            class="btn btn-outline-primary">+ Berita Acara</a>
                                    @else
                                        <a class="btn disabled">Tertutup</a>
                                    @endif
                                @endif
                            </td>

                            {{-- PTK --}}
                            <td class="text-center">
                                @if ($item->ptk->isNotEmpty())
                                    @php
                                        $ptk = $item->ptk->first();
                                    @endphp
                                    @if (!$expired)
                                        {{-- Isi PTK --}}
                                        @if ($ptk->status_ptk_auditor->isEmpty())
                                            <form action="{{ route('auditor.lapangan.ptk.isi_ptk', ['ptk' => $ptk->id]) }}"
                                                method="post" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-outline-info">Isi</button>
                                            </form>
                                        @else
                                            @php
                                                $status = $ptk->status_ptk_auditor->first();
                                            @endphp
                                            @if ($status->status === 'completed')
                                                <a href="{{ route('auditor.lapangan.ptk.form', $ptk->id) }}"
                                                    class="btn btn-info">Sudah Isi</a>
                                            @else
                                                <a href="{{ route('auditor.lapangan.ptk.form', $ptk->id) }}"
                                                    class="btn btn-outline-info">Isi</a>
                                            @endif
                                        @endif
                                        {{-- Edit PTK --}}
                                        <div class="dropdown d-inline">
                                            <button class="btn btn-outline-warning dropdown-toggle" type="button"
                                                id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true"
                                                aria-expanded="false">
                                                Aksi
                                            </button>
                                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                <a class="dropdown-item"
                                                    href="{{ route('auditor.lapangan.ptk.edit', [
                                                        'ptk' => $ptk->id,
                                                    ]) }}">Edit</a>
                                                <a href="{{ route('auditor.lapangan.ptk.delete', [
                                                    'ptk' => $ptk->id,
                                                ]) }}"
                                                    class="dropdown-item" data-confirm-delete="true">Hapus</a>
                                            </div>
                                        </div>
                                    @else
                                        <a href="{{ route('auditor.lapangan.ptk.form', $ptk->id) }}"
                                            class="btn btn-outline-primary">Lihat</a>
                                    @endif

                                    @if ($ptk && $ptk->auditor->isNotEmpty() && optional($ptk->auditor->first()->pivot)->approve === false)
                                        @if (!$expired)
                                            <form
                                                action="{{ route('auditor.lapangan.ptk.approve', ['ptk' => $ptk->id, 'auditor' => $auditor->id]) }}"
                                                method="post" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-outline-success">Approve</button>
                                            </form>
                                        @endif
                                    @elseif ($ptk && $ptk->auditor->isNotEmpty() && optional($ptk->auditor->first()->pivot)->approve === true)
                                        <button disabled="disabled" class="btn btn-secondary">Approved</button>
                                    @endif

                                    <form action="{{ route('download.ptk', $ptk->id) }}" method="post" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-outline-dark">
                                            <i class="fa fa-download p-1"></i>
                                        </button>
                                    </form>
                                @else
                                    @if (!$expired)
                                        <a href="{{ route('auditor.lapangan.ptk.create', [
                                            'jadwalAudit' => $jadwal->id,
                                            'unit' => $item->id,
                                            'type' => $item->type,
                                        ]) }}"
                                            class="btn btn-outline-primary">+ Temuan Negatif</a>
                                    @else
                                        <a class="btn disabled">Tertutup</a>
                                    @endif
                                @endif
                            </td>

                            {{-- Laporan --}}
                            <td class="text-center">
                                @if ($item->laporan->isNotEmpty())
                                    @php
                                        $laporan = $item->laporan->first();
                                    @endphp
                                    @if (!$expired)
                                        {{-- Isi Laporan --}}
                                        @if ($laporan->status_laporan->isEmpty())
                                            <form
                                                action="{{ route('auditor.lapangan.laporan.isi_laporan', ['laporan' => $laporan->id]) }}"
                                                method="post" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-outline-info">Isi</button>
                                            </form>
                                        @else
                                            @php
                                                $status = $laporan->status_laporan->first();
                                            @endphp
                                            @if ($status->status === 'completed')
                                                <a href="{{ route('auditor.lapangan.laporan.form', $laporan->id) }}"
                                                    class="btn btn-info">Sudah Isi</a>
                                            @else
                                                <a href="{{ route('auditor.lapangan.laporan.form', $laporan->id) }}"
                                                    class="btn btn-outline-info">Isi</a>
                                            @endif
                                        @endif

                                        {{-- Edit Laporan --}}
                                        <div class="dropdown d-inline">
                                            <button class="btn btn-outline-warning dropdown-toggle" type="button"
                                                id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true"
                                                aria-expanded="false">
                                                Aksi
                                            </button>
                                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                <a class="dropdown-item"
                                                    href="{{ route('auditor.lapangan.laporan.edit', [
                                                        'laporan' => $laporan->id,
                                                    ]) }}">Edit</a>
                                                <a href="{{ route('auditor.lapangan.laporan.delete', [
                                                    'laporan' => $laporan->id,
                                                ]) }}"
                                                    class="dropdown-item" data-confirm-delete="true">Hapus</a>
                                            </div>
                                        </div>
                                    @else
                                        <a href="{{ route('auditor.lapangan.laporan.form', $laporan->id) }}"
                                            class="btn btn-outline-primary">Lihat</a>
                                    @endif

                                    @if ($laporan && $laporan->auditor->isNotEmpty() && optional($laporan->auditor->first()->pivot)->approve === false)
                                        @if (!$expired)
                                            <form
                                                action="{{ route('auditor.lapangan.laporan.approve', ['laporan' => $laporan->id, 'auditor' => $auditor->id]) }}"
                                                method="post" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-outline-success">Approve</button>
                                            </form>
                                        @endif
                                    @elseif ($laporan && $laporan->auditor->isNotEmpty() && optional($laporan->auditor->first()->pivot)->approve === true)
                                        <button disabled="disabled" class="btn btn-secondary">Approved</button>
                                    @endif


                                    <form action="{{ route('download.laporan', $laporan->id) }}" method="post"
                                        class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-outline-dark">
                                            <i class="fa fa-download p-1"></i>
                                        </button>
                                    </form>
                                @else
                                    @if (!$expired)
                                        <a href="{{ route('auditor.lapangan.laporan.create', [
                                            'jadwalAudit' => $jadwal->id,
                                            'unit' => $item->id,
                                            'type' => $item->type,
                                        ]) }}"
                                            class="btn btn-outline-primary">+ Praktik Baik</a>
                                    @else
                                        <a class="btn disabled">Tertutup</a>
                                    @endif
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

        </div>
        <!-- /.card-body -->
    </div>
@endsection

@section('style')
    <!-- DataTables -->
    <link rel="stylesheet" href="{{ asset('plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
@endsection

@section('script')
    <!-- DataTables & Plugins -->
    <script src="{{ asset('plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>



    <!-- Page specific script -->
    <script>
        $(function() {
            $("#auditor").DataTable({
                "responsive": true,
                "autoWidth": false,
                "columnDefs": [{
                        "width": "5%",
                        "targets": [0]
                    },
                    {
                        "width": "15%",
                        "targets": [1]
                    },
                    {
                        "width": "20%",
                        "targets": [2]
                    },
                    {
                        "width": "30%",
                        "targets": [3]
                    },
                    {
                        "width": "30%",
                        "targets": [4]
                    },
                ]
            });
        })
    </script>
@endsection
