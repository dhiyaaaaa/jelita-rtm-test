@extends('components.layout.main_layout')

@section('content')
    <div class="card card-dark">
        <div class="card-header">
            <h3 class="card-title title-size">{{ $title }}</h3>
        </div>
        <!-- /.card-header -->
        <div class="card-body">
            <div class="d-flex">
                <a href="{{ route('instrumen.create') }}" class="btn btn-outline-primary mb-3">+ Tambah Instrumen</a>
                {{-- Download Instrumen --}}
                <div class="ml-3">
                    <form action="{{ route('download.instrumen') }}" method="post">
                        @csrf
                        <button type="submit" class="btn btn-outline-dark">
                            <i class="fas fa-download mr-2"></i> Instrumen
                        </button>
                    </form>
                </div>
            </div>

            {{-- Filter --}}
            <div class="row">
                {{-- Jenjang/Auditee --}}
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Jenjang/Auditan:</label>
                        <select class="select2" multiple="multiple" data-placeholder="All" style="width: 100%;"
                            id="jenjangAuditee">
                            @foreach ($jenjangAuditee as $item)
                                <option value="{{ $item->nama }}">{{ $item->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                {{-- Unit/Lembaga --}}
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Unit/Lembaga:</label>
                        <select class="select2" style="width: 100%;" data-placeholder="Pilih Unit/Lembaga" id="unit">
                            <option selected value="all">All</option>
                            @foreach ($units as $unit)
                                <option value="{{ $unit->nama }}">{{ $unit->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                {{-- Level --}}
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Level:</label>
                        <select class="select2" style="width: 100%;" data-placeholder="Pilih Level" id="level">
                            <option selected value="all">All</option>
                            @php
                                $hasUpps = false;
                            @endphp
                            @foreach ($level as $item)
                                @if ($item->slug === 'prodi')
                                    <option value="ps">PS</option>
                                @elseif (($item->slug === 'fakultas' || $item->slug === 'universitas') && !$hasUpps)
                                    <option value="upps">UPPS</option>
                                    @php
                                        $hasUpps = true;
                                    @endphp
                                @endif
                            @endforeach

                        </select>
                    </div>
                </div>
            </div>

            <div class="mb-3">
                <button type="button" id="button-search" class="btn btn-primary">Search</button>
                <button id="button-search-loading" class="btn btn-primary d-none" type="button" disabled>
                    <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                    Loading...
                </button>
            </div>

            <table id="instrumen" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th class="text-center">No</th>
                        <th class="text-center">Kode</th>
                        <th class="text-center">Pernyataan</th>
                        <th class="text-center">Jenjang/Auditan</th>
                        <th class="text-center">Unit/Lembaga</th>
                        <th class="text-center">Level</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>

        </div>
        <!-- /.card-body -->
    </div>
@endsection


@section('style')
    <!-- DataTables -->
    <link rel="stylesheet" href="{{ asset('plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
    <!-- Select2 -->
    <link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset('dist/css/adminlte.min.css') }}">
@endsection

@section('script')
    <!-- DataTables & Plugins -->
    <script src="{{ asset('plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>

    <!-- Select2 -->
    <script src="{{ asset('plugins/select2/js/select2.full.min.js') }}"></script>

    <!-- Page specific script -->
    <script>
        $(function() {
            $('.select2').select2();


            const table = $("#instrumen").DataTable({
                "processing": true,
                "serverSide": true,
                "pageLength": 25,
                "ajax": {
                    "url": "{{ route('instrumen.get_instrumen') }}",
                    "data": function(d) {
                        d.jenjangAuditee = $('#jenjangAuditee').val();
                        d.level = $('#level').val();
                        d.unit = $('#unit').val();
                    },
                    "error": function(xhr, error, thrown) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: 'Terjadi kesalahan saat memuat data!',
                        });

                        document.getElementById('button-search').classList.remove('d-none');
                        document.getElementById('button-search-loading').classList.add('d-none');
                    }
                },
                "columns": [{
                        "data": null,
                        "sortable": false,
                        "render": function(data, type, row, meta) {
                            return meta.row + meta.settings._iDisplayStart + 1;
                        },
                        "className": "text-center"
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
                    {
                        "data": "action",
                        "orderable": false,
                        "searchable": false,
                        "className": "text-center"
                    }
                ],
                "responsive": true,
                "autoWidth": false,
                "columnDefs": [{
                    "width": "5%",
                    "targets": [0]
                }, ]
            })

            // $('#jenjangAuditee, #level, #unit').on('change', function() {
            //     table.draw();
            // });

            $('#button-search').on('click', function() {
                document.getElementById('button-search').classList.add('d-none');
                document.getElementById('button-search-loading').classList.remove('d-none');

                table.draw();

                $.ajax({
                    url: "{{ route('instrumen.get_instrumen') }}",
                    data: {
                        jenjangAuditee: $('#jenjangAuditee').val(),
                        level: $('#level').val(),
                        unit: $('#unit').val(),
                    },
                    success: function(data) {
                        document.getElementById('button-search').classList.remove('d-none');
                        document.getElementById('button-search-loading').classList.add(
                            'd-none');

                        if (data.data.length > 0) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Pencarian berhasil',
                                text: 'Instrumen berhasil ditemukan!'
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Tidak ditemukan',
                                text: 'Tidak ada instrumen yang cocok dengan filter!'
                            });
                        }
                    },
                    error: function() {
                        document.getElementById('button-search').classList.remove('d-none');
                        document.getElementById('button-search-loading').classList.add(
                            'd-none');

                        Swal.fire({
                            icon: 'error',
                            title: 'Terjadi kesalahan',
                            text: 'Gagal melakukan pencarian.'
                        });
                    }
                });
            });

        });
    </script>
@endsection
