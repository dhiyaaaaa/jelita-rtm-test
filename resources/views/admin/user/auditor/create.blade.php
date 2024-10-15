@extends('components.layout.main_layout')

@section('content')
    <div class="card card-primary">
        <div class="card-header">
            <h3 class="card-title title-size">{{ $title }}</h3>
        </div>
        <!-- /.card-header -->
        <!-- form start -->
        <form method="POST" action="{{ route('auditor.store', $jadwalAudit->id) }}" id="create-form">
            @csrf
            <div class="card-body">
                <div class="form-group">
                    <label>Auditor</label>
                    <span class="text-danger">&#42;</span>
                    <table id="auditor" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>
                                </th>
                                <th class="text-center">Nama</th>
                                <th class="text-center">Email</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>

                    @if ($errors->has('auditor'))
                        <span class="text-danger d-block" style="font-size: 14px">{{ $errors->first('auditor') }}</span>
                    @endif
                </div>
                <!-- /.form-group -->
                <div>
                    <a href="{{ route('auditor') }}" class="btn btn-outline-secondary">Kembali</a>
                    <x-button-submit text="Tambah Auditor" formId="create-form" />
                </div>
            </div>
        </form>
    </div>
@endsection

@section('style')
    <!-- DataTables -->
    <link rel="stylesheet" href="{{ asset('plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/iCheck/1.0.2/skins/flat/blue.css">
    <link rel="stylesheet"
        href="https://gyrocode.github.io/jquery-datatables-checkboxes/1.2.7/css/dataTables.checkboxes.css">
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
            var table = $('#auditor').DataTable({
                "processing": true,
                "serverSide": true,
                "ajax": "{{ route('auditor.get_users', $jadwalAudit->id) }}",
                'drawCallback': function() {
                    $('input[type="checkbox"]').iCheck({
                        "checkboxClass": 'icheckbox_flat-blue'
                    });
                },
                "columns": [{
                        'targets': 0,
                        'data': "checkbox",
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
                        "data": "name",
                        "className": "text-center"
                    },
                    {
                        "data": "email",
                        "className": "text-center"
                    },
                ],
                "responsive": true,
                "autoWidth": false,
                "columnDefs": [{
                        "width": "3%",
                        "targets": [0]
                    },
                    {
                        "width": "46%",
                        "targets": [1]
                    },
                    {
                        "width": "46%",
                        "targets": [2]
                    },
                ],
                'select': {
                    'style': 'multi'
                },
                'order': [
                    [1, 'asc']
                ],
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
                        .attr('name', 'auditor[]')
                        .val(rowId)
                    );

                });

                form.submit();
            });
        });
    </script>
@endsection
