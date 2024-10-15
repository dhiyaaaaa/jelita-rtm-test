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
            <a href="{{ route('hasil_assessment') }}" class="btn btn-outline-secondary mb-3">Kembali</a>

            {{-- Detail Jadwal --}}
            <table class="table mb-3">
                <tr>
                    <td class="text-bold" width="10%">Nama</td>
                    <td width="5%">:</td>
                    <td>{{ $auditor->user->name }}</td>
                </tr>
                <tr>
                    <td class="text-bold" width="10%">Jadwal</td>
                    <td width="5%">:</td>
                    <td>
                        {{ Carbon::parse($auditor->jadwal_audit->tgl_mulai)->translatedFormat('j F Y') }} -
                        {{ Carbon::parse($auditor->jadwal_audit->tgl_selesai)->translatedFormat('j F Y') }}
                    </td>
                </tr>
            </table>

            <table id="assessment" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th class="text-center">No</th>
                        <th class="text-center">Rekan Auditor</th>
                        <th class="text-center">Prodi/Fakultas/Unit yang diaudit</th>
                        <th class="text-center">Total Skor</th>
                        <th class="text-center">Status</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($auditors as $item)
                        <tr>
                            <td class="text-center">{{ $loop->iteration }}</td>
                            <td>{{ $item->auditor->user->name }}</td>
                            <td class="text-center">
                                @if ($item->prodi)
                                    {{ 'Program Studi ' . $item->prodi->nama . ' ' . $item->prodi->jenjang->nama }}
                                @elseif($item->fakultas)
                                    {{ 'Fakultas ' . $item->fakultas->nama }}
                                @elseif($item->unit)
                                    {{ $item->unit->nama }}
                                @else
                                    -
                                @endif
                            </td>
                            <td class="text-center">
                                @php
                                    $jawaban = $item->auditor->assessment_jawaban_penilai->pluck('jawaban');
                                    $skor = $jawaban->map(function ($val) {
                                        return (int) $val;
                                    });
                                    $total = $skor->count() > 0 ? $skor->sum() / $skor->count() : null;
                                    $final_skor = $total !== null ? (number_format($total, 2) . '/5') : '-';
                                @endphp

                                <span class="badge badge-success" style="font-size: 14px">{{ $final_skor }}</span>
                            </td>
                            <td class="text-center">
                                @php
                                    $status = null;
                                    if ($item->auditor->status_assessment_penilai->isNotEmpty()) {
                                        $status = $item->auditor->status_assessment_penilai->where(
                                            'jadwal_audit_id',
                                            $item->jadwal_audit->id,
                                        );
                                        if ($item->prodi) {
                                            $status = $item->auditor->status_assessment_penilai
                                                ->where('jadwal_audit_id', $item->jadwal_audit->id)
                                                ->where('prodi_id', $item->prodi_id)
                                                ->first();
                                        } elseif ($item->fakultas) {
                                            $status = $item->auditor->status_assessment_penilai
                                                ->where('jadwal_audit_id', $item->jadwal_audit->id)
                                                ->where('fakultas_id', $item->fakultas_id)
                                                ->first();
                                        } elseif ($item->unit) {
                                            $status = $item->auditor->status_assessment_penilai
                                                ->where('jadwal_audit_id', $item->jadwal_audit->id)
                                                ->where('unit_id', $item->unit_id)
                                                ->first();
                                        }
                                    }
                                    $unit = get_type_model($item);
                                @endphp
                                @if ($status && $status->status === 'selesai')
                                    <a href="{{ route('hasil_assessment.show_assessment', ['auditorDinilai' => $auditor->id, 'auditorPenilai' => $item->auditor->id, 'unit' => $unit['value'], 'type' => $unit['type']]) }}"
                                        class="btn btn-info">Sudah Isi</a>
                                @else
                                    <a href="{{ route('hasil_assessment.show_assessment', ['auditorDinilai' => $auditor->id, 'auditorPenilai' => $item->auditor->id, 'unit' => $unit['value'], 'type' => $unit['type']]) }}"
                                        class="btn btn-outline-primary">Belum mengisi</a>
                                @endif
                            </td>
                        </tr>

                    @empty
                        <tr>
                            <td colspan="5" class="text-center">Belum ada Rekan Auditor</td>
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
        $(document).ready(function() {
            $("#assessment").DataTable({
                "responsive": true,
                "autoWidth": false,
                "pageLength": 25,
                "columnDefs": [{
                        "width": "5%",
                        "targets": [0]
                    },
                    {
                        "width": "30%",
                        "targets": [1]
                    },
                    {
                        "width": "25%",
                        "targets": [1]
                    },
                    {
                        "width": "20%",
                        "targets": [1]
                    },
                    {
                        "width": "20%",
                        "targets": [1]
                    },
                ]
            });
        });
    </script>
@endsection
