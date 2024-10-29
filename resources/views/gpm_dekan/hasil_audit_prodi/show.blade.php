@extends('components.layout.main_layout')

@section('content')
    <div class="card card-dark">
        <div class="card-header">
            <h3 class="card-title title-size">{{ $title }}</h3>
        </div>
        <!-- /.card-header -->
        <div class="card-body">
            <div>
                <h5>Program Studi</h5>
                @if (Auth::user()->jabatan->isNotEmpty() && Auth::user()->jabatan->first()->slug === 'rektor')
                    <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary mb-3">Kembali</a>
                @else
                    <a href="{{ route('hasil_audit_prodi') }}" class="btn btn-outline-secondary mb-3">Kembali</a>
                @endif

                <table id="ps" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th class="text-center">No</th>
                            <th class="text-center">Prodi</th>
                            <th class="text-center">Auditan</th>
                            <th class="text-center">Auditor</th>
                            <th class="text-center">Status Audit Dokumen</th>
                            <th class="text-center">Status Audit Lapangan</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>


                        @foreach ($prodi as $item)
                            <tr>
                                {{-- No --}}
                                <td class="text-center align-middle">{{ $loop->iteration }}</td>

                                {{-- Prodi --}}
                                <td class="align-middle">{{ $item->nama }} {{ $item->jenjang->nama }}</td>

                                {{-- Auditee --}}
                                <td class="align-middle">
                                    @if ($item->auditee->isNotEmpty())
                                        @foreach ($item->auditee as $auditee)
                                            <p style="margin:0; padding:0;">{{ $loop->iteration }}.
                                                {{ $auditee->user->name }}
                                            </p>
                                        @endforeach
                                    @else
                                        -
                                    @endif
                                </td>

                                {{-- Auditor --}}
                                <td class="align-middle">
                                    @if ($item->auditee->isNotEmpty())
                                        @php
                                            $uniqueAuditors = collect();
                                            $noAuditorDisplayed = false;
                                        @endphp
                                        @foreach ($item->auditee as $auditee)
                                            @if ($auditee->auditor->isNotEmpty())
                                                @foreach ($auditee->auditor as $auditor)
                                                    @if (!$uniqueAuditors->contains('user_id', $auditor->user_id))
                                                        <p style="margin:0; padding:0;">{{ $loop->iteration }}.
                                                            {{ $auditor->user->name }}</p>
                                                        @php
                                                            $uniqueAuditors->push($auditor);
                                                        @endphp
                                                    @endif
                                                @endforeach
                                            @else
                                                @if (!$noAuditorDisplayed)
                                                    <div class="text-center">
                                                        <a class="btn disabled">Belum ada Auditor</a>
                                                    </div>
                                                    @php
                                                        $noAuditorDisplayed = true;
                                                    @endphp
                                                @endif
                                            @endif
                                        @endforeach
                                    @else
                                        <a class="btn disabled text-center">Belum ada Auditor</a>
                                    @endif
                                </td>

                                {{-- Status Audit Dokumen --}}
                                <td class="text-center align-middle">
                                    @php
                                        $status_audit_auditee = $item->status_audit_auditee->first();
                                        $status_audit_auditor = $item->status_audit_auditor->first();
                                    @endphp
                                    @if ($status_audit_auditee && $status_audit_auditor)
                                        @if ($status_audit_auditee->status === 'completed' && $status_audit_auditor->status === 'completed')
                                            <p class="list badge badge-success p-1" style="font-size: 14px;">Audit
                                                Dokumen selesai</p>
                                        @else
                                            <p class="list badge badge-warning p-1" style="font-size: 14px;">Auditan dan
                                                Auditor sedang mengisi audit</p>
                                        @endif
                                    @elseif ($status_audit_auditee)
                                        <p class="list badge badge-warning p-1" style="font-size: 14px;">Auditan sedang
                                            mengisi audit</p>
                                    @elseif ($status_audit_auditor)
                                        <p class="list badge badge-warning p-1" style="font-size: 14px;">Auditan dan
                                            Auditor sedang mengisi audit</p>
                                    @else
                                        <a class="btn disabled">Belum Mulai</a>
                                    @endif
                                </td>

                                {{-- Status Audit Lapangan --}}
                                <td class="text-center align-middle">
                                    @php
                                        $berita_acara = $item->berita_acara->first();
                                        $ptk = $item->ptk->first();
                                        $laporan = $item->laporan->first();

                                        // Status PTK dan Laporan
                                        $status_ptk_auditee =
                                            $ptk && $ptk->status_ptk_auditee ? $ptk->status_ptk_auditee->first() : null;
                                        $status_ptk_auditor =
                                            $ptk && $ptk->status_ptk_auditor ? $ptk->status_ptk_auditor->first() : null;
                                        $status_laporan =
                                            $laporan && $laporan->status_laporan
                                                ? $laporan->status_laporan->first()
                                                : null;

                                        $ptk_auditor_selesai =
                                            $status_ptk_auditor && $status_ptk_auditor->status === 'completed';
                                        $ptk_auditee_selesai =
                                            $status_ptk_auditee && $status_ptk_auditee->status === 'completed';
                                        $laporan_selesai = $status_laporan && $status_laporan->status === 'completed';
                                        $laporan_mulai = $status_laporan && $status_laporan->status !== null;

                                        $buat_ptk = $ptk && !$status_ptk_auditor && !$status_ptk_auditee;

                                        $auditor_mulai = $status_ptk_auditor && !$ptk_auditor_selesai;
                                        $auditee_mulai = $status_ptk_auditee && !$ptk_auditee_selesai;
                                    @endphp

                                    @if ($laporan_selesai)
                                        <p class="list badge badge-success p-1" style="font-size: 14px">Temuan Positif
                                            berhasil
                                            dibuat</p>
                                    @elseif ($ptk_auditor_selesai && $ptk_auditee_selesai && $laporan_mulai && !$laporan_selesai)
                                        <p class="list badge badge-warning p-1" style="font-size: 14px">Auditor sedang
                                            mengisi Temuan Positif</p>
                                    @elseif ($ptk_auditor_selesai && $ptk_auditee_selesai && !$laporan_mulai)
                                        <p class="list badge badge-warning p-1" style="font-size: 14px">Temuan Negatif
                                            selesai,
                                            menunggu pembuatan Temuan Positif</p>
                                    @elseif ($buat_ptk)
                                        <p class="list badge badge-warning p-1" style="font-size: 14px">Temuan Negatif
                                            berhasil
                                            dibuat</p>
                                    @elseif ($ptk_auditor_selesai && !$auditee_mulai)
                                        <p class="list badge badge-warning p-1" style="font-size: 14px">Auditor sudah
                                            mengisi Temuan Negatif, menunggu Auditan</p>
                                    @elseif ($auditor_mulai && !$auditee_mulai)
                                        <p class="list badge badge-warning p-1" style="font-size: 14px">Auditor sedang
                                            mengisi Temuan Negatif</p>
                                    @elseif ($auditee_mulai && !$auditor_mulai)
                                        <p class="list badge badge-warning p-1" style="font-size: 14px">Auditan sedang
                                            mengisi Temuan Negatif</p>
                                    @elseif ($auditor_mulai && $auditee_mulai)
                                        <p class="list badge badge-warning p-1" style="font-size: 14px">Auditan dan
                                            Auditor sedang mengisi Temuan Negatif</p>
                                    @elseif ($status_laporan && !$laporan_selesai)
                                        <p class="list badge badge-warning p-1" style="font-size: 14px">Auditor sedang
                                            mengisi Temuan Positif</p>
                                    @elseif ($berita_acara)
                                        <p class="list badge badge-warning p-1" style="font-size: 14px">Berita Acara
                                            berhasil dibuat</p>
                                    @else
                                        <a class="btn disabled">Belum Ada</a>
                                    @endif
                                </td>

                                {{-- Aksi --}}
                                <td class="text-center align-middle">
                                    <div class="d-flex justify-content-center align-items-center">
                                        {{-- Lihat --}}
                                        <div class="dropdown mr-2">
                                            <button class="btn btn-primary dropdown-toggle" type="button"
                                                id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true"
                                                aria-expanded="false">
                                                Lihat
                                            </button>
                                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                <a href="{{ route('hasil_audit_prodi.audit_dokumen', ['jadwalAudit' => $jadwalAudit->id, 'unit' => $item->id]) }}"
                                                    class="dropdown-item">Audit Dokumen</a>
                                                <a href="{{ route('hasil_audit_prodi.daftar_tilik', ['jadwalAudit' => $jadwalAudit->id, 'unit' => $item->id]) }}"
                                                    class="dropdown-item">Daftar Tilik</a>
                                                @if ($item->ptk->isNotEmpty())
                                                    <a href="{{ route('hasil_audit_prodi.ptk', $item->ptk->first()->id) }}"
                                                        class="dropdown-item">Temuan Negatif</a>
                                                @endif
                                                @if ($item->laporan->isNotEmpty())
                                                    <a href="{{ route('hasil_audit_prodi.laporan', $item->laporan->first()->id) }}"
                                                        class="dropdown-item">Temuan Positif</a>
                                                @endif
                                            </div>
                                        </div>

                                        {{-- Download --}}
                                        <div class="dropdown">
                                            <button class="btn btn-success dropdown-toggle" type="button"
                                                id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true"
                                                aria-expanded="false">
                                                Download
                                            </button>
                                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                {{-- Download Isian Audit Auditan --}}
                                                <form
                                                    action="{{ route('download.isi_audit_auditee', ['jadwalAudit' => $jadwalAudit->id, 'unit' => $item->id, 'type' => $item->type]) }}"
                                                    method="post" class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="dropdown-item">
                                                        Isian Audit Auditan
                                                    </button>
                                                </form>

                                                {{-- Download Daftar Tilik --}}
                                                <form
                                                    action="{{ route('download.daftar_tilik', ['jadwalAudit' => $jadwalAudit->id, 'unit' => $item->id, 'type' => $item->type]) }}"
                                                    method="post" class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="dropdown-item">
                                                        Daftar Tilik
                                                    </button>
                                                </form>

                                                {{-- Download Berita Acara --}}
                                                @if ($item->berita_acara->isNotEmpty())
                                                    <form
                                                        action="{{ route('download.berita-acara', $item->berita_acara->first()->id) }}"
                                                        method="post" class="d-inline">
                                                        @csrf
                                                        <button type="submit" class="dropdown-item">
                                                            Berita Acara
                                                        </button>
                                                    </form>
                                                @endif

                                                {{-- Download Temuan Negatif --}}
                                                @if ($item->ptk->isNotEmpty())
                                                    <form action="{{ route('download.ptk', $item->ptk->first()->id) }}"
                                                        method="post" class="d-inline">
                                                        @csrf
                                                        <button type="submit" class="dropdown-item">
                                                            Temuan Negatif
                                                        </button>
                                                    </form>
                                                @endif

                                                {{-- Download Temuan Positif --}}
                                                @if ($item->laporan->isNotEmpty())
                                                    <form
                                                        action="{{ route('download.laporan', $item->laporan->first()->id) }}"
                                                        method="post" class="d-inline">
                                                        @csrf
                                                        <button type="submit" class="dropdown-item">
                                                            Temuan Positif
                                                        </button>
                                                    </form>
                                                @endif

                                                {{-- Download Semua --}}
                                                {{-- <div class="dropdown-divider"></div>
                                                <form
                                                    action="{{ route('download.daftar_tilik', ['jadwalAudit' => $jadwalAudit->id, 'unit' => $item->id, 'type' => $item->type]) }}"
                                                    method="post" class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="dropdown-item">
                                                        All
                                                    </button>
                                                </form> --}}
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforeach

                    </tbody>
                </table>
            </div>
        </div>
        <!-- /.card-body -->
    </div>
@endsection


@section('style')
    <!-- DataTables -->
    <link rel="stylesheet" href="{{ asset('plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">

    <style>
        .list {
            margin: 0;
            padding: 0;
        }
    </style>
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
            $("#ps").DataTable({
                "responsive": true,
                "autoWidth": false,
                "pageLength": 25,
                "columnDefs": [{
                        "width": "5%",
                        "targets": [0]
                    }, {
                        "width": "10%",
                        "targets": [1]
                    }, {
                        "width": "15%",
                        "targets": [2]
                    },
                    {
                        "width": "15%",
                        "targets": [3]
                    },
                    {
                        "width": "15%",
                        "targets": [4]
                    },
                    {
                        "width": "15%",
                        "targets": [5]
                    },
                    {
                        "width": "25%",
                        "targets": [6]
                    },
                ]
            });
            $("#upps").DataTable({
                "responsive": true,
                "autoWidth": false,
                "pageLength": 25,
                "columnDefs": [{
                        "width": "5%",
                        "targets": [0]
                    }, {
                        "width": "10%",
                        "targets": [1]
                    }, {
                        "width": "15%",
                        "targets": [2]
                    },
                    {
                        "width": "15%",
                        "targets": [3]
                    },
                    {
                        "width": "15%",
                        "targets": [4]
                    },
                    {
                        "width": "15%",
                        "targets": [5]
                    },
                    {
                        "width": "25%",
                        "targets": [6]
                    },
                ]
            });
        });
    </script>
@endsection
