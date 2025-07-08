@php
    use Carbon\Carbon;
@endphp

<div>
    <div class="card card-dark">
        <div class="card-header">
            <h3 class="card-title title-size">{{ $title }}</h3>
        </div>
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-3"> {{-- Tambahkan div ini untuk layout --}}
                <div>
                    <a href="{{ asset('user_manual/RTM_Universitas.pdf') }}" target="_blank" class="btn btn-outline-info mr-2 mb-3">
                        <i class="fa fa-book mr-2"></i> User Manual
                    </a>
                    <a href="{{ route('admin.rtm-univ.create-livewire')}}" class="btn btn-outline-primary mr-2 mb-3">Buat Agenda RTM</a>
                </div>
                {{-- Input searching --}}
                <div class="input-group" style="width: 300px;"> 
                    <input type="text" wire:model.live.debounce.300ms="search" class="form-control" placeholder="Cari agenda, tanggal, atau periode audit...">
                    <div class="input-group-append">
                        <span class="input-group-text"><i class="fas fa-search"></i></span>
                    </div>
                </div>
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
                    @forelse ($rtmJadwal as $item)
                        <tr>
                            <td class="text-center">{{ ($rtmJadwal->currentPage() - 1) * $rtmJadwal->perPage() + $loop->index + 1 }}</td>
                            <td class="text-center">{{ $item->agenda }}</td>
                            <td class="text-center">{{ Carbon::parse($item->tanggal)->translatedFormat('l, j F Y') }}</td>
                            <td class="text-center">{{ Carbon::parse($item->jam_mulai)->translatedFormat('H:i') }} - {{ Carbon::parse($item->jam_selesai)->translatedFormat('H:i') }}</td>
                            <td class="text-center">{{ $item->jadwal_audit->jadwal }}</td>

                            {{-- Lampiran --}}
                            <td class="text-center">
                                <button class="btn btn-outline-primary btn-sm btn-fixed-size lampiran-btn"
                                        wire:click="openLampiranModal('{{ $item->id }}')">
                                    +Lampiran
                                </button>
                            </td>

                            {{-- Tindak Lanjut RTM --}}
                            <td class="text-center">
                                <a href="{{ route('admin.rtm-rtl-univ.show-livewire', $item->id) }}" class="btn btn-primary">Lihat RTL</a>
                            </td>
                            
                            {{-- Aksi --}}
                            <td class="text-center">
                                <div class="dropdown">
                                    <button class="btn btn-outline-primary btn-sm btn-fixed-size dropdown-toggle" type="button"
                                            id="dropdownMenuButton{{ $item->id }}" data-toggle="dropdown" aria-haspopup="true"
                                            aria-expanded="false">
                                        Aksi
                                    </button>
                                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton{{ $item->id }}">
                                        <li>
                                            <a class="dropdown-item" href="{{ route('admin.rtm-univ.show-livewire', $item->id) }}">
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
                                            wire:click.prevent="deleteJadwal('{{ $item->id }}')" dusk="delete-rtm-id-{{ $item->id }}"
                                            wire:confirm="Apakah Anda yakin ingin menghapus jadwal RTM ini?">
                                                <i class="fas fa-trash-alt"></i> Hapus
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center">Tidak ada data jadwal RTM.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <div class="mt-4">
                {{ $rtmJadwal->links() }}
            </div>
        </div>
    </div>

    <div class="modal fade" id="livewireLampiranModal" tabindex="-1" aria-labelledby="lampiranModalLabel" aria-hidden="true"
        wire:ignore.self>
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="lampiranModalLabel">Lampiran RTM</h5>
                    <button type="button" class="close" wire:click="closeLampiranModal" aria-label="Close"></button>
                </div>
                {{-- Gunakan wire:submit.prevent untuk mengirim form melalui Livewire --}}
                <form wire:submit.prevent="saveLampiran">
                    <div class="modal-body">
                        <input type="hidden" wire:model="rtmJadwalId"> {{-- Tidak perlu name di sini --}}

                        <div class="mb-3">
                            <label for="undangan" class="form-label">Upload File Undangan</label>
                            <small class="form-text text-muted" style="margin: -10px 0 8px 0">Upload file pdf dengan ukuran maks. 2mb</small>
                            <input type="file" class="form-control @error('undangan') is-invalid @enderror" id="undangan" wire:model="undangan">
                            @error('undangan') <span class="text-danger">{{ $message }}</span> @enderror
                            <div wire:loading wire:target="undangan">Mengunggah Undangan...</div>
                        </div>
                        <div class="mb-3">
                            <label for="presensi" class="form-label">Upload File Presensi</label>
                            <small class="form-text text-muted" style="margin: -10px 0 8px 0">Upload file pdf dengan ukuran maks. 2mb</small>
                            <input type="file" class="form-control @error('presensi') is-invalid @enderror" id="presensi" wire:model="presensi">
                            @error('presensi') <span class="text-danger">{{ $message }}</span> @enderror
                            <div wire:loading wire:target="presensi">Mengunggah Presensi...</div>
                        </div>
                        <div class="mb-3">
                            <label for="dokumentasi" class="form-label">Upload File Dokumentasi</label>
                            <small class="form-text text-muted" style="margin: -10px 0 8px 0">Upload file pdf dengan ukuran maks. 5mb</small>
                            <input type="file" class="form-control @error('dokumentasi') is-invalid @enderror" id="dokumentasi" wire:model="dokumentasi">
                            @error('dokumentasi') <span class="text-danger">{{ $message }}</span> @enderror
                            <div wire:loading wire:target="dokumentasi">Mengunggah Dokumentasi...</div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" wire:click="closeLampiranModal">Tutup</button>
                        <button type="submit" class="btn btn-primary" wire:loading.attr="disabled" wire:target="saveLampiran, undangan, presensi, dokumentasi">
                            <span wire:loading wire:target="saveLampiran, undangan, presensi, dokumentasi" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                            <span wire:loading.remove wire:target="saveLampiran, undangan, presensi, dokumentasi">Simpan</span>
                            <span wire:loading wire:target="saveLampiran, undangan, presensi, dokumentasi">Menyimpan...</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
       
        <script>
            document.addEventListener('livewire:initialized', () => {
                Livewire.on('swal:modal', (event) => {
                    Swal.fire({
                        icon: event[0].icon,
                        title: event[0].title,
                        text: event[0].text,
                        showConfirmButton: event[0].showConfirmButton !== undefined ? event[0].showConfirmButton : true,
                        timer: event[0].timer || null
                    });
                });

                Livewire.on('show-modal', () => {
                    $('#livewireLampiranModal').modal('show');
                });

                Livewire.on('hide-modal', () => {
                    $('#livewireLampiranModal').modal('hide');
                });

                // Event listener untuk memastikan modal tampil/sembunyi dengan benar
                Livewire.hook('element.init', ({ component, el }) => {
                    if (el.id === 'livewireLampiranModal') {
                        $(el).on('show.bs.modal', function() {
                            component.set('showLampiranModal', true);
                        });
                        $(el).on('hide.bs.modal', function() {
                            component.set('showLampiranModal', false);
                        });
                    }
                });

                
            });
        </script>
    @endpush
</div>