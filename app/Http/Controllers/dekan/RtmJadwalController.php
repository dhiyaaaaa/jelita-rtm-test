<?php 
namespace App\Http\Controllers\dekan;

use App\Http\Controllers\Controller;
use App\Http\Requests\JadwalRtmStoreRequest;
use App\Models\JadwalAudit;
use App\Models\RtmJadwal;
use App\Models\Unit;
use App\Models\Fakultas;
use App\Models\Prodi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class RtmJadwalController extends Controller
{
    protected $user;
    protected $unitOrFakultasId;
    protected $isUnit; 

    public function __construct()
    {
        $this->user = Auth::user();

        // Ambil data pivot dari relasi jabatan
        $jabatanUser = Auth::user()->jabatan->isNotEmpty() ? Auth::user()->jabatan->first() : null;

        if ($jabatanUser) {
            if ($jabatanUser->pivot->unit_id) {
                $this->unitOrFakultasId = $jabatanUser->pivot->unit_id;
                $this->isUnit = true;
            }

            elseif ($jabatanUser->pivot->prodi_id) {
                $prodi = Prodi::find($jabatanUser->pivot->prodi_id);
                if ($prodi) {
                    $this->unitOrFakultasId = $prodi->fakultas_id;
                    $this->isUnit = false; 
                }
            }
            elseif ($jabatanUser->pivot->fakultas_id) {
                $this->unitOrFakultasId = $jabatanUser->pivot->fakultas_id;
                $this->isUnit = false;
            }
            else {
                $this->unitOrFakultasId = null;
                $this->isUnit = false;
            }
        } else {
            $this->unitOrFakultasId = null;
            $this->isUnit = false;
        }
    }

    public function index()
    {
        $title = 'Hapus Rencana Tindak Lanjut!';
        $text = "Apakah Anda yakin ingin menghapus rencana tindak lanjut ini?";
        confirmDelete($title, $text);
        $jadwal = RtmJadwal::with(['jadwal_audit', 'fakultas', 'rtm_rtl'])
            ->when($this->isUnit, function ($query) {
                return $query->where('unit_id', $this->unitOrFakultasId);
            }, function ($query) {
                return $query->where('fakultas_id', $this->unitOrFakultasId);
            })
            ->get();

        $data = [
            'unitOrFakultasId' => $this->unitOrFakultasId,
            'rtmJadwal' => $jadwal,
            'title' => 'Agenda RTM',
        ];
        return view('dekan.rtm.index', $data);
    }

    public function create()
    {
        $jadwalAudit = JadwalAudit::all();
        $jabatanUser = Auth::user()->jabatan->isNotEmpty() ? Auth::user()->jabatan->first()->id : null;
        $fakultas = collect([]);
        $units = collect([]);

        if ($jabatanUser) {
            if ($this->isUnit) {
                $units = Unit::where('id', $this->unitOrFakultasId)->get();
            } else {
                $fakultas = Fakultas::where('id', $this->unitOrFakultasId)->get();
            }
        }
        $data = [
            'title' => 'Tambah Jadwal RTM',
            'jadwalAudit' => $jadwalAudit,
            'fakultas' => $fakultas,
            'units' => $units,
            'isUnit' => $this->isUnit,
        ];
        return view('dekan.rtm.jadwal.create', $data);
    }

    public function store(JadwalRtmStoreRequest $request)
    {
        $data = $request->validated(); 

        if ($this->isUnit) {
            $data['unit_id'] = $this->unitOrFakultasId;
        } else {
            $data['fakultas_id'] = $this->unitOrFakultasId;
        }

        $data['jadwal_audit_id'] = $request->jadwal_audit_id; 

        RtmJadwal::updateOrCreate($data);

        return redirect()->route('dekan.jadwal-rtm.index')->with('success', 'Data RTM berhasil disimpan!');
    }

    public function show(Request $request, $id)
    {
        $item = RtmJadwal::with('jadwal_audit', 'fakultas')->findOrFail($id);

        $isEditMode = $request->has('edit') && $request->edit == 'true';
        $jadwalAudit = JadwalAudit::all();
        $fakultas = Fakultas::where('id', $this->unitOrFakultasId)->get();
        $unit = Unit::where('id', $this->unitOrFakultasId)->get();

        $data = [
            'title' => 'Detail Rapat Tinjauan Manajemen',
            'item' => $item,
            'isEditMode' => $isEditMode,
            'jadwalAudit' => $jadwalAudit,
            'fakultas' => $fakultas,
            'unit' => $unit,
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
