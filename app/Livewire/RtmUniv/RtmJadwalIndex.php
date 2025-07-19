<?php

namespace App\Livewire\RtmUniv;

use App\Models\RtmJadwal;
use App\Models\RtmLampiran;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

use Livewire\Component;

class RtmJadwalIndex extends Component
{
    use WithPagination, WithFileUploads;

    public $search = '';
    public $perPage = 10;
    protected $paginationTheme = 'bootstrap'; 

    public $title = 'Agenda RTM';

    public $rtmJadwalId;
   

    public function updatedSearch()
    {
        $this->resetPage();
    }


    public function deleteJadwal($id)
    {
        try {
            $rtmJadwal = RtmJadwal::findOrFail($id);
            $rtmJadwal->delete();
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
        $rtmJadwal = RtmJadwal::with(['jadwal_audit'])
            ->whereNull('fakultas_id')
            ->whereNull('unit_id')
            ->where(function ($query) {
                $query->where('agenda', 'like', '%' . $this->search . '%')
                      ->orWhere('tanggal', 'like', '%' . $this->search . '%')
                      ->orWhereHas('jadwal_audit', function ($q) {
                          $q->where('jadwal', 'like', '%' . $this->search . '%');
                      });
            })
            ->paginate($this->perPage); 

        return view('livewire.rtm-univ.rtm-jadwal-index', [
            'rtmJadwal' => $rtmJadwal,
            'title' => $this->title,
        ])->layout('components.layout.main_layout', ['title' => 'Agenda RTM (Livewire Version)']);
    }
}
