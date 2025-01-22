@extends('components.layout.main_layout')

@section('content')
    <div class="card card-dark">
        <div class="card-header">
            <h3 class="card-title title-size">{{ $title }}</h3>
        </div>
        <!-- /.card-header -->
        <div class="card-body">
            <div>
                {{-- Button Kembali --}}
                <div>
                    @if (Auth::user()->jabatan->isNotEmpty() && Auth::user()->jabatan->first()->slug === 'rektor')
                        <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary mb-3">Kembali</a>
                    @else
                        <a href="{{ route('hasil_audit_prodi') }}" class="btn btn-outline-secondary mb-3">Kembali</a>
                    @endif
                </div>

                <table id="ps" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th class="text-center">No</th>
                            <th class="text-center">Program Studi</th>
                            <th class="text-center">Auditan</th>
                            <th class="text-center">Auditor</th>
                            <th class="text-center">Status Audit Dokumen</th>
                            <th class="text-center">Status Audit Lapangan</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($prodi as $item)
                            <tr>
                                {{-- No --}}
                                <td class="text-center align-middle">{{ $loop->iteration }}</td>

                                {{-- Prodi --}}
                                <td class="align-middle">{{ $item->nama }} {{ $item->jenjang }}</td>

                                {{-- Auditee --}}
                                <td class="align-middle">
                                    @php
                                        if ($item->auditees) {
                                            $item->auditees = explode('|', $item->auditees);
                                        }
                                    @endphp

                                    @if (is_array($item->auditees) && count($item->auditees) > 0)
                                        @foreach ($item->auditees as $index => $auditee)
                                            <p style="margin:0; padding:0;">{{ $index + 1 }}. {{ $auditee }}</p>
                                        @endforeach
                                    @else
                                        <div class="text-center">
                                            <a class="btn disabled">-</a>
                                        </div>
                                    @endif
                                </td>

                                {{-- Auditor --}}
                                <td class="align-middle">
                                    @php
                                        if ($item->auditors) {
                                            $item->auditors = explode('|', $item->auditors);
                                        }
                                    @endphp

                                    @if (is_array($item->auditors) && count($item->auditors) > 0)
                                        @foreach ($item->auditors as $index => $auditor)
                                            <p style="margin:0; padding:0;">{{ $index + 1 }}.
                                                {{ $auditor }}
                                            </p>
                                        @endforeach
                                    @else
                                        <div class="text-center">
                                            <a class="btn disabled">Belum ada Auditor</a>
                                        </div>
                                    @endif
                                </td>

                                {{-- Status Audit Dokumen --}}
                                <td class="text-center align-middle">
                                    @if ($item->status_audit_auditee && $item->status_audit_auditor)
                                        @if ($item->status_audit_auditee === 'completed' && $item->status_audit_auditor === 'completed')
                                            <p class="list badge badge-success p-1" style="font-size: 14px;">Audit
                                                Dokumen selesai</p>
                                        @else
                                            <p class="list badge badge-warning p-1" style="font-size: 14px;">Auditan sedang
                                                mengisi</p>
                                            <p class="list badge badge-warning p-1" style="font-size: 14px;">Auditor sedang
                                                mengisi</p>
                                        @endif
                                    @elseif ($item->status_audit_auditee)
                                        @if ($item->status_audit_auditee === 'completed')
                                            <p class="list badge badge-success p-1" style="font-size: 14px;">Auditan
                                                sudah mengisi</p>
                                        @else
                                            <p class="list badge badge-warning p-1" style="font-size: 14px;">Auditan sedang
                                                mengisi</p>
                                        @endif
                                    @elseif ($item->status_audit_auditor)
                                        <p class="list badge badge-warning p-1" style="font-size: 14px;">Auditor sedang
                                            mengisi</p>
                                    @else
                                        <a class="btn disabled">Belum Mulai</a>
                                    @endif
                                </td>

                                {{-- Status Audit Lapangan --}}
                                <td class="text-center align-middle">
                                    @php
                                        $status_ptk_auditee = $item->status_ptk_auditee;
                                        $status_ptk_auditor = $item->status_ptk_auditor;
                                        $status_laporan = $item->status_laporan;

                                        $ptk_auditor_selesai = $status_ptk_auditor === 'completed';
                                        $ptk_auditee_selesai = $status_ptk_auditee === 'completed';
                                        $laporan_selesai = $status_laporan === 'completed';
                                        $laporan_mulai = $status_laporan !== null;

                                        $buat_ptk = $item->ptk_id && !$status_ptk_auditor && !$status_ptk_auditee;

                                        $auditor_mulai = $status_ptk_auditor && !$ptk_auditor_selesai;
                                        $auditee_mulai = $status_ptk_auditee && !$ptk_auditee_selesai;
                                    @endphp

                                    @if ($item->berita_acara_id)
                                        <p class="list badge badge-success p-1" style="font-size: 14px">Berita Acara
                                            berhasil dibuat</p>
                                        <p class="list badge badge-success p-1" style="font-size: 14px">Audit Lapangan
                                            Selesai</p>
                                    @elseif ($ptk_auditor_selesai && $ptk_auditee_selesai && $laporan_mulai && !$laporan_selesai)
                                        <p class="list badge badge-warning p-1" style="font-size: 14px">Auditor sedang
                                            mengisi Temuan Positif</p>
                                    @elseif ($ptk_auditor_selesai && $ptk_auditee_selesai && !$laporan_mulai)
                                        <p class="list badge badge-warning p-1" style="font-size: 14px">Temuan Negatif
                                            selesai, menunggu pembuatan Temuan Positif</p>
                                    @elseif ($buat_ptk)
                                        <p class="list badge badge-warning p-1" style="font-size: 14px">Temuan Negatif
                                            berhasil dibuat</p>
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
                                        <p class="list badge badge-warning p-1" style="font-size: 14px">Auditan dan Auditor
                                            sedang mengisi Temuan Negatif</p>
                                    @elseif ($laporan_mulai && !$laporan_selesai)
                                        <p class="list badge badge-warning p-1" style="font-size: 14px">Auditor sedang
                                            mengisi Temuan Positif</p>
                                    @elseif ($laporan_selesai)
                                        <p class="list badge badge-success p-1" style="font-size: 14px">Laporan selesai</p>
                                    @else
                                        <a class="btn disabled">Belum Ada</a>
                                    @endif
                                </td>


                                {{-- Aksi --}}
                                <td class="text-center align-middle">
                                    <div class="d-flex justify-content-center align-items-center">
                                        {{-- Lihat --}}
                                        <div class="dropdown mr-2">
                                            <button class="btn btn-outline-primary dropdown-toggle" type="button"
                                                id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true"
                                                aria-expanded="false">
                                                <i class="fa fa-eye"></i>
                                            </button>
                                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                <a href="{{ route('hasil_audit_prodi.audit_dokumen', ['jadwalAudit' => $jadwalAudit->id, 'unit' => $item->id]) }}"
                                                    class="dropdown-item">Isian Audit Auditan</a>
                                                <a href="{{ route('hasil_audit_prodi.daftar_tilik', ['jadwalAudit' => $jadwalAudit->id, 'unit' => $item->id]) }}"
                                                    class="dropdown-item">Daftar Tilik</a>
                                                @if ($item->ptk_id)
                                                    <a href="{{ route('hasil_audit_prodi.ptk', $item->ptk_id) }}"
                                                        class="dropdown-item">Temuan Negatif</a>
                                                @endif
                                                @if ($item->laporan_id)
                                                    <a href="{{ route('hasil_audit_prodi.laporan', $item->laporan_id) }}"
                                                        class="dropdown-item">Temuan Positif</a>
                                                @endif
                                            </div>
                                        </div>

                                        {{-- Download --}}
                                        <div class="dropdown mr-2">
                                            <button class="btn btn-outline-dark dropdown-toggle" type="button"
                                                id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true"
                                                aria-expanded="false">
                                                <i class="fa fa-download"></i>
                                            </button>
                                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                {{-- Download Isian Audit Auditan --}}
                                                <form
                                                    action="{{ route('download.isi_audit_auditee', ['jadwalAudit' => $jadwalAudit->id, 'unit' => $item->id, 'type' => 'prodi']) }}"
                                                    method="post" class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="dropdown-item">
                                                        Isian Audit Auditan
                                                    </button>
                                                </form>

                                                {{-- Download Daftar Tilik --}}
                                                <form
                                                    action="{{ route('download.daftar_tilik', ['jadwalAudit' => $jadwalAudit->id, 'unit' => $item->id, 'type' => 'prodi']) }}"
                                                    method="post" class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="dropdown-item">
                                                        Daftar Tilik
                                                    </button>
                                                </form>

                                                {{-- Download Berita Acara --}}
                                                @if ($item->berita_acara_id)
                                                    <form
                                                        action="{{ route('download.berita-acara', $item->berita_acara_id) }}"
                                                        method="post" class="d-inline">
                                                        @csrf
                                                        <button type="submit" class="dropdown-item">
                                                            Berita Acara
                                                        </button>
                                                    </form>
                                                @endif

                                                {{-- Download Temuan Negatif --}}
                                                @if ($item->ptk_id)
                                                    <form action="{{ route('download.ptk', $item->ptk_id) }}"
                                                        method="post" class="d-inline">
                                                        @csrf
                                                        <button type="submit" class="dropdown-item">
                                                            Temuan Negatif
                                                        </button>
                                                    </form>
                                                @endif


                                                {{-- Download Temuan Positif --}}
                                                @if ($item->laporan_id)
                                                    <form action="{{ route('download.laporan', $item->laporan_id) }}"
                                                        method="post" class="d-inline">
                                                        @csrf
                                                        <button type="submit" class="dropdown-item">
                                                            Temuan Positif
                                                        </button>
                                                    </form>
                                                @endif

                                                {{-- Download Semua --}}
                                                <div class="dropdown-divider"></div>
                                                <form
                                                    action="{{ route('download.zip_unit', ['jadwalAudit' => $jadwalAudit->id, 'unit' => $item->id, 'type' => 'prodi']) }}"
                                                    method="post" class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="dropdown-item">
                                                        Merge All
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7">Data Tidak Ada</td>
                            </tr>
                        @endforelse
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
        });
    </script>
@endsection
