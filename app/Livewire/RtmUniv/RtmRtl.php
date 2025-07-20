<?php

namespace App\Livewire\RtmUniv;

use App\Models\Kriteria;
use App\Models\RtmJadwal;
use App\Models\RtmLampiran;
use App\Models\RtmRtlUniv;
use App\Models\RtmUnivApprove;
use App\Models\StatusRtmRtlUniv;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

class RtmRtl extends Component
{
    use WithPagination, WithFileUploads;
    public $rtmJadwalId;
    public $rtmJadwal;
    public $kriteriaOptions;
    public $selectedKriteria = [];
    public $user;
    public $approvalRektor;
    public $approvalKetuaLP3M;
    public $isRektor;
    public $isKetuaLP3M;
    public $rektorId;
    public $ketuaLP3MId;

    //Lampiran
    public $rtmLampiran;
    public $undangan;
    public $presensi;
    public $dokumentasi;

    public $isUploading = false;
    public $uploadSuccess = false;
    public $uploadError = false;

    public $search = '';
    protected $paginationTheme = 'bootstrap'; 
   
    //Konfirmasi
    public $showKonfirmasiModal = false;
    public $modalAuditId;
    public $modalFakultasId;
    public $modalUnitId;
    protected $queryString = [
        'selectedKriteria' => ['except' => []],
        'search' => ['except' => ''], 
    ];

    public function mount($rtmJadwalId)
    {
        $this->rtmJadwal = RtmJadwal::with(['jadwal_audit', 'rtm_rtl_univ', 'user', 'rtm_lampiran'])->find($rtmJadwalId);

        if (!$this->rtmJadwal || !$this->rtmJadwal->jadwal_audit) {
            abort(404, 'Jadwal audit tidak ditemukan');
        }

        $this->rtmLampiran = RtmLampiran::where('rtm_jadwal_id', $rtmJadwalId)->first();

        $this->kriteriaOptions = Kriteria::all();
        $this->user = Auth::user();

        $rektor = User::whereHas('jabatan', fn($q) => $q->where('slug', 'rektor'))->first();
        $ketuaLP3M = User::whereHas('jabatan', fn($q) => $q->where('slug', 'ketua-lp3m'))->first();

        $this->rektorId = optional($rektor)->id;
        $this->ketuaLP3MId = optional($ketuaLP3M)->id;

        $this->isRektor = $this->user->jabatan->contains('slug', 'rektor');
        $this->isKetuaLP3M = $this->user->jabatan->contains('slug', 'ketua-lp3m');

        $this->loadData();
        $this->loadApprovalStatus();
    }

    public function loadData()
    {
     $this->rtmJadwal = RtmJadwal::with(['jadwal_audit', 'rtm_rtl_univ', 'user', 'rtm_lampiran'])->find($this->rtmJadwalId);

        if (!$this->rtmJadwal || !$this->rtmJadwal->jadwal_audit) {
            abort(404, 'Jadwal audit tidak ditemukan');
        }

        $this->kriteriaOptions = Kriteria::all();
        $this->user = Auth::user();

        $countTemuanFakultas = DB::table('rtm_tindak_lanjut')
            ->join('rtm_rtl', 'rtm_tindak_lanjut.rtm_rtl_id', '=', 'rtm_rtl.id')
            ->join('jabatan', 'rtm_tindak_lanjut.jabatan_id', '=', 'jabatan.id')
            ->selectRaw('
                rtm_rtl.fakultas_id as fakultas_id,
                COUNT(DISTINCT rtm_tindak_lanjut.form_id) as jumlah_temuan_fakultas
            ')
            ->where('rtm_rtl.jadwal_audit_id', $this->rtmJadwal->jadwal_audit_id)
            ->whereNotNull('rtm_rtl.fakultas_id')
            ->where('jabatan.type', 'universitas')
            ->when(!empty($this->selectedKriteria), function ($query) {
                return $query->whereIn('rtm_tindak_lanjut.kriteria_id', $this->selectedKriteria);
            })
            ->groupBy('rtm_rtl.fakultas_id');

        $countTemuanUnit = DB::table('jawaban_auditor')
            ->join('form', 'jawaban_auditor.form_id', '=', 'form.id')
            ->selectRaw('
                jawaban_auditor.unit_id as unit_id,
                COUNT(DISTINCT jawaban_auditor.form_id) as jumlah_temuan_unit
            ')
            ->where('jawaban_auditor.jadwal_audit_id', $this->rtmJadwal->jadwal_audit_id)
            ->whereNotNull('jawaban_auditor.unit_id')
            ->when(!empty($this->selectedKriteria), function ($query) {
                return $query->whereIn('jawaban_auditor.kriteria_id', $this->selectedKriteria);
            })
            ->groupBy('jawaban_auditor.unit_id');

        $fakultasQuery = DB::table('fakultas')
            ->leftJoin('rtm_rtl_univ', function ($join) {
                $join->on('fakultas.id', '=', 'rtm_rtl_univ.fakultas_id')
                    ->where('rtm_rtl_univ.jadwal_audit_id', $this->rtmJadwal->jadwal_audit_id);
            })
            ->leftJoin('rtm_rtl', function ($join) {
                $join->on('fakultas.id', '=', 'rtm_rtl.fakultas_id')
                    ->where('rtm_rtl.jadwal_audit_id', $this->rtmJadwal->jadwal_audit_id);
            })
            ->leftJoin('status_rtm_rtl_univ', 'rtm_rtl_univ.id', '=', 'status_rtm_rtl_univ.rtm_rtl_univ_id')
            ->leftJoin('status_rtm_rtl', 'rtm_rtl.id', '=', 'status_rtm_rtl.rtm_rtl_id')
            ->leftJoin('rtm_rtl_univ_approve', 'rtm_rtl_univ.id', '=', 'rtm_rtl_univ_approve.rtm_rtl_univ_id')
            ->leftJoinSub($countTemuanFakultas, 'temuan_fakultas', function ($join) {
                $join->on('fakultas.id', '=', 'temuan_fakultas.fakultas_id');
            })
            ->select([
                'fakultas.id',
                'fakultas.nama',
                'rtm_rtl.id as rtm_rtl_id',
                'status_rtm_rtl.status',
                'rtm_rtl_univ.id as rtm_rtl_univ_id',
                'status_rtm_rtl_univ.status as rtm_rtl_univ_status', 
                'rtm_rtl_univ_approve.approve as approval_status',
                DB::raw("'fakultas' as jenis_unit"),
                DB::raw('COALESCE(temuan_fakultas.jumlah_temuan_fakultas, 0) as jumlah_temuan')
            ])
            ->when($this->search, function($query){
                return $query->whereRaw('LOWER(fakultas.nama) LIKE ?', ['%' . strtolower($this->search) . '%']);
            });

        $unitQuery = DB::table('unit')
            ->leftJoin('rtm_rtl_univ', function ($join) {
                $join->on('unit.id', '=', 'rtm_rtl_univ.unit_id')
                    ->where('rtm_rtl_univ.jadwal_audit_id', $this->rtmJadwal->jadwal_audit_id);
            })
            ->leftJoin('rtm_rtl', function ($join) {
                $join->on('unit.id', '=', 'rtm_rtl.unit_id')
                    ->where('rtm_rtl.jadwal_audit_id', $this->rtmJadwal->jadwal_audit_id);
            })
            ->leftJoin('status_rtm_rtl_univ', 'rtm_rtl_univ.id', '=', 'status_rtm_rtl_univ.rtm_rtl_univ_id')
            ->leftJoin('status_rtm_rtl', 'rtm_rtl.id', '=', 'status_rtm_rtl.rtm_rtl_id')
            ->leftJoin('rtm_rtl_univ_approve', 'rtm_rtl_univ.id', '=', 'rtm_rtl_univ_approve.rtm_rtl_univ_id')
            ->leftJoinSub($countTemuanUnit, 'temuan_unit', function ($join) {
                $join->on('unit.id', '=', 'temuan_unit.unit_id');
            })
            ->select([
                'unit.id',
                'unit.nama',
                'rtm_rtl.id as rtm_rtl_id',
                'status_rtm_rtl.status',
                'rtm_rtl_univ.id as rtm_rtl_univ_id',
                'status_rtm_rtl_univ.status as rtm_rtl_univ_status', 
                'rtm_rtl_univ_approve.approve as approval_status',
                DB::raw("'unit' as jenis_unit"),
                DB::raw('COALESCE(temuan_unit.jumlah_temuan_unit, 0) as jumlah_temuan')
            ])
            ->when($this->search, function($query){
                return $query->whereRaw('LOWER(unit.nama) LIKE ?', ['%' . strtolower($this->search) . '%']);
            });

        $unitsQuery = $fakultasQuery->union($unitQuery);

        $this->units = $unitsQuery->orderBy('nama', 'asc')->paginate(10);

        $rektor = User::whereHas('jabatan', fn ($q) => $q->where('slug', 'rektor'))->first();
        $ketuaLP3M = User::whereHas('jabatan', fn ($q) => $q->where('slug', 'ketua-lp3m'))->first();

        $this->approvalRektor = RtmUnivApprove::with(['user.jabatan'])
            ->where('rtm_jadwal_id', $this->rtmJadwal->id)
            ->get()
            ->first(function ($item) {
                return $item->user->jabatan->contains('slug', 'rektor');
            });

        $this->approvalKetuaLP3M = RtmUnivApprove::with(['user.jabatan'])
            ->where('rtm_jadwal_id', $this->rtmJadwal->id)
            ->get()
            ->first(function ($item) {
                return $item->user->jabatan->contains('slug', 'ketua-lp3m');
            });

        $this->isRektor = $this->user->jabatan->contains('slug', 'rektor');
        $this->isKetuaLP3M = $this->user->jabatan->contains('slug', 'ketua-lp3m');
        $this->rektorId = optional($rektor)->id;
        $this->ketuaLP3MId = optional($ketuaLP3M)->id;
    }
    

    public function updatingSearch()
    {
        $this->resetPage(); // Reset halaman saat melakukan pencarian baru
    }


    public function updatedSelectedKriteria($value)
    {
        $this->resetPage(); // Reset halaman saat mengubah filter kriteria
        $this->loadData();
    }

    public function resetFilter()
    {
        $this->selectedKriteria = [];
        $this->search = ''; // Reset pencarian juga
        $this->resetPage();
        $this->loadData();
    }

    public function loadApprovalStatus()
    {
        $this->approvalRektor = RtmUnivApprove::with(['user.jabatan'])
            ->where('rtm_jadwal_id', $this->rtmJadwal->id)
            ->get()
            ->first(function ($item) {
                return $item->user->jabatan->contains('slug', 'rektor');
            });

        $this->approvalKetuaLP3M = RtmUnivApprove::with(['user.jabatan'])
            ->where('rtm_jadwal_id', $this->rtmJadwal->id)
            ->get()
            ->first(function ($item) {
                return $item->user->jabatan->contains('slug', 'ketua-lp3m');
            });
    }

    public function confirmRtmRtlUniv($fakultasId = null, $unitId = null)
    {
        //$this->showKonfirmasiModal = true;
        $this->modalFakultasId = $fakultasId ?: null;
        $this->modalUnitId = $unitId ?: null;
        $this->modalAuditId = $this->rtmJadwal->jadwal_audit_id;
        $this->dispatch('show-konfirmasi-modal');
    }
    
    public function storeRtmRtl()
    {
        $this->validate([
            'modalAuditId' => 'required|exists:jadwal_audit,id',
            'modalFakultasId' => 'nullable|exists:fakultas,id',
            'modalUnitId' => 'nullable|exists:unit,id',
        ]);

        try {
            RtmRtlUniv::updateOrCreate([
                'rtm_jadwal_id' => $this->rtmJadwal->id,
                'fakultas_id' => $this->modalFakultasId,
                'unit_id' => $this->modalUnitId,
                'jadwal_audit_id' => $this->modalAuditId,
                'tgl' => Carbon::now()->toDateString(),
            ]);

            session()->flash('info_message', 'Tindak lanjut berhasil ditambahkan.');
        } catch (\Exception $e) {
            session()->flash('warning', 'Gagal menindaklanjuti: ' . $e->getMessage());
        }

        $this->dispatch('hide-konfirmasi-modal');
        $this->loadData();
        $this->dispatch('reload-datatable');
    }

    public function isiRtmRtlUniv($rtmRtlUnivId)
    {
        $rtmRtlUniv = RtmRtlUniv::find($rtmRtlUnivId);
        if ($rtmRtlUniv) {
            StatusRtmRtlUniv::updateOrCreate(
                [
                    'rtm_rtl_univ_id' => $rtmRtlUniv->id,
                ],
                ['status' => 'in_progress']
            );
            return redirect()->route('admin.rtm-rtl.form', ['rtmRtlUniv' => $rtmRtlUniv->id]);
        }
    }

    public function approveRtmUniv($rtmJadwalId, $userId)
    {
        try {
            RtmUnivApprove::updateOrCreate(
                [
                    'rtm_jadwal_id' => $rtmJadwalId,
                    'user_id' => $userId,
                ],
                ['approve' => true]
            );
            session()->flash('info_message', 'Approval berhasil disimpan.');
        } catch (\Exception $e) {
            session()->flash('warning', 'Gagal melakukan approval: ' . $e->getMessage());
        }
        $this->loadApprovalStatus(); 
    }

    public function saveLampiran()
    {
        $this->validate([
            'undangan' => 'required|file|mimes:pdf|max:2048',
            'presensi' => 'required|file|mimes:pdf|max:2048',
            'dokumentasi' => 'required|file|mimes:pdf|max:5120',
        ]);

        try {
            $this->isUploading = true;
            $rtmJadwalId = $this->rtmJadwal->id; 
            $timestamp = now()->timestamp;

            $rtmLampiran = RtmLampiran::updateOrCreate(
                ['rtm_jadwal_id' => $rtmJadwalId],
                [
                    'undangan' => $this->undangan->storeAs(
                        'lampiran_rtm', "undangan_rtm_{$rtmJadwalId}_{$timestamp}.pdf"
                    ),
                    'presensi' => $this->presensi->storeAs(
                        'lampiran_rtm', "presensi_rtm_{$rtmJadwalId}_{$timestamp}.pdf"
                    ),
                    'dokumentasi' => $this->dokumentasi->storeAs(
                        'lampiran_rtm', "dokumentasi_rtm_{$rtmJadwalId}_{$timestamp}.pdf"
                    ),
                ]
            );

            $this->reset(['undangan', 'presensi', 'dokumentasi']); 

            $this->rtmLampiran = $rtmLampiran;
            $this->isUploading = false;
            $this->uploadSuccess = true;

            $this->dispatch('show-alert', ['type' => 'success', 'message' => 'Lampiran berhasil disimpan.']);
        } catch (ValidationException $e) {
            $this->dispatch('show-alert', ['type' => 'error', 'message' => 'Terdapat kesalahan validasi.']);
            throw $e; 
        }
    }

    public function render()
    {
        $this->loadData();
        return view('livewire.rtm-univ.rtm-rtl', [
            'units' => $this->units,
            'rtmLampiran' => $this->rtmLampiran
        ])->layout('components.layout.main_layout', ['title' => 'Rencana Tindak Lanjut RTM Univ']);
    }
}