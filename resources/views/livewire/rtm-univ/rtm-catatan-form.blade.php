<div>
    <div class="row mb-4">
        <div class="col-12">
            <form wire:submit="{{ $editingCatatanId ? 'update' : 'store' }}">
                <input type="hidden" name="rtm_jadwal_id" value="{{ $rtmJadwal->id }}">

                @if($editingCatatanId)
                    <input type="hidden" wire:model="editingCatatanId">
                @endif

                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <div class="d-flex justify-content-between align-items-center">
                            <h3 class="card-title mb-0">{{ $title }}</h3>
                            <a href="{{ route('admin.rtm-rtl-univ.show-livewire', $rtmJadwal->id) }}" class="btn btn-outline-light mb-3">
                                <i class="fas fa-arrow-left mr-1"></i> Kembali
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div id="catatan-container">
                            <div class="form-group">
                                <label>Judul</label>
                                <input type="text" dusk="judul-input" class="form-control @error('judul') is-invalid @enderror" 
                                wire:model.defer="{{ $editingCatatanId ? 'editingJudul' : 'judul' }}"
                                    placeholder="Tambahkan judul narasi" required>
                                @error('judul') <span class="invalid-feedback">{{ $message }}</span> @enderror
                            </div>
                            <div class="form-group">
                                <label>Isi</label>
                                <div wire:ignore> 
                                    <textarea class="form-control summernote" dusk="isi-textarea"
                                        wire:model.defer="{{ $editingCatatanId ? 'editingIsi' : 'isi' }}"
                                        placeholder="Tambahkan isian narasi" required></textarea>
                                </div>
                                @error('isi') <span class="invalid-feedback">{{ $message }}</span> @enderror
                            </div>
                            <div class="mt-3">
                                <button type="submit" id="submit-button" class="btn btn-primary">
                                    {{ $editingCatatanId ? 'Update' : 'Submit' }}
                                </button>

                                @if($editingCatatanId)
                                    <button type="button" class="btn btn-secondary ml-2" wire:click="cancelEdit">
                                        Batal
                                    </button>
                                @endif
                            </div>
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
                                                <button class="btn btn-sm btn-warning" wire:click="edit({{ $item->id }})" dusk="edit-catatan-{{ $item->id }}">
                                                    <i class="fas fa-pencil-alt me-1"></i>
                                                </button>

                                                <button type="button" class="btn btn-danger btn-sm" wire:click="confirmDelete({{ $item->id }})" dusk="delete-catatan-{{ $item->id }}">
                                                    <i class="fas fa-trash-alt me-1"></i>
                                                </button>
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

    @section('style')
        <link rel="stylesheet" href="{{ asset('plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
        <link rel="stylesheet" href="{{ asset('plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">

        <link rel="stylesheet" href="{{ asset('plugins/summernote/summernote-bs4.min.css') }}">
        {{-- <link rel="stylesheet" href="{{ asset('plugins/bootstrap/css/bootstrap.min.css') }}"> --}}

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
        {{-- <script src="{{ asset('plugins/jquery/jquery.min.js') }}"></script>
        <script src="{{ asset('plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script> --}}
        <script src="{{ asset('plugins/summernote/summernote-bs4.min.js') }}"></script>
        <script src="{{ asset('plugins/datatables/jquery.dataTables.min.js') }}"></script>
        <script src="{{ asset('plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>

        <script>
            document.addEventListener('livewire:initialized', () => {
                // SweetAlert2 (untuk pesan sukses/error)
                Livewire.on('show-alert', (event) => {
                    Swal.fire({
                        icon: event[0].type,
                        title: event[0].type === 'success' ? 'Berhasil!' : 'Oops...',
                        text: event[0].message
                    });
                });

                // SweetAlert2 (untuk konfirmasi hapus)
                Livewire.on('confirmDeleteAlert', (id) => {
                    Swal.fire({
                        title: 'Hapus Catatan Narasi RTM?',
                        text: "Apakah Anda yakin ingin menghapus narasi ini?",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Ya, Hapus!',
                        cancelButtonText: 'Batal'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            Livewire.dispatch('deleteCatatan', id);
                        }
                    });
                });

                // Inisialisasi DataTables
                $('#catatan').DataTable({
                    responsive: true,
                    autoWidth: false,
                    "columnDefs": [{
                        "width": "5%",
                        "targets": [0]
                    }, {
                        "width": "20%",
                        "targets": [1]
                    }, {
                        "width": "65%",
                        "targets": [2]
                    }, {
                        "width": "10%",
                        "targets": [3]
                    }]
                });

                // Inisialisasi Summernote untuk form create
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
                        onChange: debounce(function(contents, $editable) {
                            const mode = @this.editingCatatanId ? 'edit' : 'create';
                            @this.dispatch('summernoteUpdate', [{ 
                                id: mode, 
                                content: contents 
                            }]);
                        }, 500)
                    }
                });

                Livewire.on('initSummernoteEdit', (event) => {
                    if (event[0].id === 'main') {
                        $('.summernote').summernote('code', event[0].content);
                    }
                });

                // Handler untuk mereset summernote
                Livewire.on('resetSummernote', () => {
                    $('.summernote').summernote('reset');
                });

                // Debounce function (tetap diperlukan untuk Summernote onChange)
                function debounce(func, delay) {
                    let debounceTimer;
                    return function() {
                        const context = this;
                        const args = arguments;
                        clearTimeout(debounceTimer);
                        debounceTimer = setTimeout(() => func.apply(context, args), delay);
                    };
                }
            });
        </script>
    @endsection
</div>
