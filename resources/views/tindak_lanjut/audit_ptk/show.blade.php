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
                <div class="table-responsive">
                    <table id="admin" class="table table-bordered table-striped" style="width:100%">
                        <thead>
                            <tr>
                                <th class="text-center" style="width:5%">No</th>
                                <th class="text-center" style="width:20%">Unit/Fakultas</th>
                                <th class="text-center" style="width:15%">Auditan</th>
                                <th class="text-center" style="width:15%">Auditor</th>
                                <th class="text-center" style="width:15%">Status Isian PTK</th>
                                <th class="text-center" style="width:15%">Status Monitoring PTK</th>
                                <th class="text-center" style="width:10%">Approve Audit PTK</th>
                                <th class="text-center" style="width:10%">Download Laporan</th>
                            </tr>
                        </thead>
                        <tbody>
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
                                <td class="text-center align-middle wrap-text">
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
                                            <span class="badge badge-danger p-1 d-block text-wrap">Tindak Lanjut berhasil dibuat</span>
                                        @elseif ($auditee_mulai)
                                            <span class="badge badge-warning p-1 d-block text-wrap">Auditan sedang menindaklanjuti temuan {{ $nama_kriteria_rtl }}</span>
                                        @elseif ($rtl_selesai)
                                            <span class="badge badge-success p-1 d-block text-wrap">Tindak lanjut temuan {{ $nama_kriteria_rtl }} selesai</span>
                                        @else
                                            <span class="text-muted">Belum Ada</span>
                                        @endif
                                    @endforeach
                                </td>

                                {{-- Monitoring PTK Auditor --}}
                                <td class="text-center align-middle wrap-text">
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
                                            <span class="badge badge-danger p-1 d-block text-wrap">Tindak Lanjut berhasil dibuat</span>
                                        @elseif ($auditee_mulai)
                                            <span class="badge badge-warning p-1 d-block text-wrap">Auditor sedang memonitoring temuan {{ $nama_kriteria_monitoring }}</span>
                                        @elseif ($monitoring_selesai)
                                            <span class="badge badge-success p-1 d-block text-wrap">Tindak lanjut temuan {{ $nama_kriteria_monitoring }} selesai</span>
                                        @else
                                            <span class="text-muted">Belum Ada</span>
                                        @endif
                                    @endforeach
                                </td>

                                <td class="text-center align-middle wrap-text">
                                    @php
                                        $hasRtl = $group->items->contains('rtl_id', '!=', null);
                                        $hasMonitoring = $group->items->contains('monitoring_id', '!=', null);
                                        
                                        $rtlId = $group->items->firstWhere('rtl_id', '!=', null)->rtl_id ?? null;
                                        $monitoringId = $group->items->firstWhere('monitoring_id', '!=', null)->monitoring_id ?? null;
                                    @endphp

                                    {{-- Untuk Ketua LP3M --}}
                                    @if($isKetuaLP3M)
                                        {{-- Approve RTL --}}
                                        @if($hasRtl && $user == $ketuaLP3MId && (!$approvalRtlKetuaLP3M || !$approvalRtlKetuaLP3M->approve))
                                            <form action="{{ route('lp3m.rtl.approve', ['rtl' => $rtlId, 'user' => $user]) }}" method="post" class="mb-2">
                                                @csrf
                                                <button type="submit" class="btn btn-outline-success w-100 text-wrap">
                                                    <i class="fas fa-thumbs-up"></i> Approve Tindak Lanjut
                                                </button>
                                            </form>
                                        @elseif($hasRtl && $approvalRtlKetuaLP3M && $approvalRtlKetuaLP3M->approve)
                                            <button disabled class="btn btn-secondary w-100 mb-2 text-wrap">
                                                <i class="fas fa-check-circle"></i> Tindak Lanjut Approved
                                            </button>
                                        @endif

                                        {{-- Approve Monitoring --}}
                                        @if($hasMonitoring && $user == $ketuaLP3MId && (!$approvalMonitoringKetuaLP3M || !$approvalMonitoringKetuaLP3M->approve))
                                            <form action="{{ route('lp3m.monitoring.approve', ['monitoring' => $monitoringId, 'user' => $user]) }}" method="post" class="mb-2">
                                                @csrf
                                                <button type="submit" class="btn btn-outline-success w-100 text-wrap">
                                                    <i class="fas fa-thumbs-up"></i> Approve Monitoring
                                                </button>
                                            </form>
                                        @elseif($hasMonitoring && $approvalMonitoringKetuaLP3M && $approvalMonitoringKetuaLP3M->approve)
                                            <button disabled class="btn btn-secondary w-100 mb-2 text-wrap">
                                                <i class="fas fa-check-circle"></i> Monitoring Approved
                                            </button>
                                        @endif
                                    @else
                                        {{-- Untuk Admin --}}
                                        <div class="d-flex flex-column gap-1">
                                            @if($hasRtl)
                                                @if($approvalRtlKetuaLP3M && $approvalRtlKetuaLP3M->approve)
                                                    <span class="badge badge-success p-1 text-wrap">Tindak Lanjut Approved</span>
                                                @else
                                                    <span class="badge badge-warning p-1 text-wrap">Tindak Lanjut Belum Approved</span>
                                                @endif
                                            @endif
                                            
                                            @if($hasMonitoring)
                                                @if($approvalMonitoringKetuaLP3M && $approvalMonitoringKetuaLP3M->approve)
                                                    <span class="badge badge-success p-1 text-wrap">Monitoring Approved</span>
                                                @else
                                                    <span class="badge badge-warning p-1 text-wrap">Monitoring Belum Approved</span>
                                                @endif
                                            @endif
                                        </div>
                                    @endif
                                </td>
                                
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
                                                    <button type="submit" class="dropdown-item text-wrap">
                                                        Isian Tindak Lanjut 
                                                    </button>
                                                </form>
                                            @endif
                                            @if ($item->monitoring_id)
                                                <form action="{{ route('download.monitoring_rtl', $item->monitoring_id) }}" method="post">
                                                    @csrf
                                                    <button type="submit" class="dropdown-item text-wrap">
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
                                <td colspan="8" class="text-center">Data Tidak Ada</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <!-- /.card-body -->
    </div>
@endsection

@section('style')
    <link rel="stylesheet" href="{{ asset('plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">

    <style>
        /* Styling untuk tabel */
        #admin {
            table-layout: fixed;
            width: 100% !important;
        }
        
        #admin thead th {
            position: sticky;
            top: 0;
            background-color: #343a40;
            color: white;
            z-index: 10;
            vertical-align: middle;
        }
        
        #admin tbody td {
            vertical-align: middle;
        }
        
        /* Text wrapping */
        .wrap-text,
        #admin td.wrap-text,
        #admin .text-wrap {
            white-space: normal !important;
            word-wrap: break-word;
            word-break: break-word;
        }
        
        /* Badge styling with wrapping */
        #admin .badge {
            white-space: normal;
            text-align: center;
            display: inline-block;
            width: 100%;
            margin-bottom: 3px;
            word-break: break-word;
        }
        
        /* Button wrapping */
        #admin .btn {
            white-space: normal;
            word-break: break-word;
        }
        
        /* Dropdown styling */
        #admin_wrapper .dropdown-menu {
            position: absolute !important;
            z-index: 1060 !important;
            min-width: 200px;
        }
        
        .dropdown-item {
            white-space: normal;
            word-break: break-word;
        }
        
        .table-responsive {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
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
                "responsive": true,
                "scrollX": false,
                "autoWidth": false,
                "fixedHeader": true,
                "columnDefs": [
                    { 
                        "width": "5%", 
                        "targets": 0,
                        "className": "dt-center"
                    },
                    { 
                        "width": "20%", 
                        "targets": 1,
                        "className": "dt-center"
                    },
                    { 
                        "width": "15%", 
                        "targets": [2,3],
                        "className": "dt-center"
                    },
                    { 
                        "width": "15%", 
                        "targets": [4,5],
                        "className": "dt-center wrap-text"
                    },
                    { 
                        "width": "10%", 
                        "targets": 6,
                        "className": "dt-center wrap-text"
                    },
                    { 
                        "width": "10%", 
                        "targets": 7,
                        "orderable": false,
                        "searchable": false,
                        "className": "dt-center"
                    }
                ],
                "initComplete": function() {
                    this.api().columns.adjust();
                    
                    $('.dropdown-toggle').dropdown();
                },
                "drawCallback": function(settings) {
                    $('.dropdown-toggle').dropdown();
                }
            });

            $(window).on('resize', function() {
                table.columns.adjust();
            });

            $(document).on('click', '.dropdown-menu', function(e) {
                e.stopPropagation();
            });
        });
    </script>
@endsection