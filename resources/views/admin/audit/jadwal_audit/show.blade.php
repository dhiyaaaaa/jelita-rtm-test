    @php
        use Carbon\Carbon;
    @endphp

    @extends('components.layout.main_layout')

    @section('content')
        <div class="card card-dark">
            <div class="card-header">
                <h3 class="card-title title-size">{{ $title }}</h3>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <a href="{{ route('jadwal_audit') }}" class="btn btn-outline-secondary">Kembali</a>
                </div>

                {{-- Detail Menu --}}
                <table class="table mt-3 mb-3">
                    <tr>
                        <td class="text-bold" width="15%">Jadwal</td>
                        <td width="3%">:</td>
                        <td>{{ $jadwalAudit->jadwal }}</td>
                    </tr>
                    <tr>
                        <td class="text-bold" width="15%">Tanggal Mulai</td>
                        <td width="3%">:</td>
                        <td>
                            {{ Carbon::parse($jadwalAudit->tgl_mulai)->translatedFormat('j F Y') }}
                        </td>
                    </tr>
                    <tr>
                        <td class="text-bold" width="15%">Tanggal Selesai</td>
                        <td width="3%">:</td>
                        <td>
                            {{ Carbon::parse($jadwalAudit->tgl_selesai)->translatedFormat('j F Y') }}
                        </td>
                    </tr>
                </table>


                <table id="jadwalAudit" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th class="text-center">No</th>
                            <th class="text-center">Kode</th>
                            <th class="text-center">Pernyataan</th>
                            <th class="text-center">Jenjang/Auditee</th>
                            <th class="text-center">Unit/Lembaga</th>
                            <th class="text-center">Level</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        
                    </tbody>
                </table>
            </div>
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
                const table = $("#jadwalAudit").DataTable({
                    "processing": true,
                    "serverSide": true,
                    "pageLength": 25,
                    "ajax": {
                        "url": "{{ route('jadwal_audit.get_instrumen_by_jadwal', $jadwalAudit->id) }}",
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
                            "data": "kode",
                            "className": "text-center"
                        },
                        {
                            "data": "pernyataan",
                            "className": "text-center"
                        },
                        {
                            "data": "jenjang",
                            "className": "text-center"
                        },
                        {
                            "data": "unit",
                            "className": "text-center"
                        },
                        {
                            "data": "level",
                            "className": "text-center"
                        },
                        {
                            "data": "action",
                            "orderable": false,
                            "searchable": false,
                            "className": "text-center"
                        }
                    ],
                    "responsive": true,
                    "autoWidth": false,
                    "pageLength": 25,
                    "columnDefs": [{
                        "width": "10%",
                        "targets": [0]
                    }, ]
                })
            });
        </script>
    @endsection
