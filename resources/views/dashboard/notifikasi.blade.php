@extends('components.layout.main_layout')

@section('content')
    <div class="row">
        <div class="col-md-12">
            @if ($notifikasi->isNotEmpty())
                <div class="timeline">
                    @foreach ($notifikasi as $date => $items)
                        <div class="time-label">
                            <span class="bg-red">{{ $date }}</span>
                        </div>

                        @foreach ($items as $item)
                            @php
                                $unitId = $item->prodi_id ?? ($item->fakultas_id ?? $item->unit_id);
                                $type = $item->prodi_id ? 'prodi' : ($item->fakultas_id ? 'fakultas' : 'universitas');
                                $auditee = $auditees->where('jadwal_audit_id', $item->jadwal_audit_id)->first();
                                $auditeeId = $auditee ? $auditee->id : null;
                            @endphp

                            <div>
                                <i class="fas fa-envelope bg-blue"></i>
                                <div class="timeline-item">
                                    <span class="time"><i class="fas fa-clock"></i>
                                        {{ \Carbon\Carbon::parse($item->created_at)->format('H:i') }}</span>

                                    @role('auditor')
                                        <h3 class="timeline-header"><a href="#">{{ $item->auditee->user->name }}</a>
                                            mengirim notifikasi pada <span
                                                class="text-bold">{{ $item->jadwal_audit->jadwal }}</span>
                                        </h3>
                                    @endrole
                                    @role(['pj_universitas', 'pj_fakultas', 'pj_prodi'])
                                        <h3 class="timeline-header"><a href="#">{{ $item->auditor->user->name }}</a>
                                            mengirim notifikasi pada <span
                                                class="text-bold">{{ $item->jadwal_audit->jadwal }}</span></h3>
                                    @endrole

                                    <div class="timeline-body">
                                        @role('auditor')
                                            @if ($type === 'prodi')
                                                <span class="text-bold">Prodi {{ $item->prodi->nama }}</span>
                                            @elseif($type === 'fakultas')
                                                <span class="text-bold">Fakultas {{ $item->fakultas->nama }}</span>
                                            @elseif($type === 'universitas')
                                                <span class="text-bold">Unit {{ $item->unit->nama }}</span>
                                            @else
                                                -
                                            @endif
                                        @endrole
                                        <p style="margin: 0; padding:0;">
                                            <span class="badge badge-success">{{ $item->form->instrumen->kode }}</span>
                                        </p>
                                        @role('auditor')
                                            Jawaban sudah diubah
                                        @endrole
                                        @role(['pj_universitas', 'pj_fakultas', 'pj_prodi'])
                                            {{ $item->pesan }}
                                        @endrole
                                    </div>

                                    <div class="timeline-footer" style="margin-top:-10px;">
                                        @role('auditor')
                                            <a href="{{ route('auditor.dokumen.create', ['jadwalAudit' => $item->jadwal_audit_id, 'unit' => $unitId, 'type' => $type]) }}"
                                                class="btn btn-success btn-sm {{ $item->status == 'diterima' ? '' : 'disabled' }}">
                                                {{ ucfirst($item->status == 'diterima' ? 'Lihat' : $item->status) }}
                                            </a>
                                        @endrole
                                        @role(['pj_universitas', 'pj_fakultas', 'pj_prodi'])
                                            <a href="{{ $auditeeId && $item->status == 'terkirim' ? route('auditee.dokumen.create', ['jadwalAudit' => $item->jadwal_audit_id, 'unit' => $unitId, 'type' => $type]) : '#' }}"
                                                class="btn btn-success btn-sm {{ $auditeeId && $item->status == 'terkirim' ? '' : 'disabled' }}">
                                                {{ ucfirst($item->status == 'terkirim' ? 'Lihat' : $item->status) }}
                                            </a>
                                        @endrole
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @endforeach

                    <div><i class="fas fa-clock bg-gray"></i></div>
                </div>
            @else
                Belum ada notifikasi
            @endif
        </div>
    </div>
@endsection
