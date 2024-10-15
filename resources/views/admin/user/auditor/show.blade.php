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
            <a href="{{ route('auditor') }}" class="btn btn-outline-secondary mb-3">Kembali</a>
            <a href="{{ route('auditor.create', $jadwalAudit->id) }}" class="btn btn-outline-primary mb-3">+ Tambah Auditor</a>
            {{-- Auditor --}}
            <div>
                <table id="auditor" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th class="text-center">No</th>
                            <th class="text-center">Auditor</th>
                            <th class="text-center">Email</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($auditors as $item)
                            @foreach ($item->auditor as $auditor)
                                <tr>
                                    <td class="text-center">{{ $loop->iteration }}</td>
                                    <td class="text-center">{{ $auditor->user->name }}</td>
                                    <td class="text-center">{{ $auditor->user->email }}
                                    </td>
                                    <td class="text-center">
                                        <a class="btn btn-danger"
                                            href="{{ route('auditor.delete', $auditor->id) }}"data-confirm-delete="true">Hapus</a>
                                    </td>
                                </tr>
                            @endforeach
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
                "pageLength": 25,
            })
        });
    </script>
@endsection
