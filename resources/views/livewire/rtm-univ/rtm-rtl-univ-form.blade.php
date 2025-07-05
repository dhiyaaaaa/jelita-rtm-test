<div class="container-fluid py-4">
    {{-- Pesan Peringatan --}}
    @if ($warningMessage)
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
            {{ $warningMessage }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    {{-- Pesan Error Validasi --}}
    @if ($errorMessage)
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ $errorMessage }}
            @if (!empty($missingFields))
                <p class="mt-2">Field yang perlu diisi:</p>
                <ul>
                    @foreach ($missingFields as $field)
                        <li>{{ str_replace(['rekomendasi_', 'koreksi_'], ['Rekomendasi ', 'Koreksi '], $field) }}</li>
                    @endforeach
                </ul>
            @endif
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <div class="row">
        <div class="col-md-9">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white rounded-top-lg">
                    <h3 class="card-title font-weight-bold">Tindak Lanjut Hasil Audit</h3>
                </div>
                <div class="card-body">
                    {{-- Form Livewire --}}
                    <form wire:submit.prevent="saveAll(false)"> {{-- Default: save as draft --}}
                        @php
                            $no = ($paginatedTemuan->currentPage() - 1) * $paginatedTemuan->perPage() + 1;
                            $isDisabled = isset($status) && $status->status === 'completed';
                        @endphp

                        @foreach ($paginatedTemuan as $item)
                            <div class="row mb-4">
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
                                            <!-- Catatan Auditor -->
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

                                            <!-- Rencana Tindakan yang sudah dibuat -->
                                            <div class="mb-4">
                                                <label class="font-weight-bold">Rencana Tindakan yang sudah dibuat</label>
                                                <div class="mt-2">
                                                    @php
                                                        // Filter rencana tindakan berdasarkan kriteria item saat ini
                                                        $filteredTindakan = $item->form->rtm_tindak_lanjut
                                                                ->where('kriteria_id', $item->kriteria->id)
                                                                ->sortBy('waktu');
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

                                            <!-- Form Jawaban -->
                                            <div class="form-group">
                                                <!-- Rekomendasi -->
                                                <div class="form-group">
                                                    <label for="rekomendasi_{{ $item->form->id }}">Rekomendasi</label>
                                                    <span class="text-danger">&#42;</span>
                                                    <textarea
                                                        wire:model.lazy="rekomendasi.{{ $item->form->id }}"
                                                        id="rekomendasi_{{ $item->form->id }}"
                                                        class="form-control summernote @if (in_array('rekomendasi_' . $item->form->id, $missingFields)) is-invalid @endif"
                                                        rows="5" placeholder="Masukkan rekomendasi"
                                                        dusk="rekomendasi-{{ $item->form->id }}"
                                                        {{ $isDisabled ? 'disabled' : '' }}
                                                        wire:ignore
                                                    ></textarea>
                                                    @if (in_array('rekomendasi_' . $item->form->id, $missingFields))
                                                        <span class="text-danger d-block" style="font-size: 14px">Rekomendasi harus diisi.</span>
                                                    @endif
                                                </div>

                                                <!-- PTK -->
                                                <div class="form-group">
                                                    <label for="koreksi_{{ $item->form->id }}">Permintaan Tindakan Koreksi</label>
                                                    <span class="text-danger">&#42;</span>
                                                    <textarea
                                                        wire:model.lazy="koreksi.{{ $item->form->id }}"
                                                        id="koreksi_{{ $item->form->id }}"
                                                        class="form-control summernote @if (in_array('koreksi_' . $item->form->id, $missingFields)) is-invalid @endif"
                                                        rows="5" placeholder="Masukkan koreksi"
                                                        dusk="koreksi-{{ $item->form->id }}"
                                                        {{ $isDisabled ? 'disabled' : '' }}
                                                        wire:ignore
                                                    ></textarea>
                                                    @if (in_array('koreksi_' . $item->form->id, $missingFields))
                                                        <span class="text-danger d-block" style="font-size: 14px">Permintaan Tindakan Koreksi harus diisi.</span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </form> {{-- Close the form here, as buttons are outside the loop --}}
                </div>
            </div>
        </div>

        <!-- Pagination & Action Buttons -->
        <div class="col-md-3">
            <div class="pagination-container">
                <div class="card card-primary shadow-sm">
                    <div class="card-header bg-primary text-white">
                        Halaman
                    </div>
                    <div class="card-body">
                        <div class="pagination-wrapper">
                            {{ $paginatedTemuan->links('pagination::bootstrap-4') }}
                        </div>

                        {{-- Tombol Simpan Draf --}}
                        <div class="mb-3">
                            <button type="button" class="btn btn-primary btn-block rounded-pill shadow-sm" wire:click="saveAll(false)" wire:loading.attr="disabled" wire:target="saveAll(false)">
                                <span wire:loading wire:target="saveAll(false)"><i class="fas fa-spinner fa-spin mr-2"></i>Menyimpan Draf...</span>
                                <span wire:loading.remove wire:target="saveAll(false)"><i class="fas fa-save mr-2"></i>Simpan Draf</span>
                            </button>
                        </div>

                        {{-- Tombol Selesaikan & Kirim (hanya di halaman terakhir jika belum completed) --}}
                        @if ($paginatedTemuan->currentPage() == $paginatedTemuan->lastPage())
                            @if (!isset($status) || $status->status !== 'completed')
                                <div class="mb-3">
                                    <button type="button" class="btn btn-success btn-block rounded-pill shadow-sm" wire:click="saveAll(true)" wire:loading.attr="disabled" wire:target="saveAll(true)">
                                        <span wire:loading wire:target="saveAll(true)"><i class="fas fa-spinner fa-spin mr-2"></i>Menyelesaikan...</span>
                                        <span wire:loading.remove wire:target="saveAll(true)"><i class="fas fa-check-circle mr-2"></i>Selesaikan & Kirim</span>
                                    </button>
                                </div>
                            @endif
                        @endif

                        {{-- Tombol Ubah (jika status sudah completed) --}}
                        @if (isset($status) && $status->status === 'completed')
                            <div class="mb-3">
                                <button type="button" class="btn btn-warning btn-block rounded-pill shadow-sm" wire:click="toggleEditMode" wire:loading.attr="disabled" wire:target="toggleEditMode">
                                    <span wire:loading wire:target="toggleEditMode"><i class="fas fa-spinner fa-spin mr-2"></i>Memuat...</span>
                                    <span wire:loading.remove wire:target="toggleEditMode"><i class="fas fa-edit mr-2"></i>Ubah</span>
                                </button>
                            </div>
                        @endif

                        <!-- Tombol Kembali -->
                        <div class="d-flex justify-content-start mt-3">
                            <a href="{{ route('admin.rtm-rtl.show', $rtmJadwal->id) }}" class="btn btn-outline-secondary btn-block rounded-pill shadow-sm">
                                <i class="fas fa-arrow-left mr-2"></i>Kembali
                            </a>
                        </div>
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

        .pagination-wrapper .pagination {
            display: flex;
            flex-wrap: wrap;
        }

        .pagination-wrapper .page-item {
            flex: 1 0 1;
        }

        .filter-section {
            max-height: 300px;
            overflow-y: auto;
            padding: 10px;
            border: 1px solid #eee;
            border-radius: 5px;
            margin-bottom: 15px;
        }
    </style>
@endsection

@section('script')
    <!-- Summernote -->
    <script src="{{ asset('plugins/summernote/summernote-bs4.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> {{-- Tambahkan SweetAlert2 --}}

    <script>
        // Global function to handle SweetAlert2 for session messages
        document.addEventListener('DOMContentLoaded', function() {
            @if (session('success'))
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: "{{ session('success') }}"
                });
            @endif

            @if (session('error'))
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: "{{ session('error') }}"
                });
            @endif
        });

        // Initialize Summernote and link to Livewire
        document.addEventListener('livewire:load', function () {
            // Function to initialize Summernote for a given textarea
            function initializeSummernote(textareaElement) {
                let textarea = $(textareaElement);
                if (!textarea.data('summernote-initialized')) { // Prevent re-initialization
                    textarea.summernote({
                        height: 150,
                        callbacks: {
                            onChange: function (contents) {
                                // Get the wire:model.lazy attribute value
                                let propertyName = textarea.attr('wire:model.lazy');
                                if (propertyName) {
                                    // Update the Livewire component property
                                    @this.set(propertyName, contents);
                                }
                            }
                        }
                    });

                    // Disable Summernote if the textarea is disabled
                    if (textarea.prop('disabled')) {
                        textarea.summernote('disable');
                    }
                    textarea.data('summernote-initialized', true); // Mark as initialized
                }
            }

            // Initialize Summernote for all existing textareas on initial load
            $('.summernote').each(function () {
                initializeSummernote(this);
            });

            // Re-initialize Summernote when Livewire updates elements (e.g., pagination changes)
            Livewire.hook('element.updated', (el, component) => {
                // Check if the updated element is a textarea with class 'summernote'
                // and has not been initialized yet
                if ($(el).is('textarea.summernote') && !$(el).data('summernote-initialized')) {
                    initializeSummernote(el);
                }
            });

            // Handle the sticky pagination container
            var $paginationContainer = $('.pagination-container');
            var navbarHeight = 56; // Adjust if your navbar height is different

            $(window).on('scroll', function() {
                if ($(this).scrollTop() > 0) {
                    $paginationContainer.css('padding-top', (navbarHeight + 12) + 'px');
                } else {
                    $paginationContainer.css('padding-top', '12px');
                }
            });

            $(window).trigger('scroll'); // Trigger on load
        });
    </script>
@endsection
