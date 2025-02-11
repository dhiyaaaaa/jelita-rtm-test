@extends('components.layout.main_layout')

@section('content')
    <div class="card card-primary">
        <div class="card-header">
            <h3 class="card-title title-size">{{ $title }}</h3>
        </div>
        <!-- /.card-header -->
        <!-- form start -->
        <form method="POST"
            action="{{ route('auditee.isian-tindak-lanjut.update', ['rtl' => $rtl->id,]) }}"id="create-form">
            @csrf
            @method('PUT')
            
            <div class="card-body">
                {{-- Tanggal --}}
                <div class="form-group">
                    <label for="tgl">Tanggal</label>
                    <span class="text-danger">&#42;</span>
                    <input type="date" name="tgl" class="form-control @error('tgl') is-invalid @enderror"
                        id="tgl" placeholder="Masukkan Tanggal" value="{{ old('tgl', $rtl->tgl) }}">
                    @error('tgl')
                        <span class="text-danger d-block" style="font-size: 14px">{{ $message }}</span>
                    @enderror
                </div>

                {{-- Auditee --}}
                <div class="form-group" id="auditee">
                    <label for="auditee">Auditan</label>
                    <span class="text-danger">&#42;</span>
                    <select class="select2 form-control @error('auditee') is-invalid @enderror"
                        id="auditee" name="auditee" data-placeholder="Pilih Auditan" style="width: 100%;">
                        <option disabled selected>-- Pilih Auditan --</option>
                        @foreach ($auditees as $item)
                            <option value="{{ $item->id }}"
                                {{ in_array($item->id, $auditeeSelected->toArray()) ? 'selected' : '' }}>
                                {{ ucwords($item->user->name) }}</option>
                        @endforeach
                    </select>
                    @error('auditee')
                        <span class="text-danger d-block" style="font-size: 14px">{{ $message }}</span>
                    @enderror
                </div>


                <!-- /.form-group -->
                <div class="mt-4">
                    <a href="{{ route('auditee.isian-tindak-lanjut.index')}}" class="btn btn-outline-secondary">Kembali</a>
                    <x-button-submit text="Edit Tindak Lanjut" formId="create-form" />
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
        });
    </script>
@endsection
