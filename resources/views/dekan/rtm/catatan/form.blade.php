@extends('components.layout.main_layout')

@section('content')
    <form id="catatan-form" action="{{ route('dekan.rtm-catatan.store', ['rtmJadwal' => $rtmJadwal]) }}" method="POST">
        @csrf
        <input type="hidden" name="rtm_jadwal_id" value="{{ $rtmJadwal->id }}">
        <div class="d-flex justify-content-start">
            <a href="{{ route('dekan.rtm-rtl.show', ['rtmRtl' => $rtmRtl->id]) }}" class="btn btn-outline-dark mb-3">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
        </div>
        <div class="card">
            <div class="card-header">
                <h3 class="card-title text-bold">{{ $title }}</h3>
            </div>
            <div class="card-body">
                <div id="catatan-container">
                    <div class="form-group">
                        <label>Judul</label>
                            <input type="text" class="form-control" name="judul" 
                            placeholder="Tambahkan judul narasi" required 
                            value="{{ $sessionCatatan['judul'] ?? old('judul') }}">
                    </div>
                    <div class="form-group">
                        <label>Isi</label>
                            <textarea class="form-control summernote" name="isi" 
                            placeholder="Tambahkan isian narasi" required>
                            {{ $sessionCatatan['isi'] ?? old('isi') }}</textarea>
                    </div>
                    <div class="mt-3">
                        <button type="submit" id="submit-button" class="btn btn-primary">Submit</button>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <div class="card">
        <div class="card-body">
            <div>
                <table id="catatan" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th class="text-center">No</th>
                            <th class="text-center">Judul</th>
                            <th class="text-center">Isi</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($rtmCatatan as $index => $item)
                            <tr data-id="{{ $item->id }}">
                                <td class="text-center">{{$index+1}}</td>
                                <td class="text-center">{{ $item->judul }}</td>
                                <td>{!! $item->isi !!}</td>
                                <td class="text-center">
                                    <div class="btn-group">
                                        <button class="btn btn-sm btn-warning edit-btn" 
                                        data-toggle="collapse" 
                                        data-target="#editForm-{{ $item->id }}">
                                        <i class="fas fa-pencil-alt me-1"></i></button>

                                        <form action="{{ route('dekan.rtm-catatan.destroy', ['rtmCatatan' => $item->id]) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm" data-confirm-delete="true"><i class="fas fa-trash-alt" ></i></button>
                                        </form>

                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>    
                @foreach ($rtmCatatan as $item)
                    <div class="collapse mt-3 mb-4 border p-3 rounded" id="editForm-{{ $item->id }}">
                        <form action="{{ route('dekan.rtm-catatan.update', $item->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="row">
                                <div class="col-md-4 form-group">
                                    <label>Judul</label>
                                    <input type="text" name="judul" class="form-control" value="{{ $item->judul }}" required>
                                </div>
                                <div class="col-md-12 form-group mt-2">
                                    <label>Isi</label>
                                    <textarea name="isi" class="form-control summernote-edit" required>{{ $item->isi }}</textarea>
                                </div>
                                <div class="col-md-12 mt-2 text-end">
                                    <button type="submit" class="btn btn-success btn-sm me-2">
                                        Simpan Perubahan
                                    </button>
                                    <button type="button" class="btn btn-secondary btn-sm cancel-edit" 
                                        data-target="#editForm-{{ $item->id }}">
                                        Batal
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div> 
                @endforeach
            </div>
        </div>
    </div>
@endsection

@section('style')
    <!-- DataTables -->
    <link rel="stylesheet" href="{{ asset('plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">

    <!-- Summernote -->
    <link rel="stylesheet" href="{{ asset('plugins/summernote/summernote-bs4.min.css') }}">
    <!-- Bootstrap 4 -->
    <link rel="stylesheet" href="{{ asset('plugins/bootstrap/css/bootstrap.min.css') }}">

    <style>
        /* Nonaktifkan resize untuk semua textarea */
        textarea.form-control {
            resize: none;
            min-height: 150px;
        }
        
        /* Nonaktifkan resize untuk Summernote */
        .note-editor .note-editable {
            resize: none !important;
            overflow: auto !important;
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

    <script>
        $(document).ready(function() {
            // Inisialisasi DataTables
            $('#catatan').DataTable({
                responsive: true,
                autoWidth: false,
                "columnDefs": [{
                        "width": "5%",
                        "targets": [0]
                    },
                    {
                        "width": "20%",
                        "targets": [1]
                    },
                    {
                        "width": "65%",
                        "targets": [2]
                    },
                    {
                        "width": "10%",
                        "targets": [3]
                    }
                ]
            });

            // Summernote untuk form create
            $('.summernote').summernote({
                height: 150,
                disableResizeEditor: true,
                placeholder: 'Tambahkan isian narasi',
                toolbar: [
                    ['style', ['bold', 'italic', 'underline', 'clear']],
                    ['font', ['strikethrough', 'superscript', 'subscript']],
                    ['para', ['ul', 'ol', 'paragraph']],
                    ['height', ['height']]
                ],
                callbacks: {
                    onInit: function() {
                        var sessionIsi = @json($sessionCatatan['isi'] ?? '');
                        if (sessionIsi) {
                            $(this).summernote('code', sessionIsi)
                        }
                    }
                }
            });

            // Toggle form edit
            $(document).on('click', '.edit-btn', function() {
                var target = $(this).data('target');
                $(target).collapse('toggle');
                
                // Tutup semua form edit 
                $('.collapse').not(target).collapse('hide');
            });

            // Tombol batal
            $(document).on('click', '.cancel-edit', function() {
                var target = $(this).data('target');
                $(target).collapse('hide');
            });

            // Summernote untuk form edit
            $(document).on('shown.bs.collapse', function(e) {
                $(e.target).find('.summernote-edit').summernote({
                    height: 150,
                    disableResizeEditor: true,
                    toolbar: [
                        ['style', ['bold', 'italic', 'underline', 'clear']],
                        ['font', ['strikethrough', 'superscript', 'subscript']],
                        ['para', ['ul', 'ol', 'paragraph']],
                        ['height', ['height']]
                    ]
                });
            });

            $(document).on('hidden.bs.collapse', function(e) {
                var note = $(e.target).find('.summernote-edit');
                if (note.summernote('isEmpty')) {
                    note.summernote('destroy');
                }
            });

            function save_session() {
                const formData = $('#catatan-form').serialize();

                $.ajax({
                    url: '{{ route('rtm-catatan-fakultas.session', ['rtmJadwal' => $rtmJadwal->id]) }}',
                    type: 'POST',
                    data: formData,
                    success: function() {
                        console.log('Session tersimpan');
                    },
                    error: function(xhr) {
                        console.error('Gagal menyimpan session');
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

            $('input, textarea').on('input', debounce(save_session, 500));
            $('.summernote').on('summernote.change', debounce(save_session, 500));
        });
    </script>
@endsection

