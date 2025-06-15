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
                                    <div class="row g-3">
                                        @foreach ($allKriteria as $kriteria)
                                            @php
                                                $monitoring = $item->monitoring->first();
                                                $status = $monitoring->status_monitoring->where('kriteria_id', $kriteria->id)->first();
                                            @endphp

                                            <div class="col-12 col-md-6 col-lg-4">
                                                <div class="card shadow-sm border-0 h-100" style="border-radius: 16px;">
                                                    <div class="card-body text-center d-flex flex-column justify-content-between py-4">
                                                        <div>
                                                            <i class="{{ $status && $status->status === 'completed' ? 'fas fa-check-circle text-success' : 'fas fa-edit text-warning' }} fa-2x mb-2"></i>
                                                            <h6 class="fw-semibold text-dark">{{ $kriteria->nama }}</h6>
                                                        </div>

                                                        <div class="mt-3">
                                                            @if (!$status)
                                                                <form action="{{ route('auditor.tindak-lanjut.isi', ['monitoring' => $monitoring->id, 'kriteria' => $kriteria->id]) }}" method="POST">
                                                                    @csrf
                                                                    <button type="submit" class="btn btn-danger w-100 py-2 rounded-3">
                                                                        Mulai Isi
                                                                    </button>
                                                                </form>
                                                            @else
                                                                <a href="{{ route('auditor.tindak-lanjut.form', ['monitoring' => $monitoring->id, 'kriteria' => $kriteria->id]) }}"
                                                                    class="btn btn-{{ $status->status === 'completed' ? 'success' : 'primary' }} w-100 py-2 rounded-3">
                                                                    {{ $status->status === 'completed' ? 'Sudah Isi' : 'Isi' }}
                                                                </a>
                                                            @endif
                                                        </div>
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

                                    <div class="text-center">
                                        <div class="d-flex justify-content-center gap-2 mb-2">
                                            <a class="btn btn-warning btn-sm" href="{{ route('auditor.tindak-lanjut.edit', ['monitoring' => $monitoring->id]) }}">
                                                <i class="fas fa-edit"></i> Edit
                                            </a>
                                            <a class="btn btn-danger btn-sm" href="{{ route('auditor.tindak-lanjut.delete', ['monitoring' => $monitoring->id]) }}" data-confirm-delete="true">
                                                <i class="fas fa-trash"></i> Hapus
                                            </a>

                                            <form action="{{ route('download.monitoring_rtl', $monitoring->id) }}" method="post" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-primary btn-sm">
                                                    <i class="fa fa-download"></i> Download
                                                </button>
                                            </form>
                                        </div>

                                        <div class = "mb-2">
                                            <button class="btn btn-outline-primary btn-sm btn-fixed-size auditee-btn"
                                                data-bs-toggle="modal" data-bs-target="#auditeeModal"
                                                data-id="{{ $item->id }}"
                                                data-type="{{ $item->type }}"
                                                data-jadwal-id="{{ $jadwal->id }}"><i class="fa fa-eye"></i>
                                                Lihat Auditee Auditor AIMA
                                            </button>
                                        </div>

                                    
                                        @if($monitoring->auditor->isNotEmpty())
                                            @php 
                                                $approval = $monitoring->auditor->first()->pivot->approve ?? false;
                                            @endphp
                                            <form action="{{ route('auditor.tindak-lanjut.approve', ['monitoring' => $monitoring->id, 'auditor' => $auditor->id]) }}" method="post">
                                                @csrf
                                                <button type="submit" class="btn btn-{{ $approval ? 'secondary' : 'success' }} btn-sm btn-fixed-size" {{ $approval ? 'disabled' : '' }}>
                                                    <i class="fas {{ $approval ? 'fa-check-circle' : 'fa-thumbs-up' }}"></i> 
                                                    {{ $approval ? 'Approved' : 'Approve' }}
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                @else 
                                    <button class="btn btn-outline-primary btn-sm btn-fixed-size auditee-btn"
                                            data-bs-toggle="modal" data-bs-target="#auditeeModal"
                                            data-id="{{ $item->id }}"
                                            data-type="{{ $item->type }}"
                                            data-jadwal-id="{{ $jadwal->id }}"><i class="fa fa-eye"></i>
                                        Lihat Auditee Auditor AIMA
                                    </button>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    {{-- Modal Auditee Auditor AIMA --}}
    <div class="modal fade" id="auditeeModal" tabindex="-1" aria-labelledby="auditeeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header bg-dark text-white">
                    <h5 class="modal-title" id="auditeeModalLabel"> Daftar Auditee Auditor AIMA</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <table class="table table-bordered table hover" id="auditeeModal">
                        <thead class="bg-light">
                            <tr>
                                <th>No</th>
                                <th>Fakultas/Unit/Prodi</th>
                                <th>Auditee</th>
                                <th>Auditor</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>
@endsection


@section('style')
    {{-- DataTables --}}
    <link rel="stylesheet" href="{{ asset('plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">

    <style>
        .btn-fixed-size {
            width: 200px; 
        }
    </style>

@endsection

@section('script')
    <!-- DataTables & Plugins -->
    <script src="{{ asset('plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Page specific script -->
    <script>
        $(function() {
            $("#auditor").DataTable({
                "responsive": true,
                "autoWidth": false,
                "columnDefs": [{
                        "width": "5%",
                        "targets": [0]
                    },
                    {
                        "width": "15%",
                        "targets": [1]
                    },
                    {
                        "width": "40%",
                        "targets": [2]
                    },
                    {
                        "width": "40%",
                        "targets": [3]
                    },
                ]
            });
        })
    </script>

    <script>
        $(document).ready(function() {
            let modalTableInitialized = false;

            $('#auditeeModal').on('show.bs.modal', function(event) {
                const button = $(event.relatedTarget);
                const itemId = button.data('id');
                const type = button.data('type');
                const jadwalId = button.data('jadwal-id');
                const modal = $(this);
                const table = modal.find('#auditeeModal')

                //modal.find('tbody').html('<tr><td colspan="4" class="text-center"><i class="fas fa-spinner fa-spin"></i> Memuat data...</td></tr>');
        
                $.ajax({
                    url: '{{ route('auditor.auditee-auditor-aima.show') }}',
                    method: 'GET',
                    data: {
                        item_id: itemId,
                        type: type,
                        jadwal_id: jadwalId
                    },
                    success: function(response) {
                        const tbody = modal.find('tbody');
                        tbody.empty();
                        
                        if(response.data.length > 0) {
                            response.data.forEach((item, index) => {
                                tbody.append(`
                                    <tr>
                                        <td>${index + 1}</td>
                                        <td>${item.unit}</td>
                                        <td>${item.auditees}</td>
                                        <td>${item.auditors}</td>
                                    </tr>
                                `);
                            });
                        } else {
                            tbody.html('<tr><td colspan="4" class="text-center">Tidak ada data ditemukan</td></tr>');
                        }
                        if (!modalTableInitialized) {
                            table.DataTable({
                                responsive: true,
                                autoWidth: false,
                                "columnDefs": [{
                                        "width": "5%",
                                        "targets": [0]
                                    },
                                    {
                                        "width": "25%",
                                        "targets": [1]
                                    },
                                    {
                                        "width": "35%",
                                        "targets": [2]
                                    },
                                    {
                                        "width": "35%",
                                        "targets": [3]
                                    },
                                ]
                            });
                            modalTableInitialized = true;
                        }
                    },
                    error: function() {
                        modal.find('tbody').html('<tr><td colspan="4" class="text-center text-danger">Gagal memuat data</td></tr>');
                    }
                    
                });
            })
        })
    </script>
@endsection
