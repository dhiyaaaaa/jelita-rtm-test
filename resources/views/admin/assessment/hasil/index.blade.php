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
                            @forelse ($jadwal as $item)
                                <option value="{{ $item->id }}">{{ $item->jadwal }}</option>
                            @empty
                                <option selected value="null">Belum Ada Jadwal</option>
                            @endforelse
                        </select>
                    </div>
                </div>
            </div>

            <table id="assessment" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th class="text-center">No</th>
                        <th class="text-center">Auditor</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
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
        $(document).ready(function() {
            $('.select2').select2();

            const table = $("#assessment").DataTable({
                "processing": true,
                "serverSide": true,
                "responsive": true,
                "autoWidth": false,
                "pageLength": 25,
                "ajax": {
                    "url": "{{ route('hasil_assessment.get_auditors') }}",
                    "data": function(d) {
                        d.jadwal = $('#jadwal').val();
                    },
                },
                "columns": [{
                        "data": null,
                        "sortable": false,
                        "render": function(data, type, row, meta) {
                            return meta.row + meta.settings._iDisplayStart + 1;
                        },
                        "className": "text-center"
                    },
                    {
                        "data": "nama",
                        "className": "text-center"
                    },
                    {
                        "data": "action",
                        "orderable": false,
                        "searchable": false,
                        "className": "text-center"
                    }
                ],
                "columnDefs": [{
                    "width": "5%",
                    "targets": [0]
                }, ]
            })

            $('#jadwal').on('change', function() {
                console.log('Selected Jadwal:', $('#jadwal').val());
                table.ajax.reload();
            });

        });
    </script>
@endsection
