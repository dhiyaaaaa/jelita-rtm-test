<?php

namespace App\Livewire\RtmUniv;

use App\Models\JawabanAuditor;
use App\Models\RtmJadwal;
use App\Models\RtmRtlForm;
use App\Models\RtmRtlUniv;
use App\Models\RtmTindakLanjut;
use App\Models\StatusRtmRtlUniv;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Livewire\Component;
use Livewire\WithPagination;

class RtmRtlUnivForm extends Component
{
    use WithPagination;

    public $rtmRtlUnivId;
    // Remove $rtmRtlUnivModel and $temuanQuery from public properties
    public $temuan;
    public $paginatedTemuan;

    public $selectedKriteria = [];
    public $rtmJadwal;
    public $status;
    public $jawabanRtm = [];

    public $rekomendasi = [];
    public $koreksi = [];

    public $warningMessage;
    public $errorMessage;
    public $missingFields = [];

    protected $queryString = ['page'];

    public function mount(RtmRtlUniv $rtmRtlUniv)
    {
        $this->rtmRtlUnivId = $rtmRtlUniv->id;
        $this->loadData();
    }

    // Helper method to get the model when needed
    private function getRtmRtlUnivModel()
    {
        return RtmRtlUniv::findOrFail($this->rtmRtlUnivId);
    }

    public function loadData()
    {
        $rtmRtlUniv = $this->getRtmRtlUnivModel();
        $this->rtmJadwal = RtmJadwal::findOrFail($rtmRtlUniv->rtm_jadwal_id);
        $this->status = StatusRtmRtlUniv::where('rtm_rtl_univ_id', $rtmRtlUniv->id)->first();

        $order_by_kode = DB::raw("
            CASE
                WHEN REGEXP_REPLACE((SELECT kode FROM instrumen WHERE instrumen.id = form.instrumen_id), '[^0-9]', '', 'g') ~ '^[0-9]+$'
                THEN CAST(REGEXP_REPLACE((SELECT kode FROM instrumen WHERE instrumen.id = form.instrumen_id), '[^0-9]', '', 'g') AS INTEGER)
                ELSE NULL
            END
        ");

        $query = null;

        if ($rtmRtlUniv->fakultas_id) {
            $query = RtmTindakLanjut::join('rtm_rtl', 'rtm_tindak_lanjut.rtm_rtl_id', "=", 'rtm_rtl.id')
                ->join('form', 'rtm_tindak_lanjut.form_id', '=', 'form.id')
                ->join('instrumen', 'form.instrumen_id', '=', 'instrumen.id')
                ->where(function ($query) use ($rtmRtlUniv) {
                    if ($rtmRtlUniv->fakultas_id) {
                        $query->where('rtm_rtl.fakultas_id', $rtmRtlUniv->fakultas_id);
                    } else {
                        $query->where('rtm_rtl.unit_id', $rtmRtlUniv->unit_id);
                    }
                })
                ->whereHas('jabatan', function ($query) {
                    $query->where('type', 'universitas');
                })
                ->when(!empty($this->selectedKriteria), function ($query) {
                    return $query->whereIn('kriteria_id', $this->selectedKriteria);
                })
                ->with([
                    'form.instrumen',
                    'form.jawaban_auditor',
                    'form.ptk_form_deskripsi',
                    'form.laporan_form',
                    'kriteria',
                    'jabatan',
                ])
                ->orderBy($order_by_kode);

            if ($query->count() === 0) {
                $this->warningMessage = 'Tidak ada rencana tindak lanjut yang sudah dibuat pada kriteria yang Anda pilih';
                $this->temuan = collect();
                return;
            }
        } else {
            $query = JawabanAuditor::join('form', 'jawaban_auditor.form_id', '=', 'form.id')
                ->join('instrumen', 'form.instrumen_id', '=', 'instrumen.id')
                ->where('jadwal_audit_id', $rtmRtlUniv->jadwal_audit_id)
                ->where(function ($query) use ($rtmRtlUniv) {
                    if ($rtmRtlUniv->fakultas_id) {
                        $query->where('fakultas_id', $rtmRtlUniv->fakultas_id);
                    } else {
                        $query->where('unit_id', $rtmRtlUniv->unit_id);
                    }
                })
                ->when(!empty($this->selectedKriteria), function ($query) {
                    return $query->whereIn('jawaban_auditor.kriteria_id', $this->selectedKriteria);
                })
                ->with([
                    'form.instrumen',
                    'form.jawaban_auditor',
                    'form.ptk_form_deskripsi',
                    'form.laporan_form',
                    'kriteria',
                ])
                ->orderBy($order_by_kode);

            if ($query->count() === 0) {
                $this->warningMessage = 'Tidak ada data temuan hasil audit yang tersedia';
                $this->temuan = collect();
                return;
            }
        }

        // For the full collection (used in other parts of your code)
        $this->temuan = $query->get()->unique('form_id')->values();

        $this->jawabanRtm = RtmRtlForm::where('rtm_rtl_univ_id', $this->rtmRtlUnivId)->get()->keyBy('form_id')->toArray();

        foreach ($this->temuan as $item) {
            $formId = $item->form_id;
            if (isset($this->jawabanRtm[$formId])) {
                $this->rekomendasi[$formId] = $this->jawabanRtm[$formId]['rekomendasi'];
                $this->koreksi[$formId] = $this->jawabanRtm[$formId]['koreksi'];
            } else {
                $this->rekomendasi[$formId] = null;
                $this->koreksi[$formId] = null;
            }
        }
    }

    // Helper method to rebuild the query when needed
    private function buildTemuanQuery()
    {
        $rtmRtlUniv = $this->getRtmRtlUnivModel();
        
        $order_by_kode = DB::raw("
            CASE
                WHEN REGEXP_REPLACE((SELECT kode FROM instrumen WHERE instrumen.id = form.instrumen_id), '[^0-9]', '', 'g') ~ '^[0-9]+$'
                THEN CAST(REGEXP_REPLACE((SELECT kode FROM instrumen WHERE instrumen.id = form.instrumen_id), '[^0-9]', '', 'g') AS INTEGER)
                ELSE NULL
            END
        ");

        if ($rtmRtlUniv->fakultas_id) {
            return RtmTindakLanjut::join('rtm_rtl', 'rtm_tindak_lanjut.rtm_rtl_id', "=", 'rtm_rtl.id')
                ->join('form', 'rtm_tindak_lanjut.form_id', '=', 'form.id')
                ->join('instrumen', 'form.instrumen_id', '=', 'instrumen.id')
                ->where(function ($query) use ($rtmRtlUniv) {
                    if ($rtmRtlUniv->fakultas_id) {
                        $query->where('rtm_rtl.fakultas_id', $rtmRtlUniv->fakultas_id);
                    } else {
                        $query->where('rtm_rtl.unit_id', $rtmRtlUniv->unit_id);
                    }
                })
                ->whereHas('jabatan', function ($query) {
                    $query->where('type', 'universitas');
                })
                ->when(!empty($this->selectedKriteria), function ($query) {
                    return $query->whereIn('kriteria_id', $this->selectedKriteria);
                })
                ->with([
                    'form.instrumen',
                    'form.jawaban_auditor',
                    'form.ptk_form_deskripsi',
                    'form.laporan_form',
                    'kriteria',
                    'jabatan',
                ])
                ->orderBy($order_by_kode);
        } else {
            return JawabanAuditor::join('form', 'jawaban_auditor.form_id', '=', 'form.id')
                ->join('instrumen', 'form.instrumen_id', '=', 'instrumen.id')
                ->where('jadwal_audit_id', $rtmRtlUniv->jadwal_audit_id)
                ->where(function ($query) use ($rtmRtlUniv) {
                    if ($rtmRtlUniv->fakultas_id) {
                        $query->where('fakultas_id', $rtmRtlUniv->fakultas_id);
                    } else {
                        $query->where('unit_id', $rtmRtlUniv->unit_id);
                    }
                })
                ->when(!empty($this->selectedKriteria), function ($query) {
                    return $query->whereIn('jawaban_auditor.kriteria_id', $this->selectedKriteria);
                })
                ->with([
                    'form.instrumen',
                    'form.jawaban_auditor',
                    'form.ptk_form_deskripsi',
                    'form.laporan_form',
                    'kriteria',
                ])
                ->orderBy($order_by_kode);
        }
    }

    public function updated($propertyName)
    {
        $this->errorMessage = null;
        $this->missingFields = [];
    }

    public function saveJawaban($final = false)
    {
        $user = Auth::id();
        $rtmRtlUniv = $this->getRtmRtlUnivModel();

        $requestData = [];
        foreach ($this->rekomendasi as $formId => $value) {
            $requestData['rekomendasi_' . $formId] = $value;
            $requestData['koreksi_' . $formId] = $this->koreksi[$formId] ?? null;
        }

        $rules = [];
        $messages = [];
        $missingFields = [];

        foreach ($this->temuan as $item) {
            $formId = $item->form_id;
            $rules['rekomendasi_' . $formId] = 'required';
            $messages['rekomendasi_' . $formId . '.required'] = 'Rekomendasi harus diisi.';
            $rules['koreksi_' . $formId] = 'required';
            $messages['koreksi_' . $formId . '.required'] = 'Permintaan Tindakan Koreksi harus diisi.';

            if (empty($this->rekomendasi[$formId]) || empty($this->koreksi[$formId])) {
                $missingFields[] = 'rekomendasi_' . $formId;
                $missingFields[] = 'koreksi_' . $formId;
            }
        }

        $validator = Validator::make($requestData, $rules, $messages);

        if ($validator->fails()) {
            $this->errorMessage = 'Harap isi semua jawaban terlebih dahulu.';
            $this->missingFields = $missingFields;

            $firstMissingFormId = null;
            if (!empty($missingFields)) {
                preg_match('/^(rekomendasi|koreksi)_(\d+)$/', $missingFields[0], $matches);
                if (isset($matches[2])) {
                    $firstMissingFormId = $matches[2];
                }
            }
            if ($firstMissingFormId) {
                $perPage = 10;
                // Fixed: Use $this->temuan instead of $this->temuanCollection
                $index = $this->temuan->search(fn($item) => $item->form_id == $firstMissingFormId);
                if ($index !== false) {
                    $pageToGo = floor($index / $perPage) + 1;
                    $this->gotoPage($pageToGo);
                }
            }
            return;
        }

        DB::transaction(function () use ($user, $rtmRtlUniv, $final) {
            foreach ($this->rekomendasi as $formId => $rekomendasiValue) {
                RtmRtlForm::updateOrCreate(
                    [
                        'rtm_rtl_univ_id' => $rtmRtlUniv->id,
                        'form_id' => $formId,
                    ],
                    [
                        'user_id' => $user,
                        'rekomendasi' => $rekomendasiValue,
                        'koreksi' => $this->koreksi[$formId] ?? null,
                    ]
                );
            }

            if ($final) {
                $status = StatusRtmRtlUniv::where('rtm_rtl_univ_id', $rtmRtlUniv->id)->first();
                if ($status && $status->status == 'in_progress') {
                    $status->status = 'completed';
                    $status->save();
                }
            }
        });
        
        session()->flash('success', 'Formulir berhasil disimpan!');
        return redirect()->route('admin.rtm-rtl.show', ['rtmJadwal' => $rtmRtlUniv->rtm_jadwal_id]);
    }

    public function filterByKriteria()
    {
        $this->resetPage();
        $this->loadData();
    }

    public function render()
    {
        // Rebuild the query for pagination
        $temuanQuery = $this->buildTemuanQuery();
        
        if ($temuanQuery) {
            $this->paginatedTemuan = $temuanQuery->paginate(10);
        } else {
            $this->paginatedTemuan = collect()->paginate(10);
        }

        return view('livewire.rtm-univ.rtm-rtl-univ-form', [
            'paginatedTemuan' => $this->paginatedTemuan,
        ])->layout('components.layout.main_layout', ['title' => 'Form Rencana Tindak Lanjut RTM Univ']);
    }
}