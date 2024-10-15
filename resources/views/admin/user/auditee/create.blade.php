@extends('components.layout.main_layout')

@section('content')
    <div class="card card-primary">
        <div class="card-header">
            <h3 class="card-title title-size">{{ $title }}</h3>
        </div>
        <!-- /.card-header -->
        <!-- form start -->
        <form method="POST"
            action="{{ route('auditee.store', ['jadwalAudit' => $jadwalAudit->id, 'unit' => $unit, 'type' => $type]) }}"
            id="create-form">
            @csrf
            <div class="card-body">
                <div class="form-group">
                    <label>Auditan</label>
                    <span class="text-danger">&#42;</span>

                    <select class="select2" multiple data-placeholder="Pilih Auditan" style="width: 100%;" name="auditee[]">
                        @foreach ($user as $us)
                            <option value="{{ $us->id }}">
                                {{ $us->name . ' - ' . $us->jabatan->first()->nama }}
                            </option>
                        @endforeach
                    </select>


                    @if ($errors->has('auditee'))
                        <span class="text-danger d-block" style="font-size: 14px">{{ $errors->first('auditee') }}</span>
                    @endif
                </div>
                <!-- /.form-group -->
                <div>
                    <a href="{{ route('auditee.show', ['jadwalAudit' => $jadwalAudit->id, 'type' => $route]) }}"
                        class="btn btn-outline-secondary">Kembali</a>
                    <x-button-submit text="Tambah Auditan" formId="create-form" />
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
