@php
    use Carbon\Carbon;
@endphp

@extends('components.layout.main_layout')

@section('content')
    <div class="card shadow border-0" style="background: linear-gradient(135deg, #6694ea, #614ba2); color: white;">
        <div class="card-header border-0">
            <h3 class="card-title title-size text-white">Tindak Lanjut Permintaan Tindakan Koreksi</h3>
        </div>

        <div class="card-body">
            <a href="{{ route('dekan.rtl.index') }}" class="btn btn-outline-light mb-3">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>

            <table class="table table-borderless text-white">
                <tr>
                    <td class="fw-bold" width="10%">Jadwal</td>
                    <td width="5%">:</td>
                    <td>{{ $rtl->jadwal_audit->jadwal }}</td>
                </tr>
                <tr>
                    <td class="fw-bold">Periode</td>
                    <td>:</td>
                    <td>
                        {{ Carbon::parse($rtl->jadwal_audit->tgl_mulai)->translatedFormat('j F Y') }} - 
                        {{ Carbon::parse($rtl->jadwal_audit->tgl_selesai)->translatedFormat('j F Y') }}
                    </td>
                </tr>
            </table>
        </div>
    </div>

    <div class="card shadow border-0 mt-3">
        <div class="card-header bg-white border-bottom">
            <h5 class="mb-0 text-dark"><i class="fas fa-file-alt me-2"></i>Tindak Lanjut Temuan Hasil Hasil {{ $rtl->jadwal_audit->jadwal }} Sesuai Kriteria</h3>
        </div>

        <div class="card-body">
            @php
                $kriteriaList = $rtl->status_rtl->pluck('kriteria')->unique();
            @endphp

            <div class="row g-3 justify-content-center">
                @foreach ($allKriteria as $kriteria)
                    @php
                        $status = $rtl->status_rtl->where('kriteria_id', $kriteria->id)->first();
                    @endphp
                    <div class="col-12 col-md-6 col-lg-4">
                        <div class="card text-center transition-modern shadow border-0 w-100" style="border-radius: 16px;">
                            <div class="card-body py-4">
                                <i class="{{ $status && $status->status === 'completed' ? 'fas fa-check-circle text-success' : 'fas fa-edit text-warning' }} fa-3x mb-3"></i>
                                <h6 class="fw-bold text-dark">{{ $kriteria->nama }}</h6>
                                @if (!$status)
                                    <form action="{{ route('dekan.rtl.isi', ['rtl' => $rtl->id, 'kriteria' => $kriteria->id]) }}"
                                        method="post" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-danger w-100 py-2 transition-modern">
                                            Mulai Isi
                                        </button>
                                    </form>
                                @else
                                    @if ($status->status === 'completed')
                                        <a href="{{ route('dekan.rtl.form', ['rtl' => $rtl->id, 'kriteria' => $kriteria->id]) }}"
                                            class="btn btn-primary w-100 py-2 transition-modern"> Sudah Isi </a>
                                    @else
                                        <a href="{{ route('dekan.rtl.form', ['rtl' => $rtl->id, 'kriteria' => $kriteria->id]) }}"
                                            class="btn btn-primary w-100 py-2 transition-modern"> Isi </a>
                                    @endif
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <div class="row align-items-stretch mb-4">
        <div class="col-sm-6 mb-3 mb-sm-0 d-flex">
            <div class="card w-100 h-100">
                <div class="card-body">
                    <h5 class="card-title title-size text-dark">Download dan Approve Tindak Lanjut PTK</h5>
                    <p class="card-text">Silahkan download dan approve, setelah mengisi tindak lanjut Permintaan Tindakan Koreksi (PTK).</p>
                    @if ($rtl->auditee->isNotEmpty())
                    @php $approval = optional($rtl->auditee->first()->pivot)->approve; @endphp
                    <form action="{{ route('auditee.rtl.approve', ['rtl' => $rtl->id, 'auditee' => $auditee->id]) }}" method="post" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-outline-{{ $approval ? 'secondary' : 'success' }} btn-sm" {{ $approval ? 'disabled' : '' }}>
                            <i class="fas {{ $approval ? 'fa-check-circle' : 'fa-thumbs-up' }}"></i> {{ $approval ? 'Approved' : 'Approve' }}
                        </button>
                    </form>
                    @endif
                    
                    <form action="{{ route('download.rtl', $rtl->id) }}" method="post"
                        class="d-inline">
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

    <div class="card shadow border-0 paper-style">
        <div class="card-header bg-light">
            <h3 class="card-title title-size text-dark">List Auditan dan Auditor {{ $rtl->jadwal_audit->jadwal }}</h3>
        </div>
    
        <div class="card-body">
            <table id="auditan-table" class="table table-striped table-bordered">
                <thead class="table-light">
                    <tr class="text-center">
                        <th>No</th>
                        <th>Fakultas / Prodi / Unit</th>
                        <th>Auditan</th>
                        <th>Auditor</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($items as $index => $item)
                        <tr>
                            <td class="text-center">{{ $index + 1 }}</td>
                            <td>{{ $item['nama'] }}</td>
                            <td>
                                @forelse ($item['auditee'] as $auditee)
                                    {{ $auditee->user->name }}<br>
                                @empty
                                    <span class="text-muted">Tidak Ada Auditan</span>
                                @endforelse
                            </td>
                            <td>
                                @forelse ($item['auditor'] as $auditor)
                                    {{ $auditor->user->name }}<br>
                                @empty
                                    <span class="text-muted">Tidak Ada Auditor</span>
                                @endforelse
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>    
            
@endsection

@section('style')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
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

@section ('script')
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    <script>
        $(document).ready(function () {
            $('#auditan-table').DataTable({
                responsive: true,
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/id.json' 
                }
            });
        });
    </script>
@endsection

