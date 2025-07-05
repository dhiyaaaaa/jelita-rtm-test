@extends('components.layout.main_layout')

@section('content')
    <form action="{{ route('admin.rtm-rtl.store_form', ['rtmRtlUniv' => $rtmRtlUnivId]) }}"
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
                            $no = ($paginatedTemuan->currentPage() - 1) * $paginatedTemuan->perPage() + 1;
                        @endphp
                        
                        <input type="hidden" name="totalPage" value="{{ $paginatedTemuan->lastPage() }}">
                        <input type="hidden" name="page_{{ $paginatedTemuan->currentPage() }}"
                            value="{{ $paginatedTemuan->currentPage() }}">

                        @foreach ($paginatedTemuan as $item)
                       

                                <input type="hidden" name="formIds[]" value="{{ $item->form->id }}">
                                <div class="row mb-4">
                                    <input type="hidden" name="formId" value="{{ $item->form->id }}">
                                    
                                    @php
                                        $jawaban = $jawabanRtm->where('form_id', $item->form->id)->first();

                                        $sessionRekomendasi = $jawaban && $jawaban->rekomendasi
                                            ? $jawaban->rekomendasi
                                            : $sessionFormData['rekomendasi_' . $item->form->id] ?? '';

                                        $sessionKoreksi = $jawaban && $jawaban->koreksi
                                            ? $jawaban->koreksi
                                            : $sessionFormData['koreksi_' . $item->form->id] ?? '';
                                            
                                        $isDisabled = isset($status) && $status->status === 'completed';
                                    @endphp

                                    <div class="col-12">
                                        <div class="card mb-3">
                                            <div class="card-header bg-light">
                                                <h4 class="card-title w-100">
                                                    <div class="d-flex flex-wrap gap-2 mb-2">
                                                        @forelse ($item->form->instrumen->jabatan as $jabatanInstrumen)
                                                            <span class="badge badge-primary">{{ $jabatanInstrumen->nama }}</span>
                                                        @empty
                                                            <span class="badge badge-secondary">-</span>
                                                        @endforelse
                                                    </div>
                                                    <div class="mb-2">
                                                        @if ($item->kriteria->slug === 'belum-memenuhi')
                                                            <span class="badge badge-danger">{{ $item->kriteria->nama }}</span>
                                                        @elseif ($item->kriteria->slug === 'memenuhi')
                                                            <span class="badge badge-warning">{{ $item->kriteria->nama }}</span>
                                                        @elseif ($item->kriteria->slug === 'melampaui')
                                                            <span class="badge badge-success">{{ $item->kriteria->nama }}</span>
                                                        @else
                                                            <span class="badge badge-secondary">{{ $item->kriteria->nama }}</span>
                                                        @endif
                                                    </div>
                                                    <p class="mb-1">{{ $no++ }}. {{ $item->form->instrumen->kode }}</p>
                                                    <p class="mb-0">{{ $item->form->instrumen->pernyataan }}</p>
                                                </h4>
                                            </div>
                                            <div class="card-body">
                                                <!-- Catatan Auditor -->
                                                <div class="form-group mb-4">
                                                    <label class="font-weight-bold">Catatan Auditor</label>
                                                    <div class="mt-2">
                                                        @if ($item->kriteria->slug === 'melum-memenuhi')
                                                            @if ($item->form->ptk_form_deskripsi->isNotEmpty())
                                                                @foreach ($item->form->ptk_form_deskripsi as $deskripsi)
                                                                    <p class="text-muted">{{ $deskripsi->deskripsi }}</p>
                                                                @endforeach
                                                            @elseif ($item->form->jawaban_auditor->isNotEmpty())
                                                                @foreach ($item->form->jawaban_auditor as $jawaban)
                                                                    @if ($jawaban->catatan)
                                                                        <p class="text-muted">{{ $jawaban->catatan }}</p>
                                                                    @endif
                                                                @endforeach
                                                            @else
                                                                <p class="text-muted">Tidak ada catatan</p>
                                                            @endif
                                                        @else
                                                            @if ($item->form->laporan_form->isNotEmpty())
                                                                @foreach ($item->form->laporan_form as $kelebihan)
                                                                    <p class="text-muted">{{ $kelebihan->kelebihan }}</p>
                                                                @endforeach
                                                            @elseif ($item->form->jawaban_auditor->isNotEmpty())
                                                                @foreach ($item->form->jawaban_auditor as $jawaban)
                                                                    @if ($jawaban->catatan)
                                                                        <p class="text-muted">{{ $jawaban->catatan }}</p>
                                                                    @endif
                                                                @endforeach
                                                            @else
                                                                <p class="text-muted">Tidak ada catatan</p>
                                                            @endif
                                                        @endif
                                                    </div>
                                                </div>

                                                
                                                <div class="mb-4">
                                                    <label class="font-weight-bold">Rencana Tindakan yang sudah dibuat</label>
                                                    <div class="mt-2">
                                                        @php
                                                            // Filter rencana tindakan berdasarkan kriteria item saat ini
                                                            $filteredTindakan = $item->form->rtm_tindak_lanjut
                                                                ->where('kriteria_id', $item->kriteria->id);
                                                        @endphp
                                                        
                                                        @if ($filteredTindakan->isNotEmpty())
                                                            @foreach ($filteredTindakan as $index => $tindakan)
                                                                <div class="mb-2">
                                                                    <p class="text-muted">
                                                                        {{ $index + 1 }}. Tindakan: {{ $tindakan->tindakan }}<br>
                                                                        PIC: {{ $tindakan->jabatan->nama }}-
                                                                        @if ($tindakan->prodi_id)
                                                                            {{ $tindakan->prodi->nama }}
                                                                        @elseif ($tindakan->fakultas_id)
                                                                            {{ $tindakan->fakultas->nama }}
                                                                        @endif
                                                                        <br>
                                                                        Target Waktu: {{ $tindakan->waktu }}
                                                                    </p>
                                                                </div>
                                                            @endforeach
                                                        @else 
                                                            <p class="text-muted">Belum ada rencana tindak lanjut untuk kriteria ini.</p>
                                                        @endif
                                                    </div>
                                                </div>

                                                <!-- Form Jawaban -->
                                                <div class="form-group">
                                                    <!-- Rekomendasi -->
                                                    <div class="form-group">
                                                        <label for="">Rekomendasi</label>
                                                        <span class="text-danger">&#42;</span>
                                                        <textarea 
                                                            name="rekomendasi_{{ $item->form->id }}" cols="10" rows="5"
                                                            class="form-control summernote @if ($errors->has('rekomendasi_' . $item->form->id)) is-invalid @endif" {{ $isDisabled ? 'disabled' : '' }}>{{ old('rekomendasi_' . $item->form->id, $sessionRekomendasi) }}</textarea>

                                                        @if ($errors->has('rekomendasi_' . $item->form->id))
                                                            <span class="text-danger d-block"
                                                                style="font-size: 14px">{{ $errors->first('rekomendasi_' . $item->form->id) }}</span>
                                                        @endif
                                                    </div>
                                                    
                                                    <!-- PTK -->
                                                    <div class="form-group">
                                                        <label for="">Permintaan Tindakan Koreksi</label>
                                                        <textarea 
                                                            name="koreksi_{{ $item->form->id }}" cols="10" rows="5"
                                                            class="form-control summernote @if ($errors->has('koreksi_' . $item->form->id)) is-invalid @endif" {{ $isDisabled ? 'disabled' : '' }}>{{ old('koreksi_' . $item->form->id, $sessionKoreksi) }}</textarea>

                                                        @if ($errors->has('koreksi_' . $item->form->id))
                                                            <span class="text-danger d-block"
                                                                style="font-size: 14px">{{ $errors->first('koreksi_' . $item->form->id) }}</span>
                                                        @endif
                                                    </div>
                                                </div>

                                                <!-- Tombol Simpan -->
                                                <div class="mb-3">
                                                    <button id="simpan_{{ $item->form->id }}" class="btn btn-warning" 
                                                        type="button" {{ $isDisabled ? 'disabled' : '' }}>
                                                        Simpan
                                                    </button>
                                                    <button id="simpan-button-loading_{{ $item->form->id }}" 
                                                        class="btn btn-warning d-none" type="button" disabled>
                                                        <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                                        Loading...
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Pagination -->
            <div class="col-md-3">
                <div class="pagination-container">
                    <div class="card card-primary shadow-sm">
                        <div class="card-header bg-primary text-white">
                            Halaman
                        </div>
                        <div class="card-body">
                            <div class="pagination-wrapper">
                                {{ $paginatedTemuan->links('pagination::bootstrap-4') }}
                            </div>
                            
                            <input type="hidden" name="totalPage" value="{{ $paginatedTemuan->lastPage() }}">
                            <input type="hidden" name="page_{{ $paginatedTemuan->currentPage() }}"
                                value="{{ $paginatedTemuan->currentPage() }}">

                            @if ($paginatedTemuan->currentPage() == $paginatedTemuan->lastPage())
                                @if (!isset($status) || $status->status !== 'completed')
                                    <div class="mb-3">
                                        <input type="hidden" name="final" value="final">
                                        <button type="submit" id="button-submit" class="btn btn-primary" 
                                            {{ $isDisabled ? 'disabled' : '' }}>
                                            Submit
                                        </button>
                                        <button id="button-submit-loading" class="btn btn-primary d-none" 
                                            type="button" disabled>
                                            <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                            Loading...
                                        </button>
                                    </div>
                                @elseif ($paginatedTemuan->lastPage() > 1)
                                    <div>
                                        <a id="previous" href="{{ $paginatedTemuan->previousPageUrl() }}"
                                            class="btn btn-primary mb-3">Previous</a>
                                    </div>
                                @endif
                            @else
                                <div class="mb-3">
                                    @if ($paginatedTemuan->currentPage() > 1)
                                        <a id="previous" href="{{ $paginatedTemuan->previousPageUrl() }}"
                                            class="btn btn-primary">Previous</a>
                                    @endif
                                    <a id="next" href="{{ $paginatedTemuan->nextPageUrl() }}"
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

                            <!-- Tombol Kembali -->
                            <div class="d-flex justify-content-start">
                                <a href="{{ route('admin.rtm-rtl.show', $rtmJadwal->id) }}" class="btn btn-outline-secondary mr-2">Kembali</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection

@section('style')
    <link rel="stylesheet" href="{{ asset('plugins/summernote/summernote-bs4.min.css') }}">

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

        .filter-section {
            max-height: 300px;
            overflow-y: auto;
            padding: 10px;
            border: 1px solid #eee;
            border-radius: 5px;
            margin-bottom: 15px;
        }
    </style>
@endsection

@section('script')
        <!-- Summernote -->
    <script src="{{ asset('plugins/summernote/summernote-bs4.min.js') }}"></script>

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
            var form = $('#create-form');

            // Summernote
            $('.summernote').summernote({
                height: 150,
                callbacks: {
                    onChange: debounce (function(contents) {
                        save_session();
                    }, 1000),
                    onInit: function() {
                        var textarea = $(this).siblings('.note-editor').prev('textarea');
                        if (textarea.length && textarea.val()) {
                            $(this).summernote('code', textarea.val())
                        }
                    }
                }
            });

            //diabled
            $('.summernote').each(function() {
                if ($(this).prop('disabled')) {
                    $(this).summernote('disable');
                }
            });

            // Handle form submit dengan konfirmasi
            form.on('submit', function(e) {
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

            // Tombol edit
            $('#edit-button').on('click', function() {
                $('#edit-button').addClass('d-none');
                $('#button-edit-loading').removeClass('d-none');

                $.ajax({
                    url: "{{ route('admin.rtm-rtl.isi', ['rtmRtlUniv' => $rtmRtlUnivId]) }}",
                    type: 'POST',
                    success: function(response) {
                        $('#edit-button').removeClass('d-none');
                        $('#button-edit-loading').addClass('d-none');
                        window.location.reload();
                    },
                    error: function(xhr) {
                        Swal.fire({
                            title: 'Error!',
                            text: 'Terjadi kesalahan',
                            icon: 'error',
                            confirmButtonText: 'OK'
                        });
                    }
                });
            });
            save_session();

            $(document).on('click', '[id^=simpan_]', function() {
                var id = $(this).attr('id').split('_')[1];

                $('[id^=simpan_' + id + ']').addClass('d-none');

                $('#simpan-button-loading_' + id).removeClass('d-none');

                save_jawaban(id);

                save_session();
            });

            $('textarea[name^="rekomendasi_"], textarea[name^="koreksi_"]').on('input',
                debounce(function() {
                    save_session();
                }, 1000));

            $(document).on('click', '.pagination a', function(event) {
                event.preventDefault();
                const url = $(this).attr('href');

                save_session();

                setTimeout(function() {
                    window.location.href = url;
                }, 500);
            });
        });
            

        // Fungsi simpan jawaban
        function save_jawaban(formId) {
            event.preventDefault();

            // Target Selector
            const rekomendasiSelector = document.querySelector(`[name="rekomendasi_${formId}"]`);
            const rekomendasi = rekomendasiSelector ? rekomendasiSelector.value : null;

            // PIC Selector
            const koreksiSelector = document.querySelector(`[name="koreksi_${formId}"]`);
            const koreksi = koreksiSelector ? koreksiSelector.value : null;

            // Loading
            document.getElementById(`simpan_${formId}`).classList.add('d-none');
            document.getElementById(`simpan-button-loading_${formId}`).classList.remove('d-none');

            // Form Data
            const formData = new FormData();
            formData.append(`rekomendasi_${formId}`, rekomendasi);
            formData.append(`koreksi_${formId}`, koreksi);

            $.ajax({
                url: '{{ route('admin.rtm-rtl.save_form', ['rtmRtlUniv' => $rtmRtlUnivId]) }}',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil',
                        text: response.message,
                    }).then((result) => {
                        if (result.isConfirmed) {
                            location.reload();
                        }
                    });

                    document.getElementById(`simpan_${formId}`).classList.remove('d-none');
                    document.getElementById(`simpan-button-loading_${formId}`).classList.add('d-none');
                },
                error: function(xhr) {
                    var response = JSON.parse(xhr.responseText);
                    console.error(response);
                    let errorMessages = '';

                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        html: response.message,
                    });
                    document.getElementById(`simpan_${formId}`).classList.remove('d-none');
                    document.getElementById(`simpan-button-loading_${formId}`).classList.add('d-none');
                }
            });
        }

        // Save ke Session
        function save_session() {
            $('.summernote').each(function() {
                var $textarea = $(this);
                $textarea.val($textarea.summernote('code'));
            });
            const formData = new FormData(document.getElementById('create-form'));

            $.ajax({
                url: '{{ route('admin.rtm-rtl.session', ['rtmRtlUniv' => $rtmRtlUnivId]) }}',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    if (response.success) {
                        console.log('Session berhasil tersimpan.');
                    }
                },
                error: function(response) {
                    console.error('Error.');
                }
            });
        }

        function debounce(func, delay) {
            let debounceTimer;
            return function() {
                const context = this;
                const args = arguments;
                clearTimeout(debounceTimer);
                debounceTimer = setTimeout(() => func.apply(context, args), delay);
            };
        }

    </script>
@endsection