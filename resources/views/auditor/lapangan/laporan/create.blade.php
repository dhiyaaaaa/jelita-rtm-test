@extends('components.layout.main_layout')

@section('content')
    <div class="card card-primary">
        <div class="card-header">
            <h3 class="card-title title-size">{{ $title }}</h3>
        </div>
        <!-- /.card-header -->
        <!-- form start -->
        <form method="POST"
            action="{{ route('auditor.lapangan.laporan.store', [
                'jadwalAudit' => $jadwalId,
                'unit' => $unitId,
                'type' => $type,
            ]) }}"
            id="create-form">
            @csrf
            <div class="card-body">
                {{-- tanggal --}}
                <div class="form-group">
                    <label for="tgl">Tanggal</label>
                    <span class="text-danger">&#42;</span>
                    <input type="date" name="tgl" class="form-control @error('tgl') is-invalid @enderror"
                        id="tgl" placeholder="Masukkan Level">
                    @if ($errors->has('tgl'))
                        <span class="text-danger d-block" style="font-size: 14px">{{ $errors->first('tgl') }}</span>
                    @endif
                </div>

                {{-- auditee --}}
                <div class="form-group" id="unit">
                    <label>Auditan</label>
                    <span class="text-danger">&#42;</span>
                    <select class="select2" data-placeholder="Pilih Auditan" style="width: 100%;" name="auditee">
                        @foreach ($auditees as $item)
                            <option value="{{ $item->id }}">{{ ucwords($item->user->name) }}</option>
                        @endforeach
                    </select>
                    {{-- <small class="form-text text-muted">Instrumen ini untuk unit mana</small> --}}
                    @if ($errors->has('unit'))
                        <span class="text-danger d-block" style="font-size: 14px">{{ $errors->first('unit') }}</span>
                    @endif
                </div>

                <!-- /.form-group -->
                <div>
                    <a href="{{ route('auditor.lapangan.show', $jadwalId) }}" class="btn btn-outline-secondary">Kembali</a>
                    <x-button-submit text="Tambah Temuan Positif" formId="create-form" />
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
