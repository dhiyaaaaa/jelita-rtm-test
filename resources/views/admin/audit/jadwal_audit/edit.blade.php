@extends('components.layout.main_layout')

@section('content')
    <div class="card card-primary">
        <div class="card-header">
            <h3 class="card-title title-size">{{ $title }}</h3>
        </div>
        <!-- /.card-header -->
        <!-- form start -->
        <form method="POST" action="{{ route('jadwal_audit.update', $jadwalAudit->id) }}" id="create-form">
            @csrf
            @method('PUT')
            <div class="card-body">
                {{-- Jadwal --}}
                <div class="form-group">
                    <label>Jadwal</label>
                    <span class="text-danger">&#42;</span>
                    <input type="text" name="jadwal" id="jadwal"
                        class="form-control @error('jadwal') is-invalid @enderror" placeholder="Masukkan jadwal"
                        value="{{ old('jadwal', $jadwalAudit->jadwal) }}">
                    @if ($errors->has('jadwal'))
                        <span class="text-danger d-block" style="font-size: 14px">{{ $errors->first('jadwal') }}</span>
                    @endif
                </div>

                {{-- Tgl Mulai --}}
                <div class="form-group">
                    <label>Tanggal Mulai</label>
                    <span class="text-danger">&#42;</span>
                    <input type="date" name="tgl_mulai" id="tgl_mulai"
                        class="form-control @error('tgl_mulai') is-invalid @enderror" placeholder="Masukkan tanggal mulai"
                        value="{{ old('tgl_mulai', $jadwalAudit->tgl_mulai) }}">
                    @if ($errors->has('tgl_mulai'))
                        <span class="text-danger d-block" style="font-size: 14px">{{ $errors->first('tgl_mulai') }}</span>
                    @endif
                </div>

                {{-- Tgl Selesai --}}
                <div class="form-group">
                    <label>Tanggal Selesai</label>
                    <span class="text-danger">&#42;</span>
                    <input type="date" name="tgl_selesai" id="tgl_selesai"
                        class="form-control @error('tgl_selesai') is-invalid @enderror"
                        placeholder="Masukkan tanggal selesai" value="{{ old('tgl_selesai', $jadwalAudit->tgl_selesai) }}">
                    @if ($errors->has('tgl_selesai'))
                        <span class="text-danger d-block" style="font-size: 14px">{{ $errors->first('tgl_selesai') }}</span>
                    @endif
                </div>

                {{-- Instrumen --}}
                <div class="form-group">
                    <label>Instrumen</label>
                    <span class="text-danger">&#42;</span>
                    <table id="instrumen" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>
                                    <input type="checkbox" id="select-all">
                                </th>
                                <th class="text-center">Kode</th>
                                <th class="text-center">Pernyataan</th>
                                <th class="text-center">Jenjang/Auditee</th>
                                <th class="text-center">Unit/Lembaga</th>
                                <th class="text-center">Level</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($instrumen as $item)
                                <tr>
                                    <td>
                                        <input type="checkbox" name="instrumen[]" value="{{ $item->id }}"
                                            {{ in_array($item->id, $instrumenSelected) ? 'checked' : '' }}>
                                    </td>
                                    <td class="text-center">{{ $item->kode }}</td>
                                    <td>{{ $item->pernyataan }}</td>

                                    <td class="text-center">
                                        @if ($item->jenjang->isNotEmpty() && $item->prodi->isNotEmpty())
                                            @foreach ($item->jenjang->pluck('nama') as $nama)
                                                <span class="badge bg-secondary mb-2">{{ $nama }}</span>
                                            @endforeach
                                            @foreach ($item->prodi->pluck('nama') as $nama)
                                                <span class="badge bg-info mb-2">{{ $nama }}</span>
                                            @endforeach
                                        @elseif ($item->jenjang->isNotEmpty())
                                            @foreach ($item->jenjang->pluck('nama') as $nama)
                                                <span class="badge bg-secondary mb-2">{{ $nama }}</span>
                                            @endforeach
                                        @elseif ($item->prodi->isNotEmpty())
                                            @foreach ($item->prodi->pluck('nama') as $nama)
                                                <span class="badge bg-info mb-2">{{ $nama }}</span>
                                            @endforeach
                                        @elseif ($item->jabatan->isNotEmpty())
                                            @foreach ($item->jabatan->pluck('nama') as $nama)
                                                <span class="badge bg-secondary mb-2">{{ $nama }}</span>
                                            @endforeach
                                        @else
                                            <span class="badge">-</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @if (in_array($item->level->slug, ['prodi', 'fakultas']))
                                            -
                                        @else
                                            @foreach ($item->jabatan as $jab)
                                                @php
                                                    $units = $jab->unit->pluck('nama')->unique();
                                                @endphp

                                                @if ($units->isNotEmpty())
                                                    @foreach ($units as $nama)
                                                        <span class="badge bg-info mb-2">{{ $nama }}</span>
                                                    @endforeach
                                                @else
                                                    -
                                                @endif
                                            @endforeach
                                        @endif
                                    </td>

                                    <td class="text-center">
                                        @if ($item->level->slug === 'prodi')
                                            <span class="badge" style="background-color: #f012be;color: #fff; ">PS</span>
                                        @else
                                            <span class="badge" style="background-color: #ff851b;color: #fff; ">UPPS</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4">Kosong</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>

                    @if ($errors->has('instrumen'))
                        <span class="text-danger d-block" style="font-size: 14px">{{ $errors->first('instrumen') }}</span>
                    @endif
                </div>

                <!-- /.form-group -->
                <div>
                    <a href="{{ route('jadwal_audit') }}" class="btn btn-outline-secondary">Kembali</a>
                    <x-button-submit text="Simpan Jadwal Audit" formId="create-form" />
                </div>
            </div>
        </form>
    </div>
@endsection

@section('style')
    <!-- DataTables -->
    <link rel="stylesheet" href="{{ asset('plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/icheck/dataTables.checkboxes.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/iCheck/1.0.2/skins/flat/blue.css">
@endsection

@section('script')
    <!-- DataTables & Plugins -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/iCheck/1.0.2/icheck.min.js"></script>
    <script src="{{ asset('plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables-checkboxes/js/dataTables.checkboxes.min.js') }}"></script>

    <script>
        $(document).ready(function() {
            var table = $('#instrumen').DataTable({
                "responsive": true,
                "autoWidth": false,
                'paging': false,
                'scrollCollapse': true,
                'scrollX': true,
                'scrollY': 300,
                "initComplete": function(settings, json) {
                    selectAll();
                }
            });

            function selectAll() {
                if ($('input[name="instrumen[]"]').length === $('input[name="instrumen[]"]:checked')
                    .length) {
                    $('#select-all').prop('checked', true);
                } else {
                    $('#select-all').prop('checked', false);
                }
            }

            $('#select-all').on('click', function() {
                var isChecked = $(this).is(':checked');
                $('input[name="instrumen[]"]').prop('checked', isChecked);
            });

            $('#instrumen tbody').on('change', 'input[name="instrumen[]"]', function() {
                selectAll();
            });

            $('#create-form').on('submit', function(e) {
                e.preventDefault();

                var form = this;

                var rows_selected = table.column(0).checkboxes.selected();

                $.each(rows_selected, function(index, rowId) {
                    $(form).append(
                        $('<input>')
                        .attr('type', 'hidden')
                        .attr('name', 'instrumen[]')
                        .val(rowId)
                    );
                });

                form.submit();
            });
        });
    </script>
@endsection
