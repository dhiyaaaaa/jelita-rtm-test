<?php

namespace App\Livewire\Rtm;

use App\Http\Controllers\dekan\RtmLampiranController;
use App\Models\RtmJadwal;
use App\Models\RtmLampiran;
use Livewire\Component;
use Livewire\WithFileUploads;

class Jadwal extends Component
{
    use WithFileUploads;

    public $title = 'Agenda RTM';
    public $rtmJadwal;
    public $selectedRtmId;

    //Lampiran
    public $showLampiranModal = false;
    public $undangan;
    public $presensi;
    public $dokumentasi;

    protected $listeners = ['refreshComponent' => '$refresh'];

    public function mount()
    {
        $this->loadRtmJadwal();
    }

    public function loadRtmJadwal()
    {
        $this->rtmJadwal = RtmJadwal::with(['jadwal_audit', 'rtm_lampiran', 'rtm_rtl'])
            ->whereNull('fakultas_id')
            ->whereNull('unit_id')
            ->get();
    }

    public function openLampiranModal($rtmId)
    {
        $this->selectedRtmId = $rtmId;
        $this->showLampiranModal = true;
    }

    public function saveLampiran()
    {
        $this->validate([
            'undangan' => 'required|file|mimes:pdf|max:2048',
            'presensi' => 'required|file|mimes:pdf|max:2048',
            'dokumentasi' => 'required|file|mimes:pdf|max:5120',
        ]);

        try {
            $timestamp = now()->timestamp;

            $undanganPath = $this->undangan->storeAs(
                'lampiran_rtm', "undangan_rtm_{$this->selectedRtmId}_{$timestamp}.pdf"
            );
            $presensiPath = $this->presensi->storeAs(
                'lampiran_rtm', "presensi_rtm_{$this->selectedRtmId}_{$timestamp}.pdf"
            );
            $dokumentasiPath = $this->dokumentasi->storeAs(
                'lampiran_rtm', "dokumentasi_rtm_{$this->selectedRtmId}_{$timestamp}.pdf"
            );

            RtmLampiran::updateOrCreate(
                ['rtm_jadwal_id' => $this->selectedRtmId],
                [
                    'undangan' => $undanganPath,
                    'presensi' => $presensiPath,
                    'dokumentasi' => $dokumentasiPath,

                ]
            );

            $this->emit('showAlert', 'succes', 'Lampiran berhasil disimpan!');
            $this->reset(['undangan', 'presensi', 'dokumentasi', 'showLampiranModal']);

        } catch (\Exception $e) {
            $this->emit('showAlert', 'error', 'Terjadi kesalahan saat menyimpan lampiran');
        }
    }

    public function deleteRtm($id)
    {
        $rtm = RtmJadwal::findOrFail($id);
        $rtm->delete();

        $this->emit('showAlert', 'succes', 'Jadwal RTM berhasil dihapus!');
        $this->loadRtmJadwal();
    }

    public function render()
    {
        $data = [
            'title' => $this->title,
            'rtmJadwal' => $this->rtmJadwal
        ];
        
        return view('livewire.rtm.jadwal', $data);
    }
}
