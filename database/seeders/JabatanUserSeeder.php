<?php

namespace Database\Seeders;

use App\Models\Fakultas;
use App\Models\Jabatan;
use App\Models\Prodi;
use App\Models\Unit;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class JabatanUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Kaprodi
        $role = 'pj_prodi';
        $kaprodiJabatan = Jabatan::where('slug', 'kaprodi')->first();

        $prodis = Prodi::with('jenjang')->get();
        $emailCounters = [];

        foreach ($prodis as $prodi) {
            $nama = $prodi->nama . ' ' . $prodi->jenjang->nama;
            $prodiNameKey = strtolower(str_replace(' ', '', $nama));
            if (!isset($emailCounters[$prodiNameKey])) {
                $emailCounters[$prodiNameKey] = 1;
            } else {
                $emailCounters[$prodiNameKey]++;
            }

            $emailSuffix = $emailCounters[$prodiNameKey] > 1 ? $emailCounters[$prodiNameKey] : '';
            $email = 'pjprodi' . $prodiNameKey . $emailSuffix . '@example.com';

            $user = User::create([
                'name' => 'Kaprodi ' . $prodi->nama,
                'email' => $email,
                'password' => Hash::make('123123'),
            ]);

            $user->assignRole($role);

            $user->jabatan()->attach($kaprodiJabatan->id, [
                'prodi_id' => $prodi->id,
            ]);
        }


        // Dekan
        // WD I, WD II, WD III

        $roleFak = 'pj_fakultas';

        $rolesWakilDekans = [
            'dekan',
            'wd-i',
            'wd-ii',
            'wd-iii',
        ];

        $fakultas = Fakultas::all();

        foreach ($rolesWakilDekans as $wd) {
            $jabatan = Jabatan::where('slug', $wd)->first();
            foreach ($fakultas as $fak) {
                $user = User::create([
                    'name' => strtoupper($jabatan->nama) . ' ' . $fak->nama,
                    'email' => strtolower(str_replace(' ', '', $jabatan->nama)) . strtolower(str_replace(' ', '', $fak->nama)) . '@example.com',
                    'password' => Hash::make('123123'),
                ]);

                $user->assignRole($roleFak);

                $user->jabatan()->attach($jabatan->id, [
                    'fakultas_id' => $fak->id,
                ]);
            }
        }

        $roleUnit = 'pj_universitas';

        $rolesUnits = [
            'Ketua LPPM' => 'LPPM',
            'Ketua LP3M' => 'LP3M',
            'WR I' => 'WR',
            'WR II' => 'WR',
            'WR III' => 'WR',
            'WR IV' => 'WR',
            'Ketua LPTSI' => 'LPTSI',
            'Ketua ULT' => 'ULT',
            'Kepala Biro Akademik' => 'Biro Akademik',
            'Kepala Biro Keuangan' => 'Biro Keuangan',
        ];

        foreach ($rolesUnits as $role => $unit) {
            $jabatan = Jabatan::where('nama', $role)->first();
            $unit = Unit::where('nama', $unit)->first();

            $user = User::create([
                'name' => $role,
                'email' => strtolower(str_replace(' ', '', $role)) . '@example.com',
                'password' => Hash::make('123123'),
            ]);

            $user->assignRole($roleUnit);

            $user->jabatan()->attach($jabatan->id, [
                'unit_id' => $unit->id,
            ]);
        }
    }
}
