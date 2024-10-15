@extends('components.layout.main_layout')

@section('content')
    <div class="card card-primary">
        <div class="card-header">
            <h3 class="card-title title-size">{{ $title }}</h3>
        </div>
        <!-- /.card-header -->
        <!-- form start -->
        <form method="POST" action="{{ route('peraturan.store') }}" id="create-form">
            @csrf
            <div class="card-body">
                {{-- peraturan Input --}}
                <div class="form-group">
                    <label for="nama">Peraturan</label>
                    <span class="text-danger">&#42;</span>
                    <input type="text" name="nama" class="form-control @error('nama') is-invalid @enderror"
                        id="nama" placeholder="Masukkan Peraturan">
                    @if ($errors->has('nama'))
                        <span class="text-danger d-block" style="font-size: 14px">{{ $errors->first('nama') }}</span>
                    @endif
                </div>
                <div class="form-group">
                    <label for="tahun">Tahun</label>
                    <span class="text-danger">&#42;</span>
                    <input type="number" name="tahun" class="form-control @error('tahun') is-invalid @enderror"
                        id="tahun" placeholder="Masukkan Tahun">
                    @if ($errors->has('tahun'))
                        <span class="text-danger d-block" style="font-size: 14px">{{ $errors->first('tahun') }}</span>
                    @endif
                </div>
                {{-- Status Input --}}
                <div class="form-group">
                    <label>Status</label>
                    <span class="text-danger">&#42;</span>
                    <div class="d-flex">
                        <div class="custom-control custom-radio mr-5">
                            <input class="custom-control-input" type="radio" id="aktif" name="status" value="1">
                            <label for="aktif" class="custom-control-label">Aktif</label>
                        </div>
                        <div class="custom-control custom-radio">
                            <input class="custom-control-input" type="radio" id="tidak_aktif" name="status"
                                value="0">
                            <label for="tidak_aktif" class="custom-control-label">Tidak Aktif</label>
                        </div>
                    </div>
                    @if ($errors->has('status'))
                        <span class="text-danger d-block" style="font-size: 14px">{{ $errors->first('status') }}</span>
                    @endif
                </div>

                <!-- /.form-group -->
                <div>
                    <a href="{{ route('peraturan') }}" class="btn btn-outline-secondary">Kembali</a>
                    <x-button-submit text="Tambah Peraturan" formId="create-form" />
                </div>
            </div>
        </form>

    </div>
@endsection

@section('style')
    <!-- Select2 -->
    <link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
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
