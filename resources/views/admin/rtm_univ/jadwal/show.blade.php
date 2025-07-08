@extends('components.layout.main_layout')

@section('content')
<div class="container">

    <div class="card">
        <div class="card-body">
            @if($isEditMode)
                <form action="{{ route('admin.rtm-univ.update', $item->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="agenda" class="form-label">Agenda:</label>
                            <input type="text" name="agenda" id="agenda" class="form-control" value="{{ old('agenda', $item->agenda) }}" required>
                        </div>
                        <div class="col-md-6">
                            <label for="tanggal" class="form-label">Tanggal:</label>
                            <input type="date" name="tanggal" id="tanggal" class="form-control" value="{{ old('tanggal', $item->tanggal) }}" required>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="tempat" class="form-label">Tempat:</label>
                            <input type="text" name="tempat" id="tempat" class="form-control" value="{{ old('tempat', $item->tempat) }}" required>
                        </div>
                        <div class="col-md-3">
                            <label for="jam_mulai" class="form-label">Jam Mulai:</label>
                            <input type="time" name="jam_mulai" class="form-control bg-white text-black" value="{{ old('jam_mulai', $item->jam_mulai) }}" required>
                        </div>
                        <div class="col-md-3">
                            <label for="jam_selesai" class="form-label">Jam Selesai:</label>
                            <input type="time" name="jam_selesai" class="form-control bg-white text-black" value="{{ old('jam_selesai', $item->jam_selesai) }}" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="pimpinan" class="form-label">Pimpinan Rapat:</label>
                        <input type="text" name="pimpinan" id="pimpinan" class="form-control" value="{{ old('pimpinan', $item->pimpinan) }}" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="jadwal_audit_id">Pilih Periode Audit</label>
                        <select name="jadwal_audit_id" id="jadwal_audit_id" class="form-control" required>
                            @foreach($jadwalAudit as $jadwal)
                                <option value="{{ $jadwal->id }}" 
                                        {{ old('jadwal_audit_id', $item->jadwal_audit_id) == $jadwal->id ? 'selected' : '' }}>
                                    {{ $jadwal->jadwal }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label for="peserta" class="form-label">Jumlah Peserta Rapat:</label>
                        <small class="form-text text-muted">Masukkan jumlah peserta rapat (dalam format angka)</small>
                        <input type="text" name="peserta" id="peserta" class="form-control" value="{{ old('peserta', $item->peserta) }}" required>
                    </div>
                    <button type="submit" class="btn btn-primary me-2">Simpan Perubahan</button>
                    <a href="{{ route("admin.rtm-univ.index") }}" class="btn btn-outline-secondary me-2">Kembali</a>
                </form>
            @else
                {{-- This is the display-only mode content --}}
                <div class="card mt-3">
                    <div class="card-header bg-primary text-white">
                        <h4>Detail Jadwal RTM</h4>
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
                        </div>
                        <div class="col-md-6">
                            <p><strong>Jumlah Peserta Rapat:</strong> {{ $item->peserta }}</p>
                        </div>
                    </div>
                    <div class="card-footer text-center">
                        <a href="{{ route("admin.rtm-univ.index") }}" class="btn btn-outline-secondary me-2">Kembali</a>
                        <a href="{{ route('admin.rtm-univ.show', ['id' => $item->id, 'edit' => 'true']) }}" class="btn btn-warning" dusk="edit-jadwal-btn">Edit Jadwal</a>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection