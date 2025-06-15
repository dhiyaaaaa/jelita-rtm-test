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
            <div class="table-container">
                <a href="{{ route('hasil_rtm.index') }}" class="btn btn-outline-dark mb-3">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
                <table id="admin" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th class="text-center">No</th>
                            <th class="text-center">Unit/Fakultas</th>
                            <th class="text-center">Auditan</th>
                            <th class="text-center">Auditor</th>
                            <th class="text-center">Status Isian PTK</th>
                            <th class="text-center">Status Monitoring PTK</th>
                            @role(['pj_universitas'])
                            <th class="text-center">Approve Audit PTK</th>
                            @endrole
                            <th class="text-center">Download Laporan</th>
                        </tr>
                    </thead>
                        @forelse($groupedUpps as $group)
                        <tr>
                            <td class="text-center align-middle">{{ $loop->iteration }}</td>
                            <td class="text-center align-middle">{{ $group->nama }}</td>
                            
                            {{-- Auditan --}}
                            <td class="text-center align-middle">
                                @if ($group->auditees)
                                    @foreach (explode('|', $group->auditees) as $index => $auditee)
                                        <p class="m-0 p-0">{{ $index + 1 }}. {{ $auditee }}</p>
                                    @endforeach
                                @else
                                    <p class="text-muted m-0">-</p>
                                @endif
                            </td>

                            {{-- Auditor --}}
                            <td class="text-center align-middle">
                                @if ($group->auditors)
                                    @foreach (explode('|', $group->auditors) as $index => $auditor)
                                        <p class="m-0 p-0">{{ $index + 1 }}. {{ $auditor }}</p>
                                    @endforeach
                                @else
                                    <p class="text-muted m-0">Belum Ada</p>
                                @endif
                            </td> 

                            {{-- Status RTL Auditee --}}
                            <td class="text-center align-middle">
                                @php
                                    $displayedStatusesRtl = [];
                                @endphp
                                @foreach ($group->items as $item)
                                    @php
                                        $key = $item->nama_kriteria_rtl.$item->status_rtl;
                                        if(in_array($key, $displayedStatusesRtl)) continue;
                                        $displayedStatusesRtl[] = $key;

                                        $status_rtl = $item->status_rtl;
                                        $nama_kriteria_rtl = $item->nama_kriteria_rtl;
                                        $rtl_selesai = $status_rtl === 'completed';
                                        $buat_rtl = $item->rtl_id && !$status_rtl;
                                        $auditee_mulai = $status_rtl && !$rtl_selesai;
                                    @endphp

                                    @if ($buat_rtl)
                                        <span class="badge badge-danger p-1 d-block mb-1">Tindak Lanjut berhasil dibuat</span>
                                    @elseif ($auditee_mulai)
                                        <span class="badge badge-warning p-1 d-block mb-1">Auditan sedang menindaklanjuti temuan {{ $nama_kriteria_rtl }}</span>
                                    @elseif ($rtl_selesai)
                                        <span class="badge badge-success p-1 d-block mb-1">Tindak lanjut temuan {{ $nama_kriteria_rtl }} selesai</span>
                                    @else
                                        <span class="text-muted">Belum Ada</span>
                                    @endif
                                @endforeach
                            </td>

                            {{-- Monitoring PTK Auditor --}}
                            <td class="text-center align-middle">
                                @php
                                    $displayedStatuses = [];
                                @endphp
                                @foreach ($group->items as $item)
                                    @php
                                        $key = $item->nama_kriteria_monitoring.$item->status_monitoring;
                                        if(in_array($key, $displayedStatuses)) continue;
                                        $displayedStatuses[] = $key;

                                        $status_monitoring = $item->status_monitoring;
                                        $nama_kriteria_monitoring = $item->nama_kriteria_monitoring;
                                        $monitoring_selesai = $status_monitoring === 'completed';
                                        $buat_monitoring = $item->monitoring_id && !$status_monitoring;
                                        $auditee_mulai = $status_monitoring && !$monitoring_selesai;
                                    @endphp

                                    @if ($buat_monitoring)
                                        <span class="badge badge-danger p-1 d-block mb-1">Tindak Lanjut berhasil dibuat</span>
                                    @elseif ($auditee_mulai)
                                        <span class="badge badge-warning p-1 d-block mb-1">Auditor sedang memonitoring temuan {{ $nama_kriteria_monitoring }}</span>
                                    @elseif ($monitoring_selesai)
                                        <span class="badge badge-success p-1 d-block mb-1">Tindak lanjut temuan {{ $nama_kriteria_monitoring }} selesai</span>
                                    @else
                                        <span class="text-muted">Belum Ada</span>
                                    @endif
                                @endforeach
                            </td>


                            @role(['pj_universitas'])
                            {{-- Approve audit ptk/rtl ketua Lp3m --}}
                            <td>
                                @php
                                    $hasRtl = $group->items->contains('rtl_id', '!=', null);
                                    $hasMonitoring = $group->items->contains('monitoring_id', '!=', null);
                                    
                                    $rtlId = $group->items->firstWhere('rtl_id', '!=', null)->rtl_id ?? null;
                                    $monitoringId = $group->items->firstWhere('monitoring_id', '!=', null)->monitoring_id ?? null;
                                @endphp

                                {{-- Approve RTL) --}}
                                @if($hasRtl && $isKetuaLP3M && $user == $ketuaLP3MId && (!$approvalRtlKetuaLP3M || !$approvalRtlKetuaLP3M->approve))
                                    <form action="{{ route('lp3m.rtl.approve', ['rtl' => $rtlId, 'user' => $user]) }}" method="post" class="mb-2">
                                        @csrf
                                        <button type="submit" class="btn btn-outline-success w-100">
                                            <i class="fas fa-thumbs-up"></i> Approve Tindak Lanjut
                                        </button>
                                    </form>
                                @elseif($hasRtl && $isKetuaLP3M && $approvalRtlKetuaLP3M && $approvalRtlKetuaLP3M->approve)
                                    <button disabled class="btn btn-secondary w-100 mb-2">
                                        <i class="fas fa-check-circle"></i> Tindak Lanjut Approved
                                    </button>
                                @endif

                                {{-- Approve Monitoring) --}}
                                @if($hasMonitoring && $isKetuaLP3M && $user == $ketuaLP3MId && (!$approvalMonitoringKetuaLP3M || !$approvalMonitoringKetuaLP3M->approve))
                                    <form action="{{ route('lp3m.monitoring.approve', ['monitoring' => $monitoringId, 'user' => $user]) }}" method="post" class="mb-2">
                                        @csrf
                                        <button type="submit" class="btn btn-outline-success w-100">
                                            <i class="fas fa-thumbs-up"></i> Approve Monitoring
                                        </button>
                                    </form>
                                @elseif($hasMonitoring && $isKetuaLP3M && $approvalMonitoringKetuaLP3M && $approvalMonitoringKetuaLP3M->approve)
                                    <button disabled class="btn btn-secondary w-100 mb-2">
                                        <i class="fas fa-check-circle"></i> Monitoring Approved
                                    </button>
                                @endif
                            </td>
                            @endrole
                            
                            {{-- Download --}}
                            <td class="text-center align-middle">
                                <div class="dropdown">
                                    <button class="btn btn-outline-dark dropdown-toggle" type="button"
                                        id="dropdownMenuButton-{{ $loop->iteration }}" 
                                        data-toggle="dropdown" 
                                        aria-haspopup="true"
                                        aria-expanded="false">
                                        <i class="fa fa-download"></i>
                                    </button>
                                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton-{{ $loop->iteration }}">
                                        
                                            @if ($item->rtl_id)
                                                <form action="{{ route('download.rtl', $item->rtl_id) }}" method="post">
                                                    @csrf
                                                    <button type="submit" class="dropdown-item">
                                                        Isian Tindak Lanjut 
                                                    </button>
                                                </form>
                                            @endif
                                            @if ($item->monitoring_id)
                                                <form action="{{ route('download.monitoring_rtl', $item->monitoring_id) }}" method="post">
                                                    @csrf
                                                    <button type="submit" class="dropdown-item">
                                                        Monitoring 
                                                    </button>
                                                </form>
                                            @endif
                                    </div>
                                </div>    
                            </td>                   
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center">Data Tidak Ada</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <!-- /.card-body -->
    </div>
@endsection

@section('style')
    <link rel="stylesheet" href="{{ asset('plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">

    <style>
        /* Fix untuk dropdown di DataTables */
        #admin_wrapper .dropdown-menu {
            position: absolute !important;
            z-index: 1060 !important;
            will-change: transform;
            top: 0;
            left: 0;
            transform: translate3d(0, 38px, 0px);
        }
        
        /* Pastikan tombol dropdown tetap visible */
        .dropdown-toggle::after {
            display: inline-block;
        }
        
        /* Fix untuk dropdown item */
        .dropdown-item {
            white-space: normal;
            padding: 0.25rem 1.5rem;
        }
        
        /* Fix untuk button di dropdown */
        .dropdown-item button {
            background: none;
            border: none;
            width: 100%;
            text-align: left;
            padding: 0.25rem 0;
        }
    </style>
@endsection

@section('script')
    <!-- Pastikan urutan pemanggilan library benar -->
    <script src="{{ asset('plugins/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>

    <script>
        $(document).ready(function() {
            // Inisialisasi DataTables
            var table = $('#admin').DataTable({
                "responsive": false,
                "scrollX": true,
                "autoWidth": false,
                "columnDefs": [
                    { "width": "5%", "targets": 0 },
                    { "width": "15%", "targets": 1 },
                    { "width": "15%", "targets": 2 },
                    { "width": "15%", "targets": 3 },
                    { "width": "15%", "targets": 4 },
                    { "width": "15%", "targets": 5 },
                    { "width": "10%", "targets": 6 },
                    { 
                        "width": "15%", 
                        "targets": 7,
                        "orderable": false,
                        "searchable": false
                    }
                ],
                "drawCallback": function(settings) {
                    // Inisialisasi ulang dropdown setelah tabel di-render
                    $('.dropdown-toggle').dropdown();
                }
            });

            // Handle klik dropdown agar tidak menutup saat memilih
            $(document).on('click', '.dropdown-menu', function(e) {
                e.stopPropagation();
            });

            // Fix untuk dropdown di dalam DataTables
            $('#admin').on('draw.dt', function() {
                $('.dropdown-toggle').dropdown();
            });
        });
    </script>
@endsection