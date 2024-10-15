@extends('components.layout.main_layout')

@section('content')
    <div class="card card-primary">
        <div class="card-header">
            <h3 class="card-title title-size">{{ $title }}</h3>
        </div>
        <!-- /.card-header -->
        <!-- form start -->
        <form method="POST" action="{{ route('kategori.update', $kategori->id) }}" id="create-form">
            @csrf
            @method('put')
            <div class="card-body">
                {{-- kategori Input --}}
                <div class="form-group">
                    <label for="nama">Kategori</label>
                    <span class="text-danger">&#42;</span>
                    <input type="text" name="nama" class="form-control @error('nama') is-invalid @enderror"
                        id="nama" placeholder="Masukkan Kategori" value="{{ $kategori->nama }}">
                    @if ($errors->has('nama'))
                        <span class="text-danger d-block" style="font-size: 14px">{{ $errors->first('nama') }}</span>
                    @endif
                </div>

                {{-- standar --}}
                <div class="form-group">
                    <label>Standar</label>
                    <span class="text-danger">&#42;</span>
                    <select class="select2" data-placeholder="Pilih standar" style="width: 100%;" name="standar">
                        <option disabled selected></option>
                        @foreach ($standar as $item)
                            <option value="{{ $item->id }}"
                                {{ $kategori->standar->id === $item->id ? 'selected' : '' }}>{{ $item->nama }}</option>
                        @endforeach
                    </select>
                    <small class="form-text text-muted">Jika standar kosong silahkan tambah standar terlebih
                        dahulu</small>
                    @if ($errors->has('standar'))
                        <span class="text-danger d-block" style="font-size: 14px">{{ $errors->first('standar') }}</span>
                    @endif
                </div>

                {{-- Kode --}}
                <div class="form-group">
                    <label for="kode">Kode</label>
                    <span class="text-danger">&#42;</span>
                    <input type="text" name="kode" class="form-control @error('kode') is-invalid @enderror"
                        id="kode" placeholder="Masukkan Kode" value="{{ $kategori->kode }}">
                    @if ($errors->has('kode'))
                        <span class="text-danger d-block" style="font-size: 14px">{{ $errors->first('kode') }}</span>
                    @endif
                </div>

                <!-- /.form-group -->
                <div>
                    <a href="{{ route('standar.show', $kategori->standar->id) }}" class="btn btn-outline-secondary">Kembali</a>
                    <x-button-submit text="Edit Kategori" formId="create-form" />
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
    <!-- summernote -->
    {{-- <link rel="stylesheet" href="{{ asset('plugins/summernote/summernote-bs4.min.css') }}"> --}}
@endsection

@section('script')
    <!-- Select2 -->
    <script src="{{ asset('plugins/select2/js/select2.full.min.js') }}"></script>
    <!-- Summernote -->
    {{-- <script src="{{ asset('plugins/summernote/summernote-bs4.min.js') }}"></script> --}}
    <script>
        $(function() {
            //Initialize Select2 Elements
            $('.select2').select2()

            //Initialize Select2 Elements
            $('.select2bs4').select2({
                theme: 'bootstrap4'
            })
            // Summernote
            // $('#summernote').summernote()
        });
    </script>
@endsection
