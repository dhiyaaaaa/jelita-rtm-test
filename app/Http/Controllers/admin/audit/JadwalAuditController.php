<?php

namespace App\Http\Controllers\admin\audit;

use App\Http\Controllers\Controller;
use App\Http\Requests\JadwalAuditStoreUpdateRequest;
use App\Http\Requests\SettingUpdateRequest;
use App\Models\Auditee;
use App\Models\Fakultas;
use App\Models\Form;
use App\Models\Instrumen;
use App\Models\Jabatan;
use App\Models\JadwalAudit;
use App\Models\Prodi;
use App\Models\Setting;
use App\Models\Unit;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Yajra\DataTables\DataTables;

class JadwalAuditController extends Controller
{
    // Ajax Request
    // Get All Instrumen
    public function get_instrumen(Request $request): JsonResponse
    {
        if ($request->ajax()) {
            try {
                $instrumen = Instrumen::with(['level'])
                    ->select(['id', 'kode', 'pernyataan', 'level_id']);
                // ->orderBy('level_id', 'asc');
                // ->orderBy('standar_id', 'asc')
                // ->orderBy('kategori_id', 'asc')
                // ->orderByRaw("REGEXP_REPLACE(kode, '[^0-9]', '', 'g')::int NULLS FIRST, REGEXP_REPLACE(kode, '[0-9]', '', 'g') ASC");
                
                // $total = $instrumen->count();

                // if ($searchValue = $request->input('search.value')) {
                //     $instrumen->where(function ($query) use ($searchValue) {
                //         $query->where('pernyataan', 'ilike', "%{$searchValue}%")
                //             ->orWhere('kode', 'ilike', "%{$searchValue}%")
                //             ->orWhereHas('jenjang', function ($query) use ($searchValue) {
                //                 $query->where('nama', 'ilike', "%{$searchValue}%");
                //             })
                //             ->orWhereHas('prodi', function ($query) use ($searchValue) {
                //                 $query->where('nama', 'ilike', "%{$searchValue}%");
                //             })
                //             ->orWhereHas('jabatan', function ($query) use ($searchValue) {
                //                 $query->where('nama', 'ilike', "%{$searchValue}%");
                //             })
                //             ->orWhereHas('level', function ($query) use ($searchValue) {
                //                 $query->where('nama', 'ilike', "%{$searchValue}%");
                //             })
                //             ->orWhereHas('jabatan.unit', function ($query) use ($searchValue) {
                //                 $query->where('nama', 'ilike', "%{$searchValue}%");
                //             });
                //     });
                // }

                return DataTables::of($instrumen)
                    ->addColumn('checkbox', function ($item) {
                        return $item->id;
                    })
                    // ->addColumn('jenjang', function ($row) {
                    //     if ($row->jenjang->isNotEmpty() && $row->prodi->isNotEmpty()) {
                    //         $jenjang = $row->jenjang->pluck('nama')->map(function ($nama) {
                    //             return '<span class="badge bg-secondary mb-2">' . $nama . '</span>';
                    //         })->implode(' ');

                    //         $prodi = $row->prodi->pluck('nama')->map(function ($nama) {
                    //             return '<span class="badge bg-info mb-2">' . $nama . '</span>';
                    //         })->implode(' ');

                    //         return $jenjang . ' ' . $prodi;
                    //     } elseif ($row->jenjang->isNotEmpty()) {
                    //         return $row->jenjang->pluck('nama')->map(function ($nama) {
                    //             return '<span class="badge bg-secondary mb-2">' . $nama . '</span>';
                    //         })->implode(' ');
                    //     } elseif ($row->prodi->isNotEmpty()) {
                    //         return $row->prodi->pluck('nama')->map(function ($nama) {
                    //             return '<span class="badge bg-info mb-2">' . $nama . '</span>';
                    //         })->implode(' ');
                    //     } elseif ($row->jabatan->isNotEmpty()) {
                    //         return $row->jabatan->pluck('nama')->map(function ($nama) {
                    //             return '<span class="badge bg-secondary mb-2">' . $nama . '</span>';
                    //         })->implode(' ');
                    //     } else {
                    //         return '<span class="badge">-</span>';
                    //     }
                    // })
                    // ->addColumn('unit', function ($row) {
                    //     if (in_array($row->level->slug, ['prodi', 'fakultas'])) {
                    //         return '-';
                    //     } else {
                    //         foreach ($row->jabatan as $jab) {
                    //             $units = $jab->unit->pluck('nama')->unique()->map(function ($nama) {
                    //                 return '<span class="badge bg-info mb-2">' . $nama . '</span>';
                    //             })->implode(' ');

                    //             return $units ?: '-';
                    //         }
                    //     }
                    // })
                    ->addColumn('level', function ($row) {
                        if ($row->level->slug === 'prodi') {
                            return '<span class="badge" style="background-color: #f012be;color: #fff; ">PS</span>';
                        } else {
                            return '<span class="badge" style="background-color: #ff851b;color: #fff; ">UPPS</span>';
                        }
                    })
                    ->rawColumns(['level', 'checkbox'])
                    // ->setTotalRecords($total)
                    // ->setFilteredRecords($total)
                    ->make(true);
            } catch (\Exception $e) {
                return response()->json([
                    'error' => 'Error'
                ], 500);
            }
        }
        return response()->json([
            'error' => 'Invalid request.'
        ], 400);
    }

    public function get_instrumen_by_jadwal(Request $request, JadwalAudit $jadwalAudit): JsonResponse
    {
        if ($request->ajax()) {
            try {
                $instrumen = Form::where('jadwal_id', $jadwalAudit->id)
                    ->with(['instrumen.jenjang', 'instrumen.prodi', 'instrumen.level', 'instrumen.jabatan.unit'])
                    ->select(['form.id', 'instrumen_id', 'instrumen.kode', 'instrumen.pernyataan'])
                    ->join('instrumen', 'form.instrumen_id', '=', 'instrumen.id')
                    ->orderBy('instrumen.level_id', 'asc')
                    ->orderBy('instrumen.standar_id', 'asc')
                    ->orderBy('instrumen.kategori_id', 'asc')
                    ->orderByRaw("REGEXP_REPLACE(instrumen.kode, '[^0-9]', '', 'g')::int NULLS FIRST, REGEXP_REPLACE(instrumen.kode, '[0-9]', '', 'g') ASC");

                return DataTables::of($instrumen)
                    ->addColumn('jenjang', function ($row) {
                        if ($row->instrumen->jenjang->isNotEmpty() && $row->instrumen->prodi->isNotEmpty()) {
                            $jenjang = $row->instrumen->jenjang->pluck('nama')->map(function ($nama) {
                                return '<span class="badge bg-secondary mb-2">' . $nama . '</span>';
                            })->implode(' ');

                            $prodi = $row->instrumen->prodi->pluck('nama')->map(function ($nama) {
                                return '<span class="badge bg-info mb-2">' . $nama . '</span>';
                            })->implode(' ');

                            return $jenjang . ' ' . $prodi;
                        } elseif ($row->instrumen->jenjang->isNotEmpty()) {
                            return $row->instrumen->jenjang->pluck('nama')->map(function ($nama) {
                                return '<span class="badge bg-secondary mb-2">' . $nama . '</span>';
                            })->implode(' ');
                        } elseif ($row->instrumen->prodi->isNotEmpty()) {
                            return $row->instrumen->prodi->pluck('nama')->map(function ($nama) {
                                return '<span class="badge bg-info mb-2">' . $nama . '</span>';
                            })->implode(' ');
                        } elseif ($row->instrumen->jabatan->isNotEmpty()) {
                            return $row->instrumen->jabatan->pluck('nama')->map(function ($nama) {
                                return '<span class="badge bg-secondary mb-2">' . $nama . '</span>';
                            })->implode(' ');
                        } else {
                            return '<span class="badge">-</span>';
                        }
                    })
                    ->addColumn('unit', function ($row) {
                        if (in_array($row->instrumen->level->slug, ['prodi', 'fakultas'])) {
                            return '-';
                        } else {
                            foreach ($row->instrumen->jabatan as $jab) {
                                $units = $jab->unit->pluck('nama')->unique()->map(function ($nama) {
                                    return '<span class="badge bg-info mb-2">' . $nama . '</span>';
                                })->implode(' ');

                                return $units ?: '-';
                            }
                        }
                    })
                    ->addColumn('level', function ($row) {
                        if ($row->instrumen->level->slug === 'prodi') {
                            return '<span class="badge" style="background-color: #f012be;color: #fff; ">PS</span>';
                        } else {
                            return '<span class="badge" style="background-color: #ff851b;color: #fff; ">UPPS</span>';
                        }
                    })
                    ->addColumn('action', function ($row) {
                        $btn = '<div class="dropdown">
                            <button class="btn btn-success dropdown-toggle" type="button" id="dropdownMenuButton' . $row->instrumen->id . '" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                Actions
                            </button>
                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton' . $row->instrumen->id . '">
                                <a class="dropdown-item" href="' . route('instrumen.edit', $row->instrumen->id) . '">Edit</a>
                                <a class="dropdown-item" href="' . route('jadwal_audit.delete_form', $row->id) . '" data-confirm-delete="true">Hapus</a>
                            </div>
                        </div>';
                        return $btn;
                    })
                    ->rawColumns(['jenjang', 'unit', 'level', 'action'])
                    ->filter(function ($query) use ($request) {
                        if ($request->has('search.value')) {
                            $searchValue = $request->input('search.value');
                            $query->where(function ($query) use ($searchValue) {
                                $query->where('instrumen.pernyataan', 'ilike', "%{$searchValue}%")
                                    ->orWhere('instrumen.kode', 'ilike', "%{$searchValue}%")
                                    ->orWhereHas('instrumen.jenjang', function ($query) use ($searchValue) {
                                        $query->where('nama', 'ilike', "%{$searchValue}%");
                                    })
                                    ->orWhereHas('instrumen.prodi', function ($query) use ($searchValue) {
                                        $query->where('nama', 'ilike', "%{$searchValue}%");
                                    })
                                    ->orWhereHas('instrumen.jabatan', function ($query) use ($searchValue) {
                                        $query->where('nama', 'ilike', "%{$searchValue}%");
                                    })
                                    ->orWhereHas('instrumen.level', function ($query) use ($searchValue) {
                                        $query->where('nama', 'ilike', "%{$searchValue}%");
                                    })
                                    ->orWhereHas('instrumen.jabatan.unit', function ($query) use ($searchValue) {
                                        $query->where('nama', 'ilike', "%{$searchValue}%");
                                    });
                            });
                        }
                    })
                    ->make(true);
            } catch (\Exception $e) {
                return response()->json([
                    'error' => 'Error'
                ], 500);
            }
        }
        return response()->json([
            'error' => 'Invalid request.'
        ], 400);
    }



    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $jadwal = JadwalAudit::with(['assessment_form'])->orderBy('created_at', 'DESC')->get();

        // Sweet Alert
        $title = 'Hapus Jadwal!';
        $text = 'Apakah Anda yakin ingin menghapus?';
        confirmDelete($title, $text);

        $data = [
            'title' => 'Jadwal Audit',
            'jadwalAudit' => $jadwal,
        ];

        return view('admin.audit.jadwal_audit.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $data = [
            'title' => 'Tambah Jadwal Audit',
        ];

        return view('admin.audit.jadwal_audit.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(JadwalAuditStoreUpdateRequest $request): RedirectResponse
    {
        $jadwal = JadwalAudit::create([
            'jadwal' => $request->jadwal,
            'tgl_mulai' => $request->tgl_mulai,
            'tgl_selesai' => $request->tgl_selesai,
        ]);

        if ($request->instrumen) {
            foreach ($request->instrumen as $instrumen) {
                Form::create([
                    'jadwal_id' => $jadwal->id,
                    'instrumen_id' => $instrumen,
                ]);
            }
        }

        // Tambah Auditee
        $settingProdi = Setting::where('nama_setting', 'prodi')->pluck('jabatan_id')->first();
        $userKaprodi = Prodi::whereHas('user.jabatan', function ($query) use ($settingProdi) {
            $query->where('jabatan_id', $settingProdi);
        })->with(['user.jabatan'])->get();

        foreach ($userKaprodi as $prodi) {
            foreach ($prodi->user as $user) {
                foreach ($user->jabatan as $jabatan) {
                    if ($jabatan->id === $settingProdi) {
                        Auditee::create([
                            'user_id' => $user->id,
                            'jabatan_id' => $jabatan->id,
                            'prodi_id' => $prodi->id,
                            'jadwal_audit_id' => $jadwal->id,
                        ]);
                    }
                }
            }
        }

        // Auditee Fakultas (Dekan WD)
        $settingFakultas = Setting::where('nama_setting', 'fakultas')->pluck('jabatan_id')->toArray();
        $userFakultas = Fakultas::whereHas('user.jabatan', function ($query) use ($settingFakultas) {
            $query->whereIn('jabatan_id', $settingFakultas);
        })->with(['user.jabatan'])->get();

        foreach ($userFakultas as $fakultas) {
            foreach ($fakultas->user as $user) {
                foreach ($user->jabatan as $jabatan) {
                    if (in_array($jabatan->id, $settingFakultas)) {
                        Auditee::create([
                            'user_id' => $user->id,
                            'jabatan_id' => $jabatan->id,
                            'fakultas_id' => $fakultas->id,
                            'jadwal_audit_id' => $jadwal->id,
                        ]);
                    }
                }
            }
        }

        // Auditee Unit
        $settingUniversitas = Setting::where('nama_setting', 'universitas')->pluck('jabatan_id')->toArray();
        $userUnit = Unit::whereHas('user.jabatan', function ($query) use ($settingUniversitas) {
            $query->whereIn('jabatan_id', $settingUniversitas);
        })->with(['user.jabatan'])->get();

        foreach ($userUnit as $unit) {
            foreach ($unit->user as $user) {
                foreach ($user->jabatan as $jabatan) {
                    if (in_array($jabatan->id, $settingUniversitas)) {
                        Auditee::create([
                            'user_id' => $user->id,
                            'jabatan_id' => $jabatan->id,
                            'unit_id' => $unit->id,
                            'jadwal_audit_id' => $jadwal->id,
                        ]);
                    }
                }
            }
        }

        return redirect()->route('jadwal_audit')->with('success', 'Jadwal berhasil ditambah!');
    }

    /**
     * Display the specified resource.
     */
    public function show(JadwalAudit $jadwalAudit): View
    {
        $form = Form::where('jadwal_id', $jadwalAudit->id)->get();

        // Sweet Alert
        $title = 'Hapus Instrumen dari Jadwal ' . $jadwalAudit->jadwal . '!';
        $text = 'Apakah Anda yakin ingin menghapus?';
        confirmDelete($title, $text);

        $data = [
            'title' => 'Jadwal Audit',
            'form' => $form,
            'jadwalAudit' => $jadwalAudit,
        ];

        return view('admin.audit.jadwal_audit.show', $data);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(JadwalAudit $jadwalAudit): View
    {
        $instrumen = Instrumen::with(['level'])->get();
        $instrumenSelected = Form::where('jadwal_id', $jadwalAudit->id)->pluck('instrumen_id')->toArray();

        $data = [
            'title' => 'Edit Jadwal Audit',
            'instrumen' => $instrumen,
            'instrumenSelected' => $instrumenSelected,
            'jadwalAudit' => $jadwalAudit,
        ];

        return view('admin.audit.jadwal_audit.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(JadwalAuditStoreUpdateRequest $request, JadwalAudit $jadwalAudit): RedirectResponse
    {
        $jadwalAudit->update([
            'jadwal' => $request->jadwal,
            'tgl_mulai' => $request->tgl_mulai,
            'tgl_selesai' => $request->tgl_selesai,
        ]);

        $instrumen_lama = Form::where('jadwal_id', $jadwalAudit->id)->pluck('instrumen_id')->toArray();

        $instrumen_hapus = array_diff($instrumen_lama, $request->instrumen);
        $instrumen_tambah = array_diff($request->instrumen, $instrumen_lama);

        Form::where('jadwal_id', $jadwalAudit->id)->whereIn('instrumen_id', $instrumen_hapus)->delete();

        foreach ($instrumen_tambah as $instrumen) {
            Form::create([
                'jadwal_id' => $jadwalAudit->id,
                'instrumen_id' => $instrumen,
            ]);
        }

        return redirect()->route('jadwal_audit')->with('success', 'Jadwal berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(JadwalAudit $jadwalAudit): RedirectResponse
    {
        $jadwalAudit->delete();

        return back()->with('success', 'Jadwal berhasil dihapus!');
    }

    public function destroy_form(Form $form): RedirectResponse
    {
        $form->delete();

        return back()->with('success', 'Instrumen berhasil dihapus!');
    }

    // Import
    public function import(JadwalAudit $jadwalAudit): RedirectResponse
    {
        $jadwalAudit->update([
            'import' => $jadwalAudit->import === true ? false : true,
        ]);

        return redirect()->route('jadwal_audit')->with('success', $jadwalAudit->import === true ? 'Fitur Import berhasil dihidupkan!' : 'Fitur Import berhasil dimatikan!');
    }

    // Auditor
    public function fitur_auditor(JadwalAudit $jadwalAudit): RedirectResponse
    {
        $jadwalAudit->update([
            'fitur_auditor' => $jadwalAudit->fitur_auditor === true ? false : true,
        ]);

        return redirect()->route('jadwal_audit')->with('success', $jadwalAudit->fitur_auditor === true ? 'Fitur Auditor berhasil dihidupkan!' : 'Fitur Auditor berhasil dimatikan!');
    }

    // Setting
    public function edit_setting(): View
    {
        $prodi = Jabatan::where('type', 'prodi')->get();
        $selectedProdi = Setting::where('nama_setting', 'prodi')->pluck('jabatan_id')->toArray();
        $fakultas = Jabatan::where('type', 'fakultas')->get();
        $selectedFakultas = Setting::where('nama_setting', 'fakultas')->pluck('jabatan_id')->toArray();
        $universitas = Jabatan::where('type', 'universitas')->get();
        $selectedUniversitas = Setting::where('nama_setting', 'universitas')->pluck('jabatan_id')->toArray();

        $data = [
            'title' => 'Setting Jadwal Audit',
            'prodi' => $prodi,
            'fakultas' => $fakultas,
            'universitas' => $universitas,
            'selectedProdi' => $selectedProdi,
            'selectedFakultas' => $selectedFakultas,
            'selectedUniversitas' => $selectedUniversitas,
        ];

        return view('admin.audit.jadwal_audit.setting', $data);
    }

    public function update_setting(SettingUpdateRequest $request): RedirectResponse
    {
        $all = array_merge($request->prodi, $request->fakultas, $request->universitas);

        $lama = Setting::pluck('jabatan_id')->toArray();
        $hapus = array_diff($lama, $all);
        $tambah = array_diff($all, $lama);

        Setting::whereIn('jabatan_id', $hapus)->delete();

        foreach ($tambah as $item) {
            $jabatan = Jabatan::findOrFail($item);

            Setting::updateOrCreate([
                'nama_setting' => $jabatan->type,
                'jabatan_id' => $item,
            ]);
        }

        return redirect()->route('jadwal_audit')->with('success', 'Setting berhasil diperbarui!');
    }
}
