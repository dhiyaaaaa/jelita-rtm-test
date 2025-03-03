@extends('components.layout.auditee_layout')

@section('content')
    <div class="row">
        <div class="col-md-9">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h3 class="card-title font-weight-bold">{{ $title }}</h3>
                </div>
                <div class="card-body">
                    @php
                        $no = ($paginatedTemuanFakultas->currentPage() - 1) * $paginatedTemuanFakultas->perPage() + 1;
                    @endphp
                    @foreach ($paginatedTemuanFakultas as $item)
                        <div class="row mb-4">
                            <div class="col-12">
                                <div class="card mb-3">
                                    <div class="card-header bg-light">
                                        <h4 class="card-title w-100">
                                            <div class="d-flex flex-wrap gap-2 mb-2">
                                                @if ($item->form->instrumen->jabatan->isNotEmpty())
                                                    @foreach ($item->form->instrumen->jabatan as $jabatanInstrumen)
                                                        <span class="badge badge-primary mr-1">{{ $jabatanInstrumen->nama }}</span>
                                                    @endforeach
                                                @else
                                                    <span class="badge badge-secondary">-</span>
                                                @endif
                                            </div>
                                            <div class="mb-2">
                                                @if ($item->kriteria->nama === 'Belum Memenuhi')
                                                    <span class="badge badge-danger">{{ $item->kriteria->nama }}</span>
                                                @elseif ($item->kriteria->nama === 'Memenuhi')
                                                    <span class="badge badge-warning">{{ $item->kriteria->nama }}</span>
                                                @elseif ($item->kriteria->nama === 'Melampaui')
                                                    <span class="badge badge-success">{{ $item->kriteria->nama }}</span>
                                                @else
                                                    <span class="badge badge-secondary">{{ $item->kriteria->nama }}</span>
                                                @endif
                                            </div>
                                            <p class="mb-1">{{ $no++ }}. {{ $item->form->instrumen->kode }}</p>
                                            <p class="mb-0">{{ $item->form->instrumen->pernyataan }}</p>
                                        </h4>
                                    </div>
                                    <div class="card-body">
                                        {{-- Catatan Auditor --}}
                                        <div class="form-group mb-4">
                                            <label class="font-weight-bold">Catatan Auditor</label>
                                            <div class="mt-2">
                                                @if ($item->catatan)
                                                    <p class="text-muted">{{ $item->catatan }}</p>
                                                @else
                                                    <p class="text-muted">Tidak ada catatan</p>
                                                @endif
                                            </div>
                                        </div>

                                        {{-- Tindakan --}}
                                        <div class="form-group">
                                            <label class="font-weight-bold">
                                                @if ($item->kriteria->nama === 'Belum Memenuhi')
                                                    Rencana Perbaikan
                                                @else
                                                    Rencana Peningkatan
                                                @endif
                                            </label>
                                            <div id="tindakan-inputs-{{ $item->form->id }}" class="mb-3">
                                                @if (isset($jawabanTindakLanjut[$item->form->id]))
                                                    @foreach ($jawabanTindakLanjut[$item->form->id] as $tindakan)
                                                        <div class="input-group mb-2">
                                                            <textarea class="form-control small-textarea" disabled>{{ $tindakan->tindakan }}</textarea>
                                                            <textarea class="form-control small-textarea" disabled>{{ $tindakan->pic }}</textarea>
                                                            <textarea class="form-control small-textarea" disabled>{{ $tindakan->waktu }}</textarea>
                                                        </div>
                                                    @endforeach
                                                @else
                                                    <div class="input-group mb-2">
                                                        <textarea class="form-control small-textarea" disabled></textarea>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- Pagination --}}
        <div class="col-md-3">
            <div class="pagination-container">
                <div class="card card-primary shadow-sm">
                    <div class="card-header bg-primary text-white">
                        Halaman
                    </div>
                    <div class="card-body">
                        <div class="pagination-wrapper">
                            {{ $paginatedTemuanFakultas->links('pagination::bootstrap-4') }}
                        </div>
                        @if ($fakultas)
                        <div class="d-flex justify-content-start mb-2">
                            <a href="{{ route('hasil_rtm_rtl_prodi.show', $rtmRtl->id) }}" class="btn btn-primary mr-2">Temuan Prodi</a>
                        </div>
                        @endif
                        <div class="d-flex justify-content-start mb-2">
                            <a href="{{ route('hasil_rtm_fakultas.show', $jadwalAudit->id) }}" class="btn btn-outline-secondary mr-2">Kembali</a>
                        </div>
                        
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('style')
    <style>
        .pagination-container {
            position: sticky;
            top: 0;
            z-index: 1000;
            background-color: #f4f6f9;
            padding: 12px 0;
            transition: padding-top 0.3s;
        }

        .pagination-wrapper {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
        }

        .pagination-wrapper .pagination {
            display: flex;
            flex-wrap: wrap;
        }

        .pagination-wrapper .page-item {
            flex: 1 0 1;
        }

        /* CSS untuk textarea kecil */
        .small-textarea {
            resize: none; /* Nonaktifkan resize */
            height: 38px; /* Tinggi textarea */
            min-height: 38px; /* Tinggi minimum */
            max-height: 100px; /* Tinggi maksimum */
            overflow-y: auto; /* Tambahkan scroll jika konten melebihi tinggi */
            font-size: 14px; /* Ukuran font */
            padding: 6px 12px; /* Padding untuk tampilan yang lebih baik */
        }
    </style>
@endsection

@section('script')
    @if (session('error_message'))
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: "{{ session('error_message') }}"
            });
        </script>
    @endif

    {{-- Pagination --}}
    <script>
        $(document).ready(function() {
            var $paginationContainer = $('.pagination-container');
            var navbarHeight = 56;

            $(window).on('scroll', function() {
                if ($(this).scrollTop() > 0) {
                    $paginationContainer.css('padding-top', (navbarHeight + 12) + 'px');
                } else {
                    $paginationContainer.css('padding-top', '12px');
                }
            });

            $(window).trigger('scroll');
        });
    </script>
@endsection