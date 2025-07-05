<?php

namespace App\Livewire\RtmUniv;

use App\Models\JadwalAudit;
use App\Models\RtmJadwal;
use Livewire\Component;

class JadwalRtmShow extends Component
{
    public $item;
    public $isEditMode = false;

    public $agenda;
    public $tanggal;
    public $tempat;
    public $jam_mulai;
    public $jam_selesai;
    public $pimpinan;
    public $jadwal_audit_id;
    public $peserta;

    public function mount($id, $edit = false)
    {
        $this->item = RtmJadwal::with('jadwal_audit')->findOrFail($id);

        if ($this->isEditMode) {
            $this->agenda = $this->item->agenda;
            $this->tanggal = $this->item->tanggal;
            $this->tempat = $this->item->tempat;
            $this->jam_mulai = $this->item->jam_mulai;
            $this->jam_selesai = $this->item->jam_selesai;
            $this->pimpinan = $this->item->pimpinan;
            $this->jadwal_audit_id = $this->item->jadwal_audit_id;
            $this->peserta = $this->item->peserta;
        }
    }

    protected function rules()
    {
        return [
            'agenda' => 'required|string|max:255',
            'tanggal' => 'required|date',
            'tempat' => 'required|string|max:255',
            'jam_mulai' => 'required|date_format:H:i',
            'jam_selesai' => 'required|date_format:H:i|after:jam_mulai',
            'pimpinan' => 'required|string|max:255',
            'jadwal_audit_id' => 'required|exists:jadwal_audit,id',
            'peserta' => 'required|integer|min:1',
        ];
    }

    protected function messages()
    {
        return [
            'agenda.required' => 'Agenda wajib diisi.',
            'tanggal.required' => 'Tanggal wajib diisi.',
            'tanggal.date' => 'Format tanggal tidak valid.',
            'tempat.required' => 'Tempat wajib diisi.',
            'jam_mulai.required' => 'Jam mulai wajib diisi.',
            'jam_mulai.date_format' => 'Format jam mulai tidak valid (HH:MM).',
            'jam_selesai.required' => 'Jam selesai wajib diisi.',
            'jam_selesai.date_format' => 'Format jam selesai tidak valid (HH:MM).',
            'jam_selesai.after' => 'Jam selesai harus setelah jam mulai.',
            'pimpinan.required' => 'Pimpinan rapat wajib diisi.',
            'jadwal_audit_id.required' => 'Periode audit wajib dipilih.',
            'jadwal_audit_id.exists' => 'Periode audit tidak valid.',
            'peserta.required' => 'Jumlah peserta wajib diisi.',
            'peserta.integer' => 'Jumlah peserta harus berupa angka.',
            'peserta.min' => 'Jumlah peserta minimal 1.',
        ];
    }

    public function toggleEditMode()
    {
        $this->isEditMode = !$this->isEditMode;
        if ($this->isEditMode) {
            $this->agenda = $this->item->agenda;
            $this->tanggal = $this->item->tanggal;
            $this->tempat = $this->item->tempat;
            $this->jam_mulai = $this->item->jam_mulai;
            $this->jam_selesai = $this->item->jam_selesai;
            $this->pimpinan = $this->item->pimpinan;
            $this->jadwal_audit_id = $this->item->jadwal_audit_id;
            $this->peserta = $this->item->peserta;
        }
    }

    public function updateJadwalRtm()
    {
        $this->validate();

        try {
            $this->item->update([
                'agenda' => $this->agenda,
                'tanggal' => $this->tanggal,
                'tempat' => $this->tempat,
                'jam_mulai' => $this->jam_mulai,
                'jam_selesai' => $this->jam_selesai,
                'pimpinan' => $this->pimpinan,
                'jadwal_audit_id' => $this->jadwal_audit_id,
                'peserta' => $this->peserta,
            ]);
            
            session()->flash('success', 'Jadwal RTM behasil diperbarui!');
            $this->isEditMode = false;
        } catch (\Exception $e) {
            session()->flash('error', 'Terjadi kesalahan saat memperbarui data: ' . $e->getMessage());

        }
    }
    
    public function render()
    {
        $jadwalAuditOptions = JadwalAudit::all();
        return view('livewire.rtm-univ.jadwal-rtm-show', [
            'jadwalAuditOptions' => $jadwalAuditOptions,
            'title' => 'Detail Rapat Tinjauan Manajemen (Livewire Ver)',
        ])->layout('components.layout.main_layout', ['title' => 'Detail Rapat Tinjauan Manajemen (Livewire Version)']);
    }
}
