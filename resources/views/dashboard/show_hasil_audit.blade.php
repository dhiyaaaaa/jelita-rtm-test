@extends('components.layout.main_layout')

@section('content')
    <div class="card card-dark">
        <div class="card-header">
            <h3 class="card-title title-size">{{ $title }}</h3>
        </div>
        <!-- /.card-header -->
        <div class="card-body">
            {{-- PS --}}
            @if ($type === 'ps')
                <div>
                    <h5>Program Studi</h5>
                    <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary mb-3">Kembali</a>

                    <table id="ps" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th class="text-center">No</th>
                                <th class="text-center">Prodi</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>


                            @foreach ($ps as $item)
                                <tr>
                                    {{-- No --}}
                                    <td class="text-center align-middle">{{ $loop->iteration }}</td>

                                    {{-- Prodi --}}
                                    <td class="align-middle">{{ $item->nama }} {{ $item->jenjang->nama }}</td>

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
                                                    <a href="{{ route('dashboard.hasil_audit.audit_dokumen', ['jadwalAudit' => $jadwalAudit->id, 'unit' => $item->id, 'type' => $item->type]) }}"
                                                        class="dropdown-item">Audit Dokumen</a>
                                                    <a href="{{ route('dashboard.hasil_audit.daftar_tilik', ['jadwalAudit' => $jadwalAudit->id, 'unit' => $item->id, 'type' => $item->type]) }}"
                                                        class="dropdown-item">Daftar Tilik</a>
                                                    @if ($item->ptk->isNotEmpty())
                                                        <a href="{{ route('dashboard.hasil_audit.ptk', $item->ptk->first()->id) }}"
                                                            class="dropdown-item">Temuan Negatif</a>
                                                    @endif
                                                    @if ($item->laporan->isNotEmpty())
                                                        <a href="{{ route('dashboard.hasil_audit.laporan', $item->laporan->first()->id) }}"
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
            @endif

            {{-- UPPS --}}
            @if ($type === 'upps')
                <div>
                    <h5>Unit Pengelola Program Studi</h5>
                    <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary mb-3">Kembali</a>
                    <table id="upps" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th class="text-center">No</th>
                                <th class="text-center">Fakultas/Unit</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>


                            @foreach ($upps as $item)
                                <tr>
                                    {{-- No --}}
                                    <td class="text-center align-middle">{{ $loop->iteration }}</td>

                                    {{-- Fakultas/Unit --}}
                                    <td class="align-middle">
                                        {{ $item->nama }}
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
                                                    <a href="{{ route('dashboard.hasil_audit.audit_dokumen', ['jadwalAudit' => $jadwalAudit->id, 'unit' => $item->id, 'type' => $item->type]) }}"
                                                        class="dropdown-item">Audit Dokumen</a>
                                                    <a href="{{ route('dashboard.hasil_audit.daftar_tilik', ['jadwalAudit' => $jadwalAudit->id, 'unit' => $item->id, 'type' => $item->type]) }}"
                                                        class="dropdown-item">Daftar Tilik</a>
                                                    @if ($item->ptk->isNotEmpty())
                                                        <a href="{{ route('dashboard.hasil_audit.ptk', $item->ptk->first()->id) }}"
                                                            class="dropdown-item">Temuan Negatif</a>
                                                    @endif
                                                    @if ($item->laporan->isNotEmpty())
                                                        <a href="{{ route('dashboard.hasil_audit.laporan', $item->laporan->first()->id) }}"
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
                                                        <form
                                                            action="{{ route('download.ptk', $item->ptk->first()->id) }}"
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
            @endif
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
