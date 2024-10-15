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
                        <th class="text-center">Berita Acara</th>
                        <th class="text-center">Temuan Negatif</th>
                        <th class="text-center">Temuan Positif</th>

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

                            {{-- Berita Acara --}}
                            <td class="text-center align-middle">
                                @if ($item->berita_acara->isNotEmpty())
                                    @php
                                        $berita_acara = $item->berita_acara->first();
                                    @endphp
                                    {{-- {{ $berita_acara }} --}}
                                    {{-- Approve --}}
                                    @php
                                        $auditee = $berita_acara->auditee->first();
                                    @endphp
                                    @if ($auditee)
                                        @if ($auditee->pivot->approve == 0)
                                            @if (!$item->expired)
                                                <form
                                                    action="{{ route('auditee.lapangan.berita_acara.approve', ['beritaAcara' => $berita_acara->id, 'auditee' => $berita_acara->auditee->first()->pivot->auditee_id]) }}"
                                                    method="post" class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="btn btn-outline-success">Approve</button>
                                                </form>
                                            @else
                                                <button disabled="disabled" class="btn btn-secondary">Not Approved</button>
                                            @endif
                                        @else
                                            <button disabled="disabled" class="btn btn-secondary">Approved</button>
                                        @endif
                                    @endif

                                    {{-- Download --}}
                                    <form action="{{ route('download.berita-acara', $berita_acara->id) }}" method="post"
                                        class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-outline-dark">
                                            <i class="fa fa-download p-1"></i>
                                        </button>
                                    </form>
                                @else
                                    <a href="#" class="btn disabled">Belum Ada</a>
                                @endif
                            </td>


                            {{-- Ptk --}}
                            <td class="text-center align-middle">
                                @if ($item->ptk->isNotEmpty())
                                    @php
                                        $ptk = $item->ptk->first();
                                    @endphp

                                    {{-- Isi PTK --}}
                                    @if ($ptk->status_ptk_auditee->isNotEmpty())
                                        @if (!$item->expired)
                                            @if ($ptk->status_ptk_auditee->first()->status === 'completed')
                                                <a href="{{ route('auditee.lapangan.create_ptk', $ptk->id) }}"
                                                    class="btn btn-info">Sudah Isi</a>
                                            @else
                                                <form
                                                    action="{{ route('auditee.lapangan.isi_ptk', ['ptk' => $ptk->id]) }}"
                                                    method="post" class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="btn btn-outline-info">Isi</button>
                                                </form>
                                            @endif
                                        @else
                                            @if ($ptk->status_ptk_auditee->first()->status === 'completed')
                                                <a href="{{ route('auditee.lapangan.create_ptk', $ptk->id) }}"
                                                    class="btn btn-info">Sudah Isi</a>
                                            @else
                                                <a href="{{ route('auditee.lapangan.create_ptk', $ptk->id) }}"
                                                    class="btn btn-outline-primary">Lihat</a>
                                            @endif
                                        @endif
                                    @else
                                        @if (!$item->expired)
                                            <form action="{{ route('auditee.lapangan.isi_ptk', ['ptk' => $ptk->id]) }}"
                                                method="post" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-outline-info">Isi</button>
                                            </form>
                                        @else
                                            <a href="{{ route('auditee.lapangan.create_ptk', $ptk->id) }}"
                                                class="btn btn-outline-primary">Lihat</a>
                                        @endif
                                    @endif

                                    {{-- Approve PTK --}}
                                    @php
                                        $ptk_auditee = $ptk->auditee->first();
                                    @endphp
                                    @if ($ptk_auditee)
                                        @if ($ptk_auditee->pivot->approve == 0)
                                            @if (!$item->expired)
                                                <form
                                                    action="{{ route('auditee.lapangan.approve_ptk', ['ptk' => $ptk->id, 'auditee' => $ptk->auditee->first()->pivot->auditee_id]) }}"
                                                    method="post" class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="btn btn-outline-success">Approve</button>
                                                </form>
                                            @else
                                                <button disabled="disabled" class="btn btn-secondary">Not Approved</button>
                                            @endif
                                        @else
                                            <button disabled="disabled" class="btn btn-secondary">Approved</button>
                                        @endif
                                    @endif

                                    <form action="{{ route('download.ptk', $ptk->id) }}" method="post" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-outline-dark">
                                            <i class="fa fa-download p-1"></i>
                                        </button>
                                    </form>
                                @else
                                    <a href="#" class="btn disabled">Belum Ada</a>
                                @endif
                            </td>

                            {{-- Laporan --}}
                            <td class="text-center align-middle">
                                @if ($item->laporan->isNotEmpty())
                                    @php
                                        $laporan = $item->laporan->first();
                                    @endphp
                                    {{-- Approve Laporan --}}
                                    @php
                                        $laporan_auditee = $laporan->auditee->first();
                                    @endphp
                                    @if ($laporan_auditee)
                                        @if ($laporan_auditee->pivot->approve == 0)
                                            @if (!$item->expired)
                                                <form
                                                    action="{{ route('auditee.lapangan.laporan.approve', ['laporan' => $laporan->id, 'auditee' => $laporan->auditee->first()->pivot->auditee_id]) }}"
                                                    method="post" class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="btn btn-outline-success">Approve</button>
                                                </form>
                                            @else
                                                <button disabled="disabled" class="btn btn-secondary">Not Approved</button>
                                            @endif
                                        @else
                                            <button disabled="disabled" class="btn btn-secondary">Approved</button>
                                        @endif
                                    @endif

                                    {{-- Download Laporan --}}
                                    @if ($laporan)
                                        <form action="{{ route('download.laporan', $laporan->id) }}" method="post"
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
                "columnDefs": [{
                        "width": "5%",
                        "targets": [0]
                    },
                    {
                        "width": "10%",
                        "targets": [1]
                    },
                    {
                        "width": "15%",
                        "targets": [2]
                    },
                    {
                        "width": "20%",
                        "targets": [3]
                    },
                    {
                        "width": "30%",
                        "targets": [4]
                    },
                    {
                        "width": "20%",
                        "targets": [5]
                    },
                ]
            });
        })
    </script>
@endsection
