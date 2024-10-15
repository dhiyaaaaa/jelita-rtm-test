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
                        $no = ($paginatedForms->currentPage() - 1) * $paginatedForms->perPage() + 1;
                    @endphp
                    {{-- Page --}}
                    @foreach ($paginatedForms as $item)
                        @php
                            // Jawaban
                            $jawaban = $jawabanLaporan->where('form_id', $item->form->id)->first();
                            $sessionKelebihan = $jawaban ? $jawaban->kelebihan : '';
                            $sessionRuang = $jawaban ? $jawaban->ruang_peningkatan : '';
                        @endphp
                        <div class="card">
                            <div class="card-header">
                                {{-- Pertanyaan --}}
                                <h4 class="card-title w-100">
                                    <span class="badge badge-success mb-3">
                                        {{ $item->form->instrumen->kode }}</span>
                                    <p>{{ $no++ }}. {{ $item->form->instrumen->pernyataan }}
                                        <span class="text-danger">&#42;</span>
                                    </p>
                                </h4>
                            </div>
                            <div class="card-body">
                                {{-- Jawaban Auditee --}}
                                <div class="form-group">
                                    <div>
                                        <label for="">Jawaban Auditee</label>
                                        @foreach ($item->form->jawaban_auditee as $jawaban)
                                            <p>
                                                {{ $jawaban->jawaban }}
                                            </p>
                                        @endforeach
                                    </div>
                                    <div>
                                        <label for="">Link</label><br>
                                        @foreach ($item->form->link as $link)
                                            <a href="{{ $link->link }}" target="_blank">{{ $link->link }}</a><br>
                                        @endforeach
                                    </div>
                                </div>

                                {{-- Analisis --}}
                                <div class="form-group">
                                    <label for="">Kelebihan</label>
                                    <textarea name="kelebihan_{{ $item->form->id }}" cols="10" rows="5" class="form-control" disabled>{{ $sessionKelebihan }}</textarea>

                                </div>

                                {{-- Penyebab --}}
                                <div class="form-group">
                                    <label for="">Ruang Peningkatan</label>
                                    <textarea name="ruang_{{ $item->form->id }}" cols="10" rows="5" class="form-control" disabled>{{ $sessionRuang }}</textarea>
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
                            {{ $paginatedForms->links('pagination::bootstrap-4') }}
                        </div>

                        @if (($paginatedForms->currentPage() == $paginatedForms->lastPage()) == 1)
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
                                    @if ($paginatedForms->lastPage() !== 1)
                                        <a id="previous" href="{{ $paginatedForms->previousPageUrl() }}"
                                            class="btn btn-primary">Previous</a>
                                    @endif
                                @endif
                            </div>
                        @else
                            <div class="">
                                @if ($paginatedForms->currentPage() > 1)
                                    <a id="previous" href="{{ $paginatedForms->previousPageUrl() }}"
                                        class="btn btn-primary">Previous</a>
                                @else
                                    <div></div>
                                @endif
                                <a id="next" href="{{ $paginatedForms->nextPageUrl() }}"
                                    class="btn btn-primary">Next</a>
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
                                    <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                    Loading...
                                </button>
                            </div>
                        @endif

                        {{-- Kembali ke halaman auditee --}}
                        @if ($type === 'prodi')
                            <a href="{{ route('hasil_audit.show', ['jadwalAudit' => $jadwal, 'type' => 'ps']) }}"
                                class="btn btn-outline-secondary mt-3">Kembali</a>
                        @elseif ($type === 'fakultas' || $type === 'universitas')
                            <a href="{{ route('hasil_audit.show', ['jadwalAudit' => $jadwal, 'type' => 'upps']) }}"
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
