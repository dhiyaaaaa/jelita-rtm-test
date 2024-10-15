<?php

namespace Database\Seeders;

use App\Models\Ayat;
use App\Models\JenisPertanyaan;
use App\Models\Kategori;
use App\Models\Kriteria;
use App\Models\Level;
use App\Models\Pasal;
use App\Models\Peraturan;
use App\Models\Standar;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PeraturanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $peraturan = Peraturan::create([
            'nama' => 'Permenristekdikti No 53 Tahun 2023 tentang Sistem Penjaminan Mutu Pendidikan Tinggi',
            'tahun' => 2023,
            'status' => 1
        ]);

        // Create Pasals and Ayats
        $pasal1 = Pasal::create([
            'pasal' => 1,
            'isi' => 'Dalam Peraturan Menteri ini yang dimaksud dengan:
1. Penjaminan Mutu Pendidikan Tinggi adalah kegiatan sistemik untuk meningkatkan mutu pendidikan tinggi secara berencana dan berkelanjutan.
2. Standar Nasional Pendidikan Tinggi yang selanjutnya disebut SN Dikti adalah satuan standar yang meliputi standar nasional pendidikan ditambah dengan standar penelitian dan standar pengabdian kepada masyarakat.
3. Tridharma Perguruan Tinggi yang selanjutnya disebut Tridharma adalah kewajiban perguruan tinggi untuk menyelenggarakan pendidikan, penelitian, dan pengabdian kepada masyarakat.',
            'peraturan_id' => $peraturan->id
        ]);

        Ayat::create([
            'ayat' => 1,
            'isi' => 'Penjaminan Mutu Pendidikan Tinggi adalah kegiatan sistemik untuk meningkatkan mutu pendidikan tinggi secara berencana dan berkelanjutan.',
            'peraturan_id' => $peraturan->id,
            'pasal_id' => $pasal1->id,
        ]);

        Ayat::create([
            'ayat' => 2,
            'isi' => 'Standar Nasional Pendidikan Tinggi yang selanjutnya disebut SN Dikti adalah satuan standar yang meliputi standar nasional pendidikan ditambah dengan standar penelitian dan standar pengabdian kepada masyarakat.',
            'peraturan_id' => $peraturan->id,
            'pasal_id' => $pasal1->id,
        ]);

        Ayat::create([
            'ayat' => 3,
            'isi' => 'Tridharma Perguruan Tinggi yang selanjutnya disebut Tridharma adalah kewajiban perguruan tinggi untuk menyelenggarakan pendidikan, penelitian, dan pengabdian kepada masyarakat.',
            'peraturan_id' => $peraturan->id,
            'pasal_id' => $pasal1->id,
        ]);

        $pasal2 = Pasal::create([
            'pasal' => 2,
            'isi' => 'Penjaminan Mutu Pendidikan Tinggi dilakukan melalui penetapan, pelaksanaan, evaluasi, pengendalian, dan peningkatan standar pendidikan tinggi.',
            'peraturan_id' => $peraturan->id
        ]);

        Ayat::create([
            'ayat' => 1,
            'isi' => 'Penjaminan Mutu Pendidikan Tinggi dilakukan melalui penetapan, pelaksanaan, evaluasi, pengendalian, dan peningkatan standar pendidikan tinggi.',
            'peraturan_id' => $peraturan->id,
            'pasal_id' => $pasal2->id,
        ]);

        $pasal3 = Pasal::create([
            'pasal' => 3,
            'isi' => 'Setiap perguruan tinggi wajib melaksanakan penjaminan mutu pendidikan tinggi.',
            'peraturan_id' => $peraturan->id
        ]);

        Ayat::create([
            'ayat' => 1,
            'isi' => 'Setiap perguruan tinggi wajib melaksanakan penjaminan mutu pendidikan tinggi.',
            'peraturan_id' => $peraturan->id,
            'pasal_id' => $pasal3->id,
        ]);

        $pasal4 = Pasal::create([
            'pasal' => 4,
            'isi' => 'Penjaminan Mutu Pendidikan Tinggi terdiri atas Penjaminan Mutu Internal dan Penjaminan Mutu Eksternal.',
            'peraturan_id' => $peraturan->id
        ]);

        Ayat::create([
            'ayat' => 1,
            'isi' => 'Penjaminan Mutu Pendidikan Tinggi terdiri atas Penjaminan Mutu Internal dan Penjaminan Mutu Eksternal.',
            'peraturan_id' => $peraturan->id,
            'pasal_id' => $pasal4->id,
        ]);

        Ayat::create([
            'ayat' => 2,
            'isi' => 'Penjaminan Mutu Internal adalah penjaminan mutu yang dilaksanakan oleh perguruan tinggi sendiri.',
            'peraturan_id' => $peraturan->id,
            'pasal_id' => $pasal4->id,
        ]);

        Ayat::create([
            'ayat' => 3,
            'isi' => 'Penjaminan Mutu Eksternal adalah penjaminan mutu yang dilaksanakan oleh lembaga di luar perguruan tinggi.',
            'peraturan_id' => $peraturan->id,
            'pasal_id' => $pasal4->id,
        ]);

        // Standar
        $standarList = [
            'Standar Nasional Pendidikan' => 'PD',
            'Standar Penelitian' => 'PN',
            'Pengabdian Kepada Masyarakat' => 'PM',
            'Non-Akademik' => 'NA'
        ];

        $kategoriList = [
            'Standar Nasional Pendidikan' => ['Luaran' => 'L', 'Proses' => 'P', 'Masukan' => 'M'],
            'Standar Penelitian' => ['Luaran' => 'L', 'Proses' => 'P', 'Masukan' => 'M'],
            'Pengabdian Kepada Masyarakat' => ['Luaran' => 'L', 'Proses' => 'P', 'Masukan' => 'M'],
            'Non-Akademik' => [
                'Kerjasama' => 'K',
                'Layanan' => 'L',
                'Tatakelola' => 'T',
                'Kemahasiswaan' => 'M',
                'Keuangan' => 'U',
                'Sarana dan Prasarana' => 'S'
            ]
        ];

        foreach ($standarList as $standarNama => $kode) {
            $standar = Standar::create([
                'nama' => $standarNama,
                'peraturan_id' => $peraturan->id,
                'kode' => $kode
            ]);

            foreach ($kategoriList[$standarNama] as $kategoriNama => $kategoriKode) {
                Kategori::create([
                    'nama' => $kategoriNama,
                    'standar_id' => $standar->id,
                    'kode' => $kategoriKode
                ]);
            }
        }


        // Jenis Indikator PS dan UPPS
        $ps = Level::create([
            'nama' => 'Prodi Program Studi',
            'slug' => 'prodi',
        ]);
        $upps = Level::create([
            'nama' => 'Fakultas Unit Pengelola Program Studi',
            'slug' => 'fakultas',
        ]);
        $univ = Level::create([
            'nama' => 'Universitas',
            'slug' => 'universitas',
        ]);

        // Kriteria
        $kriteriaList = ['Belum Memenuhi', 'Memenuhi', 'Melampaui'];
        foreach ($kriteriaList as $item) {
            Kriteria::create([
                'nama' => $item,
            ]);
        }

        // Jenis Pertanyaan
        $jenisPertanyaan = ['text', 'number'];
        foreach ($jenisPertanyaan as $item) {
            JenisPertanyaan::create([
                'nama' => $item,
            ]);
        }
    }
}
