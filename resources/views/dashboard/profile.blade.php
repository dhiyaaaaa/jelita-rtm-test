@extends('components.layout.main_layout')

@section('content')
    <div class="row mt-5">
        <div class="col-md-3">

            <!-- Profile Image -->
            <div class="card card-primary card-outline">
                <div class="card-body box-profile">
                    <div class="text-center">
                        <img class="profile-user-img img-fluid" src="{{ asset('dist/img/logo_unsoed.png') }}"
                            alt="User profile picture" style="width: auto; height: 180px;">
                    </div>
                </div>
                <!-- /.card-body -->
            </div>
        </div>

        <!-- /.col -->
        <div class="col-md-9">
            <div class="card card-primary card-outline">
                <div class="card-body">

                    <div class="form-group row">
                        <label for="inputName" class="col-sm-2 col-form-label">Nama</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" value="{{ $user->name }}" disabled>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="inputEmail" class="col-sm-2 col-form-label">Email</label>
                        <div class="col-sm-10">
                            <input type="email" class="form-control" value="{{ $user->email }}" disabled>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="inputName2" class="col-sm-2 col-form-label">Jabatan</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control"
                                value="{{ $user->jabatan->isNotEmpty() ? $user->jabatan->first()->nama : '-' }}" disabled>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="inputExperience" class="col-sm-2 col-form-label">Prodi/Fakultas/Unit</label>
                        <div class="col-sm-10">
                            @php
                                $unit = '-';
                                if ($user->prodi->isNotEmpty()) {
                                    $unit = $user->prodi->first()->nama;
                                } elseif ($user->fakultas->isNotEmpty()) {
                                    $unit = $user->fakultas->first()->nama;
                                } elseif ($user->unit->isNotEmpty()) {
                                    $unit = $user->unit->first()->nama;
                                }
                            @endphp
                            <input type="text" class="form-control" value="{{ $unit }}" disabled />
                        </div>
                    </div>

                    <form class="form-horizontal" action="{{ route('profile.edit', $user->id) }}" method="post"
                        id="no-telepon-form">
                        @csrf
                        <div class="form-group row">
                            <label for="inputName2" class="col-sm-2 col-form-label">No HP</label>
                            <div class="col-sm-10">
                                <input type="number" class="form-control" id="no_telepon"
                                    value="{{ $user->no_telepon ? $user->no_telepon : '' }}" name="no_telepon" disabled>
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="offset-sm-2 col-sm-10">
                                <button id="edit-button" type="button" class="btn btn-warning" onclick="edit(true)">Ubah</button>
                                
                                <div id="submit-button" class="d-none">
                                    <button class="btn btn-outline-secondary" onclick="edit(false)" type="button">Batal</button>
                                    <x-button-submit text="Simpan" formId="no-telepon-form"></x-button-submit>
                                </div>
                            </div>
                        </div>
                    </form>

                    {{-- <button id="test">Alert</button> --}}
                </div><!-- /.card-body -->
            </div>
            <!-- /.card -->
        </div>
        <!-- /.col -->
    </div>
@endsection

@section('script')
    <script>
        function edit(enable) {
            var input = document.getElementById('no_telepon');
            var edit = document.getElementById('edit-button');
            var submit = document.getElementById('submit-button');

            if (enable) {
                input.removeAttribute('disabled');
                edit.classList.add('d-none');
                submit.classList.remove('d-none');
            } else {
                input.setAttribute('disabled', true);
                edit.classList.remove('d-none');
                submit.classList.add('d-none');
            }
        }
    </script>
@endsection
