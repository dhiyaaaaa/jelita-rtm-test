@extends('components.layout.auditee_layout')

@section('content')
    <form action="{{ route('auditor.lapangan.laporan.store_form', ['laporan' => $laporan->id, 'auditor' => $auditor->id]) }}"
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
                            $no = ($paginatedForms->currentPage() - 1) * $paginatedForms->perPage() + 1;
                        @endphp
                        {{-- Page --}}
                        <input type="hidden" name="totalPage" value="{{ $paginatedForms->lastPage() }}">
                        <input type="hidden" name="page_{{ $paginatedForms->currentPage() }}"
                            value="{{ $paginatedForms->currentPage() }}">
                        @foreach ($paginatedForms as $item)
                            @php
                                // Jawaban
                                $jawaban = $jawabanLaporan->where('form_id', $item->form->id)->first();

                                // Catatan Auditor
                                $jawabanAuditor = $jawabanAuditorAll->where('form_id', $item->form->id)->first();

                                $sessionKelebihan =
                                    $jawaban && $jawaban->kelebihan
                                        ? $jawaban->kelebihan
                                        : $sessionFormData['kelebihan_' . $item->form->id] ?? '';

                                $sessionRuang =
                                    $jawaban && $jawaban->ruang_peningkatan
                                        ? $jawaban->ruang_peningkatan
                                        : $sessionFormData['ruang_' . $item->form->id] ?? '';

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
                                        <div>
                                            <label for="">Catatan Auditor</label><br>
                                            @if ($jawabanAuditor && $jawabanAuditor->catatan)
                                                <p>{{ $jawabanAuditor->catatan }}</p>
                                            @else
                                                <p>Tidak ada catatan</p>
                                            @endif
                                        </div>
                                    </div>

                                    {{-- Kelebihan --}}
                                    <div class="form-group">
                                        <label for="">Kelebihan</label>
                                        <span class="text-danger">&#42;</span>
                                        <textarea name="kelebihan_{{ $item->form->id }}" cols="10" rows="5"
                                            class="form-control @if ($errors->has('kelebihan_' . $item->form->id)) is-invalid @endif" {{ $isDisabled ? 'disabled' : '' }}>{{ old('kelebihan_' . $item->form->id, $sessionKelebihan) }}</textarea>

                                        @if ($errors->has('kelebihan_' . $item->form->id))
                                            <span class="text-danger d-block"
                                                style="font-size: 14px">{{ $errors->first('kelebihan_' . $item->form->id) }}</span>
                                        @endif
                                    </div>

                                    {{-- Ruang Peningkatan --}}
                                    <div class="form-group">
                                        <label for="">Ruang Peningkatan</label>
                                        <span class="text-danger">&#42;</span>
                                        <textarea name="ruang_{{ $item->form->id }}" cols="10" rows="5"
                                            class="form-control @if ($errors->has('ruang_' . $item->form->id)) is-invalid @endif" {{ $isDisabled ? 'disabled' : '' }}>{{ old('ruang_' . $item->form->id, $sessionRuang) }}</textarea>

                                        @if ($errors->has('ruang_' . $item->form->id))
                                            <span class="text-danger d-block"
                                                style="font-size: 14px">{{ $errors->first('ruang_' . $item->form->id) }}</span>
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
                                {{ $paginatedForms->links('pagination::bootstrap-4') }}
                            </div>

                            @if (($paginatedForms->currentPage() == $paginatedForms->lastPage()) == 1)
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

                            @if (isset($status) && $status->status === 'completed' && !$expired)
                                <div class="mb-3">
                                    <button type="button" class="btn btn-warning" id="edit-button">Ubah</button>
                                    <button id="button-edit-loading" class="btn btn-warning d-none" type="button" disabled>
                                        <span class="spinner-border spinner-border-sm" role="status"
                                            aria-hidden="true"></span>
                                        Loading...
                                    </button>
                                </div>
                            @endif

                            {{-- Kembali ke halaman jadwal --}}
                            <a href="{{ route('auditor.lapangan.show', $jadwal) }}"
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
                    url: "{{ route('auditor.lapangan.laporan.isi_laporan', ['laporan' => $laporan->id]) }}",
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

            $('textarea[name^="kelebihan_"], textarea[name^="ruang_"]').on('input',
                debounce(function() {
                    save_session();
                }, 1000));

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

            // Kelebihan Selector
            const kelebihanSelector = document.querySelector(`[name="kelebihan_${formId}"]`);
            const kelebihan = kelebihanSelector ? kelebihanSelector.value : null;

            // Ruang Peningkatan Selector
            const ruangSelector = document.querySelector(`[name="ruang_${formId}"]`);
            const ruang = ruangSelector ? ruangSelector.value : null;

            // Loading
            document.getElementById(`simpan_${formId}`).classList.add('d-none');
            document.getElementById(`simpan-button-loading_${formId}`).classList.remove('d-none');

            // Form Data
            const formData = new FormData();
            formData.append(`kelebihan_${formId}`, kelebihan);
            formData.append(`ruang_${formId}`, ruang);

            $.ajax({
                url: '{{ route('auditor.lapangan.laporan.save_form', ['laporan' => $laporan->id, 'auditor' => $auditor->id]) }}',
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
                url: '{{ route('auditor.lapangan.laporan.session', ['laporan' => $laporan->id, 'auditor' => $auditor->id]) }}',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    if (response.success) {
                        console.log('Form data saved to session.');
                    }
                },
                error: function(response) {
                    console.error('Error saving form data to session.');
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
