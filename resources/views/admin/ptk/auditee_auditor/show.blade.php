@extends('components.layout.main_layout')

@section('content')
    <div class="card card-dark">
        <div class="card-header">
            <h3 class="card-title title-size">{{ $title }}</h3>
        </div>
        <!-- /.card-header -->
        <div class="card-body">
            {{-- UPPS --}}
                <div>
                    <h5>Unit Pengelola Program Studi</h5>
                    <a href="{{ route('auditee_auditor_ptk.index') }}" class="btn btn-outline-secondary mb-3">Kembali</a>
                    <table id="upps" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th class="text-center">No</th>
                                <th class="text-center">Fakultas/Unit</th>
                                <th class="text-center">Auditan</th>
                                <th class="text-center">Auditor</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $no = 1;
                            @endphp

                            @foreach ($upps as $item)
                                <tr>
                                    <td class="text-center">{{ $loop->iteration }}</td>
                                    <td>{{ $item->nama }}</td>
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
                                                @if ($auditee->auditorPtk->isNotEmpty())
                                                    @foreach ($auditee->auditorPtk as $auditor)
                                                        @if (!$uniqueAuditors->contains('user_id', $auditor->user_id))
                                                            <p style="margin:0; padding:0;">Auditor {{ $loop->iteration }}
                                                                :
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
                                            <div class="dropdown">
                                                <button class="btn btn-success dropdown-toggle" type="button"
                                                    id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true"
                                                    aria-expanded="false">
                                                    Actions
                                                </button>
                                                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                    @php
                                                        $auditorsExist = false;
                                                    @endphp
                                                    @foreach ($item->auditee as $auditee)
                                                        @if ($auditee->auditorPtk->isNotEmpty())
                                                            @php
                                                                $auditorsExist = true;
                                                                break;
                                                            @endphp
                                                        @endif
                                                    @endforeach

                                                    @if ($auditorsExist)
                                                        <a class="dropdown-item"
                                                            href="{{ route('auditee_auditor_ptk.edit', ['jadwalAudit' => $jadwalAudit->id, 'unit' => $item->id, 'type' => $item->type]) }}">Ubah
                                                            Auditor</a>
                                                    @else
                                                        <a class="dropdown-item"
                                                            href="{{ route('auditee_auditor_ptk.create', ['jadwalAudit' => $jadwalAudit->id, 'unit' => $item->id, 'type' => $item->type]) }}">Tambah
                                                            Auditor</a>
                                                    @endif
                                                    <a href="{{ route('auditee_auditor_ptk.delete', ['jadwalAudit' => $jadwalAudit->id, 'unit' => $item->id, 'type' => $item->type]) }}"
                                                        class="dropdown-item" data-confirm-delete="true">Hapus Auditor</a>
                                                </div>
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
                    "width": "25%",
                    "targets": [1]
                }, {
                    "width": "30%",
                    "targets": [2]
                }, {
                    "width": "30%",
                    "targets": [3]
                }, ]
            });
            $("#upps").DataTable({
                "responsive": true,
                "autoWidth": false,
                "pageLength": 25,
                "columnDefs": [{
                    "width": "5%",
                    "targets": [0]
                }, {
                    "width": "25%",
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
