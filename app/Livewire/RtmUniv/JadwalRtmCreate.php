<?php

namespace App\Livewire\RtmUniv;

use App\Models\JadwalAudit;
use App\Models\RtmJadwal;
use Livewire\Component;

class JadwalRtmCreate extends Component
{
    public $agenda;
    public $tanggal;
    public $tempat;
    public $jam_mulai;
    public $jam_selesai;
    public $pimpinan;
    public $jadwal_audit_id;
    public $peserta;

    public $jadwalAuditOptions;

    public $title = 'Tambah Jadwal RTM';

    public function mount()
    {
        $this->jadwalAuditOptions = JadwalAudit::all();
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

    public function store()
    {
        $this->validate();

            RtmJadwal::create([
                'agenda' => $this->agenda,
                'tanggal' => $this->tanggal,
                'tempat' => $this->tempat,
                'jam_mulai' => $this->jam_mulai,
                'jam_selesai' => $this->jam_selesai,
                'pimpinan' => $this->pimpinan,
                'jadwal_audit_id' => $this->jadwal_audit_id,
                'peserta' => $this->peserta,
            ]);
            
            $this->reset([
                'agenda', 'tanggal', 'tempat', 'jam_mulai', 'jam_selesai',
                'pimpinan', 'jadwal_audit_id', 'peserta'
            ]);


            return redirect()
            ->route('admin.rtm-univ.index-livewire')
            ->with('success', 'Jadwal RTM berhasil disimpan!');
    }
    

    public function render()
    {
        return view('livewire.rtm-univ.jadwal-rtm-create')
            ->layout('components.layout.main_layout', ['title' => $this->title]);
    }
}
