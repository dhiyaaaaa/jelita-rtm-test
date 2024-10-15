@extends('components.layout.main_layout')

@section('content')
    <div class="card card-dark">
        <div class="card-header">
            <h3 class="card-title title-size">{{ $title }}</h3>
        </div>
        <div class="card-body">
            <div class="mb-3">
                <a href="{{ route('standar') }}" class="btn btn-outline-secondary">Kembali</a>
                <a href="{{ route('kategori.create') }}" class="btn btn-outline-primary">+ Tambah Kategori</a>

                {{-- Detail Standar --}}
                <table class="table mt-3">
                    <tr>
                        <td class="text-bold" width="10%">Standar</td>
                        <td width="3%">:</td>
                        <td>{{ $standar->nama }}</td>
                    </tr>
                    <tr>
                        <td class="text-bold" width="10%">Peraturan</td>
                        <td width="3%">:</td>
                        <td>{{ $standar->peraturan->nama }}</td>
                    </tr>
                </table>

                {{-- Table Kategori --}}
                <table id="menu" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th class="text-center">No</th>
                            <th class="text-center">Kategori</th>
                            <th class="text-center">Kode</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $no = 1;
                        @endphp
                        @foreach ($kategori as $item)
                            <tr>
                                <td class="text-center">{{ $no++ }}</td>
                                <td>{{ ucwords($item->nama) }}</td>
                                <td class="text-center">{{ $item->kode }}</td>
                                <td class="text-center">
                                    <div class="dropdown">
                                        <button class="btn btn-success dropdown-toggle" type="button"
                                            id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true"
                                            aria-expanded="false">
                                            Actions
                                        </button>
                                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                            <a class="dropdown-item"
                                                href="{{ route('kategori.edit', $item->id) }}">Edit</a>
                                            <a class="dropdown-item" href="{{ route('kategori.delete', $item->id) }}"
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
                    "width": "40%",
                    "targets": [1]
                }, ]
            });
        });
    </script>
@endsection
