@extends('components.layout.main_layout')

@section('content')
    <div class="card card-primary">
        <div class="card-header">
            <h3 class="card-title title-size">{{ $title }}</h3>
        </div>
        <!-- /.card-header -->
        <!-- form start -->
        <form method="POST" action="{{ route('jadwal_audit.update_setting') }}" id="create-form">
            @csrf
            <div class="card-body">
                {{-- Prodi --}}
                <div class="form-group">
                    <label>Program Studi</label>
                    {{-- <span class="text-danger">&#42;</span> --}}
                    @foreach ($prodi as $item)
                        <div class="custom-control custom-checkbox">
                            <input class="custom-control-input" type="checkbox" id="{{ $item->id }}" name="prodi[]"
                                value="{{ $item->id }}" @if (in_array($item->id, $selectedProdi)) checked @endif>
                            <label for="{{ $item->id }}" class="custom-control-label">{{ ucfirst($item->nama) }}</label>
                        </div>
                    @endforeach
                   
                    <small class="form-text text-muted">Khusus untuk Dosen yang mempunyai role GKM akan otomatis terdaftar sebagai auditan prodinya sendiri sedangkan Dosen yang mempunyai role GPM akan otomatis terdaftar sebagai GPM pada fakultasnya sendiri</small>

                    @if ($errors->has('prodi'))
                        <span class="text-danger d-block" style="font-size: 14px">{{ $errors->first('prodi') }}</span>
                    @endif
                </div>

                {{-- Fakultas --}}
                <div class="form-group">
                    <label>Fakultas</label>
                    {{-- <span class="text-danger">&#42;</span> --}}
                    @foreach ($fakultas as $item)
                        <div class="custom-control custom-checkbox">
                            <input class="custom-control-input" type="checkbox" id="{{ $item->id }}" name="fakultas[]"
                                value="{{ $item->id }}" @if (in_array($item->id, $selectedFakultas)) checked @endif>
                            <label for="{{ $item->id }}"
                                class="custom-control-label">{{ ucfirst($item->nama) }}</label>
                        </div>
                    @endforeach
                    
                    @if ($errors->has('fakultas'))
                        <span class="text-danger d-block" style="font-size: 14px">{{ $errors->first('fakultas') }}</span>
                    @endif
                </div>

                {{-- Universitas --}}
                <div class="form-group">
                    <label>Universitas</label>
                    {{-- <span class="text-danger">&#42;</span> --}}
                    @foreach ($universitas as $item)
                        <div class="custom-control custom-checkbox">
                            <input class="custom-control-input" type="checkbox" id="{{ $item->id }}" name="universitas[]"
                                value="{{ $item->id }}" @if (in_array($item->id, $selectedUniversitas)) checked @endif>
                            <label for="{{ $item->id }}"
                                class="custom-control-label">{{ ucfirst($item->nama) }}</label>
                        </div>
                    @endforeach
                    <small class="form-text text-muted">Pilih jabatan agar ketika membuat jadwal audit auditan otomatis bertambah</small>
                    @if ($errors->has('universitas'))
                        <span class="text-danger d-block" style="font-size: 14px">{{ $errors->first('universitas') }}</span>
                    @endif
                </div>

                <div>
                    <a href="{{ route('jadwal_audit') }}" class="btn btn-outline-secondary">Kembali</a>
                    <x-button-submit text="Edit Setting" formId="create-form" />
                </div>
            </div>
        </form>

    </div>
@endsection
