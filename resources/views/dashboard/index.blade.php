@php
    use Carbon\Carbon;
@endphp

@extends('components.layout.main_layout')

@section('content')

    <div>
        <!-- Small boxes (Stat box) -->
        @hasanyrole('pusjamu|auditor|pj_universitas|pj_fakultas|pj_prodi|gkm|gpm')
            {{-- Pusjamu --}}
            @role('pusjamu')
                @if ($user->jabatan->isNotEmpty() && $user->jabatan->first()->slug === 'rektor')
                    <div class="card card-dark">
                        <div class="card-header">
                            <h3 class="card-title title-size">Hasil Audit</h3>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">

                            {{-- Auditor --}}
                            <div>
                                <table id="hasil-audit" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th class="text-center">No</th>
                                            <th class="text-center">Jadwal</th>
                                            <th class="text-center">Tgl Mulai</th>
                                            <th class="text-center">Tgl Selesai</th>
                                            <th class="text-center">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                        @forelse ($jadwal as $item)
                                            <tr>
                                                <td class="text-center">{{ $loop->iteration }}</td>
                                                <td class="text-center">{{ $item->jadwal }}</td>
                                                <td class="text-center">
                                                    {{ Carbon::parse($item->tgl_mulai)->translatedFormat('j F Y') }}
                                                </td>
                                                <td class="text-center">
                                                    {{ Carbon::parse($item->tgl_selesai)->translatedFormat('j F Y') }}
                                                </td>
                                                <td class="text-center">
                                                    <a class="btn btn-outline-info"
                                                        href="{{ route('hasil_audit.show', ['jadwalAudit' => $item->id, 'type' => 'ps']) }}">Hasil
                                                        Audit
                                                        PS</a>
                                                    <a class="btn btn-outline-primary"
                                                        href="{{ route('hasil_audit.show', ['jadwalAudit' => $item->id, 'type' => 'upps']) }}">Hasil
                                                        Audit
                                                        UPPS</a>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td class="text-center" colspan="5">Tidak Tersedia</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <!-- /.card-body -->
                    </div>
                @else
                    <div class="row">
                        @foreach ($box as $item => $value)
                            <div class="col-lg-3 col-6">
                                <!-- small box -->
                                <div class="small-box bg-{{ $value['color'] }}">
                                    <div class="inner">
                                        <h3>{{ $value['count'] }}</h3>

                                        <p>{{ $item }}</p>
                                    </div>
                                    <div class="icon">
                                        <i class="ion ion-bag"></i>
                                    </div>
                                    <a href="{{ route($value['route']) }}" class="small-box-footer">More info <i
                                            class="fas fa-arrow-circle-right"></i></a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            @endrole

            {{-- GPM --}}
            @role('gpm')
                <div class="row">
                    @forelse ($box as $item => $value)
                        <div class="col-lg-3 col-6">
                            <!-- small box -->
                            <div class="small-box bg-{{ $value['color'] }}">
                                <div class="inner">
                                    <h3>{{ $value['count'] }}</h3>

                                    <p>{{ $item }}</p>
                                </div>
                                <div class="icon">
                                    <i class="ion ion-bag"></i>
                                </div>
                                <a href="{{ route($value['route']) }}" class="small-box-footer">More info <i
                                        class="fas fa-arrow-circle-right"></i></a>
                            </div>
                        </div>
                    @empty
                    @endforelse
                </div>
            @endrole

            {{-- Auditor --}}
            @role('auditor')
                <div class="card card-dark">
                    <div class="card-header">
                        <h3 class="card-title title-size">Daftar Auditan</h3>
                    </div>
                    <div class="card-body">
                        <div class="row mb-3">
                            {{-- Download Instrumen --}}
                            <div class="mr-2">
                                <form action="{{ route('download.instrumen') }}" method="post">
                                    @csrf
                                    <button type="submit" class="btn btn-outline-primary">
                                        <i class="fas fa-download mr-2"></i> Instrumen
                                    </button>
                                </form>
                            </div>

                            {{-- User Manual --}}
                            <div class="">
                                <a href="{{ asset('user_manual/Auditor.pdf') }}" target="_blank" class="btn btn-outline-info">
                                    <i class="fa fa-book mr-2"></i> User Manual
                                </a>
                            </div>
                        </div>

                        <!-- /.info-box -->
                        <table id="auditor" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th class="text-center">No</th>
                                    <th class="text-center">Jadwal</th>
                                    <th class="text-center">Periode</th>
                                    <th class="text-center">Auditan</th>
                                    <th class="text-center">Prodi/Fakultas/Unit</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($jadwalAuditor as $item)
                                    <tr>
                                        <td class="text-center">{{ $loop->iteration }}</td>
                                        <td class="text-center">{{ $item['jadwal'] }}</td>
                                        <td class="text-center">
                                            {{ \Carbon\Carbon::parse($item['tgl_mulai'])->translatedFormat('j F Y') }}
                                            -
                                            {{ \Carbon\Carbon::parse($item['tgl_selesai'])->translatedFormat('j F Y') }}
                                        </td>
                                        <td class="align-middle">
                                            @if (!empty($item['units']) && count($item['units']) > 0)
                                                @foreach ($item['units'] as $unit)
                                                    <div class="mb-3">
                                                        <p class="list">
                                                            {{ $loop->iteration }}. {{ $unit['unitType'] }}
                                                            {{ $unit['unitName'] }}
                                                        </p>
                                                        @foreach ($unit['auditees'] as $aud)
                                                            Auditor {{ $loop->iteration }}&nbsp;:&nbsp; {{ $aud->name }}
                                                            {{ '(' . $aud->no_telepon . ')' }}
                                                            <br>
                                                        @endforeach
                                                    </div>
                                                @endforeach
                                            @else
                                                <div class="text-center">
                                                    <a class="btn disabled">Belum Ada</a>
                                                </div>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            @if (!$item['expired'])
                                                @if ($item['fitur_auditor'])
                                                    <a href="{{ route('auditor.unit.show', ['jadwalAudit' => $item['id'], 'type' => 'prodi']) }}"
                                                        class="btn btn-outline-info">Pilih Prodi</a>
                                                    <a href="{{ route('auditor.unit.show', ['jadwalAudit' => $item['id'], 'type' => 'upps']) }}"
                                                        class="btn btn-outline-primary">Pilih Fakultas/Unit</a>
                                                @else
                                                    <a class="btn disabled">Tidak Tersedia</a>
                                                @endif
                                            @else
                                                <a class="btn disabled">Tidak Tersedia</a>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td class="text-center" colspan="5">Tidak Tersedia</td>
                                    </tr>
                                @endforelse

                            </tbody>
                        </table>

                    </div>
                </div>
            @endrole

            {{-- Auditan --}}
            @role(['pj_fakultas', 'pj_prodi', 'gkm'])
                <div class="card card-dark">
                    <div class="card-header">
                        <h3 class="card-title title-size">Daftar Auditor</h3>
                    </div>
                    <div class="card-body">
                        {{-- User Manual --}}
                        <div class="mb-3">
                            <a href="{{ asset('user_manual/Auditan.pdf') }}" target="_blank" class="btn btn-outline-info">
                                <i class="fa fa-book mr-2"></i> User Manual
                            </a>
                        </div>

                        <table id="auditee" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th class="text-center">No</th>
                                    <th class="text-center">Jadwal</th>
                                    <th class="text-center">Periode</th>
                                    <th class="text-center">Status</th>
                                    <th class="text-center">Auditor</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($jadwalAuditan as $item)
                                    <tr>
                                        <td class="text-center align-middle">{{ $loop->iteration }}</td>
                                        <td class="text-center align-middle">{{ $item->jadwal }}</td>
                                        <td class="text-center align-middle">
                                            {{ Carbon::parse($item->tgl_mulai)->translatedFormat('j F Y') }} -
                                            {{ Carbon::parse($item->tgl_selesai)->translatedFormat('j F Y') }}
                                        </td>
                                        <td class="text-center align-middle">
                                            @if (!$item->expired)
                                                <span class="badge badge-success">Terbuka</span>
                                            @else
                                                <span class="badge badge-danger">Tertutup</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($item->auditee_auditor->isNotEmpty())
                                                @foreach ($item->auditee_auditor as $auditeeAuditor)
                                                    <p style="margin: 0; padding: 0;">{{ $loop->iteration }}.
                                                        {{ $auditeeAuditor->auditor->user->name }}
                                                        {{ '(' . $auditeeAuditor->auditor->user->no_telepon . ')' }}</p>
                                                @endforeach
                                            @else
                                                <div class="text-center">
                                                    <a class="btn disabled">Belum ada auditor</a>
                                                </div>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td class="text-center" colspan="5">Tidak Tersedia</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            @endrole

            {{-- PJ Universitas --}}
            @role('pj_universitas')
                <div class="card card-dark">
                    <div class="card-header">
                        <h3 class="card-title title-size">Daftar Auditor</h3>
                    </div>
                    <div class="card-body">
                        {{-- User Manual --}}
                        <div class="mb-3">
                            <a href="{{ asset('user_manual/Auditan.pdf') }}" target="_blank" class="btn btn-outline-info">
                                <i class="fa fa-book mr-2"></i> User Manual
                            </a>
                        </div>

                        <table id="auditee" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th class="text-center">No</th>
                                    <th class="text-center">Jadwal</th>
                                    <th class="text-center">Periode</th>
                                    <th class="text-center">Status</th>
                                    <th class="text-center">Auditor</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($jadwalAuditan as $item)
                                    <tr>
                                        <td class="text-center align-middle">{{ $loop->iteration }}</td>
                                        <td class="text-center align-middle">{{ $item->jadwal }}</td>
                                        <td class="text-center align-middle">
                                            {{ Carbon::parse($item->tgl_mulai)->translatedFormat('j F Y') }} -
                                            {{ Carbon::parse($item->tgl_selesai)->translatedFormat('j F Y') }}
                                        </td>
                                        <td class="text-center align-middle">
                                            @if (!$item->expired)
                                                <span class="badge badge-success">Terbuka</span>
                                            @else
                                                <span class="badge badge-danger">Tertutup</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($item->auditee_auditor->isNotEmpty())
                                                @foreach ($item->auditee_auditor as $auditeeAuditor)
                                                    <p style="margin: 0; padding: 0;">{{ $loop->iteration }}.
                                                        {{ $auditeeAuditor->auditor->user->name }}
                                                        {{ '(' . $auditeeAuditor->auditor->user->no_telepon . ')' }}</p>
                                                @endforeach
                                            @else
                                                <div class="text-center">
                                                    <a class="btn disabled">Belum ada auditor</a>
                                                </div>
                                            @endif
                                        </td>
                                    </tr>
                                @empty<tr>
                                        <td class="text-center" colspan="5">Tidak Tersedia</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            @endrole
        @else
            @if ($user->jabatan->isNotEmpty() && $user->jabatan->first()->slug === 'rektor')
                <div class="card card-dark">
                    <div class="card-header">
                        <h3 class="card-title title-size">Hasil Audit</h3>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        {{-- Auditor --}}
                        <div>
                            <table id="hasil-audit" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th class="text-center">No</th>
                                        <th class="text-center">Jadwal</th>
                                        <th class="text-center">Tgl Mulai</th>
                                        <th class="text-center">Tgl Selesai</th>
                                        <th class="text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($jadwal as $item)
                                        <tr>
                                            <td class="text-center">{{ $loop->iteration }}</td>
                                            <td class="text-center">{{ $item->jadwal }}</td>
                                            <td class="text-center">
                                                {{ Carbon::parse($item->tgl_mulai)->translatedFormat('j F Y') }}
                                            </td>
                                            <td class="text-center">
                                                {{ Carbon::parse($item->tgl_selesai)->translatedFormat('j F Y') }}
                                            </td>
                                            <td class="text-center">
                                                <a class="btn btn-outline-info"
                                                    href="{{ route('hasil_audit.show', ['jadwalAudit' => $item->id, 'type' => 'ps']) }}">Hasil
                                                    Audit
                                                    PS</a>
                                                <a class="btn btn-outline-primary"
                                                    href="{{ route('hasil_audit.show', ['jadwalAudit' => $item->id, 'type' => 'upps']) }}">Hasil
                                                    Audit
                                                    UPPS</a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td class="text-center" colspan="5">Tidak Tersedia</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <!-- /.card-body -->
                </div>
            @else
                <section class="content">
                    <div class="error-page">
                        <h2 class="headline text-warning"> 403</h2>

                        <div class="error-content">
                            <h3><i class="fas fa-exclamation-triangle text-warning"></i> Oops! Anda tidak punya akses ke dalam
                                sistem.</h3>

                            <p class="error" style="font-size: 18px;">
                                Silahkan hubungi Admin atau
                                untuk sementara, Anda dapat <a href="{{ route('dashboard') }}">kembali ke dashboard.</a>
                            </p>

                        </div>
                        <!-- /.error-content -->
                    </div>
                    <!-- /.error-page -->
                </section>
            @endif
        @endhasanyrole

    </div>


    <!-- ./col -->
    <!-- /.row -->
@endsection

@section('style')
    <!-- DataTables -->
    <link rel="stylesheet" href="{{ asset('plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
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
            $("#auditor").DataTable({
                "responsive": true,
                "autoWidth": false,
                "columnDefs": [{
                        "width": "5%",
                        "targets": [0]
                    },
                    {
                        "width": "20%",
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
                        "width": "25%",
                        "targets": [4]
                    },
                ]
            });
            $("#auditee").DataTable({
                "responsive": true,
                "autoWidth": false,
                "columnDefs": [{
                        "width": "5%",
                        "targets": [0]
                    },
                    {
                        "width": "20%",
                        "targets": [1]
                    },
                    {
                        "width": "25%",
                        "targets": [2]
                    },
                    {
                        "width": "20%",
                        "targets": [3]
                    },
                ]
            })
            $("#hasil-audit").DataTable({
                "responsive": true,
                "autoWidth": false,
            })
        });
    </script>
@endsection
