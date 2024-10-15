@extends('components.layout.main_layout')

@section('content')
    <div class="card card-primary">
        <div class="card-header">
            <h3 class="card-title title-size">{{ $title }}</h3>
        </div>
        <!-- /.card-header -->
        <!-- form start -->
        <form method="POST" action="{{ route('pertanyaan.update', $pertanyaan->id) }}" id="edit-form">
            @csrf\
            @method('put')
            <div class="card-body">
                {{-- Pertanyaan --}}
                <div class="form-group">
                    <label>Pertanyaan</label>
                    <span class="text-danger">&#42;</span>
                    <textarea name="pertanyaan" cols="30" rows="3" class="form-control @error('pertanyaan') is-invalid @enderror"
                        placeholder="Masukkan Pertanyaan">{{ $pertanyaan->pertanyaan }}</textarea>
                    @if ($errors->has('pertanyaan'))
                        <span class="text-danger d-block" style="font-size: 14px">{{ $errors->first('pertanyaan') }}
                        </span>
                    @endif
                </div>

                <!-- /.form-group -->
                <div>
                    <a href="{{ route('pertanyaan') }}" class="btn btn-outline-secondary">Kembali</a>
                    <x-button-submit text="Edit Pertanyaan" formId="edit-form" />
                </div>
            </div>
        </form>

    </div>
@endsection
