@extends('components.layout.main_layout')

@section('content')
    <div class="card card-primary">
        <div class="card-header">
            <h3 class="card-title title-size">{{ $title }}</h3>
        </div>
        <!-- /.card-header -->
        <!-- form start -->
        <form method="POST" action="{{ route('pertanyaan.store') }}" id="create-form">
            @csrf
            <div class="card-body">
                {{-- Pertanyaan --}}
                <div class="form-group">
                    <label>Pertanyaan</label>
                    <span class="text-danger">&#42;</span>
                    <textarea name="pertanyaan" cols="30" rows="3" class="form-control @error('pertanyaan') is-invalid @enderror" placeholder="Masukkan Pertanyaan"></textarea>
                    @if ($errors->has('pertanyaan'))
                        <span class="text-danger d-block" style="font-size: 14px">{{ $errors->first('pertanyaan') }}
                        </span>
                    @endif
                </div>

                <!-- /.form-group -->
                <div>
                    <a href="{{ route('pertanyaan') }}" class="btn btn-outline-secondary">Kembali</a>
                    <x-button-submit text="Tambah Pertanyaan" formId="create-form" />
                </div>
            </div>
        </form>

    </div>
@endsection
