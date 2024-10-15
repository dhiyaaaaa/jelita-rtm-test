@extends('components.layout.main_layout')

@section('content')
    <div class="card card-dark">
        <div class="card-header">
            <h3 class="card-title title-size">{{ $title }}</h3>
        </div>
        <div class="card-body">
            <div class="mb-3">
                <a href="{{ route('jabatan') }}" class="btn btn-outline-secondary">Kembali</a>
                <a href="{{ route('user.create') }}" class="btn btn-outline-primary">+ Tambah User</a>
            </div>

            {{-- Detail Jabatan --}}
            <table class="table mt-3 mb-3">
                <tr>
                    <td class="text-bold" width="7%">
                        Jabatan
                    </td>
                    <td width="3%">:</td>
                    <td>{{ strtoupper($jabatan->nama) }}</td>
                </tr>
                <tr>
                    <td class="text-bold" width="7%">
                        Tipe
                    </td>
                    <td width="3%">:</td>
                    <td>{{ ucwords($jabatan->type) }}</td>
                </tr>
            </table>

            {{-- Table User --}}

            <table id="menu" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th class="text-center">No</th>
                        <th class="text-center">Nama</th>
                        <th class="text-center">Jabatan</th>
                        @if ($jabatan->type == 'prodi')
                            <th class="text-center">Fakultas</th>
                            <th class="text-center">Prodi</th>
                        @elseif ($jabatan->type == 'fakultas')
                            <th class="text-center">Fakultas</th>
                        @endif
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($users as $user)
                        <tr>
                            <td class="text-center">{{ $loop->iteration }}</td>
                            <td>{{ ucwords($user->name) }}</td>
                            <td class="text-center">{{ $user->jabatan[0]->nama }}</td>
                            @if ($jabatan->type == 'prodi')
                                <td class="text-center">{{ $user->prodi[0]->fakultas->nama }}</td>
                                <td class="text-center">{{ $user->prodi[0]->nama }}</td>
                            @elseif ($jabatan->type == 'fakultas')
                                <td class="text-center">{{ $user->fakultas[0]->nama }}</td>
                            @endif
                            <td class="text-center">
                                <div class="dropdown">
                                    <button class="btn btn-success dropdown-toggle" type="button" id="dropdownMenuButton"
                                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        Actions
                                    </button>
                                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                        <a class="dropdown-item" href="{{ route('user.edit', $user->id) }}">Edit</a>
                                        <a class="dropdown-item" href="{{ route('user.delete', $user->id) }}"
                                            data-confirm-delete="true">Hapus</a>
                                    </div>
                                </div>
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
            $("#menu").DataTable({
                "responsive": true,
                "autoWidth": false,
                "columnDefs": [{
                    "width": "20%",
                    "targets": [1]
                }, ]
            });
        });
    </script>
@endsection
