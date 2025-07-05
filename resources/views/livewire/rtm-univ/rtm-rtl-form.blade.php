<div class="row">
    <div class="col-md-9">
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <h3 class="card-title font-weight-bold">{{ $title }}</h3>
            </div>
            <div class="card-body">
                @php
                    // Tambahkan pengecekan untuk memastikan $paginatedTemuan adalah instance paginator
                    // dan memiliki item sebelum memanggil currentPage() atau perPage()
                    $no = 1; // Nilai default
                    if ($paginatedTemuan && $paginatedTemuan->count() > 0) {
                        $no = ($paginatedTemuan->currentPage() - 1) * $paginatedTemuan->perPage() + 1;
                    }
                @endphp

                {{-- Notifikasi --}}
                @if (session()->has('message'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('message') }}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif

                @if (session()->has('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ session('error') }}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif

                {{-- Baris yang menyebabkan error (line 36) adalah @forelse ($paginatedTemuan as $item) --}}
                {{-- Dengan perbaikan di komponen Livewire, $paginatedTemuan seharusnya tidak akan pernah null --}}
                @forelse ($paginatedTemuan as $item)
                    <div class="row mb-4">
                        @php
                            $jawaban = $jawabanRtm->where('form_id', $item->form->id)->first();
                        @endphp

                        <div class="col-12">
                            <div class="card mb-3">
                                <div class="card-header bg-light">
                                    <h4 class="card-title w-100">
                                        <div class="d-flex flex-wrap gap-2 mb-2">
                                            @forelse ($item->form->instrumen->jabatan as $jabatanInstrumen)
                                                <span class="badge badge-primary">{{ $jabatanInstrumen->nama }}</span>
                                            @empty
                                                <span class="badge badge-secondary">-</span>
                                            @endforelse
                                        </div>
                                        <div class="mb-2">
                                            @if ($item->kriteria->slug === 'belum-memenuhi')
                                                <span class="badge badge-danger">{{ $item->kriteria->nama }}</span>
                                            @elseif ($item->kriteria->slug === 'memenuhi')
                                                <span class="badge badge-warning">{{ $item->kriteria->nama }}</span>
                                            @elseif ($item->kriteria->slug === 'melampaui')
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
                                    <div class="form-group mb-4">
                                        <label class="font-weight-bold">Catatan Auditor</label>
                                        <div class="mt-2">
                                            @if ($item->kriteria->slug === 'belum-memenuhi')
                                                @if ($item->form->ptk_form_deskripsi->isNotEmpty())
                                                    @foreach ($item->form->ptk_form_deskripsi as $deskripsi)
                                                        <p class="text-muted">{{ $deskripsi->deskripsi }}</p>
                                                    @endforeach
                                                @elseif ($item->form->jawaban_auditor->isNotEmpty())
                                                    @foreach ($item->form->jawaban_auditor as $jawaban)
                                                        @if ($jawaban->catatan)
                                                            <p class="text-muted">{{ $jawaban->catatan }}</p>
                                                        @endif
                                                    @endforeach
                                                @else
                                                    <p class="text-muted">Tidak ada catatan</p>
                                                @endif
                                            @else
                                                @if ($item->form->laporan_form->isNotEmpty())
                                                    @foreach ($item->form->laporan_form as $kelebihan)
                                                        <p class="text-muted">{{ $kelebihan->kelebihan }}</p>
                                                    @endforeach
                                                @elseif ($item->form->jawaban_auditor->isNotEmpty())
                                                    @foreach ($item->form->jawaban_auditor as $jawaban)
                                                        @if ($jawaban->catatan)
                                                            <p class="text-muted">{{ $jawaban->catatan }}</p>
                                                        @endif
                                                    @endforeach
                                                @else
                                                    <p class="text-muted">Tidak ada catatan</p>
                                                @endif
                                            @endif
                                        </div>
                                    </div>

                                    <div class="mb-4">
                                        <label class="font-weight-bold">Rencana Tindakan yang sudah dibuat</label>
                                        <div class="mt-2">
                                            @php
                                                $filteredTindakan = $item->form->rtm_tindak_lanjut
                                                    ->where('kriteria_id', $item->kriteria->id);
                                            @endphp

                                            @if ($filteredTindakan->isNotEmpty())
                                                @foreach ($filteredTindakan as $index => $tindakan)
                                                    <div class="mb-2">
                                                        <p class="text-muted">
                                                            {{ $index + 1 }}. Tindakan: {{ $tindakan->tindakan }}<br>
                                                            PIC: {{ $tindakan->jabatan->nama }}-
                                                            @if ($tindakan->prodi_id)
                                                                {{ $tindakan->prodi->nama }}
                                                            @elseif ($tindakan->fakultas_id)
                                                                {{ $tindakan->fakultas->nama }}
                                                            @endif
                                                            <br>
                                                            Target Waktu: {{ $tindakan->waktu }}
                                                        </p>
                                                    </div>
                                                @endforeach
                                            @else
                                                <p class="text-muted">Belum ada rencana tindak lanjut untuk kriteria ini.</p>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <div class="form-group">
                                            <label for="rekomendasi_{{ $item->form->id }}">Rekomendasi</label>
                                            <span class="text-danger">&#42;</span>
                                            <textarea
                                                wire:model.debounce.500ms="rekomendasi.{{ $item->form->id }}"
                                                id="rekomendasi_{{ $item->form->id }}"
                                                cols="10" rows="5"
                                                class="form-control summernote @error('rekomendasi.' . $item->form->id) is-invalid @enderror"
                                                {{ $isCompleted ? 'disabled' : '' }}></textarea>

                                            @error('rekomendasi.' . $item->form->id)
                                                <span class="text-danger d-block" style="font-size: 14px">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        <div class="form-group">
                                            <label for="koreksi_{{ $item->form->id }}">Permintaan Tindakan Koreksi</label>
                                            <span class="text-danger">&#42;</span>
                                            <textarea
                                                wire:model.debounce.500ms="koreksi.{{ $item->form->id }}"
                                                id="koreksi_{{ $item->form->id }}"
                                                cols="10" rows="5"
                                                class="form-control summernote @error('koreksi.' . $item->form->id) is-invalid @enderror"
                                                {{ $isCompleted ? 'disabled' : '' }}></textarea>

                                            @error('koreksi.' . $item->form->id)
                                                <span class="text-danger d-block" style="font-size: 14px">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <button wire:click="saveItem('{{ $item->form->id }}')" wire:loading.attr="disabled"
                                            wire:target="saveItem('{{ $item->form->id }}')" class="btn btn-warning"
                                            type="button" {{ $isCompleted ? 'disabled' : '' }}>
                                            <span wire:loading.remove wire:target="saveItem('{{ $item->form->id }}')">Simpan</span>
                                            <span wire:loading wire:target="saveItem('{{ $item->form->id }}')" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                            <span wire:loading wire:target="saveItem('{{ $item->form->id }}')">Loading...</span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <p class="text-muted text-center">Tidak ada temuan yang ditemukan.</p>
                @endforelse
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="pagination-container">
            <div class="card card-primary shadow-sm">
                <div class="card-header bg-primary text-white">
                    Halaman
                </div>
                <div class="card-body">
                    @if ($paginatedTemuan && $paginatedTemuan->count() > 0)
                        <div class="pagination-wrapper">
                            {{ $paginatedTemuan->links('pagination::bootstrap-4') }}
                        </div>

                        @if ($paginatedTemuan->currentPage() == $paginatedTemuan->lastPage())
                            @if (!$isCompleted)
                                <div class="mb-3">
                                    <button wire:click="submitForm" wire:loading.attr="disabled" wire:target="submitForm"
                                        class="btn btn-primary" type="button">
                                        <span wire:loading.remove wire:target="submitForm">Submit</span>
                                        <span wire:loading wire:target="submitForm" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                        <span wire:loading wire:target="submitForm">Loading...</span>
                                    </button>
                                </div>
                            @endif
                        @endif
                    @else
                        <p class="text-muted text-center">Tidak ada halaman yang tersedia.</p>
                    @endif

                    @if ($isCompleted)
                        <div class="mb-3">
                            <button wire:click="enableEditMode" wire:loading.attr="disabled" wire:target="enableEditMode"
                                class="btn btn-warning" type="button">
                                <span wire:loading.remove wire:target="enableEditMode">Ubah</span>
                                <span wire:loading wire:target="enableEditMode" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                <span wire:loading wire:target="enableEditMode">Loading...</span>
                            </button>
                        </div>
                    @endif

                    <div class="d-flex justify-content-start">
                        <a href="{{ route('admin.rtm-rtl.show', $rtmJadwal->id) }}" class="btn btn-outline-secondary mr-2">Kembali</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@section('style')
    <link rel="stylesheet" href="{{ asset('plugins/summernote/summernote-bs4.min.css') }}">
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
    </style>
@endsection

@section('script')
    <script src="{{ asset('plugins/summernote/summernote-bs4.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            window.addEventListener('livewire:load', function () {
                $('.summernote').summernote({
                    height: 150,
                    callbacks: {
                        onChange: function(contents, $editable) {
                            @this.set($(this).attr('wire:model.debounce.500ms'), contents);
                        }
                    }
                });
            });

            Livewire.hook('element.updated', (el, component) => {
                if ($(el).is('.summernote')) {
                    $(el).summernote('destroy');
                    $(el).summernote({
                        height: 150,
                        callbacks: {
                            onChange: function(contents, $editable) {
                                @this.set($(this).attr('wire:model.debounce.500ms'), contents);
                            }
                        }
                    });
                }
            });
        });
    </script>
@endsection