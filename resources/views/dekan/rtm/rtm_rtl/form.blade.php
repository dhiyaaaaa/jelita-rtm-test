@extends('components.layout.auditee_layout')

@section('content')
<div class="container mt-4">
    <div class="row">
        <!-- Kolom Konten -->
        <div class="col-md-9">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0 text-center">Temuan Fakultas</h4>
                </div>
                <div class="card-body">
                    <form id="rtmForm" action="{{ route('dekan.rtm-rtl.store_form', ['rtmRtl' => $rtmRtl->id, 'fakultas' => $fakultas->id]) }}" method="POST">
                        @csrf
                        @foreach ($paginatedTemuanFakultas->items() as $kriteria => $instrumen)
                            <div class="card mb-3 border-0 shadow">
                                <div class="card-header bg-{{ $kriteria == 'belum-memenuhi' ? 'danger' : ($kriteria == 'memenuhi' ? 'success' : 'primary') }} text-white">
                                    <h5 class="mb-0">{{ ucfirst(str_replace('-', ' ', $kriteria)) }}</h5>
                                </div>
                                <div class="card-body">
                                    @if (!empty($instrumen) && collect($instrumen)->isNotEmpty())
                                        <ul class="list-group list-group-flush">
                                            @foreach ($instrumen as $index => $item)
                                                <li class="list-group-item">
                                                    <strong>{{ $loop->iteration }}:</strong> {{ $item['form']['instrumen']['kode'] }} - {{ $item['form']['instrumen']['pernyataan'] }}
                                                    <p class="mt-2"><strong>Jawaban Auditor:</strong> {{ $item['catatan'] ?? 'Tidak ada catatan.' }}</p>
                                                    <textarea name="tindakan[{{ $item['form']['id'] }}]" rows="3" class="form-control">{{ old("tindakan.{$item['form']['id']}") }}</textarea>
                                                </li>
                                            @endforeach
                                        </ul>
                                    @else
                                        <p class="text-muted">Tidak ada temuan.</p>
                                    @endif
                                    <button type="button" class="btn btn-primary btn-save mt-2" data-kriteria="{{ $kriteria }}">Simpan</button>
                                </div>
                            </div>
                        @endforeach
                    </form>
                </div>
            </div>
        </div>

        <!-- Kolom Pagination -->
        <div class="col-md-3">
            <div class="card shadow-sm sticky-top">
                <div class="card-header bg-secondary text-white text-center">Navigasi Halaman</div>
                <div class="card-body text-center">
                    {{ $paginatedTemuanFakultas->links('pagination::bootstrap-4') }}

                    @if ($paginatedTemuanFakultas->currentPage() == $paginatedTemuanFakultas->lastPage())
                        <div class="mt-3">
                            <input type="hidden" name="final" value="final">
                            <button type="submit" id="button-submit" class="btn btn-success">Submit</button>
                            <button id="button-submit-loading" class="btn btn-success d-none" disabled>
                                <span class="spinner-border spinner-border-sm"></span> Loading...
                            </button>
                        </div>
                    @else
                        <div class="mt-3">
                            @if ($paginatedTemuanFakultas->currentPage() > 1)
                                <a href="{{ $paginatedTemuanFakultas->previousPageUrl() }}" class="btn btn-outline-primary">Previous</a>
                            @endif
                            <a href="{{ $paginatedTemuanFakultas->nextPageUrl() }}" class="btn btn-outline-primary">Next</a>
                        </div>
                    @endif

                    <a href="{{ route('dekan.jadwal-rtm.index') }}" class="btn btn-outline-secondary w-100 mt-2">Kembali</a>
                    <a href="{{ route('dekan.rtm-rtl.form_prodi', ['rtmRtl' => $rtmRtl->id]) }}" class="btn btn-primary w-100 mt-2">Temuan Prodi</a>
                    
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script>
$(document).ready(function () {
    // SweetAlert untuk pesan error
    let errorMessage = "{{ session('error_message') }}";
    if (errorMessage) {
        Swal.fire({ icon: 'error', title: 'Error!', text: errorMessage });
    }

    // Konfirmasi submit form
    $('#button-submit').on('click', function (e) {
        e.preventDefault();
        Swal.fire({
            title: 'Yakin ingin mengirim data?',
            text: "Setelah dikirim, data tidak bisa diubah!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#28a745',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, Kirim!'
        }).then((result) => {
            if (result.isConfirmed) {
                $('#button-submit').addClass('d-none');
                $('#button-submit-loading').removeClass('d-none');
                $('#rtmForm').submit();
            }
        });
    });

    // Simpan data dengan AJAX
    $(document).on('click', '.btn-save', function (e) {
        e.preventDefault();
        let tindakan = $(this).closest('.card-body').find('textarea').val();
        let kriteria = $(this).data('kriteria');
        let button = $(this);

        $.ajax({
            url: "{{ route('dekan.rtm-rtl.store_form', ['rtmRtl' => $rtmRtl->id, 'fakultas' => $fakultas->id]) }}",
            type: 'POST',
            headers: { 'X-CSRF-TOKEN': "{{ csrf_token() }}" },
            data: { kriteria: kriteria, tindakan: tindakan },
            beforeSend: function() {
                button.html('<span class="spinner-border spinner-border-sm"></span> Menyimpan...').prop('disabled', true);
            },
            success: function () {
                Swal.fire({ icon: 'success', title: 'Berhasil!', text: 'Data berhasil disimpan.' });
                button.html('Simpan').prop('disabled', false);
            },
            error: function () {
                Swal.fire({ icon: 'error', title: 'Error!', text: 'Gagal menyimpan data!' });
                button.html('Simpan').prop('disabled', false);
            }
        });
    });

    // Simpan sebelum pindah halaman
    $('.pagination a').on('click', function (e) {
        e.preventDefault();
        let nextPageUrl = $(this).attr('href');

        $.post("{{ route('dekan.rtm-rtl.save_form', ['rtmRtl' => $rtmRtl->id, 'fakultas' => $fakultas->id]) }}", {
            _token: "{{ csrf_token() }}",
            tindakan: $('textarea').serialize()
        }, function () {
            window.location.href = nextPageUrl;
        });
    });
});
</script>
@endsection
