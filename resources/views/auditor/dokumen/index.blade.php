@php
    use Carbon\Carbon;
@endphp
@extends('components.layout.main_layout')

@section('content')
    <div class="card card-dark">
        <div class="card-header">
            <h3 class="card-title title-size">{{ $title }}</h3>
        </div>
        <!-- /.card-header -->
        <div class="card-body">
            <table id="auditor" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th class="text-center">No</th>
                        <th class="text-center">Jadwal</th>
                        <th class="text-center">Periode</th>
                        <th class="text-center">Status</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @if (!is_null($jadwal) && $jadwal->count() > 0)
                        @foreach ($jadwal as $index => $item)
                            <tr>
                                {{-- No --}}
                                <td class="text-center align-middle">{{ $loop->iteration }}</td>

                                {{-- Jadwal --}}
                                <td class="text-center align-middle">
                                    {{ $item->jadwal_audit->jadwal }}
                                </td>

                                {{-- Periode --}}
                                <td class="text-center align-middle">
                                    {{ Carbon::parse($item->jadwal_audit->tgl_mulai)->translatedFormat('j F Y') }} -
                                    {{ Carbon::parse($item->jadwal_audit->tgl_selesai)->translatedFormat('j F Y') }}
                                </td>

                                {{-- Status --}}
                                <td class="text-center align-middle">
                                    @if (!$item->jadwal_audit->expired)
                                        <span class="badge badge-success">Terbuka</span>
                                    @else
                                        <span class="badge badge-danger">Tertutup</span>
                                    @endif
                                </td>

                                {{-- Aksi --}}
                                <td class="text-center align-middle">
                                    <a href="{{ route('auditor.dokumen.show', $item->jadwal_audit->id) }}"
                                        class="btn btn-info">Lihat</a>
                                </td>
                            </tr>
                        @endforeach
                    @endif
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
    {{-- <link rel="stylesheet" href="{{ asset('plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}"> --}}
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
            $("#auditor").DataTable({
                "responsive": true,
                "autoWidth": false,
            });
        })
    </script>
@endsection
