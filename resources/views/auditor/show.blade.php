@extends('components.layout.main_layout')

@section('content')
    <div class="card card-dark">
        <div class="card-header">
            <h3 class="card-title title-size">{{ $title }}</h3>
        </div>
        <!-- /.card-header -->
        <div class="card-body">
            <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary mb-3">Kembali</a>

            <table id="assessment" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th class="text-center">No</th>
                        <th class="text-center">{{ $type === 'prodi' ? 'Program Studi' : 'Fakultas/Unit' }}</th>
                        @if ($type === 'prodi')
                            <th class="text-center">Fakultas</th>
                        @endif
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($units as $item)
                        <tr>
                            <td class="text-center">{{ $loop->iteration }}</td>
                            <td>{{ $type === 'prodi' ? $item->nama . ' ' . $item->jenjang->nama : $item->nama }}</td>
                            @if ($type === 'prodi')
                                <td class="text-center">{{ $item->fakultas->nama }}</td>
                            @endif
                            <td class="text-center">
                                @if ($item->auditor->isEmpty())
                                    <form id="pilih-form-{{ $item->id }}"
                                        action="{{ route('auditor.unit.pilih', ['auditor' => $auditor->id, 'unit' => $item->id, 'type' => $item->type]) }}"
                                        method="post" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-primary choose-button"
                                            data-id="{{ $item->id }}">Pilih</button>
                                    </form>
                                @else
                                    <button type="button" class="btn btn-secondary btn-disabled">Sudah dipilih</button>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3">Tidak Tersedia</td>
                        </tr>
                    @endforelse
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

            var table = $("#assessment").DataTable({
                "responsive": true,
                "autoWidth": false,
                "pageLength": 25,
                "columnDefs": [{
                    "width": "5%",
                    "targets": [0]
                }, ]
            })

            $(document).on('click', '.choose-button', function(event) {
                event.preventDefault();
                var id = $(this).data('id');
                Swal.fire({
                    title: "Pilih Unit!'",
                    text: "Apakah Anda yakin ingin memilih? Pastikan unit yang dipilih sesuai dengan surat tugas. Anda tidak bisa membatalkannya, jika ingin membatalkan silahkan hubungi Admin",
                    icon: "warning",
                    showCancelButton: true,
                    cancelButtonText: "Batal",
                    confirmButtonColor: "#007bff",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Ya, Pilih!"
                }).then((result) => {
                    if (result.isConfirmed) {
                        $('#pilih-form-' + id).submit();
                    }
                });
            });
        });
    </script>
@endsection
