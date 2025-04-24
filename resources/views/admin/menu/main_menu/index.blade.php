@extends('components.layout.main_layout')

@section('content')
    <div class="card card-dark">
        <div class="card-header">
            <h3 class="card-title title-size">{{ $title }}</h3>
        </div>
        <!-- /.card-header -->
        <div class="card-body">
            <a href="{{ route('menu') }}" class="btn btn-outline-primary mb-3">Kembali</a>
            <a href="{{ route('main_menu.create') }}" class="btn btn-primary mb-3">+ Tambah Main Menu</a>
            <table id="menu" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th class="text-center">No</th>
                        <th class="text-center">Main Menu</th>
                        <th class="text-center">Status</th>
                        <th class="text-center">Menu</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $no = 1;
                    @endphp
                    @foreach ($mainMenus as $mainMenu)
                        <tr>
                            <td class="text-center">{{ $no++ }}</td>
                            <td>{{ $mainMenu->mainmenu }}</td>
                            <td class="text-center">
                                @if ($mainMenu->status == 1)
                                    <span class="badge bg-success">Aktif</span>
                                @else
                                    <span class="badge bg-danger">Tidak Aktif</span>
                                @endif
                            </td>
                            <td>
                                @if ($mainMenu->menu->isNotEmpty())
                                    @foreach ($mainMenu->menu as $menu)
                                        <span class="badge badge-info mb-1">{{ $menu->menu }}</span><br>
                                    @endforeach
                                @else
                                    <span class="badge badge-danger">Tidak Ada Menu</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <div class="dropdown">
                                    <button class="btn btn-success dropdown-toggle" type="button" id="dropdownMenuButton"
                                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        Actions
                                    </button>
                                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                        <a class="dropdown-item" href="{{ route('main_menu.edit', $mainMenu->id) }}">Edit</a>
                                        <a href="{{ route('main_menu.delete', $mainMenu->id) }}" class="dropdown-item"
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
            $("#menu").DataTable({
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
                        "width": "20%",
                        "targets": [2]
                    },
                    {
                        "width": "20%",
                        "targets": [3]
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
