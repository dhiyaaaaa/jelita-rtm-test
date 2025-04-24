@extends('components.layout.auditee_layout')

@section('content')

    <!-- Form Filter -->
    <form method="GET" action="{{ route('admin.rtm-rtl.form', ['rtmRtl' => $rtmRtl->id]) }}" id="filter-form" class="mb-4">
        <div class="card shadow-sm">
            <div class="card-header bg-light">
                <h5 class="mb-0">
                    <i class="fas fa-filter me-2 text-primary"></i> Filter Berdasarkan Jabatan
                </h5>
            </div>
            <div class="card-body">
                <div class="row g-3 align-items-end">
                    <div class="col-md-6">
                        <label for="jabatan_id" class="form-label">Pilih Jabatan:</label>
                        <select name="jabatan_id[]" id="jabatan_id" class="form-select" multiple>
                            @foreach($jabatanOptions as $jabatan)
                                <option value="{{ $jabatan->id }}" {{ in_array($jabatan->id, (array)request('jabatan_id')) ? 'selected' : '' }}>
                                    {{ $jabatan->nama }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-filter"></i> Terapkan Filter
                            </button>
                            <a href="{{ route('admin.rtm-rtl.form', ['rtmRtl' => $rtmRtl->id]) }}" class="btn btn-outline-secondary">
                                <i class="fas fa-sync-alt"></i> Reset
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>

    @if($isEmptyWithFilter)
        <div class="alert alert-warning">
            <i class="fas fa-exclamation-triangle me-2"></i>
            Tidak ditemukan temuan untuk PIC dengan jabatan: 
            @foreach($selectedJabatan as $jabatan)
                <span class="badge bg-primary">{{ $jabatan->nama }}</span>
            @endforeach
            <a href="{{ route('admin.rtm-rtl.form', ['rtmRtl' => $rtmRtl->id]) }}" class="btn btn-sm btn-outline-primary ms-3">
                Tampilkan Semua
            </a>
        </div>
    @else

        <form action="{{ route('admin.rtm-rtl.store_form', ['rtmRtl' => $rtmRtl->id]) }}"
            method="post" id="create-form" enctype="multipart/form-data" class="block">
            @csrf
            <div class="row">
                <div class="col-md-9">
                    <div class="card shadow-sm">
                        <div class="card-header bg-primary text-white">
                            <h3 class="card-title font-weight-bold">{{ $title }}</h3>
                        </div>
                        <div class="card-body">
                            @php
                                $no =
                                    ($paginatedTemuanFakultas->currentPage() - 1) * $paginatedTemuanFakultas->perPage() + 1;
                            @endphp
                            {{-- Page --}}
                            <input type="hidden" name="totalPage" value="{{ $paginatedTemuanFakultas->lastPage() }}">
                            <input type="hidden" name="page_{{ $paginatedTemuanFakultas->currentPage() }}"
                                value="{{ $paginatedTemuanFakultas->currentPage() }}">

                            @foreach ($paginatedTemuanFakultas as $item)

                                @php
                                    $showItem = !request()->filled('jabatan_id') || 
                                        $item->form->instrumen->jabatan->pluck('id')->intersect((array)request('jabatan_id'))->isNotEmpty() ||
                                        in_array(optional($item->jabatan)->id, (array)request('jabatan_id')) ||
                                        $item->filtered_tindakan->isNotEmpty();
                                @endphp

                                @if($showItem)
                                    <input type="hidden" name="formIds[]" value="{{ $item->form->id }}">
                                    <div class="row mb-4">
                                        <input type="hidden" name="formId" value="{{ $item->form->id }}">
                                        
                                        @php
                                        
                                            $jawaban = $hasilRtm->where('form_id', $item->form->id)->first();

                                            $sessionRekomendasi =
                                            $jawaban && $jawaban->rekomendasi
                                                ? $jawaban->rekomendasi
                                                : $sessionFormData['rekomendasi_' . $item->form->id] ?? '';

                                            $sessionKoreksi =
                                            $jawaban && $jawaban->koreksi
                                                ? $jawaban->koreksi
                                                : $sessionFormData['koreksi_' . $item->form->id] ?? '';
                                                
                                            // isDisabled
                                            $isDisabled = false;
                                            if (isset($status) && $status->status === 'completed') {
                                                $isDisabled = true;
                                            }
                                        @endphp

                                        <div class="col-12">
                                            <div class="card mb-3">
                                                <div class="card-header bg-light">
                                                    <h4 class="card-title w-100">
                                                        <div class="d-flex flex-wrap gap-2 mb-2">
                                                            @if ($item->form->instrumen->jabatan->isNotEmpty())
                                                                @foreach ($item->form->instrumen->jabatan as $jabatanInstrumen)
                                                                    <span
                                                                        class="badge badge-primary mr-1">{{ $jabatanInstrumen->nama }}</span>
                                                                @endforeach
                                                            @else
                                                                <span class="badge badge-secondary">-</span>
                                                            @endif
                                                        </div>
                                                        <div class="mb-2">
                                                            @if ($item->kriteria->nama === 'Belum Memenuhi')
                                                                <span class="badge badge-danger">{{ $item->kriteria->nama }}</span>
                                                            @elseif ($item->kriteria->nama === 'Memenuhi')
                                                                <span class="badge badge-warning">{{ $item->kriteria->nama }}</span>
                                                            @elseif ($item->kriteria->nama === 'Melampaui')
                                                                <span
                                                                    class="badge badge-success">{{ $item->kriteria->nama }}</span>
                                                            @else
                                                                <span
                                                                    class="badge badge-secondary">{{ $item->kriteria->nama }}</span>
                                                            @endif
                                                        </div>
                                                        <p class="mb-1">{{ $no++ }}. {{ $item->form->instrumen->kode }}</p>
                                                        <p class="mb-0">{{ $item->form->instrumen->pernyataan }}</p>
                                                    </h4>
                                                </div>
                                                <div class="card-body">
                                                    {{-- Jawaban Auditee --}}
                                                    <div class="form-group mb-4">
                                                        <label class="font-weight-bold">Catatan Auditor</label>
                                                        <div class="mt-2">
                                                            @if ($item->kriteria->nama === 'Belum Memenuhi')
                                                                @if ($item->form->ptk_form_deskripsi->isNotEmpty())
                                                                    @foreach ($item->form->ptk_form_deskripsi as $deskripsi)
                                                                        <p class="text-muted">{{ $deskripsi->deskripsi }}</p>
                                                                    @endforeach
                                                                @elseif ($jawabanAuditor && $jawabanAuditor->catatan)
                                                                    <p class="text-muted">{{ $jawabanAuditor->catatan }}</p>
                                                                @else
                                                                    <p class="text-muted">Tidak ada catatan</p>
                                                                @endif
                                                            @elseif ($item->kriteria->nama === 'Memenuhi')
                                                                @if ($jawabanAuditor && $jawabanAuditor->catatan)
                                                                    <p class="text-muted">{{ $jawabanAuditor->catatan }}</p>
                                                                @else
                                                                    <p class="text-muted">Tidak ada catatan</p>
                                                                @endif
                                                            @elseif ($item->kriteria->nama === 'Melampaui')
                                                                @if ($item->form->laporan_form->isNotEmpty())
                                                                    @foreach ($item->form->laporan_form as $kelebihan)
                                                                        <p class="text-muted">{{ $kelebihan->kelebihan }}</p>
                                                                    @endforeach
                                                                @elseif ($jawabanAuditor && $jawabanAuditor->catatan)
                                                                    <p class="text-muted">{{ $jawabanAuditor->catatan }}</p>
                                                                @else
                                                                    <p class="text-muted">Tidak ada catatan</p>
                                                                @endif
                                                            @endif
                                                        </div>
                                                    </div>

                                                    <div class="mb-4">
                                                        <label class="font-weight-bold">Rencana Tindakan yang sudah dibuat</label>
                                                        <div class="mt-2">
                                                            @if ($item->form->rtm_tindak_lanjut->isNotEmpty())
                                                                @php
                                                                    // Filter tindakan berdasarkan jabatan yang dipilih
                                                                    $filteredTindakan = $item->form->rtm_tindak_lanjut;
                                                                    
                                                                    if (request()->filled('jabatan_id')) {
                                                                        $filteredTindakan = $filteredTindakan->filter(function($tindakan) {
                                                                            return in_array($tindakan->jabatan->id, (array)request('jabatan_id'));
                                                                        });
                                                                    }
                                                                @endphp
                                                                
                                                                @if ($filteredTindakan->isNotEmpty())
                                                                    @foreach ($filteredTindakan as $index => $tindakan)
                                                                        <div class="mb-2">
                                                                            <p class="text-muted">
                                                                                {{ $index + 1 }}. Tindakan: {{ $tindakan->tindakan }}<br>
                                                                                PIC: {{ $tindakan->user->name }} ({{ $tindakan->jabatan->nama }})<br>
                                                                                Waktu: {{ $tindakan->waktu }}
                                                                            </p>
                                                                        </div>
                                                                    @endforeach
                                                                @else
                                                                    <p class="text-muted">Tidak ada Rencana untuk Jabatan yang Dipilih</p>
                                                                @endif
                                                            @else
                                                                <p class="text-muted">Tidak ada Rencana</p>
                                                            @endif
                                                        </div>
                                                    </div>

                                                    {{-- Jawaban --}}
                                                    <div class="form-group">
                                                        {{-- Rekomendasi --}}
                                                        <div class="form-group">
                                                            <label for="">Rekomendasi</label>
                                                            <span class="text-danger">&#42;</span>
                                                        
                                                            <textarea 
                                                                id="markdown-rekomendasi-{{ $item->form->id }}" 
                                                                name="rekomendasi_{{ $item->form->id }}" 
                                                                class="form-control @if ($errors->has('rekomendasi_' . $item->form->id)) is-invalid @endif" 
                                                                {{ $isDisabled ? 'disabled' : '' }}
                                                            >{{ old('rekomendasi_' . $item->form->id, $sessionRekomendasi) }}</textarea>
                                                        
                                                            @if ($errors->has('rekomendasi_' . $item->form->id))
                                                                <span class="text-danger d-block" style="font-size: 14px">
                                                                    {{ $errors->first('rekomendasi_' . $item->form->id) }}
                                                                </span>
                                                            @endif
                                                        </div>
                                                        {{-- PTK --}}
                                                        <div class="form-group">
                                                            <label for="">Permintaan Tindakan Koreksi</label>
                                                            <span class="text-danger">&#42;</span>
                                                        
                                                            <textarea 
                                                                id="markdown-koreksi-{{ $item->form->id }}" 
                                                                name="koreksi_{{ $item->form->id }}" 
                                                                class="form-control @if ($errors->has('koreksi_' . $item->form->id)) is-invalid @endif" 
                                                                {{ $isDisabled ? 'disabled' : '' }}
                                                            >{{ old('koreksi_' . $item->form->id, $sessionKoreksi) }}</textarea>
                                                        
                                                            @if ($errors->has('koreksi_' . $item->form->id))
                                                                <span class="text-danger d-block" style="font-size: 14px">
                                                                    {{ $errors->first('koreksi_' . $item->form->id) }}
                                                                </span>
                                                            @endif
                                                        </div>
                                                    </div>

                                                    <div class="mb-3">
                                                        <button id="simpan_{{ $item['form']->id }}" class="btn btn-warning" type="button" {{ $isDisabled ? 'disabled' : '' }}>Simpan</button>
                                                        <button id="simpan-button-loading_{{ $item['form']->id }}" class="btn btn-warning d-none" type="button" disabled>
                                                            <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                                            Loading...
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    </div>
                </div>

                {{-- Pagination --}}
                <div class="col-md-3">
                    <div class="pagination-container">
                        <div class="card card-primary shadow-sm">
                            <div class="card-header bg-primary text-white">
                                Halaman
                            </div>
                            <div class="card-body">
                                <div class="pagination-wrapper">
                                    {{ $paginatedTemuanFakultas->links('pagination::bootstrap-4') }}
                                </div>
                                <input type="hidden" name="totalPage" value="{{ $paginatedTemuanFakultas->lastPage() }}">
                                <input type="hidden" name="page_{{ $paginatedTemuanFakultas->currentPage() }}"
                                    value="{{ $paginatedTemuanFakultas->currentPage() }}">
                                @if (($paginatedTemuanFakultas->currentPage() == $paginatedTemuanFakultas->lastPage()) == 1)

                                    @if (isset($status) && $status->status !== 'completed')
                                        <div class="mb-3">
                                            <button type="submit" id="button-submit" class="btn btn-primary" {{ $isDisabled ? 'disabled' : '' }}>Submit</button>
                                            <button id="button-submit-loading" class="btn btn-primary d-none" type="button"
                                                disabled>
                                                <span class="spinner-border spinner-border-sm" role="status"
                                                    aria-hidden="true"></span>
                                                Loading...
                                            </button>
                                        </div>
                                    @else
                                        @if ($paginatedTemuanFakultas->lastPage() !== 1)
                                            <div>
                                                <a id="previous" href="{{ $paginatedTemuanFakultas->previousPageUrl() }}"
                                                    class="btn btn-primary mb-3">Previous</a>
                                            </div>
                                        @endif
                                    @endif
                                @else
                                    <div class="mb-3">
                                        @if ($paginatedTemuanFakultas->currentPage() > 1)
                                            <a id="previous" href="{{ $paginatedTemuanFakultas->previousPageUrl() }}"
                                                class="btn btn-primary">Previous</a>
                                        @endif
                                        <a id="next" href="{{ $paginatedTemuanFakultas->nextPageUrl() }}"
                                            class="btn btn-primary">Next</a>
                                    </div>
                                @endif

                                @if (isset($status) && $status->status === 'completed')
                                    <div class="mb-3">
                                        <button type="button" class="btn btn-warning" id="edit-button">Ubah</button>
                                        <button id="button-edit-loading" class="btn btn-warning d-none" type="button"
                                            disabled>
                                            <span class="spinner-border spinner-border-sm" role="status"
                                                aria-hidden="true"></span>
                                            Loading...
                                        </button>
                                    </div>
                                @endif 


                                {{-- Kembali ke halaman jadwal --}}
                                <div class="d-flex justify-content-start">
                                    <a href="/" class="btn btn-outline-secondary mr-2">Kembali</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    @endif
@endsection

@section('style')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/easymde/dist/easymde.min.css">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet">

    <style>
        .pagination-container {
            position: sticky;
            top: 0;
            z-index: 1000;
            background-color: #f4f6f9;
            padding: 12px 0;
            transition: padding-top 0.3s;
        }

        .pagination-wrapper {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
        }

        .pagination-wrapper .pagination {
            display: flex;
            flex-wrap: wrap;
        }

        .pagination-wrapper .page-item {
            flex: 1 0 1;
        }
    </style>

    <style>
        .tindakan-row {
            display: flex;
            gap: 10px; 
            align-items: center; 
        }
        .small-textarea {
            width: 200px; 
            height: 50px; 
            resize: none; 
        }

        .button:disabled {
            opacity: 0.6;
            cursor: not-allowed;
        }
    </style>
@endsection

@section('script')
    <script src="https://cdn.jsdelivr.net/npm/easymde/dist/easymde.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error_message'))
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: "{{ session('error_message') }}"
            });
        </script>
    @endif

    {{-- Pagination --}}
    <script>
        $(document).ready(function() {
            var $paginationContainer = $('.pagination-container');
            var navbarHeight = 56;

            $(window).on('scroll', function() {
                if ($(this).scrollTop() > 0) {
                    $paginationContainer.css('padding-top', (navbarHeight + 12) + 'px');
                } else {
                    $paginationContainer.css('padding-top', '12px');
                }
            });

            $(window).trigger('scroll');
        });
    </script>
 

    <script>
        $(document).ready(function() {
            // Inisialisasi Select2
            $('#jabatan_id').select2({
                placeholder: "Pilih jabatan...",
                allowClear: true
            });

            // Inisialisasi EasyMDE untuk textarea
            $('[id^="markdown-"]').each(function() {
                new EasyMDE({
                    element: this,
                    spellChecker: false,
                    toolbar: ["bold", "italic", "heading", "|", 
                             "code", "quote", "unordered-list", "ordered-list", "|",
                             "link", "image", "|",
                             "preview", "side-by-side", "fullscreen", "|",
                             "guide"],
                    status: false
                });
            });

            // Handle form submit dengan konfirmasi
            $('#create-form').on('submit', function(e) {
                e.preventDefault();
                
                Swal.fire({
                    title: 'Submit Form?',
                    text: "Pastikan bahwa jawaban sudah terisi semua!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: 'primary',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, submit!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $('#button-submit').addClass('d-none');
                        $('#button-submit-loading').removeClass('d-none');
                        this.submit();
                    }
                });
            });

            // Tombol simpan per item
            $('[id^="simpan_"]').click(function() {
                const formId = $(this).attr('id').split('_')[1];
                saveJawaban(formId);
            });

            // Tombol edit
            $('#edit-button').click(function() {
                $(this).addClass('d-none');
                $('#button-edit-loading').removeClass('d-none');
                
                $.post("{{ route('admin.rtm-rtl.isi', ['rtmRtl' => $rtmRtl]) }}")
                    .done(function() {
                        window.location.reload();
                    })
                    .fail(function() {
                        Swal.fire('Error!', 'Terjadi kesalahan', 'error');
                        $('#edit-button').removeClass('d-none');
                        $('#button-edit-loading').addClass('d-none');
                    });
            });
        });

        // Fungsi simpan jawaban
        function saveJawaban(formId) {
            const $saveBtn = $('#simpan_' + formId);
            const $loadingBtn = $('#simpan-button-loading_' + formId);
            
            $saveBtn.addClass('d-none');
            $loadingBtn.removeClass('d-none');
            
            const formData = {
                ['rekomendasi_' + formId]: $('[name="rekomendasi_' + formId + '"]').val(),
                ['koreksi_' + formId]: $('[name="koreksi_' + formId + '"]').val(),
                _token: '{{ csrf_token() }}'
            };
            
            $.post('{{ route('admin.rtm-rtl.save_form', ['rtmRtl' => $rtmRtl]) }}', formData)
                .done(function(response) {
                    Swal.fire('Berhasil', response.message, 'success');
                })
                .fail(function(xhr) {
                    const response = xhr.responseJSON;
                    Swal.fire('Error', response.message, 'error');
                })
                .always(function() {
                    $saveBtn.removeClass('d-none');
                    $loadingBtn.addClass('d-none');
                });
        }

        // Auto save session saat input berubah
        let timeout;
        $('textarea[name^="rekomendasi_"], textarea[name^="koreksi_"]').on('input', function() {
            clearTimeout(timeout);
            timeout = setTimeout(saveSession, 1000);
        });

        // Fungsi simpan session
        function saveSession() {
            const formData = $('#create-form').serialize();
            
            $.post('{{ route('admin.rtm-rtl.session', ['rtmRtl' => $rtmRtl]) }}', formData)
                .done(function() {
                    console.log('Session tersimpan');
                })
                .fail(function() {
                    console.error('Gagal menyimpan session');
                });
        }
    </script>
@endsection