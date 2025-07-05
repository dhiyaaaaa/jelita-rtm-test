<?php

namespace App\Livewire\RtmUniv;

use App\Models\JawabanAuditor;
use App\Models\RtmJadwal;
use App\Models\RtmRtlForm as RtmRtlFormModel;
use App\Models\RtmRtlUniv;
use App\Models\RtmTindakLanjut;
use App\Models\StatusRtmRtlUniv;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Collection;

class RtmRtlForm extends Component
{
    use WithPagination;

    public $rtmRtlUnivId;
    public $selectedKriteria = [];
    public $temuan; // Ini akan menjadi Collection atau turunan Collection
    public $jawabanRtm = [];
    public $status;
    public $rtmJadwal;

    public $rekomendasi = [];
    public $koreksi = [];

    public $isCompleted = false;
    public $isSubmitting = false;

    // Inisialisasi properti untuk memastikan tidak pernah null
    public function boot()
    {
        // Pastikan $this->temuan selalu merupakan Collection sejak awal
        if (!($this->temuan instanceof Collection)) {
            $this->temuan = new Collection();
        }
    }

    public function mount(RtmRtlUniv $rtmRtlUniv)
    {
        $this->rtmRtlUnivId = $rtmRtlUniv->id;
        $this->rtmJadwal = RtmJadwal::findOrFail($rtmRtlUniv->rtm_jadwal_id);

        // Hanya load status dan isCompleted di mount
        $this->loadStatus();
        $this->isCompleted = $this->status && $this->status->status === 'completed';

        // Inisialisasi temuan di mount, tapi akan di-load ulang di render
        $this->temuan = new Collection();
    }

    private function loadTemuan()
    {
        $rtmRtlUniv = RtmRtlUniv::findOrFail($this->rtmRtlUnivId);

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
                ->where(function ($q) use ($rtmRtlUniv) {
                    if ($rtmRtlUniv->fakultas_id) {
                        $q->where('rtm_rtl.fakultas_id', $rtmRtlUniv->fakultas_id);
                    } else {
                        $q->where('rtm_rtl.unit_id', $rtmRtlUniv->unit_id);
                    }
                })
                ->whereHas('jabatan', function ($q) {
                    $q->where('type', 'universitas');
                })
                ->when(!empty($this->selectedKriteria), function ($q) {
                    return $q->whereIn('kriteria_id', $this->selectedKriteria);
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
            $query = JawabanAuditor::join('form', 'jawaban_auditor.form_id', '=', 'form.id')
                ->join('instrumen', 'form.instrumen_id', '=', 'instrumen.id')
                ->where('jadwal_audit_id', $rtmRtlUniv->jadwal_audit_id)
                ->where(function ($q) use ($rtmRtlUniv) {
                    if ($rtmRtlUniv->fakultas_id) {
                        $q->where('fakultas_id', $rtmRtlUniv->fakultas_id);
                    } else {
                        $q->where('unit_id', $rtmRtlUniv->unit_id);
                    }
                })
                ->when(!empty($this->selectedKriteria), function ($q) {
                    return $q->whereIn('jawaban_auditor.kriteria_id', $this->selectedKriteria);
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

        if ($query) {
            $this->temuan = $query->get()->unique('form_id')->values();
        } else {
            $this->temuan = new Collection();
        }

        // Isi rekomendasi dan koreksi dari data yang sudah ada setiap kali temuan dimuat
        // Ini penting agar nilai di textarea tetap ada setelah refresh
        foreach ($this->jawabanRtm as $jawaban) {
            $this->rekomendasi[$jawaban->form_id] = $jawaban->rekomendasi;
            $this->koreksi[$jawaban->form_id] = $jawaban->koreksi;
        }

        // Double check untuk memastikan $this->temuan adalah Collection
        if (!($this->temuan instanceof Collection)) {
            $this->temuan = new Collection($this->temuan);
        }
    }

    private function loadJawabanRtm()
    {
        $this->jawabanRtm = RtmRtlFormModel::where('rtm_rtl_univ_id', $this->rtmRtlUnivId)->get();
    }

    private function loadStatus()
    {
        $this->status = StatusRtmRtlUniv::where('rtm_rtl_univ_id', $this->rtmRtlUnivId)->first();
    }

    public function saveItem($formId)
    {
        $this->validate([
            "rekomendasi.$formId" => 'required',
            "koreksi.$formId" => 'required',
        ], [
            "rekomendasi.$formId.required" => 'Rekomendasi harus diisi.',
            "koreksi.$formId.required" => 'Permintaan Tindakan Koreksi harus diisi.',
        ]);

        try {
            $user = Auth::user()->id;

            RtmRtlFormModel::updateOrCreate(
                [
                    'rtm_rtl_univ_id' => $this->rtmRtlUnivId,
                    'form_id' => $formId,
                ],
                [
                    'user_id' => $user,
                    'rekomendasi' => $this->rekomendasi[$formId] ?? null,
                    'koreksi' => $this->koreksi[$formId] ?? null,
                ]
            );

            // Setelah menyimpan, load ulang jawaban untuk memastikan data terbaru tersedia
            $this->loadJawabanRtm();
            
            session()->flash('message', 'Jawaban berhasil disimpan.');
        } catch (\Exception $e) {
            session()->flash('error', 'Terjadi kesalahan saat menyimpan jawaban: ' . $e->getMessage());
            Log::error("Error saving RTM RTL Form item: " . $e->getMessage());
        }
    }

    public function submitForm()
    {
        $this->isSubmitting = true;

        $rules = [];
        $messages = [];

        // Panggil loadTemuan() sebelum iterasi untuk memastikan data terbaru
        $this->loadTemuan();

        if ($this->temuan instanceof Collection) {
            foreach ($this->temuan as $item) {
                $formId = $item->form->id;
                $rules["rekomendasi.$formId"] = 'required';
                $messages["rekomendasi.$formId.required"] = "Rekomendasi untuk instrumen {$item->form->instrumen->kode} harus diisi.";
                $rules["koreksi.$formId"] = 'required';
                $messages["koreksi.$formId.required"] = "Permintaan Tindakan Koreksi untuk instrumen {$item->form->instrumen->kode} harus diisi.";
            }
        }

        $this->validate($rules, $messages);

        try {
            $user = Auth::user()->id;

            if ($this->temuan instanceof Collection) {
                foreach ($this->temuan as $item) {
                    $formId = $item->form->id;
                    RtmRtlFormModel::updateOrCreate(
                        [
                            'rtm_rtl_univ_id' => $this->rtmRtlUnivId,
                            'form_id' => $formId,
                        ],
                        [
                            'user_id' => $user,
                            'rekomendasi' => $this->rekomendasi[$formId] ?? null,
                            'koreksi' => $this->koreksi[$formId] ?? null,
                        ]
                    );
                }
            }

            if ($this->status) {
                $this->status->status = 'completed';
                $this->status->save();
            } else {
                StatusRtmRtlUniv::create([
                    'rtm_rtl_univ_id' => $this->rtmRtlUnivId,
                    'status' => 'completed',
                ]);
            }
            $this->isCompleted = true;

            session()->flash('success', 'Semua jawaban berhasil disimpan dan form telah disubmit.');
            return redirect()->route('admin.rtm-rtl.show', ['rtmJadwal' => $this->rtmJadwal->id]);
        } catch (\Exception $e) {
            session()->flash('error', 'Terjadi kesalahan saat submit form: ' . $e->getMessage());
            Log::error("Error submitting RTM RTL Form: " . $e->getMessage());
        } finally {
            $this->isSubmitting = false;
        }
    }

    public function enableEditMode()
    {
        $this->isCompleted = false;
        if ($this->status) {
            $this->status->status = 'in_progress';
            $this->status->save();
        }
        session()->flash('message', 'Mode pengubahan diaktifkan.');
    }

    public function render()
    {
        // Panggil loadTemuan() dan loadJawabanRtm() di sini
        $this->loadJawabanRtm(); // Load jawaban terbaru setiap kali render
        $this->loadTemuan();     // Load temuan terbaru setiap kali render

        $perPage = 10;

        if (!($this->temuan instanceof Collection)) {
            $this->temuan = new Collection();
        }

        $paginatedTemuan = new LengthAwarePaginator(
            $this->temuan->forPage($this->getPage(), $perPage),
            $this->temuan->count(),
            $perPage,
            $this->getPage(),
            ['path' => route('admin.rtm-rtl.form-livewire', ['rtmRtlUniv' => $this->rtmRtlUnivId])]
        );

        return view('livewire.rtm-univ.rtm-rtl-form', [
            'paginatedTemuan' => $paginatedTemuan,
            'title' => 'Tindak Lanjut Hasil Audit',
        ])->layout('components.layout.main_layout', ['title' => 'Rencana Tindak Lanjut RTM Univ']);
    }
}