@extends('components.layout.main_layout')

@section('content')
    <div class="card card-primary">
        <div class="card-header">
            <h3 class="card-title title-size">{{ $title }}</h3>
        </div>
        <!-- /.card-header -->
        <!-- form start -->
        <form method="POST" action="{{ route('standar.store') }}" id="create-form">
            @csrf
            <div class="card-body">
                {{-- Standar Input --}}
                <div class="form-group">
                    <label for="nama">Standar</label>
                    <span class="text-danger">&#42;</span>
                    <input type="text" name="nama" class="form-control @error('nama') is-invalid @enderror"
                        id="nama" placeholder="Masukkan Standar">
                    @if ($errors->has('nama'))
                        <span class="text-danger d-block" style="font-size: 14px">{{ $errors->first('nama') }}</span>
                    @endif
                </div>

                {{-- Peraturan --}}
                <div class="form-group">
                    <label for="peraturan">Peraturan</label>
                    <span class="text-danger">&#42;</span>
                    <select class="select2" data-placeholder="Pilih Peraturan" style="width: 100%;" name="peraturan">
                        <option disabled selected></option>
                        @foreach ($peraturan as $item)
                            <option value="{{ $item->id }}">{{ $item->nama }}</option>
                        @endforeach
                    </select>
                    <small class="form-text text-muted">Jika peraturan kosong silahkan tambah peraturan terlebih
                        dahulu</small>
                    @if ($errors->has('peraturan'))
                        <span class="text-danger d-block" style="font-size: 14px">{{ $errors->first('peraturan') }}</span>
                    @endif
                </div>

                {{-- Kode --}}
                <div class="form-group">
                    <label for="kode">Kode</label>
                    <span class="text-danger">&#42;</span>
                    <input type="text" name="kode" class="form-control @error('kode') is-invalid @enderror"
                        id="kode" placeholder="Masukkan Kode">
                    @if ($errors->has('kode'))
                        <span class="text-danger d-block" style="font-size: 14px">{{ $errors->first('kode') }}</span>
                    @endif
                </div>

                <!-- /.form-group -->
                <div>
                    <a href="{{ route('standar') }}" class="btn btn-outline-secondary">Kembali</a>
                    <x-button-submit text="Tambah Standar" formId="create-form" />
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
            // Initialize Select2 Elements
            $('.select2').select2();

            // Initialize Select2 Elements with Bootstrap 4 theme
            $('.select2bs4').select2({
                theme: 'bootstrap4'
            });
        });
    </script>
@endsection
