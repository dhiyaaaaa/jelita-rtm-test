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

            {{-- Jadwal Audit --}}
            <div>
                <div class="mb-3">
                    <a href="{{ route('jadwal_audit.create') }}" class="btn btn-outline-primary mr-2">+ Tambah Jadwal</a>

                    <a href="{{ route('jadwal_audit.edit_setting') }}" class="btn btn-dark"><i class="fa fa-wrench"></i>
                        Setting</a>
                </div>
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
                        @foreach ($jadwalAudit as $index => $item)
                            <tr>
                                <td class="text-center">{{ $loop->iteration }}</td>
                                <td class="text-center">{{ $item->jadwal }}
                                </td>
                                <td class="text-center">{{ Carbon::parse($item->tgl_mulai)->translatedFormat('j F Y') }}
                                </td>
                                <td class="text-center">{{ Carbon::parse($item->tgl_selesai)->translatedFormat('j F Y') }}
                                </td>
                                <td class="text-center">
                                    @if ($index !== count($jadwalAudit) - 1)
                                        <form action="{{ route('jadwal_audit.import', $item->id) }}" method="post"
                                            class="d-inline">
                                            @csrf
                                            @method('PUT')
                                            <button type="submit"
                                                class="btn {{ !$item->import ? 'btn-info' : 'btn-danger' }}">
                                                {{ !$item->import ? 'Aktifkan Fitur Import' : 'Matikan Fitur Import' }}
                                            </button>
                                        </form>
                                    @endif

                                    <form action="{{ route('jadwal_audit.fitur_auditor', $item->id) }}" method="post"
                                        class="d-inline">
                                        @csrf
                                        @method('PUT')
                                        <button type="submit"
                                            class="btn {{ !$item->fitur_auditor ? 'btn-secondary' : 'btn-danger' }}">
                                            {{ !$item->fitur_auditor ? 'Aktifkan Fitur Auditor' : 'Matikan Fitur Auditor' }}
                                        </button>
                                    </form>

                                    @if ($item->assessment_form->isEmpty())
                                        <a href="{{ route('assessment.create', $item->id) }}"
                                            class="btn btn-outline-info">+
                                            Assessment</a>
                                    @else
                                        <a href="{{ route('assessment.edit', $item->id) }}"
                                            class="btn btn-outline-warning">Ubah
                                            Assessment</a>
                                    @endif

                                    <div class="dropdown d-inline">
                                        <button class="btn btn-success dropdown-toggle" type="button"
                                            id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true"
                                            aria-expanded="false">
                                            Actions
                                        </button>
                                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                            <a class="dropdown-item"
                                                href="{{ route('jadwal_audit.show', $item->id) }}">Lihat</a>
                                            <a class="dropdown-item"
                                                href="{{ route('jadwal_audit.edit', $item->id) }}">Edit</a>
                                            <a href="{{ route('jadwal_audit.delete', $item->id) }}" class="dropdown-item"
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
        <!-- /.card-body -->
    </div>
@endsection


@section('style')
    <!-- DataTables -->
    <link rel="stylesheet" href="{{ asset('plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">
@endsection

@section('script')
    <!-- DataTables & Plugins -->
    <script src="{{ asset('plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>



    <!-- Page specific script -->
    <script>
        $(function() {
            $("#auditor").DataTable({
                "responsive": true,
                "autoWidth": false,
                "pageLength": 25,
                "columnDefs": [{
                        "width": "5%",
                        "targets": [0]
                    },
                    {
                        "width": "20%",
                        "targets": [1]
                    },
                    {
                        "width": "15%",
                        "targets": [2]
                    },
                    {
                        "width": "15%",
                        "targets": [3]
                    },
                ]
            })
        });
    </script>
@endsection
