@extends('components.layout.auditee_layout')

@section('content')
    <div class="row">
        <div class="col-md-9">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h3 class="card-title text-bold">{{ $title }}</h3>
                </div>
                <div class="card-body">
                    @php
                        $no = ($paginatedTemuanProdi->currentPage() - 1) * $paginatedTemuanProdi->perPage() + 1;
                    @endphp

                    {{-- Loop melalui setiap temuan --}}
                    @foreach ($paginatedTemuanProdi->groupBy('form.id') as $formId => $items)
                        @php
                            // Ambil item pertama dalam grup untuk referensi form_id
                            $firstItem = $items->first();
                            $kriteriaId = $firstItem->kriteria->id ?? null;

                            // Jawaban Tindak Lanjut
                            $tindakanData = collect($jawabanTindakLanjut[$formId] ?? []);
                        @endphp
                        <div class="card mb-4 shadow-sm border-light">
                            <div class="card-header bg-light">
                                <h4 class="card-title w-100">{{ $items->first()->form->instrumen->kode }}</h4>
                                <p><strong>Pernyataan:</strong> {{ $items->first()->form->instrumen->pernyataan }}</p>
                            </div>
                            <div class="card-body">
                                @foreach ($items->groupBy('kriteria.nama') as $kriteriaNama => $prodiGroup)
                                    @php
                                        $kriteriaId = $prodiGroup->first()->kriteria->id ?? null;
                                        $tindakanDataKriteria = $tindakanData->where('kriteria_id', $kriteriaId) ?? [];
                                    @endphp

                                    <div class="mb-4 p-3 border rounded">
                                        <h6 class="mb-3">
                                            @if ($kriteriaNama === 'Belum Memenuhi')
                                                <span class="badge badge-danger">{{ $kriteriaNama }}</span>
                                            @elseif ($kriteriaNama === 'Memenuhi')
                                                <span class="badge badge-warning">{{ $kriteriaNama }}</span>
                                            @elseif ($kriteriaNama === 'Melampaui')
                                                <span class="badge badge-success">{{ $kriteriaNama }}</span>
                                            @else
                                                <span class="badge badge-secondary">{{ $kriteriaNama }}</span>
                                            @endif
                                        </h6>
                                        @foreach ($prodiGroup as $item)
                                            <p><strong> {{ $item->prodi->jenjang->nama }} {{ $item->prodi->nama }}:</strong>
                                                {{ $item->catatan ?? '(Tidak ada catatan auditor)' }}
                                            </p>
                                        @endforeach
                                        <div id="tindakan-inputs-{{ $formId }}-{{ $kriteriaId }}" class="mt-3">
                                            @if ($tindakanDataKriteria->isNotEmpty())
                                                @foreach ($tindakanDataKriteria as $index => $tindakan)
                                                <div class="row g-2 mb-3">
                                                    <input type="text"
                                                        class="form-control"
                                                        value="{{ $tindakan['tindakan'] }}"
                                                        disabled>
                                                    <input type="text"
                                                        class="form-control"
                                                        value="{{ $tindakan['pic'] }}"
                                                        disabled>
                                                    <input type="text"
                                                        class="form-control"
                                                        value="{{ $tindakan['waktu'] }}"
                                                        disabled>
                                                </div>
                                                @endforeach
                                            @else
                                                <p class="text-muted">Tidak ada rencana tindakan.</p>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- Pagination --}}
        <div class="col-md-3">
            <div class="pagination-container">
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white">
                        Halaman
                    </div>
                    <div class="card-body">
                        <div class="pagination-wrapper text-center">
                            {{ $paginatedTemuanProdi->links('pagination::bootstrap-4') }}
                        </div>
                        <div class="text-center mt-3">
                            <a href="{{ route('hasil_rtm_fakultas.show', $rtmJadwal->id) }}" class="btn btn-outline-secondary">Kembali</a>
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
    </style>
@endsection

@section('script')
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