@extends('components.layout.main_layout')

@section('content')
    <div class="card card-primary">
        <div class="card-header">
            <h3 class="card-title title-size">{{ $title }}</h3>
        </div>
        <!-- /.card-header -->
        <!-- form start -->
        <form method="POST" action="{{ route('jabatan.update', $jabatan->id) }}" id="edit-form">
            @csrf
            @method('put')
            <div class="card-body">
                {{-- jabatan Input --}}
                <div class="form-group">
                    <label for="nama">Nama Jabatan</label>
                    <span class="text-danger">&#42;</span>
                    <input type="text" name="nama" class="form-control @error('nama') is-invalid @enderror"
                        id="nama" placeholder="Masukkan Nama jabatan" value="{{ $jabatan->nama }}">
                    @if ($errors->has('nama'))
                        <span class="text-danger d-block" style="font-size: 14px">{{ $errors->first('nama') }}</span>
                    @endif
                </div>

                {{-- Type --}}
                <div class="form-group">
                    <label>Tipe Jabatan</label>
                    <span class="text-danger">&#42;</span>
                    <select class="select2" data-placeholder="Pilih Tipe Jabatan" style="width: 100%;" name="type">
                        <option value="prodi" {{ $jabatan->type == 'prodi' ? 'selected' : '' }}>Prodi</option>
                        <option value="fakultas" {{ $jabatan->type == 'fakultas' ? 'selected' : '' }}>Fakultas</option>
                        <option value="universitas" {{ $jabatan->type == 'universitas' ? 'selected' : '' }}>Universitas
                        </option>
                    </select>
                    <small class="form-text text-muted">Jabatan ini ada di tingkat prodi/fakultas/universitas</small>
                    @if ($errors->has('type'))
                        <span class="text-danger d-block" style="font-size: 14px">{{ $errors->first('type') }}</span>
                    @endif
                </div>

                {{-- Unit --}}
                <div class="form-group" id="unit">
                    <label>Unit/Lembaga</label>
                    <span class="text-danger">&#42;</span>
                    <div id="loading-spinner" style="display: none;">
                        <span>Loading...</span>
                    </div>
                    <select class="select2" data-placeholder="Pilih Tipe Jabatan" style="width: 100%;" name="unit">
                    </select>
                    <small class="form-text text-muted">Jabatan ini untuk unit/lembaga mana</small>
                    @if ($errors->has('type'))
                        <span class="text-danger d-block" style="font-size: 14px">{{ $errors->first('type') }}</span>
                    @endif
                </div>

                {{-- unik Input --}}
                <div class="form-group">
                    <label>Unik</label>
                    <span class="text-danger">&#42;</span>
                    <div class="d-flex">
                        <div class="custom-control custom-radio mr-5">
                            <input class="custom-control-input" type="radio" id="aktif" name="unik" value="1"
                                {{ $jabatan->unik == 1 ? 'checked' : '' }}>
                            <label for="aktif" class="custom-control-label">Aktif</label>
                        </div>
                        <div class="custom-control custom-radio">
                            <input class="custom-control-input" type="radio" id="tidak_aktif" name="unik"
                                value="0" {{ $jabatan->unik == 0 ? 'checked' : '' }}>
                            <label for="tidak_aktif" class="custom-control-label">Tidak Aktif</label>
                        </div>
                    </div>
                    <small class="form-text text-muted">Unik artinya jabatan tersebut hanya bisa dimiliki oleh 1
                        orang</small>
                    @if ($errors->has('unik'))
                        <span class="text-danger d-block" style="font-size: 14px">{{ $errors->first('unik') }}</span>
                    @endif
                </div>

                <!-- /.form-group -->
                <div>
                    <a href="{{ route('jabatan') }}" class="btn btn-outline-secondary">Kembali</a>
                    <x-button-submit text="Edit Jabatan" formId="edit-form" />
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
        $selectedJabatan = $jabatan->unit->first() ? $jabatan->unit->first()->id : null;
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

        function showLoading() {
            $('#loading-spinner').show();
        }

        function hideLoading() {
            $('#loading-spinner').hide();
        }

        // Get Unit
        $('select[name="type"]').on('change', function() {
            var typeId = $(this).val();
            var selectedJabatan = @json($selectedJabatan);
            if (typeId === 'universitas') {
                showLoading();
                $.ajax({
                    url: "{{ route('jabatan.get_unit_by_type', ':type') }}".replace(':type', typeId),
                    type: "GET",
                    dataType: "json",
                    success: function(data) {
                        $('select[name="unit"]').empty();
                        $.each(data, function(key, value) {
                            $('select[name="unit"]').append(
                                $('<option>', {
                                    value: key,
                                    text: value,
                                    selected: key == selectedJabatan
                                })
                            );
                        });
                        $('select[name="unit"]').trigger('change.select2');
                        $('select[name="unit"]').trigger('change');
                    },
                    complete: function() {
                        hideLoading();
                    }
                });
            } else {
                $('select[name="unit"]').empty();
            }
        });

        if ($('select[name="type"]').val()) {
            $('select[name="type"]').trigger('change');
        }

        function checkType() {
            var selectedOption = $('select[name="type"] option:selected')
            var type = selectedOption.val();

            $('#unit').hide();

            if (type == 'universitas') {
                $('#unit').show();
            } else {
                $('#unit').hide();
            }
        }

        $(document).ready(function() {
            checkType();

            $('select[name="type"]').change(function() {
                checkType();
            });
        });
    </script>
@endsection
