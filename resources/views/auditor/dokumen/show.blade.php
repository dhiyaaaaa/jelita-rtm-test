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
            <a href="{{ route('auditor.dokumen') }}" class="btn btn-outline-secondary mb-3">Kembali</a>

            {{-- Detail Jadwal --}}
            <table class="table mb-3">
                <tr>
                    <td class="text-bold" width="10%">Jadwal</td>
                    <td width="5%">:</td>
                    <td>Audit Dokumen {{ $jadwal->jadwal }}</td>
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
                        <th class="text-center">Status Auditan</th>
                        <th class="text-center">Audit</th>
                        <th class="text-center">Daftar Tilik</th>
                        <th class="text-center">Kontak Auditan</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($auditee as $item)
                        <tr>
                            {{-- No --}}
                            <td class="text-center align-middle">{{ $loop->iteration }}</td>

                            {{-- Prodi/Fakultas/Unit --}}
                            <td class="align-middle">
                                @if ($item->type === 'prodi')
                                    <span>
                                        {{ 'Program Studi ' . $item->nama }} {{ $item->jenjang->nama }}
                                    </span>
                                @elseif ($item->type === 'fakultas')
                                    <span>
                                        {{ 'Fakultas ' . $item->nama }}
                                    </span>
                                @else
                                    <span>
                                        {{ $item->nama }}
                                    </span>
                                @endif
                            </td>

                            {{-- Status Auditee --}}
                            <td class="text-center align-middle">
                                @if ($item->status_audit_auditee->isNotEmpty())
                                    @if ($item->status_audit_auditee->first()->status == 'in_progress')
                                        <span class="badge badge-warning">Progress</span>
                                    @elseif ($item->status_audit_auditee->first()->status == 'completed')
                                        <span class="badge badge-success">Selesai</span>
                                    @else
                                        <span class="badge badge-danger">Belum Mulai</span>
                                    @endif
                                @else
                                    <span class="badge badge-danger">Belum Mulai</span>
                                @endif
                            </td>

                            {{-- Audit Dokumen --}}
                            <td class="text-center align-middle">
                                @if (!$expired)
                                    @if ($item->status_audit_auditor->isNotEmpty() && $item->status_audit_auditor->first()->status == 'completed')
                                        <a href="{{ route('auditor.dokumen.create', [
                                            'jadwalAudit' => $jadwal->id,
                                            'unit' => $item->id,
                                            'type' => $item->type,
                                        ]) }}"
                                            class="btn btn-info">Sudah Audit</a>
                                    @else
                                        <form
                                            action="{{ route('auditor.dokumen.isi_audit', ['jadwalAudit' => $jadwal->id, 'unit' => $item->id, 'type' => $item->type]) }}"
                                            method="post" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-outline-info">Audit</button>
                                        </form>
                                    @endif
                                @else
                                    <a href="{{ route('auditor.dokumen.create', [
                                        'jadwalAudit' => $jadwal->id,
                                        'unit' => $item->id,
                                        'type' => $item->type,
                                    ]) }}"
                                        class="btn btn-info">Lihat</a>
                                @endif
                            </td>

                            {{-- Daftar Tilik --}}
                            <td class="text-center align-middle">
                                @if ($item->jawaban_auditor->isNotEmpty())
                                    <a href="{{ route('auditor.dokumen.daftar_tilik', [
                                        'jadwalAudit' => $jadwal->id,
                                        'unit' => $item->id,
                                        'type' => $item->type,
                                    ]) }}"
                                        class="btn btn-outline-danger">Daftar Tilik</a>

                                    <form
                                        action="{{ route('download.daftar_tilik', ['jadwalAudit' => $jadwal->id, 'unit' => $item->id, 'type' => $item->type]) }}"
                                        method="post" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-outline-dark">
                                            <i class="fa fa-download p-1"></i>
                                        </button>
                                    </form>
                                @elseif($item->jawaban_auditor->isEmpty())
                                    @if ($expired)
                                        <span class="badge badge-danger">Tertutup</span>
                                    @else
                                        <span class="badge badge-danger">Belum Ada</span>
                                    @endif
                                @endif
                            </td>

                            {{-- Kontak Auditee --}}
                            <td class="align-middle">
                                @php
                                    $uniqueAuditees = collect();
                                    $no = 1;
                                @endphp

                                @foreach ($item->auditee as $user)
                                    @php
                                        $auditee = $user->user;
                                        $auditeeKey = $auditee->name . '-' . $auditee->no_telepon;
                                    @endphp

                                    @if (!$uniqueAuditees->contains($auditeeKey))
                                        @php
                                            $uniqueAuditees->push($auditeeKey);
                                        @endphp
                                        {{ $no++ }}. {{ $auditee->name }}
                                        {{ '(' . $auditee->no_telepon . ')' }}<br>
                                    @endif
                                @endforeach
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
    {{-- <link rel="stylesheet" href="{{ asset('plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}"> --}}
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
                        "width": "10%",
                        "targets": [2]
                    },
                    {
                        "width": "15%",
                        "targets": [3]
                    },
                    {
                        "width": "25%",
                        "targets": [4]
                    },
                    {
                        "width": "25%",
                        "targets": [5]
                    },
                ]
            });
        })
    </script>
@endsection
