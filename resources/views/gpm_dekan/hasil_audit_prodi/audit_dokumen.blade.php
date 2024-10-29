@extends('components.layout.auditee_layout')

@section('content')
    <div class="row">
        <div class="col-md-9">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title text-bold">{{ $title }}</h3>
                </div>
                <div class="card-body">
                    @php
                        $no = ($paginatedForms->currentPage() - 1) * $paginatedForms->perPage();
                    @endphp

                    {{-- Page --}}

                    @foreach ($paginatedForms as $item)
                        @php
                            $no++;
                            // Jawaban Auditee
                            $jawaban = $jawabanAuditee->where('form_id', $item['form']->id)->first();
                            $linkCollection = $links->where('form_id', $item['form']->id);

                            $sessionJawaban = $jawaban ? $jawaban->jawaban : '';
                            $sessionLinks = $linkCollection->all();

                            // Jawaban Auditor
                            $jawabanAuditor = $jawabanAuditors->where('form_id', $item['form']->id)->first();
                            // $sessionKriteria = $jawabanAuditor ? $jawabanAuditor->kriteria_id : '';
                            // $sessionCatatan = $jawabanAuditor ? $jawabanAuditor->catatan : '';
                            // $sessionDaftarTilik = $jawabanAuditor ? $jawabanAuditor->daftar_tilik : '';

                            // // Notifikasi
                            // $notif_terkirim = $notifikasi
                            //     ->where('form_id', $item['form']->id)
                            //     ->where('status', 'terkirim');
                            // $notif_diterima = $notifikasi
                            //     ->where('form_id', $item['form']->id)
                            //     ->where('status', 'diterima');

                            $isDisabled = true;
                        @endphp
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title w-100">
                                    <span class="badge badge-success mb-3">{{ $item['form']->instrumen->kode }}</span>
                                    <p>{{ $no }}. {{ $item['form']->instrumen->pernyataan }}
                                        <span class="text-danger">&#42;</span>
                                    </p>
                                </h4>
                            </div>
                            <div>
                                <div class="card-body">
                                    {{-- Jawaban  Auditee --}}
                                    <div class="form-group">
                                        <label for="">Jawaban Auditee</label>
                                        @if ($item['form']->instrumen->jenis_pertanyaan->nama == 'text')
                                            <textarea name="instrumen_{{ $item['form']->id }}" cols="10" rows="5" class="form-control" disabled>{{ $sessionJawaban }}</textarea>
                                        @elseif($item['form']->instrumen->jenis_pertanyaan->nama == 'number')
                                            <input type="number" class="form-control" value="{{ $sessionJawaban }}"
                                                disabled>
                                        @endif
                                    </div>

                                    {{-- Link Dokumen --}}
                                    <div class="form-group mt-3">
                                        <label for="">Link Dokumen Pendukung</label>
                                        <small class="form-text text-muted" style="margin: -10px 0 8px 0">Untuk akses
                                            Google Drive harap diubah menjadi <i>viewer</i></small>
                                        <div id="link-inputs-{{ $item['form']->id }}">
                                            @if (!empty($sessionLinks))
                                                @foreach ($sessionLinks as $index => $link)
                                                    <div class="input-group mb-2">
                                                        <input type="text" name="link_{{ $item['form']->id }}[]"
                                                            class="form-control" value="{{ $link->link ?? $link }}"
                                                            data-link-id="{{ $link->id ?? '' }}"disabled>
                                                    </div>
                                                @endforeach
                                            @else
                                                <div class="input-group mb-2">
                                                    <input type="text" name="link_{{ $item['form']->id }}[]"
                                                        class="form-control" disabled>
                                                </div>
                                            @endif
                                        </div>
                                    </div>

                                    {{-- Notifikasi Terkirim --}}
                                    {{-- @if ($notif_terkirim->isNotEmpty())
                                        @foreach ($notif_terkirim as $notif)
                                            <div class="mt-3" id="alert_notifikasi_{{ $notif->id }}">
                                                <small>Jika jawaban sudah disimpan dan diperbaiki silahkan klik
                                                    tombol
                                                    Notifikasi
                                                    Diterima!</small>
                                                <div class="alert alert-warning" role="alert">
                                                    <span>Notifikasi dari Auditor!</span><br>
                                                    {{ $notif->pesan }}
                                                    <br>
                                                </div>
                                            </div>
                                        @endforeach
                                    @endif --}}


                                    {{-- Jawaban Auditor --}}
                                    {{-- <div class="form-group mt-3">
                                        <label for="">Jawaban Auditor</label>
                                        <span class="text-danger">&#42;</span>
                                        @foreach ($item['form']->instrumen->kriteria as $index => $kriteria)
                                            <div class="custom-control custom-radio">
                                                <input class="custom-control-input jawaban-auditor" type="radio"
                                                    id="customRadio{{ $item['form']->id }}-{{ $index }}"
                                                    name="kriteria_{{ $item['form']->id }}"
                                                    value="{{ $kriteria->id }}"
                                                    {{ $sessionKriteria == $kriteria->id ? 'checked' : '' }} disabled>
                                                <label for="customRadio{{ $item['form']->id }}-{{ $index }}"
                                                    class="custom-control-label">
                                                    <span>
                                                        {{ $kriteria->nama }}
                                                    </span><br>
                                                    <span style="font-weight: 500">
                                                        {{ $kriteria->pivot->isi }}
                                                    </span>
                                                </label>
                                                <br>
                                            </div>
                                        @endforeach
                                    </div> --}}

                                    {{-- Catatan Auditor --}}
                                    <div>
                                        <label for="">Catatan Auditor</label>
                                        <textarea cols="10" rows="3" class="form-control" disabled>{{ $jawabanAuditor ? $jawabanAuditor->catatan : '' }}</textarea>
                                    </div>

                                    {{-- Daftar Tilik --}}
                                    {{-- <div class="form-group mt-3">
                                        <label for="">Daftar Tilik</label>
                                        <span class="text-danger">&#42;</span>
                                        <div class="custom-control custom-radio">
                                            <input class="custom-control-input daftar-tilik" type="radio"
                                                id="daftarTilikYa-{{ $item['form']->id }}"
                                                name="daftar-tilik_{{ $item['form']->id }}" value="1"
                                                {{ $sessionDaftarTilik == '1' ? 'checked' : '' }} disabled>
                                            <label for="daftarTilikYa-{{ $item['form']->id }}"
                                                class="custom-control-label">
                                                Ya
                                            </label>
                                            <br>
                                        </div>
                                        <div class="custom-control custom-radio">
                                            <input class="custom-control-input daftar-tilik" type="radio"
                                                id="daftarTilikTidak-{{ $item['form']->id }}"
                                                name="daftar-tilik_{{ $item['form']->id }}" value="0"
                                                {{ $sessionDaftarTilik == '0' ? 'checked' : '' }} disabled>
                                            <label for="daftarTilikTidak-{{ $item['form']->id }}"
                                                class="custom-control-label">
                                                Tidak
                                            </label>
                                            <br>
                                        </div>
                                    </div> --}}

                                    {{-- Notifikasi Diterima --}}
                                    {{-- @if ($notif_diterima->isNotEmpty())
                                        @foreach ($notif_diterima as $notif)
                                            <div class="mt-3" id="alert_notifikasi_{{ $notif->id }}">
                                                <div class="alert alert-warning" role="alert">
                                                    Jawaban auditee sudah diperbaiki silahkan klik tombol selesai
                                                    <br>
                                                </div>

                                            </div>
                                        @endforeach
                                    @endif --}}
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


                        @if ($paginatedForms->currentPage() == $paginatedForms->lastPage())
                            @if ($paginatedForms->lastPage() !== 1)
                                <div class="">
                                    <a id="previous" href="{{ $paginatedForms->previousPageUrl() }}"
                                        class="btn btn-primary mb-3">Previous</a>
                                </div>
                            @endif
                        @else
                            <div class="mb-3">
                                @if ($paginatedForms->currentPage() > 1)
                                    <a id="previous" href="{{ $paginatedForms->previousPageUrl() }}"
                                        class="btn btn-primary">Previous</a>
                                @endif
                                <a id="next" href="{{ $paginatedForms->nextPageUrl() }}"
                                    class="btn btn-primary">Next</a>
                            </div>
                        @endif



                        {{-- Kembali ke halaman auditee --}}
                        <a href="{{ route('hasil_audit_prodi.show', ['jadwalAudit' => $jadwal->id]) }}"
                            class="btn btn-outline-secondary">Kembali</a>
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
