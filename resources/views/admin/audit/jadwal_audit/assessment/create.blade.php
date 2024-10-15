@extends('components.layout.main_layout')

@section('content')
    <div class="card card-primary">
        <div class="card-header">
            <h3 class="card-title title-size">{{ $title }}</h3>
        </div>
        <!-- /.card-header -->
        <!-- form start -->
        <form method="POST" action="{{ route('assessment.store', $jadwalAudit->id) }}" id="create-form">
            @csrf
            <div class="card-body">

                {{-- pertanyaan --}}
                <div class="form-group">
                    <label>Pertanyaan</label>
                    <span class="text-danger">&#42;</span>
                    <table id="pertanyaan" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th></th>
                                <th class="text-center">Pertanyaan</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>

                    @if ($errors->has('pertanyaan'))
                        <span class="text-danger d-block" style="font-size: 14px">{{ $errors->first('pertanyaan') }}</span>
                    @endif
                </div>

                <!-- /.form-group -->
                <div>
                    <a href="{{ route('jadwal_audit') }}" class="btn btn-outline-secondary">Kembali</a>
                    <x-button-submit text="Tambah Assessment" formId="create-form" />
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
            var table = $('#pertanyaan').DataTable({
                "processing": true,
                "serverSide": true,
                "ajax": "{{ route('assessment.get_assessment_pertanyaan') }}",
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
                        "data": "pertanyaan",
                    },
                ],
                "responsive": true,
                "autoWidth": false,
                "columnDefs": [{
                    "width": "3%",
                    "targets": [0]
                }, ],
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
                        .attr('name', 'pertanyaan[]')
                        .val(rowId)
                    );

                });

                form.submit();
            });
        });
    </script>
@endsection
