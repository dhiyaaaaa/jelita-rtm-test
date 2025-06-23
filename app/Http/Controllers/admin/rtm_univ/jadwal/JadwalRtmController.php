<?php

namespace App\Http\Controllers\admin\rtm_univ\jadwal;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\JadwalRtmStoreRequest;
use App\Models\JadwalAudit;
use App\Models\RtmJadwal;


class JadwalRtmController extends Controller
{
    public function index()
    {
        // Sweet Alert
        $title = 'Hapus Jadwal RTM!';
        $text = "Apakah Anda yakin ingin menghapus jadwal RTM ini?";
        confirmDelete($title, $text);

        $jadwal = RtmJadwal::with(['jadwal_audit', 'rtm_rtl'])
        ->whereNull('fakultas_id')
        ->whereNull('unit_id')
        ->get();

        $data = [
            'rtmJadwal' => $jadwal,
            'title' => 'Agenda RTM',
        ];
        return view('admin.rtm_univ.index', $data);
    }

    public function create()
    {
        $jadwalAudit = JadwalAudit::all();

        $data = [
            'title' => 'Tambah Jadwal RTM',
            'jadwalAudit' => $jadwalAudit,
        ];
        return view('admin.rtm_univ.jadwal.create', $data);
    }

    public function store(JadwalRtmStoreRequest $request)
    {
        $data = $request->validated(); 

        RtmJadwal::updateOrCreate($data);

        return redirect()->route('admin.rtm-univ.index')->with('success', 'Data RTM berhasil disimpan!');
    }

    public function show(Request $request, $id)
    {
        $item = RtmJadwal::with('jadwal_audit', 'fakultas')->findOrFail($id);

        $isEditMode = $request->has('edit') && $request->edit == 'true';
        $jadwalAudit = JadwalAudit::all();
       
        $data = [
            'title' => 'Detail Rapat Tinjauan Manajemen',
            'item' => $item,
            'isEditMode' => $isEditMode,
            'jadwalAudit' => $jadwalAudit,
        ];

        return view('admin.rtm_univ.jadwal.show', $data);
    }

    public function update(JadwalRtmStoreRequest $request, $id)
    {
        $data = $request->validated();

        $item = RtmJadwal::findOrFail($id);
        $item->update($data);
        
        return redirect()->route('admin.rtm-univ.show', $item->id)->with('success', 'Data RTM berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $rtmJadwal = RtmJadwal::findOrFail($id);
        $rtmJadwal->delete();
        return redirect()->route('admin.rtm-univ.index')->with('success', 'Jadwal RTM berhasil dihapus!');
    }

}
