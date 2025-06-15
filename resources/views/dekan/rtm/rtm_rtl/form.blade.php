@extends('components.layout.auditee_layout')

@section('content')
    <form action="{{ route('dekan.rtm-rtl.store_form', ['rtmRtl' => $rtmRtl->id, 'auditee' => $auditeeId, 'kriteria' => $kriteria->id]) }}"
        method="post" id="create-form" enctype="multipart/form-data" class="block">
        @csrf
        <input type="hidden" name="kriteria_id" value="{{ $kriteria->id }}">
        
        <div class="row">
            <div class="col-md-9">
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <h3 class="card-title font-weight-bold">Rencana Tindak Lanjut Temuan dengan Kriteria {{ $kriteria->nama }}</h3>
                    </div>
                    <div class="card-body">
                        @php
                            $no =
                                ($paginatedTemuan->currentPage() - 1) * $paginatedTemuan->perPage() + 1;
                        @endphp
                        {{-- Page --}}
                        <input type="hidden" name="totalPage" value="{{ $paginatedTemuan->lastPage() }}">
                        <input type="hidden" name="page_{{ $paginatedTemuan->currentPage() }}"
                            value="{{ $paginatedTemuan->currentPage() }}">

                        @foreach ($paginatedTemuan as $item)
                            <input type="hidden" name="formIds[]" value="{{ $item->form->id }}">
                            <div class="row mb-4">
                                <input type="hidden" name="formId" value="{{ $item->form->id }}">
                                @php
                                    // isDisabled
                                    $isDisabled = false;
                                    if (isset($status) && $status->status === 'completed' || !$auditee) {
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
                                                    <span class = "badge
                                                        @if($kriteria->slug === 'belum-memenuhi') badge-danger
                                                        @elseif($kriteria->slug === 'memenuhi') badge-warning
                                                        @elseif($kriteria->slug === 'melampaui') badge-succes
                                                        @else badge-secondary 
                                                        @endif">
                                                        {{ $kriteria->nama }}
                                                    </span>
                                                </div>
                                                <p class="mb-1">{{ $no++ }}. {{ $item->form->instrumen->kode }}
                                                </p>
                                                <p class="mb-0">{{ $item->form->instrumen->pernyataan }}</p>
                                            </h4>
                                        </div>
                                        <div class="card-body">
                                            {{-- Jawaban Auditee --}}
                                            {{-- Jawaban Auditor --}}
                                            <div class="form-group mb-4">
                                                <label class="font-weight-bold">Catatan Auditor</label>
                                                <div class="mt-2">
                                                    @if ($item->kriteria->slug === 'belum-memenuhi')
                                                        @if (isset($item->deskripsi) && $item->deskripsi)
                                                            @foreach(explode("\n", $item->deskripsi) as $deskripsi)
                                                                <p class="text-muted">{{ $deskripsi }}</p>
                                                            @endforeach
                                                        @else
                                                            <p class="text-muted">Tidak ada deskripsi temuan</p>
                                                        @endif
                                                    @elseif ($item->kriteria->slug === 'memenuhi')
                                                        @if (isset($item->catatan_auditor) && $item->catatan_auditor)
                                                            @foreach(explode("\n", $item->catatan_auditor) as $catatan)
                                                                <p class="text-muted">{{ $catatan }}</p>
                                                            @endforeach
                                                        @else
                                                            <p class="text-muted">Tidak ada catatan</p>
                                                        @endif
                                                    @elseif ($item->kriteria->slug === 'melampaui')
                                                        @if (isset($item->kelebihan) && $item->kelebihan)
                                                            @foreach(explode("\n", $item->kelebihan) as $kebelihan)
                                                                <p class="text-muted">{{ $kebelihan }}</p>
                                                            @endforeach
                                                        @else
                                                            <p class="text-muted">Tidak ada catatan kelebihan</p>
                                                        @endif
                                                    @endif
                                                </div>
                                            </div>

                                            {{-- Tindakan --}}
                                            <div class="form-group">
                                                <label class="font-weight-bold">
                                                    @if ($item->kriteria->slug === 'belum-memenuhi')
                                                        Rencana Perbaikan
                                                    @else
                                                        Rencana Peningkatan
                                                    @endif
                                                </label>
                                                <span class="text-danger">&#42;</span>
                                                <div id="tindakan-inputs-{{ $item->form->id }}" class="mb-3">
                                                    @php
                                                        $existingTindakan = $jawabanTindakLanjut->where('form_id', $item->form->id) ?? [];
                                                        $sessionTindakan = old('tindakan_'.$item->form->id, $sessionFormData['tindakan_'.$item->form->id] ?? []);
                                                        
                                                        $tindakanToDisplay = !empty($sessionTindakan) ? $sessionTindakan : ($existingTindakan->isNotEmpty() ? $existingTindakan : []);
                                                    @endphp

                                                    @if(count($tindakanToDisplay) > 0)
                                                        @foreach($tindakanToDisplay as $index => $tindakan)
                                                            <div class="input-group mb-2 tindakan-row">
                                                                <!-- Tindakan -->
                                                                <textarea name="tindakan_{{ $item->form->id }}[{{ $index }}][tindakan]"class="form-control @if($errors->has('tindakan_'.$item->form->id.'.'.$index.'.tindakan')) is-invalid @endif" placeholder="Rencana Tindakan" {{ $isDisabled ? 'disabled' : '' }}>{{ is_object($tindakan) ? $tindakan->tindakan : ($tindakan['tindakan'] ?? '') }}</textarea>
                                                                @if ($errors->has('tindakan_'.$item->form->id.'.'.$index.'.tindakan'))
                                                                    <span class="text-danger d-block" style="font-size: 14px">
                                                                        {{ $errors->first('tindakan_'.$item->form->id.'.'.$index.'.tindakan') }}
                                                                    </span>
                                                                @endif
                                                                
                                                                <!-- PIC -->
                                                                <select name="tindakan_{{ $item->form->id }}[{{ $index }}][pic]" 
                                                                    class="form-control @if($errors->has('tindakan_'.$item->form->id.'.'.$index.'.pic')) is-invalid @endif" {{ $isDisabled ? 'disabled' : '' }}>
                                                                    <option value="">Pilih PIC</option>
                                                                    @foreach($picData as $pic)
                                                                        @php
                                                                            $selected = '';
                                                                            $picValue = $pic->user_id.'|'.$pic->jabatan_id;
                                                                            
                                                                            if(is_object($tindakan)) {
                                                                                $currentPic = $tindakan->user_id.'|'.$tindakan->jabatan_id;
                                                                                $selected = $currentPic == $picValue ? 'selected' : '';
                                                                            } elseif(is_array($tindakan)) {
                                                                                $selected = ($tindakan['pic'] ?? '') == $picValue ? 'selected' : '';
                                                                            }
                                                                        @endphp
                                                                        <option value="{{ $picValue }}" {{ $selected }}>
                                                                            {{ $pic->jabatan_nama }} 
                                                                            @if($pic->prodi_nama) - {{ $pic->prodi_nama }} @endif
                                                                        </option>
                                                                    @endforeach
                                                                </select>
                                                                @if ($errors->has('tindakan_'.$item->form->id.'.'.$index.'.pic'))
                                                                    <span class="text-danger d-block" style="font-size: 14px">
                                                                        {{ $errors->first('tindakan_'.$item->form->id.'.'.$index.'.pic') }}
                                                                    </span>
                                                                @endif
                                                                
                                                                <!-- Waktu -->
                                                                <textarea name="tindakan_{{ $item->form->id }}[{{ $index }}][waktu]" class="form-control @if($errors->has('tindakan_'.$item->form->id.'.'.$index.'.waktu')) is-invalid @endif" placeholder="Target waktu penyelesaian" {{ $isDisabled ? 'disabled' : '' }}>{{ is_object($tindakan) ? $tindakan->waktu : ($tindakan['waktu'] ?? '') }}</textarea>
                                                                @if ($errors->has('tindakan_'.$item->form->id.'.'.$index.'.waktu'))
                                                                    <span class="text-danger d-block" style="font-size: 14px">
                                                                        {{ $errors->first('tindakan_'.$item->form->id.'.'.$index.'.waktu') }}
                                                                    </span>
                                                                @endif
                                                                
                                                                <!-- Tombol Hapus -->
                                                                <button type="button" class="btn btn-danger btn-sm remove-tindakan" {{ $isDisabled ? 'disabled' : '' }}>Hapus</button>
                                                            </div>
                                                        @endforeach
                                                    @else
                                                        <div class="input-group mb-2 tindakan-row">
                                                            <!-- Tindakan -->
                                                            <textarea name="tindakan_{{ $item->form->id }}[0][tindakan]" class="form-control" placeholder="Rencana tindakan"></textarea>
                                                            
                                                            <!-- PIC -->
                                                            <select name="tindakan_{{ $item->form->id }}[0][pic]" class="form-control">
                                                                <option value="">Pilih PIC</option>
                                                                @foreach($picData as $pic)
                                                                    <option value="{{ $pic->user_id }}|{{ $pic->jabatan_id }}">
                                                                        {{ $pic->jabatan_nama }} 
                                                                        @if($pic->prodi_nama) - {{ $pic->prodi_nama }} @endif
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                            
                                                            <!-- Waktu -->
                                                            <textarea name="tindakan_{{ $item->form->id }}[0][waktu]" class="form-control" placeholder="Target waktu penyelesaian"></textarea>
                                                            
                                                            <button type="button" class="btn btn-danger btn-sm remove-tindakan" {{ $isDisabled ? 'disabled' : '' }}>Hapus</button>
                                                        </div>
                                                    @endif
                                                </div>
                                                
                                                @if (!$isDisabled)
                                                    <div class="mb-3">
                                                        @if (!$isDisabled)
                                                            <div class="mb-3">
                                                                <button type="button" class="btn btn-secondary btn-sm" {{ $isDisabled ? 'disabled' : '' }}
                                                                    onclick="addTindakanInput('{{ $item->form->id }}', '{{ $kriteria->nama }}')">
                                                                    +Tambah Rencana
                                                                </button>
                                                            </div>
                                                        @endif
                                                    </div>
                                                @endif
                                            </div>

                                            @if (isset($status) && $status->status !== 'completed')
                                                <div class="mb-3">
                                                    <button id="simpan_{{ $item['form']->id }}" class="btn btn-warning"
                                                        type="button" {{ $isDisabled ? 'disabled' : '' }}>Simpan</button>

                                                    <button id="simpan-button-loading_{{ $item['form']->id }}"
                                                        class="btn btn-warning d-none" type="button" disabled>
                                                        <span class="spinner-border spinner-border-sm" role="status"
                                                            aria-hidden="true"></span>
                                                        Loading...
                                                    </button>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
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
                                {{ $paginatedTemuan->links('pagination::bootstrap-4') }}
                            </div>
                            <input type="hidden" name="totalPage" value="{{ $paginatedTemuan->lastPage() }}">
                            <input type="hidden" name="page_{{ $paginatedTemuan->currentPage() }}"
                                value="{{ $paginatedTemuan->currentPage() }}">
                            @if (($paginatedTemuan->currentPage() == $paginatedTemuan->lastPage()) == 1)
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
                                    @if ($paginatedTemuan->lastPage() !== 1)
                                        <div>
                                            <a id="previous" href="{{ $paginatedTemuan->previousPageUrl() }}"
                                                class="btn btn-primary mb-3">Previous</a>
                                        </div>
                                    @endif
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
                                    <button type="button" class="btn btn-warning" id="edit-button" data-kriteria="{{ $kriteria->id }}">Ubah</button>
                                    <button id="button-edit-loading" class="btn btn-warning d-none" type="button"
                                        disabled>
                                        <span class="spinner-border spinner-border-sm" role="status"
                                            aria-hidden="true"></span>
                                        Loading...
                                    </button>
                                </div>
                            @endif 
                            
                            <div class="d-flex justify-content-start">
                                <a href="{{ route('dekan.rtm-rtl.show', ['rtmRtl' => $rtmRtl->id]) }}" class="btn btn-outline-secondary mr-2">Kembali</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection

@section('style')
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
        .tindakan-row {
            display: flex;
            gap: 10px; 
            align-items: center; 
        }
        .small-textarea,
        .select-pic {
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

            //Form Submit 
            form.on('submit', function(e) {
                e.preventDefault();
                
                Swal.fire({
                    title: 'Submit Form?',
                    text: 'Pastikan semua data sudah benar',
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
                        form.off('submit')
                            .submit();
                    }
                });
            });

            $('#edit-button').on('click', function() {
                $('#edit-button').addClass('d-none');
                $('#button-edit-loading').removeClass('d-none');

                $.ajax({
                    url: "{{ route('dekan.rtm-rtl.isi', ['rtmRtl' => $rtmRtl->id, 'kriteria' => $kriteria->id]) }}",
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

            // Save Jawaban
            $(document).on('click', '[id^=simpan_]', function() {
                var id = $(this).attr('id').split('_')[1];

                $('[id^=simpan_' + id + ']').addClass('d-none');

                $('#simpan-button-loading_' + id).removeClass('d-none');

                save_jawaban(id);

                save_session();
            });

            // Auto-save on input
            $(document).on('input', 'textarea, select', debounceSave);

            $(document).on('click', '.pagination a', function(event) {
                event.preventDefault();
                const url = $(this).attr('href');

                save_session();

                setTimeout(function() {
                    window.location.href = url;
                }, 500);
            });
        });

        function debounce(func, delay) {
            let debounceTimer;
            return function() {
                const context = this;
                const args = arguments;
                clearTimeout(debounceTimer);
                debounceTimer = setTimeout(() => func.apply(context, args), delay);
            };
        }

        const debounceSave = debounce(save_session, 1000);

        // save per nomor
        function save_jawaban(formId) {
            event.preventDefault();

            // Create FormData object
            const formData = new FormData();
            
            // Add required fields
            formData.append('form_id', formId);
            formData.append('currentPage', $('input[name^="page_"]').val());
            formData.append('_token', $('meta[name="csrf-token"]').attr('content'));

            // Collect all tindakan data
            let hasData = false;
            $(`#tindakan-inputs-${formId} .tindakan-row`).each(function(index) {
                const row = $(this);
                const tindakan = row.find('textarea[name*="tindakan"]').val();
                const pic = row.find('select[name*="pic"]').val();
                const waktu = row.find('textarea[name*="waktu"]').val();

                // Only include if all fields have values
                if (tindakan && pic && waktu) {
                    formData.append(`tindakan_${formId}[${index}][tindakan]`, tindakan);
                    formData.append(`tindakan_${formId}[${index}][pic]`, pic);
                    formData.append(`tindakan_${formId}[${index}][waktu]`, waktu);
                    hasData = true;
                }
            });

            if (!hasData) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Harap isi semua field tindakan sebelum menyimpan',
                });
                $(`#simpan_${formId}`).removeClass('d-none');
                $(`#simpan-button-loading_${formId}`).addClass('d-none');
                return;
            }

            $.ajax({
                url: '{{ route('dekan.rtm-rtl.save_form', ['rtmRtl' => $rtmRtl->id, 'auditee' => $auditeeId, 'kriteria' => $kriteria->id]) }}',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    if (response.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil',
                            text: response.message,
                        }).then((result) => {
                            if (result.isConfirmed) {
                                location.reload();
                            }
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: response.message,
                        });
                    }
                },
                error: function(xhr) {
                    let errorMessage = 'Terjadi kesalahan saat menyimpan data';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMessage = xhr.responseJSON.message;
                    }
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: errorMessage,
                    });
                },
                complete: function() {
                    $(`#simpan_${formId}`).removeClass('d-none');
                    $(`#simpan-button-loading_${formId}`).addClass('d-none');
                }
            });
        }

        // Save to session
        function save_session() {
            // Kumpulkan data dari semua input di halaman saat ini
            const formData = new FormData();
            const currentPage = $('input[name^="page_"]').val();
            
            // Tambahkan semua tindakan
            $('.tindakan-row').each(function(index) {
                const row = $(this);
                const formId = row.closest('[id^="tindakan-inputs-"]').attr('id').replace('tindakan-inputs-', '');
                
                formData.append(`tindakan_${formId}[${index}][tindakan]`, row.find('textarea[name*="tindakan"]').val());
                formData.append(`tindakan_${formId}[${index}][pic]`, row.find('select[name*="pic"]').val());
                formData.append(`tindakan_${formId}[${index}][waktu]`, row.find('textarea[name*="waktu"]').val());
            });
            
            // Tambahkan data halaman
            formData.append('currentPage', currentPage);
            
            // Kirim ke server
            $.ajax({
                url: '{{ route('dekan.rtm-rtl.session', ['rtmRtl' => $rtmRtl->id, 'auditee' => $auditeeId]) }}',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    console.log('Session saved for page', currentPage);
                },
                error: function(xhr) {
                    console.error('Error saving session');
                }
            });
        }
        
            // Add new tindakan input   
            window.addTindakanInput = function(formId) {
                const container = $(`#tindakan-inputs-${formId}`);
                const index = container.find('.tindakan-row').length;
                
                const newRow = $(`
                    <div class="input-group mb-2 tindakan-row">
                        <textarea name="tindakan_${formId}[${index}][tindakan]" 
                            class="form-control" placeholder="Rencana tindakan"></textarea>
                        <select name="tindakan_${formId}[${index}][pic]" class="form-control">
                            <option value="">Pilih PIC</option>
                            @foreach($picData as $pic)
                                <option value="{{ $pic->user_id }}|{{ $pic->jabatan_id }}">
                                    {{ $pic->jabatan_nama }} 
                                    @if($pic->prodi_nama) - {{ $pic->prodi_nama }} @endif
                                </option>
                            @endforeach
                        </select>
                        <textarea name="tindakan_${formId}[${index}][waktu]" 
                            class="form-control" placeholder="Target waktu"></textarea>
                        <button type="button" class="btn btn-danger btn-sm remove-tindakan">Hapus</button>
                    </div>
                `);
                
                container.append(newRow);
                debounceSave();
            };

            // Remove tindakan input
            $(document).on('click', '.remove-tindakan', function() {
                const row = $(this).closest('.tindakan-row');
                const container = row.parent();
                
                // Jangan hapus jika hanya tersisa satu row
                if (container.find('.tindakan-row').length > 1) {
                    row.remove();
                    save_session();
                } else {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Peringatan',
                        text: 'Setidaknya harus ada satu rencana tindakan'
                    });
                }
            });
    </script>

@endsection
