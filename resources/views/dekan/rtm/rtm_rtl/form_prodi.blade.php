@extends('components.layout.auditee_layout')

@section('content')
<div class="container mt-4">
    <div class="row">
        <!-- Kolom Konten -->
        <div class="col-md-9">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0 text-center">Temuan Fakultas</h4>
                </div>
                <div class="card-body">
                    <form id="rtmForm" action="/" method="POST">
                        @csrf
                        @foreach ($temuanProdi as $instrumen => $dataKriteria)
                            <div class="card mb-4 shadow-sm">
                                <div class="card-header bg-info text-white">
                                    <h5 class="mb-0">{{ $instrumen }}</h5>
                                </div>
                                <div class="card-body">
                                    @foreach ($dataKriteria as $kriteria => $prodis)
                                        <div class="mb-3">
                                            <span class="badge bg-{{ $kriteria == 'belum-memenuhi' ? 'danger' : ($kriteria == 'memenuhi' ? 'success' : 'primary') }}">
                                                {{ ucfirst(str_replace('-', ' ', $kriteria)) }}
                                            </span>
                                            @foreach ($prodis as $prodi)
                                                <div class="mt-2">
                                                    <strong>{{ $prodi['prodi'] }}</strong>
                                                    @if ($kriteria == 'belum-memenuhi')
                                                        <p><i>{{ $prodi['deskripsi'] }}</i></p> <!-- Deskripsi dari PtkFormDeskripsi -->
                                                    @elseif ($kriteria == 'memenuhi')
                                                        <p><i>{{ $prodi['catatan'] }}</i></p> <!-- Catatan dari JawabanAuditor -->
                                                    @elseif ($kriteria == 'melampaui')
                                                        <p><i>{{ $prodi['kelebihan'] }} - {{ $prodi['ruang_peningkatan'] }}</i></p> <!-- Kelebihan & Ruang Peningkatan dari LaporanForm -->
                                                    @endif
                                                </div>
                                            @endforeach
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </form>
                </div>
            </div>
        </div>

        <!-- Kolom Pagination -->
        <div class="col-md-3">
            <div class="card shadow-sm sticky-top">
                <div class="card-header bg-secondary text-white text-center">Navigasi Halaman</div>
                <div class="card-body text-center">
            
            </div>
        </div>
    </div>
</div>
@endsection

 