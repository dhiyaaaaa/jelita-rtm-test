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
            <table id="auditee" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th class="text-center">No</th>
                        <th class="text-center">Jadwal</th>
                        <th class="text-center">Periode</th>
                        <th class="text-center">Status</th>
                        <th class="text-center">Audit</th>
                        <th class="text-center">Import Jawaban</th>
                        <th class="text-center">Kontak Auditor</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($jadwal as $index => $item)
                        <tr>
                            {{-- No --}}
                            <td class="text-center align-middle">{{ $loop->iteration }}</td>

                            {{-- Jadwal --}}
                            <td class="text-center align-middle">
                                {{ $item->jadwal }}
                            </td>

                            {{-- Periode --}}
                            <td class="text-center align-middle">
                                {{ Carbon::parse($item->tgl_mulai)->translatedFormat('j F Y') }} -
                                {{ Carbon::parse($item->tgl_selesai)->translatedFormat('j F Y') }}
                            </td>

                            {{-- Status --}}
                            <td class="text-center align-middle">
                                @if ($item->status_audit_auditee->isNotEmpty())
                                    @foreach ($item->status_audit_auditee as $status)
                                        @if ($status->status == 'in_progress')
                                            <span class="badge badge-warning">Progress</span>
                                        @elseif ($status->status == 'completed')
                                            <span class="badge badge-success">Selesai</span>
                                        @else
                                            <span class="badge badge-danger">Belum Mulai</span>
                                        @endif
                                    @endforeach
                                @else
                                    <span class="badge badge-danger">Belum Mulai</span>
                                @endif

                            </td>

                            {{-- Audit --}}
                            <td class="text-center align-middle">
                                @php
                                    $unitId = $prodi->id ?? ($fakultas->id ?? ($unit->id ?? 'null'));
                                    $type = $prodi ? 'prodi' : ($fakultas ? 'fakultas' : ($unit ? 'universitas' : ''));
                                @endphp

                                @if ($item->auditee->isNotEmpty())
                                    @if ($unitId)
                                        @if (!$item->expired)
                                            @if ($item->status_audit_auditee->isNotEmpty())
                                                @foreach ($item->status_audit_auditee as $status)
                                                    @if ($status->status == 'in_progress')
                                                        <a href="{{ route('auditee.dokumen.create', ['jadwalAudit' => $item->id, 'unit' => $unitId, 'type' => $type]) }}"
                                                            class="btn btn-outline-warning">Lanjutkan</a>
                                                    @elseif($status->status == 'completed')
                                                        <a href="{{ route('auditee.dokumen.create', ['jadwalAudit' => $item->id, 'unit' => $unitId, 'type' => $type]) }}"
                                                            class="btn btn-outline-primary">Lihat</a>
                                                    @else
                                                        <form
                                                            action="{{ route('auditee.dokumen.isi_audit', ['jadwalAudit' => $item->id, 'unit' => $unitId, 'type' => $type]) }}"
                                                            method="post" class="d-inline">
                                                            @csrf
                                                            <button type="submit"
                                                                class="btn btn-outline-success">Mulai</button>
                                                        </form>
                                                    @endif
                                                @endforeach
                                            @else
                                                <form
                                                    action="{{ route('auditee.dokumen.isi_audit', ['jadwalAudit' => $item->id, 'unit' => $unitId, 'type' => $type]) }}"
                                                    method="post" class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="btn btn-outline-success">Mulai</button>
                                                </form>
                                            @endif
                                        @else
                                            <a href="{{ route('auditee.dokumen.create', ['jadwalAudit' => $item->id, 'unit' => $unitId, 'type' => $type]) }}"
                                                class="btn btn-outline-primary">Lihat</a>
                                        @endif
                                    @endif
                                @endif
                                <form
                                    action="{{ route('download.isi_audit_auditee', ['jadwalAudit' => $item->id, 'unit' => $unitId, 'type' => $type]) }}"
                                    method="post" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-outline-dark">
                                        <i class="fa fa-download p-1"></i>
                                    </button>
                                </form>
                            </td>

                            {{-- Import Jawaban --}}
                            <td class="text-center align-middle">
                                @if ($index !== count($jadwal) - 1 && $item->import)
                                    @if (!$item->expired)
                                        <a href="{{ route('auditee.dokumen.import', ['jadwalAudit' => $item->id, 'unit' => $unitId, 'type' => $type]) }}"
                                            class="btn btn-primary">Import Jawaban</a>
                                    @else
                                        <a href="#" class="btn disabled">Tidak Tersedia</a>
                                    @endif
                                @else
                                    <a href="#" class="btn disabled">Tidak Tersedia</a>
                                @endif
                            </td>

                            {{-- Kontak Auditor --}}
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
            $("#auditee").DataTable({
                "responsive": true,
                "autoWidth": false,
                "columnDefs": [{
                        "width": "5%",
                        "targets": [0]
                    },
                    {
                        "width": "10%",
                        "targets": [1]
                    },
                    {
                        "width": "15%",
                        "targets": [2]
                    },
                    {
                        "width": "10%",
                        "targets": [3]
                    },
                    {
                        "width": "25%",
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
        })
    </script>
@endsection
