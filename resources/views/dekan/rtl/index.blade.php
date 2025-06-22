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
            <div class="">
                <a href="{{ asset('user_manual/Auditan_PTK.pdf') }}" target="_blank" class="btn btn-outline-info mr-2 mb-3">
                    <i class="fa fa-book mr-2"></i> User Manual
                </a>
            </div>
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
                            <td class="text-center align-middle">
                                @if ($item->rtl->isNotEmpty())
                                    @php
                                        $rtl = $item->rtl->first();
                                    @endphp
                                    <a href="{{ route('dekan.rtl.show', $rtl->id) }}"
                                        class="btn btn-outline-primary">Lihat</a>
                                    <a href="{{ route('dekan.rtl.delete', ['rtl' => $rtl->id,]) }}"
                                        class="btn btn-outline-danger"  data-confirm-delete="true">
                                        <i class="fas fa-trash"></i>Hapus</a>
                                @else
                                    <div class="d-flex justify-content-center">
                                        <button class="btn btn-outline-primary btn-fixed-size tindak-lanjut-btn"
                                            data-jadwal_audit_id="{{ $item->id }}">
                                            +Tindak Lanjut
                                        </button>
                                    </div>
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
                                            <form
                                                action="{{ route('dekan.tindak-lanjut.approve', ['monitoring' =>$monitoring->id, 'auditee' => $monitoring->auditee->first()->pivot->auditee_id]) }}"
                                                method="post" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-outline-primary">Approve</button>
                                            </form>
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
    <div class="modal fade" id="konfirmasiModal" tabindex="-1" role="dialog" aria-labelledby="konfirmasiModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="konfirmasiModalLabel">Konfirmasi Tindak Lanjut PTK</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    Apakah Anda ingin menindaklanjuti permintaan tindakan koreksi?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="button" id="konfirmasiTindakLanjut" class="btn btn-primary">
                        <span class="spinner-border spinner-border-sm d-none" id="tindaklanjut-spinner" role="status" aria-hidden="true"></span>
                        <span id="tindaklanjut-text">Simpan</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('style')
    <!-- DataTables -->
    <link rel="stylesheet" href="{{ asset('plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
    {{-- <link rel="stylesheet" href="{{ asset('plugins/fontawesome-free/css/all.min.css') }}"> --}}

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
    {{-- <script src="{{ asset('plugins/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script> --}}
    <script src="{{ asset('plugins/sweetalert2/sweetalert2.all.min.js') }}"></script>
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
        // Handle Tindak Lanjut
        $(document).on('click', '.tindak-lanjut-btn', function() {
            currentActionData = {
                id: $(this).data('id'),
                fakultas_id: $(this).data('fakultas_id'),
                unit_id: $(this).data('unit_id'),
                prodi_id: $(this).data('prodi_id'),
                jadwal_audit_id: $(this).data('jadwal_audit_id')
            };
            $('#konfirmasiModal').modal('show');
        });
        // Konfirmasi Tindak Lanjut
            $('#konfirmasiTindakLanjut').click(function() {
                const $btn = $(this);
                const $spinner = $('#tindaklanjut-spinner');
                const $text = $('#tindaklanjut-text');
                
                $spinner.removeClass('d-none');
                $text.addClass('d-none');
                $btn.prop('disabled', true);

                $.ajax({
                    url: "{{ route('dekan.rtl.store') }}",
                    method: "POST",
                    data: {
                        _token: "{{ csrf_token() }}",
                        jadwal_id: currentActionData.id,
                        fakultas_id: currentActionData.fakultas_id,
                        unit_id: currentActionData.unit_id,
                        jadwal_audit_id: currentActionData.jadwal_audit_id
                    },
                    success: function(response) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: 'Tindak lanjut berhasil dibuat',
                            showConfirmButton: false,
                            timer: 1500
                        });
                        setTimeout(() => location.reload(), 1500);
                    },
                    error: function(xhr) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal!',
                            text: xhr.responseJSON.message || 'Gagal membuat tindak lanjut',
                            confirmButtonText: 'Tutup'
                        });
                    },
                    complete: function() {
                        $spinner.addClass('d-none');
                        $text.removeClass('d-none');
                        $btn.prop('disabled', false);
                        $('#konfirmasiModal').modal('hide');
                    }
                });
            });
    </script>
@endsection