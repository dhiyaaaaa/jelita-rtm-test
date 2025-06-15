@php
    use Carbon\Carbon;
@endphp

@extends('components.layout.main_layout')

@section('content')
    <div class="card shadow border-0" style="background: linear-gradient(135deg, #6694ea, #614ba2); color: white;">
        <div class="card-header border-0">
            <h3 class="card-title title-size text-white">{{ $title }}</h3>
        </div>

        <div class="card-body">
            <a href="{{ route('dekan.jadwal-rtm.index') }}" class="btn btn-outline-light mb-3">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>

            <table class="table table-borderless text-white">
                <tr>
                    <td class="fw-bold" width="15%">Agenda</td>
                    <td width="5%">:</td>
                    <td>{{ $rtmRtl->rtm_jadwal->agenda }}</td>
                </tr>
                <tr>
                    <td class="fw-bold" width="10%">Pimpinan Rapat</td>
                    <td width="5%">:</td>
                    <td>{{ $rtmRtl->rtm_jadwal->pimpinan }}</td>
                </tr>
                <tr>
                    <td class="fw-bold">Tanggal</td>
                    <td>:</td>
                    <td>
                        {{ Carbon::parse($rtmRtl->rtm_jadwal->tanggal)->translatedFormat('l, j F Y') }}
                    </td>
                </tr>
                <tr>
                    <td class="fw-bold">Waktu</td>
                    <td>:</td>
                    <td>
                        {{ Carbon::parse($rtmRtl->rtm_jadwal->jam_mulai)->translatedFormat('H:i') }} - {{ Carbon::parse($rtmRtl->rtm_jadwal->jam_selesai)->translatedFormat('H:i') }}
                    </td>
                </tr>
                <tr>
                    <td class="fw-bold" width="10%">Tempat</td>
                    <td width="5%">:</td>
                    <td>{{ $rtmRtl->rtm_jadwal->tempat }}</td>
                </tr>
                <tr>
                    <td class="fw-bold" width="10%">Jumlah Kehadiran</td>
                    <td width="5%">:</td>
                    <td>{{ $rtmRtl->rtm_jadwal->peserta }} Peserta Rapat</td>
                </tr>
                <tr>
                    <td class="fw-bold" width="10%">Hasil AIMA</td>
                    <td width="5%">:</td>
                    <td>{{ $rtmRtl->jadwal_audit->jadwal }}</td>
                </tr>
                <tr>
                    <td class="fw-bold">Periode AIMA</td>
                    <td>:</td>
                    <td>
                        {{ Carbon::parse($rtmRtl->jadwal_audit->tgl_mulai)->translatedFormat('j F Y') }} - 
                        {{ Carbon::parse($rtmRtl->jadwal_audit->tgl_selesai)->translatedFormat('j F Y') }}
                    </td>
                </tr>
            </table>
        </div>
    </div>

    <div class="card shadow border-0 mt-3">
        <div class="card-header bg-white border-bottom">
            <h5 class="mb-0 text-dark"><i class="fas fa-file-alt me-2"></i> Narasi Laporan RTM</h5>
        </div>
        
        <div class="card-body">
            <p class="text-muted">Silakan tambahkan kata pengantar laporan RTM untuk menjelaskan konteks dan tujuan laporan ini, jika diperlukan.</p>
            <div class="d-flex justify-content">
                <a href="{{ route('dekan.rtm-catatan.form', [$rtmRtl->rtm_jadwal->id]) }}"
                    class="btn btn-primary"><i class="fas fa-edit me-1"></i> Isi </a>
            </div>
        </div>
    </div>
    
    <div class="card shadow border-0 mt-3">
        <div class="card-header bg-white border-bottom">
            <h5 class="mb-0 text-dark"><i class="fas fa-file-alt me-2"></i> Hasil {{ $rtmRtl->jadwal_audit->jadwal }} Berdasarkan Kriteria</h5>
        </div>

        <div class="card-body">
            @php
                $kriteriaList = $rtmRtl->status_rtm_rtl->pluck('kriteria')->unique();
            @endphp

            <div class="row g-3 justify-content-center">
                @foreach ($allKriteria as $kriteria)
                    @php
                        $status = $rtmRtl->status_rtm_rtl->where('kriteria_id', $kriteria->id)->first();
                    @endphp
                    
                    <div class="col-12 col-md-6 col-lg-4">
                        <div class="card text-center transition-modern shadow border-0 w-100" style="border-radius: 16px;">
                            <div class="card-body py-4">
                                <i class="{{ $status && $status->status === 'completed' ? 'fas fa-check-circle text-success' : 'fas fa-edit text-warning' }} fa-3x mb-3"></i>
                                <h6 class="fw-bold text-dark">{{ $kriteria->nama }}</h6>
                                
                                @if (!$status)
                                    <form action="{{ route('dekan.rtm-rtl.isi', ['rtmRtl' => $rtmRtl->id, 'kriteria' => $kriteria->id]) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn btn-danger w-100 py-2 transition-modern">Mulai Isi</button>
                                    </form>
                                @else
                                    @if ($status->status === 'completed')
                                        <a href="{{ route('dekan.rtm-rtl.form', ['rtmRtl' => $rtmRtl->id, 'kriteria' => $kriteria->id]) }}"
                                            class="btn btn-primary w-100 py-2 transition-modern">Sudah Isi</a>
                                    @else
                                        <a href="{{ route('dekan.rtm-rtl.form', ['rtmRtl' => $rtmRtl->id, 'kriteria' => $kriteria->id]) }}"
                                            class="btn btn-primary w-100 py-2 transition-modern">Isi</a>
                                    @endif
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            {{-- <div class="row g-3 justify-content-center">
                @php
                    $statusProdi = $rtmRtl->status_rtm_rtl_prodi->first();
                @endphp
                <div class="col-12 col-md-6 col-lg-4">
                    <div class="card text-center transition-modern shadow border-0 w-100" style="border-radius: 16px;">
                        <div class="card-body py-4">
                            <i class="{{ $statusProdi && $statusProdi->status === 'completed' ? 'fas fa-check-circle text-success' : 'fas fa-edit text-warning' }} fa-3x mb-3"></i>
                            <h6 class="fw-bold text-dark">Rekap Temuan Prodi</h6>
                            @if (!$statusProdi)
                                <form action="{{ route('dekan.rtm-rtl-prodi.isi', $rtmRtl->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-danger w-100 py-2 transition-modern">Mulai Isi</button>
                                </form>
                            @else
                                @if ($statusProdi->status === 'completed')
                                    <a href="{{ route('dekan.rtm-rtl-prodi.form', ['rtmRtl' => $rtmRtl->id]) }}"
                                        class="btn btn-primary w-100 py-2 transition-modern"> Sudah Isi </a>
                                @else
                                    <a href="{{ route('dekan.rtm-rtl-prodi.form', ['rtmRtl' => $rtmRtl->id]) }}"
                                        class="btn btn-primary w-100 py-2 transition-modern"> Isi </a>
                                @endif
                            @endif
                        </div>
                    </div>
                </div>
            </div> --}}
        </div>
    </div>

    <div class="row align-items-stretch mb-4">
        <div class="col-sm-6 mb-3 mb-sm-0 d-flex">
            <div class="card w-100 h-100">
                <div class="card-header border-bottom">
                    <h5 class="card-title title-size text-dark">Download dan Approve Rencana Tindak Lanjut Hasil Audit</h5>
                </div>
                <div class="card-body">
                    <p class="card-text">Silahkan download dan approve, setelah mengisi rencana Hasil Audit.</p>
                    <div class="mb-2">
                        @if (!$rtmRtl->user->first() || $rtmRtl->user->first()->pivot->approve == 0)
                            <form action="{{ route('dekan.rtm-rtl.approve', ['rtmRtl' => $rtmRtl->id, 'user' => $user]) }}" method="post">
                                @csrf
                            <button type="submit" class="btn btn-outline-success w-100">
                                    <i class="fas fa-thumbs-up"></i> Approve Laporan RTM
                                </button>
                            </form>
                        @else
                            <button disabled class="btn btn-secondary w-100">
                                <i class="fas fa-check-circle"></i>Laporan RTM Approved</button>
                        @endif
                    </div>
                    <div>
                        <form action="{{ route('download.rtm.fakultas', $rtmRtl->rtm_jadwal->id) }}" 
                            class="d-inline">
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="fas fa-download"></i> Download Laporan RTM
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 mb-3 mb-sm-0 d-flex">
            <div class="card shadow border-0 w-100 h-100" style="background: linear-gradient(135deg, #6694ea, #614ba2); color: white;">
                <div class="card-header border-bottom">
                    <h5 class="card-title title-size text-dark"><i class="fas fa-info-circle me-2"></i> Informasi Temuan Audit</h5>
                </div>
                <div class="card-body">
                    <p class="card-text">Temuan ini merupakan hasil dari audit yang sudah dilaksanakan. Pengelompokkan temuan hasil audit sesuai dengan jawaban auditor yang sudah dikelompokkan berdasarkan kriteria. </p>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('style')
<style>
    .transition-modern {
        transition: transform 0.3s ease-in-out, box-shadow 0.3s ease-in-out;
    }
    .transition-modern:hover {
        transform: scale(1.05);
        box-shadow: 0px 10px 20px rgba(0, 0, 0, 0.2);
    }

    .btn {
        border-radius: 10px;
        font-weight: 600;
    }

    .btn-danger {
        background: linear-gradient(135deg, #e74c3c, #c0392b);
        border: none;
    }
    .btn-danger:hover {
        background: linear-gradient(135deg, #c0392b, #e74c3c);
    }
    
    .btn-primary {
        background: linear-gradient(135deg, #2e80cc, #276dae);
        border: none;
    }
    .btn-primary:hover {
        background: linear-gradient(135deg, #276dae, #2e80cc);
    }
    .card.text-center.transition-modern:hover {
    background-color: #f5f5f5;
    }
    .card-title.title-size {
    font-size: 1.3rem;
    font-weight: bold;
    }
    .btn-outline-success, .btn-outline-secondary {
        border-radius: 8px;
        font-weight: 600;
    }

    .card .card-title i {
        color: white;
    }
    .paper-style {
        background-color: #fff;
        border-radius: 12px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        padding: 24px;
    }
    
</style>
@endsection


