<?php

namespace App\Http\Controllers;

use App\Models\Form;
use App\Models\JadwalAudit;
use App\Models\JawabanAuditee;
use App\Models\JawabanAuditor;
use App\Models\Kriteria;
use App\Models\Laporan;
use App\Models\LaporanForm;
use App\Models\Level;
use App\Models\Link;
use App\Models\Notifikasi;
use App\Models\Prodi;
use App\Models\Ptk;
use App\Models\PtkForm;
use App\Models\PtkFormDeskripsi;
use App\Models\PtkFormRencana;
use App\Models\StatusAuditAuditee;
use App\Models\StatusAuditAuditor;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class HasilAuditProdiController extends Controller
{
    public function index(): View
    {
        $jadwal = JadwalAudit::orderBy('created_at', 'DESC')->get();

        $data = [
            'title' => 'Hasil Audit Prodi',
            'jadwalAudit' => $jadwal,
        ];

        return view('gpm_dekan.hasil_audit_prodi.index', $data);
    }

    public function show(JadwalAudit $jadwalAudit): View
    {
        $fakultas = Auth::user()->prodi->isNotEmpty() ? Auth::user()->prodi->first()->fakultas->id : (Auth::user()->fakultas->isNotEmpty() ? Auth::user()->fakultas->first()->id : null);
        $prodi = collect();

        if ($fakultas) {
            $prodi = Prodi::where('fakultas_id', $fakultas)->with([
                'berita_acara' => function ($query) use ($jadwalAudit) {
                    $query->where('jadwal_audit_id', $jadwalAudit->id);
                },
                'ptk' => function ($query) use ($jadwalAudit) {
                    $query->where('jadwal_audit_id', $jadwalAudit->id)->with(['status_ptk_auditee', 'status_ptk_auditor']);
                },
                'laporan' => function ($query) use ($jadwalAudit) {
                    $query->where('jadwal_audit_id', $jadwalAudit->id)->with(['status_laporan']);
                },
                'status_audit_auditee' => function ($query) use ($jadwalAudit) {
                    $query->where('jadwal_audit_id', $jadwalAudit->id);
                },
                'status_audit_auditor' => function ($query) use ($jadwalAudit) {
                    $query->where('jadwal_audit_id', $jadwalAudit->id);
                },
                'jenjang'
            ])->with(['auditee' => function ($query) use ($jadwalAudit) {
                $query->whereHas('jadwal_audit', function ($subQuery) use ($jadwalAudit) {
                    $subQuery->where('jadwal_audit_id', $jadwalAudit->id);
                })->with([
                    'user',
                    'auditor.user',
                    'jadwal_audit'
                ]);
            }])->get();
        } else {
            abort(404);
        }

        $data = [
            'title' => 'Hasil Audit Prodi ' . $jadwalAudit->jadwal,
            'prodi' => $prodi,
            'jadwalAudit' => $jadwalAudit,
        ];

        return view('gpm_dekan.hasil_audit_prodi.show', $data);
    }

    // Audit Dokumen
    public function audit_dokumen(Request $request, JadwalAudit $jadwalAudit, string $unit): View
    {

        $status_audit_auditee = StatusAuditAuditee::where(['jadwal_audit_id' => $jadwalAudit->id, 'prodi_id' => $unit])->first();

        $status_audit_auditor = StatusAuditAuditor::where(['jadwal_audit_id' => $jadwalAudit->id, 'prodi_id' => $unit])->first();

        $order_by_kode = DB::raw("
            CASE
                WHEN REGEXP_REPLACE((SELECT kode FROM instrumen WHERE instrumen.id = form.instrumen_id), '[^0-9]', '', 'g') ~ '^[0-9]+$'
                THEN CAST(REGEXP_REPLACE((SELECT kode FROM instrumen WHERE instrumen.id = form.instrumen_id), '[^0-9]', '', 'g') AS INTEGER)
                ELSE NULL
            END
        ");

        $forms = collect();
        if ($unit) {
            $level = Level::where('slug', 'prodi')->first();
            $prodi = Prodi::findOrFail($unit);
            $forms = $forms->merge(
                Form::where('jadwal_id', $jadwalAudit->id)
                    ->whereHas('instrumen', function ($query) use ($level) {
                        $query->where('level_id', $level->id);
                    })
                    ->where(function ($query) use ($unit, $prodi) {
                        $query->whereHas('instrumen.jenjang', function ($query) use ($prodi) {
                            $query->where('jenjang_id', $prodi->jenjang->id);
                        });
                        $query->orWhereHas('instrumen.prodi', function ($query) use ($unit) {
                            $query->where('prodi_id', $unit);
                        });
                    })
                    ->with(['instrumen.standar', 'instrumen.kategori', 'instrumen.jenis_pertanyaan'])
                    ->orderBy(
                        DB::raw('(SELECT standar_id FROM instrumen WHERE instrumen.id = form.instrumen_id)'),
                        'asc'
                    )
                    ->orderBy(
                        DB::raw('(SELECT kategori_id FROM instrumen WHERE instrumen.id = form.instrumen_id)'),
                        'asc'
                    )
                    ->orderBy($order_by_kode)
                    ->get()
            );
        } else {
            abort(404);
        }

        $groupedForms = $forms->groupBy(function ($item) {
            return $item->instrumen->standar->nama . ' - ' . $item->instrumen->kategori->nama;
        });

        $flatForms = $groupedForms->flatMap(function ($forms, $group) {
            return $forms->map(function ($form) use ($group) {
                return ['group' => $group, 'form' => $form];
            });
        });

        $perPage = 10;
        $currentPage = $request->get('page', 1);
        $paginatedForms = new LengthAwarePaginator(
            $flatForms->forPage($currentPage, $perPage),
            $flatForms->count(),
            $perPage,
            $currentPage,
            ['path' => $request->url()]
        );

        // Jawaban dan Link Auditee
        $jawabanAuditee = JawabanAuditee::where(['jadwal_audit_id' => $jadwalAudit->id, 'prodi_id' => $unit])->get();

        $links = Link::where(['jadwal_audit_id' => $jadwalAudit->id, 'prodi_id' => $unit])->get();

        $jawabanAuditors = JawabanAuditor::where(['jadwal_audit_id' => $jadwalAudit->id, 'prodi_id' => $unit])->get();

        $notifikasi = Notifikasi::where(['jadwal_audit_id' => $jadwalAudit->id, 'prodi_id' => $unit])->get();

        $data = [
            'title' => $jadwalAudit->jadwal,
            'paginatedForms' => $paginatedForms,
            'jawabanAuditee' => $jawabanAuditee,
            'links' => $links,
            'currentPage' => $currentPage,
            'jadwal' => $jadwalAudit,
            'unitId' => $unit,
            'status_audit_auditee' => $status_audit_auditee,
            'status_audit_auditor' => $status_audit_auditor,
            'expired' => $jadwalAudit->expired,
            'jawabanAuditors' => $jawabanAuditors,
            'notifikasi' => $notifikasi,
        ];

        return view('gpm_dekan.hasil_audit_prodi.audit_dokumen', $data);
    }

    // Daftar Tilik
    public function daftar_tilik(JadwalAudit $jadwalAudit, string $unit): View
    {
        $daftarTilik = JawabanAuditor::where('jadwal_audit_id', $jadwalAudit->id)
            ->where('daftar_tilik', 1)
            ->where('prodi_id', $unit)
            ->with([
                'form.instrumen',
                'form.jawaban_auditee' => function ($query) use ($unit, $jadwalAudit) {
                    $query->where('jadwal_audit_id', $jadwalAudit->id);
                    $query->where('prodi_id', $unit);
                },
                'form.link' => function ($query) use ($unit) {
                    $query->where('prodi_id', $unit);
                },
            ])
            ->get();

        $units = Prodi::findOrFail($unit);

        $data = [
            'title' => 'Daftar Tilik',
            'daftarTilik' => $daftarTilik,
            'jadwal' => $jadwalAudit,
            'units' => $units,
            'expired' => $jadwalAudit->expired,
        ];

        return view('gpm_dekan.hasil_audit_prodi.daftar_tilik', $data);
    }

    // PTK
    public function ptk(Request $request, Ptk $ptk): View|RedirectResponse
    {
        $unit = get_type_model($ptk);

        $jadwal = JadwalAudit::findOrFail($ptk->jadwal_audit_id);

        $jawaban_auditor = JawabanAuditor::where(['jadwal_audit_id' => $jadwal->id, $unit['kolom'] => $unit['value'], 'ptk' => 1])->first();

        if (!$jawaban_auditor) {
            return back()->with('error', 'Belum ada instrumen yang masuk ke dalam PTK.');
        }

        $daftarTilik = JawabanAuditor::where('jadwal_audit_id', $ptk->jadwal_audit_id)
            ->where('ptk', 1)
            ->where($unit['kolom'], $unit['value'])
            ->with([
                'form.instrumen',
                'form.jawaban_auditee' => function ($query) use ($unit, $ptk) {
                    $query->where('jadwal_audit_id', $ptk->jadwal_audit_id);
                    $query->where($unit['kolom'], $unit['value']);
                },
                'form.link' => function ($query) use ($unit) {
                    $query->where($unit['kolom'], $unit['value']);
                }
            ])
            ->get();

        $perPage = 10;
        $currentPage = $request->get('page', 1);
        $paginatedDaftarTilik = new LengthAwarePaginator(
            $daftarTilik->forPage($currentPage, $perPage),
            $daftarTilik->count(),
            $perPage,
            $currentPage,
            ['path' => $request->url()]
        );

        $jawabanPtk = PtkForm::where('ptk_id', $ptk->id)->get();
        $jawabanPtkDeskripsi = PtkFormDeskripsi::where('ptk_id', $ptk->id)->get();
        $jawabanPtkRencana = PtkFormRencana::where('ptk_id', $ptk->id)->get();

        $data = [
            'title' => 'Temuan Negatif',
            'daftarTilik' => $paginatedDaftarTilik,
            'jadwal' => $jadwal,
            'ptkId' => $ptk->id,
            'type' => $unit['type'],
            'jawabanPtk' => $jawabanPtk,
            'jawabanPtkDeskripsi' => $jawabanPtkDeskripsi,
            'jawabanPtkRencana' => $jawabanPtkRencana,
            'expired' => $jadwal->expired,
        ];

        return view('gpm_dekan.hasil_audit_prodi.ptk', $data);
    }

    // Laporan
    public function laporan(Request $request, Laporan $laporan): View|RedirectResponse
    {
        $unit = get_type_model($laporan);

        $kriteriaMemenuhi = Kriteria::where('slug', 'memenuhi')->pluck('id')->first();
        $kriteriaMelampaui = Kriteria::where('slug', 'melampaui')->pluck('id')->first();
        $kriteria = [$kriteriaMelampaui, $kriteriaMemenuhi];

        // Cek jika kosong
        $jawaban_auditor = JawabanAuditor::where('jadwal_audit_id', $laporan->jadwal_audit_id)
            ->whereIn('kriteria_id', $kriteria)
            ->first();

        if (!$jawaban_auditor) {
            return back()->with('error', 'Belum ada instrumen yang masuk ke dalam laporan.');
        }

        $forms = JawabanAuditor::where('jadwal_audit_id', $laporan->jadwal_audit_id)
            ->whereIn('kriteria_id', $kriteria)
            ->where($unit['kolom'], $unit['value'])
            ->with([
                'form.instrumen',
                'form.jawaban_auditee' => function ($query) use ($laporan, $unit) {
                    $query->where('jadwal_audit_id', $laporan->jadwal_audit_id);
                    $query->where($unit['kolom'], $unit['value']);
                },
                'form.link' => function ($query) use ($unit) {
                    $query->where($unit['kolom'], $unit['value']);
                }
            ])
            ->get();

        $perPage = 10;
        $currentPage = $request->get('page', 1);
        $paginatedForms = new LengthAwarePaginator(
            $forms->forPage($currentPage, $perPage),
            $forms->count(),
            $perPage,
            $currentPage,
            ['path' => $request->url()]
        );

        $jawabanLaporan = LaporanForm::where('laporan_id', $laporan->id)->get();

        $data = [
            'title' => 'Temuan Positif',
            'paginatedForms' => $paginatedForms,
            'laporan' => $laporan,
            'jadwal' => $laporan->jadwal_audit_id,
            'expired' => $laporan->jadwal_audit->expired,
            'jawabanLaporan' => $jawabanLaporan,
            'type' => $unit['type'],
        ];

        return view('gpm_dekan.hasil_audit_prodi.laporan', $data);
    }
}
