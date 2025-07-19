<?php

namespace App\Livewire\RtmUniv;

use App\Models\RtmCatatan;
use App\Models\RtmJadwal;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Livewire\Component;

class RtmCatatanForm extends Component
{
    public RtmJadwal $rtmJadwal;
    public $title = 'Narasi Laporan RTM';
    public $rtmCatatan;
    public $judul = '';
    public $isi = '';

    public $editingCatatanId = null;
    public $editingJudul = '';
    public $editingIsi = '';

    protected $listeners = [
        'summernoteUpdate', 
        'deleteCatatan',
        'resetSummernoteFromLivewire' => 'resetFormFields', 
    ];

    public function mount(RtmJadwal $rtmJadwal)
    {
        $this->rtmJadwal = $rtmJadwal;
        $this->loadRtmCatatan();

        $sessionKey = 'session_catatan_' . $this->rtmJadwal->id;
        if (session()->has($sessionKey)) {
            $sessionCatatan = session()->get($sessionKey);
            $this->judul = $sessionCatatan['judul'] ?? '';
            $this->isi = $sessionCatatan['isi'] ?? '';
        }
    }

    public function loadRtmCatatan()
    {
        $this->rtmCatatan = RtmCatatan::where('rtm_jadwal_id', $this->rtmJadwal->id)
                                    ->get();
    }

    protected function rules()
    {
        return [
            'judul' => 'required|string|max:255',
            'isi' => 'required|string',
        ];
    }

    protected function messages()
    {
        return [
            'judul.required' => 'Judul wajib diisi.',
            'isi.required' => 'Isi wajib diisi.',

            'editingJudul.required' => 'Judul wajib diisi.',
            'editingIsi.required' => 'Isi wajib diisi.',
        ];
    }

    public function store()
    {
        try {
            $this->validate();

            RtmCatatan::create([
                'rtm_jadwal_id' => $this->rtmJadwal->id,
                'user_id' =>Auth::id(),
                'judul' => $this->judul,
                'isi' => $this->isi,
            ]);

            $this->reset(['judul', 'isi']); 
            $this->loadRtmCatatan(); 
            session()->forget('session_catatan_' . $this->rtmJadwal->id); 

            $this->dispatch('resetSummernote');
            $this->dispatch('show-alert', ['type' => 'success', 'message' => 'catatan berhasil disimpan.']);
        } catch (ValidationException $e) {
            $this->dispatch('show-alert', ['type' => 'error', 'message' => 'Terdapat kesalahan validasi.']);
            throw $e; 
        }
    }

    public function edit($id)
    {
        $catatan = RtmCatatan::findOrFail($id);
        $this->editingCatatanId = $catatan->id;
        $this->editingJudul = $catatan->judul;
        $this->editingIsi = $catatan->isi;

        // Emit an event to re-initialize Summernote with the content
        // Livewire.dispatch('initSummernoteEdit', [{ id: 'main', content: this.editingIsi }]);
        // This is already in your JS, just make sure your Livewire component triggers it correctly on edit method.
        $this->dispatch('initSummernoteEdit', ['id' => 'main', 'content' => $catatan->isi]);
    }

    public function update()
    {
        
        $this->validate([
            'editingJudul' => 'required|string',
            'editingIsi' => 'required|string',
        ]);

        $catatan = RtmCatatan::find($this->editingCatatanId);
        if ($catatan) {
            $catatan->update([
                'judul' => $this->editingJudul,
                'isi' => $this->editingIsi,
            ]);
            $this->cancelEdit(); 
            $this->loadRtmCatatan(); 
            $this->dispatch('show-alert', ['type' => 'success', 'message' => 'Catatan berhasil diperbarui.']);
        }
    }

    public function cancelEdit()
    {
        $this->editingCatatanId = null;
        $this->editingJudul = '';
        $this->editingIsi = '';
        $this->dispatch('resetSummernote'); 
    }

    public function updated($propertyName)
    {
        if (in_array($propertyName, ['judul', 'isi'])) {
            $this->saveSession();
        }
    }

    public function summernoteUpdate($data)
    {
        if ($data['id'] === 'create') {
        $this->isi = $data['content'];
        } elseif ($data['id'] === 'edit') {
            $this->editingIsi = $data['content'];
        }
    }

    public function confirmDelete($id)
    {
        $this->dispatch('confirmDeleteAlert', $id);
    }

    public function deleteCatatan($id)
    {
        $catatan = RtmCatatan::find($id);
        if ($catatan) {
            $catatan->delete();
            $this->loadRtmCatatan();
            $this->dispatch('show-alert', ['type' => 'success', 'message' => 'Catatan narasi berhasil dihapus.']);
            
            if ($this->editingCatatanId == $id) {
                $this->cancelEdit(); 
            }
        }
    }

    private function saveSession()
    {
        session()->put('session_catatan_' . $this->rtmJadwal->id, [
            'judul' => $this->judul,
            'isi' => $this->isi,
        ]);
    }

     public function resetFormFields()
    {
        $this->reset(['judul', 'isi', 'editingJudul', 'editingIsi', 'editingCatatanId']);
        $this->dispatch('resetSummernote'); 
    }

    public function render()
    {
        $rtmRtl = $this->rtmJadwal->rtm_rtl()->latest()->first();
        return view('livewire.rtm-univ.rtm-catatan-form', [
            'rtmRtl' => $rtmRtl,
            'user' => Auth::user(),
        ])->layout('components.layout.main_layout', ['title' => $this->title]);
    }
}
