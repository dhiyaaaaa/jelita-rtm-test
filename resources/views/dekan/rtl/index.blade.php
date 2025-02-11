@php
    use Carbon\Carbon;
@endphp
@extends('components.layout.main_layout')

@section('content')
    <div class="card card-dark">
        <div class="card-header">
            <h3 class="card-title title-size">{{ $title }}</h3>
        </div>
        <!-- /.card-header -->
        <div class="card-body">
            <table id="auditee" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th class="text-center">No</th>
                        <th class="text-center">Jadwal</th>
                        <th class="text-center">Periode</th>
                        <th class="text-center">Tindakan Koreksi</th>
                        <th class="text-center">Hasil Monitoring Tindak Lanjut Auditor</th>
                    </tr>
                </thead>
                <tbody>
                @foreach ($jadwal as $item)
                    <tr>
                        {{-- No --}}
                        <td class="text-center align-middle">{{ $loop->iteration }}</td>
                
                        {{-- Jadwal --}}
                        <td class="text-center align-middle">
                            {{ $item->jadwal }}
                        </td>
                
                        {{-- Periode --}}
                        <td class="text-center align-middle">
                            {{ Carbon::parse($item->tgl_mulai)->translatedFormat('j F Y') }} -
                            {{ Carbon::parse($item->tgl_selesai)->translatedFormat('j F Y') }}
                        </td>
                
                        {{-- Tindak Lanjut --}}
                        <td class="text-center">
                            @if ($item->rtl->isNotEmpty())
                                @php
                                    $rtl = $item->rtl->first(); // Ambil RTL pertama
                                @endphp
                                    {{-- ISI RTL --}}
                                    @if ($rtl->status_rtl_auditee->isEmpty())
                                        <form action="{{ route('auditee.isian-tindak-lanjut.rtl.isi_rtl', ['rtl' => $rtl->id]) }}" method="post" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-outline-info">Isi</button>
                                        </form>
                                    @else
                                        @php
                                            $status = $rtl->status_rtl_auditee->first();
                                        @endphp
                                        @if ($status->status === 'completed')
                                            <a href="{{ route('auditee.isian-tindak-lanjut.rtl.form', $rtl->id) }}" class="btn btn-info">Sudah Isi</a>
                                        @else
                                            <a href="{{ route('auditee.isian-tindak-lanjut.rtl.form', $rtl->id)}}" class="btn btn-outline-info">Isi</a>
                                        @endif
                                    @endif
                
                                    {{-- Edit RTL --}}
                                    <div class="dropdown d-inline">
                                        <button class="btn btn-outline-warning dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            Aksi
                                        </button>
                                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                            <a class="dropdown-item" href="{{ route('auditee.isian-tindak-lanjut.edit', ['rtl' => $rtl->id]) }}">Edit</a>
                                            <a href="{{ route('auditee.isian-tindak-lanjut.delete', ['rtl' => $rtl->id]) }}" class="dropdown-item" data-confirm-delete="true">Hapus</a>
                                        </div> 
                                    </div>
                                        @php
                                            $rtl = $item->rtl->first();
                                        @endphp
                                        {{-- Approve rtl --}}
                                        @php
                                            $rtl_auditee = $rtl->auditee->first();
                                        @endphp
                                        @if ($rtl_auditee)
                                            @if ($rtl_auditee->pivot->approve == 0)
                                                <form
                                                    action="{{ route('auditee.rtl.approve', ['rtl' => $rtl->id, 'auditee' => $rtl->auditee->first()->pivot->auditee_id]) }}"
                                                    method="post" class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="btn btn-outline-success">Approve</button>
                                                </form>                
                                            @else
                                                <button disabled="disabled" class="btn btn-secondary">Approved</button>
                                            @endif
                                        @endif
                
                                <form action="{{ route('download.rtl', $rtl->id) }}" method="post">
                                    @csrf
                                    <button type="submit" class="btn btn-outline-dark">
                                        <i class="fa fa-download p-1"></i>
                                    </button>
                                </form>
                            @else
                                <a href="{{ route('auditee.isian-tindak-lanjut.create', [
                                    'jadwalAudit' => $item->id, 
                                ]) }}" class="btn btn-primary">Buat Tindakan Koreksi</a>
                            @endif
                        </td>

                        {{-- Monitoring --}}
                        <td class="text-center align-middle">
    
                            @if ($item->monitoring && $item->monitoring->isNotEmpty())
                                @php
                                    $monitoring = $item->monitoring->first();
                                @endphp
                                {{-- Approve monitoring --}}
                                @php
                                    $monitoring_auditee = $monitoring->auditee->first();
                                @endphp
                                @if ($monitoring_auditee)
                                    @if ($monitoring_auditee->pivot->approve == 0)
                                            <form
                                                action="{{ route('auditee.monitoring.approve', ['monitoring' => $monitoring->id, 'auditee' => $monitoring->auditee->first()->pivot->auditee_id]) }}"
                                                method="post" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-outline-success">Approve</button>
                                            </form>
                                    @else
                                        <button disabled="disabled" class="btn btn-secondary">Approved</button>
                                    @endif
                                @endif

                                {{-- Download Monitoring --}}
                                @if ($monitoring)
                                    <form action="{{ route('download.monitoring_rtl', $monitoring->id) }}" method="post"
                                        class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-outline-dark">
                                            <i class="fa fa-download p-1"></i>
                                        </button>
                                    </form>
                                @endif
                            @else
                                <a href="#" class="btn disabled">Belum Ada</a>
                            @endif
                        </td>
                    </tr>
                @endforeach
                
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
    {{-- <link rel="stylesheet" href="{{ asset('plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}"> --}}
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
            $("#auditee").DataTable({
                "responsive": true,
                "autoWidth": false,
            });
        })
    </script>
@endsection
