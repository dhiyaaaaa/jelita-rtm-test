@extends('components.layout.auditee_layout')

@section('content')
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
                                // Ambil item pertama dalam grup untuk referensi form_id
                                $firstItem = $items->first();
                                $kriteriaId = $firstItem->kriteria->id ?? null;

                                // Jawaban Tindak Lanjut
                                $tindakanData = collect($jawabanTindakLanjut[$formId] ?? []);

                                // Session Form Data
                                $sessionData = $sessionFormData[$formId.'_'.$kriteriaId] ?? [];

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
                                            $tindakanDataKriteria =
                                                $tindakanData->where('kriteria_id', $kriteriaId) ?? [];
                                            $sessionDataKriteria = $sessionData[$kriteriaId] ?? [];
                                            $tindakanFinal = $tindakanDataKriteria->isNotEmpty()
                                                ? $tindakanDataKriteria->all()
                                                : collect($sessionDataKriteria);
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
                                                                <textarea name="pic_{{ $formId }}_{{ $kriteriaId }}[{{ $index }}][pic]"
                                                                        class="form-control small-textarea"
                                                                        placeholder="PIC"
                                                                        {{ $isDisabled ? 'disabled' : '' }}>{{ $tindakan['pic'] ?? '' }}</textarea>
                                                                        @error('tindakan_' . $formId . '_' . $kriteriaId . '.' . $index . '.tindakan')
                                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                                        @enderror
                                                            </div>
                                                            <div class="col-md-3">
                                                                <textarea name="waktu_{{ $formId }}_{{ $kriteriaId }}[{{ $index }}][waktu]"
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
                                                            <textarea name="pic_{{ $formId }}_{{ $kriteriaId }}[0][pic]"
                                                                      class="form-control small-textarea"
                                                                      placeholder="PIC"
                                                                      {{ $isDisabled ? 'disabled' : '' }}></textarea>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <textarea name="waktu_{{ $formId }}_{{ $kriteriaId }}[0][waktu]"
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
                                            <button id="simpan_{{ $formId }}" class="btn btn-warning"
                                                    type="button" {{ $isDisabled ? 'disabled' : '' }}>Simpan</button>

                                            <button id="simpan-button-loading_{{ $formId }}"
                                                    class="btn btn-warning d-none" type="button" disabled>
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
                                {{ $paginatedTemuanProdi->links('pagination::bootstrap-4') }}
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
    </style>

    <style>
    .small-textarea {
        resize: none; 
        height: 38px; 
        min-height: 38px; 
        max-height: 100px; 
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

            // Menampilkan error message dari session
            @if (session('error_message'))
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: "{{ session('error_message') }}"
                });
            @endif

            // Form Submit
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
                        form.off('submit')
                            .submit();
                    }
                });
            });

            $('#edit-button').on('click', function() {
                $('#edit-button').addClass('d-none');
                $('#button-edit-loading').removeClass('d-none');

                $.ajax({
                    url: "{{ route('dekan.rtm-rtl.isi', ['rtmRtl' => $rtmRtl->id]) }}",
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

            // Menyesuaikan padding untuk pagination
            var $paginationContainer = $('.pagination-container');
            var navbarHeight = 56;
            $(window).on('scroll', function() {
                $paginationContainer.css('padding-top', $(this).scrollTop() > 0 ? (navbarHeight + 12) +
                    'px' : '12px');
            }).trigger('scroll');

            save_session();

            // Menyimpan jawaban tindakan
            $(document).on('click', '[id^=simpan_]', function() {
                var id = $(this).attr('id').split('_')[1];
                save_jawaban(id);
                save_session();
            });

            // Menyimpan perubahan saat input diubah
            $('textarea[name^="tindakan_"], textarea[name^="pic_"], textarea[name^="waktu_"]').on('input', debounce(
                save_session, 1000));

            // Fungsi menyimpan jawaban
            function save_jawaban(formId) {
                console.log("Menyimpan jawaban untuk formId:", formId);

                var tindakanList = [];
                $(`[id^=tindakan-inputs-${formId}]`).each(function() {
                    let kriteriaId = $(this).attr('id').split('-').slice(-1)[0]; // Ambil kriteriaId dari ID
                    let tindakanData = [];

                    $(this).find('.row.g-2').each(function(index) {
                        let tindakan = $(this).find(`textarea[name^="tindakan_${formId}_${kriteriaId}["]`)
                            .val() || '';
                        let pic = $(this).find(`textarea[name^="pic_${formId}_${kriteriaId}["]`).val() ||
                        '';
                        let waktu = $(this).find(`textarea[name^="waktu_${formId}_${kriteriaId}["]`)
                        .val() || '';

                        if (tindakan || pic || waktu) {
                            tindakanData.push({
                                tindakan: tindakan,
                                pic: pic,
                                waktu: waktu
                            });
                        }
                    });

                    if (tindakanData.length > 0) {
                        tindakanList.push({
                            kriteriaId: kriteriaId,
                            tindakan: tindakanData
                        });
                    }
                });

                console.log("Data yang dikirim:", tindakanList);

                if (tindakanList.length === 0) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Data tidak boleh kosong.'
                    });
                    return;
                }

                // Tampilkan loading
                $(`#simpan_${formId}`).addClass('d-none');
                $(`#simpan-button-loading_${formId}`).removeClass('d-none');

                // Kirim data ke backend
                $.ajax({
                    url: '{{ route('dekan.rtm-rtl-prodi.save_form', ['rtmRtl' => $rtmRtl->id, 'auditee' => $auditeeId]) }}',
                    type: 'POST',
                    data: {
                        formId: formId,
                        kriteria: tindakanList
                    },
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
                    },
                    complete: function() {
                        $(`#simpan_${formId}`).removeClass('d-none');
                        $(`#simpan-button-loading_${formId}`).addClass('d-none');
                    }
                });
            }

            // Simpan session AJAX
            function save_session() {
                var formData = new FormData();

                var currentPage = $('input[name^="page_"]').val();
                formData.append('currentPage', currentPage);

                var formId_kriteriaIds = [];
                $('textarea[name^="tindakan_"]').each(function() {
                    var name = $(this).attr('name');
                    var matches = name.match(/tindakan_([^[]+)/);
                    if (matches && matches[1]) {
                        var formId_kriteriaId = matches[1];
                        if (!formId_kriteriaIds.includes(formId_kriteriaId)) {
                            formId_kriteriaIds.push(formId_kriteriaId);
                        }
                    }
                });

                formData.append('formId_kriteriaIds', JSON.stringify(formId_kriteriaIds));

                formId_kriteriaIds.forEach(function(formId_kriteriaId) {
                    var tindakanData = [];
                    var formId = formId_kriteriaId.split('_')[0];
                    var kriteriaId = formId_kriteriaId.split('_')[1];

                    $(`#tindakan-inputs-${formId}-${kriteriaId}`).find('.row.g-2').each(function(index) {
                        var tindakan = $(this).find(`textarea[name^="tindakan_${formId_kriteriaId}["]`).val() || '';
                        var pic = $(this).find(`textarea[name^="pic_${formId_kriteriaId}["]`).val() || '';
                        var waktu = $(this).find(`textarea[name^="waktu_${formId_kriteriaId}["]`).val() || '';

                        if (tindakan || pic || waktu) {
                            tindakanData.push({
                                tindakan: tindakan,
                                pic: pic,
                                waktu: waktu
                            });
                        }
                    });

                    formData.append(`rows_${formId_kriteriaId}`, JSON.stringify(tindakanData));
                });

                $.ajax({
                    url: '{{ route('dekan.rtm-rtl-prodi.session', ['rtmRtl' => $rtmRtl->id, 'auditee' => $auditeeId]) }}',
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        if (response.success) {
                            console.log('Session berhasil disimpan.');
                        }
                    },
                    error: function() {
                        console.error('Gagal menyimpan session.');
                    }
                });
            }
            // Use event delegation for dynamic inputs
            $(document).on('input', 'textarea[name^="tindakan_"], textarea[name^="pic_"], textarea[name^="waktu_"]',
                debounce(save_session, 1000));

            // Fungsi debounce untuk optimasi input
            function debounce(func, delay) {
                let debounceTimer;
                return function() {
                    clearTimeout(debounceTimer);
                    debounceTimer = setTimeout(() => func.apply(this, arguments), delay);
                };
            }

            // Tambah input tindakan baru
            window.addTindakanInput = function(formId, kriteriaId) {
                const tindakanInputs = document.getElementById(`tindakan-inputs-${formId}-${kriteriaId}`);
                const newRow = document.createElement('div');
                const index = tindakanInputs.querySelectorAll('.row.g-2').length;
                newRow.classList.add('row', 'g-2', 'tindakan-row');
                newRow.innerHTML = `
                <div class="col-md-4">
                    <textarea name="tindakan_${formId}_${kriteriaId}[${index}][tindakan]" class="form-control small-textarea" placeholder="Rencana"></textarea>
                </div>
                <div class="col-md-3">
                    <textarea name="pic_${formId}_${kriteriaId}[${index}][pic]" class="form-control small-textarea" placeholder="PIC"></textarea>
                </div>
                <div class="col-md-3">
                    <textarea name="waktu_${formId}_${kriteriaId}[${index}][waktu]" class="form-control small-textarea" placeholder="Waktu"></textarea>
                </div>
                <div class="col-md-2 d-flex align-items-center">
                    <button type="button" class="btn btn-danger btn-sm me-2 remove-tindakan">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            `;
                tindakanInputs.appendChild(newRow);

                // Menambahkan event listener untuk tombol hapus
                const removeButton = newRow.querySelector('.remove-tindakan');
                removeButton.addEventListener('click', function() {
                    if (tindakanInputs.children.length > 1) {
                        tindakanInputs.removeChild(newRow);
                    }
                    reindexRows(formId, kriteriaId);
                    save_session();
                });
            };

            function reindexRows(formId, kriteriaId) {
                const container = document.getElementById(`tindakan-inputs-${formId}-${kriteriaId}`);
                if (!container) return;
                const rows = container.querySelectorAll('.tindakan-row');
                rows.forEach((row, index) => {
                    const inputs = row.querySelectorAll('textarea');
                    inputs[0].name = `tindakan_${formId}_${kriteriaId}[${index}][tindakan]`;
                    inputs[1].name = `pic_${formId}_${kriteriaId}[${index}][pic]`;
                    inputs[2].name = `waktu_${formId}_${kriteriaId}[${index}][waktu]`;
                });
            }

            // Hapus input tindakan
            $(document).on("click", ".remove-tindakan", function() {
                const inputField = $(this).closest(".tindakan-row").find("textarea:first");
                const matches = inputField.attr("name").match(/tindakan_([^[]+)/);
                if (matches && matches[1]) {
                    const formId = matches[1].split("_")[0];
                    const kriteriaId = matches[1].split("_")[1];
                    $(this).closest(".tindakan-row").remove();
                    reindexRows(formId, kriteriaId);
                    save_session();
                } else {
                    console.error("Could not extract formId");
                }
            });

        });
    </script>
@endsection