@extends('components.layout.main_layout')

@section('content')
    <div class="card card-primary">
        <div class="card-header">
            <h3 class="card-title title-size">{{ $title }}</h3>
        </div>
        <!-- /.card-header -->
        <!-- form start -->
        <form method="POST" action="{{ route('jenis_pertanyaan.update', $jenisPertanyaan->id) }}" id="create-form">
            @csrf
            @method('put')
            <div class="card-body">
                {{-- jenis_Pertanyaan Input --}}
                <div class="form-group">
                    <label for="nama">Jenis Pertanyaan</label>
                    <span class="text-danger">&#42;</span>
                    <input type="text" name="nama" class="form-control @error('nama') is-invalid @enderror"
                        id="nama" placeholder="Masukkan Jenis Pertanyaan" value="{{ $jenisPertanyaan->nama }}">
                    @if ($errors->has('nama'))
                        <span class="text-danger d-block" style="font-size: 14px">{{ $errors->first('nama') }}</span>
                    @endif
                </div>

                <!-- /.form-group -->
                <div>
                    <a href="{{ route('jenis_pertanyaan') }}" class="btn btn-outline-secondary">Kembali</a>
                    <x-button-submit text="Edit Jenis Pertanyaan" formId="create-form" />
                </div>
            </div>
        </form>

    </div>
@endsection
