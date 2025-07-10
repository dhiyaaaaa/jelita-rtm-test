@extends('components.layout.main_layout')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <form id="catatan-form" action="{{ isset($sessionCatatan['id']) ? route('admin.rtm-catatan.update', $sessionCatatan['id']) : route('admin.rtm-catatan.store', ['rtmJadwal' => $rtmJadwal]) }}" method="POST">
                @csrf
                @if(isset($sessionCatatan['id']))
                    @method('PUT')
                @endif
                <input type="hidden" name="rtm_jadwal_id" value="{{ $rtmJadwal->id }}">

                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <div class="d-flex justify-content-between align-items-center">
                            <h3 class="card-title mb-0">{{ $title }}</h3>
                            <a href="{{ route('admin.rtm-rtl.show', ['rtmJadwal' => $rtmJadwal->id]) }}" class="btn btn-light btn-sm">
                                <i class="fas fa-arrow-left mr-1"></i> Kembali
                            </a>
                        </div>
                    </div>

                    <div class="card-body">
                        <div class="form-group">
                            <label class="font-weight-bold">Judul Narasi</label>
                            <input type="text" class="form-control rounded-0" name="judul"
                                           placeholder="Masukkan judul narasi" required
                                           value="{{ $sessionCatatan['judul'] ?? old('judul') }}">
                        </div>

                        <div class="form-group">
                            <label class="font-weight-bold">Isi Narasi</label>
                            <textarea class="form-control summernote" name="isi"
                                               placeholder="Tulis isi narasi disini..." required>
                                {{ $sessionCatatan['isi'] ?? old('isi') }}</textarea>
                        </div>
                    </div>

                    <div class="card-footer bg-white">
                        <div class="d-flex justify-content-end">
                            @if(isset($sessionCatatan['id']))
                                {{-- Tombol Batal mengarahkan kembali ke halaman form --}}
                                <a href="{{ route('admin.rtm-catatan.form', ['rtmJadwal' => $rtmJadwal]) }}"
                                class="btn btn-secondary mr-2">
                                    <i class="fas fa-times mr-1"></i> Batal
                                </a>
                            @endif
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save mr-1"></i>
                                {{ isset($sessionCatatan['id']) ? 'Simpan Perubahan' : 'Simpan' }}
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-info text-white">
                    <h3 class="card-title mb-0">Daftar Narasi RTM</h3>
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table id="catatan" class="table table-bordered table-hover">
                            <thead class="bg-light">
                                <tr>
                                    <th class="text-center" width="5%">No</th>
                                    <th class="text-center" width="20%">Judul</th>
                                    <th class="text-center" width="65%">Isi</th>
                                    <th class="text-center" width="10%">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($rtmCatatan as $index => $item)
                                <tr>
                                    <td class="text-center align-middle">{{ $index+1 }}</td>
                                    <td class="align-middle">{{ $item->judul }}</td>
                                    <td>{!! $item->isi !!}</td>
                                    <td class="text-center align-middle">
                                        <div class="btn-group btn-group-sm">
                                            <a href="{{ route('admin.rtm-catatan.edit', $item->id) }}"
                                               class="btn btn-warning" title="Edit"  dusk="edit-catatan-{{ $item->id }}">
                                                <i class="fas fa-pencil-alt"></i>
                                            </a>
                                            {{-- Delete Button --}}
                                            <form action="{{ route('admin.rtm-catatan.destroy', $item->id) }}" method="POST" style="display:inline;" class="delete-form">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger" title="Hapus" dusk="delete-catatan-{{ $item->id }}" data-confirm-delete="true">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('style')
<link rel="stylesheet" href="{{ asset('plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/summernote/summernote-bs4.min.css') }}">
<style>
    .card-header {
        border-bottom: none;
    }
    .table thead th {
        vertical-align: middle;
    }
    .btn-group-sm > .btn {
        padding: 0.25rem 0.5rem;
    }
    .summernote {
        border-radius: 0 !important;
    }
    .note-editor.note-frame {
        border: 1px solid #ced4da !important;
    }
</style>
@endsection

@section('script')
<script src="{{ asset('plugins/jquery/jquery.min.js') }}"></script>
<script src="{{ asset('plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('plugins/summernote/summernote-bs4.min.js') }}"></script>
<script src="{{ asset('plugins/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>


@if (session('error_message'))
<script>
    Swal.fire({
        icon: 'error',
        title: 'Oops...',
        text: "{{ session('error_message') }}"
    });
</script>
@endif

@if (session('success'))
<script>
    Swal.fire({
        icon: 'success',
        title: 'Berhasil!',
        text: "{{ session('success') }}"
    });
</script>
@endif

<script>
$(document).ready(function() {
    // Inisialisasi DataTables
    $('#catatan').DataTable({
        responsive: true,
        autoWidth: false,
    });

    // Summernote initialization
    $('.summernote').summernote({
        height: 200,
        disableResizeEditor: true,
        placeholder: 'Tulis isi narasi disini...',
        toolbar: [
            ['style', ['bold', 'italic', 'underline', 'clear']],
            ['font', ['strikethrough', 'superscript', 'subscript']],
            ['para', ['ul', 'ol', 'paragraph']],
            ['insert', ['link']],
            ['view', ['codeview', 'help']]
        ],
        callbacks: {
            onInit: function() {
                var sessionIsi = @json($sessionCatatan['isi'] ?? '');
                if (sessionIsi) {
                    $(this).summernote('code', sessionIsi);
                }
            }
        }
    });

    // Confirm before delete using SweetAlert2
    $(document).on('click', '.delete-form button[type="submit"]', function(e) {
        e.preventDefault();
        var form = $(this).closest('form');

        Swal.fire({
            title: 'Hapus Catatan Narasi RTM?',
            text: "Anda tidak akan dapat mengembalikan data ini!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
            }
        });
    });

    // Auto-save session
    function save_session() {
        const formData = $('#catatan-form').serialize();

        $.ajax({
            url: '{{ route('admin.rtm-catatan.session', ['rtmJadwal' => $rtmJadwal->id]) }}',
            type: 'POST',
            data: formData,
            success: function() {
                console.log('Session tersimpan');
            },
            error: function(xhr) {
                // Log respons error lengkap untuk debugging
                console.error('Gagal menyimpan session:', xhr.responseText);
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

    // Hanya panggil debounce pada input judul dan perubahan summernote
    $('input[name="judul"]').on('input', debounce(save_session, 1000));
    $('.summernote').on('summernote.change', debounce(save_session, 1000));
});
</script>
@endsection