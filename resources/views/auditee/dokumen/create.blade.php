@extends('components.layout.auditee_layout')

@section('content')
    <form action="{{ route('auditee.dokumen.store', ['jadwalAudit' => $jadwal->id, 'unit' => $unitId, 'type' => $type]) }}"
        method="post" id="create-form" enctype="multipart/form-data" class="block">
        @csrf
        <div class="row">
            <div class="col-md-9">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title text-bold">{{ $title }}</h3>
                    </div>
                    <div class="card-body">
                        @php
                            $no = ($paginatedForms->currentPage() - 1) * $paginatedForms->perPage();
                        @endphp

                        {{-- Page --}}
                        <input type="hidden" name="totalPage" value="{{ $paginatedForms->lastPage() }}">
                        <input type="hidden" name="page_{{ $paginatedForms->currentPage() }}"
                            value="{{ $paginatedForms->currentPage() }}">

                        @foreach ($paginatedForms as $item)
                            @php
                                $no++;
                                $jawaban = $jawabanAuditee->where('form_id', $item['form']->id)->first();
                                $linkCollection = $links->where('form_id', $item['form']->id);

                                $jawabanAuditor = $jawabanAuditors->where('form_id', $item['form']->id)->first();

                                $notifCollection = $notifikasi
                                    ->where('form_id', $item['form']->id)
                                    ->where('status', 'terkirim');

                                $sessionJawaban =
                                    $jawaban && $jawaban->jawaban
                                        ? $jawaban->jawaban
                                        : $sessionFormData['instrumen_' . $item['form']->id] ?? '';

                                $sessionLinks =
                                    $linkCollection && $linkCollection->isNotEmpty()
                                        ? $linkCollection->all()
                                        : $sessionFormData['link_' . $item['form']->id] ?? [];

                                $isDisabled = true;
                                if (!$expired) {
                                    if (isset($status) && $status->status !== 'completed') {
                                        $isDisabled = false;
                                    }
                                }
                            @endphp
                            <div class="card">
                                <div class="card-header">
                                    {{-- Pernyataan --}}
                                    <h4 class="card-title w-100">
                                        <span class="badge badge-success mb-3">{{ $item['form']->instrumen->kode }}</span><br>
                                        @if ($item['form']->instrumen->jabatan->isNotEmpty() && $type == 'fakultas')
                                            @forelse ($item['form']->instrumen->jabatan as $jab)
                                                <span class="badge badge-secondary mb-3">{{ $jab->nama }}</span>
                                            @empty
                                                <span class="badge badge-secondary mb-3">-</span>
                                            @endforelse
                                        @endif
                                        <p>{{ $no }}. {{ $item['form']->instrumen->pernyataan }}
                                            <span class="text-danger">&#42;</span>
                                        </p>
                                    </h4>

                                    {{-- Kriteria dan indikator --}}
                                    <button type="button" class="btn btn-outline-info"
                                        onclick="lihat_kriteria({{ $item['form']->instrumen->id }})">Lihat Kriteria</button>
                                    <div class="d-none mt-2" id="kriteria_{{ $item['form']->instrumen->id }}">
                                        {{-- Indikator --}}
                                        <h4 class="card-title w-100 mt-1">
                                            <span class="text-bold">Indikator</span>
                                            <p>{{ $item['form']->instrumen->indikator }}
                                            </p>
                                        </h4>

                                        {{-- Kriteria --}}
                                        <h4 class="card-title w-100">
                                            <span class="text-bold">Kriteria</span>
                                        </h4>
                                        <ul>
                                            @forelse ($item['form']->instrumen->kriteria as $kriteria)
                                                <li>
                                                    <p class="text-bold" style="margin: 0; padding:0;">
                                                        {{ $kriteria->nama }}
                                                    </p>
                                                    @if ($kriteria->pivot && $kriteria->pivot->isi)
                                                        <span>{{ $kriteria->pivot->isi }}</span>
                                                    @else
                                                        <span class="text-muted">-</span>
                                                    @endif
                                                </li>
                                            @empty
                                                <span class="text-muted">Kriteria belum ada</span>
                                            @endforelse
                                        </ul>
                                    </div>

                                </div>
                                <div>
                                    <div class="card-body">
                                        {{-- Jawaban --}}
                                        <div class="form-group">
                                            <label for="">Jawab</label>
                                            <span class="text-danger">&#42;</span>
                                            @if ($item['form']->instrumen->jenis_pertanyaan->nama == 'text')
                                                @role(['pj_universitas', 'pj_fakultas', 'pj_prodi', 'gkm'])
                                                    <textarea name="instrumen_{{ $item['form']->id }}" cols="10" rows="5"
                                                        class="form-control @if ($errors->has('instrumen_' . $item['form']->id)) is-invalid @endif" {{ $isDisabled ? 'disabled' : '' }}>{{ old('instrumen_' . $item['form']->id, $sessionJawaban) }}</textarea>
                                                @endrole
                                            @elseif($item['form']->instrumen->jenis_pertanyaan->nama == 'number')
                                                @role(['pj_universitas', 'pj_fakultas', 'pj_prodi', 'gkm'])
                                                    <input type="number" name="instrumen_{{ $item['form']->id }}"
                                                        {{ $isDisabled ? 'disabled' : '' }}
                                                        class="form-control @if ($errors->has('instrumen_' . $item['form']->id)) is-invalid @endif"
                                                        value="{{ old('instrumen_' . $item['form']->id, $sessionJawaban) }}">
                                                @endrole
                                            @endif
                                            @if ($errors->has('instrumen_' . $item['form']->id))
                                                <span class="text-danger d-block"
                                                    style="font-size: 14px">{{ $errors->first('instrumen_' . $item['form']->id) }}</span>
                                            @endif
                                        </div>

                                        {{-- Link Dokumen --}}
                                        <div class="form-group mt-3">
                                            <label for="">Link Dokumen Pendukung</label>
                                            <span class="text-danger">&#42;</span>
                                            <small class="form-text text-muted" style="margin: -10px 0 8px 0">Untuk akses
                                                Google Drive harap diubah menjadi <i>viewer</i></small>
                                            @role(['pj_universitas', 'pj_fakultas', 'pj_prodi', 'gkm'])
                                                <div class="mb-3 {{ $isDisabled ? 'd-none' : '' }}">
                                                    <button type="button" class="btn btn-secondary btn-sm"
                                                        onclick="addLinkInput('{{ $item['form']->id }}')">Tambah
                                                        Link</button>
                                                    <button type="button" id="remove-link-btn-{{ $item['form']->id }}"
                                                        class="btn btn-danger btn-sm d-none"
                                                        onclick="removeLinkInput('{{ $item['form']->id }}')">Hapus
                                                        Link</button>
                                                </div>
                                            @endrole
                                            <div id="link-inputs-{{ $item['form']->id }}">
                                                @if (!empty($sessionLinks))
                                                    @foreach ($sessionLinks as $index => $link)
                                                        @role(['pj_universitas', 'pj_fakultas', 'pj_prodi', 'gkm'])
                                                            <div class="input-group mb-2">
                                                                <input type="text" name="link_{{ $item['form']->id }}[]"
                                                                    class="form-control @if ($errors->has('link_' . $item['form']->id . '.*')) is-invalid @endif"
                                                                    value="{{ $link->link ?? $link }}"
                                                                    data-link-id="{{ $link->id ?? '' }}"
                                                                    {{ $isDisabled ? 'disabled' : '' }}>
                                                                @if (!empty($link->id))
                                                                    <input type="hidden"
                                                                        name="link-id-{{ $item['form']->id }}[]"
                                                                        value="{{ $link->id }}"
                                                                        {{ $isDisabled ? 'disabled' : '' }}>
                                                                @endif
                                                            </div>
                                                        @endrole
                                                    @endforeach
                                                @else
                                                    @role(['pj_universitas', 'pj_fakultas', 'pj_prodi', 'gkm'])
                                                        <div class="input-group mb-2">
                                                            <input type="text" name="link_{{ $item['form']->id }}[]"
                                                                class="form-control @if ($errors->has('link_' . $item['form']->id . '.*')) is-invalid @endif"
                                                                {{ $isDisabled ? 'disabled' : '' }}>
                                                        </div>
                                                    @endrole
                                                @endif
                                            </div>

                                            @if ($errors->has('link_' . $item['form']->id . '.*'))
                                                <span class="text-danger d-block"
                                                    style="font-size: 14px">{{ $errors->first('link_' . $item['form']->id . '.*') }}</span>
                                            @endif
                                        </div>

                                        {{-- Save per Nomor --}}
                                        @if (isset($status) && $status->status !== 'completed' && !$expired)
                                            <div class="mt-3 mb-3">
                                                <button id="simpan_{{ $item['form']->id }}" class="btn btn-warning"
                                                    type="button">Simpan</button>

                                                <button id="simpan-button-loading_{{ $item['form']->id }}"
                                                    class="btn btn-warning d-none" type="button" disabled>
                                                    <span class="spinner-border spinner-border-sm" role="status"
                                                        aria-hidden="true"></span>
                                                    Loading...
                                                </button>
                                            </div>
                                        @endif

                                        {{-- Catatan Auditor --}}
                                        <div>
                                            <label for="">Catatan Auditor</label>
                                            <textarea cols="10" rows="3" class="form-control" disabled>{{ $jawabanAuditor ? $jawabanAuditor->catatan : '' }}</textarea>

                                            @if ($notifCollection->isNotEmpty())
                                                @foreach ($notifCollection as $notif)
                                                    <div class="mt-3" id="alert_notifikasi_{{ $notif->id }}">
                                                        <small>Jika jawaban sudah disimpan dan diperbaiki silahkan klik
                                                            tombol
                                                            Notifikasi
                                                            Diterima!</small>
                                                        <div class="alert alert-warning" role="alert">
                                                            <span>Notifikasi dari Auditor!</span><br>
                                                            {{ $notif->pesan }}
                                                            <br>
                                                        </div>
                                                        <button type="button" class="btn btn-success "
                                                            id="notifikasi_{{ $notif->id }}">Notifikasi
                                                            Diterima!</button>

                                                        {{-- Loading --}}
                                                        <button id="button-notifikasi-loading-{{ $notif->id }}"
                                                            class="btn btn-success d-none" type="button" disabled>
                                                            <span class="spinner-border spinner-border-sm" role="status"
                                                                aria-hidden="true"></span>
                                                            Loading...
                                                        </button>
                                                    </div>
                                                @endforeach
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
                    <div class="card card-primary">
                        <div class="card-header">
                            Halaman
                        </div>
                        <div class="card-body">
                            <div class="pagination-wrapper">
                                {{ $paginatedForms->links('pagination::bootstrap-4') }}
                            </div>


                            @if ($paginatedForms->currentPage() == $paginatedForms->lastPage())
                                @if (isset($status) && $status->status !== 'completed' && !$expired)
                                    <div class="mb-3">
                                        <input type="hidden" name="final" value="final">
                                        @role(['pj_universitas', 'pj_fakultas', 'pj_prodi', 'gkm'])
                                            <button type="submit" id="button-submit" class="btn btn-primary">Submit</button>
                                        @endrole
                                        <button id="button-submit-loading" class="btn btn-primary d-none" type="button"
                                            disabled>
                                            <span class="spinner-border spinner-border-sm" role="status"
                                                aria-hidden="true"></span>
                                            Loading...
                                        </button>
                                    </div>
                                @else
                                    @if ($paginatedForms->lastPage() !== 1)
                                        <div>
                                            <a id="previous" href="{{ $paginatedForms->previousPageUrl() }}"
                                                class="btn btn-primary mb-3">Previous</a>
                                        </div>
                                    @endif
                                @endif
                            @else
                                <div class="mb-3">
                                    @if ($paginatedForms->currentPage() > 1)
                                        <a id="previous" href="{{ $paginatedForms->previousPageUrl() }}"
                                            class="btn btn-primary">Previous</a>
                                    @endif
                                    <a id="next" href="{{ $paginatedForms->nextPageUrl() }}"
                                        class="btn btn-primary">Next</a>
                                </div>
                            @endif

                            @role(['pj_universitas', 'pj_fakultas', 'pj_prodi', 'gkm'])
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
                            @endrole


                            {{-- Kembali ke halaman auditee --}}
                            @role(['pj_universitas', 'pj_fakultas', 'pj_prodi', 'gkm'])
                                <a href="{{ route('auditee.dokumen') }}" class="btn btn-outline-secondary">Kembali</a>
                            @endrole
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

        // Lihat Kriteria
        function lihat_kriteria(id) {
            $('#kriteria_' + id).toggleClass('d-none');
        }
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

            var editButton = $('#edit-button');

            // Edit Status
            $('#edit-button').on('click', function() {
                $('#edit-button').addClass('d-none');
                $('#button-edit-loading').removeClass('d-none');

                $.ajax({
                    url: "{{ route('auditee.dokumen.isi_audit', ['jadwalAudit' => $jadwal->id, 'unit' => $unitId, 'type' => $type]) }}",
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

            $('textarea[name^="instrumen_"], input[name^="instrumen_"]').on('input', debounce(function() {
                save_session();
            }, 1000));


            $('input[name^="link_"]').on('input', debounce(function() {
                save_session();
            }, 1000));

            @foreach ($paginatedForms as $item)
                tombol_hapus_link('{{ $item['form']->id }}');
            @endforeach

            $(document).on('click', '.pagination a', function(event) {
                event.preventDefault();
                const url = $(this).attr('href');

                save_session();

                setTimeout(function() {
                    window.location.href = url;
                }, 500);
            });

            // Notifikasi
            $(document).on('click', '[id^=notifikasi_]', function() {
                var id = $(this).attr('id').split('_')[1];

                $('[id^=notifikasi_' + id + ']').addClass('d-none');

                $('#button-notifikasi-loading-' + id).removeClass('d-none');

                update_notifikasi(id);
            });
        });

        // Save Jawaban
        function save_jawaban(formId) {
            event.preventDefault();

            // Jawaban Selector
            const jawabanSelector = document.querySelector(`[name="instrumen_${formId}"]`);
            const jawaban = jawabanSelector ? jawabanSelector.value : null;

            // Link Selector
            const linkSelector = document.querySelectorAll(`[name="link_${formId}[]"]`);
            const links = Array.from(linkSelector).map(link => link.value);

            // Loading
            document.getElementById(`simpan_${formId}`).classList.add('d-none');
            document.getElementById(`simpan-button-loading_${formId}`).classList.remove('d-none');

            // Form Data
            const formData = new FormData();
            formData.append(`instrumen_${formId}`, jawaban);

            links.forEach((link, index) => {
                formData.append(`link_${formId}[]`, link);
            });

            $.ajax({
                url: "{{ route('auditee.dokumen.save', ['jadwalAudit' => $jadwal->id, 'unit' => $unitId, 'type' => $type]) }}",
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

                    if (response.message) {
                        errorMessages = response.message;
                    } else if (response.errors) {
                        for (let key in response.errors) {
                            if (response.errors.hasOwnProperty(key)) {
                                errorMessages = response.errors[key][0];
                                break;
                            }
                        }
                    }

                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: errorMessages,
                    });

                    document.getElementById(`simpan_${formId}`).classList.remove('d-none');
                    document.getElementById(`simpan-button-loading_${formId}`).classList.add('d-none');
                }

            });
        }

        // Save Session
        function save_session() {
            const formData = new FormData(document.getElementById('create-form'));

            $.ajax({
                url: "{{ route('auditee.dokumen.session', ['jadwalAudit' => $jadwal->id, 'unit' => $unitId, 'auditee' => $auditee ? $auditee->id : 'null']) }}",
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

        // Notifikasi
        function update_notifikasi(id) {

            $.ajax({
                url: "{{ route('auditee.notifikasi', ['notifikasi' => ':id', 'auditee' => $auditee ? $auditee->id : 'null']) }}"
                    .replace(':id', id),
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

                    $('#alert_notifikasi_' + id).remove();
                },
                error: function(xhr) {
                    var response = JSON.parse(xhr.responseText);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: response.message,
                    });

                    $('[id^=notifikasi_' + id + ']').removeClass('d-none');
                    $('#button-notifikasi-loading-' + id).addClass('d-none');
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

        function addLinkInput(formid) {
            var inputGroupDiv = document.createElement('div');
            inputGroupDiv.className = 'input-group mb-2';

            var input = document.createElement('input');
            input.type = 'text';
            input.name = `link_${formid}[]`;
            input.className = 'form-control';

            inputGroupDiv.appendChild(input);

            $('#link-inputs-' + formid).append(inputGroupDiv);
            tombol_hapus_link(formid);

            $(input).on('input', debounce(function() {
                save_session();
            }, 500));
        }

        function removeLinkInput(formid) {
            var linkInputs = $('#link-inputs-' + formid).find('.input-group');
            if (linkInputs.length > 1) {
                var lastInputGroup = linkInputs.last();
                var linkId = lastInputGroup.find('input[type="hidden"]').val();

                if (linkId) {
                    save_session();
                }

                lastInputGroup.remove();
                save_session();
            }
            tombol_hapus_link(formid);
        }

        function tombol_hapus_link(formid) {
            var linkInputs = $('#link-inputs-' + formid).find('.input-group');
            var removeLinkBtn = $('#remove-link-btn-' + formid);
            if (linkInputs.length > 1) {
                removeLinkBtn.removeClass('d-none');
            } else {
                removeLinkBtn.addClass('d-none');
            }
        }
    </script>
@endsection
