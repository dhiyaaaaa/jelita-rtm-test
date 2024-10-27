@extends('components.layout.main_layout')

@section('content')
    <div class="card card-primary">
        <div class="card-header">
            <h3 class="card-title title-size">{{ $title }}</h3>
        </div>
        <!-- /.card-header -->
        <!-- form start -->
        <form method="POST" action="{{ route('user.store') }}" id="create-form">
            @csrf
            <div class="card-body">
                {{-- user Input --}}
                <div class="form-group">
                    <label for="name">Nama User</label>
                    <span class="text-danger">&#42;</span>
                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                        id="name" placeholder="Masukkan Nama User" value="{{ old('name') }}">
                    @if ($errors->has('name'))
                        <span class="text-danger d-block" style="font-size: 14px">{{ $errors->first('name') }}</span>
                    @endif
                </div>
                {{-- email --}}
                <div class="form-group">
                    <label for="email">Email User</label>
                    <span class="text-danger">&#42;</span>
                    <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                        id="email" placeholder="Masukkan Email User" value="{{ old('email') }}">
                    @if ($errors->has('email'))
                        <span class="text-danger d-block" style="font-size: 14px">{{ $errors->first('email') }}</span>
                    @endif
                </div>
                {{-- Password --}}
                <div class="form-group">
                    <label for="password">Password</label>
                    <span class="text-danger">&#42;</span>
                    <input type="password" name="password" class="form-control @error('password') is-invalid @enderror"
                        id="password" placeholder="Masukkan Password">
                    @if ($errors->has('password'))
                        <span class="text-danger d-block" style="font-size: 14px">{{ $errors->first('password') }}</span>
                    @endif
                </div>

                {{-- Role --}}
                <div class="form-group">
                    <label>Role</label>
                    {{-- <span class="text-danger">&#42;</span> --}}
                    <select class="select2" data-placeholder="Pilih Role" style="width: 100%;" name="role[]" id="role"
                        multiple>
                        @foreach ($roles as $role)
                            <option value="{{ $role->id }}" {{ old('role') == $role->id ? 'selected' : '' }}>
                                {{ strtoupper($role->name) }}</option>
                        @endforeach
                    </select>
                    <small class="form-text text-muted">Pilih role untuk user</small>
                    @if ($errors->has('role'))
                        <span class="text-danger d-block" style="font-size: 14px">{{ $errors->first('role') }}</span>
                    @endif
                </div>

                {{-- Jabatan --}}
                <div class="form-group">
                    <label>Jabatan</label>
                    <span class="text-danger">&#42;</span>
                    <select class="select2" data-placeholder="Pilih Jabatan" style="width: 100%;" name="jabatan"
                        id="jabatan">
                        <option disabled selected></option>
                        @foreach ($jabatans as $jabatan)
                            <option value="{{ $jabatan->id }}" data-type="{{ $jabatan->type }}"
                                {{ old('jabatan') == $jabatan->id ? 'selected' : '' }}>
                                {{ $jabatan->nama }}</option>
                        @endforeach
                    </select>
                    @if ($errors->has('jabatan'))
                        <span class="text-danger d-block" style="font-size: 14px">{{ $errors->first('jabatan') }}</span>
                    @endif
                </div>

                {{-- Fakultas --}}
                <div class="form-group" id="fakultas-group" style="display: none;">
                    <label>Fakultas</label>
                    <span class="text-danger">&#42;</span>
                    <select class="select2" data-placeholder="Pilih Fakultas" style="width: 100%;" name="fakultas"
                        id="fakultas">
                        <option disabled selected></option>
                        @foreach ($fakultas as $fak)
                            <option value="{{ $fak->id }}">{{ $fak->nama }}</option>
                        @endforeach
                    </select>
                    @if ($errors->has('fakultas'))
                        <span class="text-danger d-block" style="font-size: 14px">{{ $errors->first('fakultas') }}</span>
                    @endif
                </div>

                {{-- Prodi --}}
                <div class="form-group" id="prodi-group" style="display: none;">
                    <label>Prodi</label>
                    <span class="text-danger">&#42;</span>
                    <select class="select2" data-placeholder="Pilih Prodi" style="width: 100%;" name="prodi"
                        id="prodi">
                        <option disabled selected></option>
                        @foreach ($prodis as $prodi)
                            <option value="{{ $prodi->id }}">{{ $prodi->nama . ' ' . $prodi->jenjang->nama }}</option>
                        @endforeach
                    </select>
                    {{-- <small class="form-text text-muted">Pilih fakultas terlebih dahulu</small> --}}
                    @if ($errors->has('prodi'))
                        <span class="text-danger d-block" style="font-size: 14px">{{ $errors->first('prodi') }}</span>
                    @endif
                </div>

                {{-- Unit --}}
                <div class="form-group" id="unit-group" style="display: none;">
                    <label>Unit</label>
                    <span class="text-danger">&#42;</span>
                    <span id="loading-spinner" style="display: none;">
                        <span>Loading...</span>
                    </span>
                    <select class="select2" data-placeholder="Pilih Unit" style="width: 100%;" name="unit"
                        id="unit">
                    </select>
                    @if ($errors->has('unit'))
                        <span class="text-danger d-block" style="font-size: 14px">{{ $errors->first('unit') }}</span>
                    @endif
                </div>

                <!-- /.form-group -->
                <div>
                    <a href="{{ route('user') }}" class="btn btn-outline-secondary">Kembali</a>
                    <x-button-submit text="Tambah User" formId="create-form" />
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
            var fakultasGroup = $('#fakultas-group');
            var prodiGroup = $('#prodi-group');
            var unitGroup = $('#unit-group');

            // Old Jabatan
            var oldJabatan = "{{ old('jabatan') }}";

            if (oldJabatan) {
                var jabatanType = $(this).find('option:selected').data('type');
                loadUnit(jabatanType, oldJabatan);
            }

            function loadUnit(jabatanType, jabatanId) {
                if (jabatanType === 'prodi') {
                    prodiGroup.show();
                    fakultasGroup.hide();
                    unitGroup.hide();
                } else if (jabatanType === 'fakultas') {
                    fakultasGroup.show();
                    prodiGroup.hide();
                    unitGroup.hide();
                } else if (jabatanType === 'universitas') {
                    fakultasGroup.hide();
                    prodiGroup.hide();
                    unitGroup.show();
                    showLoading();
                    $.ajax({
                        url: "{{ route('user.get_unit_by_jabatan', ':jabatan') }}".replace(
                            ':jabatan', jabatanId),
                        type: "GET",
                        dataType: "json",
                        success: function(data) {
                            $('select[name="unit"]').empty();
                            $.each(data, function(key, value) {
                                $('select[name="unit"]').append(
                                    '<option value="' +
                                    key + '">' + value + '</option>'
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
                    fakultasGroup.hide();
                    prodiGroup.hide();
                    unitGroup.hide();
                }
            }

            $('#jabatan').change(function() {
                var jabatanType = $(this).find('option:selected').data('type');
                var jabatanId = $(this).val();

                loadUnit(jabatanType, jabatanId);
            });
        });

        function showLoading() {
            $('#loading-spinner').show();
        }

        function hideLoading() {
            $('#loading-spinner').hide();
        }
    </script>
@endsection
