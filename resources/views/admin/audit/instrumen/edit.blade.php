@extends('components.layout.main_layout')

@section('content')
    <div class="card card-primary">
        <div class="card-header">
            <h3 class="card-title title-size">{{ $title }}</h3>
        </div>
        <!-- /.card-header -->
        <!-- form start -->
        <form method="POST" action="{{ route('instrumen.update', $instrumen->id) }}" id="edit-form">
            @csrf
            @method('put')
            <div class="card-body">

                {{-- peraturan --}}
                <div class="form-group">
                    <label>Peraturan</label>
                    <span class="text-danger">&#42;</span>
                    <select class="select2" data-placeholder="Pilih Peraturan" style="width: 100%;" name="peraturan">
                        <option disabled selected></option>
                        @foreach ($peraturan as $item)
                            <option value="{{ $item->id }}"
                                {{ $instrumen->peraturan_id == $item->id ? 'selected' : '' }}>{{ $item->nama }}
                            </option>
                        @endforeach
                    </select>
                    @if ($errors->has('peraturan'))
                        <span class="text-danger d-block" style="font-size: 14px">{{ $errors->first('peraturan') }}</span>
                    @endif
                </div>

                {{-- pasal & ayat --}}
                <div class="form-group">
                    <div class="d-flex items-center">
                        <label>Pasal dan Ayat</label>
                        <div id="loading-spinner" class="ml-2" style="display: none;">
                            <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                            Loading...
                        </div>
                    </div>
                    <select class="select2" multiple data-placeholder="Pilih Pasal dan Ayat" style="width: 100%;"
                        name="pasalAyat[]">
                        <option disabled selected></option>
                    </select>
                    <small class="form-text text-muted">Jika belum muncul silahkan pilih peraturan terlebih dahulu</small>
                    @if ($errors->has('pasalAyat'))
                        <span class="text-danger d-block" style="font-size: 14px">{{ $errors->first('pasalAyat') }}</span>
                    @endif
                </div>


                {{-- standar --}}
                <div class="form-group">
                    <div class="d-flex items-center">
                        <label>Standar</label>
                        <span class="text-danger">&#42;</span>
                        <div id="loading-spinner-standar" class="ml-2" style="display: none;">
                            <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                            Loading...
                        </div>
                    </div>
                    <select class="select2" data-placeholder="Pilih Standar" style="width: 100%;" name="standar">
                        <option disabled selected></option>
                    </select>
                    <small class="form-text text-muted">Jika belum muncul silahkan pilih peraturan terlebih dahulu</small>
                    @if ($errors->has('standar'))
                        <span class="text-danger d-block" style="font-size: 14px">{{ $errors->first('standar') }}</span>
                    @endif
                </div>

                {{-- kategori --}}
                <div class="form-group">
                    <div class="d-flex items-center">
                        <label>Kategori</label>
                        <span class="text-danger">&#42;</span>
                        <div id="loading-spinner-kategori" class="ml-2" style="display: none;">
                            <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                            Loading...
                        </div>
                    </div>
                    <select class="select2" data-placeholder="Pilih Kategori" style="width: 100%;" name="kategori">
                    </select>
                    <small class="form-text text-muted">Jika belum muncul silahkan pilih standar terlebih dahulu</small>
                    @if ($errors->has('kategori'))
                        <span class="text-danger d-block" style="font-size: 14px">{{ $errors->first('kategori') }}</span>
                    @endif
                </div>

                {{-- jenis_pertanyaan --}}
                <div class="form-group">
                    <label>Jenis Pertanyaan</label>
                    <span class="text-danger">&#42;</span>
                    <select class="select2" data-placeholder="Pilih Jenis Pertanyaan" style="width: 100%;"
                        name="jenis_pertanyaan">
                        <option disabled selected></option>
                        @foreach ($jenis_pertanyaan as $item)
                            <option value="{{ $item->id }}"
                                {{ $instrumen->jenis_pertanyaan_id == $item->id ? 'selected' : '' }}>
                                {{ ucwords($item->nama) }}</option>
                        @endforeach
                    </select>
                    @if ($errors->has('jenis_pertanyaan'))
                        <span class="text-danger d-block"
                            style="font-size: 14px">{{ $errors->first('jenis_pertanyaan') }}</span>
                    @endif
                </div>

                {{-- pernyataan --}}
                <div class="form-group">
                    <label for="pernyataan">Pernyataan</label>
                    <span class="text-danger">&#42;</span>
                    <textarea name="pernyataan" id="pernyataan" cols="30" rows="3"
                        class="form-control @error('pernyataan') is-invalid @enderror" placeholder="Masukkan Pernyataan">{{ $instrumen->pernyataan }}</textarea>
                    @if ($errors->has('pernyataan'))
                        <span class="text-danger d-block" style="font-size: 14px">{{ $errors->first('pernyataan') }}</span>
                    @endif
                </div>

                {{-- indikator --}}
                <div class="form-group">
                    <label for="indikator">Indikator</label>
                    <span class="text-danger">&#42;</span>
                    <textarea name="indikator" id="indikator" cols="30" rows="3"
                        class="form-control @error('indikator') is-invalid @enderror" placeholder="Masukkan Indikator">{{ $instrumen->indikator }}</textarea>
                    @if ($errors->has('indikator'))
                        <span class="text-danger d-block" style="font-size: 14px">{{ $errors->first('indikator') }}</span>
                    @endif
                </div>


                {{-- kriteria --}}
                @foreach ($kriteria as $item)
                    <div class="form-group">
                        <label>Kriteria {{ $item->nama }}</label>
                        <span class="text-danger">&#42;</span>
                        <input type="hidden" name="kriteria_id[{{ $item->nama }}]" value="{{ $item->id }}"
                            readonly>
                        <textarea name="kriteria[{{ $item->nama }}]" cols="30" rows="2"
                            class="form-control @error('kriteria.' . $item->nama) is-invalid @enderror"
                            placeholder="Masukkan Kriteria {{ $item->nama }}">{{ old('kriteria.' . $item->nama, $item->pivot->isi) }}</textarea>
                        @if ($errors->has('kriteria.' . $item->nama))
                            <span class="text-danger d-block"
                                style="font-size: 14px">{{ $errors->first('kriteria.' . $item->nama) }}</span>
                        @endif
                    </div>
                @endforeach

                {{-- level --}}
                <div class="form-group">
                    <label>Level</label>
                    <span class="text-danger">&#42;</span>
                    <select class="select2" data-placeholder="Pilih Level" style="width: 100%;" name="level"
                        id="level">
                        <option disabled selected></option>
                        @foreach ($level as $item)
                            <option value="{{ $item->id }}" data-slug="{{ $item->slug }}"
                                {{ $instrumen->level_id == $item->id ? 'selected' : '' }}>
                                {{ $item->nama }}</option>
                        @endforeach
                    </select>
                    @if ($errors->has('level'))
                        <span class="text-danger d-block" style="font-size: 14px">{{ $errors->first('level') }}</span>
                    @endif
                </div>

                {{-- jenjang --}}
                <div class="form-group" id="jenjang" style="display: none">
                    <label>Jenjang</label>
                    <select class="select2" multiple data-placeholder="Pilih Jenjang" style="width: 100%;"
                        name="jenjang[]">
                        @foreach ($jenjang as $item)
                            <option value="{{ $item->id }}"
                                {{ in_array($item->id, $instrumen->jenjang->pluck('id')->toArray()) ? 'selected' : '' }}>
                                {{ $item->nama }}</option>
                        @endforeach
                    </select>
                    @if ($errors->has('jenjang'))
                        <span class="text-danger d-block" style="font-size: 14px">{{ $errors->first('jenjang') }}</span>
                    @endif
                </div>

                {{-- Prodi --}}
                <div class="form-group" id="prodi" style="display: none;">
                    <label>Prodi</label>
                    <select class="select2" multiple data-placeholder="Pilih Prodi" style="width: 100%;" name="prodi[]">
                        @foreach ($prodi as $item)
                            <option value="{{ $item->id }}"
                                {{ in_array($item->id, $instrumen->prodi->pluck('id')->toArray()) ? 'selected' : '' }}>
                                {{ $item->nama . ' - ' . $item->jenjang->nama }}</option>
                        @endforeach
                    </select>
                    @if ($errors->has('prodi'))
                        <span class="text-danger d-block" style="font-size: 14px">{{ $errors->first('prodi') }}</span>
                    @endif
                </div>

                {{-- Unit --}}
                <div class="form-group" id="unit" style="display: none;">
                    <label>Unit/Lembaga</label>
                    <select class="select2" multiple data-placeholder="Pilih Unit/Lembaga" style="width: 100%;"
                        name="unit[]">
                        @foreach ($units as $item)
                            <option value="{{ $item->id }}"
                                {{ in_array($item->id, $instrumen->unit->pluck('id')->toArray()) ? 'selected' : '' }}>
                                {{ ucwords($item->nama) }}</option>
                        @endforeach
                    </select>
                    <small class="form-text text-muted">Instrumen ini untuk unit mana</small>
                    @if ($errors->has('unit'))
                        <span class="text-danger d-block" style="font-size: 14px">{{ $errors->first('unit') }}</span>
                    @endif
                </div>

                {{-- jabatan --}}
                <div class="form-group" id="jabatan" style="display: none;">
                    <label>Jabatan</label>
                    <select class="select2" multiple data-placeholder="Pilih Jabatan" style="width: 100%;"
                        name="jabatan[]">
                    </select>
                    <small class="form-text text-muted">Siapa saja yang bisa mengisi pertanyaan ini</small>
                    @if ($errors->has('jabatan'))
                        <span class="text-danger d-block" style="font-size: 14px">{{ $errors->first('jabatan') }}</span>
                    @endif
                </div>

                <!-- /.form-group -->
                <div>
                    <a href="{{ route('instrumen') }}" class="btn btn-outline-secondary">Kembali</a>
                    <x-button-submit text="Edit Instrumen" formId="edit-form" />
                </div>

            </div>
        </form>
    </div>
@endsection

@section('style')
    <!-- Select2 -->
    <link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('dist/css/adminlte.min.css') }}">
@endsection

@section('script')
    @php
        $selectedPasalAyat = $instrumen->pasal
            ->map(function ($pasal) {
                return $pasal->pivot->pasal_id . '___' . $pasal->pivot->ayat_id;
            })
            ->toArray();

        $selectedJabatan = $instrumen->jabatan
            ->map(function ($jabatan) {
                return $jabatan->id;
            })
            ->toArray();
    @endphp
    <!-- Select2 -->
    <script src="{{ asset('plugins/select2/js/select2.full.min.js') }}"></script>
    <script>
        $(function() {
            //Initialize Select2 Elements
            $('.select2').select2()

            //Initialize Select2 Elements
            $('.select2bs4').select2({
                theme: 'bootstrap4'
            })
        });
    </script>

    <script>
        $(document).ready(function() {
            function showLoading(id) {
                $(id).show();
            }

            function hideLoading(id) {
                $(id).hide();
            }

            // Get Standar & Pasal
            $('select[name="peraturan"]').on('change', function() {
                var peraturanId = $(this).val();
                var standarId = @json($instrumen->standar->id);

                var selectedPasalAyat = @json($selectedPasalAyat);
                if (peraturanId) {
                    showLoading('#loading-spinner');
                    showLoading('#loading-spinner-standar');
                    $.ajax({
                        url: "{{ route('instrumen.get_standar_by_peraturan', ':peraturan') }}"
                            .replace(':peraturan', peraturanId),
                        type: "GET",
                        dataType: "json",
                        success: function(data) {
                            $('select[name="standar"]').empty();
                            $.each(data, function(key, value) {
                                $('select[name="standar"]').append(
                                    $('<option>', {
                                        value: key,
                                        text: value,
                                        selected: key == standarId
                                    })
                                );
                            });
                            $('select[name="standar"]').trigger('change.select2');
                            $('select[name="standar"]').trigger('change');
                        },
                        complete: function() {
                            hideLoading('#loading-spinner');
                        }
                    });
                    $.ajax({
                        url: "{{ route('instrumen.get_pasal_by_peraturan', ':peraturan') }}"
                            .replace(':peraturan', peraturanId),
                        type: "GET",
                        dataType: "json",
                        success: function(data) {
                            $('select[name="pasalAyat[]"]').empty();
                            $.each(data, function(key, value) {
                                $('select[name="pasalAyat[]"]').append(
                                    $('<option>', {
                                        value: key,
                                        text: value,
                                        selected: selectedPasalAyat.includes(
                                            key)
                                    })
                                );
                            });
                            $('select[name="pasalAyat[]"]').trigger('change.select2');
                            $('select[name="pasalAyat[]"]').trigger('change');
                        },
                        complete: function() {
                            hideLoading('#loading-spinner-standar');
                        }
                    });
                } else {
                    $('select[name="standar"]').empty();
                    $('select[name="pasalAyat[]"]').empty();
                }
            });

            // Get Kategori
            $('select[name="standar"]').on('change', function() {
                var standarId = $(this).val();
                
                if (standarId) {
                    var kategoriId = @json($instrumen->kategori->id);
                    showLoading('#loading-spinner-kategori');
                    $.ajax({
                        url: "{{ route('instrumen.get_kategori_by_standar', ':standar') }}"
                            .replace(':standar', standarId),
                        type: "GET",
                        dataType: "json",
                        success: function(data) {
                            $('select[name="kategori"]').empty();
                            $.each(data, function(key, value) {
                                $('select[name="kategori"]').append(
                                    $('<option>', {
                                        value: key,
                                        text: value,
                                        selected: key == kategoriId
                                    })
                                );
                            });
                            $('select[name="kategori"]').trigger('change.select2');
                            $('select[name="standar"]').trigger('change');
                        },
                        complete: function() {
                            hideLoading('#loading-spinner-kategori');
                        }
                    });
                } else {
                    $('select[name="kategori"]').empty();
                }
            });

            if ($('select[name="peraturan"]').val()) {
                $('select[name="peraturan"]').trigger('change');
            }


            // get Jabatan by Level
            function getJabatanByLevel(levelId) {
                var selectedJabatan = @json($selectedJabatan);
                
                if (levelId) {
                    showLoading();
                    $.ajax({
                        url: "{{ route('instrumen.get_jabatan_by_level', ':level') }}"
                            .replace(':level', levelId),
                        type: "GET",
                        dataType: "json",
                        success: function(data) {
                            $('select[name="jabatan[]"]').empty();
                            $.each(data, function(key, value) {
                                console.log(key);
                                $('select[name="jabatan[]"]').append(
                                    $('<option>', {
                                        value: key,
                                        text: value,
                                        selected: selectedJabatan.includes(parseInt(
                                            key))
                                    })
                                );
                            });
                            $('select[name="jabatan[]"]').trigger('change.select2');
                        },
                        complete: function() {
                            hideLoading();
                        }
                    });
                } else {
                    $('select[name="jabatan[]"]').empty();
                }
            }

            // Function to check Level and show/hide elements
            function checkLevel() {
                var selectedOption = $('#level option:selected');
                var selectedNama = selectedOption.data('slug');
                var levelId = $('#level').val();

                $('#prodi').hide();
                $('#jenjang').hide();
                $('#jabatan').hide();
                $('#unit').hide();

                if (selectedNama === 'prodi') {
                    $('#prodi').show();
                    $('#jenjang').show();
                } else if (selectedNama === 'fakultas') {
                    $('#jabatan').show();
                    getJabatanByLevel(levelId);
                    $('select[name="prodi[]"]').val(null).trigger('change');;
                    $('select[name="jenjang[]"]').val(null).trigger('change');;
                } else if (selectedNama === 'universitas') {
                    $('#jabatan').show();
                    $('#unit').show();
                    getJabatanByLevel(levelId);
                    $('select[name="prodi[]"]').val(null).trigger('change');;
                    $('select[name="jenjang[]"]').val(null).trigger('change');;
                }
            }

            checkLevel();

            $('#level').on('change', function() {
                checkLevel();
            });
        });
    </script>
@endsection
