@extends('components.layout.main_layout')

@section('content')
    <div class="card card-dark">
        <div class="card-header">
            <h3 class="card-title title-size">{{ $title }}</h3>
        </div>
        <div class="card-body">
            <div class="mb-3">
                <a href="{{ route('fakultas') }}" class="btn btn-outline-secondary">Kembali</a>
                <a href="{{ route('prodi.create') }}" class="btn btn-outline-primary">+ Tambah Prodi</a>
            </div>

            {{-- Detail Fakultas --}}
            <table class="table">
                <tr>
                    <td class="text-bold" width="7%">
                        Fakultas
                    </td>
                    <td width="3%">:</td>
                    <td>{{ $fakultas->nama }}</td>
                </tr>
            </table>

            {{-- Table Fakultas --}}
            <table id="fakultas" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th class="text-center">No</th>
                        <th class="text-center">Prodi</th>
                        <th class="text-center">Fakultas</th>
                        <th class="text-center">Jenjang</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $no = 1;
                    @endphp
                    @foreach ($prodis as $prodi)
                        <tr>
                            <td class="text-center">{{ $no++ }}</td>
                            <td>{{ ucwords($prodi->nama) }}</td>
                            <td class="text-center">{{ ucwords($prodi->fakultas->nama) }}</td>
                            <td class="text-center">{{ ucwords($prodi->jenjang->nama) }}</td>
                            <td class="text-center">
                                <a href="{{ route('prodi.edit', $prodi->id) }}" class="btn btn-warning">Edit</a>
                                <a href="{{ route('prodi.delete', $prodi->id) }}" class="btn btn-danger"
                                    data-confirm-delete="true">Hapus</a>
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
            $("#fakultas").DataTable({
                "responsive": true,
                "autoWidth": false,
                "columnDefs": [{
                        "width": "30%",
                        "targets": [1]
                    },
                    {
                        "width": "20%",
                        "targets": [2]
                    },
                ]
            });
        });
    </script>
@endsection
