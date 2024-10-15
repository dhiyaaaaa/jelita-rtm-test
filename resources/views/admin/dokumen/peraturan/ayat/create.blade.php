@extends('components.layout.main_layout')

@section('content')
    <div class="card card-primary">
        <div class="card-header">
            <h3 class="card-title title-size">{{ $title }}</h3>
        </div>
        <!-- /.card-header -->
        <!-- form start -->
        <form method="POST" action="{{ route('ayat.store') }}" id="create-form">
            @csrf
            <div class="card-body">
                {{-- Peraturan --}}
                <div class="form-group">
                    <label>Peraturan</label>
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

                {{-- Pasal --}}
                <div class="form-group">
                    <label>Pasal</label>
                    <span class="text-danger">&#42;</span>
                    <div id="loading-spinner" style="display: none;">
                        <span>Loading...</span>
                    </div>
                    <select class="select2" data-placeholder="Pilih Pasal" style="width: 100%;" name="pasal">
                    </select>
                    <small class="form-text text-muted">Jika pasal kosong silahkan tambah pasal terlebih
                        dahulu</small>
                    @if ($errors->has('pasal'))
                        <span class="text-danger d-block" style="font-size: 14px">{{ $errors->first('pasal') }}</span>
                    @endif
                </div>

                {{-- ayat Input --}}
                <div class="form-group">
                    <label for="ayat">Ayat</label>
                    <span class="text-danger">&#42;</span>
                    <input type="number" name="ayat" class="form-control @error('ayat') is-invalid @enderror"
                        id="ayat" placeholder="Masukkan Ayat">
                    @if ($errors->has('ayat'))
                        <span class="text-danger d-block" style="font-size: 14px">{{ $errors->first('ayat') }}</span>
                    @endif
                </div>
                {{-- Isi Input --}}
                <div class="form-group">
                    <label for="isi">Isi</label>
                    <span class="text-danger">&#42;</span>
                    <textarea name="isi" cols="30" rows="10" class="form-control @error('isi') is-invalid @enderror"
                        id="isi" placeholder="Masukkan isi">{{ old('isi') }}</textarea>
                    @if ($errors->has('isi'))
                        <span class="text-danger d-block" style="font-size: 14px">{{ $errors->first('isi') }}</span>
                    @endif
                </div>

                <!-- /.form-group -->
                <div>
                    <a href="{{ route('peraturan') }}" class="btn btn-outline-secondary">Kembali</a>
                    <x-button-submit text="Tambah Ayat" formId="create-form" />
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

        function showLoading() {
            $('#loading-spinner').show();
        }

        function hideLoading() {
            $('#loading-spinner').hide();
        }

        // Get Pasal
        $('select[name="peraturan"]').on('change', function() {
            var peraturanId = $(this).val();
            if (peraturanId) {
                $.ajax({
                    url: "{{ route('ayat.get_pasal', ':peraturanId') }}".replace(':peraturanId',
                        peraturanId),
                    type: "GET",
                    dataType: "json",
                    success: function(data) {
                        console.log(data);
                        $('select[name="pasal"]').empty();
                        $.each(data, function(key, value) {
                            $('select[name="pasal"]').append('<option value="' +
                                key + '">' + value + '</option>');
                        });
                        $('select[name="pasal"]').trigger('change.select2');
                        $('select[name="pasal"]').trigger('change');
                    },
                    complete: function() {
                        hideLoading();
                    }
                });
            } else {
                $('select[name="pasal"]').empty();
            }
        });
    </script>
@endsection
