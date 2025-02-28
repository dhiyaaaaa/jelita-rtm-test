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
            <table id="admin" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th class="text-center">Unit / Fakultas</th>
                        <th class="text-center">Agenda RTM</th>
                        <th class="text-center">Tanggal</th>
                        <th class="text-center">Detail Agenda</th>
                        <th class="text-center">Isian RTL RTM</th>
                        <th class="text-center">Download Laporan RTM</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($rtmJadwalList as $rtm) 
                        <tr>
                            <td class="text-center">{{ optional($rtm->fakultas)->nama ?? optional($rtm->unit)->nama ?? 'Tidak ada data' }}</td>
                            <td class="text-center">{{ $rtm->agenda }}</td>
                            <td class="text-center">{{ \Carbon\Carbon::parse($rtm->tanggal)->translatedFormat('l, j F Y') }}</td>
                            <td class="text-center"><a href="{{ route('hasil_rtm_fakultas.detail', $rtm->id) }}" class="btn btn-primary">Detail</a></td>
                            <td class="text-center">
                                @if($rtm->rtm_rtl->isNotEmpty()) 
                                    <a href="{{ route('hasil_rtm_rtl.show', $rtm->rtm_rtl->first()->id) }}" class="btn btn-primary">Lihat RTL</a>
                                @else
                                    <span class="badge bg-warning">Belum Ada</span>
                                @endif
                            </td>

                            <td class="text-center">
                                <a href="{{ route('download.rtm.fakultas', $rtm->id) }}" method="post" class="d-inline">
                                    @csrf
                                    <i class="fas fa-download"></i> Download Laporan RTM
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center">Data Tidak Ada</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <!-- /.card-body -->
    </div>
@endsection
