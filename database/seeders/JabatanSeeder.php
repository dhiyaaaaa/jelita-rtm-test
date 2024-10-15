<?php

namespace Database\Seeders;

use App\Models\Jabatan;
use App\Models\Setting;
use App\Models\Unit;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class JabatanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Data unit yang harus ada
        $unitNames = [
            'LPPM',
            'LP3M',
            'WR',
            'Biro Akademik',
            'Biro Keuangan',
            'LPTSI',
            'ULT',
        ];

        // Pastikan unit sudah ada atau buat unit jika tidak ada
        foreach ($unitNames as $name) {
            Unit::firstOrCreate(['nama' => $name]);
        }

        // Data jabatan untuk tipe fakultas dan prodi
        $jabatans = [
            ['nama' => 'Rektor', 'type' => 'universitas', 'unik' => true],
            ['nama' => 'Dekan', 'type' => 'fakultas', 'unik' => true],
            ['nama' => 'WD I', 'type' => 'fakultas', 'unik' => true],
            ['nama' => 'WD II', 'type' => 'fakultas', 'unik' => true],
            ['nama' => 'WD III', 'type' => 'fakultas', 'unik' => true],
            ['nama' => 'Kaprodi', 'type' => 'prodi', 'unik' => true],
            ['nama' => 'Dosen', 'type' => 'prodi', 'unik' => false],
        ];

        // Data jabatan dengan tipe universitas yang memerlukan unit
        $jabatanUniversitas = [
            ['nama' => 'WR I', 'type' => 'universitas', 'unik' => true, 'unit' => 'WR'],
            ['nama' => 'WR II', 'type' => 'universitas', 'unik' => true, 'unit' => 'WR'],
            ['nama' => 'WR III', 'type' => 'universitas', 'unik' => true, 'unit' => 'WR'],
            ['nama' => 'WR IV', 'type' => 'universitas', 'unik' => true, 'unit' => 'WR'],
            ['nama' => 'Ketua LPPM', 'type' => 'universitas', 'unik' => true, 'unit' => 'LPPM'],
            ['nama' => 'Ketua LPTSI', 'type' => 'universitas', 'unik' => true, 'unit' => 'LPTSI'],
            ['nama' => 'Ketua LP3M', 'type' => 'universitas', 'unik' => true, 'unit' => 'LP3M'],
            ['nama' => 'Ketua ULT', 'type' => 'universitas', 'unik' => true, 'unit' => 'ULT'],
            ['nama' => 'Kepala Biro Akademik', 'type' => 'universitas', 'unik' => true, 'unit' => 'Biro Akademik'],
            ['nama' => 'Kepala Biro Keuangan', 'type' => 'universitas', 'unik' => true, 'unit' => 'Biro Keuangan'],
        ];

        // Insert jabatan untuk fakultas dan prodi
        foreach ($jabatans as $jabatan) {
            $jab = Jabatan::create([
                'nama' => $jabatan['nama'],
                'type' => $jabatan['type'],
                'unik' => $jabatan['unik'],
            ]);

            Setting::create([
                'nama_setting' => $jab->type,
                'jabatan_id' => $jab->id,
            ]);
        }

        foreach ($jabatanUniversitas as $item) {
            $jabatan = Jabatan::create([
                'nama' => $item['nama'],
                'type' => $item['type'],
                'unik' => $item['unik'],
            ]);

            Setting::create([
                'nama_setting' => $jabatan->type,
                'jabatan_id' => $jabatan->id,
            ]);

            $unit = Unit::where('nama', $item['unit'])->first();

            if ($unit) {
                $jabatan->unit()->attach($unit->id);
            } else {
                echo "Unit {$item['unit']} tidak ditemukan untuk jabatan {$item['nama']}.";
            }
        }
    }
}
