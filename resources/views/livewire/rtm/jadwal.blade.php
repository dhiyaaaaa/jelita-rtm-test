<div class="card card-dark">
        <div class="card-header">
            <h3 class="card-title title-size">{{ $title }}</h3>
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
                    @php
                        $no = 1;
                    @endphp
                        @foreach ($rtmJadwal as $index => $item)
                            <tr>
                                <td class="text-center">{{ $no++ }}</td>
                                <td class="text-center">{{ $item->agenda }}</td>
                                <td class="text-center">{{ Carbon::parse($item->tanggal)->translatedFormat('l, j F Y') }}</td>
                                <td class="text-center">{{ Carbon::parse($item->jam_mulai)->translatedFormat('H:i') }} - {{ Carbon::parse($item->jam_selesai)->translatedFormat('H:i') }}</td>
                                <td class="text-center">{{ $item->jadwal_audit->jadwal }}</td>


                                {{-- Lampiran --}}
                                <td class="text-center">
                                    <button wire:click="openLampiranmodel({{ $item->id }})" class="btn btn-outline-primary btn-sm btn-fixed-size">
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
                                    <button class="btn btn-outline-primary btn-sm btn-fixed-size dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                        Aksi
                                    </button>
                                    <ul class="dropdown-menu">
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
                                            <a class="dropdown-item text-danger delete-btn" href="#" wire:click.prevent="deleteRtm({{ $item->id }})"
                                                onclick="confirm('Apakah Anda yakin ingin menghapus?') || event.stopImmediatePropagation()">
                                                <i class="fas fa-trash-alt"></i> Hapus
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal Lampiran -->
    @if($showLampiranModal)
    <div class="modal fade show" style="display: block; background: rgba(0,0,0,0.5);" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Lampiran RTM</h5>
                    <button type="button" wire:click="$set('showLampiranModal', false)" class="btn-close"></button>
                </div>
                <form wire:submit.prevent="saveLampiran">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="undangan" class="form-label">Upload File Undangan</label>
                            <small class="form-text text-muted" style="margin: -10px 0 8px 0">Upload file pdf dengan ukuran maks. 2mb</small>
                            <input type="file" class="form-control" id="undangan" wire:model="undangan" required>
                            @error('undangan') <span class="text-danger">{{ $message }}</span>@enderror
                        </div>
                        <div class="mb-3">
                            <label for="presensi" class="form-label">Upload File Presensi</label>
                            <small class="form-text text-muted" style="margin: -10px 0 8px 0">Upload file pdf dengan ukuran maks. 2mb</small>
                            <input type="file" class="form-control" id="presensi" wire:model="presensi" required>
                            @error('presensi') <span class="text-danger">{{ $message }}</span>@enderror
                        </div>
                        <div class="mb-3">
                            <label for="dokumentasi" class="form-label">Upload File Dokumentasi</label>
                            <small class="form-text text-muted" style="margin: -10px 0 8px 0">Upload file pdf dengan ukuran maks. 2mb</small>
                            <input type="file" class="form-control" id="dokumentasi" wire:model="dokumentasi" required>
                            @error('dokumentasi') <span class="text-danger">{{ $message }}</span>@enderror
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" wire:click="$set('showLampiranModal', false)">Tutup</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Konfirmasi Pembuatan RTMRTL-->
    <div class="modal fade" id="konfirmasiModal" tabindex="-1" aria-labelledby="konfirmasiModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="konfirmasiModalLabel">Konfirmasi Tindak Lanjut</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Apakah Anda ingin menindaklanjuti hasil audit ini?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-primary" id="konfirmasiTindakLanjut">Ya, Lanjutkan</button>
                </div>
            </div>
        </div>
    </div>