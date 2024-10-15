@extends('components.layout.main_layout')

@section('content')
    <div class="card card-dark">
        <div class="card-header">
            <h3 class="card-title title-size">{{ $title }}</h3>
        </div>
        <!-- /.card-header -->
        <div class="card-body">
            <a href="{{ route('jenjang.create') }}" class="btn btn-outline-primary mb-3">+ Tambah Jenjang</a>
            <table id="jenjang" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th class="text-center">No</th>
                        <th class="text-center">Jenjang</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($jenjangs as $jenjang)
                        <tr>
                            <td class="text-center">{{ $loop->iteration }}</td>
                            <td>{{ $jenjang->nama }}</td>
                            <td class="text-center">
                                <div class="dropdown">
                                    <button class="btn btn-success dropdown-toggle" type="button"
                                        id="dropdownMenuButton{{ $jenjang->id }}" data-toggle="dropdown"
                                        aria-haspopup="true" aria-expanded="false">
                                        Actions
                                    </button>
                                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton{{ $jenjang->id }}">
                                        <a class="dropdown-item" href="{{ route('jenjang.show', $jenjang->id) }}">Lihat</a>
                                        <a class="dropdown-item" href="{{ route('jenjang.edit', $jenjang->id) }}">Edit</a>
                                        <a class="dropdown-item" href="{{ route('jenjang.delete', $jenjang->id) }}" data-confirm-delete="true">Hapus</a>
                                    </div>
                                </div>

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
            $("#jenjang").DataTable({
                "responsive": true,
                "autoWidth": false,
                "columnDefs": [{
                        "width": "10%",
                        "targets": [0]
                    },
                    {
                        "width": "50%",
                        "targets": [1]
                    },
                    {
                        "width": "20%",
                        "targets": [2]
                    }
                ]
            });
        });
    </script>
@endsection
