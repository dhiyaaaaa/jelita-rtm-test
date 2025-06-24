<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads; // Import trait untuk upload file
use App\Models\RtmJadwal;
use App\Models\RtmLampiran; // Pastikan model RtmLampiran sudah ada
use Carbon\Carbon; // Untuk format tanggal/waktu
use Illuminate\Support\Facades\Storage; // Untuk menghapus file lama

class RtmUnivJadwalTable extends Component
{
    use WithFileUploads; // Gunakan trait ini untuk properti file

    // Properti publik yang akan tersedia di view Livewire
    public $rtmJadwal;
    public $title = 'Agenda RTM'; // Judul halaman

    // Properti untuk kontrol modal lampiran
    public $showLampiranModal = false;
    public $rtm_jadwal_id; // ID jadwal yang sedang di-upload lampirannya

    // Properti untuk input file (harus sama dengan nama input di Blade)
    public $undangan;
    public $presensi;
    public $dokumentasi;

    // Properti untuk menyimpan path file lampiran yang sudah ada (untuk keperluan preview/update)
    public $existingUndangan;
    public $existingPresensi;
    public $existingDokumentasi;

    // Listener untuk event JavaScript/Livewire (misal: untuk merefresh tabel setelah CRUD)
    protected $listeners = ['refreshTable' => '$refresh'];

    /**
     * Metode mount() dipanggil saat komponen pertama kali diinisialisasi.
     * Mirip dengan constructor atau metode index di controller biasa.
     */
    public function mount()
    {
        $this->loadRtmJadwal(); // Panggil metode untuk memuat data
    }

    /**
     * Memuat data jadwal RTM dari database.
     * Ini bisa dipanggil saat mount atau setelah ada perubahan data (misal: delete/update).
     */
    public function loadRtmJadwal()
    {
        $this->rtmJadwal = RtmJadwal::with(['jadwal_audit', 'rtm_rtl', 'lampiran'])
            ->whereNull('fakultas_id')
            ->whereNull('unit_id')
            ->get();
    }

    /**
     * Metode render() dipanggil setiap kali komponen perlu diperbarui.
     * Ini mengembalikan view Blade komponen.
     */
    public function render()
    {
        // Data yang akan dilewatkan ke view komponen
        return view('livewire.rtm_univ_jadwal_table', [
            'rtmJadwal' => $this->rtmJadwal, // Data yang sudah dimuat di mount()
            'title' => $this->title,
        ]);
    }

    /**
     * Membuka modal lampiran dan mengisi data yang sudah ada (jika ada).
     * Dipanggil dari wire:click di Blade.
     */
    public function openLampiranModal($id)
    {
        $this->resetValidation(); // Hapus pesan validasi dari percobaan sebelumnya
        $this->reset(['undangan', 'presensi', 'dokumentasi']); // Kosongkan input file

        $this->rtm_jadwal_id = $id;
        $this->showLampiranModal = true;

        // Coba muat lampiran yang sudah ada untuk jadwal ini
        $lampiran = RtmLampiran::where('rtm_jadwal_id', $id)->first();
        if ($lampiran) {
            $this->existingUndangan = $lampiran->undangan;
            $this->existingPresensi = $lampiran->presensi;
            $this->existingDokumentasi = $lampiran->dokumentasi;
        } else {
            // Jika tidak ada, pastikan properti existing juga kosong
            $this->reset(['existingUndangan', 'existingPresensi', 'existingDokumentasi']);
        }

        // Dispatch event untuk memberitahu JavaScript agar menampilkan modal Bootstrap
        $this->dispatch('show-bootstrap-modal', ['modalId' => 'lampiranModal']);
    }

    /**
     * Menutup modal lampiran dan mereset properti.
     * Dipanggil dari wire:click di Blade.
     */
    public function closeLampiranModal()
    {
        $this->showLampiranModal = false;
        $this->reset(['rtm_jadwal_id', 'undangan', 'presensi', 'dokumentasi', 'existingUndangan', 'existingPresensi', 'existingDokumentasi']);
        // Dispatch event untuk memberitahu JavaScript agar menyembunyikan modal Bootstrap
        $this->dispatch('hide-bootstrap-modal', ['modalId' => 'lampiranModal']);
    }

    /**
     * Menyimpan lampiran ke storage dan mengupdate database.
     * Dipanggil dari wire:submit.prevent di Blade.
     */
    public function saveLampiran()
    {
        // 1. Validasi input file. 'nullable' karena bisa jadi pengguna tidak mengubah semua file.
        $this->validate([
            'undangan' => 'nullable|file|mimes:pdf|max:2048',
            'presensi' => 'nullable|file|mimes:pdf|max:2048',
            'dokumentasi' => 'nullable|file|mimes:pdf|max:5120',
        ]);

        // Periksa apakah ada lampiran yang diupload atau sudah ada
        $lampiranExists = RtmLampiran::where('rtm_jadwal_id', $this->rtm_jadwal_id)->exists();

        // Jika tidak ada lampiran sebelumnya dan tidak ada file baru yang diupload, beri error
        if (!$lampiranExists && (!$this->undangan && !$this->presensi && !$this->dokumentasi)) {
            $this->addError('undangan', 'Setidaknya satu file (Undangan, Presensi, atau Dokumentasi) harus diupload.');
            $this->addError('presensi', 'Setidaknya satu file (Undangan, Presensi, atau Dokumentasi) harus diupload.');
            $this->addError('dokumentasi', 'Setidaknya satu file (Undangan, Presensi, atau Dokumentasi) harus diupload.');
            return; // Hentikan eksekusi
        }

        try {
            $timestamp = now()->timestamp;
            $rtmJadwalId = $this->rtm_jadwal_id;

            $dataToUpdate = [];
            $currentLampiran = RtmLampiran::where('rtm_jadwal_id', $rtmJadwalId)->first();

            // Handle undangan file
            if ($this->undangan) {
                // Hapus file lama jika ada
                if ($currentLampiran && $currentLampiran->undangan && Storage::disk('public')->exists($currentLampiran->undangan)) {
                    Storage::disk('public')->delete($currentLampiran->undangan);
                }
                $undanganPath = $this->undangan->storeAs(
                    'lampiran_rtm', "undangan_rtm_{$rtmJadwalId}_{$timestamp}.pdf", 'public'
                );
                $dataToUpdate['undangan'] = $undanganPath;
            } else if ($currentLampiran && $currentLampiran->undangan) {
                // Jika tidak ada upload baru, gunakan yang lama
                $dataToUpdate['undangan'] = $currentLampiran->undangan;
            } else {
                 $dataToUpdate['undangan'] = null; // Set null jika tidak ada file baru dan tidak ada file lama
            }

            // Handle presensi file
            if ($this->presensi) {
                if ($currentLampiran && $currentLampiran->presensi && Storage::disk('public')->exists($currentLampiran->presensi)) {
                    Storage::disk('public')->delete($currentLampiran->presensi);
                }
                $presensiPath = $this->presensi->storeAs(
                    'lampiran_rtm', "presensi_rtm_{$rtmJadwalId}_{$timestamp}.pdf", 'public'
                );
                $dataToUpdate['presensi'] = $presensiPath;
            } else if ($currentLampiran && $currentLampiran->presensi) {
                $dataToUpdate['presensi'] = $currentLampiran->presensi;
            } else {
                 $dataToUpdate['presensi'] = null;
            }

            // Handle dokumentasi file
            if ($this->dokumentasi) {
                if ($currentLampiran && $currentLampiran->dokumentasi && Storage::disk('public')->exists($currentLampiran->dokumentasi)) {
                    Storage::disk('public')->delete($currentLampiran->dokumentasi);
                }
                $dokumentasiPath = $this->dokumentasi->storeAs(
                    'lampiran_rtm', "dokumentasi_rtm_{$rtmJadwalId}_{$timestamp}.pdf", 'public'
                );
                $dataToUpdate['dokumentasi'] = $dokumentasiPath;
            } else if ($currentLampiran && $currentLampiran->dokumentasi) {
                $dataToUpdate['dokumentasi'] = $currentLampiran->dokumentasi;
            } else {
                 $dataToUpdate['dokumentasi'] = null;
            }

            // Update atau buat record lampiran di database
            RtmLampiran::updateOrCreate(
                ['rtm_jadwal_id' => $rtmJadwalId],
                $dataToUpdate
            );

            // Tampilkan notifikasi sukses menggunakan SweetAlert melalui dispatch event
            $this->dispatch('sweet-alert', [
                'icon' => 'success',
                'title' => 'Lampiran berhasil disimpan!',
                'text' => 'Data lampiran telah disimpan dengan sukses.',
                'showConfirmButton' => false,
                'timer' => 1500
            ]);

            // Tutup modal setelah sukses
            $this->closeLampiranModal();
            // Muat ulang data tabel agar perubahan terlihat
            $this->loadRtmJadwal();
            // Dispatch event untuk memberitahu JavaScript agar me-re-initialize DataTables
            $this->dispatch('table-updated');

        } catch (\Exception $e) {
            // Tampilkan notifikasi error
            $this->dispatch('sweet-alert', [
                'icon' => 'error',
                'title' => 'Terjadi Kesalahan!',
                'text' => 'Gagal menyimpan lampiran. Silakan coba lagi. Error: ' . $e->getMessage(),
                'confirmButtonText' => 'Tutup'
            ]);
        }
    }

    /**
     * Meminta konfirmasi hapus melalui SweetAlert.
     * Dipanggil dari wire:click di Blade.
     */
    public function confirmDelete($id)
    {
        // Dispatch event untuk memberitahu JavaScript agar menampilkan SweetAlert konfirmasi
        $this->dispatch('confirm-delete', ['id' => $id]);
    }

    /**
     * Menghapus jadwal RTM dan lampirannya dari database dan storage.
     * Dipanggil setelah konfirmasi SweetAlert dari JavaScript.
     */
    public function deleteRtm($id)
    {
        try {
            $rtmJadwal = RtmJadwal::findOrFail($id);
            $lampiran = RtmLampiran::where('rtm_jadwal_id', $id)->first();

            // Hapus file lampiran dari storage jika ada
            if ($lampiran) {
                if ($lampiran->undangan && Storage::disk('public')->exists($lampiran->undangan)) Storage::disk('public')->delete($lampiran->undangan);
                if ($lampiran->presensi && Storage::disk('public')->exists($lampiran->presensi)) Storage::disk('public')->delete($lampiran->presensi);
                if ($lampiran->dokumentasi && Storage::disk('public')->exists($lampiran->dokumentasi)) Storage::disk('public')->delete($lampiran->dokumentasi);
                $lampiran->delete(); // Hapus record lampiran dari database
            }

            $rtmJadwal->delete(); // Hapus record jadwal RTM

            // Tampilkan notifikasi sukses
            $this->dispatch('sweet-alert', [
                'icon' => 'success',
                'title' => 'Dihapus!',
                'text' => 'Jadwal RTM berhasil dihapus.',
                'showConfirmButton' => false,
                'timer' => 1500
            ]);
            // Muat ulang data tabel
            $this->loadRtmJadwal();
            // Dispatch event untuk me-re-initialize DataTables
            $this->dispatch('table-updated');

        } catch (\Exception $e) {
            // Tampilkan notifikasi error
            $this->dispatch('sweet-alert', [
                'icon' => 'error',
                'title' => 'Gagal!',
                'text' => 'Gagal menghapus jadwal RTM: ' . $e->getMessage(),
                'confirmButtonText' => 'Tutup'
            ]);
        }
    }
}