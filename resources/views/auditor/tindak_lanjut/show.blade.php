@php
    use Carbon\Carbon;
@endphp
@extends('components.layout.main_layout')

@section('content')
    <div class="card shadow-lg border-0 rounded-lg">
        <div class="card-header bg-dark text-white">
            <h3 class="card-title text-center">{{ $title }}</h3>
        </div>

        <div class="card-body">
            <a href="{{ route('auditor.tindak-lanjut.index') }}" class="btn btn-outline-secondary mb-3">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>

            <table class="table table-bordered mb-4">
                <tr>
                    <td class="fw-bold" width="15%">Jadwal</td>
                    <td>Monitoring Tindak Lanjut {{ $jadwal->jadwal }}</td>
                </tr>
                <tr>
                    <td class="fw-bold">Periode</td>
                    <td>
                        {{ Carbon::parse($jadwal->tgl_mulai)->translatedFormat('j F Y') }} -
                        {{ Carbon::parse($jadwal->tgl_selesai)->translatedFormat('j F Y') }}
                    </td>
                </tr>
            </table>

            <table id="auditor" class="table table-hover table-striped">
                <thead class="bg-dark text-white text-center">
                    <tr>
                        <th>No</th>
                        <th>Fakultas/Unit</th>
                        <th>Monitoring Tindak Lanjut</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @php $no = 1; @endphp
                    @foreach ($auditee as $item)
                        <tr class="text-center">
                            <td>{{ $no++ }}</td>
                            <td>{{ $item->type === 'fakultas' ? 'Fakultas ' . $item->nama : $item->nama }}</td>
                            <td>
                                @if ($item->monitoring->isNotEmpty())
                                    @php
                                        $monitoring = $item->monitoring->first();
                                        $statusBelumMemenuhi = $monitoring->status_monitoring->where('kriteria.nama', 'Belum Memenuhi')->first();
                                        $statusMemenuhi = $monitoring->status_monitoring->where('kriteria.nama', 'Memenuhi')->first();
                                        $statusMelampaui = $monitoring->status_monitoring->where('kriteria.nama', 'Melampaui')->first();
                                    @endphp
                                    <div class="row g-2 justify-content-center">
                                        @foreach (['Belum Memenuhi' => $statusBelumMemenuhi, 'Memenuhi' => $statusMemenuhi, 'Melampaui' => $statusMelampaui] as $kriteria => $status)
                                            <div class="col-12 col-md-6 col-lg-4">
                                                <div class="card text-center transition-modern shadow border-0" style="max-width: 280px; margin: auto; border-radius: 16px;">
                                                    <div class="card-body py-4">
                                                        <i class="{{ $status && $status->status === 'completed' ? 'fas fa-check-circle text-success' : 'fas fa-edit text-warning' }} fa-3x mb-3"></i>
                                                        <h6 class="fw-bold text-dark">{{ $kriteria }}</h6>
                                                        @if (!$status)
                                                            <form action="{{ route('auditor.tindak-lanjut.isi', ['monitoring' => $monitoring->id, 'kriteria' => $kriteria]) }}"
                                                                method="post" class="d-inline">
                                                                @csrf
                                                                <button type="submit" class="btn btn-danger w-100 py-2 transition-modern">
                                                                    Mulai Isi
                                                                </button>
                                                            </form>
                                                        @else
                                                            @if ($status->status === 'completed')
                                                                <a href="{{ route('auditor.tindak-lanjut.form', ['monitoring' => $monitoring->id, 'kriteria' => $kriteria]) }}"
                                                                    class="btn btn-primary w-100 py-2 transition-modern"> Sudah Isi </a>
                                                            @else
                                                                <a href="{{ route('auditor.tindak-lanjut.form', ['monitoring' => $monitoring->id, 'kriteria' => $kriteria]) }}"
                                                                    class="btn btn-primary w-100 py-2 transition-modern"> Isi </a>
                                                            @endif
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <a href="{{ route('auditor.tindak-lanjut.create', [
                                        'jadwalAudit' => $jadwal->id,
                                        'unit' => $item->id,
                                        'type' => $item->type,
                                    ]) }}" class="btn btn-outline-primary btn-sm">
                                        <i class="fas fa-plus-circle"></i> Tambah Monitoring
                                    </a>
                                @endif
                            </td>
                            <td>
                                @if ($item->monitoring->isNotEmpty())
                                    @php
                                        $monitoring = $item->monitoring->first();
                                       
                                    @endphp

                                    <div class="mt-2 d-flex justify-content-center gap-2">
                                        <a class="btn btn-outline-primary btn-sm" href="{{ route('auditor.tindak-lanjut.edit', ['monitoring' => $monitoring->id]) }}">
                                            <i class="fas fa-edit"></i> Edit
                                        </a>
                                        <a class="btn btn-outline-danger btn-sm" href="{{ route('auditor.tindak-lanjut.delete', ['monitoring' => $monitoring->id]) }}" data-confirm-delete="true">
                                            <i class="fas fa-trash"></i> Hapus
                                        </a>
                                    </div>

                                    @if ($monitoring->auditor->isNotEmpty())
                                        @php $approval = optional($monitoring->auditor->first()->pivot)->approve; @endphp
                                        <form action="{{ route('auditor.tindak-lanjut.approve', ['monitoring' => $monitoring->id, 'auditor' => $auditor->id]) }}" method="post" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-outline-{{ $approval ? 'secondary' : 'success' }} btn-sm" {{ $approval ? 'disabled' : '' }}>
                                                <i class="fas {{ $approval ? 'fa-check-circle' : 'fa-thumbs-up' }}"></i> {{ $approval ? 'Approved' : 'Approve' }}
                                            </button>
                                        </form>
                                    @endif

                                    <form action="{{ route('download.monitoring_rtl', $monitoring->id) }}" method="post" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-outline-dark btn-sm">
                                            <i class="fa fa-download"></i> Download
                                        </button>
                                    </form>
                                @endif
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
