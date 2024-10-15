<?php

namespace Database\Seeders;

use App\Models\Instrumen;
use App\Models\Jabatan;
use App\Models\JenisPertanyaan;
use App\Models\Jenjang;
use App\Models\Kategori;
use App\Models\Kriteria;
use App\Models\Level;
use App\Models\Peraturan;
use App\Models\Prodi;
use App\Models\Standar;
use App\Models\Unit;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class InstrumenSamaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $peraturan = Peraturan::where('tahun', 2023)->first();
        $levelPs = Level::where('slug', 'prodi')->first();
        $jenisPertanyaanText = JenisPertanyaan::where('slug', 'text')->first();
        $jenisPertanyaanNumber = JenisPertanyaan::where('slug', 'number')->first();
        $kriteriaBM = Kriteria::where('slug', 'belum-memenuhi')->first();
        $kriteriaM = Kriteria::where('slug', 'memenuhi')->first();
        $kriteriaML = Kriteria::where('slug', 'melampaui')->first();

        $standar = [
            'Pendidikan' => Standar::where(['peraturan_id' => $peraturan->id, 'kode' => 'PD'])->first(),
            'Penelitian' => Standar::where(['peraturan_id' => $peraturan->id, 'kode' => 'PN'])->first(),
            'PKM' => Standar::where(['peraturan_id' => $peraturan->id, 'kode' => 'PM'])->first(),
            'Non' => Standar::where(['peraturan_id' => $peraturan->id, 'kode' => 'NA'])->first(),
        ];

        $kategori = [
            'PDL' => Kategori::where(['standar_id' => $standar['Pendidikan']->id, 'kode' => 'L'])->first(),
            'PDP' => Kategori::where(['standar_id' => $standar['Pendidikan']->id, 'kode' => 'P'])->first(),
            'PDM' => Kategori::where(['standar_id' => $standar['Pendidikan']->id, 'kode' => 'M'])->first(),
            'PNL' => Kategori::where(['standar_id' => $standar['Penelitian']->id, 'kode' => 'L'])->first(),
            'PNP' => Kategori::where(['standar_id' => $standar['Penelitian']->id, 'kode' => 'P'])->first(),
            'PNM' => Kategori::where(['standar_id' => $standar['Penelitian']->id, 'kode' => 'M'])->first(),
            'PKML' => Kategori::where(['standar_id' => $standar['PKM']->id, 'kode' => 'L'])->first(),
            'PKMP' => Kategori::where(['standar_id' => $standar['PKM']->id, 'kode' => 'P'])->first(),
            'PKMM' => Kategori::where(['standar_id' => $standar['PKM']->id, 'kode' => 'M'])->first(),
            'NAM' => Kategori::where(['standar_id' => $standar['Non']->id, 'kode' => 'M'])->first(),
            'NAK' => Kategori::where(['standar_id' => $standar['Non']->id, 'kode' => 'K'])->first(),
            'NAL' => Kategori::where(['standar_id' => $standar['Non']->id, 'kode' => 'L'])->first(),
            'NAT' => Kategori::where(['standar_id' => $standar['Non']->id, 'kode' => 'T'])->first(),
            'NAKEM' => Kategori::where(['standar_id' => $standar['Non']->id, 'kode' => 'M'])->first(),
            'NAKEU' => Kategori::where(['standar_id' => $standar['Non']->id, 'kode' => 'U'])->first(),
            'NASAP' => Kategori::where(['standar_id' => $standar['Non']->id, 'kode' => 'S'])->first(),
        ];

        $level = [
            'ps' => Level::where('slug', 'prodi')->first(),
            'upps' => Level::where('slug', 'fakultas')->first(),
            'universitas' => Level::where('slug', 'universitas')->first(),
        ];

        $jenisPertanyaan = [
            'text' => $jenisPertanyaanText->id,
            'number' => $jenisPertanyaanNumber->id,
        ];

        $jenjangs = [
            'D3' => Jenjang::where('nama', 'D3')->first(),
            'S1' => Jenjang::where('nama', 'S1')->first(),
            'S2' => Jenjang::where('nama', 'S2')->first(),
            'S3' => Jenjang::where('nama', 'S3')->first(),
            'Profesi' => Jenjang::where('nama', 'Profesi')->first()
        ];

        $prodis = [
            'Pendidikan Profesi Dokter Gigi' => Prodi::where('nama', 'Pendidikan Profesi Dokter Gigi')->first(),
            'Pendidikan Profesi Ners' => Prodi::where('nama', 'Pendidikan Profesi Ners')->first(),
        ];

        $jabatanUpps = [
            'Dekan' => Jabatan::where('nama', 'Dekan')->first(),
            'WD I' => Jabatan::where('nama', 'WD I')->first(),
            'WD II' => Jabatan::where('nama', 'WD II')->first(),
            'WD III' => Jabatan::where('nama', 'WD III')->first(),
            'WR I' => Jabatan::where('nama', 'WR I')->first(),
            'WR II' => Jabatan::where('nama', 'WR II')->first(),
            'WR III' => Jabatan::where('nama', 'WR III')->first(),
            'WR IV' => Jabatan::where('nama', 'WR IV')->first(),
            'Kabauk' => Jabatan::where('nama', 'Kepala Biro Keuangan')->first(),
            'Kepala Biro Akademik' => Jabatan::where('nama', 'Kepala Biro Akademik')->first(),
            'Ketua LP3M' => Jabatan::where('nama', 'Ketua LP3M')->first(),
            'Ketua LPPM' => Jabatan::where('nama', 'Ketua LPPM')->first(),
            'Ketua LPTSI' => Jabatan::where('nama', 'Ketua LPTSI')->first(),
            'Ketua ULT' => Jabatan::where('nama', 'Ketua ULT')->first(),
        ];

        $units = [
            'LP3M' => Unit::where('nama', 'LP3M')->first(),
            'LPPM' => Unit::where('nama', 'LPPM')->first(),
            'WR' => Unit::where('nama', 'WR')->first(),
            'Biro Akademik' => Unit::where('nama', 'Biro Akademik')->first(),
            'Biro Keuangan' => Unit::where('nama', 'Biro Keuangan')->first(),
            'LPTSI' => Unit::where('nama', 'LPTSI')->first(),
            'ULT' => Unit::where('nama', 'ULT')->first(),
        ];

        $instrumenPDL6 = [
            [
                'pernyataan' => 'Program studi telah memiliki rumusan kompetensi utama lulusan yang memenuhi ketentuan minimal program pendidikan (S1)',
                'indikator' => 'Program studi telah memiliki rumusan kompetensi utama lulusan yang memenuhi ketentuan minimal program pendidikan (S1)',
                'kriteriaBM' => 'Rumusan kompetensi utama lulusan belum memenuhi ketentuan minimal program pendidikan.',
                'kriteriaM' => 'Kompetensi Utama Lulusan Program studi memenuhi ketentuan :
                Sarjana, minimal:
                1.	menguasai konsep teoretis bidang pengetahuan dan keterampilan tertentu secara umum dan khusus untuk menyelesaikan masalah secara prosedural sesuai dengan lingkup pekerjaannya; dan
                2.	mampu beradaptasi terhadap situasi perubahan yang dihadapi',
                'kriteriaML' => 'Memiliki Kompetensi tambahan sebagai penciri PS',
                'jenjangs' => ['S1'],
                'jenisPertanyaan' => $jenisPertanyaan['text']
            ],
            [
                'pernyataan' => 'Program studi telah memiliki rumusan kompetensi utama lulusan yang memenuhi ketentuan minimal program pendidikan (S2)',
                'indikator' => 'Program studi telah memiliki rumusan kompetensi utama lulusan yang memenuhi ketentuan minimal program pendidikan (S2)',
                'kriteriaBM' => 'Rumusan kompetensi utama lulusan belum memenuhi ketentuan minimal program pendidikan.',
                'kriteriaM' => 'Kompetensi Utama Lulusan Program studi memenuhi ketentuan :
                Magister, minimal:
                menguasai teori bidang pengetahuan tertentu untuk mengembangkan ilmu pengetahuan dan teknologi melalui riset atau penciptaan karya inovatif;',
                'kriteriaML' => 'Memiliki Kompetensi tambahan sebagai penciri PS',
                'jenjangs' => ['S2'],
                'jenisPertanyaan' => $jenisPertanyaan['text']
            ],
            [
                'pernyataan' => 'Program studi telah memiliki rumusan kompetensi utama lulusan yang memenuhi ketentuan minimal program pendidikan (S3)',
                'indikator' => 'Program studi telah memiliki rumusan kompetensi utama lulusan yang memenuhi ketentuan minimal program pendidikan (S3)',
                'kriteriaBM' => 'Rumusan kompetensi utama lulusan belum memenuhi ketentuan minimal program pendidikan.',
                'kriteriaM' => 'Kompetensi Utama Lulusan Program studi memenuhi ketentuan :
                Doktor, minimal:
                1.	menguasai filosofi keilmuan bidang ilmu pengetahuan dan keterampilan tertentu;
                2.	mampu melakukan pendalaman dan perluasan ilmu pengetahuan dan teknologi melalui riset atau penciptaan karya orisinal dan teruji;',
                'kriteriaML' => 'Memiliki Kompetensi tambahan sebagai penciri PS',
                'jenjangs' => ['S3'],
                'jenisPertanyaan' => $jenisPertanyaan['text']
            ],
            [
                'pernyataan' => 'Program studi telah memiliki rumusan kompetensi utama lulusan yang memenuhi ketentuan minimal program pendidikan (Profesi)',
                'indikator' => 'Program studi telah memiliki rumusan kompetensi utama lulusan yang memenuhi ketentuan minimal program pendidikan (Profesi)',
                'kriteriaBM' => 'Rumusan kompetensi utama lulusan belum memenuhi ketentuan minimal program pendidikan.',
                'kriteriaM' => 'Kompetensi Utama Lulusan Program studi memenuhi ketentuan :
                A. Profesi, minimal:
                1.	menguasai teori aplikasi bidang pengetahuan dan keterampilan tertentu dengan memanfaatkan ilmu pengetahuan dan teknologi pada bidang profesi tertentu; dan
                2.	mampu mengelola sumber daya, menerapkan standar profesi, mengevaluasi, dan mengembangkan strategi organisasi;
                B. Spesialis, minimal:
                menguasai teori bidang ilmu pengetahuan tertentu untuk mengembangkan ilmu pengetahuan dan teknologi pada bidang keilmuan dan praktik profesionalnya melalui praktik profesional serta didukung dengan riset keilmuan;',
                'kriteriaML' => 'Memiliki Kompetensi tambahan sebagai penciri PS',
                'jenjangs' => ['Profesi'],
                'jenisPertanyaan' => $jenisPertanyaan['text']
            ],
        ];

        foreach ($instrumenPDL6 as $data) {

            $instrumen = Instrumen::create([
                'peraturan_id' => $peraturan->id,
                'standar_id' => $standar['Pendidikan']->id,
                'kategori_id' => $kategori['PDL']->id,
                'level_id' => $levelPs->id,
                'jenis_pertanyaan_id' => $data['jenisPertanyaan'],
                'pernyataan' => $data['pernyataan'],
                'indikator' => $data['indikator'],

            ]);

            $kode = $standar['Pendidikan']->kode . $kategori['PDL']->kode . '-6';

            $instrumen->kode = $kode;
            $instrumen->save();

            $kriteriaBM->instrumen()->attach($instrumen->id, ['isi' => $data['kriteriaBM']]);
            $kriteriaM->instrumen()->attach($instrumen->id, ['isi' => $data['kriteriaM']]);
            $kriteriaML->instrumen()->attach($instrumen->id, ['isi' => $data['kriteriaML']]);

            if (isset($data['jenjangs'])) {
                foreach ($data['jenjangs'] as $jenjang) {
                    if (isset($jenjangs[$jenjang])) {
                        $jenjangs[$jenjang]->instrumen()->attach($instrumen->id);
                    }
                }
            } elseif (isset($data['prodis'])) {
                foreach ($data['prodis'] as $prodi) {
                    if (isset($prodis[$prodi])) {
                        $prodis[$prodi]->instrumen()->attach($instrumen->id);
                    }
                }
            }
        }

        $instrumenPDL9 = [
            [
                'pernyataan' => 'Rata-rata IPK lulusan pada tahun TS (tahun sekarang)',
                'indikator' => 'Rata-rata IPK lulusan pada tahun TS (tahun sekarang)',
                'kriteriaBM' => 'Kurang dari S1 = 2,10
                ',
                'kriteriaM' => 'Rata-rata IPK Lulusan S1 = 2,10',
                'kriteriaML' => 'Lebih dari S1 = 2,10',
                'jenjangs' => ['S1'],
                'jenisPertanyaan' => $jenisPertanyaan['number']
            ],
            [
                'pernyataan' => 'Rata-rata IPK lulusan pada tahun TS (tahun sekarang)',
                'indikator' => 'Rata-rata IPK lulusan pada tahun TS (tahun sekarang)',
                'kriteriaBM' => 'Kurang dari Profesi = 3,10
                ',
                'kriteriaM' => 'Rata-rata IPK Lulusan Profesi = 3,10',
                'kriteriaML' => 'Lebih dari Profesi = 3,10',
                'jenjangs' => ['Profesi'],
                'jenisPertanyaan' => $jenisPertanyaan['number']
            ],
            [
                'pernyataan' => 'Rata-rata IPK lulusan pada tahun TS (tahun sekarang)',
                'indikator' => 'Rata-rata IPK lulusan pada tahun TS (tahun sekarang)',
                'kriteriaBM' => 'Kurang dari S2/Magister = 3,25
                ',
                'kriteriaM' => 'Rata-rata IPK Lulusan S2/Magister = 3,25',
                'kriteriaML' => 'Lebih dari S2/Magister = 3,25',
                'jenjangs' => ['S2'],
                'jenisPertanyaan' => $jenisPertanyaan['number']
            ],
            [
                'pernyataan' => 'Rata-rata IPK lulusan pada tahun TS (tahun sekarang)',
                'indikator' => 'Rata-rata IPK lulusan pada tahun TS (tahun sekarang)',
                'kriteriaBM' => 'Kurang dari Doktor = 3,25 (memenuhi semua CPL)
                ',
                'kriteriaM' => 'Rata-rata IPK Lulusan Doktor = 3,25 (memenuhi semua CPL)',
                'kriteriaML' => 'Lebih dari Doktor = 3,25 (memenuhi semua CPL)',
                'jenjangs' => ['S3'],
                'jenisPertanyaan' => $jenisPertanyaan['number']
            ],
        ];

        foreach ($instrumenPDL9 as $data) {

            $instrumen = Instrumen::create([
                'peraturan_id' => $peraturan->id,
                'standar_id' => $standar['Pendidikan']->id,
                'kategori_id' => $kategori['PDL']->id,
                'level_id' => $levelPs->id,
                'jenis_pertanyaan_id' => $data['jenisPertanyaan'],
                'pernyataan' => $data['pernyataan'],
                'indikator' => $data['indikator'],

            ]);

            $kode = $standar['Pendidikan']->kode . $kategori['PDL']->kode . '-9';

            $instrumen->kode = $kode;
            $instrumen->save();

            $kriteriaBM->instrumen()->attach($instrumen->id, ['isi' => $data['kriteriaBM']]);
            $kriteriaM->instrumen()->attach($instrumen->id, ['isi' => $data['kriteriaM']]);
            $kriteriaML->instrumen()->attach($instrumen->id, ['isi' => $data['kriteriaML']]);

            if (isset($data['jenjangs'])) {
                foreach ($data['jenjangs'] as $jenjang) {
                    if (isset($jenjangs[$jenjang])) {
                        $jenjangs[$jenjang]->instrumen()->attach($instrumen->id);
                    }
                }
            } elseif (isset($data['prodis'])) {
                foreach ($data['prodis'] as $prodi) {
                    if (isset($prodis[$prodi])) {
                        $prodis[$prodi]->instrumen()->attach($instrumen->id);
                    }
                }
            }
        }

        $instrumenPDL10 = [
            [
                'pernyataan' => 'Rata-rata masa studi lulusan program studi pada tahun TS (tahun sekarang)',
                'indikator' => 'Rata-rata masa studi lulusan program studi pada tahun TS (tahun sekarang)',
                'kriteriaBM' => '-',
                'kriteriaM' => 'Rata-rata masa studi lulusan : S1 = 16 semester',
                'kriteriaML' => 'Kurang dari : S1 = 16 semester',
                'jenjangs' => ['S1'],
                'jenisPertanyaan' => $jenisPertanyaan['number']
            ],
            [
                'pernyataan' => 'Rata-rata masa studi lulusan program studi pada tahun TS (tahun sekarang)',
                'indikator' => 'Rata-rata masa studi lulusan program studi pada tahun TS (tahun sekarang)',
                'kriteriaBM' => '-',
                'kriteriaM' => 'Rata-rata masa studi lulusan : S2 = 8 semester',
                'kriteriaML' => 'Kurang dari : S2 = 8 semester',
                'jenjangs' => ['S2'],
                'jenisPertanyaan' => $jenisPertanyaan['number']
            ],
            [
                'pernyataan' => 'Rata-rata masa studi lulusan program studi pada tahun TS (tahun sekarang)',
                'indikator' => 'Rata-rata masa studi lulusan program studi pada tahun TS (tahun sekarang)',
                'kriteriaBM' => '-',
                'kriteriaM' => 'Rata-rata masa studi lulusan : S3 = 12 semester',
                'kriteriaML' => 'Kurang dari : S3 = 12 semester',
                'jenjangs' => ['S3'],
                'jenisPertanyaan' => $jenisPertanyaan['number']
            ],
            [
                'pernyataan' => 'Rata-rata masa studi lulusan program studi pada tahun TS (tahun sekarang)',
                'indikator' => 'Rata-rata masa studi lulusan program studi pada tahun TS (tahun sekarang)',
                'kriteriaBM' => '-',
                'kriteriaM' => 'Rata-rata masa studi lulusan : Profesi = 4 semester, Profesi Kedokteran Umum = 8 semester, Profesi Kedokteran gigi = 8 semester',
                'kriteriaML' => 'Kurang dari : Profesi = 4 semester, Profesi Kedokteran Umum = 8 semester, Profesi Kedokteran gigi = 8 semester',
                'jenjangs' => ['Profesi'],
                'jenisPertanyaan' => $jenisPertanyaan['number']
            ],
        ];

        foreach ($instrumenPDL10 as $data) {

            $instrumen = Instrumen::create([
                'peraturan_id' => $peraturan->id,
                'standar_id' => $standar['Pendidikan']->id,
                'kategori_id' => $kategori['PDL']->id,
                'level_id' => $levelPs->id,
                'jenis_pertanyaan_id' => $data['jenisPertanyaan'],
                'pernyataan' => $data['pernyataan'],
                'indikator' => $data['indikator'],

            ]);

            $kode = $standar['Pendidikan']->kode . $kategori['PDL']->kode . '-10';

            $instrumen->kode = $kode;
            $instrumen->save();

            $kriteriaBM->instrumen()->attach($instrumen->id, ['isi' => $data['kriteriaBM']]);
            $kriteriaM->instrumen()->attach($instrumen->id, ['isi' => $data['kriteriaM']]);
            $kriteriaML->instrumen()->attach($instrumen->id, ['isi' => $data['kriteriaML']]);

            if (isset($data['jenjangs'])) {
                foreach ($data['jenjangs'] as $jenjang) {
                    if (isset($jenjangs[$jenjang])) {
                        $jenjangs[$jenjang]->instrumen()->attach($instrumen->id);
                    }
                }
            } elseif (isset($data['prodis'])) {
                foreach ($data['prodis'] as $prodi) {
                    if (isset($prodis[$prodi])) {
                        $prodis[$prodi]->instrumen()->attach($instrumen->id);
                    }
                }
            }
        }

        $instrumenPDL11 = [
            [
                'pernyataan' => 'Persentase lulusan tepat waktu pada jenjang pendidikan S1.',
                'indikator' => 'Persentase lulusan tepat waktu pada jenjang pendidikan S1.',
                'kriteriaBM' => 'Prosentase Lulusan Tepat waktu S1 : < 30%',
                'kriteriaM' => 'Prosentase Lulusan Tepat waktu S1 = 30-80%',
                'kriteriaML' => 'Prosentase Lulusan Tepat waktu S1 > 80%',
                'jenjangs' => ['S1'],
                'jenisPertanyaan' => $jenisPertanyaan['number']
            ],
            [
                'pernyataan' => 'Persentase lulusan tepat waktu pada jenjang pendidikan S2/Magister.',
                'indikator' => 'Persentase lulusan tepat waktu pada jenjang pendidikan S2/Magister.',
                'kriteriaBM' => 'Prosentase Lulusan Tepat waktu S2/Magister : < 30%',
                'kriteriaM' => 'Prosentase Lulusan Tepat waktu S2/Magister = 30-80%',
                'kriteriaML' => 'Prosentase Lulusan Tepat waktu S2/Magister > 80%',
                'jenjangs' => ['S2'],
                'jenisPertanyaan' => $jenisPertanyaan['number']
            ],
            [
                'pernyataan' => 'Persentase lulusan tepat waktu pada jenjang pendidikan S3/Doktor.',
                'indikator' => 'Persentase lulusan tepat waktu pada jenjang pendidikan S3/Doktor.',
                'kriteriaBM' => 'Prosentase Lulusan Tepat waktu S3/Doktor : < 30%',
                'kriteriaM' => 'Prosentase Lulusan Tepat waktu S3/Doktor = 30-80%',
                'kriteriaML' => 'Prosentase Lulusan Tepat waktu S3/Doktor > 80%',
                'jenjangs' => ['S3'],
                'jenisPertanyaan' => $jenisPertanyaan['number']
            ],
            [
                'pernyataan' => 'Persentase lulusan tepat waktu pada jenjang pendidikan Profesi.',
                'indikator' => 'Persentase lulusan tepat waktu pada jenjang pendidikan Profesi.',
                'kriteriaBM' => 'Prosentase Lulusan Tepat waktu Profesi : < 30%',
                'kriteriaM' => 'Prosentase Lulusan Tepat waktu Profesi = 30-80%',
                'kriteriaML' => 'Prosentase Lulusan Tepat waktu Profesi > 80%',
                'jenjangs' => ['Profesi'],
                'jenisPertanyaan' => $jenisPertanyaan['number']
            ],
        ];

        foreach ($instrumenPDL11 as $data) {

            $instrumen = Instrumen::create([
                'peraturan_id' => $peraturan->id,
                'standar_id' => $standar['Pendidikan']->id,
                'kategori_id' => $kategori['PDL']->id,
                'level_id' => $levelPs->id,
                'jenis_pertanyaan_id' => $data['jenisPertanyaan'],
                'pernyataan' => $data['pernyataan'],
                'indikator' => $data['indikator'],

            ]);

            $kode = $standar['Pendidikan']->kode . $kategori['PDL']->kode . '-11';

            $instrumen->kode = $kode;
            $instrumen->save();

            $kriteriaBM->instrumen()->attach($instrumen->id, ['isi' => $data['kriteriaBM']]);
            $kriteriaM->instrumen()->attach($instrumen->id, ['isi' => $data['kriteriaM']]);
            $kriteriaML->instrumen()->attach($instrumen->id, ['isi' => $data['kriteriaML']]);

            if (isset($data['jenjangs'])) {
                foreach ($data['jenjangs'] as $jenjang) {
                    if (isset($jenjangs[$jenjang])) {
                        $jenjangs[$jenjang]->instrumen()->attach($instrumen->id);
                    }
                }
            } elseif (isset($data['prodis'])) {
                foreach ($data['prodis'] as $prodi) {
                    if (isset($prodis[$prodi])) {
                        $prodis[$prodi]->instrumen()->attach($instrumen->id);
                    }
                }
            }
        }

        $instrumenPDL12 = [
            [
                'pernyataan' => 'Persentase tingkat kelulusan.',
                'indikator' => 'Persentase tingkat kelulusan.',
                'kriteriaBM' => 'Lebih dari S1 = minimal 75%',
                'kriteriaM' => 'Produktivitas (tingkat kelulusan) S1 = minimal 75%',
                'kriteriaML' => 'Kurang dari S1 = minimal 75%',
                'jenjangs' => ['S1'],
                'jenisPertanyaan' => $jenisPertanyaan['number']
            ],
            [
                'pernyataan' => 'Persentase tingkat kelulusan.',
                'indikator' => 'Persentase tingkat kelulusan.',
                'kriteriaBM' => 'Lebih dari S2/Magister = 75%',
                'kriteriaM' => 'Produktivitas (tingkat kelulusan) S2/Magister = 75%',
                'kriteriaML' => 'Kurang dari S2/Magister = 75%',
                'jenjangs' => ['S2'],
                'jenisPertanyaan' => $jenisPertanyaan['number']
            ],
            [
                'pernyataan' => 'Persentase tingkat kelulusan.',
                'indikator' => 'Persentase tingkat kelulusan.',
                'kriteriaBM' => 'Lebih dari S3/Doktor = 75%',
                'kriteriaM' => 'Produktivitas (tingkat kelulusan) S3/Doktor = 75%',
                'kriteriaML' => 'Kurang dari S3/Doktor = 75%',
                'jenjangs' => ['S3'],
                'jenisPertanyaan' => $jenisPertanyaan['number']
            ],
            [
                'pernyataan' => 'Persentase tingkat kelulusan.',
                'indikator' => 'Persentase tingkat kelulusan.',
                'kriteriaBM' => 'Lebih dari Profesi = 75%',
                'kriteriaM' => 'Produktivitas (tingkat kelulusan) Profesi = 75%',
                'kriteriaML' => 'Kurang dari Profesi = 75%',
                'jenjangs' => ['Profesi'],
                'jenisPertanyaan' => $jenisPertanyaan['number']
            ],
        ];

        foreach ($instrumenPDL12 as $data) {

            $instrumen = Instrumen::create([
                'peraturan_id' => $peraturan->id,
                'standar_id' => $standar['Pendidikan']->id,
                'kategori_id' => $kategori['PDL']->id,
                'level_id' => $levelPs->id,
                'jenis_pertanyaan_id' => $data['jenisPertanyaan'],
                'pernyataan' => $data['pernyataan'],
                'indikator' => $data['indikator'],

            ]);

            $kode = $standar['Pendidikan']->kode . $kategori['PDL']->kode . '-12';

            $instrumen->kode = $kode;
            $instrumen->save();

            $kriteriaBM->instrumen()->attach($instrumen->id, ['isi' => $data['kriteriaBM']]);
            $kriteriaM->instrumen()->attach($instrumen->id, ['isi' => $data['kriteriaM']]);
            $kriteriaML->instrumen()->attach($instrumen->id, ['isi' => $data['kriteriaML']]);

            if (isset($data['jenjangs'])) {
                foreach ($data['jenjangs'] as $jenjang) {
                    if (isset($jenjangs[$jenjang])) {
                        $jenjangs[$jenjang]->instrumen()->attach($instrumen->id);
                    }
                }
            } elseif (isset($data['prodis'])) {
                foreach ($data['prodis'] as $prodi) {
                    if (isset($prodis[$prodi])) {
                        $prodis[$prodi]->instrumen()->attach($instrumen->id);
                    }
                }
            }
        }

        $instrumenPDP24 = [
            [
                'pernyataan' => 'Tersedia pelaporan hasil penilaian sumatif dari masing-masing program studi ke PD Dikti yang paling lambat dilaksanakan 2 bulan setelah akhir semester',
                'kriteriaBM' => 'Tidak dilakukan  Pelaporan hasil penilaian sumatif dari masing-masing program studi ke PD Dikti paling lambat dilaksanakan 2 bulan setelah akhir semester.',
                'kriteriaM' => 'Pelaporan hasil penilaian sumatif dari masing-masing program studi ke PD Dikti paling lambat dilaksanakan 2 bulan setelah akhir semester.',
                'kriteriaML' => 'Pelaporan hasil penilaian sumatif dari masing-masing program studi ke PD Dikti paling lambat dilaksanakan 2 bulan setelah akhir semester dan dilakukan evaluasi  secara berkala terhadap hasil penilaian sumatif.',
                'jabatans' => ['Kepala Biro Akademik'],
                'units' => ['Biro Akademik'],
                'jenisPertanyaan' => $jenisPertanyaan['text']
            ],
        ];

        foreach ($instrumenPDP24 as $data) {
            $instrumen = Instrumen::create([
                'peraturan_id' => $peraturan->id,
                'standar_id' => $standar['Pendidikan']->id,
                'kategori_id' => $kategori['PDP']->id,
                'level_id' => $level['universitas']->id,
                'jenis_pertanyaan_id' => $data['jenisPertanyaan'],
                'pernyataan' => $data['pernyataan'],
                'indikator' => $data['pernyataan'],

            ]);

            $kode = $standar['Penelitian']->kode . $kategori['PDP']->kode . '-24';

            $instrumen->kode = $kode;
            $instrumen->save();

            $kriteriaBM->instrumen()->attach($instrumen->id, ['isi' => $data['kriteriaBM']]);
            $kriteriaM->instrumen()->attach($instrumen->id, ['isi' => $data['kriteriaM']]);
            $kriteriaML->instrumen()->attach($instrumen->id, ['isi' => $data['kriteriaML']]);

            foreach ($data['units'] as $unit) {
                $units[$unit]->instrumen()->attach($instrumen->id);
            }
            foreach ($data['jabatans'] as $jabatan) {
                $jabatanUpps[$jabatan]->instrumen()->attach($instrumen->id);
            }
        }

        $instrumenPDP25 = [
            [
                'pernyataan' => 'Tersedia bukti dokumen penetapan syarat dan predikat kelulusan sesuai program Pendidikan',
                'kriteriaBM' => 'Tidak tersedia dokumen penetapan syarat dan predikat kelulusan sesuai program pendidikan.',
                'kriteriaM' => 'Tersedia dokumen penetapan syarat dan predikat kelulusan sesuai program pendidikan.',
                'kriteriaML' => 'Tersedia dokumen penetapan syarat dan predikat kelulusan sesuai program Pendidikan yang disosialisasikan di media sosial fakultas/prodi dan ditinjau secara berkala.',
                'jabatans' => ['Kepala Biro Akademik'],
                'units' => ['Biro Akademik'],
                'jenisPertanyaan' => $jenisPertanyaan['text']
            ],
        ];

        foreach ($instrumenPDP25 as $data) {
            $instrumen = Instrumen::create([
                'peraturan_id' => $peraturan->id,
                'standar_id' => $standar['Pendidikan']->id,
                'kategori_id' => $kategori['PDP']->id,
                'level_id' => $level['universitas']->id,
                'jenis_pertanyaan_id' => $data['jenisPertanyaan'],
                'pernyataan' => $data['pernyataan'],
                'indikator' => $data['pernyataan'],

            ]);

            $kode = $standar['Penelitian']->kode . $kategori['PDP']->kode . '-25';

            $instrumen->kode = $kode;
            $instrumen->save();

            $kriteriaBM->instrumen()->attach($instrumen->id, ['isi' => $data['kriteriaBM']]);
            $kriteriaM->instrumen()->attach($instrumen->id, ['isi' => $data['kriteriaM']]);
            $kriteriaML->instrumen()->attach($instrumen->id, ['isi' => $data['kriteriaML']]);

            foreach ($data['units'] as $unit) {
                $units[$unit]->instrumen()->attach($instrumen->id);
            }
            foreach ($data['jabatans'] as $jabatan) {
                $jabatanUpps[$jabatan]->instrumen()->attach($instrumen->id);
            }
        }

        $instrumenTambahan = [
            [
                'pernyataan' => 'Apakah UPPS telah melaksanakan Rapat Tinjauan Manajemen?',
                'kriteriaBM' => 'Belum dilaksanakan',
                'kriteriaM' => 'Sudah dilaksanakan',
                'kriteriaML' => 'Memiliki laporan pelaksanaan RTM',
                'jabatans' => ['Dekan', 'WD I', 'WD II', 'WD III'],
                'jenisPertanyaan' => $jenisPertanyaan['text']
            ],
        ];

        foreach ($instrumenTambahan as $data) {
            $instrumen = Instrumen::create([
                'peraturan_id' => $peraturan->id,
                'standar_id' => $standar['Pendidikan']->id,
                'kategori_id' => $kategori['PDL']->id,
                'level_id' => $level['upps']->id,
                'jenis_pertanyaan_id' => $data['jenisPertanyaan'],
                'pernyataan' => $data['pernyataan'],
                'indikator' => $data['pernyataan'],
            ]);

            $instrumen->kode = 'NPM-1';
            $instrumen->save();

            $kriteriaBM->instrumen()->attach($instrumen->id, ['isi' => $data['kriteriaBM']]);
            $kriteriaM->instrumen()->attach($instrumen->id, ['isi' => $data['kriteriaM']]);
            $kriteriaML->instrumen()->attach($instrumen->id, ['isi' => $data['kriteriaML']]);

            foreach ($data['jabatans'] as $jabatan) {
                $jabatanUpps[$jabatan]->instrumen()->attach($instrumen->id);
            }
        }
    }
}
