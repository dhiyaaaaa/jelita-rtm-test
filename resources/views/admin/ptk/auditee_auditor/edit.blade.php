@extends('components.layout.main_layout')

@section('content')
    <div class="card card-primary">
        <div class="card-header">
            <h3 class="card-title title-size">{{ $title }}</h3>
        </div>
        <!-- /.card-header -->
        <!-- form start -->
        <form method="POST"
            action="{{ route('auditee_auditor_ptk.update', ['jadwalAudit' => $jadwalAudit->id, 'unit' => $unit, 'type' => $type]) }}"
            id="create-form">
            @csrf
            @method('put')
            <div class="card-body">
                <div class="form-group">
                    <label>Auditor 1</label>
                    <span class="text-danger">&#42;</span>
                    <select class="select2 auditor-select" data-placeholder="Pilih Auditor 1" style="width: 100%;"
                        name="auditor_1">
                        <option value="" selected>Pilih Auditor 1</option>
                        @foreach ($auditors as $item)
                            @if ($item->user->prodi->isNotEmpty())
                                <option value="{{ $item->id }}"
                                    {{ isset($auditor[0]) && $item->id == $auditor[0] ? 'selected' : '' }}>
                                    {{ $item->user->name . ' - ' . $item->user->prodi->first()->nama . ' ' . $item->user->prodi->first()->jenjang->nama }}
                                </option>
                            @else
                                <option value="{{ $item->id }}"
                                    {{ isset($auditor[0]) && $item->id == $auditor[0] ? 'selected' : '' }}>
                                    {{ $item->user->name }}
                                </option>
                            @endif
                        @endforeach
                    </select>
                    <div class="input-group-append mt-3">
                        <button type="button" class="btn btn-danger clear-btn"
                            data-target="auditor_1">Hapus</button>
                    </div>
                    @if ($errors->has('auditor_1'))
                        <span class="text-danger d-block" style="font-size: 14px">{{ $errors->first('auditor_1') }}</span>
                    @endif
                </div>
                <div class="form-group">
                    <label>Auditor 2</label>
                    <select class="select2 auditor-select" data-placeholder="Pilih Auditor 2" style="width: 100%;"
                        name="auditor_2">
                        <option value="" selected>Pilih Auditor 2</option>
                        @foreach ($auditors as $item)
                            @if ($item->user->prodi->isNotEmpty())
                                <option value="{{ $item->id }}"
                                    {{ isset($auditor[1]) && $item->id == $auditor[1] ? 'selected' : '' }}>
                                    {{ $item->user->name . ' - ' . $item->user->prodi->first()->nama . ' ' . $item->user->prodi->first()->jenjang->nama }}
                                </option>
                            @else
                                <option value="{{ $item->id }}"
                                    {{ isset($auditor[1]) && $item->id == $auditor[1] ? 'selected' : '' }}>
                                    {{ $item->user->name }}
                                </option>
                            @endif
                        @endforeach
                    </select>
                    <div class="input-group-append mt-3">
                        <button type="button" class="btn btn-danger clear-btn"
                            data-target="auditor_2">Hapus</button>
                    </div>
                    @if ($errors->has('auditor_2'))
                        <span class="text-danger d-block" style="font-size: 14px">{{ $errors->first('auditor_2') }}</span>
                    @endif
                </div>
                <div class="form-group">
                    <label>Auditor 3</label>
                    <select class="select2 auditor-select" data-placeholder="Pilih Auditor 3" style="width: 100%;"
                        name="auditor_3">
                        <option value="" selected>Pilih Auditor 3</option>
                        @foreach ($auditors as $item)
                            @if ($item->user->prodi->isNotEmpty())
                                <option value="{{ $item->id }}"
                                    {{ isset($auditor[2]) && $item->id == $auditor[2] ? 'selected' : '' }}>
                                    {{ $item->user->name . ' - ' . $item->user->prodi->first()->nama . ' ' . $item->user->prodi->first()->jenjang->nama }}
                                </option>
                            @else
                                <option value="{{ $item->id }}"
                                    {{ isset($auditor[2]) && $item->id == $auditor[2] ? 'selected' : '' }}>
                                    {{ $item->user->name }}
                                </option>
                            @endif
                        @endforeach
                    </select>
                    <div class="input-group-append mt-3">
                        <button type="button" class="btn btn-danger clear-btn"
                            data-target="auditor_3">Hapus</button>
                    </div>
                    @if ($errors->has('auditor_3'))
                        <span class="text-danger d-block" style="font-size: 14px">{{ $errors->first('auditor_3') }}</span>
                    @endif
                </div>
                <!-- /.form-group -->
                <div>
                    <a href="{{ route('auditee_auditor_ptk.show', ['jadwalAudit' => $jadwalAudit->id,  ($type === 'fakultas' || $type === 'universitas' ? 'upps' : '')]) }}"
                        class="btn btn-outline-secondary">Kembali</a>
                    <x-button-submit text="Ubah Auditor" formId="create-form" />
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

            function updateAuditorOptions() {
                let selectedValues = [];
                $('.auditor-select').each(function() {
                    let value = $(this).val();
                    if (value) {
                        selectedValues.push(value);
                    }
                });

                $('.auditor-select').each(function() {
                    let select = $(this);
                    select.find('option').each(function() {
                        let optionValue = $(this).attr('value');
                        if (selectedValues.includes(optionValue) && optionValue !== select.val()) {
                            $(this).attr('disabled', true);
                        } else {
                            $(this).attr('disabled', false);
                        }
                    });
                });
            }

            $('.auditor-select').on('change', function() {
                updateAuditorOptions();
            });

            $('.clear-btn').on('click', function() {
                let targetName = $(this).data('target');
                let selectElement = $(`select[name="${targetName}"]`);
                selectElement.val(null).trigger('change');
                updateAuditorOptions();
            });

            updateAuditorOptions();
        });
    </script>
@endsection
