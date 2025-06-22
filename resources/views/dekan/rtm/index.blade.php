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
                <a href="{{ asset('user_manual/RTM_Fakultas.pdf') }}" target="_blank" class="btn btn-outline-info mr-2 mb-3">
                    <i class="fa fa-book mr-2"></i> User Manual
                </a>
            </div>
            @role(['pj_fakultas'])
                <a href="{{ route('dekan.jadwal-rtm.create')}}" class="btn btn-outline-primary mr-2 mb-3">Buat Agenda RTM</a>
            @endrole

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
                                        data-toggle="modal" data-target="#lampiranModal"
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
                                <div class="dropdown d-inline">
                                    <button class="btn btn-outline-primary btn-sm btn-fixed-size dropdown-toggle" type="button"
                                        id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true"
                                        aria-expanded="false">
                                        Aksi
                                    </button>
                                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                        <a class="dropdown-item" href="{{ route('dekan.jadwal-rtm.show', $item->id) }}">
                                            <i class="fas fa-file"></i> Lihat Agenda
                                        </a>
                                        <a class="dropdown-item text-danger" href="{{ route('dekan.jadwal-rtm.destroy', $item->id) }}"
                                            data-confirm-delete="true">
                                            <i class="fas fa-trash"></i>Hapus</a>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    {{-- Modal Lampiran --}}
    <div class="modal fade" id="lampiranModal" tabindex="-1" role="dialog" aria-labelledby="lampiranModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="lampiranModalLabel">Lampiran RTM</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="lampiranForm" method="POST" action="{{ route('dekan.lampiran-rtm.store') }}" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="rtm_jadwal_id" id="rtm_jadwal_id">

                    <div class="modal-body">
                        <div class="form-group">
                            <label for="undangan">Upload File Undangan</label>
                            <small class="form-text text-muted" style="margin: -10px 0 8px 0; display: block">Upload file pdf dengan ukuran maks. 2mb</small>
                            <input type="file" class="form-control-file" id="undangan" name="undangan" required>
                        </div>
                        <div class="form-group">
                            <label for="presensi">Upload File Presensi</label>
                            <small class="form-text text-muted" style="margin: -10px 0 8px 0; display: block">Upload file pdf dengan ukuran maks. 2mb</small>
                            <input type="file" class="form-control-file" id="presensi" name="presensi" required>
                        </div>
                        <div class="form-group">
                            <label for="dokumentasi">Upload File Dokumentasi</label>
                            <small class="form-text text-muted" style="margin: -10px 0 8px 0; display: block">Upload file pdf dengan ukuran maks. 5mb</small>
                            <input type="file" class="form-control-file" id="dokumentasi" name="dokumentasi" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                        <button type="submit" id="saveLampiranBtn" class="btn btn-primary">
                            <span class="spinner-border spinner-border-sm d-none" id="lampiran-spinner" role="status" aria-hidden="true"></span>
                            <span id="lampiran-text">Simpan</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Konfirmasi Pembuatan RTMRTL-->
    <div class="modal fade" id="konfirmasiModal" tabindex="-1" role="dialog" aria-labelledby="konfirmasiModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="konfirmasiModalLabel">Konfirmasi Tindak Lanjut</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    Apakah Anda ingin menindaklanjuti hasil audit ini?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
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
        .dropdown-item i {
            margin-right: 5px; 
        }

        .dropdown-item:hover {
            background-color: #f8f9fa; 
        }

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
                "pageLength": 25,
                "columnDefs": [
                    { "orderable": false, "targets": [5,6,7] } 
                ]
            });
        });

        $(document).ready(function() {
            // Variabel untuk menyimpan data tindak lanjut
            let currentActionData = {};
            
            // Handle Lampiran
            $('.lampiran-btn').click(function() {
                let jadwalId = $(this).data('id');
                $('#rtm_jadwal_id').val(jadwalId);
                
                // $.ajax({
                //     url: "/lampiran-rtm/" + jadwalId + "/edit",
                //     method: "GET",
                //     success: function(response) {
                //         if (response) {
                //             $('#undangan').val('').prop('disabled', true);
                //             $('#presensi').val('').prop('disabled', true);
                //             $('#dokumentasi').val('').prop('disabled', true);
                //         } else {
                //             $('#lampiranForm')[0].reset();
                //             $('#undangan, #presensi, #dokumentasi').prop('disabled', false);
                //         }
                //     },
                //     error: function() {
                //         $('#lampiranForm')[0].reset();
                //         $('#undangan, #presensi, #dokumentasi').prop('disabled', false);
                //     },
                //     complete: function() {
                //         $('#lampiranModal').modal('show');
                //     }
                // });
            });

            // Submit form lampiran
            $('#lampiranForm').submit(function(event) {
                event.preventDefault();
                
                // Show loading state
                $('#lampiran-spinner').removeClass('d-none');
                $('#lampiran-text').text('Menyimpan...');
                $('#saveLampiranBtn').prop('disabled', true);
                
                let formData = new FormData(this);

                $.ajax({
                    url: $(this).attr('action'),
                    method: "POST",
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: 'Lampiran berhasil disimpan',
                            showConfirmButton: false,
                            timer: 1500
                        });
                        setTimeout(() => location.reload(), 1500);
                    },
                    error: function(xhr) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal!',
                            text: xhr.responseJSON.message || 'Gagal menyimpan lampiran',
                            confirmButtonText: 'Tutup'
                        });
                    },
                    complete: function() {
                        $('#lampiran-spinner').addClass('d-none');
                        $('#lampiran-text').text('Simpan');
                        $('#saveLampiranBtn').prop('disabled', false);
                    }
                });
            });

            // Handle Tindak Lanjut
            $(document).on('click', '.tindak-lanjut-btn', function() {
                currentActionData = {
                    id: $(this).data('id'),
                    fakultas_id: $(this).data('fakultas_id'),
                    unit_id: $(this).data('unit_id'),
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
                    url: "{{ route('dekan.rtm-rtl.store') }}",
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

            // Handle Delete
            $('.delete-btn').click(function(e) {
                e.preventDefault();
                var id = $(this).data('id');
                var form = $('#delete-form-' + id);

                Swal.fire({
                    title: 'Apakah Anda yakin?',
                    text: "Data ini akan dihapus secara permanen!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        });
    </script>
@endsection