<?php

namespace App\Livewire\Admin\RtmUniv;

use App\Models\RtmJadwal;
use App\Models\RtmLampiran;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

class JadwalRtmIndex extends Component
{
    use WithPagination, WithFileUploads;

    public $search = '';
    public $perPage = 10;

    public $rtmJadwal;
    public $title = 'Agenda RTM';

    public $showLampiranModal = false;
    public $rtmJadwalId;
    public $undangan;
    public $presensi;
    public $dokumentasi;

    public $isUploading = false;
    public $uploadSuccess = false;
    public $uploadError = false;

    public function mount() 
    {
        $this->loadRtmJadwal();
    }

    public function loadRtmJadwal()
    {
        $this->rtmJadwal = RtmJadwal::with(['jadwal_audit'])
            ->whereNull('fakultas_id')
            ->whereNull('unit_id')
            ->get();
    }

    public function openLampiranModal($id)
    {
        $this->rtmJadwalId = $id;
        $this->showLampiranModal = true;
        $this->resetUploadFields(); 
        $this->uploadSuccess = false;
        $this->uploadError = false;

        $this->dispatch('show-modal');
    }

    public function closeLampiranModal()
    {
        $this->showLampiranModal = false;
        $this->resetUploadFields();
        $this->resetValidation(); 
    }

    protected function resetUploadFields()
    {
        $this->undangan = null;
        $this->presensi = null;
        $this->dokumentasi = null;
    }

    public function saveLampiran()
    {
        $this->validate([
            'undangan' => 'required|file|mimes:pdf|max:2048',
            'presensi' => 'required|file|mimes:pdf|max:2048',
            'dokumentasi' => 'required|file|mimes:pdf|max:5120',
        ], [
            'undangan.required' => 'File undangan wajib diunggah.',
            'undangan.mimes' => 'File undangan harus berformat PDF.',
            'undangan.max' => 'Ukuran file undangan maksimal 2MB.',
            'presensi.required' => 'File presensi wajib diunggah.',
            'presensi.mimes' => 'File presensi harus berformat PDF.',
            'presensi.max' => 'Ukuran file presensi maksimal 2MB.',
            'dokumentasi.required' => 'File dokumentasi wajib diunggah.',
            'dokumentasi.mimes' => 'File dokumentasi harus berformat PDF.',
            'dokumentasi.max' => 'Ukuran file dokumentasi maksimal 5MB.',
        ]);

        $this->isUploading = true;
        $this->uploadSuccess = false;
        $this->uploadError = false;

        try {
            $rtmJadwalId = $this->rtmJadwalId;
            $timestamp = now()->timestamp;

            $undanganPath = $this->undangan->storeAs(
                'lampiran_rtm', "undangan_rtm_{$rtmJadwalId}_{$timestamp}.pdf"
            );
            $presensiPath = $this->presensi->storeAs(
                'lampiran_rtm', "presensi_rtm_{$rtmJadwalId}_{$timestamp}.pdf"
            );
            $dokumentasiPath = $this->dokumentasi->storeAs(
                'lampiran_rtm', "dokumentasi_rtm_{$rtmJadwalId}_{$timestamp}.pdf"
            );

            RtmLampiran::updateOrCreate(
                ['rtm_jadwal_id' => $rtmJadwalId],
                [
                    'undangan' => $undanganPath,
                    'presensi' => $presensiPath,
                    'dokumentasi' => $dokumentasiPath,
                ]
            );

            $this->dispatch('swal:modal', [
                'icon' => 'success',
                'title' => 'Lampiran berhasil disimpan!',
                'text' => 'Data lampiran telah disimpan dengan sukses.',
                'timer' => 1500
            ]);

            $this->uploadSuccess = true;
            $this->closeLampiranModal(); 
            $this->loadRtmJadwal(); 

        } catch (\Exception $e) {
            $this->dispatch('swal:modal', [
                'icon' => 'error',
                'title' => 'Terjadi Kesalahan!',
                'text' => 'Gagal menyimpan lampiran. Silakan coba lagi. ' . $e->getMessage(),
                'confirmButtonText' => 'Tutup'
            ]);
            $this->uploadError = true;
        } finally {
            $this->isUploading = false;
        }
    }

    public function deleteJadwal($id)
    {
        try {
            $rtmJadwal = RtmJadwal::findOrFail($id);
            $rtmJadwal->delete();
            $this->loadRtmJadwal(); 
            $this->dispatch('swal:modal', [
                'icon' => 'success',
                'title' => 'Jadwal RTM berhasil dihapus!',
                'showConfirmButton' => false,
                'timer' => 1500
            ]);
        } catch (\Exception $e) {
            $this->dispatch('swal:modal', [
                'icon' => 'error',
                'title' => 'Gagal menghapus!',
                'text' => 'Terjadi kesalahan saat menghapus jadwal RTM: ' . $e->getMessage(),
                'confirmButtonText' => 'Tutup'
            ]);
        }
    }


    public function render()
    {
        return view('livewire.admin.rtm-univ.jadwal-rtm-index', [
            'rtmJadwal' => $this->rtmJadwal,
            'title' => $this->title,
        ]);
    }
}
