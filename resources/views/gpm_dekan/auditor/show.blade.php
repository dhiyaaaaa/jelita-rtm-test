@extends('components.layout.main_layout')

@section('content')
    <div class="card card-dark">
        <div class="card-header">
            <h3 class="card-title title-size">{{ $title }}</h3>
        </div>
        <!-- /.card-header -->
        <div class="card-body">
            <div>
                <h5>Program Studi</h5>
                <a href="{{ route('gpm.auditor') }}" class="btn btn-outline-secondary mb-3">Kembali</a>

                <table id="ps" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th class="text-center">No</th>
                            <th class="text-center">Prodi</th>
                            <th class="text-center">Auditan</th>
                            <th class="text-center">Auditor</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $no = 1;
                        @endphp

                        @foreach ($prodi as $item)
                            <tr>
                                <td class="text-center">{{ $loop->iteration }}</td>
                                <td>{{ $item->nama }} {{ $item->jenjang->nama }}</td>
                                <td>
                                    @if ($item->auditee->isNotEmpty())
                                        @foreach ($item->auditee as $auditee)
                                            <p style="margin:0; padding:0;">{{ $loop->iteration }}.
                                                {{ $auditee->user->name }}
                                            </p>
                                        @endforeach
                                    @else
                                        <div class="text-center">
                                            <a class="btn disabled ">Belum ada Auditan</a>
                                        </div>
                                    @endif
                                </td>
                                <td>
                                    @if ($item->auditee->isNotEmpty())
                                        @php
                                            $uniqueAuditors = collect();
                                            $noAuditorDisplayed = false;
                                        @endphp
                                        @foreach ($item->auditee as $auditee)
                                            @if ($auditee->auditor->isNotEmpty())
                                                @foreach ($auditee->auditor as $auditor)
                                                    @if (!$uniqueAuditors->contains('user_id', $auditor->user_id))
                                                        <p style="margin:0; padding:0;">Auditor {{ $loop->iteration }} :
                                                            {{ $auditor->user->name }}</p>
                                                        @php
                                                            $uniqueAuditors->push($auditor);
                                                        @endphp
                                                    @endif
                                                @endforeach
                                            @else
                                                @if (!$noAuditorDisplayed)
                                                    <div class="text-center">
                                                        <a class="btn disabled ">Belum ada Auditor</a>
                                                    </div>
                                                    @php
                                                        $noAuditorDisplayed = true;
                                                    @endphp
                                                @endif
                                            @endif
                                        @endforeach
                                    @else
                                        <div class="text-center">
                                            <a class="btn disabled ">Belum ada Auditor</a>
                                        </div>
                                    @endif
                                </td>

                                <td class="text-center">
                                    @if ($item->auditee->isNotEmpty())
                                        @php
                                            $auditorsExist = false;
                                        @endphp
                                        @foreach ($item->auditee as $auditee)
                                            @if ($auditee->auditor->isNotEmpty())
                                                @php
                                                    $auditorsExist = true;
                                                    break;
                                                @endphp
                                            @endif
                                        @endforeach

                                        @if ($auditorsExist)
                                            <a class="btn btn-warning"
                                                href="{{ route('gpm.auditor.edit', ['jadwalAudit' => $jadwalAudit->id, 'unit' => $item->id]) }}">Ubah
                                                Auditor</a>
                                        @else
                                            <a class="btn btn-primary"
                                                href="{{ route('gpm.auditor.create', ['jadwalAudit' => $jadwalAudit->id, 'unit' => $item->id]) }}">+ Tambah
                                                Auditor</a>
                                        @endif
                                        {{-- <a href="{{ route('auditee.edit', ['jadwalAudit' => $jadwalAudit->id, 'unit' => $item->id, 'type' => $item->type]) }}"
                                                    class="dropdown-item">Ubah Auditan</a>
                                                <a href="{{ route('auditee.delete', ['jadwalAudit' => $jadwalAudit->id, 'unit' => $item->id, 'type' => $item->type]) }}"
                                                    class="dropdown-item" data-confirm-delete="true">Hapus</a> --}}
                                    @else
                                        <div class="text-center">
                                            <a class="btn disabled ">-</a>
                                        </div>
                                    @endif
                                </td>
                            </tr>
                        @endforeach

                    </tbody>
                </table>
            </div>
        </div>
        <!-- /.card-body -->
    </div>
@endsection


@section('style')
    <!-- DataTables -->
    <link rel="stylesheet" href="{{ asset('plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
@endsection

@section('script')
    <!-- DataTables & Plugins -->
    <script src="{{ asset('plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>



    <!-- Page specific script -->
    <script>
        $(function() {
            $("#ps").DataTable({
                "responsive": true,
                "autoWidth": false,
                "pageLength": 25,
                "columnDefs": [{
                    "width": "5%",
                    "targets": [0]
                }, {
                    "width": "20%",
                    "targets": [1]
                }, {
                    "width": "30%",
                    "targets": [2]
                }, {
                    "width": "30%",
                    "targets": [3]
                }, ]
            });
        });
    </script>
@endsection
