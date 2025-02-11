@php
    use Carbon\Carbon;
@endphp
@extends('components.layout.main_layout')

@section('content')
    <div class="card card-dark">
        <div class="card-header">
            <h3 class="card-title title-size">{{ $title }}</h3>
        </div>

        <div class="card-body">
            <a href="{{ route('auditor.tindak-lanjut.index') }}" class="btn btn-outline-secondary mb-3">Kembali</a>

            {{-- Jadwal --}}
            <table class="table mb-3">
                <tr>
                    <td class="text-bold" width="10%">Jadwal</td>
                    <td width="5%">:</td>
                    <td>Monitoring Tindak Lanjut {{ $jadwal->jadwal }}</td>
                </tr>
                <tr>
                    <td class="text-bold" width="10%">Periode</td>
                    <td width="5%">:</td>
                    <td>
                        {{ Carbon::parse($jadwal->tgl_mulai)->translatedFormat('j F Y') }} -
                        {{ Carbon::parse($jadwal->tgl_selesai)->translatedFormat('j F Y') }}
                    </td>
                </tr>
                

                <table id="auditor" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th class="text-center">No</th>
                            <th class="text-center">Prodi/Fakultas/Unit</th>
                            <th class="text-center">Monitoring Tindak Lanjut</th>
                        </tr>
                    </thead>

                    <tbody>
                        @php
                            $no =1;
                        @endphp
                        @foreach ($auditee as $item)
                            <tr>
                                    {{-- No --}}
                                <td class="text-center">{{ $no++ }}</td>

                                {{-- Type Auditee --}}
                                <td>
                                    @if ($item->type === 'prodi')
                                        {{ 'Program Studi ' . $item->nama . ' ' . $item->jenjang->nama }}
                                    @elseif ($item->type === 'fakultas')
                                        {{ 'Fakultas ' . $item->nama }}
                                    @else
                                        {{ $item->nama }}
                                    @endif
                                </td>

     
                             <td class="text-center">
                                @if ($item->monitoring->isNotEmpty())
                                    @php
                                        $monitoring = $item->monitoring->first();
                                    @endphp
                                        {{-- Isi monitoring --}}
                                        @if ($monitoring->status_monitoring->isEmpty())
                                            <form
                                                action="{{ route('auditor.tindak-lanjut.isi', ['monitoring' => $monitoring->id]) }}"
                                                method="post" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-outline-info">Isi</button>
                                            </form>
                                        @else
                                            @php
                                                $status = $monitoring->status_monitoring->first();
                                            @endphp
                                            @if ($status->status === 'completed')
                                                <a href="{{ route('auditor.tindak-lanjut.form', $monitoring->id) }}"
                                                    class="btn btn-info">Sudah Isi</a>
                                            @else
                                                <a href="{{ route('auditor.tindak-lanjut.form', $monitoring->id) }}"
                                                    class="btn btn-outline-info">Isi</a>
                                            @endif
                                        @endif

                                        {{-- Edit monitoring --}}
                                        <div class="dropdown d-inline">
                                            <button class="btn btn-outline-warning dropdown-toggle" type="button"
                                                id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true"
                                                aria-expanded="false">
                                                Aksi
                                            </button>
                                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                <a class="dropdown-item"
                                                    href="{{ route('auditor.tindak-lanjut.edit', [
                                                        'monitoring' => $monitoring->id,
                                                    ]) }}">Edit</a>
                                                <a href="{{ route('auditor.tindak-lanjut.delete', [
                                                    'monitoring' => $monitoring->id,
                                                ]) }}"
                                                    class="dropdown-item" data-confirm-delete="true">Hapus</a>
                                            </div>
                                        </div>

                                    @if ($monitoring && $monitoring->auditor->isNotEmpty() && optional($monitoring->auditor->first()->pivot)->approve === false)
                                            <form
                                                action="{{ route('auditor.tindak-lanjut.approve', ['monitoring' => $monitoring->id, 'auditor' => $auditor->id]) }}"
                                                method="post" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-outline-success">Approve</button>
                                            </form>
                                    @elseif ($monitoring && $monitoring->auditor->isNotEmpty() && optional($monitoring->auditor->first()->pivot)->approve === true)
                                        <button disabled="disabled" class="btn btn-secondary">Approved</button>
                                    @endif


                                    <form action="{{ route('download.monitoring_rtl', $monitoring->id) }}" method="post"
                                        class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-outline-dark">
                                            <i class="fa fa-download p-1"></i>
                                        </button>
                                    </form>
                                @else
                                        <a href="{{ route('auditor.tindak-lanjut.create', [
                                            'jadwalAudit' => $jadwal->id,
                                            'unit' => $item->id,
                                            'type' => $item->type,
                                        ]) }}"
                                            class="btn btn-outline-primary">+ Monitoring Tindak Lanjut</a>
                                @endif
                            </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </table>
        </div>
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
            $("#auditee").DataTable({
                "responsive": true,
                "autoWidth": false,
                "columnDefs": [{
                        "width": "5%",
                        "targets": [0]
                    },
                    {
                        "width": "10%",
                        "targets": [1]
                    },
                    {
                        "width": "15%",
                        "targets": [2]
                    },
                    {
                        "width": "20%",
                        "targets": [3]
                    },
                    {
                        "width": "30%",
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
