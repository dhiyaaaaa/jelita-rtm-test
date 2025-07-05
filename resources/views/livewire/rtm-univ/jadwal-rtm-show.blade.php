<div class="container">
    <h2>{{ $title }}</h2>
    <div class="mb-3">
        <a href="{{ route('admin.rtm-univ.index-livewire') }}" class="btn btn-outline-secondary">Kembali</a>
    </div>

    <div class="card">
        <div clss="card-body">
        @if (session()->has('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        @if (session()->has('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif
        
        @if($isEditMode)
            <form wire:submit.prevent="updateJadwalRtm">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="agenda" class="form-label">Agenda:</label>
                        <input type="text" wire:model="agenda" class="form-control @error('agenda') is-invalid @enderror bg-white text-black">
                        @error('agenda') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="tanggal" class="form-label">Tanggal:</label>
                        <input type="date" wire:model="tanggal" class="form-control @error('tanggal') is-invalid @enderror bg-white text-black">
                        @error('tanggal') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="tempat" class="form-label">Tempat:</label>
                        <input type="text" wire:model="tempat" class="form-control @error('tempat') is-invalid @enderror bg-white text-black">
                        @error('tempat') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-3">
                        <label for="jam_mulai" class="form-label">Jam Mulai:</label>
                        <input type="time" wire:model="jam_mulai" class="form-control @error('jam_mulai') is-invalid @enderror bg-white text-black">
                        @error('jam_mulai') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-3">
                        <label for="jam_selesai" class="form-label">Jam Selesai:</label>
                        <input type="time" wire:model="jam_selesai" class="form-control @error('jam_selesai') is-invalid @enderror bg-white text-black">
                        @error('jam_selesai') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="mb-3">
                    <label for="pimpinan" class="form-label">Pimpinan Rapat:</label>
                    <input type="text" wire:model="pimpinan" class="form-control @error('pimpinan') is-invalid @enderror bg-white text-black">
                    @error('pimpinan') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="mb-3"> 
                    <label for="jadwal_audit_id" class="form-label">Pilih Periode Audit</label>
                    <small class="form-text text-muted">Pastikan memilih jadwal audit</small>
                    <select name="jadwal_audit_id" id="jadwal_audit_id" wire:model="jadwal_audit_id"
                        class="form-control @error('jadwal_audit_id') is-invalid @enderror bg-white text-black">
                        <option value="">-- Pilih Jadwal Audit --</option> 
                        @foreach ($jadwalAuditOptions as $audit)
                            <option value="{{ $audit->id }}">{{ $audit->jadwal }}</option>
                        @endforeach
                    </select>
                    @error('jadwal_audit_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="mb-3">
                    <label for="peserta" class="form-label">Jumlah Peserta:</label>
                    <small class="form-text text-muted">Masukkan jumlah peserta rapat (dalam format angka)</small>
                    <input type="number" wire:model="peserta" class="form-control @error('peserta') is-invalid @enderror bg-white text-black" min="1">
                    @error('peserta') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="d-flex justify-content-end">
                    <button type="submit" class="btn btn-primary me-2">Simpan</button>
                    <button type="button" wire:click="toggleEditMode" class="btn btn-secondary">Batal</button>
                </div>
            </form>
        @else
            <div class="card mt-3">
                <div class="card-header bg-primary text-white">
                    <h4>{{ $title }}</h4>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <p><strong>Agenda:</strong> {{ $item->agenda }}</p>
                        </div>
                            <div class="col-md-6">
                                <p><strong>Tanggal:</strong> {{ \Carbon\Carbon::parse($item->tanggal)->format('d-m-Y') }}</p>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <p><strong>Jam Mulai:</strong> {{ \Carbon\Carbon::parse($item->jam_mulai)->format('H:i') }}</p>
                            </div>
                            <div class="col-md-6">
                                <p><strong>Jam Selesai:</strong> {{ \Carbon\Carbon::parse($item->jam_selesai)->format('H:i') }}</p>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <p><strong>Tempat:</strong> {{ $item->tempat }}</p>
                            </div>
                            <div class="col-md-6">
                                <p><strong>Pimpinan Rapat:</strong> {{ $item->pimpinan }}</p>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <p><strong>Jadwal Audit:</strong> {{ $item->jadwal_audit->jadwal }}</p>
                            </div>
                            <div class="col-md-6">
                                <p><strong>Jumlah Peserta Rapat:</strong> {{ $item->peserta }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer text-center">
                        <button type="button" wire:click="toggleEditMode" class="btn btn-warning">Edit Jadwal</button>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>