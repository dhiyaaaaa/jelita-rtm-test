@extends('components.layout.main_layout')

@section('content')
    <div class="card card-primary">
        <div class="card-header">
            <h3 class="card-title title-size">{{ $title }}</h3>
        </div>
        <!-- /.card-header -->
        <!-- form start -->
        <form method="POST" action="{{ route('user.store_user_role') }}" id="create-form">
            @csrf
            <div class="card-body">

                {{-- Role --}}
                <div class="form-group">
                    <label>Role</label>
                    <span class="text-danger">&#42;</span>
                    <select class="select2" data-placeholder="Pilih Role" style="width: 100%;" name="role" id="role">
                        @foreach ($roles as $role)
                            <option value="{{ $role->id }}" {{ old('role') == $role->id ? 'selected' : '' }}>
                                {{ strtoupper($role->name) }}</option>
                        @endforeach
                    </select>
                    <small class="form-text text-muted">Pilih role untuk user</small>
                    @if ($errors->has('role'))
                        <span class="text-danger d-block" style="font-size: 14px">{{ $errors->first('role') }}</span>
                    @endif
                </div>

                {{-- User --}}
                <div class="form-group">
                    <label>User</label>
                    <span class="text-danger">&#42;</span>
                    <table id="user" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>
                                    Check
                                </th>
                                <th class="text-center">Nama</th>
                                {{-- <th class="text-center">Email</th> --}}
                                <th class="text-center">Jabatan</th>
                                <th class="text-center">Prodi/Fakultas/Unit</th>
                                <th class="text-center">Role</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($users as $item)
                                <tr>
                                    <td class="text-center">
                                        <input type="checkbox" name="user[]" value="{{ $item->id }}">
                                    </td>
                                    <td class="text-center">{{ $item->name }}</td>
                                    {{-- <td class="text-center">{{ $item->email }}</td> --}}
                                    <td class="text-center">
                                        <span>
                                            {{ $item->jabatan->isNotEmpty() ? $item->jabatan->pluck('nama')->implode(', ') : '-' }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        @if ($item->prodi->isNotEmpty())
                                            <span>
                                                {{ 'Prodi ' . $item->prodi->pluck('nama')->implode(', ') . ' ' . $item->prodi->first()->jenjang->nama }}
                                            </span>
                                        @elseif ($item->fakultas->isNotEmpty())
                                            <span>
                                                {{ 'Fakultas ' . $item->fakultas->pluck('nama')->implode(', ') }}
                                            </span>
                                        @elseif ($item->unit->isNotEmpty())
                                            <span>
                                                {{ $item->unit->pluck('nama')->implode(', ') }}
                                            </span>
                                        @else
                                            <span>-</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @if ($item->roles->isNotEmpty())
                                            @foreach ($item->roles as $role)
                                                @php
                                                    $badgeClass = '';
                                                    if ($role->name === 'pj_prodi') {
                                                        $badgeClass = 'badge-primary';
                                                    } elseif ($role->name === 'pj_fakultas') {
                                                        $badgeClass = 'badge-custom-purple';
                                                    } elseif ($role->name === 'pj_universitas') {
                                                        $badgeClass = 'badge-custom-fuchsia';
                                                    } elseif ($role->name === 'gkm') {
                                                        $badgeClass = 'badge-custom-orange';
                                                    } elseif ($role->name === 'gpm') {
                                                        $badgeClass = 'badge-info';
                                                    } elseif ($role->name === 'auditor') {
                                                        $badgeClass = 'badge-secondary';
                                                    } elseif ($role->name === 'pusjamu') {
                                                        $badgeClass = 'badge-danger';
                                                    } else {
                                                        $badgeClass = 'badge-light';
                                                    }
                                                @endphp

                                                <span
                                                    class="badge {{ $badgeClass }}">{{ strtoupper($role->name) }}</span>
                                            @endforeach
                                        @else
                                            -
                                        @endif

                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td class="text-center" colspan="6">Tidak ada data tersedia</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>

                    @if ($errors->has('user'))
                        <span class="text-danger d-block" style="font-size: 14px">{{ $errors->first('user') }}</span>
                    @endif
                </div>

                <!-- /.form-group -->
                <div>
                    <a href="{{ route('user') }}" class="btn btn-outline-secondary">Kembali</a>
                    <x-button-submit text="Tambah Role untuk User" formId="create-form" />
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
    <!-- DataTables -->
    <link rel="stylesheet" href="{{ asset('plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
@endsection

@section('script')
    <!-- DataTables & Plugins -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/iCheck/1.0.2/icheck.min.js"></script>
    <script src="{{ asset('plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables-checkboxes/js/dataTables.checkboxes.min.js') }}"></script>

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

        $(document).ready(function() {
            var table = $('#user').DataTable({
                "responsive": true,
                "autoWidth": false,
                'paging': false,
                'scrollCollapse': true,
                'scrollX': true,
                'scrollY': 300,
            });
        });
    </script>
@endsection
