@extends('components.layout.main_layout')

@section('content')
    <div class="card card-primary">
        <div class="card-header">
            <h3 class="card-title title-size">{{ $title }}</h3>
        </div>
        <!-- /.card-header -->
        <!-- form start -->
        <form method="POST" action="{{ route('user.update', $user->id) }}" id="create-form">
            @csrf
            @method('put')
            <div class="card-body">
                {{-- user Input --}}
                <div class="form-group">
                    <label for="name">Nama User</label>
                    <span class="text-danger">&#42;</span>
                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                        value="{{ $user->name }}" id="name" placeholder="Masukkan Nama User">
                    @if ($errors->has('name'))
                        <span class="text-danger d-block" style="font-size: 14px">{{ $errors->first('name') }}</span>
                    @endif
                </div>
                {{-- email --}}
                <div class="form-group">
                    <label for="email">Email User</label>
                    <span class="text-danger">&#42;</span>
                    <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                        value="{{ $user->email }}" id="email" placeholder="Masukkan Email User">
                    @if ($errors->has('email'))
                        <span class="text-danger d-block" style="font-size: 14px">{{ $errors->first('email') }}</span>
                    @endif
                </div>
                {{-- Password --}}
                <div class="form-group">
                    <label for="password">Password</label>
                    <span class="text-danger">&#42;</span>
                    <input type="password" name="password" class="form-control @error('password') is-invalid @enderror"
                        value="" id="password" placeholder="Masukkan Password">
                    @if ($errors->has('password'))
                        <span class="text-danger d-block" style="font-size: 14px">{{ $errors->first('password') }}</span>
                    @endif
                </div>

                {{-- Role --}}
                <div class="form-group">
                    <label>Role</label>
                    <span class="text-danger">&#42;</span>
                    <select class="select2" data-placeholder="Pilih Role" style="width: 100%;" name="role[]" id="role"
                        multiple>
                        @foreach ($roles as $role)
                            @if ($user->roles->isNotEmpty())
                                <option value="{{ $role->id }}"
                                    {{ in_array($role->id, $user->roles->pluck('id')->toArray()) ? 'selected' : '' }}>
                                    {{ strtoupper($role->name) }}</option>
                            @else
                                <option value="{{ $role->id }}">
                                    {{ strtoupper($role->name) }}</option>
                            @endif
                        @endforeach
                    </select>
                    <small class="form-text text-muted">Pilih role untuk user</small>
                    @if ($errors->has('jabatan'))
                        <span class="text-danger d-block" style="font-size: 14px">{{ $errors->first('jabatan') }}</span>
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
                            @if ($user->jabatan->isNotEmpty())
                                <option value="{{ $jabatan->id }}"
                                    {{ $user->jabatan[0]->id == $jabatan->id ? 'selected' : '' }}
                                    data-type="{{ $jabatan->type }}">
                                    {{ $jabatan->nama }}</option>
                            @else
                                <option value="{{ $jabatan->id }}" data-type="{{ $jabatan->type }}">
                                    {{ $jabatan->nama }}</option>
                            @endif
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
                            @if ($user->jabatan->isNotEmpty() && $user->jabatan[0]->pivot->fakultas_id != null)
                                <option value="{{ $fak->id }}"
                                    {{ $user->jabatan[0]->pivot->fakultas_id == $fak->id ? 'selected' : '' }}>
                                    {{ $fak->nama }}</option>
                            @else
                                <option value="{{ $fak->id }}">{{ $fak->nama }}</option>
                            @endif
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
                            @if ($user->jabatan->isNotEmpty() && $user->jabatan[0]->pivot->prodi_id != null)
                                <option value="{{ $prodi->id }}"
                                    {{ $user->jabatan[0]->pivot->prodi_id == $prodi->id ? 'selected' : '' }}>
                                    {{ $prodi->nama . ' ' . $prodi->jenjang->nama }}</option>
                            @else
                                <option value="{{ $prodi->id }}">{{ $prodi->nama . ' ' . $prodi->jenjang->nama }}
                                </option>
                            @endif
                        @endforeach
                    </select>
                    <small class="form-text text-muted">Pilih fakultas terlebih dahulu</small>
                    @if ($errors->has('prodi'))
                        <span class="text-danger d-block" style="font-size: 14px">{{ $errors->first('prodi') }}</span>
                    @endif
                </div>

                {{-- Unit --}}
                <div class="form-group" id="unit-group" style="display: none;">
                    <label>Unit</label>
                    <span class="text-danger">&#42;</span>
                    <div id="loading-spinner" style="display: none;">
                        <span>Loading...</span>
                    </div>
                    <select class="select2" data-placeholder="Pilih Unit" style="width: 100%;" name="unit"
                        id="unit">
                        <option disabled selected></option>
                        {{-- @foreach ($units as $unit)
                            @if ($user->jabatan->isNotEmpty() && $user->jabatan[0]->pivot->unit_id != null)
                                <option value="{{ $unit->id }}"
                                    {{ $user->jabatan[0]->pivot->unit_id == $unit->id ? 'selected' : '' }}>
                                    {{ $unit->nama }}</option>
                            @else
                                <option value="{{ $unit->id }}">{{ $unit->nama }}</option>
                            @endif
                        @endforeach --}}
                    </select>
                    {{-- <small class="form-text text-muted">Pilih fakultas terlebih dahulu</small> --}}
                    @if ($errors->has('unit'))
                        <span class="text-danger d-block" style="font-size: 14px">{{ $errors->first('unit') }}</span>
                    @endif
                </div>

                <!-- /.form-group -->
                <div>
                    <a href="{{ route('user') }}" class="btn btn-outline-secondary">Kembali</a>
                    <x-button-submit text="Edit User" formId="create-form" />
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
            function checkJabatan() {
                var jabatanType = $('#jabatan option:selected').data('type');
                var jabatanId = $('#jabatan').val();
                var fakultasGroup = $('#fakultas-group');
                var prodiGroup = $('#prodi-group');
                var unitGroup = $('#unit-group');

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

            $('#jabatan').change(checkJabatan);

            checkJabatan();
        });

        function showLoading() {
            $('#loading-spinner').show();
        }

        function hideLoading() {
            $('#loading-spinner').hide();
        }
    </script>
@endsection
