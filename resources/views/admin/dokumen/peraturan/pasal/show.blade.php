@extends('components.layout.main_layout')

@section('content')
    <div class="card card-dark">
        <div class="card-header">
            <h3 class="card-title title-size">{{ $title }}</h3>
        </div>
        <div class="card-body">
            <div class="mb-3">
                <a href="{{ route('peraturan.show', $pasal->peraturan->id) }}" class="btn btn-outline-secondary">Kembali</a>
                <a href="{{ route('ayat.create') }}" class="btn btn-outline-primary">+ Tambah Ayat</a>
            </div>

            {{-- Detail Pasal --}}
            <table class="table mt-3 mb-3">
                <tr>
                    <td class="text-bold" width="10%">Peraturan</td>
                    <td width="3%">:</td>
                    <td>{{ $pasal->peraturan->nama }}</td>
                </tr>
                <tr>
                    <td class="text-bold" width="10%">Pasal</td>
                    <td width="3%">:</td>
                    <td>
                        {{ $pasal->pasal }}
                    </td>
                </tr>
                <tr>
                    <td class="text-bold" width="10%">Isi</td>
                    <td width="3%">:</td>
                    <td>
                        {{ $pasal->isi }}
                    </td>
                </tr>
            </table>

            {{-- Table Pasal --}}
            <table id="menu" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th class="text-center">Ayat</th>
                        <th class="text-center">Isi</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $no = 1;
                    @endphp
                    @foreach ($ayat as $item)
                        <tr>
                            <td class="text-center">{{ $item->ayat }}</td>
                            <td>{{ $item->isi }}</td>
                            <td class="text-center">
                                <div class="dropdown">
                                    <button class="btn btn-success dropdown-toggle" type="button" id="dropdownMenuButton"
                                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        Actions
                                    </button>
                                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                        <a class="dropdown-item" href="{{ route('ayat.edit', $item->id) }}">Edit</a>
                                        <a class="dropdown-item" href="{{ route('ayat.delete', $item->id) }}"
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
                        "width": "10%",
                        "targets": [0]
                    },
                    {
                        "width": "70%",
                        "targets": [1]
                    },
                ]
            });
        });
    </script>
@endsection
