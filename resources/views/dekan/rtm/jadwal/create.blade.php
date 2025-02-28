@extends('components.layout.auditee_layout')

@section('content')
    {{-- Agenda Rapat --}}
    <div class="card border-0 bg-white text-black shadow-sm mb-4">
        <div class="card-header bg-black text-white">
            <h3 class="card-title title-size">
                Agenda Rapat Tinjauan Manajemen
            </h3>
        </div>

        <div class="container py-4">
            <form id="form-rapat" action="{{ route('dekan.jadwal-rtm.store') }}" method="POST" enctype="multipart/form-data" class="mb-5">
                @csrf
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="agenda" class="form-label">Agenda:</label>
                        <input type="text" name="agenda" class="form-control bg-white text-black" required>
                    </div>
                    <div class="col-md-6">
                        <label for="tanggal" class="form-label">Tanggal:</label>
                        <input type="date" name="tanggal" class="form-control bg-white text-black" required>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="tempat" class="form-label">Tempat:</label>
                        <input type="text" name="tempat" class="form-control bg-white text-black" required>
                    </div>
                    <div class="col-md-3">
                        <label for="jam_mulai" class="form-label">Jam Mulai:</label>
                        <input type="time" name="jam_mulai" class="form-control bg-white text-black" required>
                    </div>
                    <div class="col-md-3">
                        <label for="jam_selesai" class="form-label">Jam Selesai:</label>
                        <input type="time" name="jam_selesai" class="form-control bg-white text-black" required>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="pimpinan" class="form-label">Pimpinan Rapat:</label>
                    <input type="text" name="pimpinan" class="form-control bg-white text-black" required>
                </div>

                <div class="form-group">
                    <label for="jadwal_audit_id">Pilih Periode Audit</label>
                    <small class="form-text text-muted">Pastikan memilih jadwal audit</small>
                    <select name="jadwal_audit_id" class="form-control" required>
                        @foreach($jadwalAudit as $jadwalAudit)
                            <option value="{{ $jadwalAudit->id }}">{{ $jadwalAudit->jadwal }}</option>
                        @endforeach
                    </select>
                </div>

                @if($isUnit)
                    <div class="form-group">
                        <label for="unit_id">Pilih Unit</label>
                        <small class="form-text text-muted">Pastikan memilih unit</small>
                        <select name="unit_id" class="form-control" required>
                            @foreach($units as $unitItem)
                                <option value="{{ $unitItem->id }}">{{ $unitItem->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                @else
                    <div class="form-group">
                        <label for="fakultas_id">Pilih Fakultas</label>
                        <small class="form-text text-muted">Pastikan memilih fakultas</small>
                        <select name="fakultas_id" class="form-control" required>
                            @foreach($fakultas as $fakultasItem)
                                <option value="{{ $fakultasItem->id }}">{{ $fakultasItem->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                @endif

                
                <div class="mb-3">
                    <label for="peserta" class="form-label">Jumlah Peserta:</label>
                    <small class="form-text text-muted">Masukkan jumlah peserta rapat (dalam format angka)</small>
                    <input type="number" name="peserta" class="form-control bg-white text-black" required min="1">
                    
                </div>

                <div class="d-flex justify-content-end gap-2">
                    <button type="submit" class="btn btn-success me-2">Simpan</button>
                    <a href="{{ route('dekan.jadwal-rtm.index') }}" class="btn btn-outline-secondary">Kembali</a>
                </div>                
            </form>
        </div>
    </div>
@endsection

@section('style')
    <!-- Select2 -->
    <link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('dist/css/adminlte.min.css') }}">
@endsection

