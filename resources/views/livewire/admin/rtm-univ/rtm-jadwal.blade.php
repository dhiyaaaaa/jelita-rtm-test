<div>
    {{-- Ini adalah wrapper div utama untuk komponen Livewire --}}
    @php use Carbon\Carbon; @endphp

    <div class="card card-dark">
        <div class="card-header">
            <h3 class="card-title title-size">{{ $title }}</h3>
        </div>
        <!-- /.card-header -->
        <div class="card-body">
            <div class="">
                <a href="{{ asset('user_manual/RTM_Universitas.pdf') }}" target="_blank" class="btn btn-outline-info mr-2 mb-3">
                    <i class="fa fa-book mr-2"></i> User Manual
                </a>
            </div>
            <a href="{{ route('admin.rtm-univ.create')}}" class="btn btn-outline-primary mr-2 mb-3">Buat Agenda RTM</a>
            <table id="rtm" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th class="text-center" style="width: 5%">No</th>
                        <th class="text-center" style="width: 15%">Agenda</th>
                        <th class="text-center" style="width: 15%">Tanggal</th>
                        <th class="text-center" style="width: 15%">Waktu</th>
                        <th class="text-center" style="width: 15%">Periode Audit</th>
                        <th class="text-center" style="width: 10%">Lampiran</th>
                        <th class="text-center" style="width: 10%">Tindak Lanjut Audit</th>
                        <th class="text-center" style="width: 15%">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @php $no = 1; @endphp
                    {{-- Gunakan @forelse untuk menampilkan pesan jika tidak ada data --}}
                    @forelse ($rtmJadwal as $item)
                        <tr>
                            <td class="text-center">{{ $no++ }}</td>
                            <td class="text-center">{{ $item->agenda }}</td>
                            <td class="text-center">{{ Carbon::parse($item->tanggal)->translatedFormat('l, j F Y') }}</td>
                            <td class="text-center">{{ Carbon::parse($item->jam_mulai)->translatedFormat('H:i') }} - {{ Carbon::parse($item->jam_selesai)->translatedFormat('H:i') }}</td>
                            <td class="text-center">{{ $item->jadwal_audit->jadwal }}</td>

                            {{-- Lampiran --}}
                            <td class="text-center">
                                {{-- wire:click untuk memanggil metode openLampiranModal di komponen Livewire --}}
                                <button class="btn btn-outline-primary btn-sm btn-fixed-size"
                                        wire:click="openLampiranModal({{ $item->id }})">
                                    +Lampiran
                                </button>
                            </td>

                            {{-- Tindak Lanjut RTM --}}
                            <td class="text-center">
                                <a href="{{ route('admin.rtm-rtl.show', $item->id) }}" class="btn btn-primary">Lihat RTL</a>
                            </td>

                            {{-- Aksi --}}
                            <td class="text-center">
                                <div class="dropdown">
                                    <button class="btn btn-outline-primary btn-sm btn-fixed-size dropdown-toggle" type="button"
                                            id="dropdownMenuButton-{{ $item->id }}" data-toggle="dropdown" aria-haspopup="true"
                                            aria-expanded="false">
                                        Aksi
                                    </button>
                                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton-{{ $item->id }}">
                                        <li>
                                            <a class="dropdown-item" href="{{ route('admin.rtm-univ.show', $item->id) }}">
                                                <i class="fas fa-file"></i> Lihat Agenda
                                            </a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item" href="{{ route('download.rtm.univ', $item->id) }}">
                                                <i class="fas fa-download"></i> Download Laporan RTM
                                            </a>
                                        </li>
                                        <li>
                                            {{-- wire:click.prevent untuk memanggil metode konfirmasi penghapusan Livewire --}}
                                            <a class="dropdown-item text-danger" href="#"
                                               wire:click.prevent="confirmRtmDeletion({{ $item->id }})">
                                                <i class="fas fa-trash-alt"></i> Hapus
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center">Tidak ada jadwal RTM yang ditemukan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal Lampiran -->
    {{-- wire:ignore.self menjaga agar Livewire tidak merender ulang modal ini kecuali secara eksplisit --}}
    <div wire:ignore.self class="modal fade" id="lampiranModal" tabindex="-1" aria-labelledby="lampiranModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="lampiranModalLabel">Lampiran RTM</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
                </div>
                {{-- wire:submit.prevent untuk mencegah submit form default dan memanggil metode Livewire --}}
                <form wire:submit.prevent="storeLampiran">
                    {{-- wire:model untuk binding dua arah dengan properti Livewire --}}
                    <input type="hidden" wire:model="rtm_jadwal_id">

                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="undangan" class="form-label">Upload File Undangan</label>
                            <small class="form-text text-muted" style="margin: -10px 0 8px 0">Upload file pdf dengan ukuran maks. 2mb</small>
                            {{-- wire:model untuk upload file. Penting: gunakan ID yang sama dengan properti Livewire --}}
                            <input type="file" class="form-control" wire:model="undangan" id="undangan">
                            @error('undangan') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                        <div class="mb-3">
                            <label for="presensi" class="form-label">Upload File Presensi</label>
                            <small class="form-text text-muted" style="margin: -10px 0 8px 0">Upload file pdf dengan ukuran maks. 2mb</small>
                            <input type="file" class="form-control" wire:model="presensi" id="presensi">
                            @error('presensi') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                        <div class="mb-3">
                            <label for="dokumentasi" class="form-label">Upload File Dokumentasi</label>
                            <small class="form-text text-muted" style="margin: -10px 0 8px 0">Upload file pdf dengan ukuran maks. 5mb</small>
                            <input type="file" class="form-control" wire:model="dokumentasi" id="dokumentasi">
                            @error('dokumentasi') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                        <button type="submit" class="btn btn-primary" wire:loading.attr="disabled">
                            {{-- Indikator loading saat file diupload --}}
                            <span wire:loading wire:target="storeLampiran" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                            <span wire:loading.remove wire:target="storeLampiran">Simpan</span>
                            <span wire:loading wire:target="storeLampiran">Menyimpan...</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@section('style')
    <!-- DataTables -->
    <link rel="stylesheet" href="{{ asset('plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/fontawesome-free/css/all.min.css') }}">
    <!-- SweetAlert2 CSS (Tambahkan ini untuk notifikasi yang lebih baik) -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

    <style>
        .dropdown-item i {
            margin-right: 5px;
        }

        .dropdown-item:hover {
            background-color: #f8f9fa;
        }

        .btn-fixed-size {
            width: 120px;
            height: 38px;
            display: flex;
            align-items: center;
            justify-content: center;
            white-space: nowrap;
        }

        .dropdown .btn-fixed-size {
            width: 120px;
        }
    </style>
@endsection

@section('script')
    <!-- DataTables & Plugins -->
    <script src="{{ asset('plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>
    <!-- SweetAlert2 JS (Tambahkan ini) -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        $(document).ready(function() {
            // Fungsi untuk menginisialisasi ulang DataTables
            function initializeDataTable() {
                // Pastikan DataTables di-destroy dulu sebelum diinisialisasi ulang
                if ($.fn.DataTable.isDataTable('#rtm')) {
                    $('#rtm').DataTable().destroy();
                }
                $('#rtm').DataTable({
                    "responsive": true,
                    "autoWidth": false,
                    "pageLength": 25,
                    "columnDefs": []
                });
            }

            // Inisialisasi DataTables pertama kali saat dokumen siap
            initializeDataTable();

            // Mendengarkan event Livewire 'reloadDataTable' untuk menginisialisasi ulang DataTables
            window.livewire.on('reloadDataTable', () => {
                initializeDataTable();
            });

            // Mendengarkan event Livewire 'openLampiranModal' untuk membuka modal
            window.livewire.on('openLampiranModal', () => {
                $('#lampiranModal').modal('show');
            });

            // Mendengarkan event Livewire 'closeLampiranModal' untuk menutup modal
            window.livewire.on('closeLampiranModal', () => {
                $('#lampiranModal').modal('hide');
            });

            // SweetAlert untuk notifikasi sukses (toast di pojok kanan atas)
            window.livewire.on('showSuccessToast', event => {
                Swal.fire({
                    icon: 'success',
                    title: event.message,
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true,
                    didOpen: (toast) => {
                        toast.addEventListener('mouseenter', Swal.stopTimer)
                        toast.addEventListener('mouseleave', Swal.resumeTimer)
                    }
                });
            });

            // SweetAlert untuk notifikasi error (toast di pojok kanan atas)
            window.livewire.on('showErrorToast', event => {
                Swal.fire({
                    icon: 'error',
                    title: event.message,
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true,
                    didOpen: (toast) => {
                        toast.addEventListener('mouseenter', Swal.stopTimer)
                        toast.addEventListener('mouseleave', Swal.resumeTimer)
                    }
                });
            });

            // SweetAlert untuk konfirmasi penghapusan
            window.livewire.on('showDeleteConfirmation', event => {
                Swal.fire({
                    title: 'Hapus Jadwal RTM!',
                    text: "Apakah Anda yakin ingin menghapus jadwal RTM ini?",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, Hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Jika dikonfirmasi, panggil metode Livewire `destroyRtm`
                        Livewire.emit('deleteConfirmed');
                    }
                })
            });

            // Hapus kode jQuery yang berinteraksi langsung dengan form dan AJAX
            // seperti $('#lampiranForm').submit() dan penyimpanan draft lokal
            // karena Livewire sudah menanganinya secara reaktif.
        });
    </script>
@endsection
