@extends('components.layout.main_layout')

@section('content')
    <div class="card card-dark">
        <div class="card-header">
            <h3 class="card-title title-size">{{ $title }}</h3>
        </div>
        <!-- /.card-header -->
        <div class="card-body">
            <div class="mb-3">
                <a href="{{ route('standar.create') }}" class="btn btn-outline-primary ">+ Tambah Standar</a>
                <a href="{{ route('kategori.create') }}" class="btn btn-outline-primary">+ Tambah Kategori</a>
            </div>

            <table id="standar" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th class="text-center">No</th>
                        <th class="text-center">Standar</th>
                        <th class="text-center">Peraturan</th>
                        <th class="text-center">Kode</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $no = 1;
                    @endphp
                    @foreach ($standar as $item)
                        <tr>
                            <td class="text-center">{{ $no++ }}</td>
                            <td>{{ ucwords($item->nama) }}</td>
                            <td class="text-center">{{ $item->peraturan->nama }}</td>
                            <td class="text-center">{{ $item->kode }}</td>
                            <td class="text-center">
                                <div class="dropdown">
                                    <button class="btn btn-success dropdown-toggle" type="button" id="dropdownMenuButton"
                                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        Actions
                                    </button>
                                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                        <a class="dropdown-item" href="{{ route('standar.show', $item->id) }}">Lihat</a>
                                        <a class="dropdown-item" href="{{ route('standar.edit', $item->id) }}">Edit</a>
                                        <a class="dropdown-item" href="{{ route('standar.delete', $item->id) }}"
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
            $("#standar").DataTable({
                "responsive": true,
                "autoWidth": false,
                "columnDefs": [{
                    "width": "30%",
                    "targets": [2]
                }]
            });
        });
    </script>
@endsection
