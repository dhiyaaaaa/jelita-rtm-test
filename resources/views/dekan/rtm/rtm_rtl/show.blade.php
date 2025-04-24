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
                    <td class="fw-bold" width="30%">Rapat Tinjauan Manajemen</td>
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
        <div class="card-header border-0">
            <h3 class="card-title title-size text-dark">Hasil {{ $rtmRtl->jadwal_audit->jadwal }} Berdasarkan Kriteria</h3>
        </div>

        <div class="card-body">
            @php
                $statusBelumMemenuhi = $rtmRtl->status_rtm_rtl->where('kriteria.nama', 'Belum Memenuhi')->first();
                $statusMemenuhi = $rtmRtl->status_rtm_rtl->where('kriteria.nama', 'Memenuhi')->first();
                $statusMelampaui = $rtmRtl->status_rtm_rtl->where('kriteria.nama', 'Melampaui')->first();
                $statusProdi = $rtmRtl->status_rtm_rtl_prodi->first();
            @endphp

            <div class="row g-3 justify-content-center">
                @foreach (['Belum Memenuhi' => $statusBelumMemenuhi, 'Memenuhi' => $statusMemenuhi, 'Melampaui' => $statusMelampaui] as $kriteria => $status)
                    <div class="col-12 col-md-6 col-lg-4">
                        <div class="card text-center transition-modern shadow border-0 w-100" style="border-radius: 16px;">
                            <div class="card-body py-4">
                                <i class="{{ $status && $status->status === 'completed' ? 'fas fa-check-circle text-success' : 'fas fa-edit text-warning' }} fa-3x mb-3"></i>
                                <h6 class="fw-bold text-dark">{{ $kriteria }}</h6>
                                @if (!$status)
                                    <form action="{{ route('dekan.rtm-rtl.isi', ['rtmRtl' => $rtmRtl->id, 'kriteria' => $kriteria]) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn btn-danger w-100 py-2 transition-modern">Mulai Isi</button>
                                    </form>
                                </form>
                                @else
                                    @if ($status->status === 'completed')
                                        <a href="{{ route('dekan.rtm-rtl.form', ['rtmRtl' => $rtmRtl->id, 'kriteria' => $kriteria]) }}"
                                            class="btn btn-primary w-100 py-2 transition-modern"> Sudah Isi </a>
                                    @else
                                        <a href="{{ route('dekan.rtm-rtl.form', ['rtmRtl' => $rtmRtl->id, 'kriteria' => $kriteria]) }}"
                                            class="btn btn-primary w-100 py-2 transition-modern"> Isi </a>
                                    @endif
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="row g-3 justify-content-center">
                <div class="col-12 col-md-6 col-lg-4">
                    <div class="card text-center transition-modern shadow border-0 w-100" style="border-radius: 16px;">
                        <div class="card-body py-4">
                            <i class="{{ $statusProdi && $statusProdi->statusProdi === 'completed' ? 'fas fa-check-circle text-success' : 'fas fa-edit text-warning' }} fa-3x mb-3"></i>
                            <h6 class="fw-bold text-dark">Rekap Temuan Prodi</h6>
                            @if (!$statusProdi)
                                <form action="{{ route('dekan.rtm-rtl-prodi.isi', $rtmRtl->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn-danger w-100 py-2 transition-modern">Mulai Isi</button>
                                </form>
                            @else
                                @if ($statusProdi->statusProdi === 'completed')
                                    <a href="{{ route('dekan.rtm-rtl.form_prodi', ['rtmRtl' => $rtmRtl->id]) }}"
                                        class="btn btn-primary w-100 py-2 transition-modern"> Sudah Isi </a>
                                @else
                                    <a href="{{ route('dekan.rtm-rtl.form_prodi', ['rtmRtl' => $rtmRtl->id]) }}"
                                        class="btn btn-primary w-100 py-2 transition-modern"> Isi </a>
                                @endif
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row align-items-stretch mb-4">
        <div class="col-sm-6 mb-3 mb-sm-0 d-flex">
            <div class="card w-100 h-100">
                <div class="card-body">
                    <h5 class="card-title title-size text-dark">Download dan Approve Rencana Tindak Lanjut Hasil Audit</h5>
                    <p class="card-text">Silahkan download dan approve, setelah mengisi rencana Hasil Audit.</p>
                    @foreach(auth()->user()->jabatan as $jabatan)
                        @if (in_array($jabatan->type, ['fakultas', 'universitas']))

                            @php
                                $approve = $approveRtmRtl[$rtmRtl->id][$jabatan->id] ?? null;
                            @endphp

                            @if ($approve === false || $approve === null)
                                <form action="/" method="POST">
                                    @csrf
                                    <input type="hidden" name="jabatan_id" value="{{ $jabatan->id }}">
                                    <button type="submit" class="btn btn-outline-success">Approve</button>
                                </form>
                            @elseif ($approve === true)
                                <button disabled="disabled" class="btn btn-secondary">Approved</button>
                            @endif
                        @endif
                    @endforeach
                    <form action="/" 
                        method="post"class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-download"></i> Download Hasil Tindak Lanjut PTK
                        </button>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-sm-6 mb-3 mb-sm-0 d-flex">
            <div class="card shadow border-0 w-100 h-100" style="background: linear-gradient(135deg, #6694ea, #614ba2); color: white;">
                <div class="card-body">
                    <h5 class="card-title title-size text-dark"><i class="fas fa-info-circle me-2"></i> Informasi Temuan Audit</h5>
                    <p class="card-text">Temuan ini merupakan hasil dari audit yang sudah dilaksanakan. Pengelompokkan temuan sesuai kriteria ini sesuai dengan jawaban auditor. </p>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('style')
<style>
    /* Efek hover lebih menarik */
    .transition-modern {
        transition: transform 0.3s ease-in-out, box-shadow 0.3s ease-in-out;
    }
    .transition-modern:hover {
        transform: scale(1.05);
        box-shadow: 0px 10px 20px rgba(0, 0, 0, 0.2);
    }

    /* Gaya untuk tombol */
    .btn {
        border-radius: 10px;
        font-weight: 600;
    }

    /* Animasi efek warna tombol */
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


