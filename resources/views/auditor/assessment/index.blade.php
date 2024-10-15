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

            {{-- Filter --}}
            <div class="row">
                {{-- Jadwal --}}
                <div class="col-3">
                    <div class="form-group">
                        <label>Jadwal Audit:</label>
                        <select class="select2" style="width: 100%;" data-placeholder="Pilih Jadwal Audit" id="jadwal">
                            @if ($jadwal->isNotEmpty())
                                @foreach ($jadwal as $item)
                                    <option value="{{ $item->id }}">{{ $item->jadwal }}</option>
                                @endforeach
                            @else
                                <option selected value="all">All</option>
                            @endif
                        </select>
                    </div>
                </div>
            </div>

            <table id="assessment" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th class="text-center">No</th>
                        <th class="text-center">Auditor</th>
                        <th class="text-center">Prodi/Fakultas/Unit</th>
                        <th class="text-center">Jadwal</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($auditors as $item)
                        <tr data-jadwal="{{ $item->jadwal_audit->id }}">
                            <td class="text-center">{{ $loop->iteration }}</td>
                            <td>{{ $item->auditor->user->name }}</td>
                            <td>
                                @if ($item->prodi)
                                    {{ 'Program Studi ' . $item->prodi->nama . ' ' . $item->prodi->jenjang->nama }}
                                @elseif($item->fakultas)
                                    {{ 'Fakultas ' . $item->fakultas->nama }}
                                @elseif($item->unit)
                                    {{ $item->unit->nama }}
                                @else
                                    -
                                @endif
                            </td>
                            <td class="text-center">{{ $item->jadwal_audit->jadwal }}</td>
                            <td class="text-center">
                                @php
                                    $status = null;
                                    if ($item->auditor->status_assessment_dinilai->isNotEmpty()) {
                                        $status = $item->auditor->status_assessment_dinilai->where(
                                            'jadwal_audit_id',
                                            $item->jadwal_audit->id,
                                        );
                                        if ($item->prodi) {
                                            $status = $item->auditor->status_assessment_dinilai
                                                ->where('jadwal_audit_id', $item->jadwal_audit->id)
                                                ->where('prodi_id', $item->prodi_id)
                                                ->first();
                                        } elseif ($item->fakultas) {
                                            $status = $item->auditor->status_assessment_dinilai
                                                ->where('jadwal_audit_id', $item->jadwal_audit->id)
                                                ->where('fakultas_id', $item->fakultas_id)
                                                ->first();
                                        } elseif ($item->unit) {
                                            $status = $item->auditor->status_assessment_dinilai
                                                ->where('jadwal_audit_id', $item->jadwal_audit->id)
                                                ->where('unit_id', $item->unit_id)
                                                ->first();
                                        }
                                    }
                                    $unit = get_type_model($item);
                                @endphp
                                @if ($status && $status->status == 'selesai')
                                    <a href="{{ route('auditor.peer-assessment.create', ['auditor' => $item->auditor_id, 'jadwalAudit' => $item->jadwal_audit->id, 'unit' => $unit['value'], 'type' => $unit['type']]) }}"
                                        class="btn btn-info">Sudah Isi</a>
                                @else
                                    <a href="{{ route('auditor.peer-assessment.create', ['auditor' => $item->auditor_id, 'jadwalAudit' => $item->jadwal_audit->id, 'unit' => $unit['value'], 'type' => $unit['type']]) }}"
                                        class="btn btn-outline-primary">Isi</a>
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
    <!-- Select2 -->
    <link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset('dist/css/adminlte.min.css') }}">
@endsection

@section('script')
    <!-- DataTables & Plugins -->
    <script src="{{ asset('plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>

    <!-- Select2 -->
    <script src="{{ asset('plugins/select2/js/select2.full.min.js') }}"></script>

    <!-- Page specific script -->
    <script>
        $(function() {
            $('.select2').select2();

            var table = $("#assessment").DataTable({
                "responsive": true,
                "autoWidth": false,
                "pageLength": 25,
                "columnDefs": [{
                        "width": "10%",
                        "targets": [0]
                    },
                    {
                        "width": "30%",
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
                    {
                        "width": "15%",
                        "targets": [4]
                    },
                ]
            })

            // Filter
            $('#jadwal').on('change', function() {
                var selectedJadwal = $(this).val();
                if (selectedJadwal === "all") {
                    table.rows().show();
                } else {
                    table.rows().every(function(rowIdx, tableLoop, rowLoop) {
                        var row = $(this.node());
                        if (row.data('jadwal') == selectedJadwal) {
                            row.show();
                        } else {
                            row.hide();
                        }
                    });
                }
            });

            if ($('#jadwal').val()) {
                $('#jadwal').trigger('change');
            }
        });
    </script>
@endsection
