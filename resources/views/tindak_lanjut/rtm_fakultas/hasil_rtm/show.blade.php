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
            <a href="{{ route('hasil_rtm.index') }}" class="btn btn-outline-dark mb-3">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
            <table id="admin" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th class="text-center ">No</th>
                        <th class="text-center">Unit/Fakultas</th>
                        <th class="text-center">Agenda RTM</th>
                        <th class="text-center">Tanggal</th>
                        <th class="text-center">Detail Agenda</th>
                        <th class="text-center">Download Laporan RTM</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($fakultasList as $fakultas)
                        @php
                            $rtm = $rtmJadwalList->firstWhere('fakultas_id', $fakultas->id);
                        @endphp
                        <tr>
                            <td class="text-center align-middle">{{ $loop->iteration }}</td>
                            <td class="text-center align-middle">Fakultas {{ $fakultas->nama }}</td>
                            <td class="text-center align-middle">{{ $rtm->agenda ?? '-' }}</td>
                            <td class="text-center align-middle">
                                @if($rtm && $rtm->tanggal)
                                    {{ \Carbon\Carbon::parse($rtm->tanggal)->translatedFormat('l, j F Y') }}
                                @else
                                    <p class="text-muted">-</p>
                                @endif
                            </td>
                            <td class="text-center align-middle">
                                @if($rtm)
                                    <a href="{{ route('hasil_rtm_fakultas.detail', $rtm->id) }}" class="btn btn-primary">Detail</a>
                                @else
                                    <p class="text-muted">-</p>
                                @endif
                            </td>
                            <td class="text-center align-middle">
                                @if($rtm)
                                    <a href="{{ route('download.rtm.fakultas', $rtm->id) }}" method="post" class="d-inline">
                                        @csrf
                                        <i class="fas fa-download"></i> Download Laporan RTM
                                    </a>
                                @else
                                    <p class="text-muted">Belum Ada</p>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                    
                    @foreach($unitList as $unit)
                        @php
                            $rtm = $rtmJadwalList->firstWhere('unit_id', $unit->id);
                        @endphp
                        <tr>
                            <td class="text-center align-middle">{{ $loop->iteration }}</td>
                            <td class="text-center align-middle">{{ $unit->nama }}</td>
                            <td class="text-center align-middle">{{ $rtm->agenda ?? '-' }}</td>
                            <td class="text-center align-middle">
                                @if($rtm && $rtm->tanggal)
                                    {{ \Carbon\Carbon::parse($rtm->tanggal)->translatedFormat('l, j F Y') }}
                                @else
                                    -
                                @endif
                            </td>
                            <td class="text-center align-middle">
                                @if($rtm)
                                    <a href="{{ route('hasil_rtm_fakultas.detail', $rtm->id) }}" class="btn btn-primary">Detail</a>
                                @else
                                    <p class="text-muted">-</p>
                                @endif
                            </td>
                            {{-- <td class="align-middle">
                                @if($rtm && $rtm->rtm_rtl->isNotEmpty())
                                    <a href="{{ route('hasil_rtm_rtl.show', $rtm->rtm_rtl->first()->id) }}" class="btn btn-primary">Lihat RTL</a>
                                @else
                                    <p class="text-muted">Belum Ada</p>
                                @endif
                            </td> --}}
                            <td class="text-center align-middle">
                                @if($rtm)
                                    <a href="{{ route('download.rtm.fakultas', $rtm->id) }}" method="post" class="d-inline">
                                        @csrf
                                        <i class="fas fa-download"></i> Download Laporan RTM
                                    </a>
                                @else
                                    <p class="text-muted">Belum Ada</p>
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
            $("#admin").DataTable({
                "responsive": true,
                "autoWidth": false,
            })
        });
    </script>
@endsection