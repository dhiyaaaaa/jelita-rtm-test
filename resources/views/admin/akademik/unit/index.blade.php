@extends('components.layout.main_layout')

@section('content')
    <div class="card card-dark">
        <div class="card-header">
            <h3 class="card-title title-size">{{ $title }}</h3>
        </div>
        <!-- /.card-header -->
        <div class="card-body">
            <a href="{{ route('unit.create') }}" class="btn btn-outline-primary mb-3">+ Tambah Unit</a>
            <table id="unit" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th class="text-center">No</th>
                        <th class="text-center">Unit</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($units as $unit)
                        <tr>
                            <td class="text-center">{{ $loop->iteration }}</td>
                            <td>{{ ucwords($unit->nama) }}</td>
                            <td class="text-center">
                                <div class="dropdown">
                                    <button class="btn btn-success dropdown-toggle" type="button"
                                        id="dropdownMenuButton{{ $unit->id }}" data-toggle="dropdown"
                                        aria-haspopup="true" aria-expanded="false">
                                        Actions
                                    </button>
                                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton{{ $unit->id }}">
                                        <a class="dropdown-item" href="{{ route('unit.edit', $unit->id) }}">Edit</a>
                                        <a class="dropdown-item" href="{{ route('unit.delete', $unit->id) }}"
                                            data-confirm-delete="true">Hapus</a>
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
            $("#unit").DataTable({
                "responsive": true,
                "autoWidth": false,
                "pageLength": 25,
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
            })
        });
    </script>
@endsection
