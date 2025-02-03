@extends('components.layout.main_layout')

@section('content')
    <div class="card card-dark">
        <div class="card-header">
            <h3 class="card-title title-size">{{ $title }}</h3>
        </div>
        <div class="card-body">
            <div class="mb-3">
                <a href="{{ route('menu') }}" class="btn btn-outline-secondary">Kembali</a>
                <a href="{{ route('submenu.create', $menu->id) }}" class="btn btn-outline-primary">+ Tambah
                    Submenu</a>
            </div>

            {{-- Detail Menu --}}
            <table class="table mt-3 mb-3">
                <tr>
                    <td class="text-bold" width="10%">Menu</td>
                    <td width="5%">:</td>
                    <td>{{ $menu->menu }}</td>
                </tr>
                <tr>
                    <td class="text-bold" width="10%">Status</td>
                    <td width="5%">:</td>
                    <td>
                        @if ($menu->status == 1)
                            <span class="badge bg-success ">Aktif</span>
                        @else
                            <span class="badge bg-danger ">Tidak Aktif</span>
                        @endif
                    </td>
                </tr>
                <tr>
                    <td class="text-bold" width="10%">Roles</td>
                    <td width="5%">:</td>
                    <td>
                        @foreach ($roles as $role)
                            <span class=" ">
                                <span class="badge badge-info">
                                    {{ strtoupper($role->name) }}
                                </span>
                                &nbsp;
                            </span>
                        @endforeach
                    </td>
                </tr>
            </table>

            {{-- Table Submenu --}}
            <table id="submenu" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th class="text-center">No</th>
                        <th class="text-center">Submenu</th>
                        <th class="text-center">Status</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $no = 1;
                    @endphp
                    @foreach ($submenus as $submenu)
                        <tr>
                            <td class="text-center">{{ $no++ }}</td>
                            <td>{{ $submenu->submenu }}</td>
                            <td class="text-center">
                                @if ($submenu->status == 1)
                                    <span class="badge bg-success">Aktif</span>
                                @else
                                    <span class="badge bg-danger">Tidak Aktif</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <a href="{{ route('submenu.edit', ['menu' => $menu->id, 'submenu' => $submenu->id]) }}"
                                    class="btn btn-warning">Edit</a>
                                <a href="{{ route('submenu.delete', ['menu' => $menu->id, 'submenu' => $submenu->id]) }}"
                                    class="btn btn-danger" data-confirm-delete="true">Hapus</a>
                            </td>
                        </tr>
                    @endforeach

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
            $("#submenu").DataTable({
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
                    },
                    {
                        "width": "20%",
                        "targets": [3]
                    }
                ]
            });
        });
    </script>
@endsection
