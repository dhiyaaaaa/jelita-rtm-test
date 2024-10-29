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
            {{-- Kembali ke halaman hasil audit --}}
            <a href="{{ route('hasil_audit_prodi.show', ['jadwalAudit' => $jadwal->id]) }}"
                class="btn btn-outline-secondary mt-3">Kembali</a>

            {{-- Detail Daftar Tilik --}}
            <table class="table mt-3 mb-3">
                <tr>
                    <td class="text-bold" width="15%">Prodi</td>
                    <td width="3%">:</td>
                    <td>
                        {{ $units->nama . ' ' . $units->jenjang->nama }}
                    </td>
                </tr>
            </table>

            <table id="daftar-tilik" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th class="text-center">No</th>
                        <th class="text-center">Kode</th>
                        <th class="text-center">Pernyataan</th>
                        <th class="text-center">Jawaban</th>
                        <th class="text-center">Link</th>
                        <th class="text-center">Catatan Auditor</th>
                    </tr>
                </thead>
                <tbody>
                    @if ($daftarTilik->isNotEmpty())
                        @foreach ($daftarTilik as $item)
                            <tr>
                                <td class="text-center">{{ $loop->iteration }}</td>
                                <td class="text-center">{{ $item->form->instrumen->kode }}</td>
                                <td>{{ $item->form->instrumen->pernyataan }}</td>
                                <td>
                                    @foreach ($item->form->jawaban_auditee as $jawaban)
                                        {{ $jawaban->jawaban }}
                                    @endforeach
                                </td>
                                <td>
                                    @foreach ($item->form->link as $link)
                                        {{ $loop->iteration }}. {{ $link->link }}
                                    @endforeach
                                </td>
                                <td>{{ $item->catatan }}</td>

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
            $("#daftar-tilik").DataTable({
                "responsive": true,
                "autoWidth": false,
                "pageLength": 25,
                "columnDefs": [{
                        "width": "5%",
                        "targets": [0]
                    },
                    {
                        "width": "5%",
                        "targets": [1]
                    },
                    {
                        "width": "25%",
                        "targets": [2]
                    },
                    {
                        "width": "25%",
                        "targets": [3]
                    },
                    {
                        "width": "20%",
                        "targets": [4]
                    },
                    {
                        "width": "20%",
                        "targets": [5]
                    },
                ]
            });
        })
    </script>
@endsection
