@php
    use Carbon\Carbon;
@endphp
@extends('components.layout.main_layout')

@section('content')
    {{-- INFO RTM --}}
    <div class="card shadow border-0" style="background: linear-gradient(135deg, #6694ea, #614ba2); color: white;">
        <div class="card-header border-0">
            <h3 class="card-title title-size text-white">Tindak Lanjut Permintaan Tindakan Koreksi</h3>
        </div>

        <div class="card-body">
            <a href="{{ route('dekan.rtl.index') }}" class="btn btn-outline-light mb-3">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>

            <table class="table table-borderless text-white">
                <tr>
                    <td class="fw-bold" width="10%">Jadwal</td>
                    <td width="5%">:</td>
                    <td>{{ $rtmJadwal->jadwal_audit->jadwal }}</td>
                </tr>
                <tr>
                    <td class="fw-bold">Periode</td>
                    <td>:</td>
                    <td>
                        {{ Carbon::parse($rtmJadwal->jadwal_audit->tgl_mulai)->translatedFormat('j F Y') }} - 
                        {{ Carbon::parse($rtmJadwal->jadwal_audit->tgl_selesai)->translatedFormat('j F Y') }}
                    </td>
                </tr>
            </table>
        </div>
    </div>

    <div class="card shadow-lg border-0 rounded-lg">
        <div class="card-body">

            <table id="rtm" class="table table-hover table-striped">
                <thead class="bg-dark text-white text-center">
                    <tr>
                        <th>No</th>
                        <th>Fakultas/Unit</th>
                        <th>Tindak Lanjut</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @php 
                        $no = 1; 
                    @endphp
                    @foreach ($units as $index => $item)
                        <tr class="text-center">
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $item->nama }}</td>
                            <td>
                                @if($item->rtm_rtl_id)
                                    <span class="badge bg-success">Ada</span>
                                @else
                                    <span class="badge bg-secondary">Tidak Ada</span>
                                @endif
                            <td>
                                @if($item->rtm_rtl_id)
                                    @if(!empty($item->status))
                                        @if($item->status === 'completed')
                                            <a href="{{ route('admin.rtm-rtl.form', [$item->rtm_rtl_id]) }}"
                                                class="btn btn-primary btn-fixed-size">Sudah Isi</a>
                                        @else
                                            <a href="{{ route('admin.rtm-rtl.form', [$item->rtm_rtl_id]) }}"
                                                class="btn btn-outline-primary btn-fixed-size">Isi</a>
                                        @endif
                                    @else
                                        <form action="{{ route('admin.rtm-rtl.isi', [$item->rtm_rtl_id]) }}"
                                            method="post" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-outline-primary btn-fixed-size">Isi</button>
                                        </form>
                                    @endif
                                @else
                                    <button class="btn btn-outline-primary btn-fixed-size tindak-lanjut-btn" 
                                        data-fakultas_id="{{ $item->jenis_unit === 'fakultas' ? $item->id : '' }}"
                                        data-unit_id="{{ $item->jenis_unit === 'unit' ? $item->id : '' }}"
                                        data-jadwal_audit_id="{{ $rtmJadwal->jadwal_audit_id }}">
                                        +Tindak Lanjut
                                    </button>
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
                    <h5 class="modal-title" id="konfirmasiModalLabel">Konfirmasi Tindak Lanjut </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Apakah Anda ingin membuat rekomendasi dan permintaan tindakan koreksi?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-primary" id="konfirmasiTindakLanjut">
                        <span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                        <span class="btn-text">Ya, Lanjutkan</span>
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
    $("#rtm").DataTable({
        "responsive": true,
        "autoWidth": false,
        "columnDefs": [{
                "width": "10%",
                "targets": [0]
            },
            {
                "width": "30%",
                "targets": [1]
            },
            {
                "width": "30%",
                "targets": [2]
            },
            {
                "width": "30%",
                "targets": [3]
            },
        ]
    });
})
</script>

<script>
    $(document).ready(function() {
        $(document).on('click', '.tindak-lanjut-btn', function() {
            console.log("Tombol diklik");
            let auditId = $(this).data('jadwal_audit_id');
            let fakultasId = $(this).data('fakultas_id');
            let unitId = $(this).data('unit_id');

            console.log("Audit ID:", auditId);

            $('#konfirmasiModal').modal('show');

            $('#konfirmasiTindakLanjut').off('click').on('click', function() {
                const $btn = $(this);
                const $spinner = $btn.find('.spinner-border');
                const $text = $btn.find('.btn-text');

                $spinner.removeClass('d-none');
                $text.addClass('d-none');
                $btn.prop('disabled', true);

                $.ajax({
                    url: "{{ route('admin.rtm-rtl.store') }}",
                    method: "POST",
                    data: {
                        _token: "{{ csrf_token() }}",
                        jadwal_audit_id: auditId,
                        fakultas_id: fakultasId,
                        unit_id: unitId,
                        jadwal_id: "{{ $rtmJadwal->id }}"
                    },
                    
                    success: function(response) {
                        location.reload();
                    },
                    error: function(xhr) {
                        alert("Gagal menindaklanjuti: " + xhr.responseText);
                    },

                    complete: function(){
                        $spinner.addClass('d-none');
                        $text.removeClass('d-none');
                        $btn.prop('disabled', false);
                    }
                });
            });
        });
    });

</script>
@endsection
