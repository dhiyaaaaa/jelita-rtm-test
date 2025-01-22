@extends('components.layout.main_layout')

@section('content')
    <div class="card card-dark">
        <div class="card-header">
            <h3 class="card-title title-size">{{ $title }}</h3>
        </div>
        <!-- /.card-header -->
        <div class="card-body">
            <div class="mb-3">
                <div class="mb-3">
                    {{-- Button Tambah User --}}
                    <a href="{{ route('user.create') }}" class="btn btn-outline-primary mr-3">+ Tambah User</a>

                    {{-- Button Tambah User Role --}}
                    <a href="{{ route('user.create_user_role') }}" class="btn btn-outline-primary">+ Tambah Role Untuk
                        User</a>
                </div>

                {{-- Download User berdasarkan role --}}
                <div>
                    <label>Download User</label>
                    <form action="{{ route('download.user') }}" method="post" class="d-flex" id="download-form">
                        @csrf
                        <div class="mr-3" style="width: 20%">
                            <select class="select2" data-placeholder="Pilih Role" name="role" style="width: 100%"
                                id="role">
                                @foreach ($roles as $role)
                                    <option value="{{ $role->id }}">{{ strtoupper($role->name) }}</option>
                                @endforeach
                            </select>

                            @if ($errors->has('role'))
                                <span class="text-danger d-block"
                                    style="font-size: 14px">{{ $errors->first('unit') }}</span>
                            @endif
                        </div>
                        <div>
                            <button type="submit" class="btn btn-outline-success">Download</button>
                        </div>
                    </form>
                </div>
            </div>

            <table id="user" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th class="text-center">No</th>
                        <th class="text-center">Nama</th>
                        <th class="text-center">Email</th>
                        <th class="text-center">Jabatan</th>
                        <th class="text-center">Prodi/Fakultas/Unit</th>
                        <th class="text-center">Role</th>
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
    <link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
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
    <script>
        $(function() {
            //Initialize Select2 Elements
            $('.select2').select2()

            //Initialize Select2 Elements
            $('.select2bs4').select2({
                theme: 'bootstrap4'
            })
        });
    </script>

    <!-- Page specific script -->
    <script>
        $(function() {
            $("#user").DataTable({
                "processing": true,
                "serverSide": true,
                "ajax": "{{ route('user.get_users') }}",
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
                        "className": "text-center"
                    },
                    {
                        "data": "prodi_fakultas_unit",
                        "className": "text-center"
                    },
                    {
                        "data": "role",
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
                "pageLength": 50,
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
                        "width": "10%",
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
                        "width": "15%",
                        "targets": [6]
                    }
                ]
            })
        });
    </script>
@endsection
