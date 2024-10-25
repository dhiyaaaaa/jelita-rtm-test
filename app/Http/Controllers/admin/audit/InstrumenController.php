<?php

namespace App\Http\Controllers\admin\audit;

use App\Http\Controllers\Controller;
use App\Http\Requests\InstrumenStoreRequest;
use App\Http\Requests\InstrumenUpdateRequest;
use App\Models\Ayat;
use App\Models\Instrumen;
use App\Models\Jabatan;
use App\Models\JenisPertanyaan;
use App\Models\Jenjang;
use App\Models\Kategori;
use App\Models\Kriteria;
use App\Models\Level;
use App\Models\Pasal;
use App\Models\Peraturan;
use App\Models\Prodi;
use App\Models\Standar;
use App\Models\Unit;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Yajra\DataTables\DataTables;

class InstrumenController extends Controller
{
    // Ajax Request
    // Get Instrumen
    public function get_instrumens(Request $request): JsonResponse
    {
        if ($request->ajax()) {
            try {
                $instrumen = Instrumen::with(['jenjang', 'prodi', 'level', 'jabatan.unit', 'unit'])
                    ->select(['id', 'kode', 'pernyataan', 'level_id'])
                    ->orderBy('level_id', 'asc')
                    ->orderBy('standar_id', 'asc')
                    ->orderBy('kategori_id', 'asc')
                    ->orderByRaw("REGEXP_REPLACE(kode, '[^0-9]', '', 'g')::int NULLS FIRST, REGEXP_REPLACE(kode, '[0-9]', '', 'g') ASC");

                if (!empty($request->jenjangAuditee) && $request->level && $request->level !== 'all' && $request->unit && $request->unit !== 'all') {
                    $instrumen->whereHas('jenjang', function ($query) use ($request) {
                        $query->whereIn('nama', $request->jenjangAuditee);
                    })->orWhereHas('jabatan', function ($query) use ($request) {
                        $query->whereIn('nama', $request->jenjangAuditee);
                    });

                    if ($request->level === 'prodi') {
                        $instrumen->whereHas('level', function ($query) {
                            $query->where('slug', 'prodi');
                        });
                    } elseif ($request->level === 'fakultas') {
                        $instrumen->whereHas('level', function ($query) {
                            $query->where('slug', 'fakultas');
                        });
                    } elseif ($request->level === 'universitas') {
                        $instrumen->whereHas('level', function ($query) {
                            $query->where('slug', 'universitas');
                        });
                    }


                    $instrumen->whereHas('unit', function ($query) use ($request) {
                        $query->where('nama', 'ilike', "%{$request->unit}%");
                    });
                } else if (!empty($request->jenjangAuditee) && $request->level && $request->level !== 'all') {
                    $instrumen->whereHas('jenjang', function ($query) use ($request) {
                        $query->whereIn('nama', $request->jenjangAuditee);
                    })->orWhereHas('jabatan', function ($query) use ($request) {
                        $query->whereIn('nama', $request->jenjangAuditee);
                    });

                    if ($request->level === 'prodi') {
                        $instrumen->whereHas('level', function ($query) {
                            $query->where('slug', 'prodi');
                        });
                    } elseif ($request->level === 'fakultas') {
                        $instrumen->whereHas('level', function ($query) {
                            $query->where('slug', 'fakultas');
                        });
                    } elseif ($request->level === 'universitas') {
                        $instrumen->whereHas('level', function ($query) {
                            $query->where('slug', 'universitas');
                        });
                    }
                } else if (!empty($request->jenjangAuditee) && $request->unit && $request->unit !== 'all') {
                    $instrumen->whereHas('jenjang', function ($query) use ($request) {
                        $query->whereIn('nama', $request->jenjangAuditee);
                    })->orWhereHas('jabatan', function ($query) use ($request) {
                        $query->whereIn('nama', $request->jenjangAuditee);
                    });

                    $instrumen->whereHas('unit', function ($query) use ($request) {
                        $query->where('nama', 'ilike', "%{$request->unit}%");
                    });
                } else if ($request->level && $request->level !== 'all' && $request->unit && $request->unit !== 'all') {
                    if ($request->level === 'prodi') {
                        $instrumen->whereHas('level', function ($query) {
                            $query->where('slug', 'prodi');
                        });
                    } elseif ($request->level === 'fakultas') {
                        $instrumen->whereHas('level', function ($query) {
                            $query->where('slug', 'fakultas');
                        });
                    } elseif ($request->level === 'universitas') {
                        $instrumen->whereHas('level', function ($query) {
                            $query->where('slug', 'universitas');
                        });
                    }

                    $instrumen->whereHas('unit', function ($query) use ($request) {
                        $query->where('nama', 'ilike', "%{$request->unit}%");
                    });
                }

                if (!empty($request->jenjangAuditee)) {
                    $instrumen->whereHas('jenjang', function ($query) use ($request) {
                        $query->whereIn('nama', $request->jenjangAuditee);
                    })->orWhereHas('jabatan', function ($query) use ($request) {
                        $query->whereIn('nama', $request->jenjangAuditee);
                    });
                }

                if ($request->level && $request->level !== 'all') {
                    if ($request->level === 'prodi') {
                        $instrumen->whereHas('level', function ($query) {
                            $query->where('slug', 'prodi');
                        });
                    } elseif ($request->level === 'fakultas') {
                        $instrumen->whereHas('level', function ($query) {
                            $query->where('slug', 'fakultas');
                        });
                    } elseif ($request->level === 'universitas') {
                        $instrumen->whereHas('level', function ($query) {
                            $query->where('slug', 'universitas');
                        });
                    }
                }

                if ($request->unit && $request->unit !== 'all') {
                    $instrumen->whereHas('unit', function ($query) use ($request) {
                        $query->where('nama', 'ilike', "%{$request->unit}%");
                    });
                }

                return DataTables::of($instrumen)
                    ->addColumn('jenjang', function ($row) {
                        if ($row->jenjang->isNotEmpty() && $row->prodi->isNotEmpty()) {
                            $jenjang = $row->jenjang->pluck('nama')->map(function ($nama) {
                                return '<span class="badge bg-secondary mb-2">' . $nama . '</span>';
                            })->implode(' ');

                            $prodi = $row->prodi->pluck('nama')->map(function ($nama) {
                                return '<span class="badge bg-info mb-2">' . $nama . '</span>';
                            })->implode(' ');

                            return $jenjang . ' ' . $prodi;
                        } elseif ($row->jenjang->isNotEmpty()) {
                            return $row->jenjang->pluck('nama')->map(function ($nama) {
                                return '<span class="badge bg-secondary mb-2">' . $nama . '</span>';
                            })->implode(' ');
                        } elseif ($row->prodi->isNotEmpty()) {
                            return $row->prodi->pluck('nama')->map(function ($nama) {
                                return '<span class="badge bg-info mb-2">' . $nama . '</span>';
                            })->implode(' ');
                        } elseif ($row->jabatan->isNotEmpty()) {
                            return $row->jabatan->pluck('nama')->map(function ($nama) {
                                return '<span class="badge bg-secondary mb-2">' . $nama . '</span>';
                            })->implode(' ');
                        } else {
                            return '<span class="badge">-</span>';
                        }
                    })
                    ->addColumn('unit', function ($row) {
                        if (in_array($row->level->slug, ['prodi', 'fakultas'])) {
                            return '-';
                        } else {
                            if ($row->unit->isEmpty()) {
                                return '-';
                            }

                            $units = $row->unit->pluck('nama')->map(function ($nama) {
                                return '<span class="badge bg-info mb-2">' . $nama . '</span>';
                            })->implode(' ');

                            return $units ?: '-';
                        }
                    })
                    ->addColumn('level', function ($row) {
                        if ($row->level->slug == 'prodi') {
                            return '<span class="badge" style="background-color: #f012be;color: #fff; ">PS</span>';
                        } elseif ($row->level->slug == 'fakultas') {
                            return '<span class="badge" style="background-color: #39cccc;color: #fff; ">UPPS</span>';
                        } elseif ($row->level->slug == 'universitas') {
                            return '<span class="badge" style="background-color: #ff851b;color: #fff; ">' . $row->level->nama . '</span>';
                        }
                    })
                    ->addColumn('action', function ($row) {
                        $btn = '<div class="dropdown">
                            <button class="btn btn-success dropdown-toggle" type="button" id="dropdownMenuButton' . $row->id . '" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                Actions
                            </button>
                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton' . $row->id . '">
                                <a class="dropdown-item" href="' . route('instrumen.edit', $row->id) . '">Edit</a>
                                <a class="dropdown-item" href="' . route('instrumen.delete', $row->id) . '" data-confirm-delete="true">Hapus</a>
                            </div>
                        </div>';
                        return $btn;
                    })
                    ->rawColumns(['jenjang', 'unit', 'level', 'action'])
                    ->filter(function ($query) use ($request) {
                        if ($request->has('search.value')) {
                            $searchValue = $request->input('search.value');
                            $query->where(function ($query) use ($searchValue) {
                                $query->where('pernyataan', 'ilike', "%{$searchValue}%")
                                    ->orWhere('kode', 'ilike', "%{$searchValue}%")
                                    ->orWhereHas('jenjang', function ($query) use ($searchValue) {
                                        $query->where('nama', 'ilike', "%{$searchValue}%");
                                    })
                                    ->orWhereHas('prodi', function ($query) use ($searchValue) {
                                        $query->where('nama', 'ilike', "%{$searchValue}%");
                                    })
                                    ->orWhereHas('jabatan', function ($query) use ($searchValue) {
                                        $query->where('nama', 'ilike', "%{$searchValue}%");
                                    })
                                    ->orWhereHas('level', function ($query) use ($searchValue) {
                                        $query->where('nama', 'ilike', "%{$searchValue}%");
                                    })
                                    ->orWhereHas('jabatan.unit', function ($query) use ($searchValue) {
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
            'error' => 'Invalid Request.'
        ], 400);
    }

    // Get Pasal By Peraturan
    public function get_pasal_by_peraturan(Request $request, string $peraturan): JsonResponse
    {
        if ($request->ajax()) {
            try {
                $pasalList = Pasal::where('peraturan_id', $peraturan)->get();
                $data = [];

                foreach ($pasalList as $pasal) {
                    $ayatList = Ayat::where('pasal_id', $pasal->id)->get();

                    if ($ayatList->isNotEmpty()) {
                        foreach ($ayatList as $ayat) {
                            $key = $pasal->id . '___' . $ayat->id;
                            $value = 'Pasal ' . $pasal->pasal . ' Ayat ' . $ayat->ayat;
                            $data[$key] = $value;
                        }
                    } else {
                        $key = $pasal->id;
                        $value = 'Pasal ' . $pasal->pasal;
                        $data[$key] = $value;
                    }
                }

                return response()->json($data);
            } catch (\Exception $e) {
                return response()->json([
                    'error' => 'Error'
                ], 500);
            }
        }

        return response()->json([
            'error' => 'Invalid Request.'
        ], 400);
    }

    // Get Ayat By Pasal
    public function get_ayat_by_pasal(Request $request, string $pasal): JsonResponse
    {
        if ($request->ajax()) {
            try {
                $ayat = Ayat::where('pasal_id', $pasal)->pluck('ayat', 'id');

                return response()->json($ayat);
            } catch (\Exception $e) {
                return response()->json([
                    'error' => 'Error'
                ], 500);
            }
        }

        return response()->json([
            'error' => 'Invalid Request.'
        ], 400);
    }

    // Get Standar By Peraturan
    public function get_standar_by_peraturan(Request $request, string $peraturan): JsonResponse
    {
        if ($request->ajax()) {
            try {
                $standar = Standar::where('peraturan_id', $peraturan)->pluck('nama', 'id');

                return response()->json($standar);
            } catch (\Exception $e) {
                return response()->json([
                    'error' => 'Error'
                ], 500);
            }
        }

        return response()->json([
            'error' => 'Invalid Request.'
        ], 400);
    }

    // Get Kategori By Standar
    public function get_kategori_by_standar(Request $request, string $standar): JsonResponse
    {
        if ($request->ajax()) {
            try {
                $kategori = Kategori::where('standar_id', $standar)->pluck('nama', 'id');
                return response()->json($kategori);
            } catch (\Exception $e) {
                return response()->json([
                    'error' => 'Error'
                ], 500);
            }
        }

        return response()->json([
            'error' => 'Invalid Request.'
        ], 400);
    }

    // Get Jabatan By Level
    public function get_jabatan_by_level(Request $request, string $level): JsonResponse
    {
        if ($request->ajax()) {
            try {
                $findLevel = Level::findOrFail($level);
                if ($findLevel->slug === 'fakultas') {
                    $jabatan = Jabatan::where('type', 'fakultas')->pluck('nama', 'id');
                } else if ($findLevel->slug === 'universitas') {
                    $jabatan = Jabatan::where('type', 'universitas')->pluck('nama', 'id');
                } else {
                    $jabatan = Jabatan::pluck('nama', 'id');
                }
                // dd($jabatan);
                return response()->json($jabatan);
            } catch (\Exception $e) {
                return response()->json([
                    'error' => 'Error'
                ], 500);
            }
        }

        return response()->json([
            'error' => 'Invalid Request.'
        ], 400);
    }

    // Get Kode
    public function get_kode(Request $request, string $standar, string $kategori): JsonResponse
    {
        if ($request->ajax()) {
            try {
                $find_standar = Standar::findOrFail($standar);
                $find_kategori = Kategori::findOrFail($kategori);

                $lastInstrumen = Instrumen::where('kategori_id', $kategori)
                    ->orderByRaw("REGEXP_REPLACE(kode, '[^0-9]', '', 'g')::int DESC NULLS FIRST, REGEXP_REPLACE(kode, '[0-9]', '', 'g') ASC")
                    ->first();

                if ($lastInstrumen) {
                    $lastKode = $lastInstrumen->kode;
                    $lastParts = explode('-', $lastKode);
                    $lastNumber = (int) end($lastParts);
                    $nextNumber = $lastNumber;
                } else {
                    $nextNumber = 1;
                }

                $kode = $find_standar->kode . $find_kategori->kode . '-' . $nextNumber;

                return response()->json($kode);
            } catch (\Exception $e) {
                return response()->json([
                    'error' => 'Error'
                ], 500);
            }
        }

        return response()->json([
            'error' => 'Invalid Request.'
        ], 400);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        // Sweet Alert
        $title = 'Hapus Instrumen!';
        $text = "Apakah Anda yakin ingin menghapus instrumen ini? ";
        confirmDelete($title, $text);

        $level = Level::all();
        $units = Unit::all();
        $jenjang = Jenjang::all();
        $auditee = Jabatan::all();
        $jenjangAuditee = $jenjang->concat($auditee);

        $data = [
            'title' => 'Instrumen',
            'level' => $level,
            'units' => $units,
            'jenjangAuditee' => $jenjangAuditee,
        ];

        return view('admin.audit.instrumen.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $data = [
            'title' => 'Tambah Instrumen',
            'peraturan' => Peraturan::all(),
            'level' => Level::all(),
            'jenis_pertanyaan' => JenisPertanyaan::all(),
            'kriteria' => Kriteria::all(),
            'jenjang' => Jenjang::all(),
            'prodi' => Prodi::with(['jenjang'])->get(),
            'units' => Unit::all(),
        ];

        return view('admin.audit.instrumen.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(InstrumenStoreRequest $request): RedirectResponse
    {
        $prodiId = Level::where('slug', 'prodi')->pluck('id')->first();
        $fakultasId = Level::where('slug', 'fakultas')->pluck('id')->first();
        $universitasId = Level::where('slug', 'universitas')->pluck('id')->first();

        $standar = Standar::findOrFail($request->standar);
        $kategori = Kategori::findOrFail($request->kategori);

        if ($request->kode_huruf == null && $request->kode_angka != null) {
            $kode = $standar->kode . $kategori->kode . '-' . ($request->kode_angka);
        } elseif ($request->kode_huruf != null && $request->kode_angka == null) {
            return back()->with('error', 'Kode angka harus dimasukan.');
        } elseif ($request->kode_huruf != null && $request->kode_angka != null) {
            $kode = strtoupper($request->kode_huruf) . '-' . ($request->kode_angka);
        } else {
            $last = Instrumen::where('kategori_id', $request->kategori)
                ->orderByRaw("REGEXP_REPLACE(kode, '[^0-9]', '', 'g')::int DESC NULLS FIRST, REGEXP_REPLACE(kode, '[0-9]', '', 'g') ASC")
                ->first();

            if ($last) {
                $lastKode = $last->kode;
                $lastParts = explode('-', $lastKode);
                $lastNumber = (int) end($lastParts);
                $nextNumber = $lastNumber;
            } else {
                $nextNumber = 1;
            }
            $kode = $standar->kode . $kategori->kode . '-' . ($nextNumber + 1);
        }

        $instrumen = new Instrumen();
        $instrumen->fill([
            'pernyataan' => $request->pernyataan,
            'indikator' => $request->indikator,
            'peraturan_id' => $request->peraturan,
            'standar_id' => $request->standar,
            'kategori_id' => $request->kategori,
            'level_id' => $request->level,
            'jenis_pertanyaan_id' => $request->jenis_pertanyaan,
        ]);

        $instrumen->kode = $kode;
        $instrumen->save();

        if (!empty($request->pasalAyat)) {
            if ($request->pasalAyat) {
                foreach ($request->pasalAyat as $pasalAyat) {
                    list($pasalid, $ayatid) = explode('___', $pasalAyat);
                    $instrumen->pasal()->attach($pasalid, ['ayat_id' => $ayatid]);
                }
            }
        }

        if ($request->level == $prodiId) {
            if (!empty($request->jenjang)) {
                $instrumen->jenjang()->sync($request->jenjang);
            }
            if (!empty($request->prodi)) {
                $instrumen->prodi()->sync($request->prodi);
            }
        }

        foreach ($request->kriteria as $nama => $isi) {
            $instrumen->kriteria()->attach([$request->kriteria_id[$nama]], ['isi' => $isi]);
        }

        if ($request->level == $fakultasId) {
            if (!empty($request->jabatan)) {
                $instrumen->jabatan()->sync($request->jabatan);
            }
        }

        if ($request->level == $universitasId) {
            if (!empty($request->jabatan)) {
                $instrumen->jabatan()->sync($request->jabatan);
            }
            if (!empty($request->unit)) {
                $instrumen->unit()->sync($request->unit);
            }
        }

        return redirect()->route('instrumen')->with('success', 'Instrumen berhasil ditambahkan.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Instrumen $instrumen): View
    {
        $allKriteria = Kriteria::get();

        $combinedKriteria = $allKriteria->map(function ($item) use ($instrumen) {
            $instrumenKriteria = $instrumen->kriteria->firstWhere('id', $item->id);
            $item->pivot = $instrumenKriteria ? $instrumenKriteria->pivot : (object) ['isi' => ''];
            return $item;
        });

        $data = [
            'title' => 'Edit Instrumen',
            'instrumen' => $instrumen,
            'peraturan' => Peraturan::all(),
            'level' => Level::all(),
            'jenis_pertanyaan' => JenisPertanyaan::all(),
            'kriteria' => $combinedKriteria,
            'jenjang' => Jenjang::all(),
            'prodi' => Prodi::all(),
            'jabatan' => Jabatan::all(),
            'units' => Unit::all(),
        ];

        return view('admin.audit.instrumen.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(InstrumenUpdateRequest $request, Instrumen $instrumen): RedirectResponse
    {
        $prodiId = Level::where('slug', 'prodi')->pluck('id')->first();
        $fakultasId = Level::where('slug', 'fakultas')->pluck('id')->first();
        $universitasId = Level::where('slug', 'universitas')->pluck('id')->first();

        $instrumen->update([
            'pernyataan' => $request->pernyataan,
            'indikator' => $request->indikator,
            'peraturan_id' => $request->peraturan,
            'standar_id' => $request->standar,
            'kategori_id' => $request->kategori,
            'level_id' => $request->level,
            'jenis_pertanyaan_id' => $request->jenis_pertanyaan,
        ]);

        $instrumen->pasal()->sync([]);
        if (!empty($request->pasalAyat)) {
            $pasalAyatData = [];
            foreach ($request->pasalAyat as $pasalAyat) {
                list($pasalid, $ayatid) = explode('___', $pasalAyat);
                $pasalAyatData[$pasalid] = ['ayat_id' => $ayatid];
            }
            $instrumen->pasal()->attach($pasalAyatData);
        }

        $instrumen->jenjang()->sync([]);

        $instrumen->prodi()->sync([]);

        if ($request->level == $prodiId) {
            if (!empty($request->jenjang)) {
                $instrumen->jenjang()->sync($request->jenjang);
            }
            if (!empty($request->prodi)) {
                $instrumen->prodi()->sync($request->prodi);
            }
        }

        $kriteriaData = [];
        foreach ($request->kriteria as $nama => $isi) {
            $kriteriaData[$request->kriteria_id[$nama]] = ['isi' => $isi];
        }
        $instrumen->kriteria()->sync($kriteriaData);

        $instrumen->jabatan()->sync([]);
        $instrumen->unit()->sync([]);
        if ($request->level == $fakultasId) {
            if (!empty($request->jabatan)) {
                $instrumen->jabatan()->sync($request->jabatan);
            }
        }

        if ($request->level == $universitasId) {
            if (!empty($request->jabatan)) {
                $instrumen->jabatan()->sync($request->jabatan);
            }
            if (!empty($request->unit)) {
                $instrumen->unit()->sync($request->unit);
            }
        }

        return redirect()->route('instrumen')->with('success', 'Instrumen berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Instrumen $instrumen): RedirectResponse
    {
        $instrumen->delete();

        return redirect()->route('instrumen')->with('success', 'Instrumen berhasil dihapus.');
    }
}
