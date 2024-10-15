<?php

namespace Database\Seeders;

use App\Models\Fakultas;
use App\Models\Jenjang;
use App\Models\Prodi;
use App\Models\Unit;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProdiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $jenjang = [
            'D3' => Jenjang::firstOrCreate(['nama' => 'D3']),
            'S1' => Jenjang::firstOrCreate(['nama' => 'S1']),
            'S2' => Jenjang::firstOrCreate(['nama' => 'S2']),
            'S3' => Jenjang::firstOrCreate(['nama' => 'S3']),
            'Profesi' => Jenjang::firstOrCreate(['nama' => 'Profesi']),
        ];
        

        $unit = [
            'LP3M' => Unit::firstOrCreate(['nama' => 'LP3M']),
            'LPPM' => Unit::firstOrCreate(['nama' => 'LPPM']),
            'WR' => Unit::firstOrCreate(['nama' => 'WR']),
            'Biro Akademik' => Unit::firstOrCreate(['nama' => 'Biro Akademik']),
            'Biro Keuangan' => Unit::firstOrCreate(['nama' => 'Biro Keuangan']),
            'LPTSI' => Unit::firstOrCreate(['nama' => 'LPTSI']),
            'ULT' => Unit::firstOrCreate(['nama' => 'ULT']),
        ];        

        $fakultas = [
            'Pertanian' => Fakultas::firstOrCreate(['nama' => 'Pertanian']),
            'Biologi' => Fakultas::firstOrCreate(['nama' => 'Biologi']),
            'FEB' => Fakultas::firstOrCreate(['nama' => 'Ekonomi dan Bisnis']),
            'Peternakan' => Fakultas::firstOrCreate(['nama' => 'Peternakan']),
            'Hukum' => Fakultas::firstOrCreate(['nama' => 'Hukum']),
            'FISIP' => Fakultas::firstOrCreate(['nama' => 'Ilmu Sosial dan Ilmu Politik']),
            'Kedokteran' => Fakultas::firstOrCreate(['nama' => 'Kedokteran']),
            'Teknik' => Fakultas::firstOrCreate(['nama' => 'Teknik']),
            'FIKES' => Fakultas::firstOrCreate(['nama' => 'Ilmu-Ilmu Kesehatan']),
            'FIB' => Fakultas::firstOrCreate(['nama' => 'Ilmu Budaya']),
            'MIPA' => Fakultas::firstOrCreate(['nama' => 'Matematika dan IPA']),
            'FPIK' => Fakultas::firstOrCreate(['nama' => 'Ilmu Perikanan dan Kelautan']),
        ];        

        $prodi = [
            ['jenjang' => $jenjang['S1'], 'fakultas' => $fakultas['Pertanian'], 'nama' => 'Agribisnis'],
            ['jenjang' => $jenjang['S1'], 'fakultas' => $fakultas['Pertanian'], 'nama' => 'Teknologi Pangan'],
            ['jenjang' => $jenjang['S1'], 'fakultas' => $fakultas['Pertanian'], 'nama' => 'Teknik Pertanian'],
            ['jenjang' => $jenjang['S1'], 'fakultas' => $fakultas['Pertanian'], 'nama' => 'Agroteknologi'],
            ['jenjang' => $jenjang['S1'], 'fakultas' => $fakultas['Pertanian'], 'nama' => 'Proteksi Tanaman'],
            ['jenjang' => $jenjang['S1'], 'fakultas' => $fakultas['Biologi'], 'nama' => 'Biologi'],
            ['jenjang' => $jenjang['S1'], 'fakultas' => $fakultas['Biologi'], 'nama' => 'Mikrobiologi'],
            ['jenjang' => $jenjang['S1'], 'fakultas' => $fakultas['Biologi'], 'nama' => 'Biologi Terapan'],
            ['jenjang' => $jenjang['S1'], 'fakultas' => $fakultas['FEB'], 'nama' => 'Ekonomi Pembangunan'],
            ['jenjang' => $jenjang['S1'], 'fakultas' => $fakultas['FEB'], 'nama' => 'Manajemen'],
            ['jenjang' => $jenjang['S1'], 'fakultas' => $fakultas['FEB'], 'nama' => 'Akuntansi'],
            ['jenjang' => $jenjang['S1'], 'fakultas' => $fakultas['FEB'], 'nama' => 'Pendidikan Ekonomi'],
            ['jenjang' => $jenjang['S1'], 'fakultas' => $fakultas['Peternakan'], 'nama' => 'Peternakan'],
            ['jenjang' => $jenjang['S1'], 'fakultas' => $fakultas['Hukum'], 'nama' => 'Hukum'],
            ['jenjang' => $jenjang['S1'], 'fakultas' => $fakultas['FISIP'], 'nama' => 'Administrasi Publik'],
            ['jenjang' => $jenjang['S1'], 'fakultas' => $fakultas['FISIP'], 'nama' => 'Ilmu Komunikasi'],
            ['jenjang' => $jenjang['S1'], 'fakultas' => $fakultas['FISIP'], 'nama' => 'Ilmu Politik'],
            ['jenjang' => $jenjang['S1'], 'fakultas' => $fakultas['FISIP'], 'nama' => 'Hubungan Internasional'],
            ['jenjang' => $jenjang['S1'], 'fakultas' => $fakultas['Kedokteran'], 'nama' => 'Pendidikan Dokter'],
            ['jenjang' => $jenjang['S1'], 'fakultas' => $fakultas['Kedokteran'], 'nama' => 'Kedokteran Gigi'],
            ['jenjang' => $jenjang['S1'], 'fakultas' => $fakultas['Teknik'], 'nama' => 'Teknik Elektro'],
            ['jenjang' => $jenjang['S1'], 'fakultas' => $fakultas['Teknik'], 'nama' => 'Teknik Sipil'],
            ['jenjang' => $jenjang['S1'], 'fakultas' => $fakultas['Teknik'], 'nama' => 'Teknik Geologi'],
            ['jenjang' => $jenjang['S1'], 'fakultas' => $fakultas['Teknik'], 'nama' => 'Informatika'],
            ['jenjang' => $jenjang['S1'], 'fakultas' => $fakultas['Teknik'], 'nama' => 'Teknik Industri'],
            ['jenjang' => $jenjang['S1'], 'fakultas' => $fakultas['Teknik'], 'nama' => 'Teknik Mesin'],
            ['jenjang' => $jenjang['S1'], 'fakultas' => $fakultas['FIKES'], 'nama' => 'Kesehatan Masyarakat'],
            ['jenjang' => $jenjang['S1'], 'fakultas' => $fakultas['FIKES'], 'nama' => 'Keperawatan'],
            ['jenjang' => $jenjang['S1'], 'fakultas' => $fakultas['FIKES'], 'nama' => 'Farmasi'],
            ['jenjang' => $jenjang['S1'], 'fakultas' => $fakultas['FIKES'], 'nama' => 'Ilmu Gizi'],
            ['jenjang' => $jenjang['S1'], 'fakultas' => $fakultas['FIKES'], 'nama' => 'Pendidikan Jasmani'],
            ['jenjang' => $jenjang['S1'], 'fakultas' => $fakultas['FIB'], 'nama' => 'Sastra Inggris'],
            ['jenjang' => $jenjang['S1'], 'fakultas' => $fakultas['FIB'], 'nama' => 'Sastra Indonesia'],
            ['jenjang' => $jenjang['S1'], 'fakultas' => $fakultas['FIB'], 'nama' => 'Pendidikan Bahasa Indonesia'],
            ['jenjang' => $jenjang['S1'], 'fakultas' => $fakultas['FIB'], 'nama' => 'Pendidikan Bahasa Inggris'],
            ['jenjang' => $jenjang['S1'], 'fakultas' => $fakultas['FIB'], 'nama' => 'Sastra Jepang'],
            ['jenjang' => $jenjang['S1'], 'fakultas' => $fakultas['FIB'], 'nama' => 'Pendidikan Bahasa Jepang'],
            ['jenjang' => $jenjang['S1'], 'fakultas' => $fakultas['MIPA'], 'nama' => 'Kimia'],
            ['jenjang' => $jenjang['S1'], 'fakultas' => $fakultas['MIPA'], 'nama' => 'Matematika'],
            ['jenjang' => $jenjang['S1'], 'fakultas' => $fakultas['MIPA'], 'nama' => 'Fisika'],
            ['jenjang' => $jenjang['S1'], 'fakultas' => $fakultas['MIPA'], 'nama' => 'Statistika'],
            ['jenjang' => $jenjang['S1'], 'fakultas' => $fakultas['FPIK'], 'nama' => 'Manajemen Sumberdaya Perairan'],
            ['jenjang' => $jenjang['S1'], 'fakultas' => $fakultas['FPIK'], 'nama' => 'Akuakultur'],
            ['jenjang' => $jenjang['S1'], 'fakultas' => $fakultas['FPIK'], 'nama' => 'Ilmu Kelautan'],
            ['jenjang' => $jenjang['S1'], 'fakultas' => $fakultas['Biologi'], 'nama' => 'Biologi Internasional'],
            ['jenjang' => $jenjang['S1'], 'fakultas' => $fakultas['FIKES'], 'nama' => 'Keperawatan Internasional'],
            ['jenjang' => $jenjang['S1'], 'fakultas' => $fakultas['FEB'], 'nama' => 'Manajemen Internasional'],
            ['jenjang' => $jenjang['S1'], 'fakultas' => $fakultas['FEB'], 'nama' => 'Ekonomi Pembangunan Internasional'],
            ['jenjang' => $jenjang['S1'], 'fakultas' => $fakultas['FEB'], 'nama' => 'Akuntansi Internasional'],
            ['jenjang' => $jenjang['S1'], 'fakultas' => $fakultas['Hukum'], 'nama' => 'Hukum Internasional'],
            ['jenjang' => $jenjang['S1'], 'fakultas' => $fakultas['FISIP'], 'nama' => 'Hubungan Internasional Kelas Internasional'],
            ['jenjang' => $jenjang['D3'], 'fakultas' => $fakultas['Pertanian'], 'nama' => 'Agribisnis'],
            ['jenjang' => $jenjang['D3'], 'fakultas' => $fakultas['Pertanian'], 'nama' => 'Perencanaan Sumber Daya Lahan'],
            ['jenjang' => $jenjang['D3'], 'fakultas' => $fakultas['Biologi'], 'nama' => 'Budidaya Ikan'],
            ['jenjang' => $jenjang['D3'], 'fakultas' => $fakultas['FEB'], 'nama' => 'Administrasi Bisnis'],
            ['jenjang' => $jenjang['D3'], 'fakultas' => $fakultas['FEB'], 'nama' => 'Administrasi Perkantoran'],
            ['jenjang' => $jenjang['D3'], 'fakultas' => $fakultas['FEB'], 'nama' => 'Akuntansi'],
            ['jenjang' => $jenjang['D3'], 'fakultas' => $fakultas['FEB'], 'nama' => 'Bisnis Internasional'],
            ['jenjang' => $jenjang['D3'], 'fakultas' => $fakultas['Peternakan'], 'nama' => 'Budidaya Ternak'],
            ['jenjang' => $jenjang['D3'], 'fakultas' => $fakultas['FIB'], 'nama' => 'Bahasa Inggris'],
            ['jenjang' => $jenjang['D3'], 'fakultas' => $fakultas['FIB'], 'nama' => 'Bahasa Mandarin'],
            ['jenjang' => $jenjang['Profesi'], 'fakultas' => $fakultas['Kedokteran'], 'nama' => 'Anestesiologi dan Terapi Intensif'], 
            ['jenjang' => $jenjang['Profesi'], 'fakultas' => $fakultas['FEB'], 'nama' => 'Pendidikan Profesi Akuntan'], 
            ['jenjang' => $jenjang['Profesi'], 'fakultas' => $fakultas['Kedokteran'], 'nama' => 'Pendidikan Profesi Dokter'], 
            ['jenjang' => $jenjang['Profesi'], 'fakultas' => $fakultas['Kedokteran'], 'nama' => 'Pendidikan Profesi Dokter Gigi'], 
            ['jenjang' => $jenjang['Profesi'], 'fakultas' => $fakultas['FIKES'], 'nama' => 'Pendidikan Profesi Ners'], 
            ['jenjang' => $jenjang['Profesi'], 'fakultas' => $fakultas['FIKES'], 'nama' => 'Pendidikan Profesi Apoteker'], 
            ['jenjang' => $jenjang['S3'], 'fakultas' => $fakultas['Pertanian'], 'nama' => 'Ilmu Pertanian'], 
            ['jenjang' => $jenjang['S3'], 'fakultas' => $fakultas['Biologi'], 'nama' => 'Biologi'], 
            ['jenjang' => $jenjang['S3'], 'fakultas' => $fakultas['FEB'], 'nama' => 'Akuntansi'], 
            ['jenjang' => $jenjang['S3'], 'fakultas' => $fakultas['FEB'], 'nama' => 'Ilmu Manajemen'], 
            ['jenjang' => $jenjang['S3'], 'fakultas' => $fakultas['FEB'], 'nama' => 'Ekonomi'], 
            ['jenjang' => $jenjang['S3'], 'fakultas' => $fakultas['Peternakan'], 'nama' => 'Peternakan'], 
            ['jenjang' => $jenjang['S3'], 'fakultas' => $fakultas['Hukum'], 'nama' => 'Hukum'], 
            ['jenjang' => $jenjang['S3'], 'fakultas' => $fakultas['FEB'], 'nama' => 'Administrasi Publik'], 
            ['jenjang' => $jenjang['S2'], 'fakultas' => $fakultas['Pertanian'], 'nama' => 'Magister Ilmu Lingkungan'], 
            ['jenjang' => $jenjang['S2'], 'fakultas' => $fakultas['Pertanian'], 'nama' => 'Magister Penyuluh Pertanian'], 
            ['jenjang' => $jenjang['S2'], 'fakultas' => $fakultas['Pertanian'], 'nama' => 'Magister Bioteknologi Pertanian'], 
            ['jenjang' => $jenjang['S2'], 'fakultas' => $fakultas['Pertanian'], 'nama' => 'Magister Agribisnis'], 
            ['jenjang' => $jenjang['S2'], 'fakultas' => $fakultas['Pertanian'], 'nama' => 'Agronomi'], 
            ['jenjang' => $jenjang['S2'], 'fakultas' => $fakultas['Pertanian'], 'nama' => 'Ilmu Pangan'], 
            ['jenjang' => $jenjang['S2'], 'fakultas' => $fakultas['Biologi'], 'nama' => 'Biologi'], 
            ['jenjang' => $jenjang['S2'], 'fakultas' => $fakultas['FEB'], 'nama' => 'Ekonomi'], 
            ['jenjang' => $jenjang['S2'], 'fakultas' => $fakultas['FEB'], 'nama' => 'Ilmu Manajemen'], 
            ['jenjang' => $jenjang['S2'], 'fakultas' => $fakultas['FEB'], 'nama' => 'Magister Akuntansi (MAKSI)'], 
            ['jenjang' => $jenjang['S2'], 'fakultas' => $fakultas['Peternakan'], 'nama' => 'Magister Peternakan'], 
            ['jenjang' => $jenjang['S2'], 'fakultas' => $fakultas['Hukum'], 'nama' => 'Magister Hukum'], 
            ['jenjang' => $jenjang['S2'], 'fakultas' => $fakultas['Hukum'], 'nama' => 'Magister Kenotariatan (MKn)'], 
            ['jenjang' => $jenjang['S2'], 'fakultas' => $fakultas['FISIP'], 'nama' => 'Magister Administrasi Publik'], 
            ['jenjang' => $jenjang['S2'], 'fakultas' => $fakultas['FISIP'], 'nama' => 'Magister Sosiologi'], 
            ['jenjang' => $jenjang['S2'], 'fakultas' => $fakultas['FISIP'], 'nama' => 'Magister Ilmu Komunikasi'], 
            ['jenjang' => $jenjang['S2'], 'fakultas' => $fakultas['FISIP'], 'nama' => 'Ilmu Politik'], 
            ['jenjang' => $jenjang['S2'], 'fakultas' => $fakultas['Teknik'], 'nama' => 'Teknik Sipil'], 
            ['jenjang' => $jenjang['S2'], 'fakultas' => $fakultas['Kedokteran'], 'nama' => 'Ilmu Biomedis'], 
            ['jenjang' => $jenjang['S2'], 'fakultas' => $fakultas['FIKES'], 'nama' => 'Kesehatan Masyarakat'], 
            ['jenjang' => $jenjang['S2'], 'fakultas' => $fakultas['FIKES'], 'nama' => 'Magister Keperawatan'], 
            ['jenjang' => $jenjang['S2'], 'fakultas' => $fakultas['MIPA'], 'nama' => 'Fisika'], 
            ['jenjang' => $jenjang['S2'], 'fakultas' => $fakultas['FPIK'], 'nama' => 'Ilmu Kelautan'], 
            ['jenjang' => $jenjang['S2'], 'fakultas' => $fakultas['FPIK'], 'nama' => 'Sumberdaya Akuatik'], 
        ];

        foreach ($prodi as $pro) {
            Prodi::create([
                'jenjang_id' => $pro['jenjang']->id,
                'fakultas_id' => $pro['fakultas']->id,
                'nama' => $pro['nama'],
            ]);
        }
    }
}
