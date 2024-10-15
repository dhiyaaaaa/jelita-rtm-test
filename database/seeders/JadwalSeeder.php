<?php

namespace Database\Seeders;

use App\Models\Auditee;
use App\Models\Fakultas;
use App\Models\Form;
use App\Models\Instrumen;
use App\Models\Jabatan;
use App\Models\JadwalAudit;
use App\Models\Prodi;
use App\Models\Unit;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class JadwalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Jabatan
        $kaprodi = Jabatan::where('slug', 'kaprodi')->pluck('id')->first();
        $jabatanFak = Jabatan::whereIn('slug', ['dekan', 'wd-i', 'wd-ii', 'wd-iii'])->pluck('id')->toArray();
        $jabatanUniversitas = Jabatan::where('type', 'universitas')->pluck('id')->toArray();

        // Jadwal 
        $jadwal1 = JadwalAudit::create([
            'jadwal' => "Audit 1",
            "tgl_mulai" => "2024-08-01",
            "tgl_selesai" => "2024-10-31",
        ]);

        $instrumenAll = Instrumen::all();
        foreach ($instrumenAll as $instrumen) {
            Form::create([
                'jadwal_id' => $jadwal1->id,
                'instrumen_id' => $instrumen->id,
            ]);
        }

        // Tambah Auditee
        $userKaprodi = Prodi::whereHas('user.jabatan', function ($query) use ($kaprodi) {
            $query->where('jabatan_id', $kaprodi);
        })->with(['user.jabatan'])->get();


        foreach ($userKaprodi as $prodi) {
            Auditee::create([
                'user_id' => $prodi->user->first()->id,
                'jabatan_id' => $prodi->user->first()->jabatan->first()->id,
                'prodi_id' => $prodi->id,
                'jadwal_audit_id' => $jadwal1->id,
            ]);
        }

        // Auditee Fakultas (Dekan WD)
        $userFakultas = Fakultas::whereHas('user.jabatan', function ($query) use($jabatanFak){
            $query->whereIn('jabatan_id', $jabatanFak);
        })->with(['user.jabatan'])->get();

        foreach ($userFakultas as $fakultas) {
            foreach ($fakultas->user as $user) {
                foreach ($user->jabatan as $jabatan) {
                    if (in_array($jabatan->id, $jabatanFak)) {
                        Auditee::create([
                            'user_id' => $user->id,
                            'jabatan_id' => $jabatan->id,
                            'fakultas_id' => $fakultas->id,
                            'jadwal_audit_id' => $jadwal1->id,
                        ]);
                    }
                }
            }
        }

        // Auditee Unit 
        
        $userUnit = Unit::whereHas('user.jabatan', function ($query) use ($jabatanUniversitas) {
            $query->whereIn('jabatan_id', $jabatanUniversitas);
        })->with(['user.jabatan'])->get();

        foreach ($userUnit as $unit) {
            foreach ($unit->user as $user) {
                foreach ($user->jabatan as $jabatan) {
                    if (in_array($jabatan->id, $jabatanUniversitas)) {
                        Auditee::create([
                            'user_id' => $user->id,
                            'jabatan_id' => $jabatan->id,
                            'unit_id' => $unit->id,
                            'jadwal_audit_id' => $jadwal1->id,
                        ]);
                    }
                }
            }
        }

        // Jadwal 2
        $jadwal2 = JadwalAudit::create([
            'jadwal' => "Audit 2",
            "tgl_mulai" => "2024-08-01",
            "tgl_selesai" => "2024-10-31",
        ]);

        foreach ($instrumenAll as $instrumen) {
            Form::create([
                'jadwal_id' => $jadwal2->id,
                'instrumen_id' => $instrumen->id,
            ]);
        }

        // Tambah Auditee
        $userKaprodi = Prodi::whereHas('user.jabatan', function ($query) use ($kaprodi) {
            $query->where('jabatan_id', $kaprodi);
        })->with(['user.jabatan'])->get();


        foreach ($userKaprodi as $prodi) {
            Auditee::create([
                'user_id' => $prodi->user->first()->id,
                'jabatan_id' => $prodi->user->first()->jabatan->first()->id,
                'prodi_id' => $prodi->id,
                'jadwal_audit_id' => $jadwal2->id,
            ]);
        }

        // Auditee Fakultas (Dekan WD)
        $userFakultas = Fakultas::whereHas('user.jabatan', function ($query) use($jabatanFak) {
            $query->whereIn('jabatan_id', $jabatanFak);
        })->with(['user.jabatan'])->get();

        foreach ($userFakultas as $fakultas) {
            foreach ($fakultas->user as $user) {
                foreach ($user->jabatan as $jabatan) {
                    if (in_array($jabatan->id, $jabatanFak)) {
                        Auditee::create([
                            'user_id' => $user->id,
                            'jabatan_id' => $jabatan->id,
                            'fakultas_id' => $fakultas->id,
                            'jadwal_audit_id' => $jadwal2->id,
                        ]);
                    }
                }
            }
        }

        // Auditee Unit 
        $userUnit = Unit::whereHas('user.jabatan', function ($query) use ($jabatanUniversitas) {
            $query->whereIn('jabatan_id', $jabatanUniversitas);
        })->with(['user.jabatan'])->get();

        foreach ($userUnit as $unit) {
            foreach ($unit->user as $user) {
                foreach ($user->jabatan as $jabatan) {
                    if (in_array($jabatan->id, $jabatanUniversitas)) {
                        Auditee::create([
                            'user_id' => $user->id,
                            'jabatan_id' => $jabatan->id,
                            'unit_id' => $unit->id,
                            'jadwal_audit_id' => $jadwal2->id,
                        ]);
                    }
                }
            }
        }
    }
}
