@extends('components.layout.main_layout')

@section('content')
    <div class="card card-primary">
        <div class="card-header">
            <h3 class="card-title title-size">{{ $title }}</h3>
        </div>
        <!-- /.card-header -->
        <!-- form start -->
        <form method="POST" action="{{ route('instrumen.store') }}" id="create-form">
            @csrf
            <div class="card-body">

                {{-- peraturan --}}
                <div class="form-group">
                    <label>Peraturan</label>
                    <span class="text-danger">&#42;</span>
                    <select class="select2" data-placeholder="Pilih Peraturan" style="width: 100%;" name="peraturan">
                        <option disabled selected></option>
                        @foreach ($peraturan as $item)
                            <option value="{{ $item->id }}" {{ old('peraturan') == $item->id ? 'selected' : '' }}>
                                {{ $item->nama }}</option>
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
                                {{ old('jenis_pertanyaan') == $item->id ? 'selected' : '' }}>{{ ucwords($item->nama) }}
                            </option>
                        @endforeach
                    </select>
                    @if ($errors->has('jenis_pertanyaan'))
                        <span class="text-danger d-block"
                            style="font-size: 14px">{{ $errors->first('jenis_pertanyaan') }}</span>
                    @endif
                </div>

                {{-- Kode --}}
                <div class="form-group">
                    <div class="d-flex items-center">
                        <label>Kode</label>
                    </div>
                    <div class="row">
                        <div class="col">
                            <div class="row">
                                <div class="col">
                                    <input type="text" name="kode_huruf" class="form-control"
                                        value="{{ old('kode_huruf') }}" placeholder="Masukkan kode huruf">
                                </div>
                                <div class="col">
                                    <input type="number" name="kode_angka" class="form-control"
                                        value="{{ old('kode_angka') }}" placeholder="Masukkan angka">
                                </div>
                            </div>
                        </div>
                        <div class="col d-flex align-items-center">
                            <div id="loading-spinner-kode" style="display: none;">
                                <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                Loading...
                            </div>
                            <span id="kode"></span>
                        </div>
                    </div>
                    <small class="form-text text-muted">Opsional tidak harus diisi</small>
                    <small class="form-text text-muted">Isi kolom kode jika ingin menambahkan instrumen dengan kode yang
                        sama atau kode yang berbeda</small>
                    @if ($errors->has('kode_huruf'))
                        <span class="text-danger d-block" style="font-size: 14px">{{ $errors->first('kode_huruf') }}</span>
                    @endif
                    @if ($errors->has('kode_angka'))
                        <span class="text-danger d-block"
                            style="font-size: 14px">{{ $errors->first('kode_angka') }}</span>
                    @endif
                </div>

                {{-- pernyataan --}}
                <div class="form-group">
                    <label for="pernyataan">Pernyataan</label>
                    <span class="text-danger">&#42;</span>
                    <textarea name="pernyataan" id="pernyataan" cols="30" rows="3"
                        class="form-control @error('pernyataan') is-invalid @enderror" placeholder="Masukkan Pernyataan">{{ old('pernyataan') }}</textarea>
                    @if ($errors->has('pernyataan'))
                        <span class="text-danger d-block"
                            style="font-size: 14px">{{ $errors->first('pernyataan') }}</span>
                    @endif
                </div>

                {{-- indikator --}}
                <div class="form-group">
                    <label for="indikator">Indikator</label>
                    <span class="text-danger">&#42;</span>
                    <textarea name="indikator" id="indikator" cols="30" rows="3"
                        class="form-control @error('indikator') is-invalid @enderror" placeholder="Masukkan Indikator">{{ old('indikator') }}</textarea>
                    @if ($errors->has('indikator'))
                        <span class="text-danger d-block"
                            style="font-size: 14px">{{ $errors->first('indikator') }}</span>
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
                            placeholder="Masukkan Kriteria {{ $item->nama }}">{{ old('kriteria.' . $item->nama) }}</textarea>
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
                    <select id="level" class="select2" data-placeholder="Pilih Level" style="width: 100%;"
                        name="level">
                        <option disabled selected></option>
                        @foreach ($level as $item)
                            <option value="{{ $item->id }}" data-slug={{ $item->slug }}
                                {{ old('level') == $item->id ? 'selected' : '' }}>{{ $item->nama }}
                            </option>
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
                            <option value="{{ $item->id }}">{{ ucwords($item->nama) }}</option>
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
                            <option value="{{ $item->id }}">{{ $item->nama . ' - ' . $item->jenjang->nama }}</option>
                        @endforeach
                    </select>
                    <small class="form-text text-muted">Pilih prodi jika instrumen ini untuk prodi tertentu</small>
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
                            <option value="{{ $item->id }}">{{ ucwords($item->nama) }}</option>
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
                    <x-button-submit text="Tambah Instrumen" formId="create-form" />
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
            // Old Pasal dan Ayat, Standar, Kategori, Peraturan
            var oldPasalAyat = @json(old('pasalAyat'));
            var oldStandar = "{{ old('standar') }}";
            var oldKategori = "{{ old('kategori') }}";
            var oldPeraturan = "{{ old('peraturan') }}";
            if (oldPeraturan) {
                loadPasaldanStandar(oldPeraturan);
            }

            function showLoading(id) {
                $(id).show();
            }

            function hideLoading(id) {
                $(id).hide();
            }

            function loadPasaldanStandar(peraturanId) {
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

                            if (oldStandar) {
                                $.each(data, function(key, value) {
                                    $('select[name="standar"]').append(
                                        $('<option>', {
                                            value: key,
                                            text: value,
                                            selected: key == oldStandar
                                        })
                                    );
                                });
                            } else {
                                $.each(data, function(key, value) {
                                    $('select[name="standar"]').append('<option value="' +
                                        key + '">' + value + '</option>');
                                });
                            }
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

                            if (oldPasalAyat) {
                                $.each(data, function(key, value) {
                                    $('select[name="pasalAyat[]"]').append(
                                        $('<option>', {
                                            value: key,
                                            text: value,
                                            selected: oldPasalAyat
                                                .includes(
                                                    key)
                                        })
                                    );
                                });
                            } else {
                                $.each(data, function(key, value) {
                                    $('select[name="pasalAyat[]"]').append(
                                        '<option value="' +
                                        key + '">' + value + '</option>');
                                });
                            }
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
            }

            function loadKategori(standarId) {
                if (standarId) {
                    showLoading('#loading-spinner-kategori');
                    $.ajax({
                        url: "{{ route('instrumen.get_kategori_by_standar', ':standar') }}"
                            .replace(':standar', standarId),
                        type: "GET",
                        dataType: "json",
                        success: function(data) {
                            $('select[name="kategori"]').empty();

                            if (oldKategori) {
                                $.each(data, function(key, value) {
                                    $('select[name="kategori"]').append(
                                        $('<option>', {
                                            value: key,
                                            text: value,
                                            selected: key == oldKategori
                                        })
                                    );
                                });
                            } else {
                                $.each(data, function(key, value) {
                                    $('select[name="kategori"]').append('<option value="' +
                                        key + '">' + value + '</option>');
                                });
                            }
                            $('select[name="kategori"]').trigger('change.select2');
                            $('select[name="kategori"]').trigger('change');
                        },
                        complete: function() {
                            hideLoading('#loading-spinner-kategori');
                        }
                    });
                } else {
                    $('select[name="kategori"]').empty();
                }
            }

            // Get Standar & Pasal
            $('select[name="peraturan"]').on('change', function() {
                var peraturanId = $(this).val();

                loadPasaldanStandar(peraturanId);
            });

            // Get Kategori
            $('select[name="standar"]').on('change', function() {
                var standarId = $(this).val();

                loadKategori(standarId);
            });

            // Get Kode
            $('select[name="kategori"]').on('change', function() {
                var standarId = $('select[name="standar"]').val();
                var kategoriId = $(this).val();
                if (kategoriId) {
                    showLoading('#loading-spinner-kode');
                    $.ajax({
                        url: "{{ route('instrumen.get_kode', ['standar' => ':standar', 'kategori' => ':kategori']) }}"
                            .replace(':standar', standarId).replace(':kategori', kategoriId),
                        type: "GET",
                        dataType: "json",
                        success: function(data) {
                            $('#kode').text("Kode instrumen sebelumnya : " + data);
                        },
                        complete: function() {
                            hideLoading('#loading-spinner-kode');
                        }
                    });
                } else {
                    $('#kode').text('');
                }
            });

            // Get Jabatan by Level
            function getJabatanByLevel(levelId) {
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
                                $('select[name="jabatan[]"]').append(
                                    '<option value="' + key + '">' + value + '</option>'
                                );
                            });
                            $('select[name="jabatan[]"]').trigger('change.select2');
                            $('select[name="jabatan[]"]').trigger('change');
                        },
                        complete: function() {
                            hideLoading();
                        }
                    });
                } else {
                    $('select[name="jabatan[]"]').empty();
                }
            }

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
                } else if (selectedNama === 'universitas') {
                    $('#jabatan').show();
                    $('#unit').show();
                    getJabatanByLevel(levelId);
                }
            }

            $(document).ready(function() {
                checkLevel();
            });

            $('#level').change(function() {
                checkLevel();
            });


        });
    </script>
@endsection
