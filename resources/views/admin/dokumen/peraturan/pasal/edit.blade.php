@extends('components.layout.main_layout')

@section('content')
    <div class="card card-primary">
        <div class="card-header">
            <h3 class="card-title title-size">{{ $title }}</h3>
        </div>
        <!-- /.card-header -->
        <!-- form start -->
        <form method="POST" action="{{ route('pasal.update', $pasal->id) }}" id="create-form">
            @csrf
            @method('put')
            <div class="card-body">
                {{-- Peraturan --}}
                <div class="form-group">
                    <label>Peraturan</label>
                    <span class="text-danger">&#42;</span>
                    <select class="select2" data-placeholder="Pilih Peraturan" style="width: 100%;" name="peraturan">
                        <option disabled selected></option>
                        @foreach ($peraturan as $item)
                            <option value="{{ $item->id }}" {{ $pasal->peraturan->id == $item->id ? 'selected' : '' }}>
                                {{ $item->nama }}</option>
                        @endforeach
                    </select>
                    <small class="form-text text-muted">Jika peraturan kosong silahkan tambah peraturan terlebih
                        dahulu</small>
                    @if ($errors->has('peraturan'))
                        <span class="text-danger d-block" style="font-size: 14px">{{ $errors->first('peraturan') }}</span>
                    @endif
                </div>

                {{-- pasal Input --}}
                <div class="form-group">
                    <label for="pasal">Pasal</label>
                    <span class="text-danger">&#42;</span>
                    <input type="number" name="pasal" class="form-control @error('pasal') is-invalid @enderror"
                        id="pasal" placeholder="Masukkan Pasal" value="{{ $pasal->pasal }}">
                    @if ($errors->has('pasal'))
                        <span class="text-danger d-block" style="font-size: 14px">{{ $errors->first('pasal') }}</span>
                    @endif
                </div>
                {{-- Isi Input --}}
                <div class="form-group">
                    <label for="isi">Isi</label>
                    <span class="text-danger">&#42;</span>
                    <textarea name="isi" cols="30" rows="10" class="form-control @error('isi') is-invalid @enderror"
                        id="isi" placeholder="Masukkan isi">{{ $pasal->isi }}</textarea>
                    @if ($errors->has('isi'))
                        <span class="text-danger d-block" style="font-size: 14px">{{ $errors->first('isi') }}</span>
                    @endif
                </div>

                <!-- /.form-group -->
                <div>
                    <a href="{{ route('peraturan') }}" class="btn btn-outline-secondary">Kembali</a>
                    <x-button-submit text="Edit Pasal" formId="create-form" />
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
@endsection
