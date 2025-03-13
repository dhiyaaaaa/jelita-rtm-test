@extends('components.layout.auditee_layout')

@section('content')
    <form action="{{ route('dekan.rtm-rtl.store_form', ['rtmRtl' => $rtmRtl->id, 'auditee' => $auditeeId]) }}"
        method="post" id="create-form" enctype="multipart/form-data" class="block">
        @csrf
        @php
            Log::info('Error', $errors->all());
        @endphp
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
                            <input type="hidden" name="formIds[]" value="{{ $item->form->id }}">
                            <div class="row mb-4">
                                <input type="hidden" name="formId" value="{{ $item->form->id }}">
                                @php
                                    $sessionData = $sessionFormData[$item->form->id] ?? [];
                                    $sessionTindakan = $sessionData['tindakan'] ?? [];
                                    // dd($sessionFormData);

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
                                                </div>
                                            </div>

                                            {{-- Tindakan --}}
                                            <div class="form-group">
                                                <label class="font-weight-bold">
                                                    @if ($item->kriteria->nama === 'Belum Memenuhi')
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
                                                                <textarea
                                                                    name="tindakan_{{ $item->form->id }}[{{ $index }}][tindakan]"
                                                                    class="form-control small-textarea @error('tindakan_'.$item->form->id.'.'.$index.'.tindakan') is-invalid @enderror"
                                                                    placeholder="{{ $item->kriteria->nama === 'Belum Memenuhi' ? 'Rencana Perbaikan' : 'Rencana Peningkatan' }}"
                                                                    {{ $isDisabled ? 'disabled' : '' }}>{{ $tindakan['tindakan'] ?? '' }}</textarea>
                                                                @error('tindakan_'.$item->form->id.'.'.$index.'.tindakan')
                                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                                @enderror
                                                
                                                                <textarea
                                                                    name="pic_{{ $item->form->id }}[{{ $index }}][pic]"
                                                                    class="form-control small-textarea @error('pic_'.$item->form->id.'.'.$index.'.pic') is-invalid @enderror"
                                                                    placeholder="PIC"
                                                                    {{ $isDisabled ? 'disabled' : '' }}>{{ $tindakan['pic'] ?? '' }}</textarea>
                                                                @error('pic_'.$item->form->id.'.'.$index.'.pic')
                                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                                @enderror
                                                
                                                                <textarea
                                                                    name="waktu_{{ $item->form->id }}[{{ $index }}][waktu]"
                                                                    class="form-control small-textarea @error('waktu_'.$item->form->id.'.'.$index.'.waktu') is-invalid @enderror"
                                                                    placeholder="{{ $item->kriteria->nama === 'Belum Memenuhi' ? 'Waktu Perbaikan' : 'Waktu Peningkatan' }}"
                                                                    {{ $isDisabled ? 'disabled' : '' }}>{{ $tindakan['waktu'] ?? '' }}</textarea>
                                                                @error('waktu_'.$item->form->id.'.'.$index.'.waktu')
                                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                                @enderror
                                                
                                                                <button type="button"
                                                                    class="btn btn-danger btn-sm remove-tindakan ml-2" {{ $isDisabled ? 'disabled' : '' }}>Hapus</button>
                                                            </div>
                                                        @endforeach
                                                    @else
                                                        <div class="input-group mb-2 tindakan-row">
                                                            <textarea
                                                                name="tindakan_{{ $item->form->id }}[0][tindakan]"
                                                                class="form-control small-textarea"
                                                                placeholder="{{ $item->kriteria->nama === 'Belum Memenuhi' ? 'Rencana Perbaikan' : 'Rencana Peningkatan' }}"
                                                                {{ $isDisabled ? 'disabled' : '' }}></textarea>
                                                
                                                            <textarea
                                                                name="pic_{{ $item->form->id }}[0][pic]"
                                                                class="form-control small-textarea"
                                                                placeholder="PIC"
                                                                {{ $isDisabled ? 'disabled' : '' }}></textarea>
                                                
                                                            <textarea
                                                                name="waktu_{{ $item->form->id }}[0][waktu]"
                                                                class="form-control small-textarea"
                                                                placeholder="{{ $item->kriteria->nama === 'Belum Memenuhi' ? 'Waktu Perbaikan' : 'Waktu Peningkatan' }}"
                                                                {{ $isDisabled ? 'disabled' : '' }}></textarea>
                                                
                                                            <button type="button"
                                                                class="btn btn-danger btn-sm remove-tindakan ml-2" {{ $isDisabled ? 'disabled' : '' }}>Hapus</button>
                                                        </div>
                                                    @endif
                                                </div>

                                                @if (!$isDisabled)
                                                    <div class="mb-3">
                                                        @if (!$isDisabled)
                                                            <div class="mb-3">
                                                                <button type="button" class="btn btn-secondary btn-sm" {{ $isDisabled ? 'disabled' : '' }}
                                                                    onclick="addTindakanInput('{{ $item->form->id }}', '{{ $item->kriteria->nama }}')">
                                                                    Tambah Rencana
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
                                                    <p class="mt-2 text-muted">Harap tekan tombol "Simpan" untuk menyimpan perubahan.</p>
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
                                @role(['pj_fakultas', 'gpm', 'gkm'])
                                <a href="{{ route('dekan.rtm-rtl.form_prodi', $rtmRtl->id) }}" class="btn btn-primary mr-2">Temuan
                                    Prodi</a>
                                @endrole 

                                <a href="{{ route('dekan.jadwal-rtm.index') }}" class="btn btn-outline-secondary mr-2">Kembali</a>
                                
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
            var form = $('#create-form');

            // Menampilkan error message dari session
            @if (session('error_message'))
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: "{{ session('error_message') }}"
                });
            @endif

            $(document).ready(function() {
                $('#create-form').on('submit', function(e) {
                    e.preventDefault();

                    // Validasi semua input
                    var isValid = true;
                    $('input[name^="tindakan_"], input[name^="pic_"], input[name^="waktu_"]').each(
                        function() {
                            if ($(this).val().trim() === '') {
                                isValid = false;
                                $(this).addClass(
                                    'is-invalid'
                                ); // Tambahkan class untuk menandai input yang tidak valid
                            } else {
                                $(this).removeClass('is-invalid');
                            }
                        });

                    if (!isValid) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: 'Harap isi semua field tindakan, PIC, dan waktu sebelum submit!',
                        });
                        return;
                    }

                    // Lanjutkan submit jika semua input valid
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
                            save_session();
                            $('#create-form').off('submit').submit();
                        }
                    });
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
            $(document).on('input', 'textarea[name^="tindakan_"], textarea[name^="pic_"], textarea[name^="waktu_"]',
                debounce(function() {
                    save_session();
                }, 1000));
            // $('input[name^="tindakan_"], input[name^="pic_"], input[name^="waktu_"]').on('input', debounce(save_session, 1000));

            // Fungsi menyimpan jawaban
            function save_jawaban(formId) {
                console.log("Menyimpan jawaban untuk formId:", formId);
                console.log($(`#tindakan-inputs-${formId}`).html()); 

                var tindakanList = [];
                $(`#tindakan-inputs-${formId} .tindakan-row`).each(function() {
                    var textareas = $(this).find('textarea');
                    tindakanList.push({
                        tindakan: textareas.eq(0).val(),
                        pic: textareas.eq(1).val(),
                        waktu: textareas.eq(2).val()
                    });
                });
                // Tampilkan loading
                $(`#simpan_${formId}`).addClass('d-none');
                $(`#simpan-button-loading_${formId}`).removeClass('d-none');

                //Baris Baru
                var formData = new FormData();
                tindakanList.forEach((tindakan, index) => {
                    formData.append(`tindakan_${formId}[${index}][tindakan]`, tindakan.tindakan);
                    formData.append(`tindakan_${formId}[${index}][pic]`, tindakan.pic);
                    formData.append(`tindakan_${formId}[${index}][waktu]`, tindakan.waktu);
                });
                var currentPage = $('input[name^="page_"]').val();

                formData.append('currentPage', currentPage);


                console.log(tindakanList);

                $.ajax({
                    url: '{{ route('dekan.rtm-rtl.save_form', ['rtmRtl' => $rtmRtl->id, 'auditee' => $auditeeId]) }}',
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
                    },
                    complete: function() {
                        $(`#simpan_${formId}`).removeClass('d-none');
                        $(`#simpan-button-loading_${formId}`).addClass('d-none');
                    }
                });
            }

            // Fungsi untuk menyiapkan data form
            function prepareFormData() {
                var formData = new FormData();
                var currentPage = $('input[name="currentPage"]').val();
                if (!currentPage) {
                    var urlParams = new URLSearchParams(window.location.search);
                    currentPage = urlParams.get('page') || 1;
                }

                var formIds = [];
                $('textarea[name^="tindakan_"]').each(function() {
                    var name = $(this).attr('name');
                    var formId = name.match(/tindakan_([a-zA-Z0-9-]+)/)[1];
                    if (!formIds.includes(formId)) {
                        formIds.push(formId);
                    }
                });

                formData.append('currentPage', currentPage);
                formData.append('formIds', JSON.stringify(formIds));

                formIds.forEach(function(formId) {
                    var tindakan = [];
                    var pic = [];
                    var waktu = [];

                    $(`textarea[name^="tindakan_${formId}"]`).each(function() {
                        tindakan.push($(this).val());
                    });

                    $(`textarea[name^="pic_${formId}"]`).each(function() {
                        pic.push($(this).val());
                    });

                    $(`textarea[name^="waktu_${formId}"]`).each(function() {
                        waktu.push($(this).val());
                    });

                    formData.append(`tindakan_${formId}`, JSON.stringify(tindakan));
                    formData.append(`pic_${formId}`, JSON.stringify(pic));
                    formData.append(`waktu_${formId}`, JSON.stringify(waktu));
                });

                return formData;
            }

            function reindexRows(formId) {
                const container = document.getElementById(`tindakan-inputs-${formId}`);
                if (!container) return;
                const rows = container.querySelectorAll('.tindakan-row');
                rows.forEach((row, index) => {
                    const textareas = row.querySelectorAll('textarea');
                    textareas[0].name = `tindakan_${formId}[${index}][tindakan]`;
                    textareas[1].name = `pic_${formId}[${index}][pic]`;
                    textareas[2].name = `waktu_${formId}[${index}][waktu]`;
                });
            }

            // Simpan session AJAX
            function save_session(callback) {
                var formData = new FormData();
                var currentPage = $('input[name="currentPage"]').val();
                if (!currentPage) {
                    var urlParams = new URLSearchParams(window.location.search);
                    currentPage = urlParams.get('page') || 1;
                }

                var formIds = [];
                $('textarea[name^="tindakan_"]').each(function() {
                    var name = $(this).attr('name');
                    var formId = name.match(/tindakan_([a-zA-Z0-9-]+)/)[1];
                    if (!formIds.includes(formId)) {
                        formIds.push(formId);
                    }
                });

                formData.append('currentPage', currentPage);
                formData.append('formIds', JSON.stringify(formIds));

                formIds.forEach(function(formId) {
                    var rowsData = [];
                    $(`#tindakan-inputs-${formId} .tindakan-row`).each(function() {
                        var textareas = $(this).find('textarea');
                        rowsData.push({
                            tindakan: textareas.eq(0).val(),
                            pic: textareas.eq(1).val(),
                            waktu: textareas.eq(2).val()
                        });
                    });
                    formData.append(`rows_${formId}`, JSON.stringify(rowsData));
                });

                $.ajax({
                    url: '{{ route('dekan.rtm-rtl.session', ['rtmRtl' => $rtmRtl->id, 'auditee' => $auditeeId]) }}',
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        if (response.success) {
                            console.log('Session berhasil disimpan.');
                            // if (callback) callback();
                        }
                    },
                    error: function() {
                        console.error('Gagal menyimpan session.');
                    }
                });
            }

            function debounce(func, delay) {
                let debounceTimer;
                return function() {
                    clearTimeout(debounceTimer);
                    debounceTimer = setTimeout(() => func.apply(this, arguments), delay);
                };
            }


            // Tambah input tindakan baru
            window.addTindakanInput = function(formId, kriteriaNama) {
                let container = document.getElementById(`tindakan-inputs-${formId}`);
                if (!container) return;
                let index = container.querySelectorAll(".tindakan-row").length;
                let newRow = document.createElement("div");
                newRow.classList.add("input-group", "mb-2", "tindakan-row");

                // Tentukan placeholder berdasarkan kriteria
                const tindakanPlaceholder = kriteriaNama === 'Belum Memenuhi' ? 'Rencana Perbaikan' : 'Rencana Peningkatan';
                const waktuPlaceholder = kriteriaNama === 'Belum Memenuhi' ? 'Waktu Perbaikan' : 'Waktu Peningkatan';

                newRow.innerHTML = `
                    <textarea
                        name="tindakan_${formId}[${index}][tindakan]"
                        class="form-control small-textarea"
                        placeholder="${tindakanPlaceholder}"
                        required></textarea>
                    <textarea
                        name="pic_${formId}[${index}][pic]"
                        class="form-control small-textarea"
                        placeholder="PIC"
                        required></textarea>
                    <textarea
                        name="waktu_${formId}[${index}][waktu]"
                        class="form-control small-textarea"
                        placeholder="${waktuPlaceholder}"
                        required></textarea>
                    <button type="button" class="btn btn-danger btn-sm remove-tindakan ml-2">Hapus</button>
                `;
                container.appendChild(newRow);

                // Tambahkan event listener untuk tombol hapus
                newRow.querySelector('.remove-tindakan').addEventListener('click', function() {
                    newRow.remove();
                    reindexRows(formId);
                    save_session(); // Simpan session setelah menghapus input
                });
            };
            
            // Hapus input tindakan
            $(document).on("click", ".remove-tindakan", function() {
                const row = $(this).closest(".tindakan-row");
                const formId = row.find('textarea').first().attr('name').match(/tindakan_([^[]+)/)[1];
                row.remove();
                reindexRows(formId);
                save_session(); 
            });

        });
    </script>
@endsection
