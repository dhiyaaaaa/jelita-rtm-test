<?php

namespace App\Livewire;

use App\Models\RtmJadwal as ModelsRtmJadwal;
use Livewire\Component;
use App\Models\RtmLampiran;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class Rtmjadwal extends Component
{
    use WithPagination, WithFileUploads;

    // Properti untuk Pencarian dan Paginasi
    public $search = '';
    public $perPage = 10; // Sesuaikan dengan jumlah item per halaman yang Anda inginkan

    // Properti untuk Modal Lampiran
    public $showLampiranModal = false;
    public $rtmJadwalId;
    public $undangan;
    public $presensi;
    public $dokumentasi;

    // Listener untuk event SweetAlert konfirmasi hapus
    protected $listeners = ['deleteConfirmed' => 'delete'];

    /**
     * Metode ini dipanggil sebelum properti $search diperbarui.
     * Berguna untuk mereset paginasi ke halaman pertama saat melakukan pencarian baru.
     */
    public function updatingSearch()
    {
        $this->resetPage();
    }

    /**
     * Merender tampilan komponen Livewire.
     * Mengambil data jadwal RTM dengan filter pencarian dan paginasi.
     */
    public function render()
    {
        $rtmJadwal = ModelsRtmJadwal::with(['jadwal_audit'])
            ->whereNull('fakultas_id') // Memfilter jadwal RTM universitas
            ->whereNull('unit_id')    // Memfilter jadwal RTM universitas
            ->when($this->search, function($query) {
                // Menerapkan filter pencarian jika $search tidak kosong
                $query->where('agenda', 'like', '%'.$this->search.'%')
                      ->orWhereHas('jadwal_audit', function($q) {
                          $q->where('jadwal', 'like', '%'.$this->search.'%');
                      });
            })
            ->paginate($this->perPage); // Menerapkan paginasi

        return view('livewire.rtmJadwal', [
            'rtmJadwal' => $rtmJadwal,
        ])->layout('components.layout.main_layout', ['title' => 'Agenda RTM']); // Menggunakan layout utama dengan title
    }

    /**
     * Membuka modal lampiran dan mengatur ID jadwal RTM.
     * @param int $id ID jadwal RTM.
     */
    public function openLampiranModal($id)
    {
        // --- DEBUGGING: Cek apakah metode ini dipanggil ---
        //("Method openLampiranModal called with ID: " . $id);
        // Jika Anda melihat pesan ini di browser, berarti wire:click bekerja.
        // Hapus baris dd() ini setelah debugging.
        // ----------------------------------------------------

        $this->rtmJadwalId = $id;
        $this->showLampiranModal = true;
    }

    public function closeLampiranModal()
    {
        $this->reset(['showLampiranModal', 'rtmJadwalId', 'undangan', 'presensi', 'dokumentasi']);
        $this->dispatch('file-inputs-reset');
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
                'public/lampiran_rtm', "undangan_rtm_{$this->rtmJadwalId}_{$timestamp}.pdf"
            );
            $presensiPath = $this->presensi->storeAs(
                'public/lampiran_rtm', "presensi_rtm_{$this->rtmJadwalId}_{$timestamp}.pdf"
            );
            $dokumentasiPath = $this->dokumentasi->storeAs(
                'public/lampiran_rtm', "dokumentasi_rtm_{$this->rtmJadwalId}_{$timestamp}.pdf"
            );

            RtmLampiran::updateOrCreate(
                ['rtm_jadwal_id' => $this->rtmJadwalId],
                [
                    'undangan' => $undanganPath,
                    'presensi' => $presensiPath,
                    'dokumentasi' => $dokumentasiPath,
                ]
            );

            $this->dispatch('swal:success', [
                'title' => 'Lampiran berhasil disimpan!',
                'text' => 'Data lampiran telah disimpan dengan sukses.'
            ]);

            $this->closeLampiranModal();
            $this->dispatch('refreshComponent');
        } catch (\Exception $e) {
            Log::error("Error saving RTM lampiran: " . $e->getMessage());
            $this->dispatch('swal:error', [
                'title' => 'Terjadi Kesalahan!',
                'text' => 'Gagal menyimpan lampiran. Silakan coba lagi. Error: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Meminta konfirmasi hapus menggunakan SweetAlert.
     * @param int $id ID jadwal RTM yang akan dihapus.
     */
    public function confirmDelete($id)
    {
        $this->dispatchBrowserEvent('swal:confirm', [
            'title' => 'Hapus Jadwal RTM!',
            'text' => "Apakah Anda yakin ingin menghapus jadwal RTM ini?",
            'id' => $id,
        ]);
    }

    /**
     * Menghapus jadwal RTM setelah konfirmasi.
     * @param int $id ID jadwal RTM yang akan dihapus.
     */
    public function delete($id)
    {
        try {
            $rtmJadwal = RtmJadwal::findOrFail($id);

            // Opsional: Hapus file lampiran terkait dari storage jika ada
            $lampiran = RtmLampiran::where('rtm_jadwal_id', $id)->first();
            if ($lampiran) {
                // Hapus file fisik dari storage
                Storage::delete($lampiran->undangan);
                Storage::delete($lampiran->presensi);
                Storage::delete($lampiran->dokumentasi);
                $lampiran->delete(); // Hapus entri dari database RtmLampiran
            }

            $rtmJadwal->delete(); // Hapus entri dari database RtmJadwal

            // Kirim event SweetAlert sukses ke browser
            $this->dispatch('swal:success', [
                'title' => 'Jadwal RTM berhasil dihapus!',
                'text' => 'Data jadwal RTM telah dihapus dengan sukses.'
            ]);
        } catch (\Exception $e) {
            // Log error untuk debugging lebih lanjut
            Log::error("Error deleting RTM jadwal: " . $e->getMessage());
            // Kirim event SweetAlert error ke browser
            $this->dispatch('swal:error', [
                'title' => 'Terjadi Kesalahan!',
                'text' => 'Gagal menghapus jadwal RTM. Silakan coba lagi. Detail: ' . $e->getMessage()
            ]);
        }
    }
}
