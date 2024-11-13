@extends('components.layout.auditee_layout')

@section('content')
    <form
        action="{{ route('auditor.dokumen.store', ['jadwalAudit' => $jadwalAudit->id, 'unit' => $unitId, 'type' => $type]) }}"
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
                            $no = ($paginatedForms->currentPage() - 1) * $paginatedForms->perPage();
                        @endphp

                        {{-- Page --}}
                        <input type="hidden" name="totalPage" value="{{ $paginatedForms->lastPage() }}">
                        <input type="hidden" name="page_{{ $paginatedForms->currentPage() }}"
                            value="{{ $paginatedForms->currentPage() }}">

                        @foreach ($paginatedForms as $item)
                            @php
                                $no++;
                                // Jawaban Auditee
                                $jawaban = $jawabanAuditee->where('form_id', $item['form']->id)->first();
                                $linkCollection = $links->where('form_id', $item['form']->id);

                                // Notifikasi
                                $notifCollection = $notifikasi
                                    ->where('form_id', $item['form']->id)
                                    ->where('status', 'diterima');

                                // Jawaban Auditor
                                $jawabanAuditor = $jawabanAuditors->where('form_id', $item['form']->id)->first();

                                $sessionKriteria =
                                    $jawabanAuditor && $jawabanAuditor->kriteria_id
                                        ? $jawabanAuditor->kriteria_id
                                        : $sessionFormData['kriteria_' . $item['form']->id] ?? '';

                                $sessionCatatan =
                                    $jawabanAuditor && $jawabanAuditor->catatan
                                        ? $jawabanAuditor->catatan
                                        : $sessionFormData['catatan_' . $item['form']->id] ?? '';

                                $sessionDaftarTilik =
                                    $jawabanAuditor && $jawabanAuditor->daftar_tilik
                                        ? $jawabanAuditor->daftar_tilik
                                        : $sessionFormData['daftar-tilik_' . $item['form']->id] ?? '';

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
                                    <h4 class="card-title w-100">
                                        <span class="badge badge-success mb-3">
                                            {{ $item['form']->instrumen->kode }}</span>
                                        <p>{{ $no }}. {{ $item['form']->instrumen->pernyataan }}
                                            <span class="text-danger">&#42;</span>
                                        </p>
                                    </h4>
                                </div>
                                <div>
                                    <div class="card-body">

                                        {{-- Jawaban Auditee --}}
                                        <div class="form-group">
                                            <label for="">Jawaban Auditan</label>
                                            @if ($item['form']->instrumen->jenis_pertanyaan->nama == 'text')
                                                <textarea cols="10" rows="5" class="form-control" disabled>{{ $jawaban ? $jawaban->jawaban : '' }}</textarea>
                                            @elseif($item['form']->instrumen->jenis_pertanyaan->nama == 'number')
                                                <input type="number" class="form-control" disabled
                                                    value="{{ $jawaban ? $jawaban->jawaban : '' }}">
                                            @endif
                                        </div>
                                        <div class="form-group mt-3">
                                            <label for="">Link</label>
                                            <div id="link-inputs-{{ $item['form']->id }}">
                                                @if ($linkCollection->isNotEmpty())
                                                    @foreach ($linkCollection as $index => $link)
                                                        <div class="input-group mb-2">
                                                            <a href="{{ $link->link ?? $link }}"
                                                                target="_blank">{{ $link->link ?? $link }}</a>
                                                        </div>
                                                    @endforeach
                                                @else
                                                    <p>Belum ada</p>
                                                @endif
                                            </div>
                                        </div>

                                        {{-- Jawaban Auditor --}}
                                        <div class="form-group mt-3">
                                            <label for="">Jawaban Auditor</label>
                                            <span class="text-danger">&#42;</span>
                                            @foreach ($item['form']->instrumen->kriteria->sortBy('id') as $index => $kriteria)
                                                <div class="custom-control custom-radio">
                                                    <input
                                                        class="custom-control-input jawaban-auditor @if ($errors->has('kriteria_' . $item['form']->id)) is-invalid @endif"
                                                        type="radio"
                                                        id="customRadio{{ $item['form']->id }}-{{ $index }}"
                                                        name="kriteria_{{ $item['form']->id }}"
                                                        value="{{ $kriteria->id }}"
                                                        {{ old('kriteria_' . $item['form']->id, $sessionKriteria) == $kriteria->id ? 'checked' : '' }}
                                                        {{ $isDisabled ? 'disabled' : '' }}>
                                                    <label for="customRadio{{ $item['form']->id }}-{{ $index }}"
                                                        class="custom-control-label">
                                                        <span>
                                                            {{ $kriteria->nama }}
                                                        </span><br>
                                                        <span style="font-weight: 500">
                                                            {{ $kriteria->pivot->isi }}
                                                        </span>
                                                    </label>
                                                    <br>
                                                </div>
                                            @endforeach
                                            @if ($errors->has('kriteria_' . $item['form']->id))
                                                <span class="text-danger d-block"
                                                    style="font-size: 14px">{{ $errors->first('kriteria_' . $item['form']->id) }}</span>
                                            @endif
                                        </div>

                                        {{-- Daftar Tilik --}}
                                        <div class="form-group mt-3">
                                            <label for="">Daftar Tilik</label>
                                            <span class="text-danger">&#42;</span>
                                            <div class="custom-control custom-radio">
                                                <input
                                                    class="custom-control-input daftar-tilik @if ($errors->has('daftar-tilik_' . $item['form']->id)) is-invalid @endif"
                                                    type="radio" id="daftarTilikYa-{{ $item['form']->id }}"
                                                    name="daftar-tilik_{{ $item['form']->id }}" value="1"
                                                    {{ old('daftar-tilik_' . $item['form']->id, $sessionDaftarTilik) == '1' ? 'checked' : '' }}
                                                    {{ $isDisabled ? 'disabled' : '' }}>
                                                <label for="daftarTilikYa-{{ $item['form']->id }}"
                                                    class="custom-control-label">
                                                    Ya
                                                </label>
                                                <br>
                                            </div>
                                            <div class="custom-control custom-radio">
                                                <input
                                                    class="custom-control-input daftar-tilik @if ($errors->has('daftar-tilik_' . $item['form']->id)) is-invalid @endif"
                                                    type="radio" id="daftarTilikTidak-{{ $item['form']->id }}"
                                                    name="daftar-tilik_{{ $item['form']->id }}" value="0"
                                                     {{ old('daftar-tilik_' . $item['form']->id) == '0' || (isset($sessionDaftarTilik) && $sessionDaftarTilik == '0') ? 'checked' : '' }}
                                                    {{ $isDisabled ? 'disabled' : '' }}>
                                                <label for="daftarTilikTidak-{{ $item['form']->id }}"
                                                    class="custom-control-label">
                                                    Tidak
                                                </label>
                                                <br>
                                            </div>

                                            @if ($errors->has('daftar-tilik_' . $item['form']->id))
                                                <span class="text-danger d-block"
                                                    style="font-size: 14px">{{ $errors->first('daftar-tilik_' . $item['form']->id) }}</span>
                                            @endif
                                        </div>

                                        {{-- Catatan --}}
                                        <div class="form-group mt-3">
                                            <label for="catatan-{{ $item['form']->id }}">Catatan Auditor</label>
                                            <textarea name="catatan_{{ $item['form']->id }}" cols="10" rows="5" class="form-control"
                                                {{ $isDisabled ? 'disabled' : '' }}>{{ old('catatan_' . $item['form']->id, $sessionCatatan) }}</textarea>
                                        </div>

                                        @if (isset($status) && $status->status !== 'completed' && !$expired)
                                            <div class="mb-3">
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

                                        {{-- Notifikasi --}}
                                        <div class="{{ $isDisabled ? 'd-none' : '' }}">
                                            <div>
                                                <button type="button" id="notifikasi_{{ $item['form']->id }}"
                                                    class="btn btn-success">
                                                    Kirim Notifikasi
                                                </button>

                                                {{-- Loading --}}
                                                <button id="button-notifikasi-loading-{{ $item['form']->id }}"
                                                    class="btn btn-success d-none" type="button" disabled>
                                                    <span class="spinner-border spinner-border-sm" role="status"
                                                        aria-hidden="true"></span>
                                                    Loading...
                                                </button>
                                            </div>

                                            <small>Pastikan isi dan simpan catatan terlebih dahulu sebelum mengirim
                                                notifikasi</small>
                                        </div>

                                        {{-- Notifikasi Selesai --}}
                                        @if ($notifCollection->isNotEmpty())
                                            @foreach ($notifCollection as $notif)
                                                <div class="mt-3" id="alert_notifikasi_{{ $notif->id }}">
                                                    <div class="alert alert-warning" role="alert">
                                                        {{-- <span>{{ $notif->pesan }}</span><br> --}}
                                                        Jawaban auditan sudah diperbaiki silahkan klik tombol selesai
                                                        <br>
                                                    </div>
                                                    <button type="button" class="btn btn-success"
                                                        id="selesai_notifikasi_{{ $notif->id }}">Selesai!</button>

                                                    {{-- Loading --}}
                                                    <button id="button-notifikasi-loading-selesai-{{ $notif->id }}"
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

                            @if (($paginatedForms->currentPage() == $paginatedForms->lastPage()) == 1)
                                @if (isset($status) && $status->status !== 'completed' && !$expired)
                                    <input type="hidden" name="final" value="final">
                                    <div class="mb-3">
                                        <button type="submit" id="button-submit" class="btn btn-primary">Submit</button>
                                        <button id="button-submit-loading" class="btn btn-primary d-none" type="button"
                                            disabled>
                                            <span class="spinner-border spinner-border-sm" role="status"
                                                aria-hidden="true"></span>
                                            Loading...
                                        </button>
                                    </div>
                                @endif
                            @else
                                <div class="">
                                    @if ($paginatedForms->currentPage() > 1)
                                        <a id="previous" href="{{ $paginatedForms->previousPageUrl() }}"
                                            class="btn btn-primary">Previous</a>
                                    @else
                                        <div></div>
                                    @endif
                                    <a id="next" href="{{ $paginatedForms->nextPageUrl() }}"
                                        class="btn btn-primary">Next</a>
                                    <div class="mt-3">
                                    </div>
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
                            <a href="{{ route('auditor.dokumen.show', $jadwalAudit->id) }}"
                                class="btn btn-outline-secondary">Kembali</a>
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
            /* padding: 12px 0; */
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
                    url: "{{ route('auditor.dokumen.isi_audit', ['jadwalAudit' => $jadwalAudit->id, 'unit' => $unitId, 'type' => $type]) }}",
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


            $('.jawaban-auditor').on('change', function() {
                var id = $(this).attr('name').split('_')[1];
                var daftarTilikYa = $('#daftarTilikYa-' + id);
                var daftarTilikTidak = $('#daftarTilikTidak-' + id);
                var jawaban = $(this).siblings('label').find('span:nth-child(1)').text().trim();
                // console.log(jawaban);
                if (jawaban === 'Belum Memenuhi') {
                    daftarTilikYa.prop('checked', true);
                } else if (jawaban === 'Memenuhi' || jawaban === 'Melampaui') {
                    daftarTilikTidak.prop('checked', true);
                }

                save_session();
            });

            $('.daftar-tilik').on('change', function() {
                var id = $(this).attr('name').split('_')[1];
                var value = $(this).val();
                save_session();
            });

            $(document).on('click', '.pagination a', function(event) {
                event.preventDefault();
                const url = $(this).attr('href');

                save_session();

                setTimeout(function() {
                    window.location.href = url;
                }, 500);
            });

            // Kirim Notifikasi
            $(document).on('click', '[id^=notifikasi_]', function() {
                var id = $(this).attr('id').split('_')[1];

                $('[id^=notifikasi_' + id + ']').addClass('d-none');

                $('#button-notifikasi-loading-' + id).removeClass('d-none');

                kirim_notifikasi(id);
            });

            // Notifikasi Selesai
            $(document).on('click', '[id^=selesai_notifikasi_]', function() {
                var id = $(this).attr('id').split('_')[2];

                $('#selesai_notifikasi_' + id).addClass('d-none');

                $('#button-notifikasi-loading-selesai-' + id).removeClass('d-none');

                notifikasi_selesai(id);
            });
        });


        function save_jawaban(formId) {
            event.preventDefault();

            // Kriteria Selector
            const kriteriaSelector = document.querySelector(`[name="kriteria_${formId}"]:checked`);
            const kriteria = kriteriaSelector ? kriteriaSelector.value : null;

            // Daftar Tilik Selector
            const daftartilikSelector = document.querySelector(`[name="daftar-tilik_${formId}"]:checked`);
            const daftartilik = daftartilikSelector ? daftartilikSelector.value : null;

            // Catatan Selector
            const catatanSelector = document.querySelector(`[name="catatan_${formId}"]`);
            const catatan = catatanSelector ? catatanSelector.value : null;

            // Loading
            document.getElementById(`simpan_${formId}`).classList.add('d-none');
            document.getElementById(`simpan-button-loading_${formId}`).classList.remove('d-none');

            // Form Data
            const formData = new FormData();
            formData.append(`kriteria_${formId}`, kriteria);
            formData.append(`daftar-tilik_${formId}`, daftartilik);
            formData.append(`catatan_${formId}`, catatan);

            $.ajax({
                url: '{{ route('auditor.dokumen.save', ['jadwalAudit' => $jadwalAudit->id, 'unit' => $unitId, 'type' => $type]) }}',
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
            // formData.append('_token', csrfToken);

            $('input[type="radio"]:checked').each(function() {
                var name = $(this).attr('name');
                var value = $(this).val();
                formData.set(name, value);
            });

            $.ajax({
                url: '{{ route('auditor.dokumen.session', ['jadwalAudit' => $jadwalAudit->id, 'unit' => $unitId, 'auditorId' => $auditor->id]) }}',
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

        // Kirim Notifikasi
        function kirim_notifikasi(instrumenId) {

            $.ajax({
                url: "{{ route('auditor.notifikasi', ['jadwalAudit' => $jadwalAudit->id, 'unit' => $unitId, 'type' => $type, 'instrumenId' => ':instrumenId', 'auditorId' => $auditor->id]) }}"
                    .replace(':instrumenId', instrumenId),
                type: 'POST',
                processData: false,
                contentType: false,
                success: function(response) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: response.message,
                    });

                    $('[id^=notifikasi_' + instrumenId + ']').removeClass('d-none');
                    $('#button-notifikasi-loading-' + instrumenId).addClass('d-none');
                },
                error: function(xhr) {
                    var response = JSON.parse(xhr.responseText);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        html: response.message,
                    });

                    $('[id^=notifikasi_' + instrumenId + ']').removeClass('d-none');
                    $('#button-notifikasi-loading-' + instrumenId).addClass('d-none');
                }
            });
        }

        // Notifikasi Selesai
        function notifikasi_selesai(id) {
            $.ajax({
                url: "{{ route('auditor.notifikasi.selesai', ['notifikasi' => ':id']) }}".replace(':id', id),
                type: 'POST',
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
                        html: response.message,
                    });

                    $('#selesai_notifikasi_' + id).removeClass('d-none');
                    $('#button-notifikasi-loading-selesai-' + id).addClass('d-none');
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
    </script>
@endsection
