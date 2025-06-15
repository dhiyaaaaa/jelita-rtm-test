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
            @role(['pusjamu'])
                <div>
                    <table id="rtm" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th class="text-center">No</th>
                                <th class="text-center">Jadwal</th>
                                <th class="text-center">Periode Audit</th>
                                <th class="text-center">RTM Fakultas</th>
                                <th class="text-center">Tindak Lanjut PTK</th>
                            </tr>
                        </thead>
                        <tbody>

                            @foreach ($jadwalAudit as $item)
                                <tr>
                                    <td class="text-center">{{ $loop->iteration }}</td>
                                    <td class="text-center">{{ $item->jadwal }}</td>
                                    <td class="text-center">{{ Carbon::parse($item->tgl_mulai)->translatedFormat('j F Y') }}-
                                        {{ Carbon::parse($item->tgl_selesai)->translatedFormat('j F Y') }}
                                    </td>
                                    <td class="text-center">
                                        <a class="btn btn-outline-primary"
                                            href="{{ route('hasil_rtm_fakultas.show', ['jadwalAudit' => $item->id]) }}">
                                            <i class="fa fa-eye"></i> Lihat RTM Fakultas</a>
                                    </td>
                                    <td class="text-center">
                                        <a class="btn btn-outline-primary"
                                            href="{{ route('hasil_audit_ptk.show', ['jadwalAudit' => $item->id]) }}">
                                            <i class="fa fa-eye"></i> Lihat Hasil Audit PTK</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endrole

            @role(['pj_universitas', 'pj_fakultas'])
                @php
                    $isKetuaLP3M = auth()->user()->jabatan->contains('slug', 'ketua-lp3m');
                @endphp
                <div>
                    <table id="rtm" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th class="text-center">No</th>
                                <th class="text-center">Jadwal</th>
                                <th class="text-center">Periode Audit</th>
                                <th class="text-center">Hasil RTM Universitas</th>
                                <th class="text-center">Aksi</th>
                                @if($isKetuaLP3M)
                                <th class="text-center">Hasil Audit PTK UPPS</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>

                            @foreach ($jadwalAudit as $item)
                                <tr>
                                    <td class="text-center">{{ $loop->iteration }}</td>
                                    <td class="text-center">{{ $item->jadwal }}</td>
                                    <td class="text-center">{{ Carbon::parse($item->tgl_mulai)->translatedFormat('j F Y') }}-
                                        {{ Carbon::parse($item->tgl_selesai)->translatedFormat('j F Y') }}
                                    </td>
                                    <td class="text-center align-middle">
                                        @php
                                            $rtm_jadwal_univ = $item->rtm_jadwal->whereNull('fakultas_id')->whereNull('unit_id')->first();
                                        @endphp

                                        @if ($rtm_jadwal_univ)
                                            <form action="{{ route('download.rtm.univ', ['rtmJadwal' => $rtm_jadwal_univ->id]) }}" class="d-inline">
                                                <button type="submit" class="btn btn-primary w-100">
                                                    <i class="fas fa-download"></i> Download Laporan RTM
                                                </button>
                                            </form>
                                        @else
                                            <a href="#" class="btn disabled">Belum Ada</a>
                                        @endif
                                    </td>
                                    <td class="text-center align-middle">
                                        @if ($item->rtm_rtl_univ->isNotEmpty())
                                            @php
                                                // Ambil ID fakultas/unit user dari pivot
                                                $userFakultasId = $user->jabatan->pluck('pivot.fakultas_id')->filter()->first();
                                                $userUnitId = $user->jabatan->pluck('pivot.unit_id')->filter()->first();
                                                
                                                // Cari RTM/RTL Univ yang sesuai
                                                $rtmRtlUniv = $item->rtm_rtl_univ->filter(function($rtm) use ($userFakultasId, $userUnitId) {
                                                    return $rtm->fakultas_id == $userFakultasId || $rtm->unit_id == $userUnitId;
                                                })->first();
                                                
                                                // Cek approval
                                                $isApproved = $rtmRtlUniv ? $rtmRtlUniv->user->contains($user->id) : false;
                                            @endphp

                                            @if($rtmRtlUniv)
                                                @if($isApproved)
                                                    <button disabled class="btn btn-secondary w-100">
                                                        <i class="fas fa-check-circle"></i> Laporan RTM Approved
                                                    </button>
                                                @else
                                                    <form action="{{ route('dekan.rtm-rtl-univ.approve', [$rtmRtlUniv->id, $user->id]) }}" method="post">
                                                        @csrf
                                                        <button type="submit" class="btn btn-outline-success w-100">
                                                            <i class="fas fa-thumbs-up"></i> Approve Laporan RTM
                                                        </button>
                                                    </form>
                                                @endif
                                            @else
                                                <a href="#" class="btn disabled">Belum Ada</a>
                                            @endif
                                        @else
                                            <a href="#" class="btn disabled">Belum Ada</a>
                                        @endif
                                    </td>

                                    @if($isKetuaLP3M)
                                        <td class="text-center align-middle">
                                            <a class="btn btn-outline-primary"
                                            href="{{ route('hasil_audit_ptk.show', ['jadwalAudit' => $item->id]) }}">
                                            <i class="fa fa-eye"></i> Lihat Hasil Audit PTK</a>
                                        </td>
                                    @endif
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endrole

            @role(['pj_prodi', 'gkm', 'gpm'])
                <div>
                    <table id="rtm" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th class="text-center">No</th>
                                <th class="text-center">Jadwal</th>
                                <th class="text-center">Periode Audit</th>
                                <th class="text-center">Hasil RTM Fakultas</th>
                                <th class="text-center">Hasil RTM Universitas</th>
                            </tr>
                        </thead>
                        <tbody>

                            @foreach ($jadwalAudit as $item)
                                <tr>
                                    <td class="text-center">{{ $loop->iteration }}</td>
                                    <td class="text-center">{{ $item->jadwal }}</td>
                                    <td class="text-center">{{ Carbon::parse($item->tgl_mulai)->translatedFormat('j F Y') }}-
                                        {{ Carbon::parse($item->tgl_selesai)->translatedFormat('j F Y') }}
                                    </td>
                                    <td class="text-center align-middle">
                                        @php
                                        $user = auth()->user();
                                            // Dapatkan fakultas_id dari prodi user yang login
                                            $fakultasId = $user->prodi->first()?->fakultas_id;
                                            
                                            // Cari RTM/RTL yang terkait dengan fakultas prodi yang login
                                            $rtmFakultas = $item->rtm_jadwal->where('fakultas_id', $fakultasId)->first();
                                        @endphp

                                        @if ($rtmFakultas)
                                            <form action="{{ route('download.rtm.fakultas', ['rtmJadwal' => $rtmFakultas->id]) }}" class="d-inline">
                                                <button type="submit" class="btn btn-primary w-100">
                                                    <i class="fas fa-download"></i> Download Laporan RTM Fakultas
                                                </button>
                                            </form>
                                        @else
                                            <a href="#" class="btn disabled">Belum Ada</a>
                                        @endif
                                    </td>
                                    <td class="text-center align-middle">
                                        @php
                                            $rtm_jadwal_univ = $item->rtm_jadwal->whereNull('fakultas_id')->whereNull('unit_id')->first();
                                        @endphp

                                        @if ($rtm_jadwal_univ)
                                            <form action="{{ route('download.rtm.univ', ['rtmJadwal' => $rtm_jadwal_univ->id]) }}" class="d-inline">
                                                <button type="submit" class="btn btn-primary w-100">
                                                    <i class="fas fa-download"></i> Download Laporan RTM
                                                </button>
                                            </form>
                                        @else
                                            <a href="#" class="btn disabled">Belum Ada</a>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endrole
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
            $("#rtm").DataTable({
                "responsive": true,
                "autoWidth": false,
            })
        });
    </script>
@endsection
