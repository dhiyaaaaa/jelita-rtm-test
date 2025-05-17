@extends('components.layout.auditee_layout')

@section('content')
    <form action="{{ route('dekan.rtl.store_form', ['rtl' => $rtl->id, 'auditee' => $auditeeId, 'kriteria' => $kriteria->id]) }}"method="post"
        id="create-form" enctype="multipart/form-data" class="block">
        @csrf
        <input type="hidden" name="kriteria_id" value="{{ $kriteria->id }}">

        {{-- Tampilkan judul berdasarkan kriteria --}}
        <div class="row">
            <div class="col-md-9">
                <div class="card shadow-sm">
                    <div class="card-header bg-secondary text-white">
                        <h3 class="card-title font-weight-bold">
                            Tindak Lanjut Temuan dengan Kriteria {{ $kriteria->nama }}
                        </h3>
                    </div>

                    <div class="card-body">
                        @php
                            $no=($paginatedTemuan->currentPage() - 1) * $paginatedTemuan->perPage() + 1;
                        @endphp

                        {{-- page --}}
                        <input type="hidden" name="totalPage" value="{{ $paginatedTemuan->lastPage() }}">
                        <input type="hidden" name="currentPage_{{ $paginatedTemuan->currentPage() }}"
                            value="{{ $paginatedTemuan->currentPage() }}">

                        {{-- Tampilkan temuan berdasarkan kriteria --}}
                        @foreach ($paginatedTemuan as $item)
                            <input type="hidden" name="formIds[]" value="{{ $item->form->id }}">
                            <div class="row mb-4">
                                @if ($item->form)
                                    <input type="hidden" name="formId" value="{{ $item->form->id }}">
                                    @php
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
                                                                <span class="badge badge-primary mr-1">{{ $jabatanInstrumen->nama }}</span>
                                                            @endforeach
                                                        @else
                                                            <span class="badge badge-secondary">-</span>
                                                        @endif
                                                    </div>
                                                    <div class="mb-2">
                                                        <span class = "badge
                                                            @if($kriteria->slug === 'belum-memenuhi') badge-danger
                                                            @elseif($kriteria->slug === 'memenuhi') badge-warning
                                                            @elseif($kriteria->slug === 'melampuai') badge-succes
                                                            @else badge-secondary 
                                                            @endif">
                                                            {{ $kriteria->nama }}
                                                        </span>
                                                    </div>
                                                    <p> {{ $no++ }}. {{ $item->form->instrumen->kode }}</p>
                                                    <p class="mb-0">{{ $item->form->instrumen->pernyataan }}</p>
                                                </h4>
                                            </div>
                                            <div class="card-body">
                                                {{-- Tampilkan catatan temuan --}}
                                                <div class="mb-4">
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
                                                            @foreach ($item->form->rtm_tindak_lanjut as $index => $tindakan)
                                                                <div class="mb-2">
                                                                    <p class="text-muted">
                                                                        {{ $index + 1 }}. Tindakan: {{ $tindakan->tindakan }}<br>
                                                                        PIC: {{ $tindakan->jabatan->nama }}
                                                                            @foreach ($tindakan->user->prodi as $prodi)
                                                                                {{ $prodi->nama }}
                                                                            @endforeach

                                                                            @foreach ($tindakan->user->fakultas as $fakultas)
                                                                                {{ $fakultas->nama }}
                                                                            @endforeach <br>
                                                                        Waktu: {{ $tindakan->waktu }}
                                                                    </p>
                                                                </div>
                                                            @endforeach
                                                        @else
                                                            <p class="text-muted">Tidak ada Rencana</p>
                                                        @endif
                                                    </div>
                                                </div>

                                                {{-- Tindakan --}}
                                                <div class="form-group">
                                                    <label class="font-weight-bold">Rencana Tindakan yang sudah dibuat</label>

                                                    {{-- Form tindakan --}}
                                                    <div id="tindakan-inputs-{{ $item->form->id }}" class="mb-3">
                                                        @if (!empty($sessionFormData[$item->form->id]['tindakan']))
                                                            @foreach ($sessionFormData[$item->form->id]['tindakan'] as $index => $tindakan)
                                                                <div class="input-group mb-2 tindakan-row">
                                                                    <textarea name="tindakan_{{ $item->form->id }}[{{ $index }}][tindakan]"
                                                                        class="form-control small-textarea @error('tindakan_'.$item->form->id.'.'.$index.'.tindakan') is-invalid @enderror"
                                                                        placeholder="Tindakan yang sudah dilaksanakan"
                                                                        {{ $isDisabled ? 'disabled' : '' }}>{{ $tindakan['tindakan'] ?? '' }}</textarea>
                                                                    @error('tindakan_'.$item->form->id.'.'.$index.'.tindakan')
                                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                                    @enderror
                                                                    
                                                                    <textarea name="tindakan_{{ $item->form->id }}[{{ $index }}][bukti]"
                                                                        class="form-control small-textarea @error('tindakan_'.$item->form->id.'.'.$index.'.bukti') is-invalid @enderror"
                                                                        placeholder="Bukti Tindakan (url)"
                                                                        pattern="https?://.+"
                                                                        {{ $isDisabled ? 'disabled' : '' }}>{{ $tindakan['bukti'] ?? '' }}</textarea>
                                                                    @error('tindakan_'.$item->form->id.'.'.$index.'.bukti')
                                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                                    @enderror
                                                                    
                                                                    @if (isset($tindakan['id']))
                                                                        <input type="hidden" name="tindakan_{{ $item->form->id }}[{{ $index }}][id]" 
                                                                               value="{{ $tindakan['id'] }}">
                                                                    @endif
                                                                    
                                                                    <button type="button" class="btn btn-danger btn-sm remove-tindakan ml-2" 
                                                                        {{ $isDisabled ? 'disabled' : '' }}>Hapus</button>
                                                                </div>
                                                            @endforeach
                                                        @else
                                                            <div class="input-group mb-2 tindakan-row">
                                                                <textarea name="tindakan_{{ $item->form->id }}[0][tindakan]"
                                                                    class="form-control small-textarea"
                                                                    placeholder="Tindakan yang sudah dilaksanakan"
                                                                    {{ $isDisabled ? 'disabled' : '' }}></textarea>
                                                                
                                                                <textarea name="tindakan_{{ $item->form->id }}[0][bukti]"
                                                                    class="form-control small-textarea"
                                                                    placeholder="Bukti Tindakan (url)"
                                                                    {{ $isDisabled ? 'disabled' : '' }}></textarea>
                                                                
                                                                <button type="button" class="btn btn-danger btn-sm remove-tindakan ml-2" 
                                                                    {{ $isDisabled ? 'disabled' : '' }}>Hapus</button>
                                                            </div>
                                                        @endif
                                                    </div>

                                                    @if (!$isDisabled)
                                                        <div class="mb-3">
                                                            @if (!$isDisabled)
                                                                <div class="mb-3">
                                                                    <button type="button" class="btn btn-secondary btn-sm" {{ $isDisabled ? 'disabled' : '' }}
                                                                        onclick="addTindakanInput('{{ $item->form->id }}', '{{ $kriteria }}')">
                                                                        + Tindakan
                                                                    </button>
                                                                </div>
                                                            @endif
                                                        </div>
                                                    @endif
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
                                @else
                                    <p>tidak ada temuan</p>
                                @endif
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
                                    @if ($paginatedTemuan->lastPage() > 1)
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
                                    <button type="button" class="btn btn-warning" id="edit-button">Ubah</button>
                                    <button id="button-edit-loading" class="btn btn-warning d-none" type="button"
                                        disabled>
                                        <span class="spinner-border spinner-border-sm" role="status"
                                            aria-hidden="true"></span>
                                        Loading...
                                    </button>
                                </div>
                            @endif 


                            {{-- Kembali ke halaman awal --}}
                            <div class="d-flex justify-content-start">
                                <a href="{{ route('dekan.rtl.index') }}" class="btn btn-outline-secondary mr-2">Kembali</a>
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
    @if (session('error_message'))
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: '{{ session('error_message') }}',
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

            @if (session('error_message'))
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: "{{ session('error_message') }}"
                });
            @endif

            // debounce function
            function debounce(func, delay) {
                let debounceTimer;
                return function() {
                    const context = this;
                    const args = arguments;
                    clearTimeout(debounceTimer);
                    debounceTimer = setTimeout(() => func.apply(context, args), delay);
                };
            }

            // Submit form handler
            form.on('submit', function(e) {
                e.preventDefault();
                let isValid = true;
                const errorMessages = [];
                
                $('.tindakan-row').each(function() {
                    const tindakan = $(this).find('textarea[name*="[tindakan]"]').val().trim();
                    const bukti = $(this).find('textarea[name*="[bukti]"]').val().trim();
                    
                    if (!tindakan) {
                        $(this).find('textarea[name*="[tindakan]"]').addClass('is-invalid');
                        isValid = false;
                        errorMessages.push('Tindakan');
                    }
                    
                    if (!bukti || !/^https?:\/\//i.test(bukti)) {
                        $(this).find('textarea[name*="[bukti]"]').addClass('is-invalid');
                        isValid = false;
                        errorMessages.push('Bukti');
                    }
                });

                if (!isValid) {
                        $('#button-submit').removeClass('d-none');
                        $('#button-submit-loading').addClass('d-none');
                        
                        Swal.fire({
                            title: 'Submit Form?',
                            text: 'Pastikan data sudah benar!',
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: 'primary',
                            cancelButtonColor: '#d33',
                            confirmButtonText: 'Ya, Submit',
                            cancelButtonText: 'Batal'
                        });
                    return;
                }
                    $('#button-submit').addClass('d-none');
                    $('#button-submit-loading').removeClass('d-none');
                    
                    save_session(function() {
                        form.off('submit').submit();
                });
            });

            // Edit button handler
            $('#edit-button').on('click', function() {
                
                $('#edit-button').addClass('d-none');
                $('#button-edit-loading').removeClass('d-none');

                $.ajax({
                    url: "{{ route('dekan.rtl.isi', ['rtl' => $rtl->id, 'kriteria' => $kriteria->id]) }}",
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

            // Menyesuaikan padding untuk pagination
            var $paginationContainer = $('.pagination-container');
            var navbarHeight = 56;
            $(window).on('scroll', function() {
                $paginationContainer.css('padding-top', $(this).scrollTop() > 0 ? (navbarHeight + 12) +
                    'px' : '12px');
            }).trigger('scroll');

            save_session();

            // Save Jawabn
            $(document).on('click', '[id^=simpan_]', function() {
                const formId = $(this).attr('id').split('_')[1];

                save_jawaban(formId);
                save_session();
            });

            $('textarea[name*="[tindakan]"], textarea[name*="[bukti]"]').on('input', debounce(function() {
                save_session();
            }, 1000));

            // Pagination link handler
            $(document).on('click', '.pagination a', function(event) {
                event.preventDefault();
                const url = $(this).attr('href');

                save_session();

                setTimeout(function() {
                    window.location.href = url;
                }, 500);
            });

            
            // Save jawaban 
            function save_jawaban(formId) {
                event.preventDefault();

                let isValid = true;
                const errorMessages = [];
                const formData = new FormData();

                $(`#tindakan-inputs-${formId} .tindakan-row`).each(function(index) {
                    const tindakanInput =$(this).find('textarea[name*="[tindakan]"]');
                    const buktiInput = $(this).find('textarea[name*="[bukti]"]');
                    const tindakan = tindakanInput.val().trim();
                    const bukti = buktiInput.val().trim();

                    //reset error class
                    tindakanInput.removeClass('is-invalid');
                    buktiInput.removeClass('is-invalid');

                    //validasi tindakan
                    if (!tindakan) {
                        tindakanInput.addClass('is-invalid');
                        isValid = false;
                        errorMessages.push(`Baris ${index + 1}: Tindakan pelaksanaan harus diisi`);
                    }

                    //validasi bukti
                    if (!bukti) {
                        buktiInput.addClass('is-invalid');
                        isValid = false;
                        errorMessages.push(`Baris ${index + 1}: Bukti pelaksanaan harus diisi`);
                    }else if (!/^https?:\/\//i.test(bukti)){
                        buktiInput.addClass('is-invalid');
                        isValid = false;
                        errorMessages.push(`Baris ${index + 1}: URL bukti harus brupa URL valid`);
                    }

                    if (tindakan && bukti && /^https?:\/\//i.test(bukti)) {
                        formData.append(`tindakan_${formId}[${index}][tindakan]`, tindakan);
                        formData.append(`tindakan_${formId}[${index}][bukti]`, bukti);
                    }
                });

                if (!isValid) {
                    $(`#simpan_${formId}`).removeClass('d-none');
                    $(`#simpan-button-loading_${formId}`).addClass('d-none');

                    let title = 'Validasi Gagal';

                    if (errorMessages.some(msg => msg.includes('URL'))) {
                        title = 'URL Tidak Valid';
                    } else if (errorMessages.some(msg => msg.includes('Tindakan'))) {
                        title = 'Tindakan Pelaksanaan Belum Diisi';
                    } else if (errorMessages.some(msg => msg.includes('Bukti'))) {
                        title = 'Bukti Pelaksanaan Belum Diisi';
                    }

                    Swal.fire({
                        icon: 'error',
                        title: title,
                        html: errorMessages.join('<br>')
                    });
                    return;
                }

                $.ajax({
                    url: '{{ route('dekan.rtl.save_form', ['rtl' => $rtl->id, 'auditee' => $auditeeId, 'kriteria' => $kriteria->id]) }}',
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil',
                            text: response.message
                        }).then(() => location.reload());
                    },
                    error: function(xhr) {
                        const response = xhr.responseJSON || {};
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            html: response.message || 'Terjadi kesalahan'
                        });
                    },
                    complete: function() {
                        $(`#simpan_${formId}`).removeClass('d-none');
                        $(`#simpan-button-loading_${formId}`).addClass('d-none');
                    }
                });
            }

            // Save session function
            function save_session(callback) {
                const formData = new FormData();
                const currentPage = $('input[name^="page_"]').val();
                
                // Kumpulkan semua formIds
                const formIds = [];
                $('input[name="formIds[]"]').each(function() {
                    formIds.push($(this).val());
                });
                
                formData.append('currentPage', currentPage);
                formData.append('formIds', JSON.stringify(formIds));

                // Kumpulkan data tindakan untuk setiap form
                formIds.forEach(function(formId) {
                    const rowsData = [];
                    $(`#tindakan-inputs-${formId} .tindakan-row`).each(function() {
                        const textareas = $(this).find('textarea');
                        rowsData.push({
                            tindakan: textareas.eq(0).val(),
                            bukti: textareas.eq(1).val()
                        });
                    });
                    formData.append(`tindakan_${formId}`, JSON.stringify(rowsData));
                });
                
                $.ajax({
                    url: '{{ route('dekan.rtl.session', ['rtl' => $rtl->id, 'auditee' => $auditeeId]) }}',
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function() {
                        console.log('Session saved');
                        if (typeof callback === 'function') {
                            callback(); // Panggil callback jika ada
                        }
                    },
                    error: function() {
                        console.error('Error saving session');
                        // Tampilkan kembali tombol submit jika error
                        $('#button-submit').removeClass('d-none');
                        $('#button-submit-loading').addClass('d-none');
                        Swal.fire('Error', 'Gagal menyimpan data sementara', 'error');
                    }
                });
            }

            // Reindex rows setelah hapus
            function reindexRows(formId) {
                const container = $(`#tindakan-inputs-${formId}`);
                container.find('.tindakan-row').each(function(index) {
                    $(this).find('textarea').each(function() {
                        const name = $(this).attr('name');
                        const newName = name.replace(/\[\d+\]/, `[${index}]`);
                        $(this).attr('name', newName);
                    });
                });
            }

            // Tambah tindakan input
            window.addTindakanInput = function(formId, kriteriaNama) {
                const container = $(`#tindakan-inputs-${formId}`);
                const index = container.find('.tindakan-row').length;
                const newRow = $(`
                    <div class="input-group mb-2 tindakan-row">
                        <textarea name="tindakan_${formId}[${index}][tindakan]" 
                                class="form-control small-textarea" 
                                placeholder="Tindakan yang sudah dilaksanakan" required></textarea>
                        <textarea name="tindakan_${formId}[${index}][bukti]" 
                                class="form-control small-textarea" 
                                placeholder="Bukti Tindakan (url)" 
                                pattern="https?://.+" required></textarea>
                        <button type="button" class="btn btn-danger btn-sm remove-tindakan ml-2">Hapus</button>
                    </div>
                `);
                
                container.append(newRow);
                save_session();
            };
            
            // Hapus tindakan input
            $(document).on('click', '.remove-tindakan', function() {
                const row = $(this).closest('.tindakan-row');
                const formId = row.find('textarea').first().attr('name').match(/tindakan_([^[]+)/)[1];

                row.remove();
                reindexRows(formId);
                save_session();

                //update data
                const formData = new FormData(document.getElementById('create-form'));

                //hapus item dari formData
                formData.delete(`tindakan_${formId}[tindakan]`);
                formData.delete(`tindakan_${formId}[bukti]`);
                save_session();
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

            function validateFormBeforeSubmit() {
                let isValid = true;
                $('.tindakan-row').each(function() {
                    const tindakan = $(this).find('textarea[name*="[tindakan]"]').val();
                    const bukti = $(this).find('textarea[name*="[bukti]"]').val();
                    
                    if (!tindakan || !bukti) {
                        isValid = false;
                        $(this).addClass('border border-danger');
                    }
                });
                return isValid;
            }
        });
    </script>
@endsection