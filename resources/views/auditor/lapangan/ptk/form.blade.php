@extends('components.layout.auditee_layout')

@section('content')
    <form action="{{ route('auditor.lapangan.ptk.store_form', ['ptk' => $ptkId, 'auditor' => $auditor->id]) }}" method="post"
        id="create-form" enctype="multipart/form-data" class="block">
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

                                // Jawaban Auditor
                                $deskripsiCollection = $jawabanPtkDeskripsi->where('form_id', $item->form->id);

                                $sessionDeskripsi =
                                    $deskripsiCollection && $deskripsiCollection->isNotEmpty()
                                        ? $deskripsiCollection->all()
                                        : $sessionFormData['deskripsi_' . $item->form->id] ?? [];

                                $sessionAnalisis =
                                    $jawaban && $jawaban->analisis
                                        ? $jawaban->analisis
                                        : $sessionFormData['analisis_' . $item->form->id] ?? '';

                                $sessionAkibat =
                                    $jawaban && $jawaban->akibat
                                        ? $jawaban->akibat
                                        : $sessionFormData['akibat_' . $item->form->id] ?? '';

                                $sessionKategori =
                                    $jawaban && $jawaban->kategori_temuan
                                        ? $jawaban->kategori_temuan
                                        : $sessionFormData['kategori_' . $item->form->id] ?? '';

                                // isDisabled
                                $isDisabled = true;
                                if (!$expired) {
                                    if (isset($status) && $status->status !== 'completed') {
                                        $isDisabled = false;
                                    }
                                }
                            @endphp
                            <div class="card">
                                <div class="card-header">
                                    {{-- Pertanyaan --}}
                                    <h4 class="card-title w-100">
                                        <span class="badge badge-success mb-3">
                                            {{ $item->form->instrumen->kode }}</span>
                                        <p>{{ $no++ }}. {{ $item->form->instrumen->pernyataan }}
                                            <span class="text-danger">&#42;</span>
                                        </p>
                                    </h4>
                                </div>
                                <div class="card-body">
                                    {{-- Jawaban Auditee --}}
                                    <div class="form-group">
                                        <div>
                                            <label for="">Jawaban Auditan</label>
                                            @forelse ($item->form->jawaban_auditee as $jawaban)
                                                <p>
                                                    {{ $jawaban->jawaban }}
                                                </p>
                                            @empty
                                                <p>Belum ada jawaban dari auditan</p>
                                            @endforelse
                                        </div>
                                        <div>
                                            <label for="">Link</label><br>
                                            @forelse ($item->form->link as $link)
                                                <a href="{{ $link->link }}" target="_blank">{{ $link->link }}</a><br>
                                            @empty
                                                <p>Belum ada link dari auditan</p>
                                            @endforelse
                                        </div>
                                    </div>

                                    {{-- Kategori Temuan --}}
                                    <div class="form-group">
                                        <label for="">Kategori Temuan</label>
                                        <span class="text-danger">&#42;</span>
                                        <div class="custom-control custom-radio">
                                            <input class="custom-control-input kategori" type="radio"
                                                id="kategoriObservasi-{{ $item->form->id }}"
                                                name="kategori_{{ $item->form->id }}" value="observasi"
                                                {{ old('kategori_' . $item->form->id, $sessionKategori) == 'observasi' ? 'checked' : '' }}
                                                {{ $isDisabled ? 'disabled' : '' }}>
                                            <label for="kategoriObservasi-{{ $item->form->id }}"
                                                class="custom-control-label" style="font-weight: 500">
                                                Observasi
                                            </label>
                                            <br>
                                        </div>
                                        <div class="custom-control custom-radio">
                                            <input class="custom-control-input kategori" type="radio"
                                                id="kategoriMinor-{{ $item->form->id }}"
                                                name="kategori_{{ $item->form->id }}" value="minor"
                                                {{ old('kategori_' . $item->form->id, $sessionKategori) == 'minor' ? 'checked' : '' }}
                                                {{ $isDisabled ? 'disabled' : '' }}>
                                            <label for="kategoriMinor-{{ $item->form->id }}" class="custom-control-label"
                                                style="font-weight: 500">
                                                KTS Minor
                                            </label>
                                            <br>
                                        </div>
                                        <div class="custom-control custom-radio">
                                            <input class="custom-control-input kategori" type="radio"
                                                id="kategoriMayor-{{ $item->form->id }}"
                                                name="kategori_{{ $item->form->id }}" value="mayor"
                                                {{ old('kategori_' . $item->form->id, $sessionKategori) == 'mayor' ? 'checked' : '' }}
                                                {{ $isDisabled ? 'disabled' : '' }}>
                                            <label for="kategoriMayor-{{ $item->form->id }}" class="custom-control-label"
                                                style="font-weight: 500">
                                                KTS Mayor
                                            </label>
                                            <br>
                                        </div>

                                        @if ($errors->has('kategori_' . $item->form->id))
                                            <span class="text-danger d-block"
                                                style="font-size: 14px">{{ $errors->first('kategori_' . $item->form->id) }}</span>
                                        @endif
                                    </div>

                                    {{-- Deskripsi --}}
                                    <div class="form-group">
                                        <label for="">Deskripsi</label>
                                        <span class="text-danger">&#42;</span>
                                        <div class="mb-3 {{ $isDisabled ? 'd-none' : '' }}">
                                            <button type="button" class="btn btn-secondary btn-sm"
                                                onclick="addDeskripsiInput('{{ $item->form->id }}')">Tambah
                                                Deskripsi/Temuan</button>
                                            <button type="button" id="remove-deskripsi-btn-{{ $item->form->id }}"
                                                class="btn btn-danger btn-sm d-none"
                                                onclick="removeDeskripsiInput('{{ $item->form->id }}')">Hapus
                                                Deskripsi/Temuan</button>
                                        </div>
                                        <div id="deskripsi-inputs-{{ $item->form->id }}">
                                            @if (!empty($sessionDeskripsi))
                                                @foreach ($sessionDeskripsi as $index => $deskripsi)
                                                    {{-- {{ $deskripsi }} --}}
                                                    <div class="input-group mb-2">
                                                        <textarea name="deskripsi_{{ $item->form->id }}[]" class="form-control" data-deskripsi-id="{{ $deskripsi->id ?? '' }}"
                                                            cols="30" rows="3" {{ $isDisabled ? 'disabled' : '' }}>{{ $deskripsi->deskripsi ?? $deskripsi }}</textarea>
                                                        @if (!empty($deskripsi->id))
                                                            <input type="hidden"
                                                                name="deskripsi-id-{{ $item->form->id }}[]"
                                                                value="{{ $deskripsi->id }}">
                                                        @endif
                                                    </div>
                                                @endforeach
                                            @else
                                                <div class="input-group mb-2">
                                                    <textarea name="deskripsi_{{ $item->form->id }}[]" class="form-control" cols="30" rows="3"
                                                        {{ $isDisabled ? 'disabled' : '' }}></textarea>
                                                </div>
                                            @endif
                                        </div>

                                        @if ($errors->has('deskripsi_' . $item->form->id . '.*'))
                                            <span class="text-danger d-block"
                                                style="font-size: 14px">{{ $errors->first('deskripsi_' . $item->form->id . '.*') }}</span>
                                        @endif
                                    </div>

                                    {{-- Analisis --}}
                                    <div class="form-group">
                                        <label for="">Analisis</label>
                                        <span class="text-danger">&#42;</span>
                                        <textarea name="analisis_{{ $item->form->id }}" cols="10" rows="5" class="form-control"
                                            {{ $isDisabled ? 'disabled' : '' }}>{{ old('analisis_' . $item->form->id, $sessionAnalisis) }}</textarea>

                                        @if ($errors->has('analisis_' . $item->form->id))
                                            <span class="text-danger d-block"
                                                style="font-size: 14px">{{ $errors->first('analisis_' . $item->form->id) }}</span>
                                        @endif
                                    </div>

                                    {{-- Penyebab --}}
                                    <div class="form-group">
                                        <label for="">Penyebab</label>
                                        <span class="text-danger">&#42;</span>
                                        <textarea name="akibat_{{ $item->form->id }}" cols="10" rows="5" class="form-control"
                                            {{ $isDisabled ? 'disabled' : '' }}>{{ old('akibat_' . $item->form->id, $sessionAkibat) }}</textarea>

                                        @if ($errors->has('akibat_' . $item->form->id))
                                            <span class="text-danger d-block"
                                                style="font-size: 14px">{{ $errors->first('akibat_' . $item->form->id) }}</span>
                                        @endif
                                    </div>

                                    {{-- Hapus dari PTK --}}
                                    <div class="form-group {{ $isDisabled ? 'd-none' : '' }}">
                                        <button type="button" class="btn btn-danger"
                                            id="hapus-ptk_{{ $item->form->id }}">Hapus dari
                                            Temuan Negatif</button>

                                        {{-- Loading --}}
                                        <button id="button-ptk-loading-{{ $item->form->id }}"
                                            class="btn btn-danger d-none" type="button" disabled>
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
                                <div>
                                    @if (isset($status) && $status->status !== 'completed' && !$expired)
                                        <input type="hidden" name="final" value="final">
                                        <button type="submit" id="button-submit" class="btn btn-primary">Submit</button>
                                        <button id="button-submit-loading" class="btn btn-primary d-none" type="button"
                                            disabled>
                                            <span class="spinner-border spinner-border-sm" role="status"
                                                aria-hidden="true"></span>
                                            Loading...
                                        </button>
                                        <div class="mt-3">
                                            <button id="save-button" class="btn btn-warning">Save</button>

                                            <button id="save-button-loading" class="btn btn-warning d-none"
                                                type="button" disabled>
                                                <span class="spinner-border spinner-border-sm" role="status"
                                                    aria-hidden="true"></span>
                                                Loading...
                                            </button>
                                        </div>
                                    @else
                                        @if ($daftarTilik->lastPage() !== 1)
                                            <a id="previous" href="{{ $daftarTilik->previousPageUrl() }}"
                                                class="btn btn-primary mb-3">Previous</a>
                                        @endif
                                    @endif
                                </div>
                            @else
                                <div class="">
                                    @if ($daftarTilik->currentPage() > 1)
                                        <a id="previous" href="{{ $daftarTilik->previousPageUrl() }}"
                                            class="btn btn-primary">Previous</a>
                                    @endif
                                    <a id="next" href="{{ $daftarTilik->nextPageUrl() }}"
                                        class="btn btn-primary">Next</a>
                                    <div class="mt-3">
                                        @if (isset($status) && $status->status !== 'completed' && !$expired)
                                            <button id="save-button" class="btn btn-warning">Save</button>

                                            <button id="save-button-loading" class="btn btn-warning d-none"
                                                type="button" disabled>
                                                <span class="spinner-border spinner-border-sm" role="status"
                                                    aria-hidden="true"></span>
                                                Loading...
                                            </button>
                                        @endif
                                    </div>
                                </div>
                            @endif

                            @if (isset($status) && $status->status === 'completed' && !$expired)
                                <div>
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
                            <a href="{{ route('auditor.lapangan.show', $jadwal->id) }}"
                                class="btn btn-outline-secondary mt-3">Kembali</a>
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

            form.on('submit', function(e) {
                $('#button-submit').addClass('d-none');
                $('#button-submit-loading').removeClass('d-none');
            });

            $('#edit-button').on('click', function() {
                $('#edit-button').addClass('d-none');
                $('#button-edit-loading').removeClass('d-none');

                $.ajax({
                    url: "{{ route('auditor.lapangan.ptk.isi_ptk', ['ptk' => $ptkId]) }}",
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

            $('#save-button').on('click', save_jawaban);

            $('textarea[name^="deskripsi_"], textarea[name^="analisis_"], textarea[name^="akibat_"]').on('input',
                debounce(function() {
                    save_session();
                }, 100));

            $('.kategori').on('change', function() {
                var id = $(this).attr('name').split('_')[1];
                var value = $(this).val();
                save_session();
            });

            @foreach ($daftarTilik as $item)
                toggleRemoveDeskripsiButton('{{ $item->form->id }}');
            @endforeach

            // Pagination
            $(document).on('click', '.pagination a', function(event) {
                event.preventDefault();
                const url = $(this).attr('href');

                save_session();

                setTimeout(function() {
                    window.location.href = url;
                }, 500);
            });

            // Hapus PTK
            $(document).on('click', '[id^=hapus-ptk_]', function() {
                var id = $(this).attr('id').split('_')[1];

                $('[id^=hapus-ptk_' + id + ']').addClass('d-none');

                $('#button-ptk-loading-' + id).removeClass('d-none');

                hapus_ptk(id);
            });
        });


        // Save ke DB
        function save_jawaban(e) {
            e.preventDefault();

            const formData = new FormData(document.getElementById('create-form'));

            document.getElementById('save-button').classList.add('d-none');
            document.getElementById('save-button-loading').classList.remove('d-none');
            // console.log(formData);
            $.ajax({
                url: '{{ route('auditor.lapangan.ptk.save_form', ['ptk' => $ptkId, 'auditor' => $auditor->id]) }}',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil',
                        text: response.message,
                    });

                    document.getElementById('save-button').classList.remove('d-none');
                    document.getElementById('save-button-loading').classList.add('d-none');
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
                    document.getElementById('save-button').classList.remove('d-none');
                    document.getElementById('save-button-loading').classList.add('d-none');
                }
            });
        }

        // Save ke Session
        function save_session() {
            const formData = new FormData(document.getElementById('create-form'));

            $.ajax({
                url: '{{ route('auditor.lapangan.ptk.session', ['ptk' => $ptkId, 'auditor' => $auditor->id]) }}',
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

        // Hapus PTK
        function hapus_ptk(formId) {
            $.ajax({
                url: "{{ route('auditor.lapangan.ptk.delete_instrumen', ['ptk' => $ptkId, 'form' => ':formId']) }}"
                    .replace(':formId', formId),
                type: 'POST',
                processData: false,
                contentType: false,
                success: function(response) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: response.message,
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.reload();
                        }
                    });

                    $('[id^=hapus-ptk_' + formId + ']').removeClass('d-none');
                    $('#button-ptk-loading-' + formId).addClass('d-none');

                },
                error: function(xhr) {
                    var response = JSON.parse(xhr.responseText);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        html: response.message,
                    });

                    $('[id^=hapus-ptk_' + formId + ']').removeClass('d-none');
                    $('#button-ptk-loading-' + formId).addClass('d-none');
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

        function addDeskripsiInput(formid) {
            var deskripsiInputHtml = `
                <div class="input-group mb-2">
                    <textarea name="deskripsi_${formid}[]" class="form-control" cols="30" rows="3"></textarea>
                </div>`;
            $('#deskripsi-inputs-' + formid).append(deskripsiInputHtml);
            toggleRemoveDeskripsiButton(formid);

            $('#deskripsi-inputs-' + formid).on('input', debounce(function() {
                // autoSaveFormLink($(this).attr('name'), $(this).attr('id'));
                save_session();
            }, 100));
        }

        function removeDeskripsiInput(formid) {
            var deskripsiInputs = $('#deskripsi-inputs-' + formid).find('.input-group');
            if (deskripsiInputs.length > 1) {
                var lastInputGroup = deskripsiInputs.last();
                var deskripsiId = lastInputGroup.find('input[type="hidden"]').val();

                if (deskripsiId) {
                    save_session();
                }

                lastInputGroup.remove();
                save_session();
            }
            toggleRemoveDeskripsiButton(formid);
        }

        function toggleRemoveDeskripsiButton(formid) {
            var deskripsiInputs = $('#deskripsi-inputs-' + formid).find('.input-group');
            var removeDeskripsiBtn = $('#remove-deskripsi-btn-' + formid);
            if (deskripsiInputs.length > 1) {
                removeDeskripsiBtn.removeClass('d-none');
            } else {
                removeDeskripsiBtn.addClass('d-none');
            }
        }
    </script>
@endsection
