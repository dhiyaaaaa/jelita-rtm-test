@extends('components.layout.main_layout')

@section('content')
    <div class="row">
        <div class="col-md-12">
            @php
                $hasAuditorRole = auth()->user()->hasRole('auditor');
                $hasAuditeeRole = auth()->user()->hasAnyRole('pj_universitas|pj_fakultas|pj_prodi|gkm');
            @endphp

            @if ($hasAuditorRole && $hasAuditeeRole)
                <ul class="nav nav-pills mb-3" id="notificationTabs" role="tablist">
                    @hasrole('auditor')
                        <li class="nav-item">
                            <a class="nav-link active" id="auditor-tab" data-toggle="tab" href="#auditor" role="tab"
                                aria-controls="auditor" aria-selected="true">Auditor</a>
                        </li>
                    @endhasrole
                    @hasanyrole('pj_universitas|pj_fakultas|pj_prodi|gkm')
                        <li class="nav-item">
                            <a class="nav-link" id="auditee-tab" data-toggle="tab" href="#auditee" role="tab"
                                aria-controls="auditee" aria-selected="false">Auditan</a>
                        </li>
                    @endhasanyrole
                </ul>
            @endif

            <div class="tab-content" id="notificationTabsContent">
                {{-- Auditor --}}
                @hasrole('auditor')
                    <div class="tab-pane fade show active" id="auditor" role="tabpanel" aria-labelledby="auditor-tab">
                        @if ($notifikasiAuditor->isNotEmpty())
                            <div class="timeline">
                                @foreach ($notifikasiAuditor as $date => $items)
                                    <div class="time-label">
                                        <span class="bg-red">{{ $date }}</span>
                                    </div>
                                    @foreach ($items as $item)
                                        @php
                                            $unitId = $item->prodi_id ?? ($item->fakultas_id ?? $item->unit_id);
                                            $type = $item->prodi_id
                                                ? 'prodi'
                                                : ($item->fakultas_id
                                                    ? 'fakultas'
                                                    : 'universitas');
                                        @endphp
                                        <div>
                                            <i class="fas fa-envelope bg-blue"></i>
                                            <div class="timeline-item">
                                                <span class="time" style="font-size: 16px;"><i class="fas fa-clock"></i>
                                                    {{ \Carbon\Carbon::parse($item->created_at)->format('H:i') }}
                                                </span>
                                                <h3 class="timeline-header">
                                                    <a href="#">{{ $item->auditee->user->name }}</a> mengirim
                                                    notifikasi
                                                    pada <span class="text-bold">{{ $item->jadwal_audit->jadwal }}</span>
                                                </h3>
                                                <div class="timeline-body">
                                                    <span class="text-bold">
                                                        {{ $item->prodi
                                                            ? 'Program Studi ' . $item->prodi->nama
                                                            : ($item->fakultas
                                                                ? 'Fakultas ' . $item->fakultas->nama
                                                                : ($item->unit
                                                                    ? 'Unit ' . $item->unit->nama
                                                                    : '')) }}

                                                    </span>
                                                    <p style="margin: 0; padding: 0;">
                                                        <span
                                                            class="badge badge-success">{{ $item->form->instrumen->kode }}</span>
                                                    </p>
                                                    Jawaban sudah diubah
                                                </div>
                                                <div class="timeline-footer" style="margin-top:-10px;">
                                                    <a href="{{ route('auditor.dokumen.create', ['jadwalAudit' => $item->jadwal_audit_id, 'unit' => $unitId, 'type' => $type]) }}"
                                                        class="btn btn-success btn-sm {{ $item->status == 'diterima' ? '' : 'disabled' }}">
                                                        {{ ucfirst($item->status == 'diterima' ? 'Lihat' : $item->status) }}
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                @endforeach
                                <div><i class="fas fa-clock bg-gray"></i></div>
                            </div>
                        @else
                            <p>Belum ada notifikasi sebagai Auditor</p>
                        @endif
                    </div>
                @endhasrole

                {{-- Auditan --}}
                @hasanyrole('pj_universitas|pj_fakultas|pj_prodi|gkm')
                    <div class="tab-pane fade {{ $hasAuditeeRole && !$hasAuditorRole ? 'show active' : '' }}" id="auditee"
                        role="tabpanel" aria-labelledby="auditee-tab">
                        @if ($notifikasiAuditee->isNotEmpty())
                            <div class="timeline">
                                @foreach ($notifikasiAuditee as $date => $items)
                                    <div class="time-label">
                                        <span class="bg-red">{{ $date }}</span>
                                    </div>
                                    @foreach ($items as $item)
                                        @php
                                            $unitId = $item->prodi_id ?? ($item->fakultas_id ?? $item->unit_id);
                                            $type = $item->prodi_id
                                                ? 'prodi'
                                                : ($item->fakultas_id
                                                    ? 'fakultas'
                                                    : 'universitas');
                                            // $auditee = $auditees
                                            //     ->where('jadwal_audit_id', $item->jadwal_audit_id)
                                            //     ->first();
                                            // $auditeeId = $auditee ? $auditee->id : null;
                                        @endphp
                                        <div>
                                            <i class="fas fa-envelope bg-blue"></i>
                                            <div class="timeline-item">
                                                <span class="time" style="font-size: 16px;"><i class="fas fa-clock"></i>
                                                    {{ \Carbon\Carbon::parse($item->created_at)->format('H:i') }}
                                                </span>
                                                <h3 class="timeline-header">
                                                    <a href="#">{{ $item->auditor->user->name }}</a> mengirim
                                                    notifikasi
                                                    pada <span class="text-bold">{{ $item->jadwal_audit->jadwal }}</span>
                                                </h3>
                                                <div class="timeline-body">
                                                    <p style="margin: 0; padding: 0;">
                                                        <span
                                                            class="badge badge-success">{{ $item->form->instrumen->kode }}</span>
                                                    </p>

                                                    <span>{{ $item->pesan }}</span>
                                                </div>

                                                <div class="timeline-footer" style="margin-top:-10px;">
                                                    <a href="{{ $item->status == 'terkirim' ? route('auditee.dokumen.create', ['jadwalAudit' => $item->jadwal_audit_id, 'unit' => $unitId, 'type' => $type]) : '#' }}"
                                                        class="btn btn-success btn-sm {{ $item->status == 'terkirim' ? '' : 'disabled' }}">
                                                        {{ ucfirst($item->status == 'terkirim' ? 'Lihat' : $item->status) }}
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                @endforeach
                                <div><i class="fas fa-clock bg-gray"></i></div>
                            </div>
                        @else
                            <p>Belum ada notifikasi sebagai Auditan</p>
                        @endif
                    </div>
                @endhasanyrole
            </div>

        </div>
    </div>
@endsection

@section('script')
    <script></script>
@endsection
