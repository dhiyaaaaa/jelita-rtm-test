@extends('components.layout.main_layout')

@section('content')
    <div class="card card-dark">
        <div class="card-header">
            <h3 class="card-title title-size">{{ $title }}</h3>
        </div>
        <!-- /.card-header -->
        <div class="card-body">
            <div class="mb-3">
                <a href="{{ route('role') }}" class="btn btn-outline-secondary">Kembali</a>
                <a href="{{ route('user.create') }}" class="btn btn-outline-primary">+ Tambah User</a>
            </div>

            {{-- Detail Role --}}
            <table class="table mt-3 mb-3">
                <tr>
                    <td class="text-bold" width="7%">
                        Role
                    </td>
                    <td width="3%">:</td>
                    <td>{{ strtoupper($role->name) }}</td>
                </tr>
            </table>

            {{-- Table User --}}
            <table id="user" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th class="text-center">No</th>
                        <th class="text-center">Nama</th>
                        <th class="text-center">Email</th>
                        <th class="text-center">Jabatan</th>
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
            $("#user").DataTable({
                "processing": true,
                "serverSide": true,
                "ajax": "{{ route('role.get_users_by_role', $role->id) }}",
                "columns": [{
                        "data": null,
                        "sortable": false,
                        "render": function(data, type, row, meta) {
                            return meta.row + meta.settings._iDisplayStart + 1;
                        },
                        "className": "text-center"
                    },
                    {
                        "data": "name",
                        "className": "text-center"
                    },
                    {
                        "data": "email",
                        "className": "text-center"
                    },
                    {
                        "data": "jabatan",
                        "className": "text-center",
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
                        "width": "20%",
                        "targets": [1]
                    },
                    {
                        "width": "20%",
                        "targets": [2]
                    },
                    {
                        "width": "20%",
                        "targets": [4]
                    }
                ]
            });
        });
    </script>
@endsection
