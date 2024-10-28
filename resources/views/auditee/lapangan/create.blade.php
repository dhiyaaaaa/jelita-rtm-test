@extends('components.layout.auditee_layout')

@section('content')
    <form
        action="{{ route('auditee.lapangan.store_ptk', ['ptk' => $ptkId, 'auditee' => $auditee ? $auditee->id : 'null']) }}"
        method="post" id="create-form" enctype="multipart/form-data" class="block">
        @csrf
        <div class="row">
            <div class="col-md-9 ">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title text-bold">{{ $title }}</h3>
                    </div>
                    <div class="card-body">
                        @php
                            $no = ($daftarTilik->currentPage() - 1) * $daftarTilik->perPage() + 1;
                        @endphp
                        {{-- Page --}}
                        <input type="hidden" name="totalPage" value="{{ $daftarTilik->lastPage() }}">
                        <input type="hidden" name="page_{{ $daftarTilik->currentPage() }}"
                            value="{{ $daftarTilik->currentPage() }}">
                        @foreach ($daftarTilik as $item)
                            @php
                                // Jawaban Auditee
                                $jawaban = $jawabanPtk->where('form_id', $item->form->id)->first();

                                // Jawaban
                                $rencanaCollection = $jawabanPtkRencana->where('form_id', $item->form->id);
                                $deskripsiCollection = $jawabanPtkDeskripsi->where('form_id', $item->form->id);

                                $sessionRencana =
                                    $rencanaCollection && $rencanaCollection->isNotEmpty()
                                        ? $rencanaCollection->all()
                                        : $sessionFormData['rencana_' . $item->form->id] ?? [];

                                $sessionTarget =
                                    $jawaban && $jawaban->target
                                        ? $jawaban->target
                                        : $sessionFormData['target_' . $item->form->id] ?? '';

                                $sessionPic =
                                    $jawaban && $jawaban->pic
                                        ? $jawaban->pic
                                        : $sessionFormData['pic_' . $item->form->id] ?? '';

                                $isDisabled = true;
                                if (!$expired) {
                                    if (isset($status) && $status->status !== 'completed') {
                                        $isDisabled = false;
                                    }
                                }
                            @endphp
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title w-100">
                                        <span class="badge badge-success mb-3">
                                            {{ $item->form->instrumen->kode }}
                                        </span>
                                        <p>{{ $no++ }}. {{ $item->form->instrumen->pernyataan }}
                                            <span class="text-danger">&#42;</span>
                                        </p>
                                    </h4>
                                </div>
                                <div class="card-body">

                                    <div class="form-group">
                                        <label for="">Deskripsi / Uraian Temuan</label>

                                        @if ($deskripsiCollection->isNotEmpty())
                                            @foreach ($deskripsiCollection as $deskripsi)
                                                <p style="margin: 0; padding:0;">{{ $loop->iteration }}.
                                                    {{ $deskripsi->deskripsi }}</p>
                                            @endforeach
                                        @else
                                            <p>Belum Ada Jawaban</p>
                                        @endif

                                    </div>
                                    <div class="form-group">
                                        <label for="">Analisis</label>
                                        <p>{{ $jawaban ? $jawaban->analisis : 'Belum Ada Jawaban' }}</p>
                                    </div>

                                    <div class="form-group">
                                        <label for="">Penyebab</label>
                                        <p>{{ $jawaban ? $jawaban->akibat : 'Belum Ada Jawaban' }}</p>
                                    </div>

                                    <div class="form-group">
                                        <label for="">Rencana Tindakan Perbaikan</label>
                                        <span class="text-danger">&#42;</span>
                                        <div class="mb-3 {{ $isDisabled ? 'd-none' : '' }}">
                                            <button type="button" class="btn btn-secondary btn-sm"
                                                onclick="addRencanaInput('{{ $item->form->id }}')">Tambah
                                                Rencana</button>
                                            <button type="button" id="remove-rencana-btn-{{ $item->form->id }}"
                                                class="btn btn-danger btn-sm d-none"
                                                onclick="removeRencanaInput('{{ $item->form->id }}')">Hapus
                                                Rencana</button>
                                        </div>
                                        <div id="rencana-inputs-{{ $item->form->id }}">
                                            @if (!empty($sessionRencana))
                                                @foreach ($sessionRencana as $index => $rencana)
                                                    {{-- {{ $rencana }} --}}
                                                    <div class="input-group mb-2">
                                                        <textarea name="rencana_{{ $item->form->id }}[]"
                                                            class="form-control @if ($errors->has('rencana_' . $item->form->id . '.*')) is-invalid @endif" {{ $isDisabled ? 'disabled' : '' }}
                                                            data-rencana-id="{{ $rencana->id ?? '' }}" cols="30" rows="3">{{ $rencana->rencana ?? $rencana }}</textarea>
                                                        @if (!empty($rencana->id))
                                                            <input type="hidden" name="rencana-id-{{ $item->form->id }}[]"
                                                                value="{{ $rencana->id }}">
                                                        @endif
                                                    </div>
                                                @endforeach
                                            @else
                                                <div class="input-group mb-2">
                                                    <textarea name="rencana_{{ $item->form->id }}[]"
                                                        class="form-control @if ($errors->has('rencana_' . $item->form->id . '.*')) is-invalid @endif" cols="30" rows="3"
                                                        {{ $isDisabled ? 'disabled' : '' }}></textarea>
                                                </div>
                                            @endif
                                        </div>

                                        @if ($errors->has('rencana_' . $item->form->id . '.*'))
                                            <span class="text-danger d-block"
                                                style="font-size: 14px">{{ $errors->first('rencana_' . $item->form->id . '.*') }}</span>
                                        @endif
                                    </div>

                                    <div class="form-group">
                                        <label for="">Target Penyelesaian</label>
                                        <span class="text-danger">&#42;</span>
                                        <textarea name="target_{{ $item->form->id }}" cols="10" rows="5"
                                            class="form-control @if ($errors->has('target_' . $item->form->id)) is-invalid @endif" {{ $isDisabled ? 'disabled' : '' }}>{{ old('target_' . $item->form->id, $sessionTarget) }}</textarea>

                                        @if ($errors->has('target_' . $item->form->id))
                                            <span class="text-danger d-block"
                                                style="font-size: 14px">{{ $errors->first('target_' . $item->form->id) }}</span>
                                        @endif
                                    </div>

                                    <div class="form-group">
                                        <label for="">PIC</label>
                                        <span class="text-danger">&#42;</span>
                                        <textarea name="pic_{{ $item->form->id }}" cols="10" rows="5"
                                            class="form-control @if ($errors->has('pic_' . $item->form->id)) is-invalid @endif" {{ $isDisabled ? 'disabled' : '' }}>{{ old('pic_' . $item->form->id, $sessionPic) }}</textarea>

                                        @if ($errors->has('pic_' . $item->form->id))
                                            <span class="text-danger d-block"
                                                style="font-size: 14px">{{ $errors->first('pic_' . $item->form->id) }}</span>
                                        @endif
                                    </div>

                                    {{-- Save per Nomor --}}
                                    @if (isset($status) && $status->status !== 'completed' && !$expired)
                                        <div class="mt-3 mb-3">
                                            <button id="simpan_{{ $item->form->id }}" class="btn btn-warning"
                                                type="button">Simpan</button>

                                            <button id="simpan-button-loading_{{ $item->form->id }}"
                                                class="btn btn-warning d-none" type="button" disabled>
                                                <span class="spinner-border spinner-border-sm" role="status"
                                                    aria-hidden="true"></span>
                                                Loading...
                                            </button>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- Pagination --}}
            <div class="col-md-3">
                <div class="pagination-container">
                    <div class="card card-primary">
                        <div class="card-header">
                            Halaman
                        </div>
                        <div class="card-body">
                            <div class="pagination-wrapper">
                                {{ $daftarTilik->links('pagination::bootstrap-4') }}
                            </div>

                            @if (($daftarTilik->currentPage() == $daftarTilik->lastPage()) == 1)
                                @if (isset($status) && $status->status !== 'completed' && !$expired)
                                    <div class="mb-3">
                                        <input type="hidden" name="final" value="final">
                                        <button type="submit" id="button-submit" class="btn btn-primary">Submit</button>
                                        <button id="button-submit-loading" class="btn btn-primary d-none" type="button"
                                            disabled>
                                            <span class="spinner-border spinner-border-sm" role="status"
                                                aria-hidden="true"></span>
                                            Loading...
                                        </button>
                                    </div>
                                @else
                                    @if ($daftarTilik->lastPage() !== 1)
                                        <div>
                                            <a id="previous" href="{{ $daftarTilik->previousPageUrl() }}"
                                                class="btn btn-primary mb-3">Previous</a>
                                        </div>
                                    @endif
                                @endif
                            @else
                                <div class="mb-3">
                                    @if ($daftarTilik->currentPage() > 1)
                                        <a id="previous" href="{{ $daftarTilik->previousPageUrl() }}"
                                            class="btn btn-primary">Previous</a>
                                    @endif
                                    <a id="next" href="{{ $daftarTilik->nextPageUrl() }}"
                                        class="btn btn-primary">Next</a>
                                </div>
                            @endif

                            @if (isset($status) && $status->status === 'completed' && !$expired)
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
                            <a href="{{ route('auditee.lapangan') }}" class="btn btn-outline-secondary">Kembali</a>
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
                    url: "{{ route('auditee.lapangan.isi_ptk', ['ptk' => $ptkId]) }}",
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

            $('textarea[name^="rencana_"], textarea[name^="target_"], textarea[name^="pic_"]').on('input',
                debounce(function() {
                    save_session();
                }, 1000));

            @foreach ($daftarTilik as $item)
                tombol_hapus_rencana('{{ $item->form->id }}');
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

        function save_jawaban(formId) {
            event.preventDefault();

            // Rencana Selector
            const rencanaSelectors = document.querySelectorAll(`[name="rencana_${formId}[]"]`);
            let rencanaList = [];
            rencanaSelectors.forEach((selector) => {
                rencanaList.push(selector.value);
            });

            // Target Selector
            const targetSelector = document.querySelector(`[name="target_${formId}"]`);
            const target = targetSelector ? targetSelector.value : null;

            // PIC Selector
            const picSelector = document.querySelector(`[name="pic_${formId}"]`);
            const pic = picSelector ? picSelector.value : null;

            // Loading
            document.getElementById(`simpan_${formId}`).classList.add('d-none');
            document.getElementById(`simpan-button-loading_${formId}`).classList.remove('d-none');

            // Form Data
            const formData = new FormData();
            rencanaList.forEach((rencana, index) => {
                formData.append(`rencana_${formId}[]`, rencana);
            });
            formData.append(`target_${formId}`, target);
            formData.append(`pic_${formId}`, pic);

            $.ajax({
                url: '{{ route('auditee.lapangan.save_ptk', ['ptk' => $ptkId, 'auditee' => $auditee ? $auditee->id : 'null']) }}',
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
                url: '{{ route('auditee.lapangan.session_ptk', ['ptk' => $ptkId, 'auditee' => $auditee ? $auditee->id : 'null']) }}',
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

        function addRencanaInput(formid) {
            var rencanaInputHtml = `
                <div class="input-group mb-2">
                    <textarea name="rencana_${formid}[]" class="form-control" cols="30" rows="3"></textarea>
                </div>`;
            $('#rencana-inputs-' + formid).append(rencanaInputHtml);
            tombol_hapus_rencana(formid);

            $('#rencana-inputs-' + formid).on('input', debounce(function() {
                save_session();
            }, 1000));
        }

        function removeRencanaInput(formid) {
            var rencanaInputs = $('#rencana-inputs-' + formid).find('.input-group');
            if (rencanaInputs.length > 1) {
                var lastInputGroup = rencanaInputs.last();
                var rencanaId = lastInputGroup.find('input[type="hidden"]').val();

                if (rencanaId) {
                    save_session();
                }

                lastInputGroup.remove();
                save_session();
            }
            tombol_hapus_rencana(formid);
        }

        function tombol_hapus_rencana(formid) {
            var rencanaInputs = $('#rencana-inputs-' + formid).find('.input-group');
            var removerencanaBtn = $('#remove-rencana-btn-' + formid);
            if (rencanaInputs.length > 1) {
                removerencanaBtn.removeClass('d-none');
            } else {
                removerencanaBtn.addClass('d-none');
            }
        }
    </script>
@endsection
