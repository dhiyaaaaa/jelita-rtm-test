@php
    use Carbon\Carbon;
@endphp
@extends('components.layout.main_layout')

@section('content')
<div class="card card-dark">
        <div class="card-header">
            <h3 class="card-title title-size">{{ $title }}</h3>
            <div class="card-tools">
                <input type="text" wire:model.live.debounce.500ms="search" class="form-control" placeholder="Cari agenda...">
            </div>
        </div>
        <!-- /.card-header -->
        <div class="card-body">
            <a href="{{ route('admin.rtm-univ.create')}}" class="btn btn-outline-primary mr-2 mb-3">Buat Agenda RTM</a>
            <div class="">
                <a href="{{ asset('user_manual/RTM_Universitas.pdf') }}" target="_blank" class="btn btn-outline-info mr-2 mb-3">
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
                                    <button type="button" class="btn btn-outline-primary" data-toggle="modal" data-target="#lampiranModal">
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
                                        id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true"
                                        aria-expanded="false">
                                        Aksi
                                    </button>
                                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                        <li>
                                            <a class="dropdown-item" href="{{ route('admin.rtm-univ.show', $item->id) }}">
                                                <i class="fas fa-file"></i> Lihat Agenda
                                            </a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item text-danger" href="#" 
                                            wire:click="confirmDelete({{ $item->id }})">
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
            {{ $rtmJadwal->links() }}
        </div>
    </div>

    <!-- Modal Lampiran -->
        <div class="modal fade" id="lampiranModal" tabindex="-1" aria-labelledby="lampiranModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="lampiranModalLabel">Lampiran RTM</h5>
                        <button type="button" class="close" wire:click="closeLampiranModal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form wire:submit.prevent="saveLampiran">
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="undangan">Undangan</label>
                                <input type="file" class="form-control" id="undangan" wire:model="undangan">
                                @error('undangan') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                            <div class="form-group">
                                <label for="presensi">Presensi</label>
                                <input type="file" class="form-control" id="presensi" wire:model="presensi">
                                @error('presensi') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                            <div class="form-group">
                                <label for="dokumentasi">Dokumentasi</label>
                                <input type="file" class="form-control" id="dokumentasi" wire:model="dokumentasi">
                                @error('dokumentasi') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" wire:click="closeLampiranModal">Tutup</button>
                            <button type="submit" class="btn btn-primary">Simpan Lampiran</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="modal-backdrop fade show"></div>
@endsection
