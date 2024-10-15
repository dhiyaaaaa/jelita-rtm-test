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
                                <th></th>
                                <th class="text-center">Kode</th>
                                <th class="text-center">Pernyataan</th>
                                <th class="text-center">Jenjang/Auditee</th>
                                <th class="text-center">Unit/Lembaga</th>
                                <th class="text-center">Level</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Rows will be loaded by DataTables -->
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
            var instrumenSelected = @json($instrumenSelected);

            var table = $('#instrumen').DataTable({
                "processing": true,
                "serverSide": true,
                "ajax": {
                    "url": "{{ route('jadwal_audit.get_instrumen') }}",
                    "data": function(d) {
                        d.instrumenSelected = instrumenSelected;
                    }
                },
                'drawCallback': function() {
                    $('input[type="checkbox"]').iCheck({
                        "checkboxClass": 'icheckbox_flat-blue'
                    });

                    table.rows().every(function(rowIdx, tableLoop, rowLoop) {
                        var data = this.data();
                        if (instrumenSelected.includes(data.id)) {
                            this.nodes().to$().find('input[type="checkbox"]').iCheck('check');
                        }
                    });
                },
                "columns": [{
                        'data': "checkbox",
                        'orderable': false,
                        'searchable': false,
                        'checkboxes': {
                            'selectRow': true,
                            'selectCallback': function(nodes, selected) {
                                $('input[type="checkbox"]', nodes).iCheck('update');
                            },
                            'selectAllCallback': function(nodes, selected, indeterminate) {
                                $('input[type="checkbox"]', nodes).iCheck('update');
                            }
                        },
                    },
                    {
                        "data": "kode",
                        "className": "text-center"
                    },
                    {
                        "data": "pernyataan",
                        "className": "text-center"
                    },
                    {
                        "data": "jenjang",
                        "className": "text-center"
                    },
                    {
                        "data": "unit",
                        "className": "text-center"
                    },
                    {
                        "data": "level",
                        "className": "text-center"
                    },
                ],
                "responsive": true,
                "autoWidth": false,
                "columnDefs": [{
                        "width": "20%",
                        "targets": [2]
                    },
                    {
                        "width": "15%",
                        "targets": [3]
                    },
                ],
                'select': {
                    'style': 'multi'
                },
                'paging': false,
                'scrollCollapse': true,
                'scrollX': true,
                'scrollY': 300,
            });

            $(table.table().container()).on('ifChanged', '.dt-checkboxes-select-all input[type="checkbox"]',
                function(event) {
                    var col = table.column($(this).closest('th'), {
                        filter: 'applied'
                    });
                    col.checkboxes.select(this.checked);
                });

            $(table.table().container()).on('ifChanged', '.dt-checkboxes', function(event) {
                var cell = table.cell($(this).closest('td'));
                cell.checkboxes.select(this.checked);
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
