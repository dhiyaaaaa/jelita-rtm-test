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
                        <th class="text-center" style="width: 20%">Agenda</th>
                        <th class="text-center" style="width: 15%">Tanggal</th>
                        <th class="text-center" style="width: 15%">Waktu</th>
                        <th class="text-center" style="width: 15%">Periode Audit</th>
                        <th class="text-center" style="width: 15%">Tindak Lanjut Audit</th>
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

                            {{-- Tindak Lanjut RTM --}}
                            <td class="text-center">
                                <a href="{{ route('admin.rtm-rtl-univ.show-livewire', $item->id) }}" class="btn btn-primary">Lihat RTL</a>
                            </td>
                            
                            {{-- Aksi --}}
                            <td class="text-center">
                                <div class="dropdown" dusk="dropdown-actions-{{ $item->id }}">
                                    <button class="btn btn-outline-primary btn-sm btn-fixed-size dropdown-toggle" type="button"
                                            id="dropdownMenuButton{{ $item->id }}" data-toggle="dropdown" aria-haspopup="true"
                                            aria-expanded="false" dusk="dropdown-toggle-{{ $item->id }}">
                                        Aksi
                                    </button>
                                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton{{ $item->id }}" dusk="dropdown-menu-{{ $item->id }}">
                                        <li>
                                            <a class="dropdown-item" href="{{ route('admin.rtm-univ.show-livewire', $item->id) }}">
                                                <i class="fas fa-file" dusk="view-agenda-{{ $item->id }}"></i> Lihat Agenda
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
</div>