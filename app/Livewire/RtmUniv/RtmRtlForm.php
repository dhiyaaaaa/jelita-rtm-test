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
use Illuminate\Support\Facades\Session; // Import Session Facade
use Illuminate\Validation\ValidationException;

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

    // Kunci unik untuk sesi berdasarkan ID RTM/RTL dan nomor halaman
    protected function getSessionKey()
    {
        return 'rtm_rtl_form_' . $this->rtmRtlUnivId . '_page_' . $this->getPage();
    }

    public function boot()
    {
        if (!($this->temuan instanceof Collection)) {
            $this->temuan = new Collection();
        }
    }

    public function mount(RtmRtlUniv $rtmRtlUniv)
    {
        $this->rtmRtlUnivId = $rtmRtlUniv->id;
        $this->rtmJadwal = RtmJadwal::findOrFail($rtmRtlUniv->rtm_jadwal_id);

        $this->loadStatus();
        $this->isCompleted = $this->status && $this->status->status === 'completed';

        $this->temuan = new Collection(); // Inisialisasi awal
        $this->loadJawabanRtm(); // Load jawaban dari DB
        $this->loadSessionData(); // Timpa dengan data sesi jika ada
    }

    // Metode baru untuk memuat data dari sesi
    private function loadSessionData()
    {
        $sessionData = Session::get($this->getSessionKey(), []);
        foreach ($sessionData as $formId => $data) {
            $this->rekomendasi[$formId] = $data['rekomendasi'];
            $this->koreksi[$formId] = $data['koreksi'];
        }
    }

    // Metode baru untuk menyimpan semua data saat ini ke sesi
    public function saveAllToSession()
    {
        $dataToSave = [];
        foreach ($this->rekomendasi as $formId => $rekomendasiValue) {
            $dataToSave[$formId] = [
                'rekomendasi' => $rekomendasiValue,
                'koreksi' => $this->koreksi[$formId] ?? null,
            ];
        }
        Session::put($this->getSessionKey(), $dataToSave);
        // Anda bisa menambahkan Log::info di sini jika ingin melihat data yang disimpan
        // Log::info('Data saved to session for key: ' . $this->getSessionKey(), $dataToSave);
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
        // Ini penting agar nilai di textarea tetap ada setelah refresh (dari DB atau session)
        foreach ($this->jawabanRtm as $jawaban) {
            if (!isset($this->rekomendasi[$jawaban->form_id])) { // Hanya isi jika belum ada dari sesi
                $this->rekomendasi[$jawaban->form_id] = $jawaban->rekomendasi;
            }
            if (!isset($this->koreksi[$jawaban->form_id])) { // Hanya isi jika belum ada dari sesi
                $this->koreksi[$jawaban->form_id] = $jawaban->koreksi;
            }
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

            // Simpan perubahan ke sesi setelah sukses disimpan ke DB
            $this->saveAllToSession();

            $this->dispatch('showAlert', type: 'success', title: 'Berhasil!', message: 'Jawaban berhasil disimpan.');
        } catch (\Exception $e) {
            $this->dispatch('showAlert', type: 'error', title: 'Gagal!', message: 'Terjadi kesalahan saat menyimpan jawaban: ' . $e->getMessage());
            Log::error("Error saving RTM RTL Form item: " . $e->getMessage());
        }
    }

    public function submitForm()
    {
        $this->isSubmitting = true;

        $rules = [];
        $messages = [];

        $this->loadTemuan(); // Pastikan temuan terbaru dimuat

        if ($this->temuan instanceof Collection) {
            foreach ($this->temuan as $item) {
                $formId = $item->form->id;
                $rules["rekomendasi.$formId"] = 'required';
                $messages["rekomendasi.$formId.required"] = "Rekomendasi untuk instrumen {$item->form->instrumen->kode} harus diisi.";
                $rules["koreksi.$formId"] = 'required';
                $messages["koreksi.$formId.required"] = "Permintaan Tindakan Koreksi untuk instrumen {$item->form->instrumen->kode} harus diisi.";
            }
        }

        try {
            $this->validate($rules, $messages); // Validasi sebelum menyimpan

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

            // Hapus data sesi setelah berhasil disubmit
            Session::forget('rtm_rtl_form_' . $this->rtmRtlUnivId . '_page_*'); // Hapus semua sesi untuk RTM/RTL ini
            // Atau hanya halaman saat ini: Session::forget($this->getSessionKey());


            $this->dispatch('showAlert', type: 'success', title: 'Berhasil!', message: 'Semua jawaban berhasil disimpan dan form telah disubmit.');
            return redirect()->route('admin.rtm-rtl-univ.show-livewire', ['rtmJadwal' => $this->rtmJadwal->id]);
        } catch (ValidationException $e) {
            $errors = $e->validator->errors()->all();
            $errorMessage = implode('<br>', $errors); // Gabungkan pesan error
            $this->dispatch('showAlert', type: 'error', title: 'Validasi Gagal!', message: $errorMessage);
        }
        catch (\Exception $e) {
            $this->dispatch('showAlert', type: 'error', title: 'Gagal!', message: 'Terjadi kesalahan saat submit form: ' . $e->getMessage());
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
        // Hapus data sesi saat masuk mode edit agar form kosong dari session
        Session::forget('rtm_rtl_form_' . $this->rtmRtlUnivId . '_page_*');
        $this->loadJawabanRtm(); // Load ulang dari DB setelah enable edit mode
        $this->dispatch('showAlert', type: 'info', title: 'Mode Edit', message: 'Mode pengubahan diaktifkan.');
    }

    
    public function confirmSubmit()
    {
        $this->dispatch('confirmSubmit');
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

    public function updated($propertyName)
    {
        // Auto-save with 1-second delay for textarea changes
        if (str_starts_with($propertyName, 'rekomendasi.') || str_starts_with($propertyName, 'koreksi.')) {
            $this->saveAllToSession();
            // Anda bisa menambahkan dispatch event ke front-end jika ingin feedback "Draft Saved"
            // $this->dispatch('showAlert', type: 'info', title: 'Draft Tersimpan', message: 'Perubahan otomatis disimpan.');
        }
    }
}