<?php

namespace App\Livewire\Admin\RtmUniv;

use Livewire\Component;
use App\Models\RtmJadwal;
use App\Models\RtmLampiran; // Pastikan model ini diimport
use Livewire\WithFileUploads; // Untuk mengelola upload file
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage; // Untuk operasi penyimpanan file

class RtmJadwalTable extends Component
{
    use WithFileUploads; // Wajib untuk upload file di Livewire

    public $title = 'Agenda RTM (Livewire)';
    public $rtmJadwal; // Properti untuk menampung data RtmJadwal
    public $rtm_jadwal_id; // Properti untuk menampung ID RTM saat modal lampiran dibuka

    // Properti untuk binding input file di modal lampiran
    public $undangan;
    public $presensi;
    public $dokumentasi;

    // Aturan validasi untuk upload file lampiran
    protected $rules = [
        'undangan' => 'required|file|mimes:pdf|max:2048',
        'presensi' => 'required|file|mimes:pdf|max:2048',
        'dokumentasi' => 'required|file|mimes:pdf|max:5120',
    ];

    // Listener untuk event browser (misalnya dari SweetAlert)
    protected $listeners = ['deleteConfirmed' => 'destroyRtm'];

    /**
     * Metode mount akan dijalankan saat komponen diinisialisasi pertama kali.
     * Digunakan untuk memuat data awal.
     */
    public function mount()
    {
        $this->loadRtmJadwal();
    }

    /**
     * Memuat data jadwal RTM dari database.
     * Dipanggil saat mount dan setelah operasi yang mengubah data (misal: hapus, simpan).
     */
    public function loadRtmJadwal()
    {
        $this->rtmJadwal = RtmJadwal::with(['jadwal_audit', 'rtm_rtl', 'lampiran']) // Tambahkan 'lampiran' jika ada relasi
            ->whereNull('fakultas_id')
            ->whereNull('unit_id')
            ->get();

        // Mengirim event ke browser untuk menginisialisasi ulang DataTables
        // setelah data diperbarui oleh Livewire.
        $this->dispatchBrowserEvent('reloadDataTable');
    }

    /**
     * Metode render dipanggil setiap kali ada perubahan state.
     * Mengembalikan view komponen.
     */
    public function render()
    {
        // Memastikan data selalu segar saat render
        // Jika dataset sangat besar, pertimbangkan menggunakan Livewire pagination
        // atau memuat data di mount saja dan memanggil loadRtmJadwal() secara manual
        // setelah operasi CRUD. Untuk tabel kecil, memanggilnya di render itu aman.
        $this->loadRtmJadwal();
        return view('livewire.admin.rtm-univ.rtm-jadwal-table');
    }

    /**
     * Membuka modal lampiran dan mengatur ID jadwal RTM yang akan diupload lampirannya.
     *
     * @param int $id ID dari RtmJadwal
     */
    public function openLampiranModal($id)
    {
        $this->rtm_jadwal_id = $id;

        // Reset input file saat modal dibuka untuk memastikan input bersih
        $this->undangan = null;
        $this->presensi = null;
        $this->dokumentasi = null;

        // Mengirim event ke browser untuk membuka modal
        $this->dispatchBrowserEvent('openLampiranModal');
    }

    /**
     * Menyimpan file lampiran yang diupload.
     */
    public function storeLampiran()
    {
        $this->validate(); // Jalankan validasi menggunakan aturan yang telah didefinisikan

        try {
            $rtmJadwalId = $this->rtm_jadwal_id;
            $timestamp = now()->timestamp;

            // Simpan file ke direktori storage
            $undanganPath = $this->undangan->storeAs(
                'lampiran_rtm', "undangan_rtm_{$rtmJadwalId}_{$timestamp}.{$this->undangan->getClientOriginalExtension()}"
            );
            $presensiPath = $this->presensi->storeAs(
                'lampiran_rtm', "presensi_rtm_{$rtmJadwalId}_{$timestamp}.{$this->presensi->getClientOriginalExtension()}"
            );
            $dokumentasiPath = $this->dokumentasi->storeAs(
                'lampiran_rtm', "dokumentasi_rtm_{$rtmJadwalId}_{$timestamp}.{$this->dokumentasi->getClientOriginalExtension()}"
            );

            // Simpan path file ke database (gunakan model RtmLampiran)
            RtmLampiran::updateOrCreate(
                ['rtm_jadwal_id' => $rtmJadwalId], // Mencari berdasarkan rtm_jadwal_id
                [
                    'undangan' => $undanganPath,
                    'presensi' => $presensiPath,
                    'dokumentasi' => $dokumentasiPath,
                ]
            );

            // Tampilkan notifikasi sukses menggunakan SweetAlert melalui event browser
            $this->dispatchBrowserEvent('showSuccessToast', ['message' => 'Lampiran RTM berhasil disimpan!']);
            // Tutup modal setelah berhasil
            $this->dispatchBrowserEvent('closeLampiranModal');
            // Reset properti file input agar form bersih untuk upload selanjutnya
            $this->reset(['undangan', 'presensi', 'dokumentasi']);
            // Muat ulang data tabel untuk menampilkan perubahan (jika ada)
            $this->loadRtmJadwal();

        } catch (\Exception $e) {
            // Tampilkan notifikasi error jika terjadi kesalahan
            $this->dispatchBrowserEvent('showErrorToast', ['message' => 'Terjadi kesalahan saat menyimpan lampiran: ' . $e->getMessage()]);
        }
    }

    /**
     * Meminta konfirmasi penghapusan dengan SweetAlert.
     *
     * @param int $id ID RTM yang akan dihapus
     */
    public function confirmRtmDeletion($id)
    {
        $this->rtmToDeleteId = $id; // Simpan ID RTM yang akan dihapus
        $this->dispatchBrowserEvent('showDeleteConfirmation'); // Memicu SweetAlert konfirmasi di browser
    }

    /**
     * Menghapus jadwal RTM setelah dikonfirmasi.
     */
    public function destroyRtm()
    {
        if ($this->rtmToDeleteId) {
            try {
                $rtmJadwal = RtmJadwal::findOrFail($this->rtmToDeleteId);

                // Hapus file lampiran terkait (opsional, tergantung kebutuhan Anda)
                if ($rtmJadwal->lampiran) {
                    if (Storage::exists($rtmJadwal->lampiran->undangan)) {
                        Storage::delete($rtmJadwal->lampiran->undangan);
                    }
                    if (Storage::exists($rtmJadwal->lampiran->presensi)) {
                        Storage::delete($rtmJadwal->lampiran->presensi);
                    }
                    if (Storage::exists($rtmJadwal->lampiran->dokumentasi)) {
                        Storage::delete($rtmJadwal->lampiran->dokumentasi);
                    }
                    $rtmJadwal->lampiran->delete(); // Hapus record lampiran dari DB
                }

                $rtmJadwal->delete(); // Hapus record jadwal RTM
                $this->dispatchBrowserEvent('showSuccessToast', ['message' => 'Jadwal RTM berhasil dihapus!']);
            } catch (\Exception $e) {
                $this->dispatchBrowserEvent('showErrorToast', ['message' => 'Gagal menghapus jadwal RTM: ' . $e->getMessage()]);
            }
        }
        $this->reset('rtmToDeleteId'); // Bersihkan ID setelah mencoba menghapus
        $this->loadRtmJadwal(); // Muat ulang data tabel
    }
}
