@extends('components.layout.main_layout')

@section('content')
    <div class="card card-primary">
        <div class="card-header">
            <h3 class="card-title title-size">{{ $title }}</h3>
        </div>
        <!-- /.card-header -->
        <!-- form start -->
        <form method="POST" action="{{ route('menu.update', $menu->id) }}" id="edit-form">
            @csrf
            @method('put')
            <div class="card-body">
                {{-- Menu Input --}}
                <div class="form-group">
                    <label for="menu">Menu</label>
                    <span class="text-danger">&#42;</span>
                    <input type="text" name="menu" class="form-control @error('menu') is-invalid @enderror"
                        id="menu" placeholder="Masukkan Menu" value="{{ $menu->menu }}">
                    @if ($errors->has('menu'))
                        <span class="text-danger d-block" style="font-size: 14px">{{ $errors->first('menu') }}</span>
                    @endif
                </div>

                {{-- Status Input --}}
                <div class="form-group">
                    <label>Status</label>
                    <span class="text-danger">&#42;</span>
                    <div class="d-flex">
                        <div class="custom-control custom-radio mr-5">
                            <input class="custom-control-input" type="radio" id="aktif" name="status" value="1"
                                {{ $menu->status == 1 ? 'checked' : '' }}>
                            <label for="aktif" class="custom-control-label">Aktif</label>
                        </div>
                        <div class="custom-control custom-radio">
                            <input class="custom-control-input" type="radio" id="tidak_aktif" name="status"
                                value="0" {{ $menu->status == 0 ? 'checked' : '' }}>
                            <label for="tidak_aktif" class="custom-control-label">Tidak Aktif</label>
                        </div>
                    </div>
                    <small class="form-text text-muted">Status menu jika aktif maka akan ditampilkan ke user jika tidak
                        aktif maka menu tidak ditampilkan</small>
                    @if ($errors->has('status'))
                        <span class="text-danger d-block" style="font-size: 14px">{{ $errors->first('status') }}</span>
                    @endif
                </div>

                {{-- Role --}}
                <div class="form-group">
                    <label>Role User</label>
                    <span class="text-danger">&#42;</span>
                    @foreach ($roles as $role)
                        <div class="custom-control custom-checkbox">
                            <input class="custom-control-input" type="checkbox" id="{{ $role->id }}" name="roles[]"
                                value="{{ $role->id }}" @if (in_array($role->id, $role_checked)) checked @endif>
                            <label for="{{ $role->id }}"
                                class="custom-control-label">{{ strtoupper($role->name) }}</label>
                        </div>
                    @endforeach
                    <small class="form-text text-muted">Siapa saja yang dapat mengakses menu ini</small>
                    @if ($errors->has('roles'))
                        <span class="text-danger d-block" style="font-size: 14px">{{ $errors->first('roles') }}</span>
                    @endif
                </div>

                <div>
                    <a href="{{ route('menu') }}" class="btn btn-outline-secondary">Kembali</a>
                    <x-button-submit text="Edit Menu" formId="edit-form" />
                </div>
            </div>
        </form>

    </div>
@endsection
