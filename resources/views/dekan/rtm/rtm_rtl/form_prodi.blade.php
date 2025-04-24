@extends('components.layout.auditee_layout')

@section('content')

    <form method="GET" action="{{ route('dekan.rtm-rtl.form_prodi', $rtmRtl->id) }}" class="mb-4">
        <div class="row">
            <div class="col-md-8">
                <div class="card shadow-sm">
                    <div class="card-header bg-light">
                        <h5 class="card-title mb-0">Filter Kriteria</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-8">
                                @foreach($kriteriaOptions as $kriteria)
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" 
                                        name="kriteria_ids[]" 
                                        id="kriteria_{{ $kriteria->id }}" 
                                        value="{{ $kriteria->id }}"
                                        {{ in_array($kriteria->id, $selectedKriteria) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="kriteria_{{ $kriteria->id }}">
                                        {{ $kriteria->nama }}
                                    </label>
                                </div>
                                @endforeach
                            </div>
                            <div class="col-md-4 d-flex align-items-center">
                                <button type="submit" class="btn btn-primary mr-2">
                                    <i class="fas fa-filter"></i> Filter
                                </button>
                                <a href="{{ route('dekan.rtm-rtl.form_prodi', $rtmRtl->id) }}" 
                                class="btn btn-outline-secondary">
                                    <i class="fas fa-sync-alt"></i> Reset
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <form action="{{ route('dekan.rtm-rtl-prodi.store_form', ['rtmRtl' => $rtmRtl->id, 'auditee' => $auditeeId]) }}"
        method="post" id="create-form" enctype="multipart/form-data" class="block">
        @csrf

        <div class="row">
            <div class="col-md-9">
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <h3 class="card-title text-bold">{{ $title }}</h3>
                    </div>
                    <div class="card-body">
                        @php
                            $no = ($paginatedTemuanProdi->currentPage() - 1) * $paginatedTemuanProdi->perPage() + 1;
                        @endphp
                        <input type="hidden" name="totalPage" value="{{ $paginatedTemuanProdi->lastPage() }}">
                        <input type="hidden" name="page_{{ $paginatedTemuanProdi->currentPage() }}"
                            value="{{ $paginatedTemuanProdi->currentPage() }}">

                        @foreach ($paginatedTemuanProdi->groupBy('form.id') as $formId => $items)
                            @php
                                
                                $currentFormData = $sessionFormData[$formId] ?? [];
                                // isDisabled
                                $isDisabled = false;

                                if (isset($status) && $status->status === 'completed' || !$auditee) {
                                    $isDisabled = true;
                                }
                            @endphp
                            <div class="card mb-4 shadow-sm border-light">
                                <div class="card-header bg-light">
                                    <h4 class="card-title w-100">{{ $items->first()->form->instrumen->kode }}</h4>
                                    <p><strong>Pernyataan:</strong> {{ $items->first()->form->instrumen->pernyataan }}</p>
                                </div>
                                <div class="card-body">
                                    @foreach ($items->groupBy('kriteria.nama') as $kriteriaNama => $prodiGroup)
                                        @php
                                            $kriteriaId = $prodiGroup->first()->kriteria->id ?? null;
                                            $tindakanFinal = $currentFormData[$kriteriaId]['tindakan'] ?? [];
                                        @endphp

                                        <div class="mb-4 p-3 border rounded">
                                            <h6 class="mb-3">
                                                @if ($kriteriaNama === 'Belum Memenuhi')
                                                    <span class="badge badge-danger">{{ $kriteriaNama }}</span>
                                                @elseif ($kriteriaNama === 'Memenuhi')
                                                    <span class="badge badge-warning">{{ $kriteriaNama }}</span>
                                                @elseif ($kriteriaNama === 'Melampaui')
                                                    <span class="badge badge-success">{{ $kriteriaNama }}</span>
                                                @else
                                                    <span class="badge badge-secondary">{{ $kriteriaNama }}</span>
                                                @endif
                                            </h6>
                                            @foreach ($prodiGroup as $item)
                                                <p><strong> {{ $item->prodi->jenjang->nama }}
                                                        {{ $item->prodi->nama }}:</strong>
                                                        @if ($item->kriteria->nama === 'Belum Memenuhi')
                                                            @if ($item->form->ptk_form_deskripsi->isNotEmpty())
                                                                @foreach ($item->form->ptk_form_deskripsi as $deskripsi)
                                                                    <p class="text-muted">{{ $deskripsi->deskripsi }}</p>
                                                                @endforeach
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
                                                            @else
                                                                <p class="text-muted">Tidak ada catatan</p>
                                                            @endif
                                                        @endif
                                                </p>
                                            @endforeach
                                            <div id="tindakan-inputs-{{ $formId }}-{{ $kriteriaId }}"
                                                data-kriteria-id="{{ $kriteriaId }}"
                                                data-form-id="{{ $formId }}"
                                                class="mt-3">
                                                @if (!empty($tindakanFinal) && (is_array($tindakanFinal) ? count($tindakanFinal) : $tindakanFinal->count()) > 0)
                                                    @foreach ($tindakanFinal as $index => $tindakan)
                                                        <div class="row g-2 mb-2 tindakan-row">
                                                            <div class="col-md-4">
                                                                <textarea name="tindakan_{{ $formId }}_{{ $kriteriaId }}[{{ $index }}][tindakan]"
                                                                        class="form-control small-textarea"
                                                                        placeholder="{{ $kriteriaNama === 'Belum Memenuhi' ? 'Rencana Perbaikan' : 'Rencana Peningkatan' }}"
                                                                        {{ $isDisabled ? 'disabled' : '' }}>{{ $tindakan['tindakan'] ?? '' }}</textarea>
                                                                        @error('tindakan_' . $formId . '_' . $kriteriaId . '.' . $index . '.tindakan')
                                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                                        @enderror
                                                            </div>
                                                            <div class="col-md-3">
                                                                <select name="tindakan_{{ $item->form->id }}_{{ $kriteriaId }}[{{ $index }}][pic]" 
                                                                    class="form-control select-pic @error('tindakan_'.$item->form->id.'.'.$index.'.pic') is-invalid @enderror"
                                                                    {{ $isDisabled ? 'disabled' : '' }}>
                                                                    <option value="">Pilih PIC</option>
                                                                    @foreach($picData as $pic)
                                                                        <option value="{{ $pic->user_id }}|{{ $pic->jabatan_id }}"
                                                                            {{ (isset($tindakan['pic']) && $tindakan['pic'] == ($pic->user_id . '|' . $pic->jabatan_id)) ? 'selected' : '' }}>
                                                                            {{ $pic->user_name }} ({{ $pic->jabatan_nama }})
                                                                            @if ($pic->prodi_nama)
                                                                                - {{ $pic->prodi_nama }}
                                                                            @elseif ($pic->fakultas_nama)
                                                                                - {{ $pic->fakultas_nama }}
                                                                            @elseif ($pic->unit_nama)
                                                                                - {{ $pic->unit_nama }}
                                                                            @endif
                                                                        </option>
                                                                    @endforeach
                                                                </select>
                                                                @error('tindakan_'.$item->form->id.'.'.$index.'.pic')
                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                                @enderror
                                                            </div>
                                                            <div class="col-md-3">
                                                                <textarea name="tindakan_{{ $formId }}_{{ $kriteriaId }}[{{ $index }}][waktu]"
                                                                        class="form-control small-textarea"
                                                                        placeholder="{{ $kriteriaNama === 'Belum Memenuhi' ? 'Waktu Perbaikan' : 'Waktu Peningkatan' }}"
                                                                        {{ $isDisabled ? 'disabled' : '' }}>{{ $tindakan['waktu'] ?? '' }}</textarea>
                                                                        @error('tindakan_' . $formId . '_' . $kriteriaId . '.' . $index . '.tindakan')
                                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                                        @enderror
                                                            </div>
                                                            <div class="col-md-2 d-flex align-items-center justify-content-around">
                                                                <button type="button" {{ $isDisabled ? 'disabled' : '' }}
                                                                        class="btn btn-danger btn-sm me-2 remove-tindakan">
                                                                    <i class="fas fa-trash"></i>
                                                                </button>
                                                                <button type="button" class="btn btn-secondary btn-sm" {{ $isDisabled ? 'disabled' : '' }}
                                                                        onclick="addTindakanInput('{{ $formId }}', '{{ $kriteriaId }}')">
                                                                    <i class="fas fa-plus"></i>
                                                                </button>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                @else
                                                    <div class="row g-2 mb-2 tindakan-row">
                                                        <div class="col-md-4">
                                                            <textarea name="tindakan_{{ $formId }}_{{ $kriteriaId }}[0][tindakan]"
                                                                      class="form-control small-textarea"
                                                                      placeholder="{{ $kriteriaNama === 'Belum Memenuhi' ? 'Rencana Perbaikan' : 'Rencana Peningkatan' }}"
                                                                      {{ $isDisabled ? 'disabled' : '' }}></textarea>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <select name="tindakan_{{ $item->form->id }}_{{ $kriteriaId }}[0][pic]" class="form-control select-pic">
                                                                <option value="">Pilih PIC</option>
                                                                @foreach($picData as $pic)
                                                                    <option value="{{ $pic->user_id }}|{{ $pic->jabatan_id }}">
                                                                        {{ $pic->user_name }} ({{ $pic->jabatan_nama }})
                                                                        @if ($pic->prodi_nama)
                                                                            - {{ $pic->prodi_nama }}
                                                                        @elseif ($pic->fakultas_nama)
                                                                            - {{ $pic->fakultas_nama }}
                                                                        @endif
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <textarea name="tindakan_{{ $formId }}_{{ $kriteriaId }}[0][waktu]"
                                                                      class="form-control small-textarea"
                                                                      placeholder="{{ $kriteriaNama === 'Belum Memenuhi' ? 'Waktu Perbaikan' : 'Waktu Peningkatan' }}"
                                                                      {{ $isDisabled ? 'disabled' : '' }}></textarea>
                                                        </div>
                                                        <div class="col-md-2 d-flex align-items-center">
                                                            <button type="button" {{ $isDisabled ? 'disabled' : '' }}
                                                                    class="btn btn-danger btn-sm me-2 remove-tindakan">
                                                                <i class="fas fa-trash"></i>
                                                            </button>
                                                            <button type="button" class="btn btn-secondary btn-sm" {{ $isDisabled ? 'disabled' : '' }}
                                                                    onclick="addTindakanInput('{{ $formId }}', '{{ $kriteriaId }}')">
                                                                <i class="fas fa-plus"></i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                    
                                    @if (isset($status) && $status->status !== 'completed')
                                        <div class="mb-3">
                                            <button id="simpan_{{ $formId }}" 
                                                    class="btn btn-warning"
                                                    type="button" 
                                                    {{ $isDisabled ? 'disabled' : '' }}>
                                                Simpan
                                            </button>

                                            <button id="simpan-button-loading_{{ $formId }}"
                                                    class="btn btn-warning d-none" 
                                                    type="button" 
                                                    disabled>
                                                <span class="spinner-border spinner-border-sm" role="status"
                                                    aria-hidden="true"></span>
                                                Loading...
                                            </button>
                                            <p class="mt-2 text-muted">Harap tekan tombol "Simpan" untuk menyimpan jawaban.</p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="pagination-container">
                    <div class="card shadow-sm">
                        <div class="card-header bg-primary text-white">
                            Halaman
                        </div>
                        <div class="card-body">
                            <div class="pagination-wrapper text-center">
                                {{ $paginatedTemuanProdi->appends(request()->except('page'))->links('pagination::bootstrap-4') }}
                            </div>
                            @if (($paginatedTemuanProdi->currentPage() == $paginatedTemuanProdi->lastPage()) == 1)
                                @if (isset($status) && $status->status !== 'completed')
                                    <div class="text-center">
                                        <input type="hidden" name="final" value="final">
                                        <button type="submit" id="button-submit" class="btn btn-primary" {{ $isDisabled ? 'disabled' : '' }}>Submit</button>
                                    </div>
                                @endif
                            @else
                                <div class="text-center">
                                    @if ($paginatedTemuanProdi->currentPage() > 1)
                                        <a id="previous" href="{{ $paginatedTemuanProdi->previousPageUrl() }}"
                                           class="btn btn-primary">Previous</a>
                                    @endif
                                    <a id="next" href="{{ $paginatedTemuanProdi->nextPageUrl() }}"
                                       class="btn btn-primary">Next</a>
                                </div>
                            @endif
                            @if (isset($status) && $status->status === 'completed')
                                <div class="text-center mt-3">
                                    <button type="button" class="btn btn-warning" id="edit-button">Ubah</button>
                                </div>
                            @endif
                            <div class="text-center mt-3">
                                <a href="{{ route('dekan.rtm-rtl.form', $rtmRtl->id) }}"
                                   class="btn btn-outline-secondary">Kembali</a>
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
        .small-textarea {
            resize: none; 
            height: 38px; 
            min-height: 38px; 
            max-height: 100px; 
        }        
        .form-check {
            margin-bottom: 0.5rem;
            padding-left: 1.5rem;
        }

        .form-check-input {
            margin-left: -1.5rem;
            margin-top: 0.3rem;
        }

        .form-check-input:checked {
            background-color: #0d6efd;
            border-color: #0d6efd;
        }

        .form-check-label {
            margin-left: 0.25rem;
            cursor: pointer;
        }
    </style>
@endsection

@section('script')
    @if(session('success'))
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Berhasil',
                text: "{{ session('success') }}"
            });
        </script>
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

    <script>
        $(document).ready(function() {
            const form = $('#create-form');
            
            // Fungsi untuk menambahkan input tindakan baru
            window.addTindakanInput = function(formId, kriteriaId) {
                const container = $(`#tindakan-inputs-${formId}-${kriteriaId}`);
                const index = container.find('.tindakan-row').length;
                const kriteriaNama = $(`#kriteria-nama-${formId}-${kriteriaId}`).text().trim();
                
                const tindakanPlaceholder = kriteriaNama === 'Belum Memenuhi' ? 
                    'Rencana Perbaikan' : 'Rencana Peningkatan';
                const waktuPlaceholder = kriteriaNama === 'Belum Memenuhi' ? 
                    'Waktu Perbaikan' : 'Waktu Peningkatan';
                
                const newRow = $(`
                    <div class="row g-2 mb-2 tindakan-row">
                        <div class="col-md-4">
                            <textarea name="tindakan_${formId}_${kriteriaId}[${index}][tindakan]" 
                                    class="form-control small-textarea" 
                                    placeholder="${tindakanPlaceholder}" required></textarea>
                        </div>
                        <div class="col-md-3">
                            <select name="tindakan_${formId}_${kriteriaId}[${index}][pic]" class="form-control select-pic">
                                <option value="">Pilih PIC</option>
                                @foreach($picData as $pic)
                                    <option value="{{ $pic->user_id }}|{{ $pic->jabatan_id }}">
                                        {{ $pic->user_name }} ({{ $pic->jabatan_nama }})
                                        @if ($pic->prodi_nama)
                                            - {{ $pic->prodi_nama }}
                                        @elseif ($pic->fakultas_nama)
                                            - {{ $pic->fakultas_nama }}
                                        @endif
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <textarea name="tindakan_${formId}_${kriteriaId}[${index}][waktu]" 
                                    class="form-control small-textarea" 
                                    placeholder="${waktuPlaceholder}" required></textarea>
                        </div>
                        <div class="col-md-2 d-flex align-items-center justify-content-around">
                            <button type="button" class="btn btn-danger btn-sm me-2 remove-tindakan">
                                <i class="fas fa-trash"></i>
                            </button>
                            <button type="button" class="btn btn-secondary btn-sm" 
                                    onclick="addTindakanInput('${formId}', '${kriteriaId}')">
                                <i class="fas fa-plus"></i>
                            </button>
                        </div>
                    </div>
                `);
                
                container.append(newRow);
                saveSession();
            };

            // Fungsi untuk menghapus input tindakan
            $(document).on('click', '.remove-tindakan', function() {
                const row = $(this).closest('.tindakan-row');
                const container = row.parent();
                
                // Jangan hapus jika hanya tersisa satu row
                if (container.find('.tindakan-row').length > 1) {
                    row.remove();
                    saveSession();
                } else {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Peringatan',
                        text: 'Setidaknya harus ada satu rencana tindakan'
                    });
                }
            });

            // Fungsi untuk menyimpan data ke session
            function saveSession(callback) {
                const currentPage = $('input[name^="page_"]').val();
                const formData = new FormData();
                const formIds = [];
                const kriteriaMap = {};

                // Kumpulkan semua formId dan kriteriaId
                $('[id^="tindakan-inputs-"]').each(function() {
                    const formId = $(this).data('form-id');
                    const kriteriaId = $(this).data('kriteria-id');
                    
                    if (!formIds.includes(formId)) {
                        formIds.push(formId);
                    }
                    
                    if (!kriteriaMap[formId]) {
                        kriteriaMap[formId] = [];
                    }
                    
                    if (!kriteriaMap[formId].includes(kriteriaId)) {
                        kriteriaMap[formId].push(kriteriaId);
                    }
                });

                formData.append('currentPage', currentPage);
                formData.append('formIds', JSON.stringify(formIds));

                // Kumpulkan data tindakan untuk setiap form dan kriteria
                formIds.forEach(formId => {
                    formData.append(`kriteriaIds_${formId}`, JSON.stringify(kriteriaMap[formId]));
                    
                    kriteriaMap[formId].forEach(kriteriaId => {
                        const rowsData = [];
                        const container = $(`#tindakan-inputs-${formId}-${kriteriaId}`);
                        
                        container.find('.tindakan-row').each(function() {
                            const tindakan = $(this).find('textarea[name*="[tindakan]"]').val();
                            const pic = $(this).find('select[name*="[pic]"]').val();
                            const waktu = $(this).find('textarea[name*="[waktu]"]').val();
                            
                            if (tindakan || pic || waktu) {
                                rowsData.push({
                                    tindakan: tindakan,
                                    pic: pic,
                                    waktu: waktu
                                });
                            }
                        });
                        
                        formData.append(`tindakan_${formId}_${kriteriaId}`, JSON.stringify(rowsData));
                    });
                });

                $.ajax({
                    url: '{{ route('dekan.rtm-rtl-prodi.session', ['rtmRtl' => $rtmRtl->id, 'auditee' => $auditeeId]) }}',
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function() {
                        if (callback) callback();
                    },
                    error: function(xhr) {
                        console.error('Error saving session:', xhr.responseText);
                    }
                });
            }

            // Fungsi untuk menyimpan jawaban per form
            function saveJawaban(formId) {
                const formData = new FormData();
                let isValid = true;
                const errorMessages = [];

                const kriteriaContainers = $(`[id^="tindakan-inputs-${formId}-"]`);
                
                // PERBAIKAN: Gunakan data attribute untuk ambil kriteriaId
                kriteriaContainers.each(function() {
                    const container = $(this);
                    const kriteriaId = container.data('kriteria-id'); // Ambil dari data attribute
                    
                    container.find('.tindakan-row').each(function(index) {
                        const row = $(this);
                        const tindakan = row.find('textarea[name*="[tindakan]"]').val().trim();
                        const pic = row.find('select[name*="[pic]"]').val().trim();
                        const waktu = row.find('textarea[name*="[waktu]"]').val().trim();
                        
                        // Validasi
                        if (!tindakan) {
                            errorMessages.push(`Rencana tindakan untuk kriteria ${kriteriaId} baris ${index+1} wajib diisi`);
                            isValid = false;
                        }
                        if (!pic) {
                            errorMessages.push(`PIC untuk kriteria ${kriteriaId} baris ${index+1} wajib dipilih`);
                            isValid = false;
                        }
                        if (!waktu) {
                            errorMessages.push(`Waktu untuk kriteria ${kriteriaId} baris ${index+1} wajib diisi`);
                            isValid = false;
                        }
                        
                        if (isValid) {
                            formData.append(`tindakan_${formId}_${kriteriaId}[${index}][tindakan]`, tindakan);
                            formData.append(`tindakan_${formId}_${kriteriaId}[${index}][pic]`, pic);
                            formData.append(`tindakan_${formId}_${kriteriaId}[${index}][waktu]`, waktu);
                        }
                    });
                });

                if (!isValid) {
                    Swal.fire({ icon: 'error', title: 'Validasi Gagal', html: errorMessages.join('<br>') });
                    return;
                }

                // Kirim data ke server
                formData.append('formId', formId);
                formData.append('currentPage', $('input[name^="page_"]').val());
                
                const kriteriaIds = [];
                
                // Kumpulkan kriteriaIds sebagai array number
                $('[id^="tindakan-inputs-' + formId + '-"]').each(function() {
                    const kriteriaId = parseInt($(this).data('kriteria-id'));
                    if (!isNaN(kriteriaId)) {
                        kriteriaIds.push(kriteriaId);
                    }
                });
                formData.append('kriteriaIds', JSON.stringify(kriteriaIds));
                $.ajax({
                    url: '{{ route('dekan.rtm-rtl-prodi.save_form', ['rtmRtl' => $rtmRtl->id, 'auditee' => $auditeeId]) }}',
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil',
                            text: response.message
                        }).then(() => {
                            location.reload();
                        });
                    },
                    error: function(xhr) {
                        let response = xhr.responseJSON;
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            html: response?.message || 'Terjadi kesalahan saat menyimpan data'
                        });
                    },
                    complete: function() {
                        $(`#simpan_${formId}`).removeClass('d-none');
                        $(`#simpan-button-loading_${formId}`).addClass('d-none');
                    }
                });
            }

            // Handle klik tombol simpan per form
            $(document).on('click', '[id^=simpan_]', function() {
                const formId = $(this).attr('id').split('_')[1];
                
                saveJawaban(formId);
                saveSession();
            });

            // Handle submit final form
            form.on('submit', function(e) {
                e.preventDefault();
                
                Swal.fire({
                    title: 'Submit Form?',
                    text: 'Pastikan semua data sudah benar sebelum submit!',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, Submit',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Tampilkan loading
                        $('#button-submit').addClass('d-none');
                        $('#button-submit-loading').removeClass('d-none');
                        
                        // Simpan session terakhir sebelum submit
                        saveSession(function() {
                            form.off('submit').submit();
                        });
                    }
                });
            });

            // Handle tombol edit (ubah status ke in_progress)
            $('#edit-button').on('click', function() {
                Swal.fire({
                    title: 'Ubah Data?',
                    text: 'Anda akan mengubah status data menjadi dapat diedit kembali',
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
                            url: "{{ route('dekan.rtm-rtl-prodi.isi', ['rtmRtl' => $rtmRtl->id]) }}",
                            type: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            success: function() {
                                location.reload();
                            },
                            error: function() {
                                Swal.fire('Error!', 'Terjadi kesalahan', 'error');
                                $('#edit-button').removeClass('d-none');
                                $('#button-edit-loading').addClass('d-none');
                            }
                        });
                    }
                });
            });

            // Auto-save saat ada perubahan
            $(document).on('input change', 'textarea, select', function() {
                saveSession();
            });

            // Debounce untuk auto-save
            let debounceTimer;
            function debounceSaveSession() {
                clearTimeout(debounceTimer);
                debounceTimer = setTimeout(saveSession, 1000);
            }

            // Handle pagination
            $(document).on('click', '.pagination a, #previous, #next', function(e) {
                e.preventDefault();
                const url = $(this).attr('href');
                
                saveSession(function() {
                    window.location.href = url;
                });
            });
        });
    </script>
@endsection  