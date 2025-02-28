@extends('components.layout.main_layout')

@section('content')
<div class="container">
    <h2>{{ $title }}</h2>
    <div class="mb-3">
        <a href="{{ route('hasil_rtm_fakultas.index') }}" class="btn btn-outline-secondary">Kembali</a>
    </div>

    <!-- Menampilkan detail jadwal RTM -->
    <div class="card">
        <div class="card-body">
            <div class="card mt-3">
                <div class="card-header bg-primary text-white">
                    <h4>Detail Jadwal RTM</h4>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <p><strong>Agenda:</strong> {{ $rtmJadwal->agenda }}</p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Tanggal:</strong> {{ \Carbon\Carbon::parse($rtmJadwal->tanggal)->format('d-m-Y') }}</p>
                        </div>
                    </div>
        
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <p><strong>Jam Mulai:</strong> {{ \Carbon\Carbon::parse($rtmJadwal->jam_mulai)->format('H:i') }}</p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Jam Selesai:</strong> {{ \Carbon\Carbon::parse($rtmJadwal->jam_selesai)->format('H:i') }}</p>
                        </div>
                    </div>
        
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <p><strong>Tempat:</strong> {{ $rtmJadwal->tempat }}</p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Pimpinan Rapat:</strong> {{ $rtmJadwal->pimpinan }}</p>
                        </div>
                    </div>
        
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <p><strong>Jadwal Audit:</strong> {{ $rtmJadwal->jadwal_audit->jadwal }}</p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Fakultas/Unit:</strong> {{ $rtmJadwal->fakultas?->nama ?? $rtmJadwal->unit?->nama ?? 'Tidak ada data fakultas atau unit' }}</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <p><strong>Jumlah Peserta Rapat:</strong> {{ $rtmJadwal->peserta }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection