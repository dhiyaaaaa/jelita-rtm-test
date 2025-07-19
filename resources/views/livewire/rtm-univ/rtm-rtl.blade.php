@php
    use Carbon\Carbon;
@endphp

<div>
    {{-- Info RTM --}}
    <div class="card shadow border-0" style="background: linear-gradient(135deg, #6694ea, #614ba2); color: white;">
        <div class="card-header border-0">
            <h3 class="card-title title-size text-white">Rapat Tinjauan Manajemen</h3>
        </div>

        <div class="card-body">
            <a href="{{ route('admin.rtm-univ.index-livewire') }}" class="btn btn-outline-light mb-3">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>

            <table class="table table-borderless text-white">
                <tr>
                    <td class="fw-bold" width="15%">Agenda</td>
                    <td width="5%">:</td>
                    <td>{{ $rtmJadwal->agenda }}</td>
                </tr>
                <tr>
                    <td class="fw-bold" width="10%">Pimpinan Rapat</td>
                    <td width="5%">:</td>
                    <td>{{ $rtmJadwal->pimpinan }}</td>
                </tr>
                <tr>
                    <td class="fw-bold">Tanggal</td>
                    <td>:</td>
                    <td>
                        {{ Carbon::parse($rtmJadwal->tanggal)->translatedFormat('l, j F Y') }}
                    </td>
                </tr>
                <tr>
                    <td class="fw-bold">Waktu</td>
                    <td>:</td>
                    <td>
                        {{ Carbon::parse($rtmJadwal->jam_mulai)->translatedFormat('H:i') }} - {{ Carbon::parse($rtmJadwal->jam_selesai)->translatedFormat('H:i') }}
                    </td>
                </tr>
                <tr>
                    <td class="fw-bold" width="10%">Tempat</td>
                    <td width="5%">:</td>
                    <td>{{ $rtmJadwal->tempat }}</td>
                </tr>
                <tr>
                    <td class="fw-bold" width="10%">Jumlah Kehadiran</td>
                    <td width="5%">:</td>
                    <td>{{ $rtmJadwal->peserta }} Peserta Rapat</td>
                </tr>
                <tr>
                    <td class="fw-bold" width="10%">Hasil AIMA</td>
                    <td width="5%">:</td>
                    <td>{{ $rtmJadwal->jadwal_audit->jadwal }}</td>
                </tr>
                <tr>
                    <td class="fw-bold">Periode AIMA</td>
                    <td>:</td>
                    <td>
                        {{ Carbon::parse($rtmJadwal->jadwal_audit->tgl_mulai)->translatedFormat('j F Y') }} - 
                        {{ Carbon::parse($rtmJadwal->jadwal_audit->tgl_selesai)->translatedFormat('j F Y') }}
                    </td>
                </tr>
            </table>
        </div>
    </div>

    <div class="card shadow border-0 mt-3">
        <div class="card-header bg-white border-bottom">
            <h5 class="mb-0 text-dark"><i class="fas fa-paperclip me-2"></i> Upload Lampiran RTM</h5>
        </div>
        <div class="card-body">
                <form wire:submit.prevent="saveLampiran">
                    @csrf
                    <input type="hidden" wire:model="rtmJadwalId">
                    <div class="mb-4">
                        <label for="undangan" class="form-label fw-bold">
                            <i class="fas fa-file-invoice me-1"></i> Upload File Undangan
                        </label>
                        <div class="input-group">
                            <input type="file" class="form-control" id="undangan" name="undangan" required dusk="input-undangan" wire:model="undangan">
                            @if($rtmLampiran && $rtmLampiran->undangan)
                                <a href="{{ Storage::url($rtmLampiran->undangan) }}"
                                target="_blank"
                                class="input-group-text text-decoration-none">
                                <i class="fas fa-eye"></i> Lihat
                                </a>
                            @endif
                        </div>
                    </div>
                    <div class="mb-4">
                        <label for="presensi" class="form-label fw-bold">
                            <i class="fas fa-clipboard me-1"></i> Upload File Presensi
                        </label>
                        <div class="input-group">
                            <input type="file" class="form-control" id="presensi" name="presensi" required dusk="input-presensi" wire:model="presensi">
                            @if($rtmLampiran && $rtmLampiran->presensi)
                                <a href="{{ Storage::url($rtmLampiran->presensi) }}"
                                target="_blank"
                                class="input-group-text text-decoration-none">
                                <i class="fas fa-eye"></i> Lihat
                                </a>
                            @endif
                        </div>
                    </div>
                    <div class="mb-4">
                        <label for="dokumentasi" class="form-label fw-bold">
                            <i class="fas fa-camera me-1"></i> Upload File Dokumentasi
                        </label>
                        <div class="input-group">
                            <input type="file" class="form-control" id="dokumentasi" name="dokumentasi" required dusk="input-dokumentasi" wire:model="dokumentasi">
                            @if($rtmLampiran && $rtmLampiran->dokumentasi)
                                <a href="{{ Storage::url($rtmLampiran->dokumentasi) }}"
                                target="_blank"
                                class="input-group-text text-decoration-none">
                                <i class="fas fa-eye"></i> Lihat
                                </a>
                            @endif
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary" wire:loading.attr="disabled" wire:target="saveLampiran, undangan, presensi, dokumentasi">
                        Simpan
                    </button>
                </form>
        </div>
    </div>

    {{-- Catatan Narasi --}}
    <div class="card shadow border-0 mt-3">
        <div class="card-header bg-white border-bottom">
            <h5 class="mb-0 text-dark"><i class="fas fa-file-alt me-2"></i> Narasi Laporan RTM</h5>
        </div>
        
        <div class="card-body">
            <p class="text-muted">Silakan tambahkan kata pengantar laporan RTM untuk menjelaskan konteks dan tujuan laporan ini, jika diperlukan.</p>
            <div class="d-flex justify-content">
                <a href="{{ route('admin.rtm-catatan.form-livewire', [$rtmJadwal->id]) }}"
                    class="btn btn-primary"><i class="fas fa-edit me-1"></i> Isi </a>
            </div>
        </div>
    </div>

    <div class="card shadow-lg border-0 rounded-lg">
        <div class="card-body">
            <div class="mb-3 p-3 bg-light rounded">
                <h5>Pilih Kriteria:</h5>
                <form wire:submit.prevent="loadData"> 
                    <div class="row">
                        @foreach($kriteriaOptions as $kriteria)
                        <div class="col-md-3">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox"
                                    class="custom-control-input"
                                    id="kriteria_{{ $kriteria->id }}"
                                    wire:model.live="selectedKriteria" {{-- Gunakan .live untuk update real-time --}}
                                    value="{{ $kriteria->id }}">
                                <label class="custom-control-label" for="kriteria_{{ $kriteria->id }}">
                                    {{ $kriteria->nama }}
                                </label>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    <div class="mt-3">
                        <input type="text" wire:model.live.debounce.500ms="search" class="form-control" placeholder="Cari Fakultas/Unit..." dusk="search-input">
                    </div>

                    <div class="mt-3">
                        @if(!empty($selectedKriteria) || !empty($search))
                            <button wire:click="resetFilter" type="button" class="btn btn-outline-secondary">Reset Filter</button>
                        @endif
                    </div>
                </form>
            </div>
            <table id="rtm" class="table table-hover table-striped">
                <thead class="bg-dark text-white text-center">
                    <tr>
                        <th>No</th>
                        <th>Fakultas/Unit</th>
                        <th>Rencana Tindak Lanjut</th>
                        <th>Jumlah Temuan</th>
                        <th>Aksi</th>
                        <th>Status Approval</th>
                    </tr>
                </thead>
                <tbody>
                    {{-- Hapus @php $no = 1; @endphp --}}
                    @foreach ($units as $item)
                        <tr class="text-center" wire:key="unit-{{ $item->id }}" dusk="unit-row-{{ $item->id }}">
                            <td>{{ $loop->iteration + ($units->currentPage() - 1) * $units->perPage() }}</td> {{-- Hitung nomor urut dengan paginasi --}}
                            <td>
                                @if($item->jenis_unit === 'fakultas')
                                    Fakultas {{ $item->nama }}
                                @else
                                    {{ $item->nama }}
                                @endif
                            </td>
                            <td>
                                @if($item->rtm_rtl_id)
                                    <span class="badge bg-success">Ada</span>
                                @else
                                    <span class="badge bg-secondary">Tidak Ada</span>
                                @endif
                            </td>
                            <td>
                                <span class="badge bg-info">{{ $item->jumlah_temuan ?? 0 }}</span>
                            </td>
                            <td>
                                @if($item->rtm_rtl_univ_id)
                                    @if(!empty($item->rtm_rtl_univ_status) && $item->rtm_rtl_univ_status === 'completed')
                                        <a href="{{ route('admin.rtm-rtl.form-livewire', ['rtmRtlUniv' => $item->rtm_rtl_univ_id, 'selectedKriteria' => $selectedKriteria]) }}"
                                            class="btn btn-primary btn-fixed-size">Sudah Isi</a>
                                    @else
                                        <a href="{{ route('admin.rtm-rtl.form-livewire', ['rtmRtlUniv' => $item->rtm_rtl_univ_id, 'selectedKriteria' => $selectedKriteria]) }}"
                                            class="btn btn-outline-primary btn-fixed-size">Isi</a>
                                        
                                    @endif
                                @else
                                    <button type="button"
                                            class="btn btn-outline-primary btn-fixed-size"
                                            wire:click="confirmRtmRtlUniv('{{ $item->jenis_unit === 'fakultas' ? $item->id : '' }}', '{{ $item->jenis_unit === 'unit' ? $item->id : '' }}')"
                                            @if(($item->jumlah_temuan ?? 0) == 0) disabled @endif>
                                        +Tindak Lanjut
                                    </button>
                                @endif
                            </td>
                            <td>
                                @if($item->approval_status !== null && $item->approval_status == 1)
                                    <span class="badge bg-success">Approved</span>
                                @else
                                    <span class="badge bg-secondary">User belum melakukan approval</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="mt-4" dusk="pagination">
                {{ $units->links() }}
            </div>
        </div>
    </div>

    {{-- Download dan Approve --}}
    <div class="row align-items-stretch mb-4">
        <div class="col-sm-6 mb-3 mb-sm-0 d-flex">
            <div class="card w-100 h-100">
                <div class="card-body">
                    <h5 class="card-title title-size text-dark">Download dan Approve Rencana Tindak Lanjut Hasil Audit</h5>
                    <p class="card-text">Silahkan download dan approve, setelah mengisi rencana Hasil Audit.</p>

                    <div class="mb-2">
                        @if ($isRektor && $user == $rektorId && (!$approvalRektor || !$approvalRektor->approve))
                            <button wire:click="approveRtmUniv"
                                    wire:loading.attr="disabled"
                                    class="btn btn-outline-success w-100">
                                <span wire:loading.remove><i class="fas fa-thumbs-up"></i> Approve sebagai Rektor</span>
                                <span wire:loading class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                <span wire:loading>Memproses...</span>
                            </button>
                        @elseif($isKetuaLP3M && $user == $ketuaLP3MId && (!$approvalKetuaLP3M || !$approvalKetuaLP3M->approve))
                            <button wire:click="approveRtmUniv"
                                    wire:loading.attr="disabled"
                                    class="btn btn-outline-success w-100">
                                <span wire:loading.remove><i class="fas fa-thumbs-up"></i> Approve sebagai Ketua LP3M</span>
                                <span wire:loading class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                <span wire:loading>Memproses...</span>
                            </button>
                        @elseif(($isRektor && $approvalRektor && $approvalRektor->approve) || 
                                ($isKetuaLP3M && $approvalKetuaLP3M && $approvalKetuaLP3M->approve))
                            <button disabled class="btn btn-secondary w-100">
                                <i class="fas fa-check-circle"></i> Anda sudah memberikan approval
                            </button>
                        @endif
                    </div>

                    <div class="alert alert-primary mt-3">
                        Status Approval:
                        <ul class="mt-2">
                            <li>Rektor: 
                                @if($approvalRektor && $approvalRektor->approve)
                                    <span class="badge bg-warning">Sudah Approve</span>
                                @else
                                    <span class="badge bg-danger">Belum Approve</span>
                                @endif
                            </li>
                            <li>Ketua LP3M: 
                                @if($approvalKetuaLP3M && $approvalKetuaLP3M->approve)
                                    <span class="badge bg-warning">Sudah Approve</span>
                                @else
                                    <span class="badge bg-danger">Belum Approve</span>
                                @endif
                            </li>
                        </ul>
                    </div>
                    <div>
                        <form action="{{ route('download.rtm.univ', $rtmJadwal->id) }}" 
                            class="d-inline">
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="fas fa-download"></i> Download Laporan RTM
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 mb-3 mb-sm-0 d-flex">
            <div class="card shadow border-0 w-100 h-100" style="background: linear-gradient(135deg, #6694ea, #614ba2); color: white;">
                <div class="card-body">
                    <h5 class="card-title title-size text-dark"><i class="fas fa-info-circle me-2"></i> Informasi Temuan Audit</h5>
                    <p class="card-text">Temuan ini merupakan hasil dari audit yang sudah dilaksanakan. Pengelompokkan temuan hasil audit sesuai dengan jawaban auditor yang sudah dikelompokkan berdasarkan kriteria. </p>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="konfirmasiModal" tabindex="-1" aria-labelledby="konfirmasiModalLabel" aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="konfirmasiModalLabel">Konfirmasi Tindak Lanjut </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Apakah Anda ingin membuat rekomendasi dan permintaan tindakan koreksi?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal" wire:click="$set('showKonfirmasiModal', false)">Batal</button>
                    <button type="button" class="btn btn-primary" wire:click="storeRtmRtl" wire:loading.attr="disabled">
                        <span wire:loading wire:target="storeRtmRtl" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                        <span wire:loading.remove wire:target="storeRtmRtl" class="btn-text">Ya, Lanjutkan</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@section('script')
   
    <script>
        // Handle modal (tetap pertahankan ini)
        document.addEventListener('livewire:initialized', () => {
            Livewire.on('show-konfirmasi-modal', () => {
                var myModal = new bootstrap.Modal(document.getElementById('konfirmasiModal'));
                myModal.show();
            });
            Livewire.on('hide-konfirmasi-modal', () => {
                var myModal = bootstrap.Modal.getInstance(document.getElementById('konfirmasiModal'));
                if (myModal) {
                    myModal.hide();
                }
            });

            Livewire.on('show-alert', (event) => {
                Swal.fire({
                    icon: event[0].type,
                    title: event[0].type === 'success' ? 'Berhasil!' : 'Oops...',
                    text: event[0].message
                });
            });
        });
    </script>

    @if(session()->has('info_message'))
        <div class="alert alert-info alert-dismissible fade show fixed-top mx-auto mt-3" role="alert" style="width: fit-content; z-index: 1060;">
            {{ session('info_message') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session()->has('warning'))
        <div class="alert alert-warning alert-dismissible fade show fixed-top mx-auto mt-3" role="alert" style="width: fit-content; z-index: 1060;">
            {{ session('warning') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
@endsection