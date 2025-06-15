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
            <a href="{{ route('dekan.jadwal-rtm.create')}}" class="btn btn-outline-primary mr-2 mb-3">Buat Agenda RTM</a>
            <div class="">
                <a href="{{ asset('user_manual/RTM_Fakultas.pdf') }}" target="_blank" class="btn btn-outline-info mr-2 mb-3">
                    <i class="fa fa-book mr-2"></i> User Manual
                </a>
            </div>

            <table id="rtm" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th class="text-center" style="width: 5%">No</th>
                        <th class="text-center" style="width: 15%">Agenda</th>
                        <th class="text-center" style="width: 15%">Tanggal</th>
                        <th class="text-center" style="width: 15%">Waktu</th>
                        <th class="text-center" style="width: 15%">Periode Audit</th>
                        <th class="text-center" style="width: 10%">Lampiran</th>
                        <th class="text-center" style="width: 10%">RTL RTM</th>
                        <th class="text-center" style="width: 15%">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $no = 1;
                    @endphp
                        @foreach ($rtmJadwal as $index => $item)
                            <tr>
                                <td class="text-center">{{ $no++ }}</td>
                                <td class="text-center">{{ $item->agenda }}</td>
                                <td class="text-center">{{ Carbon::parse($item->tanggal)->translatedFormat('l, j F Y') }}</td>
                                <td class="text-center">{{ Carbon::parse($item->jam_mulai)->translatedFormat('H:i') }} - {{ Carbon::parse($item->jam_selesai)->translatedFormat('H:i') }}</td>
                                <td class="text-center">{{ $item->jadwal_audit->jadwal }}</td>


                                {{-- Lampiran --}}
                                <td class="text-center">
                                    <button class="btn btn-outline-primary btn-sm btn-fixed-size lampiran-btn"
                                            data-bs-toggle="modal" data-bs-target="#lampiranModal"
                                            data-id="{{ $item->id }}">
                                        +Lampiran
                                    </button>
                                </td>

                                {{-- Tindak Lanjut RTM --}}
                                <td class="text-center">
                                    @if ($item->rtm_rtl->isNotEmpty())
                                        @php
                                            $rtmRtl = $item->rtm_rtl->first();
                                        @endphp
                                        <a href="{{ route('dekan.rtm-rtl.show', ['rtmRtl' => $rtmRtl->id]) }}"
                                            class="btn btn-outline-primary">Lihat</a>
                                    @else
                                        <button class="btn btn-outline-primary btn-fixed-size tindak-lanjut-btn" 
                                            data-id="{{ $item->id }}" 
                                            data-fakultas_id="{{ $item->fakultas_id }}"
                                            data-unit_id="{{ $item->unit_id }}"  
                                            data-jadwal_audit_id="{{ $item->jadwal_audit_id }}">
                                            +Tindak Lanjut
                                        </button>
                                    @endif
                                </td>
                            

                            {{-- Aksi --}}
                            <td class="text-center">
                                <div class="dropdown">
                                    <button class="btn btn-outline-primary btn-sm btn-fixed-size dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                        Aksi
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li>
                                            <a class="dropdown-item" href="{{ route('dekan.jadwal-rtm.show', $item->id) }}">
                                                <i class="fas fa-file"></i> Lihat Agenda
                                            </a>
                                        </li>

                                        <li>
                                            <a class="dropdown-item text-danger delete-btn" href="javascript:void(0)" data-id="{{ $item->id }}">
                                                <i class="fas fa-trash-alt"></i> Hapus
                                            </a>
                                            <form id="delete-form-{{ $item->id }}" action="{{ route('dekan.jadwal-rtm.destroy', $item->id) }}" method="POST" style="display: none;">
                                                @csrf
                                                @method('DELETE')
                                            </form>
                                        </li>
                                    </ul>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    {{-- Modal Lampiran --}}
    <div class="modal fade" id="lampiranModal" tabindex="-1" aria-labelledby="lampiranModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="lampiranModalLabel">Lampiran RTM</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="lampiranForm" method="POST" action="{{ route('dekan.lampiran-rtm.store') }}" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="rtm_jadwal_id" id="rtm_jadwal_id">

                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="undangan" class="form-label">Upload File Undangan</label>
                            <small class="form-text text-muted" style="margin: -10px 0 8px 0">Upload file pdf dengan ukuran maks. 2mb</small>
                            <input type="file" class="form-control" id="undangan" name="undangan" required>
                        </div>
                        <div class="mb-3">
                            <label for="presensi" class="form-label">Upload File Presensi</label>
                            <small class="form-text text-muted" style="margin: -10px 0 8px 0">Upload file pdf dengan ukuran maks. 2mb</small>
                            <input type="file" class="form-control" id="presensi" name="presensi" required>
                        </div>
                        <div class="mb-3">
                            <label for="dokumentasi" class="form-label">Upload File Dokumentasi</label>
                            <small class="form-text text-muted" style="margin: -10px 0 8px 0">Upload file pdf dengan ukuran maks. 5mb</small>
                            <input type="file" class="form-control" id="dokumentasi" name="dokumentasi" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                        <button type="submit" id="save-btn" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Konfirmasi Pembuatan RTMRTL-->
    <div class="modal fade" id="konfirmasiModal" tabindex="-1" aria-labelledby="konfirmasiModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="konfirmasiModalLabel">Konfirmasi Tindak Lanjut</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Apakah Anda ingin menindaklanjuti hasil audit ini?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
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
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">

    <style>
        .dropdown-item i {
            margin-right: 5px; 
        }

        .dropdown-item:hover {
            background-color: #f8f9fa; 
        }
    </style>

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
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Page specific script -->
    <script>
        $(function() {
            $("#rtm").DataTable({
                "responsive": true,
                "autoWidth": false,
                "pageLength": 25,
                "columnDefs": [
                ]
            });
        });
    </script>

    <script>
        $(document).ready(function() {
            $('.lampiran-btn').click(function() {
                let jadwalId = $(this).data('id');
                $('#rtm_jadwal_id').val(jadwalId); // Update input rtm_jadwal_id
                $('#lampiranModal').modal('show');

                $.ajax({
                    url: "/lampiran-rtm/" + jadwalId + "/edit",
                    method: "GET",
                    success: function(response) {
                        if (response){
                            $('undangan'). val(response.undangan).prop('disabled', true);
                            $('presensi'). val(response.presensi).prop('disabled', true);
                            $('dokumentasi'). val(response.dokumentasi).prop('disabled', true);
                            $('jadwalId'). val(jadwalId);
                        } else {
                            $('#lampiranForm')[0].reset();
                            $('#undangan, #presensi, #dokumentasi').prop('disabled', false);
                        }
                        $('#lampiranModal').modal('show');
                    },
                    error: function() {
                        $('#lampiranForm')[0].reset();
                        $('#undangan, #presensi, #dokumentasi').prop('disabled', false);
                        $('#jadwalId').val(jadwalId);
                        $('#lampiranModal').modal('show');
                    }
                }); 
            });

            // Submit form lampiran
            $('#lampiranForm').submit(function(event) {
                event.preventDefault();
                let formData = new FormData($('#lampiranForm')[0]);

                $.ajax({
                    url: "{{  route('dekan.lampiran-rtm.store') }}", 
                    method: "POST",
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Lampiran berhasil disimpan!',
                            text: 'Data lampiran telah disimpan dengan sukses.',
                            showConfirmButton: false,
                            timer: 1500
                        });

                        $('#undangan').val(response.undangan).prop('disabled', true);
                        $('#presensi').val(response.presensi).prop('disabled', true);
                        $('#dokumentasi').val(response.dokumentasi).prop('disabled', true);
                    },
                    error: function(xhr) {
                        Swal.fire({
                        icon: 'error',
                        title: 'Terjadi Kesalahan!',
                        text: 'Gagal menyimpan lampiran. Silakan coba lagi.',
                        confirmButtonText: 'Tutup'
                        });
                    }
                });
            });

            $('.form-control').on('input', function() {
                    let lampiranData = {
                        undangan: $('#undangan').val(),
                        presensi: $('#presensi').val(),
                        dokumentasi: $('#dokumentasi').val()
                    };
                    localStorage.setItem('lampiranDraft', JSON.stringify(lampiranData));
            });

            $('#lampiranModal').on('show.bs.modal', function() {
                let draft = localStorage.getItem('lampiranDraft');
                if (draft) {
                    draft = JSON.parse(draft);
                    $('#undangan').val(draft.undangan);
                    $('#presensi').val(draft.presensi);
                    $('#dokumentasi').val(draft.dokumentasi);
                }
            });

            $('#lampiranForm').submit(function() {
                localStorage.removeItem('lampiranDraft');
            });
        });
    </script>

    <script>
        // Meng-handle penghapusan item
        document.querySelectorAll('.delete-btn').forEach(function (button) {
            button.addEventListener('click', function (event) {
                event.preventDefault(); 

                var id = button.getAttribute('data-id');
                var form = document.getElementById('delete-form-' + id); 

                Swal.fire({
                    title: 'Apakah Anda yakin?',
                    text: "Data ini akan dihapus!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Ya, hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit(); // Submit the form to delete the item
                    }
                });
            });
        });
    </script>

    <script>
        $(document).ready(function() {
        $('.tindak-lanjut-btn').click(function() {
            let jadwalId = $(this).data('id');
            let fakultasId = $(this).data('fakultas_id');
            let unitId = $(this).data('unit_id');
            let auditId = $(this).data('jadwal_audit_id');

            console.log("Jadwal ID:", jadwalId);
            console.log("Fakultas ID:", fakultasId);
            console.log("Unit ID:", unitId);
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
                    url: "{{ route('dekan.rtm-rtl.store') }}",
                    method: "POST",
                    data: {
                        _token: "{{ csrf_token() }}",
                        jadwal_id: jadwalId,
                        fakultas_id: fakultasId,
                        unit_id: unitId,
                        jadwal_audit_id: auditId
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