@extends('components.layout.main_layout')

@section('content')
    <div class="card card-primary">
        <div class="card-header">
            <h3 class="card-title title-size">{{ $title }}</h3>
        </div>
        <!-- /.card-header -->
        <!-- form start -->
        <form method="POST" action="{{ route('prodi.store') }}" id="create-form">
            @csrf
            <div class="card-body">
                {{-- Prodi Input --}}
                <div class="form-group">
                    <label for="nama">Nama Prodi</label>
                    <span class="text-danger">&#42;</span>
                    <input type="text" name="nama" class="form-control @error('nama') is-invalid @enderror"
                        id="nama" placeholder="Masukkan Nama Prodi">
                    @if ($errors->has('nama'))
                        <span class="text-danger d-block" style="font-size: 14px">{{ $errors->first('nama') }}</span>
                    @endif
                </div>

                {{-- Fakultas --}}
                <div class="form-group">
                    <label>Fakultas</label>
                    <span class="text-danger">&#42;</span>
                    <select class="select2" data-placeholder="Pilih Fakultas" style="width: 100%;" name="fakultas">
                        <option disabled selected>Pilih Fakultas</option>
                        @foreach ($fakultas as $fak)
                            <option value="{{ $fak->id }}">{{ $fak->nama }}</option>
                        @endforeach
                    </select>
                    @if ($errors->has('fakultas'))
                        <span class="text-danger d-block" style="font-size: 14px">{{ $errors->first('fakultas') }}</span>
                    @endif
                </div>

                {{-- Jenjang --}}
                <div class="form-group">
                    <label>Jenjang</label>
                    <span class="text-danger">&#42;</span>
                    <select class="select2" data-placeholder="Pilih Jenjang" style="width: 100%;" name="jenjang">
                        <option disabled selected>Pilih Jenjang</option>
                        @foreach ($jenjang as $item)
                            <option value="{{ $item->id }}">{{ $item->nama }}</option>
                        @endforeach
                    </select>
                    @if ($errors->has('jenjang'))
                        <span class="text-danger d-block" style="font-size: 14px">{{ $errors->first('jenjang') }}</span>
                    @endif
                </div>

                <!-- /.form-group -->
                <div>
                    <a href="{{ route('prodi') }}" class="btn btn-outline-secondary">Kembali</a>
                    <x-button-submit text="Tambah Prodi" formId="create-form" />
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
    <script src="../../plugins/select2/js/select2.full.min.js"></script>
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
@endsection
