@php
    use Carbon\Carbon;
@endphp

@extends('components.layout.main_layout')

@section('content')
    {{-- INFO RTM --}}
    <div class="card shadow border-0" style="background: linear-gradient(135deg, #6694ea, #614ba2); color: white;">
        <div class="card-header border-0">
            <h3 class="card-title title-size text-white" dusk="rtm-title">Rapat Tinjauan Manajemen</h3>
        </div>
        <div class="card-body">
            <a href="{{ route('admin.rtm-univ.index') }}" class="btn btn-outline-light mb-3">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
            <table class="table table-borderless text-white">
                <tr>
                    <td class="fw-bold" width="15%">Agenda</td>
                    <td width="5%">:</td>
                    <td>{{ $rtmJadwal->agenda }}</td>
                </tr>
                <tr>
                    <td class="fw-bold" width="10%">Pimpinan Rapat</td>
                    <td width="5%">:</td>
                    <td>{{ $rtmJadwal->pimpinan }}</td>
                </tr>
                <tr>
                    <td class="fw-bold">Tanggal</td>
                    <td>:</td>
                    <td>
                        {{ Carbon::parse($rtmJadwal->tanggal)->translatedFormat('l, j F Y') }}
                    </td>
                </tr>
                <tr>
                    <td class="fw-bold">Waktu</td>
                    <td>:</td>
                    <td>
                        {{ Carbon::parse($rtmJadwal->jam_mulai)->translatedFormat('H:i') }} - {{ Carbon::parse($rtmJadwal->jam_selesai)->translatedFormat('H:i') }}
                    </td>
                </tr>
                <tr>
                    <td class="fw-bold" width="10%">Tempat</td>
                    <td width="5%">:</td>
                    <td>{{ $rtmJadwal->tempat }}</td>
                </tr>
                <tr>
                    <td class="fw-bold" width="10%">Jumlah Kehadiran</td>
                    <td width="5%">:</td>
                    <td>{{ $rtmJadwal->peserta }} Peserta Rapat</td>
                </tr>
                <tr>
                    <td class="fw-bold" width="10%">Hasil AIMA</td>
                    <td width="5%">:</td>
                    <td>{{ $rtmJadwal->jadwal_audit->jadwal }}</td>
                </tr>
                <tr>
                    <td class="fw-bold">Periode AIMA</td>
                    <td>:</td>
                    <td>
                        {{ Carbon::parse($rtmJadwal->jadwal_audit->tgl_mulai)->translatedFormat('j F Y') }} -
                        {{ Carbon::parse($rtmJadwal->jadwal_audit->tgl_selesai)->translatedFormat('j F Y') }}
                    </td>
                </tr>
            </table>
        </div>
    </div>

    <div class="card shadow border-0 mt-3">
        <div class="card-header bg-white border-bottom">
            <h5 class="mb-0 text-dark"><i class="fas fa-paperclip me-2"></i> Upload Lampiran RTM</h5>
        </div>
        <div class="card-body">
            @if($lampiran)
                <p class="text-muted">Silakan tambahkan kata pengantar laporan RTM untuk menjelaskan konteks dan tujuan laporan ini, jika diperlukan.</p>
                <form method="POST" action="{{ route('admin.lampiran-rtm-univ.store') }}" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="rtm_jadwal_id" value="{{ $rtmJadwal->id }}">
                    <div class="mb-4">
                        <label for="undangan" class="form-label fw-bold">
                            <i class="fas fa-file-invoice me-1"></i> Upload File Undangan
                        </label>
                        <div class="input-group">
                            <input type="file" class="form-control" id="undangan" name="undangan" required dusk="input-undangan">
                            @if ($lampiran->undangan)
                                <a href="{{ Storage::url($lampiran->undangan) }}"
                                    target="_blank"
                                    class="input-group-text text-decoration-none">
                                    <i class="fas fa-eye"></i> Lihat
                                </a>
                            @endif
                        </div>
                    </div>
                    <div class="mb-4">
                        <label for="presensi" class="form-label fw-bold">
                            <i class="fas fa-clipboard me-1"></i> Upload File Presensi
                        </label>
                        <div class="input-group">
                            <input type="file" class="form-control" id="presensi" name="presensi" required dusk="input-presensi">
                            @if ($lampiran->presensi)
                                <a href="{{ Storage::url($lampiran->presensi) }}"
                                    target="_blank"
                                    class="input-group-text text-decoration-none">
                                    <i class="fas fa-eye"></i> Lihat
                                </a>
                            @endif
                        </div>
                    </div>
                    <div class="mb-4">
                        <label for="dokumentasi" class="form-label fw-bold">
                            <i class="fas fa-camera me-1"></i> Upload File Dokumentasi
                        </label>
                        <div class="input-group">
                            <input type="file" class="form-control" id="dokumentasi" name="dokumentasi" required dusk="input-dokumentasi">
                            @if ($lampiran->dokumentasi)
                                <a href="{{ Storage::url($lampiran->dokumentasi) }}"
                                    target="_blank"
                                    class="input-group-text text-decoration-none">
                                    <i class="fas fa-eye"></i> Lihat
                                </a>
                            @endif
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </form>
            @endif
        </div>
    </div>

    <div class="card shadow border-0 mt-3">
        <div class="card-header bg-white border-bottom">
            <h5 class="mb-0 text-dark"><i class="fas fa-file-alt me-2"></i> Narasi Laporan RTM</h5>
        </div>
        <div class="card-body">
            <p class="text-muted">Silakan tambahkan kata pengantar laporan RTM untuk menjelaskan konteks dan tujuan laporan ini, jika diperlukan.</p>
            <div class="d-flex justify-content">
                <a href="{{ route('admin.rtm-catatan.form', [$rtmJadwal->id]) }}"
                    class="btn btn-primary"><i class="fas fa-edit me-1"></i> Isi </a>
            </div>
        </div>
    </div>

    <div class="card shadow-lg border-0 rounded-lg">
        <div class="card-body">
            <div class="mb-3 p-3 bg-light rounded">
                <h5>Pilih Kriteria:</h5>
                <form method="GET">
                    <div class="row">
                        @foreach($kriteriaOptions as $kriteria)
                        <div class="col-md-3">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox"
                                    dusk="kriteria-{{ $kriteria->id }}"
                                    class="custom-control-input"
                                    id="kriteria_{{ $kriteria->id }}"
                                    name="kriteria[]"
                                    value="{{ $kriteria->id }}"
                                    @if(in_array($kriteria->id, request('kriteria', []))) checked @endif>

                                <label class="custom-control-label" for="kriteria_{{ $kriteria->id }}">
                                    {{ $kriteria->nama }}
                                </label>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    <div class="mt-3">
                        <button type="submit" class="btn btn-primary mr-2">Terapkan Filter</button>
                        @if(request()->has('kriteria'))
                            <a href="{{ url()->current() }}" class="btn btn-outline-secondary">Reset</a>
                        @endif
                    </div>
                </form>
            </div>

             <div class="row mb-3">
                <div class="col-md-6">
                    <form method="GET" action="">
                        <div class="input-group">
                            <input type="text" name="search" class="form-control" placeholder="Cari fakultas/unit..." 
                                value="{{ request('search') }}" dusk="search-input">
                            <button class="btn btn-primary" type="submit" dusk="search-button">
                                <i class="fas fa-search"></i> Cari
                            </button>
                        </div>
                        <!-- Sertakan parameter kriteria yang sudah dipilih -->
                        @if(request('kriteria'))
                            @foreach(request('kriteria') as $kriteria)
                                <input type="hidden" name="kriteria[]" value="{{ $kriteria }}">
                            @endforeach
                        @endif
                    </form>
                </div>
            </div>

            <table class="table table-hover table-striped">
                <thead class="bg-dark text-white text-center">
                    <tr>
                        <th>No</th>
                        <th>Fakultas/Unit</th>
                        <th>Rencana Tindak Lanjut</th>
                        <th>Jumlah Temuan</th>
                        <th>Aksi</th>
                        <th>Status Approval</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $no = 1;
                    @endphp

                    @foreach ($units as $index => $item)
                        <tr class="text-center" dusk="unit-row-{{ $item->id }}">
                            <td>{{ $index + 1 }}</td>
                            <td>
                                {{ $item->nama }}
                            </td>
                            <td>
                                @if($item->rtm_rtl_id)
                                    <span class="badge bg-success">Ada</span>
                                @else
                                    <span class="badge bg-secondary">Tidak Ada</span>
                                @endif
                            </td>
                            <td>
                                <span class="badge bg-info">{{ $item->jumlah_temuan ?? 0 }}</span>
                            </td>
                            <td>
                                @if($item->rtm_rtl_univ_id)
                                    @if(!empty($item->status))
                                        @if($item->status === 'completed')
                                            <a href="{{ route('admin.rtm-rtl.form', [$item->rtm_rtl_univ_id]) }}?{{ http_build_query(request()->query()) }}"
                                                class="btn btn-primary btn-fixed-size">Sudah Isi</a>
                                        @else
                                            <a href="{{ route('admin.rtm-rtl.form', [$item->rtm_rtl_univ_id]) }}?{{ http_build_query(request()->query()) }}"
                                                class="btn btn-outline-primary btn-fixed-size">Isi</a>
                                        @endif
                                    @else
                                        <form action="{{ route('admin.rtm-rtl.isi', [$item->rtm_rtl_univ_id]) }}?{{ http_build_query(request()->query()) }}"
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
                            <td>
                                @if($item->approval_status !== null)
                                    <span class="badge bg-success">Approved</span>
                                @else
                                    <span class="badge bg-secondary">User belum melakukan approval</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                    @if($units->count() == 0)
                        <tr>
                            <td colspan="6" class="text-center">Tidak ada data yang ditemukan</td>
                        </tr>
                    @endif
                </tbody>
            </table>
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    Menampilkan {{ $units->firstItem() }} - {{ $units->lastItem() }} dari {{ $units->total() }} entri
                </div>
                <nav>
                    {{ $units->appends(request()->query())->links('pagination::bootstrap-4', [
                        'dusk' => 'pagination'
                    ]) }}
                </nav>
            </div>
        </div>
    </div>

    <div class="row align-items-stretch mb-4">
        <div class="col-sm-6 mb-3 mb-sm-0 d-flex">
            <div class="card w-100 h-100">
                <div class="card-body">
                    <h5 class="card-title title-size text-dark">Download dan Approve Rencana Tindak Lanjut Hasil Audit</h5>
                    <p class="card-text">Silahkan download dan approve, setelah mengisi rencana Hasil Audit.</p>
                    <div class="mb-2">
                        @if ($isRektor && $user == $rektorId && (!$approvalRektor || !$approvalRektor->approve))
                            <form action="{{ route('approve.rtm.univ', ['rtmJadwal' => $rtmJadwal->id, 'user' => $user]) }}" method="post">
                                @csrf
                                <button type="submit" class="btn btn-outline-success w-100">
                                    <i class="fas fa-thumbs-up"></i> Approve sebagai Rektor
                                </button>
                            </form>
                        @elseif($isKetuaLP3M && $user == $ketuaLP3MId && (!$approvalKetuaLP3M || !$approvalKetuaLP3M->approve))
                            <form action="{{ route('approve.rtm.univ', ['rtmJadwal' => $rtmJadwal->id, 'user' => $user]) }}" method="post">
                                @csrf
                                <button type="submit" class="btn btn-outline-success w-100">
                                    <i class="fas fa-thumbs-up"></i> Approve sebagai Ketua LP3M
                                </button>
                            </form>
                        @elseif(($isRektor && $approvalRektor && $approvalRektor->approve) ||
                                ($isKetuaLP3M && $approvalKetuaLP3M && $approvalKetuaLP3M->approve))
                            <button disabled class="btn btn-secondary w-100">
                                <i class="fas fa-check-circle"></i> Anda sudah memberikan approval
                            </button>
                        @endif
                    </div>

                    <div class="alert alert-primary mt-3">
                        Status Approval:
                        <ul class="mt-2">
                            <li>Rektor:
                                @if($approvalRektor && $approvalRektor->approve)
                                    <span class="badge bg-warning">Sudah Approve</span>
                                @else
                                    <span class="badge bg-danger">Belum Approve</span>
                                @endif
                            </li>
                            <li>Ketua LP3M:
                                @if($approvalKetuaLP3M && $approvalKetuaLP3M->approve)
                                    <span class="badge bg-warning">Sudah Approve</span>
                                @else
                                    <span class="badge bg-danger">Belum Approve</span>
                                @endif
                            </li>
                        </ul>
                    </div>

                    <div>
                        <form action="{{ route('download.rtm.univ', $rtmJadwal->id) }}"
                            class="d-inline">
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="fas fa-download"></i> Download Laporan RTM
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-sm-6 mb-3 mb-sm-0 d-flex">
            <div class="card shadow border-0 w-100 h-100" style="background: linear-gradient(135deg, #6694ea, #614ba2); color: white;">
                <div class="card-body">
                    <h5 class="card-title title-size text-dark"><i class="fas fa-info-circle me-2"></i> Informasi Temuan Audit</h5>
                    <p class="card-text">Temuan ini merupakan hasil dari audit yang sudah dilaksanakan. Pengelompokkan temuan hasil audit sesuai dengan jawaban auditor yang sudah dikelompokkan berdasarkan kriteria. </p>
                </div>
            </div>
        </div>
    </div>

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
    {{-- DataTables --}}
    {{-- <link rel="stylesheet" href="{{ asset('plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}"> --}}
    {{-- <link rel="stylesheet" href="{{ asset('plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}"> --}}
    {{-- <link rel="stylesheet" href="{{ asset('plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}"> --}}
@endsection

@section('script')
    {{-- SweetAlert2 JS (ensure this is loaded BEFORE this script block in your main_layout) --}}
    {{-- If you're using asset helper for SweetAlert2, it should be in main_layout --}}
    <script>
        $(document).ready(function() {
            // Your existing jQuery document.ready function for .tindak-lanjut-btn
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
                            location.reload(); // This will trigger the session('success') check on reload
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

            // SweetAlert2 for session messages
            @if(session('success'))
                Swal.fire({
                    icon: 'success',
                    title: 'Sukses!',
                    text: '{{ session('success') }}',
                    timer: 3000,
                    showConfirmButton: false
                });
            @endif

            @if(session('info_message'))
                Swal.fire({
                    icon: 'info',
                    title: 'Informasi',
                    text: '{{ session('info_message') }}',
                    timer: 3000,
                    showConfirmButton: false
                });
            @endif

            @if(session('warning'))
                Swal.fire({
                    icon: 'warning',
                    title: 'Peringatan',
                    text: '{{ session('warning') }}'
                });
            @endif
        });
    </script>
@endsection