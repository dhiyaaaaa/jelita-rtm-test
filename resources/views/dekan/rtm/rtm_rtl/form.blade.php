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
                                ($paginatedTemuanFakultas->currentPage() - 1) * $paginatedTemuanFakultas->perPage() + 1;
                        @endphp
                        {{-- Page --}}
                        <input type="hidden" name="totalPage" value="{{ $paginatedTemuanFakultas->lastPage() }}">
                        <input type="hidden" name="page_{{ $paginatedTemuanFakultas->currentPage() }}"
                            value="{{ $paginatedTemuanFakultas->currentPage() }}">

                        @foreach ($paginatedTemuanFakultas as $item)
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
                                                        @elseif($kriteria->slug === 'melampuai') badge-succes
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
                                            <div class="form-group mb-4">
                                                <label class="font-weight-bold">Catatan Auditor</label>
                                                <div class="mt-2">
                                                    @if ($item->kriteria->slug === 'belum-memenuhi')
                                                        @if ($item->form->ptk_form_deskripsi->isNotEmpty())
                                                            @foreach ($item->form->ptk_form_deskripsi as $deskripsi)
                                                                <p class="text-muted">{{ $deskripsi->deskripsi }}</p>
                                                            @endforeach
                                                        @elseif ($jawabanAuditor && $jawabanAuditor->catatan)
                                                            <p class="text-muted">{{ $jawabanAuditor->catatan }}</p>
                                                        @else
                                                            <p class="text-muted">Tidak ada catatan</p>
                                                        @endif
                                                    @elseif ($item->kriteria->slug === 'memenuhi')
                                                        @if ($jawabanAuditor && $jawabanAuditor->catatan)
                                                            <p class="text-muted">{{ $jawabanAuditor->catatan }}</p>
                                                        @else
                                                            <p class="text-muted">Tidak ada catatan</p>
                                                        @endif
                                                    @elseif ($item->kriteria->slug === 'melampaui')
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
                                                    @if (!empty($sessionFormData[$item->form->id]['tindakan']))
                                                        @foreach ($sessionFormData[$item->form->id]['tindakan'] as $index => $tindakan)
                                                            <div class="input-group mb-2 tindakan-row">

                                                            <!-- Tindakan -->
                                                            <textarea
                                                                name="tindakan_{{ $item->form->id }}[{{ $index }}][tindakan]"
                                                                class="form-control small-textarea @error('tindakan_'.$item->form->id.'.'.$index.'.tindakan') is-invalid @enderror"
                                                                placeholder="{{ $item->kriteria->slug === 'belum-memenuhi' ? 'Rencana Perbaikan' : 'Rencana Peningkatan' }}"
                                                                {{ $isDisabled ? 'disabled' : '' }}>{{ $tindakan['tindakan'] ?? '' }}</textarea>
                                                            @error('tindakan_'.$item->form->id.'.'.$index.'.tindakan')
                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                            @enderror
                                                
                                                            <!-- Select PIC -->
                                                            <select name="tindakan_{{ $item->form->id }}[{{ $index }}][pic]" 
                                                                class="form-control select-pic @error('tindakan_'.$item->form->id.'.'.$index.'.pic') is-invalid @enderror"
                                                                {{ $isDisabled ? 'disabled' : '' }}>
                                                                <option value="">Pilih PIC</option>
                                                                @foreach($picData as $pic)
                                                                    <option value="{{ $pic->user_id }}|{{ $pic->jabatan_id }}"
                                                                        {{ (isset($tindakan['pic']) && $tindakan['pic'] == ($pic->user_id . '|' . $pic->jabatan_id)) ? 'selected' : '' }}>
                                                                        {{ $pic->jabatan_nama }}
                                                                        @if ($pic->prodi_nama)
                                                                            - {{ $pic->prodi_nama }}
                                                                        @elseif ($pic->fakultas_nama)
                                                                            - {{ $pic->fakultas_nama }}
                                                                        @endif
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                            @error('tindakan_'.$item->form->id.'.'.$index.'.pic')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                            @enderror

                                                            <!--  Waktu -->
                                                            <textarea
                                                                name="tindakan_{{ $item->form->id }}[{{ $index }}][waktu]"
                                                                class="form-control small-textarea @error('tindakan_'.$item->form->id.'.'.$index.'.waktu') is-invalid @enderror"
                                                                placeholder="{{ $item->kriteria->slug === 'belum-memenuhi' ? 'Waktu Perbaikan' : 'Waktu Peningkatan' }}"
                                                                {{ $isDisabled ? 'disabled' : '' }}>{{ $tindakan['waktu'] ?? '' }}</textarea>
                                                            @error('tindakan_'.$item->form->id.'.'.$index.'.waktu')
                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                            @enderror
                                                
                                                                <!-- Remove -->
                                                                <button type="button" class="btn btn-danger btn-sm remove-tindakan ml-2" {{ $isDisabled ? 'disabled' : '' }}>Hapus</button>
                                                            </div>
                                                        @endforeach
                                                    @else
                                                        <div class="input-group mb-2 tindakan-row">
                                                            <!-- Tindakan -->
                                                            <textarea
                                                                name="tindakan_{{ $item->form->id }}[0][tindakan]"
                                                                class="form-control small-textarea"
                                                                placeholder="{{ $item->kriteria->slug === 'belum-memenuhi' ? 'Rencana Perbaikan' : 'Rencana Peningkatan' }}"
                                                                {{ $isDisabled ? 'disabled' : '' }}></textarea>
                                                
                                                            <!-- Select PIC -->
                                                            <select name="tindakan_{{ $item->form->id }}[0][pic]" class="form-control select-pic">
                                                                <option value="">Pilih PIC</option>
                                                                @foreach($picData as $pic)
                                                                    <option value="{{ $pic->user_id }}|{{ $pic->jabatan_id }}">
                                                                        {{ $pic->jabatan_nama }}
                                                                        @if ($pic->prodi_nama)
                                                                            - {{ $pic->prodi_nama }}
                                                                        @elseif ($pic->fakultas_nama)
                                                                            - {{ $pic->fakultas_nama }}
                                                                        @endif
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                
                                                            <!--  Waktu -->
                                                            <textarea
                                                                name="tindakan_{{ $item->form->id }}[0][waktu]"
                                                                class="form-control small-textarea"
                                                                placeholder="{{ $item->kriteria->slug === 'belum-memenuhi' ? 'Waktu Perbaikan' : 'Waktu Peningkatan' }}"
                                                                {{ $isDisabled ? 'disabled' : '' }}></textarea>
                                                
                                                            <!-- Button to remove -->
                                                            <button type="button" class="btn btn-danger btn-sm remove-tindakan ml-2" {{ $isDisabled ? 'disabled' : '' }}>Hapus</button>
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
    </style>

    <style>
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
            const form = $('#create-form');
            
            function showLoading(buttonId) {
                $(`#${buttonId}`).addClass('d-none'); // sembunyikan tombol Simpan
                $(`#simpan-button-loading_${buttonId.split('_')[1]}`).removeClass('d-none'); // tampilkan tombol Loading
            }

            function hideLoading(buttonId) {
                $(`#simpan-button-loading_${buttonId.split('_')[1]}`).addClass('d-none'); // sembunyikan tombol Loading
                $(`#${buttonId}`).removeClass('d-none'); // tampilkan tombol Simpan lagi
            }
            
            // Function to validate form inputs
            function validateForm() {
                let isValid = true;
                
                $('.tindakan-row').each(function() {
                    const tindakan = $(this).find('textarea[name*="[tindakan]"]').val().trim();
                    const pic = $(this).find('select[name*="[pic]"]').val().trim();
                    const waktu = $(this).find('textarea[name*="[waktu]"]').val().trim();
                    
                    if (!tindakan || !pic || !waktu) {
                        isValid = false;
                        $(this).addClass('border border-danger');
                    }
                });
                
                return isValid;
            }

            // Rest of your code remains the same...
            window.addTindakanInput = function(formId, kriteriaNama) {
                const container = $(`#tindakan-inputs-${formId}`);
                const index = container.find('.tindakan-row').length;
                
                const tindakanPlaceholder = kriteriaNama === 'Belum Memenuhi' ? 
                    'Rencana Perbaikan' : 'Rencana Peningkatan';
                const waktuPlaceholder = kriteriaNama === 'Belum Memenuhi' ? 
                    'Target Waktu Perbaikan' : 'Target Waktu Peningkatan';
                
                const newRow = $(`
                    <div class="input-group mb-2 tindakan-row">
                        <textarea name="tindakan_${formId}[${index}][tindakan]" 
                                class="form-control small-textarea" 
                                placeholder="${tindakanPlaceholder}" required></textarea>
                        <select name="tindakan_${formId}[${index}][pic]" class="form-control select-pic">
                            <option value="">Pilih PIC</option>
                            @foreach($picData as $pic)
                                <option value="{{ $pic->user_id }}|{{ $pic->jabatan_id }}">
                                    {{ $pic->jabatan_nama }}
                                    @if ($pic->prodi_nama)
                                        - {{ $pic->prodi_nama }}
                                    @elseif ($pic->fakultas_nama)
                                        - {{ $pic->fakultas_nama }}
                                    @endif
                                </option>
                            @endforeach
                        </select>
                        <textarea name="tindakan_${formId}[${index}][waktu]" 
                                class="form-control small-textarea" 
                                placeholder="${waktuPlaceholder}" required></textarea>
                        <button type="button" class="btn btn-danger btn-sm remove-tindakan ml-2">Hapus</button>
                    </div>
                `);
                
                container.append(newRow);
                save_session();
            };

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
            
            // Function to save data to session
            function save_session(callback) {
                const formData = new FormData();
                const currentPage = $('input[name^="page_"]').val();
                const formIds = [];
                
                $('input[name="formIds[]"]').each(function() {
                    formIds.push($(this).val());
                });
                
                formData.append('currentPage', currentPage);
                formData.append('formIds', JSON.stringify(formIds));
                
                formIds.forEach(function(formId) {
                    const rowsData = [];
                    $(`#tindakan-inputs-${formId} .tindakan-row`).each(function() {
                        const tindakan = $(this).find('textarea[name*="[tindakan]"]').val();
                        const pic = $(this).find('select[name*="[pic]"]').val();
                        const waktu = $(this).find('textarea[name*="[waktu]"]').val();
                        
                        rowsData.push({
                            tindakan: tindakan,
                            pic: pic,
                            waktu: waktu
                        });
                    });
                    formData.append(`tindakan_${formId}`, JSON.stringify(rowsData));
                });
                
                $.ajax({
                    url: '{{ route('dekan.rtm-rtl.session', ['rtmRtl' => $rtmRtl->id, 'auditee' => $auditeeId]) }}',
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function() {
                        console.log('Session saved');
                        if (callback) callback();
                    },
                    error: function() {
                        console.error('Error saving session');
                        Swal.fire('Error', 'Gagal menyimpan data sementara', 'error');
                    }
                });
            }
            
            function save_jawaban(formId) {
                event.preventDefault();
                let isValid = true;
                const errorMessages = [];
                const formData = new FormData();
                
                $(`#tindakan-inputs-${formId} .tindakan-row`).each(function(index) {
                    const tindakanInput = $(this).find('textarea[name*="[tindakan]"]');
                    const picInput = $(this).find('select[name*="[pic]"]');
                    const waktuInput = $(this).find('textarea[name*="[waktu]"]');
                    
                    const tindakan = tindakanInput.val().trim();
                    const pic = picInput.val().trim();
                    const waktu = waktuInput.val().trim();
                    
                    tindakanInput.removeClass('is-invalid');
                    picInput.removeClass('is-invalid');
                    waktuInput.removeClass('is-invalid');
                    
                    if (!tindakan) {
                        tindakanInput.addClass('is-invalid');
                        isValid = false;
                        errorMessages.push(`Baris ${index + 1}: Rencana tindakan harus diisi`);
                    }
                    
                    if (!pic) {
                        picInput.addClass('is-invalid');
                        isValid = false;
                        errorMessages.push(`Baris ${index + 1}: PIC harus diisi`);
                    }
                    
                    if (!waktu) {
                        waktuInput.addClass('is-invalid');
                        isValid = false;
                        errorMessages.push(`Baris ${index + 1}: Target waktu harus diisi`);
                    }
                    
                    if (tindakan && pic && waktu) {
                        formData.append(`tindakan_${formId}[${index}][tindakan]`, tindakan);
                        formData.append(`tindakan_${formId}[${index}][pic]`, pic);
                        formData.append(`tindakan_${formId}[${index}][waktu]`, waktu);
                    }
                });
                
                if (!isValid) {
                    let title = 'Validasi Gagal';
                    if (errorMessages.some(msg => msg.includes('tindakan'))) {
                        title = 'Rencana Tindakan Belum Diisi';
                    } else if (errorMessages.some(msg => msg.includes('PIC'))) {
                        title = 'PIC Belum Diisi';
                    } else if (errorMessages.some(msg => msg.includes('waktu'))) {
                        title = 'Target Waktu Belum Diisi';
                    }
                    
                    Swal.fire({
                        icon: 'error',
                        title: title,
                        html: errorMessages.join('<br>')
                    });
                    return;
                }
                
                // Show loading state and keep it shown
                showLoading(`simpan_${formId}`);
                
                $.ajax({
                    url: '{{ route('dekan.rtm-rtl.save_form', ['rtmRtl' => $rtmRtl->id, 'auditee' => $auditeeId, 'kriteria' => $kriteria->id]) }}',
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
                        let response = JSON.parse(xhr.responseText);
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            html: response.message
                        });
                    }
                });
            }

            $(document).on('click', '[id^=simpan_]', function() {
                const formId = $(this).attr('id').split('_')[1];

                save_jawaban(formId);
                save_session();
            });
            
            form.on('submit', function(e) {
                e.preventDefault();
                
                if (!validateForm()) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Data tidak lengkap',
                        text: 'Harap lengkapi semua isian sebelum submit.'
                    });
                    return;
                }
                        
                Swal.fire({
                    title: 'Submit Form?',
                    text: 'Pastikan data sudah benar!',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: 'primary',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, Submit',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $('#button-submit').addClass('d-none');
                        $('#button-submit-loading').removeClass('d-none');
                        
                        save_session(function() {
                            $('#create-form').off('submit').submit();
                        });
                    }
                });
            });

            $('#edit-button').on('click', function() {
                const kriteriaId = $(this).data('kriteria');
                
                Swal.fire({
                    title: 'Ubah Data?',
                    text: 'Anda akan dapat mengedit jawaban kembali',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, Ubah',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $('#edit-button').addClass('d-none');
                        $('#button-edit-loading').removeClass('d-none');
                        
                        $.ajax({
                            url: "{{ route('dekan.rtm-rtl.isi', ['rtmRtl' => $rtmRtl->id, 'kriteria' => $kriteria->id]) }}",
                            type: 'POST',
                            data: {
                                kriteria: kriteriaId,
                                _token: "{{ csrf_token() }}"
                            },
                            success: function(response) {
                                if (response.success) {
                                    window.location.href = response.redirect_url;
                                } else {
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Error!',
                                        text: response.message || 'Terjadi kesalahan'
                                    });
                                    $('#edit-button').removeClass('d-none');
                                    $('#button-edit-loading').addClass('d-none');
                                }
                            },
                            error: function(xhr) {
                                console.error(xhr);
                                let response = xhr.responseJSON;
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error!',
                                    text: response?.message || 'Terjadi kesalahan'
                                });
                                $('#edit-button').removeClass('d-none');
                                $('#button-edit-loading').addClass('d-none');
                            }
                        });
                    }
                });
            });

            $(document).on('click', '.pagination a', function(event) {
                event.preventDefault();
                const url = $(this).attr('href');
                
                save_session(function() {
                    window.location.href = url;
                });
            });
            
            $('textarea[name*="[tindakan]"], select[name*="[pic]"], textarea[name*="[waktu]"]').on('input', function() {
                setTimeout(save_session, 1000);
            });
        });
    </script>

@endsection
