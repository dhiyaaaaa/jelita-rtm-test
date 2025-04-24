@extends('components.layout.main_layout')

@section('content')
    <div class="card card-primary">
        <div class="card-header">
            <h3 class="card-title title-size">{{ $title }}</h3>
        </div>
        <!-- /.card-header -->
        <!-- form start -->
        <form method="POST" action="{{ route('main_menu.update', $mainMenu->id) }}" id="edit-form">
            @csrf
            @method('put')
            <div class="card-body">
                {{-- Menu Input --}}
                <div class="form-group">
                    <label for="mainmenu">Main Menu</label>
                    <span class="text-danger">&#42;</span>
                    <input type="text" name="mainmenu" class="form-control @error('mainmenu') is-invalid @enderror"
                        id="mainmenu" placeholder="Masukkan Main Menu" value="{{ $mainMenu->mainmenu }}">
                    @if ($errors->has('mainmenu'))
                        <span class="text-danger d-block" style="font-size: 14px">{{ $errors->first('mainmenu') }}</span>
                    @endif
                </div>

                {{-- Deskripsi --}}
                <div class="form-group">
                    <label for="deskripsi">Deskripsi Main Menu</label>
                    <span class="text-danger">&#42;</span>
                    <input type="text" name="deskripsi" class="form-control @error('deskripsi') is-invalid @enderror"
                        id="deskripsi" placeholder="Masukkan Deskripsi Main Menu" value="{{ $mainMenu->deskripsi }}">
                    @if ($errors->has('mainmenu'))
                        <span class="text-danger d-block" style="font-size: 14px">{{ $errors->first('deskripsi') }}</span>
                    @endif
                </div>

                {{-- Status Input --}}
                <div class="form-group">
                    <label>Status</label>
                    <span class="text-danger">&#42;</span>
                    <div class="d-flex">
                        <div class="custom-control custom-radio mr-5">
                            <input class="custom-control-input" type="radio" id="aktif" name="status" value="1"
                                {{ $mainMenu->status == 1 ? 'checked' : '' }}>
                            <label for="aktif" class="custom-control-label">Aktif</label>
                        </div>
                        <div class="custom-control custom-radio">
                            <input class="custom-control-input" type="radio" id="tidak_aktif" name="status"
                                value="0" {{ $mainMenu->status == 0 ? 'checked' : '' }}>
                            <label for="tidak_aktif" class="custom-control-label">Tidak Aktif</label>
                        </div>
                    </div>
                    <small class="form-text text-muted">Status menu jika aktif maka akan ditampilkan ke user jika tidak
                        aktif maka menu tidak ditampilkan</small>
                    @if ($errors->has('status'))
                        <span class="text-danger d-block" style="font-size: 14px">{{ $errors->first('status') }}</span>
                    @endif
                </div>

                {{-- Menu --}}
                <div class="form-group">
                    <label>Menu</label>
                    <span class="text-danger">&#42;</span>
                    @foreach ($menus as $menu)
                        <div class="custom-control custom-checkbox">
                            <input class="custom-control-input" type="checkbox" id="{{ $menu->id }}" name="menus[]"
                                value="{{ $menu->id }}" @if (in_array($menu->id, $menu_checked)) checked @endif>
                            <label for="{{ $menu->id }}"
                                class="custom-control-label">{{ strtoupper($menu->menu) }}</label>
                        </div>
                    @endforeach
                    <small class="form-text text-muted">Siapa saja yang dapat mengakses menu ini</small>
                    @if ($errors->has('menus'))
                        <span class="text-danger d-block" style="font-size: 14px">{{ $errors->first('menus') }}</span>
                    @endif
                </div>

                <div>
                    <a href="{{ route('main_menu') }}" class="btn btn-outline-secondary">Kembali</a>
                    <x-button-submit text="Edit Menu" formId="edit-form" />
                </div>
            </div>
        </form>

    </div>
@endsection
