@extends('components.layout.auditee_layout')

@section('content')
    <form
        action="{{ route('auditor.tindak-lanjut.store_form', ['monitoring' => $monitoring->id, 'auditor' => $auditor->id]) }}"
        method="post" id="create-form" enctype="multipart/form-data" class="block">
        @csrf
        <input type="hidden" name="kriteria" value="{{ $kriteriaId }}">
        
        <div class="row">
            <div class="col-md-9 ">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title text-bold">{{ $title }}</h3>
                    </div>
                    <div class="card-body">
                        @php
                            $no = ($temuanNegatif->currentPage() - 1) * $temuanNegatif->perPage() + 1;
                        @endphp

                        <input type="hidden" name="totalPage" value="{{ $temuanNegatif->lastPage() }}">
                        <input type="hidden" name="page_{{ $temuanNegatif->currentPage() }}"
                            value="{{ $temuanNegatif->currentPage() }}"value="{{ $temuanNegatif->currentPage() }}">

                        @foreach ($temuanNegatif as $item)
                            @php
                                //Monitoring RTL Auditor
                                $jawaban = $isianStatus->where('form_id', $item->form->id)->first();
                                $catatanCollection = $isianCatatan->where('form_id', $item->form->id);

                                $sessionStatus =
                                    $jawaban && $jawaban->status
                                        ? $jawaban->status
                                        : $sessionFormData['status_' . $item->form->id] ?? '';

                                $sessionCatatan =
                                    $catatanCollection && $catatanCollection->isNotEmpty()
                                        ? $catatanCollection->all()
                                        : $sessionFormData['catatan_' . $item->form->id] ?? [];

                                //Disabled
                                $isDisabled = true;
                                if (isset($status) && $status->status !== 'completed') {
                                    $isDisabled = false;
                                }
                            @endphp


                            <div class="card mb-3">
                                <div class="card-header">
                                    {{-- Pernyataan --}}
                                    <h4 class="card-title">
                                        <span class="badge badge-success mb-3">
                                            {{ $item->form->instrumen->kode }}</span>
                                        <p>{{ $no++ }}. {{ $item->form->instrumen->pernyataan }}
                                            <span class="text-danger">&#42;</span>
                                        </p>
                                    </h4>
                                </div>
                                <div class="card-body">
                                    <div class="mb-4">
                                        <label class="font-weight-bold">Rencana Tindakan yang sudah dibuat</label>
                                        <div class="mt-2">
                                            @if ($item->form->rtm_tindak_lanjut->isNotEmpty())
                                                @foreach ($item->form->rtm_tindak_lanjut as $index => $tindakan)
                                                    <div class="mb-2">
                                                        <p class="text-muted">
                                                            {{ $index + 1 }}. Tindakan: {{ $tindakan->tindakan }}<br>
                                                            PIC: {{ $tindakan->pic }}<br>
                                                            Waktu: {{ $tindakan->waktu }}
                                                        </p>
                                                    </div>
                                                @endforeach
                                            @else
                                                <p class="text-muted">Tidak ada Rencana</p>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="mb-4">
                                        <label class="font-weight-bold">Bukti dan Tindakan yang sudah dilaksanakan</label>
                                        <div class="mt-2">
                                            @if ($item->form->rtl_form->isNotEmpty())
                                                @foreach ($item->form->rtl_form as $index => $tindakan)
                                                    <div class="mb-2">
                                                        <p class="text-muted">
                                                            {{ $index + 1 }}. Tindakan: {{ $tindakan->tindakan }}<br>
                                                            Bukti: {{ $tindakan->bukti }}<br>
                                                        </p>
                                                    </div>
                                                @endforeach
                                            @else
                                                <p class="text-muted">Tidak ada Tindakan</p>
                                            @endif
                                        </div>
                                    </div>

                                    {{-- Status Tindak Lanjut --}}
                                    <div class="form-group">
                                        <label for="">Status Tindak Lanjut</label>
                                        <span class="text-danger">&#42;</span>
                                        <div class="custom-control custom-radio">
                                            <input
                                                class="custom-control-input status @if ($errors->has('status_' . $item['form']->id)) is-invalid @endif"
                                                type="radio" id="statusSelesai-{{ $item->form->id }}"
                                                name="status_{{ $item->form->id }}" value="selesai"
                                                {{ old('status_' . $item->form->id, $sessionStatus) == 'selesai' ? 'checked' : '' }}
                                                {{ $isDisabled ? 'disabled' : '' }}>
                                            <label for="statusSelesai-{{ $item->form->id }}" class="custom-control-label"
                                                style="font-weight: 500">
                                                Selesai
                                            </label>
                                        </div>
                                        <div class="custom-control custom-radio">
                                            <input
                                                class="custom-control-input status @if ($errors->has('status_' . $item['form']->id)) is-invalid @endif"
                                                type="radio" id="statusProses-{{ $item->form->id }}"
                                                name="status_{{ $item->form->id }}" value="proses"
                                                {{ old('status_' . $item->form->id, $sessionStatus) == 'proses' ? 'checked' : '' }}
                                                {{ $isDisabled ? 'disabled' : '' }}>
                                            <label for="statusProses-{{ $item->form->id }}"
                                                class="custom-control-label" style="font-weight: 500">
                                                Dalam Proses
                                            </label>
                                        </div>
                                        <div class="custom-control custom-radio">
                                            <input
                                                class="custom-control-input status @if ($errors->has('status_' . $item['form']->id)) is-invalid @endif"
                                                type="radio" id="statusBelumDilaksanakan-{{ $item->form->id }}"
                                                name="status_{{ $item->form->id }}" value="belum_dilaksanakan"
                                                {{ old('status_' . $item->form->id, $sessionStatus) == 'belum_dilaksanakan' ? 'checked' : '' }}
                                                {{ $isDisabled ? 'disabled' : '' }}>
                                            <label for="statusBelumDilaksanakan-{{ $item->form->id }}"
                                                class="custom-control-label" style="font-weight: 500">
                                                Belum Dilaksanakan
                                            </label>
                                        </div>
                                        
                                    </div>

                                    <div class="form-group">
                                        <label for="">Keterangan/Catatan Auditor</label>
                                        <span class="text-danger">&#42;</span>
                                        <div class="mb-3" {{ $isDisabled ? 'disabled' : '' }}>
                                            <button type="button" class="btn btn-secondary btn-sm"
                                                onclick="addCatatanInput('{{ $item->form->id }}')">+Tambah
                                                Keterangan/Catatan</button>
                                            <button type="button" id="remove-catatan-btn-{{ $item->form->id }}"
                                                class="btn btn-danger btn-sm d-none"
                                                onclick="removeCatatanInput('{{ $item->form->id }}')">Hapus
                                                Keterangan/Catatan</button>
                                        </div>
                                        <div id="catatan-inputs-{{ $item->form->id }}">
                                            @if (!empty($sessionCatatan))
                                                @foreach ($sessionCatatan as $index => $catatan)
                                                    {{--$catatan --}}
                                                    <div class="input-group mb-2">
                                                        <textarea name="catatan_{{ $item->form->id }}[]"
                                                            class="form-control @if ($errors->has('catatan_' . $item->form->id . '.*')) is-invalid @endif"
                                                            data-catatan-id="{{ $catatan->id ?? '' }}" cols="30" rows="3">{{ $catatan->catatan ?? $catatan }}</textarea>
                                                        @if (!empty($catatan->id))
                                                            <input type="hidden"
                                                                name="catatan-id-{{ $item->form->id }}[]"
                                                                value="{{ $catatan->id }}">
                                                        @endif
                                                    </div>
                                                @endforeach
                                            @else
                                                <div class="input-group mb-2">
                                                    <textarea name="catatan_{{ $item->form->id }}[]"
                                                        class="form-control @if ($errors->has('catatan_' . $item->form->id . '.*')) is-invalid @endif" cols="30" rows="3"
                                                        {{ $isDisabled ? 'disabled' : '' }}></textarea>
                                                </div>
                                            @endif
                                        </div>

                                        @if ($errors->has('catatan_' . $item->form->id . '.*'))
                                            <span class="text-danger d-block"
                                                style="font-size: 14px">{{ $errors->first('catatan_' . $item->form->id . '.*') }}</span>
                                        @endif
                                    </div>

                                    {{-- Save per Nomor --}}
                                   
                                        <div class="mt-3 mb-3">
                                            <button id="simpan_{{ $item->form->id }}" class="btn btn-warning"
                                                type="button">Simpan</button>

                                            <button id="simpan-button-loading_{{ $item->form->id }}"
                                                class="btn btn-warning d-none" type="button">
                                                <span class="spinner-border spinner-border-sm" role="status"
                                                    aria-hidden="true"></span>
                                                Loading...
                                            </button>
                                        </div>
                                    
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
            <!-- Kolom Pagination -->
            <div class="col-md-3">
                <div class="pagination-container">
                    <div class="card sticky-top">
                        <div class="card-header bg-secondary text-white">Navigasi Halaman</div>
                        <div class="card-body">
                            <div class="pagination-wrapper">
                                {{ $temuanNegatif->links('pagination::bootstrap-4') }}
                            </div>

                            @if (($temuanNegatif->currentPage() == $temuanNegatif->lastPage()) == 1)
                                
                                    <div class="mb-3">
                                        <input type="hidden" name="final" value="final">
                                        <button type="submit" id="button-submit" class="btn btn-primary">Submit</button>
                                        <button id="button-submit-loading" class="btn btn-primary d-none" type="button">
                                            <span class="spinner-border spinner-border-sm" role="status"
                                                aria-hidden="true"></span>
                                            Loading...
                                        </button>
                                    </div>
                               
                            @else
                                <div class="mb-3">
                                    @if ($temuanNegatif->currentPage() > 1)
                                        <a id="previous" href="{{ $temuanNegatif->previousPageUrl() }}"
                                            class="btn btn-primary">Previous</a>
                                    @endif
                                    <a id="next" href="{{ $temuanNegatif->nextPageUrl() }}"
                                        class="btn btn-primary">Next</a>
                                </div>
                            @endif

                            @if (isset($status) && $status->status === 'completed')
                                <div class="mb-3">
                                    <button type="button" class="btn btn-warning" id="edit-button">Ubah</button>
                                    <button id="button-edit-loading" class="btn btn-warning d-none" type="button">
                                        <span class="spinner-border spinner-border-sm" role="status"
                                            aria-hidden="true"></span>
                                        Loading...
                                    </button>
                                </div>
                            @endif
                            <a href="{{ route('auditor.tindak-lanjut.show', $jadwal->id) }}"
                                class="btn btn-outline-secondary w-100 mt-2">Kembali</a>
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

            // Form Submit
            form.on('submit', function(e) {
                e.preventDefault();

                Swal.fire({
                    title: 'Submit Form?',
                    text: "Pastikan bahwa status sudah terisi semua!",
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
                    url: "{{ route('auditor.tindak-lanjut.isi', ['monitoring' => $monitoring->id, 'kriteria' => $kriteriaId]) }}",
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

            $('textarea[name^="catatan_"]').on('input', debounce(function() {
                save_session();
            }, 1000));

            $('.status').on('change', function() {
                var id = $(this).attr('name').split('_')[1];
                var value = $(this).val();
                save_session();
            });

            @foreach ($temuanNegatif as $item)
                toggleRemoveCatatanButton('{{ $item->form->id }}');
            @endforeach

            $(document).on('click', '.pagination a', function(event) {
                event.preventDefault();
                const url = $(this).attr('href');

                save_session();

                setTimeout(function() {
                    window.location.href = url;
                }, 500);
            });
        });

        // Save ke DB
        function save_jawaban(formId) {
            event.preventDefault();

            // Status Selector
            const statusSelector = document.querySelector(`[name="status_${formId}"]:checked`);
            const status = statusSelector ? statusSelector.value : null;

            // Catatan Selector
            const catatanSelectors = document.querySelectorAll(`[name="catatan_${formId}[]"]`);
            let catatanList = [];
            catatanSelectors.forEach((selector) => {
                catatanList.push(selector.value);
            });

            // Loading
            document.getElementById(`simpan_${formId}`).classList.add('d-none');
            document.getElementById(`simpan-button-loading_${formId}`).classList.remove('d-none');

            // Form Data
            const formData = new FormData();
            formData.append(`status_${formId}`, status);
            catatanList.forEach((catatan, index) => {
                formData.append(`catatan_${formId}[]`, catatan);
            })

            $.ajax({
                url: '{{ route('auditor.tindak-lanjut.save_form', ['monitoring' => $monitoring->id, 'auditor' => $auditor->id]) }}',
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
            const formData = new FormData(document.getElementById('create-form'));

            $.ajax({
                url: '{{ route('auditor.tindak-lanjut.session', ['monitoring' => $monitoring->id, 'auditor' => $auditor->id]) }}',
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

        function addCatatanInput(formid) {
            var catatanInputHtml = `
                <div class="input-group mb-2">
                    <textarea name="catatan_${formid}[]" class="form-control" cols="30" rows="3"></textarea>
                </div>`;
            $('#catatan-inputs-' + formid).append(catatanInputHtml);
            toggleRemoveCatatanButton(formid);

            $('#catatan-inputs-' + formid).on('input', debounce(function() {
                // autoSaveFormLink($(this).attr('name'), $(this).attr('id'));
                save_session();
            }, 100));
        }

        function removeCatatanInput(formid) {
            var catatanInputs = $('#catatan-inputs-' + formid).find('.input-group');
            if (catatanInputs.length > 1) {
                var lastInputGroup = catatanInputs.last();
                var catatanId = lastInputGroup.find('input[type="hidden"]').val();

                if (catatanId) {
                    save_session();
                }

                lastInputGroup.remove();
                save_session();
            }
            toggleRemoveCatatanButton(formid);
        }

        function toggleRemoveCatatanButton(formid) {
            var catatanInputs = $('#catatan-inputs-' + formid).find('.input-group');
            var removeCatatanBtn = $('#remove-catatan-btn-' + formid);
            if (catatanInputs.length > 1) {
                removeCatatanBtn.removeClass('d-none');
            } else {
                removeCatatanBtn.addClass('d-none');
            }
        }
    </script>
@endsection
