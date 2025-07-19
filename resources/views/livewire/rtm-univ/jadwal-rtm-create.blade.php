<div class="card border-0 bg-white text-black shadow-sm mb-4">
    <div class="card-header bg-black text-white">
        <h3 class="card-title title-size">
            Agenda Rapat Tinjauan Manajemen (Livewire Version)
        </h3>
    </div>

    <div class="container py-4">

        <form wire:submit.prevent="store" class="mb-5">
            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="agenda" class="form-label">Agenda:</label>
                    <input type="text" wire:model="agenda" dusk="agenda-input" class="form-control @error('agenda') is-invalid @enderror bg-white text-black">
                    @error('agenda') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-6">
                    <label for="tanggal" class="form-label">Tanggal:</label>
                    <input type="date" wire:model="tanggal" dusk="tanggal-input" class="form-control @error('tanggal') is-invalid @enderror bg-white text-black">
                    @error('tanggal') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="tempat" class="form-label">Tempat:</label>
                    <input type="text" wire:model="tempat" dusk="tempat-input" class="form-control @error('tempat') is-invalid @enderror bg-white text-black">
                    @error('tempat') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-3">
                    <label for="jam_mulai" class="form-label">Jam Mulai:</label>
                    <input type="time" wire:model="jam_mulai" dusk="jam_mulai-input" class="form-control @error('jam_mulai') is-invalid @enderror bg-white text-black">
                    @error('jam_mulai') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-3">
                    <label for="jam_selesai" class="form-label">Jam Selesai:</label>
                    <input type="time" wire:model="jam_selesai" dusk="jam_selesai-input" class="form-control @error('jam_selesai') is-invalid @enderror bg-white text-black">
                    @error('jam_selesai') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>

            <div class="mb-3">
                <label for="pimpinan" class="form-label">Pimpinan Rapat:</label>
                <input type="text" wire:model="pimpinan" dusk="pimpinan-input" class="form-control @error('pimpinan') is-invalid @enderror bg-white text-black">
                @error('pimpinan') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="mb-3">
                <label for="jadwal_audit_id" class="form-label">Pilih Periode Audit</label>
                <small class="form-text text-muted">Pastikan memilih jadwal audit</small>
                <select name="jadwal_audit_id" id="jadwal_audit_id" wire:model="jadwal_audit_id" dusk="jadwal_audit_id-input"
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
                <input type="number" wire:model="peserta" dusk="peserta-input" class="form-control @error('peserta') is-invalid @enderror bg-white text-black" min="1">
                @error('peserta') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="d-flex justify-content-end">
                <button type="submit" class="btn btn-primary me-2" dusk="simpan-button">Simpan</button>
                <a href="{{ route('admin.rtm-univ.index-livewire') }}" class="btn btn-outline-secondary">Kembali</a>
            </div>
            
        </form>
    </div>
</div>