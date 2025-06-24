<?php

namespace App\Livewire;

use App\Models\RtmJadwal; // Pastikan model ini diimpor
use App\Models\RtmLampiran; // Pastikan model ini diimpor
use Carbon\Carbon; // Pastikan Carbon diimpor jika Anda menggunakannya di tampilan Livewire Blade
use Livewire\Component;
use Livewire\WithFileUploads; // Diperlukan untuk upload file
use Livewire\WithPagination; // Diperlukan untuk paginasi Livewire
use Illuminate\Support\Facades\Log; // Jika Anda menggunakan Log::error()
use Illuminate\Support\Facades\Storage; // Jika Anda menggunakan Storage::delete()

class RtmJadwalLivewireIndex extends Component // Nama kelas komponen
{
    use WithPagination, WithFileUploads;

    // Properti publik Livewire akan secara otomatis sinkron dengan frontend
    public $search = '';
    public $perPage = 10; // Jumlah item per halaman

    // Properti untuk Modal Lampiran
    public $showLampiranModal = false;
    public $rtmJadwalId;
    public $undangan;
    public $presensi;
    public $dokumentasi;

    // Listener untuk event SweetAlert konfirmasi hapus dari JavaScript
    protected $listeners = ['deleteConfirmed' => 'delete'];

    // Metode ini dipanggil otomatis oleh Livewire sebelum $search diperbarui
    public function updatingSearch()
    {
        $this->resetPage(); // Reset paginasi ke halaman 1 saat pencarian berubah
    }

    // Metode utama untuk merender tampilan komponen
    public function render()
    {
        $rtmJadwal = RtmJadwal::with(['jadwal_audit'])
            ->whereNull('fakultas_id') // Filter khusus untuk data yang Anda inginkan
            ->whereNull('unit_id')
            ->when($this->search, function($query) {
                // Terapkan filter pencarian jika $search tidak kosong
                $query->where('agenda', 'like', '%'.$this->search.'%')
                      ->orWhereHas('jadwal_audit', function($q) {
                          $q->where('jadwal', 'like', '%'.$this->search.'%');
                      });
            })
            ->paginate($this->perPage); // Gunakan paginasi Livewire

        return view('livewire.rtm-jadwal-livewire-index', [ // Pastikan nama view sesuai
            'rtmJadwal' => $rtmJadwal,
            // 'title' => 'Agenda RTM (Livewire)', // Title akan diatur di view host-nya
        ]);
        // TIDAK PERLU ->layout() di sini karena komponen ini akan di-embed di view lain
    }

    // Metode untuk membuka modal lampiran
    public function openLampiranModal($id)
    {
        $this->rtmJadwalId = $id;
        $this->showLampiranModal = true;
    }

    // Metode untuk menutup modal lampiran dan mereset properti
    public function closeLampiranModal()
    {
        $this->reset(['showLampiranModal', 'rtmJadwalId', 'undangan', 'presensi', 'dokumentasi']);
        $this->dispatch('file-inputs-reset'); // Mengirim event ke frontend untuk mereset input file
    }

    // Metode untuk menyimpan lampiran
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

            // Mengirim event sukses ke frontend (untuk SweetAlert)
            $this->dispatch('swal:success', [
                'title' => 'Lampiran berhasil disimpan!',
                'text' => 'Data lampiran telah disimpan dengan sukses.'
            ]);

            $this->closeLampiranModal(); // Tutup modal setelah berhasil
        } catch (\Exception $e) {
            Log::error("Error saving RTM lampiran: " . $e->getMessage());
            $this->dispatch('swal:error', [
                'title' => 'Terjadi Kesalahan!',
                'text' => 'Gagal menyimpan lampiran. Silakan coba lagi. Error: ' . $e->getMessage()
            ]);
        }
    }

    // Metode untuk konfirmasi hapus (mengirim event ke frontend)
    public function confirmDelete($id)
    {
        $this->dispatchBrowserEvent('swal:confirm', [
            'title' => 'Hapus Jadwal RTM!',
            'text' => "Apakah Anda yakin ingin menghapus jadwal RTM ini?",
            'id' => $id,
        ]);
    }

    // Metode untuk menghapus data (dipanggil setelah konfirmasi dari frontend)
    public function delete($id)
    {
        try {
            $rtmJadwal = RtmJadwal::findOrFail($id);
            $lampiran = RtmLampiran::where('rtm_jadwal_id', $id)->first();
            if ($lampiran) {
                Storage::delete($lampiran->undangan);
                Storage::delete($lampiran->presensi);
                Storage::delete($lampiran->dokumentasi);
                $lampiran->delete();
            }
            $rtmJadwal->delete();

            $this->dispatch('swal:success', [
                'title' => 'Jadwal RTM berhasil dihapus!',
                'text' => 'Data jadwal RTM telah dihapus dengan sukses.'
            ]);
        } catch (\Exception $e) {
            Log::error("Error deleting RTM jadwal: " . $e->getMessage());
            $this->dispatch('swal:error', [
                'title' => 'Terjadi Kesalahan!',
                'text' => 'Gagal menghapus jadwal RTM. Silakan coba lagi. Detail: ' . $e->getMessage()
            ]);
        }
    }
}