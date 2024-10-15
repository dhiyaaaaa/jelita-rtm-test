@extends('components.layout.auditee_layout')

@section('content')
    <div class="row">
        <div class="col-md-9 ">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title text-bold">{{ $title }}</h3>
                </div>
                <div class="card-body">
                    @php
                        $no = ($daftarTilik->currentPage() - 1) * $daftarTilik->perPage() + 1;
                    @endphp
                    {{-- Page --}}
                    @foreach ($daftarTilik as $item)
                        @php
                            // Jawaban Auditee
                            $jawaban = $jawabanPtk->where('form_id', $item->form->id)->first();

                            // Jawaban
                            $rencanaCollection = $jawabanPtkRencana->where('form_id', $item->form->id);
                            $deskripsiCollection = $jawabanPtkDeskripsi->where('form_id', $item->form->id);
                            $sessionRencana = $rencanaCollection->all();
                            $sessionTarget = $jawaban ? $jawaban->target : '';
                            $sessionPic = $jawaban ? $jawaban->pic : '';
                        @endphp
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title w-100">
                                    <span class="badge badge-success mb-3">
                                        {{ $item->form->instrumen->kode }}
                                    </span>
                                    <p>{{ $no++ }}. {{ $item->form->instrumen->pernyataan }}
                                        <span class="text-danger">&#42;</span>
                                    </p>
                                </h4>
                            </div>
                            <div class="card-body">

                                <div class="form-group">
                                    <label for="">Deskripsi / Uraian Temuan</label>

                                    @if ($deskripsiCollection->isNotEmpty())
                                        @foreach ($deskripsiCollection as $deskripsi)
                                            <textarea class="form-control mt-3" cols="30" rows="3" disabled>{{ $deskripsi->deskripsi }}</textarea>
                                        @endforeach
                                    @else
                                        <textarea class="form-control" cols="30" rows="3" disabled></textarea>
                                    @endif

                                </div>
                                <div class="form-group">
                                    <label for="">Analisis</label>
                                    <textarea class="form-control" cols="30" rows="5" disabled>{{ $jawaban ? $jawaban->analisis : null }}</textarea>
                                </div>

                                <div class="form-group">
                                    <label for="">Penyebab</label>
                                    <textarea class="form-control" cols="30" rows="5" disabled>{{ $jawaban ? $jawaban->akibat : null }}</textarea>
                                </div>

                                <div class="form-group">
                                    <label for="">Rencana Tindakan Perbaikan</label>
                                    <div id="rencana-inputs-{{ $item->form->id }}">
                                        @if (!empty($sessionRencana))
                                            @foreach ($sessionRencana as $index => $rencana)
                                                <div class="input-group mb-2">
                                                    <textarea class="form-control" disabled cols="30" rows="3">{{ $rencana->rencana ?? $rencana }}</textarea>
                                                </div>
                                            @endforeach
                                        @else
                                            <div class="input-group mb-2">
                                                <textarea class="form-control" cols="30" rows="3" disabled></textarea>
                                            </div>
                                        @endif
                                    </div>

                                </div>

                                <div class="form-group">
                                    <label for="">Target Penyelesaian</label>
                                    <textarea name="target_{{ $item->form->id }}" cols="10" rows="5" class="form-control" disabled>{{ $sessionTarget }}</textarea>

                                </div>

                                <div class="form-group">
                                    <label for="">PIC</label>
                                    <textarea name="pic_{{ $item->form->id }}" cols="10" rows="5" class="form-control" disabled>{{ $sessionPic }}</textarea>
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
                <div class="card card-primary">
                    <div class="card-header">
                        Halaman
                    </div>
                    <div class="card-body">
                        <div class="pagination-wrapper">
                            {{ $daftarTilik->links('pagination::bootstrap-4') }}
                        </div>

                        @if (($daftarTilik->currentPage() == $daftarTilik->lastPage()) == 1)
                            <div>
                                @if (isset($status) && $status->status !== 'completed' && !$expired)
                                    <input type="hidden" name="final" value="final">
                                    <button type="submit" id="button-submit" class="btn btn-primary">Submit</button>
                                    <button id="button-submit-loading" class="btn btn-primary d-none" type="button"
                                        disabled>
                                        <span class="spinner-border spinner-border-sm" role="status"
                                            aria-hidden="true"></span>
                                        Loading...
                                    </button>
                                    <div class="mt-3">
                                        <button id="save-button" class="btn btn-warning">Save</button>

                                        <button id="save-button-loading" class="btn btn-warning d-none" type="button"
                                            disabled>
                                            <span class="spinner-border spinner-border-sm" role="status"
                                                aria-hidden="true"></span>
                                            Loading...
                                        </button>
                                    </div>
                                @else
                                    @if ($daftarTilik->lastPage() !== 1)
                                        <a id="previous" href="{{ $daftarTilik->previousPageUrl() }}"
                                            class="btn btn-primary">Previous</a>
                                    @endif
                                @endif
                            </div>
                        @else
                            <div class="">
                                @if ($daftarTilik->currentPage() > 1)
                                    <a id="previous" href="{{ $daftarTilik->previousPageUrl() }}"
                                        class="btn btn-primary">Previous</a>
                                @else
                                    <div></div>
                                @endif
                                <a id="next" href="{{ $daftarTilik->nextPageUrl() }}" class="btn btn-primary">Next</a>
                                <div class="mt-3">
                                    @if (isset($status) && $status->status !== 'completed' && !$expired)
                                        <button id="save-button" class="btn btn-warning">Save</button>

                                        <button id="save-button-loading" class="btn btn-warning d-none" type="button"
                                            disabled>
                                            <span class="spinner-border spinner-border-sm" role="status"
                                                aria-hidden="true"></span>
                                            Loading...
                                        </button>
                                    @endif
                                </div>
                            </div>
                        @endif

                        @if (isset($status) && $status->status === 'completed' && !$expired)
                            <div>
                                <button type="button" class="btn btn-warning" id="edit-button">Ubah</button>
                                <button id="button-edit-loading" class="btn btn-warning d-none" type="button" disabled>
                                    <span class="spinner-border spinner-border-sm" role="status"
                                        aria-hidden="true"></span>
                                    Loading...
                                </button>
                            </div>
                        @endif

                        {{-- Kembali ke halaman jadwal --}}
                        @if ($type === 'prodi')
                            <a href="{{ route('hasil_audit.show', ['jadwalAudit' => $jadwal->id, 'type' => 'ps']) }}"
                                class="btn btn-outline-secondary mt-3">Kembali</a>
                        @elseif ($type === 'fakultas' || $type === 'universitas')
                            <a href="{{ route('hasil_audit.show', ['jadwalAudit' => $jadwal->id, 'type' => 'upps']) }}"
                                class="btn btn-outline-secondary mt-3">Kembali</a>
                        @endif
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
