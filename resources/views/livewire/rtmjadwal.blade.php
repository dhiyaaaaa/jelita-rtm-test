@php
    use Carbon\Carbon;
@endphp

<div class="livewire-rtm-index-wrapper"> {{-- Ini adalah elemen root tunggal yang baru --}}
    <div class="card card-dark">
        <div class="card-header">
            <h3 class="card-title title-size">Agenda RTM</h3>
            <div class="card-tools">
                <input type="text" wire:model.live.debounce.500ms="search" class="form-control" placeholder="Cari agenda...">
            </div>
        </div>
        <!-- /.card-header -->
        <div class="card-body">
            <a href="{{ route('admin.rtm-univ.create')}}" class="btn btn-outline-primary mr-2 mb-3">Buat Agenda RTM</a>
            <div class="">
                <a href="{{ asset('user_manual/RTM_Univ.pdf') }}" target="_blank" class="btn btn-outline-info mr-2 mb-3">
                    <i class="fa fa-book mr-2"></i> User Manual
                </a>
            </div>
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
                    @forelse ($rtmJadwal as $index => $item)
                        <tr>
                            <td class="text-center">{{ $rtmJadwal->firstItem() + $index }}</td>
                            <td class="text-center">{{ $item->agenda }}</td>
                            <td class="text-center">{{ Carbon::parse($item->tanggal)->translatedFormat('l, j F Y') }}</td>
                            <td class="text-center">{{ Carbon::parse($item->jam_mulai)->translatedFormat('H:i') }} - {{ Carbon::parse($item->jam_selesai)->translatedFormat('H:i') }}</td>
                            <td class="text-center">{{ $item->jadwal_audit->jadwal }}</td>

                            <td class="text-center">
                                <button wire:click="openLampiranModal('{{ $item->id }}')" class="btn btn-outline-primary btn-sm btn-fixed-size">
                                    +Lampiran
                                </button>
                            </td>

                            <td class="text-center">
                                <a href="{{ route('admin.rtm-rtl.show', $item->id) }}" class="btn btn-primary">Lihat RTL</a>
                            </td>

                            <td class="text-center">
                                <div class="dropdown">
                                    <button class="btn btn-outline-primary btn-sm btn-fixed-size dropdown-toggle" type="button"
                                        id="dropdownMenuButton_{{ $item->id }}" data-toggle="dropdown" aria-haspopup="true"
                                        aria-expanded="false">
                                        Aksi
                                    </button>
                                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton_{{ $item->id }}">
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
                                            <a class="dropdown-item text-danger" href="#"
                                            wire:click.prevent="confirmDelete('{{ $item->id }}')">
                                                <i class="fas fa-trash-alt"></i> Hapus
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center">Tidak ada data jadwal RTM ditemukan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            {{ $rtmJadwal->links() }}
        </div>
    </div>

    <!-- Modal Lampiran Livewire -->
    {{-- Gunakan x-data dan x-show untuk kontrol modal yang lebih eksplisit dengan Alpine.js --}}
    {{-- @entangle adalah cara Livewire "menautkan" properti JS ke properti Livewire PHP --}}
    {{-- x-cloak menyembunyikan elemen sebelum Alpine diinisialisasi untuk mencegah flicker --}}
    <div x-data="{ show: @entangle('showLampiranModal') }" x-show="show" x-cloak
        :class="{'show d-block': show}" {{-- Tambahkan kelas Bootstrap 'show' dan 'd-block' secara dinamis --}}
        class="modal fade" tabindex="-1" role="dialog" aria-hidden="true"
        {{-- Tambahkan x-on:click.outside untuk menutup modal jika klik di luar --}}
        x-on:click.outside="show = false; $wire.closeLampiranModal()">

        <div class="modal-dialog modal-dialog-centered" role="document"> {{-- Tambahkan modal-dialog-centered --}}
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Lampiran RTM</h5>
                    <button type="button" class="close" aria-label="Close"
                        x-on:click="show = false; $wire.closeLampiranModal()">
                        <span>&times;</span>
                    </button>
                </div>
                <form wire:submit.prevent="saveLampiran">
                    <div class="modal-body">
                        <input type="hidden" wire:model="rtmJadwalId">

                        <div class="mb-3">
                            <label for="undangan" class="form-label">Upload File Undangan</label>
                            <small class="form-text text-muted" style="margin: -10px 0 8px 0">Upload file pdf dengan ukuran maks. 2mb</small>
                            <input type="file" class="form-control" id="undangan" wire:model="undangan">
                            @error('undangan') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                        <div class="mb-3">
                            <label for="presensi" class="form-label">Upload File Presensi</label>
                            <small class="form-text text-muted" style="margin: -10px 0 8px 0">Upload file pdf dengan ukuran maks. 2mb</small>
                            <input type="file" class="form-control" id="presensi" wire:model="presensi">
                            @error('presensi') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                        <div class="mb-3">
                            <label for="dokumentasi" class="form-label">Upload File Dokumentasi</label>
                            <small class="form-text text-muted" style="margin: -10px 0 8px 0">Upload file pdf dengan ukuran maks. 5mb</small>
                            <input type="file" class="form-control" id="dokumentasi" wire:model="dokumentasi">
                            @error('dokumentasi') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary"
                            x-on:click="show = false; $wire.closeLampiranModal()">Tutup</button>
                        <button type="submit" class="btn btn-primary" wire:loading.attr="disabled">
                            <span wire:loading wire:target="saveLampiran" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                            <span wire:loading.remove wire:target="saveLampiran"> Simpan</span>
                            <span wire:loading wire:target="saveLampiran"> Menyimpan...</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    {{-- Backdrop modal. --}}
    @if ($showLampiranModal)
        <div class="modal-backdrop fade show"></div>
    @endif
</div> {{-- Tutup elemen root tunggal --}}

@push('scripts')
{{-- Memuat SweetAlert2 JS --}}
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    document.addEventListener('livewire:initialized', () => {
        @this.on('swal:confirm', (event) => {
            Swal.fire({
                title: event.detail.title,
                text: event.detail.text,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    @this.call('delete', event.detail.id);
                }
            });
        });

        @this.on('swal:success', (event) => {
            Swal.fire({
                icon: 'success',
                title: event.detail.title,
                text: event.detail.text,
                showConfirmButton: false,
                timer: 1500
            });
        });

        @this.on('swal:error', (event) => {
            Swal.fire({
                icon: 'error',
                title: event.detail.title,
                text: event.detail.text,
                confirmButtonText: 'Tutup'
            });
        });

        @this.on('file-inputs-reset', () => {
            const fileInputs = document.querySelectorAll('input[type="file"]');
            fileInputs.forEach(input => {
                input.value = '';
            });
        });

        Livewire.hook('element.init', ({ component, el }) => {
            if (el.id === 'rtm') {
                if ($.fn.DataTable.isDataTable('#rtm')) {
                    $('#rtm').DataTable().destroy();
                }
                $('#rtm').DataTable({
                    "responsive": true,
                    "autoWidth": false,
                    "paging": false,
                    "searching": false,
                    "info": false,
                    "columnDefs": []
                });
            }
        });
    });
</script>
@endpush
