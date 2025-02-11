<?php

namespace App\Http\Controllers\dekan;

use App\Http\Controllers\Controller;
use App\Http\Requests\JadwalRtmStoreRequest;
use App\Models\JadwalAudit;
use App\Models\RtmJadwal;
use App\Models\RtmRtl;
use App\Models\Fakultas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class RtmJadwalController extends Controller
{
    protected $user;
    protected $fakultasUser ;

    public function __construct()
    {
        $this->user = Auth::user();
        $this->fakultasUser = $this->user->prodi->isNotEmpty()
            ? $this->user->prodi->first()->fakultas->id
            : ($this->user->fakultas->isNotEmpty() ? $this->user->fakultas->first()->id : null);
        
    }

    public function index()
    {
        $jadwal = RtmJadwal::with(['jadwal_audit', 'fakultas', 'rtm_rtl'])
            ->where('fakultas_id', $this->fakultasUser)
            ->get();

        $data = [
            'fakultasId' => $this->fakultasUser,
            'rtmJadwal' => $jadwal,
            'title' => 'Agenda RTM',
        ];
        return view('dekan.rtm.index', $data);
    }

    public function create()
    {

        $jadwalAudit = JadwalAudit::all();
        $fakultas = Fakultas::where('id', $this->fakultasUser)->get();
    
        $data = [
            'title' => 'Tambah Jadwal RTM',
            'jadwalAudit' => $jadwalAudit,
            'fakultas' => $fakultas,
        ];
        return view('dekan.rtm.jadwal.create', $data);
    }

    public function store(JadwalRtmStoreRequest $request)
    {
        $data = $request->validated(); 

        $data['fakultas_id'] = $request->fakultas_id; 

        $data['jadwal_audit_id'] = $request->jadwal_audit_id; 

        RtmJadwal::create($data);

        return redirect()->route('dekan.jadwal-rtm.index')->with('success', 'Data RTM berhasil disimpan!');
    }

    public function show(Request $request, $id)
    {
        $item = RtmJadwal::with('jadwal_audit', 'fakultas')->findOrFail($id);

        $isEditMode = $request->has('edit') && $request->edit == 'true';
        $jadwalAudit = JadwalAudit::all();
        $fakultas = Fakultas::where('id', $this->fakultasUser)->get();

        $data = [
            'title' => 'Detail Rapat Tinjauan Manajemen',
            'item' => $item,
            'isEditMode' => $isEditMode,
            'jadwalAudit' => $jadwalAudit,
            'fakultas' => $fakultas,
        ];

        return view('dekan.rtm.jadwal.show', $data);
    }

    public function update(JadwalRtmStoreRequest $request, $id)
    {
        $data = $request->validated();

        $item = RtmJadwal::findOrFail($id);
        $item->update($data);
        
        return redirect()->route('dekan.jadwal-rtm.show', $item->id)->with('success', 'Data RTM berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $rtmJadwal = RtmJadwal::findOrFail($id);
        $rtmJadwal->delete();
        return redirect()->route('dekan.jadwal-rtm.index')->with('success', 'Jadwal RTM berhasil dihapus!');
    }
}
