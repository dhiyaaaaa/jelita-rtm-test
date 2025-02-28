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

            {{-- Auditor --}}
            <div>
                <table id="auditor" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th class="text-center">No</th>
                            <th class="text-center">Jadwal</th>
                            <th class="text-center">Tgl Mulai</th>
                            <th class="text-center">Tgl Selesai</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>

                        @foreach ($jadwalAudit as $item)
                            <tr>
                                <td class="text-center">{{ $loop->iteration }}</td>
                                <td class="text-center">{{ $item->jadwal }}</td>
                                <td class="text-center">{{ Carbon::parse($item->tgl_mulai)->translatedFormat('j F Y') }}
                                </td>
                                <td class="text-center">{{ Carbon::parse($item->tgl_selesai)->translatedFormat('j F Y') }}
                                </td>
                                <td class="text-center">
                                    <a class="btn btn-outline-primary"
                                        href="{{ route('hasil_rtm_fakultas.show', ['jadwalAudit' => $item->id]) }}">
                                        <i class="fa fa-eye"></i> Lihat RTM Fakultas</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
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
            $("#auditor").DataTable({
                "responsive": true,
                "autoWidth": false,
            })
        });
    </script>
@endsection
