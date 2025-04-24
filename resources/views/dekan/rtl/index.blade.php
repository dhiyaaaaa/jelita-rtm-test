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
            <table id="auditee" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th class="text-center">No</th>
                        <th class="text-center">Jadwal</th>
                        <th class="text-center">Periode</th>
                        <th class="text-center">Tindak Lanjut PTK</th>
                        <th class="text-center">Monitoring Tindak Lanjut PTK</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($jadwal as $item)
                        <tr>
                            {{-- No --}}
                            <td class="text-center align-middle">{{ $loop->iteration }}</td>

                            {{-- Jadwal --}}
                            <td class="text-center align-middle">
                                {{ $item->jadwal }}
                            </td>

                            {{-- Periode --}}
                            <td class="text-center align-middle">
                                {{ Carbon::parse($item->tgl_mulai)->translatedFormat('j F Y') }} -
                                {{ Carbon::parse($item->tgl_selesai)->translatedFormat('j F Y') }}
                            </td>

                            {{-- RTL --}}
                            <td class="text-center">
                                @if ($item->rtl->isNotEmpty())
                                    @php
                                        $rtl = $item->rtl->first();
                                    @endphp
                                    <a href="{{ route('dekan.rtl.show', $rtl->id) }}"
                                        class="btn btn-outline-primary">Lihat</a>
                                    <form action="{{ route('download.rtl', $rtl->id) }}" method="post"
                                        class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-outline-dark">
                                            <i class="fa fa-download p-1"></i>
                                        </button>
                                    </form>
                                @else
                                    <button class="btn btn-outline-primary btn-fixed-size tindak-lanjut-btn"
                                        data-jadwal_audit_id="{{ $item->id }}">
                                        +Tindak Lanjut
                                    </button>
                                @endif
                            </td>

                            {{-- Monitoring --}}
                            <td class="text-center align-middle">
                                @if ($item->monitoring->isNotEmpty())
                                    @php
                                        $monitoring = $item->monitoring->first();
                                    @endphp
                                    {{-- Approve monitoring --}}
                                    @php
                                        $monitoring_auditee = $monitoring->auditee->first();
                                    @endphp
                                    @if ($monitoring_auditee)
                                        @if ($monitoring_auditee->pivot->approve == 0)
                                            @if (!$item->expired)
                                                <form
                                                    action="/"
                                                    method="post" class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="btn btn-outline-primary">Approve</button>
                                                </form>
                                            @else
                                                <button disabled="disabled" class="btn btn-secondary">Not Approved</button>
                                            @endif
                                        @else
                                            <button disabled="disabled" class="btn btn-secondary">Approved</button>
                                        @endif
                                    @endif

                                    {{-- Download monitoring --}}
                                    @if ($monitoring)
                                        <form action="{{ route('download.monitoring_rtl', $monitoring->id) }}" method="post"
                                            class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-outline-dark">
                                                <i class="fa fa-download p-1"></i>
                                            </button>
                                        </form>
                                    @endif
                                @else
                                    <a href="#" class="btn disabled">Belum Ada</a>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal Konfirmasi Pembuatan RTL -->
    <div class="modal fade" id="konfirmasiModal" tabindex="-1" aria-labelledby="konfirmasiModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="konfirmasiModalLabel">Konfirmasi Tindak Lanjut PTK</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Apakah Anda ingin menindaklanjuti permintaan tindakan koreksi?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-primary" id="konfirmasiTindakLanjut">Ya, Lanjutkan</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('style')
    <!-- DataTables -->
    <link rel="stylesheet" href="{{ asset('plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">

    <style>
        .btn-fixed-size {
            width: 120px; 
            height: 38px; 
            display: flex;
            align-items: center;
            justify-content: center;
            white-space: nowrap; 
        }

        .dropdown .btn-fixed-size {
            width: 120px; 
        }

    </style>
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
                "columnDefs": [
                    { "width": "5%", "targets": [0] },
                    { "width": "10%", "targets": [1] },
                    { "width": "15%", "targets": [2] },
                    { "width": "20%", "targets": [3] },
                    { "width": "20%", "targets": [4] }
                ]
            });
        });
    </script>

    <script>
        $(document).ready(function() {
            $('.tindak-lanjut-btn').click(function() {
                let auditId = $(this).data('jadwal_audit_id');

                console.log("Audit ID:", auditId);

                $('#konfirmasiModal').modal('show');

                $('#konfirmasiTindakLanjut').off('click').on('click', function() {
                    $.ajax({
                        url: "{{ route('dekan.rtl.store') }}",
                        method: "POST",
                        data: {
                            _token: "{{ csrf_token() }}",
                            jadwal_audit_id: auditId
                        },
                        success: function(response) {
                            location.reload();
                        },
                        error: function(xhr) {
                            alert("Gagal menindaklanjuti: " + xhr.responseText);
                        }
                    });
                });
            });
        });

    </script>
@endsection