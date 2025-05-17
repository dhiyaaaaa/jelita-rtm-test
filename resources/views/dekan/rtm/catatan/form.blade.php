@extends('components.layout.main_layout')

@section('content')
<div class="container-fluid px-4">
    <form id="catatan-form" action="{{ route('dekan.rtm-catatan.store', ['rtmJadwal' => $rtmJadwal]) }}" method="POST" id="catatan-form">
        @csrf

        <div class="card">
            <div class="card-header">
                <h3 class="card-title text-bold">{{ $title }}</h3>
            </div>

            <div class="card-body">
                @php
                    // isDisabled
                    $isDisabled = false;
                    if (isset($status) && $status->status === 'completed') {
                        $isDisabled = true;
                    }
                @endphp
                <div id="catatan-container">
                    @if (!empty($rtmCatatan))
                        @foreach($rtmCatatan as $index => $catatan)
                            <div class="catatan-group mb-4 p-3 border rounded">
                                <input type="hidden" name="catatan[{{$index}}][id]" value="{{ $catatan['id'] ?? $catatan->id ?? '' }}">
                                
                                <div class="form-group">
                                    <label>Judul</label>
                                    <input type="text" name="catatan[{{$index}}][judul]" 
                                        value="{{ $catatan['judul'] ?? $catatan->judul ?? old('catatan.'.$index.'.judul', '') }}" 
                                        class="form-control" required {{ $isDisabled ? 'disabled' : '' }}>
                                </div>

                                <div class="form-group">
                                    <label>Catatan Narasi</label>
                                    <textarea name="catatan[{{$index}}][catatan]" 
                                        class="form-control" rows="5" {{ $isDisabled ? 'disabled' : '' }} required>{{ $catatan['catatan'] ?? $catatan->catatan ?? old('catatan.'.$index.'.catatan', '') }}</textarea>
                                </div>
                                
                                @if(!$isDisabled)
                                    <button type="button" class="btn btn-danger btn-sm remove-catatan">Hapus</button>
                                @endif
                            </div>
                        @endforeach
                    @else
                        <div class="catatan-group mb-4 p-3 border rounded">
                            <div class="form-group">
                                <label>Judul</label>
                                <input type="text" name="catatan[0][judul]" class="form-control" required {{ $isDisabled ? 'disabled' : '' }}>
                            </div>
                            <div class="form-group">
                                <label>Catatan Narasi</label>
                                <textarea name="catatan[0][catatan]" class="form-control" rows="5" required {{ $isDisabled ? 'disabled' : '' }}></textarea>
                            </div>
                        </div>
                    @endif
                </div>
                <div class="mt-3">
                    <button type="button" id="add-catatan" class="btn btn-secondary">+ Tambah Narasi</button>

                    @if(isset($status) && $status->status === 'completed')
                        <button type="button" id="edit-button" class="btn btn-warning">Ubah</button>
                        <button id="edit-button-loading" class="btn btn-warning d-none" type="button" disabled>
                            <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                            Loading...
                        </button>
                    @else
                        <button type="submit" id="submit-button" class="btn btn-primary">Submit</button>
                        <button id="submit-button-loading" class="btn btn-primary d-none" type="button" disabled>
                            <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                            Loading...
                        </button>
                    @endif
                </div>
                <div class="d-flex justify-content-start mt-4">
                    <a href="{{ route('dekan.rtm-rtl.show', ['rtmRtl' => $rtmRtl->id]) }}" class="btn btn-outline-dark mb-3">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection

@section('script')
<script>

    $(document).ready(function() {

        @if(session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: '{{ session('success') }}',
                timer: 3000,
                showConfirmButton: false
            });
        @endif

        // Form submission with SweetAlert confirmation
        $('#catatan-form').on('submit', function(e) {
            e.preventDefault();
            
            Swal.fire({
                title: 'Simpan Catatan?',
                text: "Apakah Anda yakin ingin menyimpan catatan ini?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: 'primary',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, Simpan!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    $('#submit-button').addClass('d-none');
                    $('#submit-button-loading').removeClass('d-none');
                    
                    this.submit();
                }
            });
        });

        // Edit button functionality
        $('#edit-button').on('click', function() {
            Swal.fire({
                title: 'Ubah Catatan?',
                text: "Apakah Anda yakin ingin mengubah catatan yang sudah disimpan?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: 'primary',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, Ubah!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    $('#edit-button').addClass('d-none');
                    $('#edit-button-loading').removeClass('d-none');
                    
                    $.ajax({
                        url: "{{ route('dekan.rtm-catatan.isi', ['rtmJadwal' => $rtmJadwal]) }}",
                        type: 'POST',
                        data: {
                            _token: "{{ csrf_token() }}"
                        },
                        success: function(response) {
                            window.location.reload();
                        },
                        error: function(xhr) {
                            $('#edit-button').removeClass('d-none');
                            $('#edit-button-loading').addClass('d-none');
                            Swal.fire({
                                icon: 'error',
                                title: 'Error!',
                                text: 'Terjadi kesalahan saat memproses permintaan'
                            });
                        }
                    });
                }
            });
        });

        // Tambah narasi baru
        $('#add-catatan').on('click', function() {
            const container = $('#catatan-container');
            const index = container.find('.catatan-group').length;
            
            const newGroup = $(`
                <div class="catatan-group mb-4 p-3 border rounded">
                    <div class="form-group">
                        <label>Judul</label>
                        <input type="text" name="catatan[${index}][judul]" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Catatan Narasi</label>
                        <textarea name="catatan[${index}][catatan]" class="form-control" rows="5" required></textarea>
                    </div>
                    <button type="button" class="btn btn-danger btn-sm remove-catatan">Hapus</button>
                </div>
            `);
            
            container.append(newGroup);
            save_session();
        });

        // Hapus narasi
        $(document).on('click', '.remove-catatan', function() {
            if($('.catatan-group').length > 1) {
                $(this).closest('.catatan-group').remove();
            } else {
                Swal.fire({
                    icon: 'warning',
                    title: 'Peringatan',
                    text: 'Minimal harus ada satu catatan!'
                });
            }
            save_session();
        });

        function save_session() {
            const formData = new FormData(document.getElementById('catatan-form'));

            $.ajax({
                url: "{{ route('dekan.rtm-catatan.session', ['rtmJadwal' => $rtmJadwal]) }}",
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function() {
                    console.log('Berhasil disimpan ke sesssion');
                }
            });
        }

        $(document).ready(function() {
            setInterval(save_session, 5000);
            
            $('input, textarea').on('input', debounce(function() {
                save_session();
            }, 1000));
        });

        // Fungsi debounce sama persis dengan kode pertama
        function debounce(func, delay) {
            let debounceTimer;
            return function() {
                clearTimeout(debounceTimer);
                debounceTimer = setTimeout(() => func.apply(this, arguments), delay);
            };
        }
    });
</script>
@endsection