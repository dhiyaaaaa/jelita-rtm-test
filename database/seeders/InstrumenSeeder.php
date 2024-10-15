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

class InstrumenSeeder extends Seeder
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

        // PS Standar Pendidikan Luar
        $instrumenDataPDL = [
            [
                'pernyataan' => 'Program Studi telah memiliki rumusan CPL yang mencakup aspek sikap, pengetahuan, keterampilan umum, dan keterampilan khusus',
                'indikator' => 'Program Studi telah memiliki rumusan CPL yang mencakup aspek sikap, pengetahuan, keterampilan umum, dan keterampilan khusus',
                'kriteriaBM' => 'Rumusan CPL belum mencakup aspek sikap, pengetahuan, keterampilan umum, dan keterampilan khusus',
                'kriteriaM' => 'CPL memenuhi kompetensi yang mencakup aspek; sikap, pengetahuan, keterampilan umum, dan keterampilan khusus',
                'kriteriaML' => 'PS Memiliki kompetensi tambahan sebagai penciri program studi',
                'jenjangs' => ['D3', 'S1', 'S2', 'S3', 'Profesi'],
                'jenisPertanyaan' => $jenisPertanyaan['text']
            ],
            [
                'pernyataan' => 'Penyusunan CPL telah melibatkan pemangku kepentingan internal dan eksternal (termasuk asosiasi dan iduka (dunia industri dan dunia kerja))',
                'indikator' => 'Penyusunan CPL telah melibatkan pemangku kepentingan internal dan eksternal (termasuk asosiasi dan iduka (dunia industri dan dunia kerja))',
                'kriteriaBM' => 'CPL disusun dengan melibatkan sebagian dari pemangku kepentingan',
                'kriteriaM' => 'Keterlibatan pemangu kepentingan dalam penyusunan CPL meliputi pihak internal dan eksternal (termasuk asosiasi dan iduka (dunia industri dan dunia kerja))',
                'kriteriaML' => '-',
                'jenjangs' => ['D3', 'S1', 'S2', 'S3', 'Profesi'],
                'jenisPertanyaan' => $jenisPertanyaan['text']
            ],
            [
                'pernyataan' => 'Penyusunan CPL program studi telah memperhatikan 7 aspek:
				1.	visi dan misi perguruan tinggi;
				2.	kerangka kualifikasi nasional Indonesia;
				3.	perkembangan ilmu pengetahuan dan teknologi;
				4.	kebutuhan kompetensi kerja dari dunia kerja; 
				5.	ranah keilmuan program studi; 
				6.	kompetensi utama lulusan program studi; dan
				7.	kurikulum program studi sejenis.',
                'indikator' => 'Penyusunan CPL program studi telah memperhatikan 7 aspek:
				1.	visi dan misi perguruan tinggi;
				2.	kerangka kualifikasi nasional Indonesia;
				3.	perkembangan ilmu pengetahuan dan teknologi;
				4.	kebutuhan kompetensi kerja dari dunia kerja; 
				5.	ranah keilmuan program studi; 
				6.	kompetensi utama lulusan program studi; dan
				7.	kurikulum program studi sejenis.',
                'kriteriaBM' => 'CPL yang disusun belum memperhatikan 7 aspek.',
                'kriteriaM' => 'CPL yang disusun dengan memperhatikan 7 aspek.',
                'kriteriaML' => 'CPL dievaluasi secara rutin dan terdokumentasi',
                'jenjangs' => ['D3', 'S1', 'S2', 'S3', 'Profesi'],
                'jenisPertanyaan' => $jenisPertanyaan['text']
            ],
            [
                'pernyataan' => 'Persentase matakuliah yang CPL dan CPMK yang tertuang di RPS telah disosialisasikan kepada mahasiswa oleh dosen penanggungjawab matakuliah melalui eldiru/di kelas.',
                'indikator' => 'Persentase matakuliah yang CPL dan CPMK yang tertuang di RPS telah disosialisasikan kepada mahasiswa oleh dosen penanggungjawab matakuliah melalui eldiru/di kelas.',
                'kriteriaBM' => 'Belum semua matakuliah melaksanakan (kurang dari 100%)',
                'kriteriaM' => '100% matakuliah CPL dan CPMK nya telah disosialisasikan kepada mahasiswa oleh dosen penanggungjawab matakuliah melalui eldiru/di kelas ',
                'kriteriaML' => 'Tersedia bukti sosialisasi RPS',
                'jenjangs' => ['D3', 'S1', 'S2', 'S3', 'Profesi'],
                'jenisPertanyaan' => $jenisPertanyaan['text']
            ],
            [
                'pernyataan' => 'Program Studi memiliki peta kurikulum yang menunjukkan kaitan dan kontribusi mata kuliah terhadap CPL serta dilengkapi dengan instrumen pengukuran pemenuhan CPL',
                'indikator' => 'Program Studi memiliki peta kurikulum yang menunjukkan kaitan dan kontribusi mata kuliah terhadap CPL serta dilengkapi dengan instrumen pengukuran pemenuhan CPL',
                'kriteriaBM' => 'Kurikulum PS belum memuat peta kurikulum yang menunjukkan kaitan dan kontribusi mata kuliah terhadap CPL',
                'kriteriaM' => 'Dokumen kurikulum PS memuat peta kurikulum yang menunjukkan kaitan dan kontribusi mata kuliah terhadap CPL',
                'kriteriaML' => 'Kurikulum PS telah dilengkapi dengan instrumen pengukuran pemenuhan CPL',
                'jenjangs' => ['D3', 'S1', 'S2', 'S3', 'Profesi'],
                'jenisPertanyaan' => $jenisPertanyaan['text']
            ],
            [
                'pernyataan' => 'Program studi telah memiliki rumusan kompetensi utama lulusan yang memenuhi ketentuan minimal program pendidikan (D3)',
                'indikator' => 'Program studi telah memiliki rumusan kompetensi utama lulusan yang memenuhi ketentuan minimal program pendidikan (D3)',
                'kriteriaBM' => 'Rumusan kompetensi utama lulusan belum memenuhi ketentuan minimal program pendidikan.',
                'kriteriaM' => 'Kompetensi Utama Lulusan Program studi memenuhi ketentuan :
                Program D3, minimal:
                1.	menguasai konsep teoretis bidang pengetahuan dan keterampilan tertentu secara umum;
                2.	mampu menyelesaikan pekerjaan berlingkup luas; dan
                3.	mampu memilih metode yang sesuai dari beragam pilihan yang sudah maupun belum baku berdasarkan analisis data;',
                'kriteriaML' => 'Memiliki Kompetensi tambahan sebagai penciri PS',
                'jenjangs' => ['D3'],
                'jenisPertanyaan' => $jenisPertanyaan['text']
            ],
            [
                'pernyataan' => 'Kompetensi utama lulusan program studi telah mengacu pada kompetensi yang disusun oleh asosiasi prodi dan pihak terkait (pengguna lulusan, organisasi profesi, alumni) ',
                'indikator' => 'Kompetensi utama lulusan program studi telah mengacu pada kompetensi yang disusun oleh asosiasi prodi dan pihak terkait (pengguna lulusan, organisasi profesi, alumni) ',
                'kriteriaBM' => 'Belum melibatkan asosiasi dan pihak terkait(pengguna lulusan, organisasi profesi, alumni)',
                'kriteriaM' => 'Kompetensi utama lulusan mengacu pada kompetensi yang disusun oleh asosiasi prodi dan pihak terkait (pengguna lulusan, organisasi profesi, alumni)',
                'kriteriaML' => 'Terdokumentasi',
                'jenjangs' => ['D3', 'S1', 'S2', 'S3', 'Profesi'],
                'jenisPertanyaan' => $jenisPertanyaan['text']
            ],
            [
                'pernyataan' => 'Program studi memiliki bukti sahih pelaksanaan asesmen terhadap ketercapaian CPL program studi.',
                'indikator' => 'Program studi memiliki bukti sahih pelaksanaan asesmen terhadap ketercapaian CPL program studi.',
                'kriteriaBM' => 'Belum melaksanakan asesmen terhadap ketercapaian CPL',
                'kriteriaM' => 'Terdapat bukti sahih pelaksanaan  asesmen terhadap ketercapaian CPL Program Studi',
                'kriteriaML' => 'Program studi melakukan asesmen terhadap ketercapaian CPL yang dilaksanakan secara rutin (setiap semester) dan hasilnya terdokumentasi dengan baik',
                'jenjangs' => ['D3', 'S1', 'S2', 'S3', 'Profesi'],
                'jenisPertanyaan' => $jenisPertanyaan['text']
            ],
            [
                'pernyataan' => 'Rata-rata IPK lulusan pada tahun TS (tahun sekarang)',
                'indikator' => 'Rata-rata IPK lulusan pada tahun TS (tahun sekarang)',
                'kriteriaBM' => 'Kurang dari D3 = 2,10
                ',
                'kriteriaM' => 'Rata-rata IPK Lulusan D3 = 2,10',
                'kriteriaML' => 'Lebih dari D3 = 2,10',
                'jenjangs' => ['D3'],
                'jenisPertanyaan' => $jenisPertanyaan['number']
            ],
            [
                'pernyataan' => 'Rata-rata masa studi lulusan program studi pada tahun TS (tahun sekarang)',
                'indikator' => 'Rata-rata masa studi lulusan program studi pada tahun TS (tahun sekarang)',
                'kriteriaBM' => '-',
                'kriteriaM' => 'Rata-rata masa studi lulusan : D3 = 12 semester',
                'kriteriaML' => 'Kurang dari : D3 = 12 semester',
                'jenjangs' => ['D3'],
                'jenisPertanyaan' => $jenisPertanyaan['number']
            ],
            [
                'pernyataan' => 'Persentase lulusan tepat waktu pada jenjang pendidikan D3.',
                'indikator' => 'Persentase lulusan tepat waktu pada jenjang pendidikan D3.',
                'kriteriaBM' => 'Prosentase Lulusan Tepat waktu D3 : < 30%',
                'kriteriaM' => 'Prosentase Lulusan Tepat waktu D3 = 30-80%',
                'kriteriaML' => 'Prosentase Lulusan Tepat waktu D3 > 80%',
                'jenjangs' => ['D3'],
                'jenisPertanyaan' => $jenisPertanyaan['number']
            ],
            [
                'pernyataan' => 'Rata-rata produktivitas program studi (tingkat kelulusan).',
                'indikator' => 'Rata-rata produktivitas program studi (tingkat kelulusan).',
                'kriteriaBM' => 'Lebih dari D3 = 75%',
                'kriteriaM' => 'Produktivitas (tingkat kelulusan) D3 = 75%',
                'kriteriaML' => 'Kurang dari D3 = 75%',
                'jenjangs' => ['D3'],
                'jenisPertanyaan' => $jenisPertanyaan['number']
            ],
            [
                'pernyataan' => 'Jumlah prestasi akademik mahasiswa ditingkat lokal/nasional/Internasional.',
                'indikator' => 'Jumlah prestasi akademik mahasiswa ditingkat lokal/nasional/Internasional.',
                'kriteriaBM' => 'Memiliki prestasi akademik mahasiswa ditingkat lokal',
                'kriteriaM' => 'Memiliki prestasi akademik mahasiswa ditingkat nasional minimal 2 buah',
                'kriteriaML' => 'Memiliki prestasi  akademik mahasiswa ditingkat Internasional minimal 1 buah',
                'jenjangs' => ['D3', 'S1', 'S2', 'S3', 'Profesi'],
                'jenisPertanyaan' => $jenisPertanyaan['text']
            ],
            [
                'pernyataan' => 'Tingkat prestasi non akademik mahasiswa?',
                'indikator' => 'Tingkat prestasi non akademik mahasiswa?',
                'kriteriaBM' => 'Memiliki prestasi akademik mahasiswa ditingkat lokal',
                'kriteriaM' => 'Memiliki prestasi non akademik mahasiswa ditingkat nasional minimal 3 buah',
                'kriteriaML' => 'Memiliki prestasi akademik mahasiswa ditingkat internasional minimal 1 buah',
                'jenjangs' => ['D3', 'S1', 'S2'],
                'jenisPertanyaan' => $jenisPertanyaan['text']
            ],
            [
                'pernyataan' => 'Persentase kesesuaian bidang kerja',
                'indikator' => 'Persentase kesesuaian bidang kerja',
                'kriteriaBM' => 'Kesesuaian bidang kerja: <50% sesuai',
                'kriteriaM' => 'Kesesuaian bidang kerja= 50% ',
                'kriteriaML' => 'Kesesuaian bidang kerja: >50% sesuai ',
                'jenjangs' => ['D3', 'S1', 'S2', 'S3', 'Profesi'],
                'jenisPertanyaan' => $jenisPertanyaan['number']
            ],
            [
                'pernyataan' => 'Rata-rata masa tunggu lulusan program studi D3/S1?',
                'indikator' => 'Rata-rata masa tunggu lulusan program studi D3/S1?',
                'kriteriaBM' => 'Lebih dari 6 bulan',
                'kriteriaM' => 'Masa tunggu lulusan = 6 bulan',
                'kriteriaML' => 'Kurang dari 6 bulan',
                'jenjangs' => ['D3', 'S1'],
                'jenisPertanyaan' => $jenisPertanyaan['number']
            ],
            [
                'pernyataan' => 'Jumlah publikasi mahasiswa di jurnal/prosiding.',
                'indikator' => 'Jumlah publikasi mahasiswa di jurnal/prosiding.',
                'kriteriaBM' => 'Kurang dari S1 = Jumlah publikasi prosiding/jurnal nasional terakreditasi sinta 1-6
                S2 = Jumlah publikasi prosiding/jurnal nasional terakreditasi sinta 1-2
                S3 = Jumlah publikasi prosiding/jurnal internasional bereputasi/sinta 1 minimal 1 buah',
                'kriteriaM' => 'S1 = Jumlah publikasi prosiding/jurnal nasional terakreditasi sinta 1-6
                S2 = Jumlah publikasi prosiding/jurnal nasional terakreditasi sinta 1-2
                S3 = Jumlah publikasi prosiding/jurnal internasional bereputasi/sinta 1 minimal 1 buah',
                'kriteriaML' => 'S1 = Jumlah publikasi prosiding/jurnal nasional terakreditasi sinta 1-6 lebih dari 1 buah
                S2 = Jumlah publikasi prosiding/jurnal nasional terakreditasi sinta 1-2 lebih dari 1 buah
                S3 = Jumlah publikasi prosiding/jurnal internasional bereputasi /sinta 1 minimal 1 buah',
                'jenjangs' => ['S1', 'S2', 'S3', 'Profesi'],
                'jenisPertanyaan' => $jenisPertanyaan['text']
            ],
            [
                'pernyataan' => 'Jumlah mahasiswa yang melakukan diseminasi pada kegiatan seminar Internasional dan Nasional.',
                'indikator' => 'Jumlah mahasiswa yang melakukan diseminasi pada kegiatan seminar Internasional dan Nasional.',
                'kriteriaBM' => 'Tidak ada mahasiswa yang melakukan diseminasi pada seminar nasional/internasional',
                'kriteriaM' => 'S1: Jumlah mahasiswa yang melakukan diseminasi pada seminar nasional minimal 1 orang
                S2: Jumlah mahasiswa yang melakukan diseminasi pada seminar nasional minimal 2 orang
                S3: Jumlah mahasiswa yang melakukan diseminasi pada seminar internasional minimal 1 orang',
                'kriteriaML' => 'S1: Jumlah mahasiswa yang melakukan diseminasi pada seminar nasional lebih adri 1 orang
                S2: Jumlah mahasiswa yang melakukan diseminasi pada seminar nasional lebih dari 2 orang
                S3: Jumlah mahasiswa yang melakukan diseminasi pada seminar internasional lebih dari 1 orang',
                'jenjangs' => ['S1', 'S2', 'S3', 'Profesi'],
                'jenisPertanyaan' => $jenisPertanyaan['number']
            ],
            [
                'pernyataan' => 'Jumlah tulisan mahasiswa pada media massa',
                'indikator' => 'Jumlah tulisan mahasiswa pada media massa',
                'kriteriaBM' => 'Tidak ada',
                'kriteriaM' => 'Menulis pada media massa = 1 ',
                'kriteriaML' => 'Menulis pada media massa >1 ',
                'jenjangs' => ['S1', 'S2', 'S3', 'Profesi'],
                'jenisPertanyaan' => $jenisPertanyaan['number']
            ],
            [
                'pernyataan' => 'Jumlah Luaran mahasiswa dalam bentuk HAKI',
                'indikator' => 'Jumlah Luaran mahasiswa dalam bentuk HAKI',
                'kriteriaBM' => 'Tidak ada',
                'kriteriaM' => 'Luaran mahasiswa dalam bentuk HAKI = 1 buah ',
                'kriteriaML' => 'Luaran mahasiswa dalam bentuk HAKI > 1 buah',
                'jenjangs' => ['S1', 'S2', 'S3', 'Profesi'],
                'jenisPertanyaan' => $jenisPertanyaan['number']
            ],
            [
                'pernyataan' => 'Jumlah Luaran mahasiswa dalam bentuk book chapter.',
                'indikator' => 'Jumlah Luaran mahasiswa dalam bentuk book chapter.',
                'kriteriaBM' => 'Tidak ada',
                'kriteriaM' => 'Luaran mahasiswa dalam bentuk book chapter = 1 ',
                'kriteriaML' => 'Luaran mahasiswa dalam bentuk book chapter > 1 ',
                'jenjangs' => ['S1', 'S2', 'S3', 'Profesi'],
                'jenisPertanyaan' => $jenisPertanyaan['number']
            ],
            [
                'pernyataan' => 'Jumlah Luaran mahasiswa dalam bentuk buku.',
                'indikator' => 'Jumlah Luaran mahasiswa dalam bentuk buku.',
                'kriteriaBM' => 'Tidak ada',
                'kriteriaM' => 'Luaran mahasiswa dalam bentuk buku = 1 ',
                'kriteriaML' => 'Luaran mahasiswa dalam bentuk buku > 1 ',
                'jenjangs' => ['S1', 'S2', 'S3', 'Profesi'],
                'jenisPertanyaan' => $jenisPertanyaan['number']
            ],
            [
                'pernyataan' => 'Presentase Kelulusan pada first taker (PFT) pada pengujian kompetensi nasional?',
                'indikator' => 'Presentase Kelulusan pada first taker (PFT) pada pengujian kompetensi nasional?',
                'kriteriaBM' => 'Kurang dari 65%',
                'kriteriaM' => 'Prosentase Kelulusan pada first taker (PFT) pada pengujian kompetensi nasional (LAM PT Kes) = 65%',
                'kriteriaML' => 'Lebih dari 65%',
                'prodis' => ['Pendidikan Profesi Ners', 'Pendidikan Profesi Dokter Gigi'],
                'jenisPertanyaan' => $jenisPertanyaan['number']
            ],
        ];

        foreach ($instrumenDataPDL as $data) {
            $existingInstrumenCount = Instrumen::where('kategori_id', $kategori['PDL']->id)->count();

            $instrumen = Instrumen::create([
                'peraturan_id' => $peraturan->id,
                'standar_id' => $standar['Pendidikan']->id,
                'kategori_id' => $kategori['PDL']->id,
                'level_id' => $levelPs->id,
                'jenis_pertanyaan_id' => $data['jenisPertanyaan'],
                'pernyataan' => $data['pernyataan'],
                'indikator' => $data['indikator'],

            ]);

            $kode = $standar['Pendidikan']->kode . $kategori['PDL']->kode . '-' . ($existingInstrumenCount + 1);

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

        // PS Standar Pendidikan Proses
        $instrumenDataPDP = [
            [
                'pernyataan' => 'Program studi telah memiliki RPS untuk semua mata kuliah',
                'indikator' => 'Program studi telah memiliki RPS untuk semua mata kuliah',
                'kriteriaBM' => 'PS belum memiliki RPS untuk semua mata kuliah (100%)',
                'kriteriaM' => 'Ketersediaan RPS pada semua mk (100%)',
                'kriteriaML' => 'PS sudah memiliki RPS untuk semua mata kuliah (100%) dan telah melakukan pembaharuan pada tahun ajaran terakhir',
                'jenjangs' => ['D3', 'S1', 'S2', 'S3', 'Profesi'],
                'jenisPertanyaan' => $jenisPertanyaan['text']
            ],
            [
                'pernyataan' => 'Dokumen RPS yang dimiliki PS telah mencakup unsur perencanaan, pelaksanaan dan penilaian pada proses pembelajaran.',
                'indikator' => 'Dokumen RPS yang dimiliki PS telah mencakup unsur perencanaan, pelaksanaan dan penilaian pada proses pembelajaran.',
                'kriteriaBM' => 'RPS yang dimiliki PS belum mencakup keseluruhan aspek: perencanaan, pelaksanaan dan penilaian proses pembelajaran',
                'kriteriaM' => 'Dokumen Rencana Pembelajaran Semester (RPS) mata kuliah mencakup tiga aspek: a. perencanaan proses pembelajaran; b. pelaksanaan proses pembelajaran; dan c. penilaian proses pembelajaran.',
                'kriteriaML' => 'RPS yang dimiliki PS telah mencakup unsur perencanaan, pelaksanaan dan penilaian pada proses pembelajaran, memiliki format penulisan seragam dan/atau dilengkapi dengan contoh soal ujian',
                'jenjangs' => ['D3', 'S1', 'S2', 'S3', 'Profesi'],
                'jenisPertanyaan' => $jenisPertanyaan['text']
            ],
            [
                'pernyataan' => 'PS sudah melaksanakan sistem SKS dengan ketentuan 1 SKS setara dengan 45 jam per semester (Permendikbudristek No.53 Tahun 2023).',
                'indikator' => 'PS sudah melaksanakan sistem SKS dengan ketentuan 1 SKS setara dengan 45 jam per semester (Permendikbudristek No.53 Tahun 2023).',
                'kriteriaBM' => 'PS belum melaksanakan sistem SKS dengan ketentuan 1 SKS setara dengan 45 jam per semester',
                'kriteriaM' => 'Proses pembelajaran dilaksanakan dengan sistem SKS dengan ketentuan 1 SKS setara dengan 45 jam per semester',
                'kriteriaML' => '-',
                'jenjangs' => ['D3', 'S1', 'S2', 'S3', 'Profesi'],
                'jenisPertanyaan' => $jenisPertanyaan['text']
            ],
            [
                'pernyataan' => 'PS telah melaksanakan pemenuhan beban belajar dalam bentuk kuliah, responsi, tutorial, seminar, praktikum, praktik, studio, penelitian, perancangan, pengembangan, tugas akhir, pelatihan bela negara, pertukaran pelajar, magang, wirausaha, pengabdian kepada masyarakat, dan/atau bentuk pembelajaran lain.
                Bentuk pembelajaran melalui kegiatan belajar terbimbing, penugasan terstruktur, dan/atau mandiri.',
                'indikator' => 'PS telah melaksanakan pemenuhan beban belajar dalam bentuk kuliah, responsi, tutorial, seminar, praktikum, praktik, studio, penelitian, perancangan, pengembangan, tugas akhir, pelatihan bela negara, pertukaran pelajar, magang, wirausaha, pengabdian kepada masyarakat, dan/atau bentuk pembelajaran lain.
                Bentuk pembelajaran melalui kegiatan belajar terbimbing, penugasan terstruktur, dan/atau mandiri.',
                'kriteriaBM' => 'PS melaksanakan pemenuhan beban belajar dalam bentuk kuliah.',
                'kriteriaM' => 'Pemenuhan beban belajar berupa: bentuk kuliah, responsi, tutorial, seminar, praktikum, praktik, studio, penelitian, perancangan, pengembangan, tugas akhir, pelatihan bela negara, pertukaran pelajar, magang, wirausaha, pengabdian kepada masyarakat, dan/atau bentuk pembelajaran lain. Bentuk pembelajaran melalui kegiatan belajar terbimbing, penugasan terstruktur, dan/atau mandiri.',
                'kriteriaML' => 'PS melakukan monitoring, evaluasi dan menindaklanjuti pemenuhan beban belajar.',
                'jenjangs' => ['D3', 'S1', 'S2', 'S3', 'Profesi'],
                'jenisPertanyaan' => $jenisPertanyaan['text']
            ],
            [
                'pernyataan' => 'Persentase mata kuliah yang telah melaksanakan penilaian dan hasil evaluasi proses pembelajaran dengan mengikuti pedoman penilaian dan hasil evaluasi proses pembelajaran yang valid, reliabel, transparan, akuntabel, berkeadilan, objektif, dan edukatif.
                Penjelasan: a. Valid atau sahih menunjukkan penilaian mencerminkan kemampuan mahasiswa yang diukur (soal ujian sesuai dengan kisi-kisi materi dan indikator pencapaian kompetensi). b. Reliabel atau andal yaitu ketika soal ujian digunakan berulang-ulang, hasilnya selalu konsisten. c. Transparan atau terbuka yaitu prosedur penilaian, kriteria penilaian, dan dasar pengambilan keputusan hasil penilaian dapat diketahui oleh pihak yang berkepentingan. d. Akuntabel yaitu penilaian dapat dipertanggungjawabkan, baik dari segi mekanisme, prosedur, teknik, maupun hasilnya. e. Berkeadilan yaitu penilaian tidak menguntungkan atau merugikan peserta didik dari perbedaan latar belakang agama, suku, budaya, adat istiadat, status sosial ekonomi, gender dan mahasiswa berkebutuhan khusus. f. Objektif berdasarkan pada standar yang jelas dan bebas dari pengaruh subjektivitas penilai. g. Edukatif yaitu penilaian dapat memotivasi mahasiswa agar mampu memperbaiki kinerja belajar dan meraih prestasi belajar yang lebih tinggi.
                ',
                'indikator' => 'Persentase mata kuliah yang telah melaksanakan penilaian dan hasil evaluasi proses pembelajaran dengan mengikuti pedoman penilaian dan hasil evaluasi proses pembelajaran yang valid, reliabel, transparan, akuntabel, berkeadilan, objektif, dan edukatif.
                Penjelasan: a. Valid atau sahih menunjukkan penilaian mencerminkan kemampuan mahasiswa yang diukur (soal ujian sesuai dengan kisi-kisi materi dan indikator pencapaian kompetensi). b. Reliabel atau andal yaitu ketika soal ujian digunakan berulang-ulang, hasilnya selalu konsisten. c. Transparan atau terbuka yaitu prosedur penilaian, kriteria penilaian, dan dasar pengambilan keputusan hasil penilaian dapat diketahui oleh pihak yang berkepentingan. d. Akuntabel yaitu penilaian dapat dipertanggungjawabkan, baik dari segi mekanisme, prosedur, teknik, maupun hasilnya. e. Berkeadilan yaitu penilaian tidak menguntungkan atau merugikan peserta didik dari perbedaan latar belakang agama, suku, budaya, adat istiadat, status sosial ekonomi, gender dan mahasiswa berkebutuhan khusus. f. Objektif berdasarkan pada standar yang jelas dan bebas dari pengaruh subjektivitas penilai. g. Edukatif yaitu penilaian dapat memotivasi mahasiswa agar mampu memperbaiki kinerja belajar dan meraih prestasi belajar yang lebih tinggi.',
                'kriteriaBM' => 'Belum 100% Mata Kuliah melaksanakan penilaian dan hasil evaluasi proses pembelajaran dengan mengikuti pedoman penilaian dan hasil evaluasi proses pembelajaran yang valid, reliabel, transparan, akuntabel, berkeadilan, objektif, dan edukatif',
                'kriteriaM' => '100% Mata Kuliah melaksanakan penilaian dan hasil evaluasi proses pembelajaran dengan mengikuti pedoman penilaian dan hasil evaluasi proses pembelajaran yang valid, reliabel, transparan, akuntabel, berkeadilan, objektif, dan edukatif',
                'kriteriaML' => 'PS melakukan monitoring, evaluasi dan menindaklanjuti pemenuhan pelaksanaan penilaian hasil evaluasi proses pembelajaran',
                'jenjangs' => ['D3', 'S1', 'S2', 'S3', 'Profesi'],
                'jenisPertanyaan' => $jenisPertanyaan['number']
            ],
            [
                'pernyataan' => 'Persentase mata kuliah yang melakukan penilaian hasil belajar mahasiswa dengan bentuk penilaian formatif dan sumatif.
                Penjelasan: a. Penilaian Formatif: Memberikan tugas berupa projek kepada siswa, baik secara individu maupun kelompok. Setelah tugas selesai, dosen memberikan tanggapan dan saran untuk perbaikan. b. Penilaian Sumatif: Melakukan kuis atau ujian harian untuk mengukur pemahaman siswa terhadap materi yang sudah disampaikan.',
                'indikator' => 'Persentase mata kuliah yang melakukan penilaian hasil belajar mahasiswa dengan bentuk penilaian formatif dan sumatif.
                Penjelasan: a. Penilaian Formatif: Memberikan tugas berupa projek kepada siswa, baik secara individu maupun kelompok. Setelah tugas selesai, dosen memberikan tanggapan dan saran untuk perbaikan. b. Penilaian Sumatif: Melakukan kuis atau ujian harian untuk mengukur pemahaman siswa terhadap materi yang sudah disampaikan.',
                'kriteriaBM' => 'Kurang dari 100% mata kuliah.',
                'kriteriaM' => '100% Mata Kuliah melakukan penilaian hasil belajar mahasiswa dalam bentuk penilaian formatif dan penilaian sumatif.',
                'kriteriaML' => 'PS melakukan monitoring, evaluasi dan menindaklanjuti pelaksanaan penilaian belajar.',
                'jenjangs' => ['D3', 'S1', 'S2', 'S3', 'Profesi'],
                'jenisPertanyaan' => $jenisPertanyaan['number']
            ],
            [
                'pernyataan' => 'Persentase mata kuliah yang melakukan sosialisasi mekanisme penilaian kepada mahasiswa.',
                'indikator' => 'Persentase mata kuliah yang melakukan sosialisasi mekanisme penilaian kepada mahasiswa.',
                'kriteriaBM' => '<100% Mata Kuliah yang melakukan sosialisasi mekanisme penilaian kepada mahasiswa.',
                'kriteriaM' => '100% Mata Kuliah yang melakukan sosialisasi mekanisme penilaian kepada mahasiswa dan tersedia rubrik penilaian di RPS.',
                'kriteriaML' => '100% Mata Kuliah yang melakukan sosialisasi mekanisme penilaian kepada mahasiswa dan tersedia rubrik penilaian di RPS',
                'jenjangs' => ['D3', 'S1', 'S2', 'S3', 'Profesi'],
                'jenisPertanyaan' => $jenisPertanyaan['number']
            ],
            [
                'pernyataan' => 'Tersedia dokumen bukti sosialisasi mekanisme penilaian kepada mahasiswa. (misalnya dalam kontrak pembelajaran yang menjelaskan mekanisme penilaian).',
                'indikator' => 'Tersedia dokumen bukti sosialisasi mekanisme penilaian kepada mahasiswa. (misalnya dalam kontrak pembelajaran yang menjelaskan mekanisme penilaian).',
                'kriteriaBM' => 'Tidak tersedia dokumen bukti sosialisasi mekanisme penilaian kepada mahasiswa (misalnya dalam kontrak pembelajaran yang menjelaskan mekanisme penilaian).',
                'kriteriaM' => 'Tersedia dokumen bukti sosialisasi mekanisme penilaian kepada mahasiswa (misalnya dalam kontrak pembelajaran yang menjelaskan mekanisme penilaian).',
                'kriteriaML' => 'Bukti sosialisasi mekanisme penilaian kepada mahasiswa (misalnya dalam kontrak pembelajaran yang menjelaskan mekanisme penilaian) setiap semester terdokumentasi secara konsisten dan mudah diakses',
                'jenjangs' => ['D3', 'S1', 'S2', 'S3', 'Profesi'],
                'jenisPertanyaan' => $jenisPertanyaan['text']
            ],
            [
                'pernyataan' => 'Program doktor telah mewajibkan pelibatan penguji dari luar perguruan tinggi.',
                'indikator' => 'Program doktor telah mewajibkan pelibatan penguji dari luar perguruan tinggi.',
                'kriteriaBM' => 'Program doktor belum mewajibkan pelibatan penguji dari luar perguruan tinggi.',
                'kriteriaM' => 'Program doktor mewajibkan pelibatan penguji dari luar perguruan tinggi.',
                'kriteriaML' => 'Program doktor mewajibkan pelibatan penguji dari luar perguruan tinggi berdasarkan standar kualifikasi penguji eksternal.',
                'jenjangs' => ['S3'],
                'jenisPertanyaan' => $jenisPertanyaan['text']
            ],
            [
                'pernyataan' => 'Tersedia dokumen kurikulum PS yang mencakup materi pembelajaran mengacu pada capaian pembelajaran lulusan dengan tingkat kedalaman dan keluasan sesuai jenis dan program pendidikan, serta standar kompetensi lulusan, dengan memperhatikan perkembangan IPTEK terkini dan relevansinya dengan dunia kerja.',
                'indikator' => 'Tersedia dokumen kurikulum PS yang mencakup materi pembelajaran mengacu pada capaian pembelajaran lulusan dengan tingkat kedalaman dan keluasan sesuai jenis dan program pendidikan, serta standar kompetensi lulusan, dengan memperhatikan perkembangan IPTEK terkini dan relevansinya dengan dunia kerja.',
                'kriteriaBM' => 'Tidak tersedia dokumen kurikulum PS yang mencakup materi pembelajaran mengacu pada capaian pembelajaran lulusan dengan tingkat kedalaman dan keluasan sesuai jenis dan program pendidikan, serta standar kompetensi lulusan, dengan memperhatikan perkembangan IPTEK terkini dan relevansinya dengan dunia kerja.',
                'kriteriaM' => 'Tersedia dokumen kurikulum PS yang mencakup materi pembelajaran mengacu pada capaian pembelajaran lulusan dengan tingkat kedalaman dan keluasan sesuai jenis dan program pendidikan, serta standar kompetensi lulusan, dengan memperhatikan perkembangan IPTEK terkini dan relevansinya dengan dunia kerja.',
                'kriteriaML' => 'Kurikulum PS berbasis Outcome (OBE) dan dievaluasi secara rutin dan terdokumentasi dengan baik.',
                'jenjangs' => ['D3', 'S1', 'S2', 'S3', 'Profesi'],
                'jenisPertanyaan' => $jenisPertanyaan['text']
            ],
            [
                'pernyataan' => 'Tersedia dokumen RPS yang mencakup materi pembelajaran dalam bentuk matakuliah, modul, blok tematik atau bentuk lainnya, dan dapat diperkaya dengan program kompetensi mikro pada semua mata kuliah.',
                'indikator' => 'Tersedia dokumen RPS yang mencakup materi pembelajaran dalam bentuk matakuliah, modul, blok tematik atau bentuk lainnya, dan dapat diperkaya dengan program kompetensi mikro pada semua mata kuliah.',
                'kriteriaBM' => 'Tidak tersedia dokumen RPS yang mencakup materi pembelajaran dalam bentuk mata kuliah, modul, blok tematik atau bentuk lainnya, dan dapat diperkaya dengan program kompetensi mikro pada semua mata kuliah',
                'kriteriaM' => 'Tersedia dokumen RPS yang mencakup materi pembelajaran dalam bentuk matakuliah, modul, blok tematik atau bentuk lainnya, dan dapat diperkaya dengan program kompetensi mikro pada semua mata kuliah',
                'kriteriaML' => 'RPS dievaluasi secara secara konsisten dan dapat diakses mahasiswa di Eldiru.',
                'jenjangs' => ['D3', 'S1', 'S2', 'S3', 'Profesi'],
                'jenisPertanyaan' => $jenisPertanyaan['text']
            ],
            [
                'pernyataan' => 'Tersedia dokumen kurikulum PS yang mencakup CPL, masa tempuh kurikulum, metode pembelajaran, modalitas pembelajaran, syarat kompetensi dan/atau kualifikasi calon mahasiswa, penilaian hasil belajar, dan materi pembelajaran, serta tata cara penerimaan mahasiswa pada berbagai tahapan kurikulum.',
                'indikator' => 'Tersedia dokumen kurikulum PS yang mencakup CPL, masa tempuh kurikulum, metode pembelajaran, modalitas pembelajaran, syarat kompetensi dan/atau kualifikasi calon mahasiswa, penilaian hasil belajar, dan materi pembelajaran, serta tata cara penerimaan mahasiswa pada berbagai tahapan kurikulum.',
                'kriteriaBM' => 'Tidak tersedia dokumen kurikulum PS yang mencakup CPL, masa tempuh kurikulum, metode pembelajaran, modalitas pembelajaran, syarat kompetensi dan/atau kualifikasi calon mahasiswa, penilaian hasil belajar, dan materi pembelajaran, serta tata cara penerimaan mahasiswa pada berbagai tahapan kurikulum.',
                'kriteriaM' => 'Tersedia dokumen kurikulum PS yang mencakup CPL, masa tempuh kurikulum, metode pembelajaran, modalitas pembelajaran, syarat kompetensi dan/atau kualifikasi calon mahasiswa, penilaian hasil belajar, dan materi pembelajaran, serta tata cara penerimaan mahasiswa pada berbagai tahapan kurikulum.',
                'kriteriaML' => 'Kurikulum PS  dievaluasi secara konsisten,  terdokumentasi   lengkap dan transparan dalam web prodi. ',
                'jenjangs' => ['D3', 'S1', 'S2', 'S3', 'Profesi'],
                'jenisPertanyaan' => $jenisPertanyaan['text']
            ],
            [
                'pernyataan' => 'Tersedia dokumen kurikulum PS Vokasi yang menerapkan kurikulum sistem ganda yang memfasilitasi mahasiswa untuk melaksanakan pembelajaran gabungan di perguruan tinggi dengan magang di dunia kerja/usaha/industri dan/atau dalam bentuk teaching industry.',
                'indikator' => 'Tersedia dokumen kurikulum PS Vokasi yang menerapkan kurikulum sistem ganda yang memfasilitasi mahasiswa untuk melaksanakan pembelajaran gabungan di perguruan tinggi dengan magang di dunia kerja/usaha/industri dan/atau dalam bentuk teaching industry.',
                'kriteriaBM' => 'Tidak tersedia dokumen kurikulum PS Vokasi yang menerapkan kurikulum sistem ganda yang memfasilitasi mahasiswa untuk melaksanakan pembelajaran gabungan di perguruan tinggi dengan magang di dunia kerja/usaha/industri dan/atau dalam bentuk teaching industry',
                'kriteriaM' => 'Tersedia dokumen kurikulum PS Vokasi yang menerapkan kurikulum sistem ganda yang memfasilitasi mahasiswa untuk melaksanakan pembelajaran gabungan di perguruan tinggi dengan magang di dunia kerja/usaha/industri dan/atau dalam bentuk teaching industry',
                'kriteriaML' => 'Kurikulum dievaluasi secara konsisten, terdokumentasi   lengkap dan transparan dalam web fakultas',
                'jenjangs' => ['D3'],
                'jenisPertanyaan' => $jenisPertanyaan['text']
            ],

        ];


        foreach ($instrumenDataPDP as $data) {
            $existingInstrumenCount = Instrumen::where('kategori_id', $kategori['PDP']->id)->count();
            $instrumen = Instrumen::create([
                'peraturan_id' => $peraturan->id,
                'standar_id' => $standar['Pendidikan']->id,
                'kategori_id' => $kategori['PDP']->id,
                'level_id' => $levelPs->id,
                'jenis_pertanyaan_id' => $data['jenisPertanyaan'],
                'pernyataan' => $data['pernyataan'],
                'indikator' => $data['indikator'],

            ]);

            $kode = $standar['Pendidikan']->kode . $kategori['PDP']->kode . '-' . ($existingInstrumenCount + 1);

            $instrumen->kode = $kode;
            $instrumen->save();

            $kriteriaBM->instrumen()->attach($instrumen->id, ['isi' => $data['kriteriaBM']]);
            $kriteriaM->instrumen()->attach($instrumen->id, ['isi' => $data['kriteriaM']]);
            $kriteriaML->instrumen()->attach($instrumen->id, ['isi' => $data['kriteriaML']]);

            foreach ($data['jenjangs'] as $jenjang) {
                $jenjangs[$jenjang]->instrumen()->attach($instrumen->id);
            }
        }

        // UPPS Standar Pendidikan Luar
        $instrumenDataUppsPDL = [
            [
                'pernyataan' => 'Persentase lulusan tepat waktu untuk Program Diploma 3
            (Merujuk pada Perban PT no 20 tahun 2019)',
                'kriteriaBM' => '< 50%',
                'kriteriaM' => '=  50 %',
                'kriteriaML' => '> 50 %',
                'jabatans' => ['Dekan', 'WD I'],
                'jenisPertanyaan' => $jenisPertanyaan['number']
            ],
            [
                'pernyataan' => 'Persentase lulusan tepat waktu untuk Program Sarjana: (Merujuk pada LAM)',
                'kriteriaBM' => '< 80%',
                'kriteriaM' => '=  80 %',
                'kriteriaML' => '> 80 %',
                'jabatans' => ['Dekan', 'WD I'],
                'jenisPertanyaan' => $jenisPertanyaan['number']
            ],
            [
                'pernyataan' => 'Persentase lulusan tepat waktu untuk Program Profesi. (Merujuk pada LAM)',
                'kriteriaBM' => '< 75%',
                'kriteriaM' => '=  75 %',
                'kriteriaML' => '> 75 %',
                'jabatans' => ['Dekan', 'WD I'],
                'jenisPertanyaan' => $jenisPertanyaan['number']
            ],
            [
                'pernyataan' => 'Persentase lulusan tepat waktu untuk Program Magister Merujuk pada LAM',
                'kriteriaBM' => '< 80%',
                'kriteriaM' => '=  80 %',
                'kriteriaML' => '> 80 %',
                'jabatans' => ['Dekan', 'WD I'],
                'jenisPertanyaan' => $jenisPertanyaan['number']
            ],
            [
                'pernyataan' => 'Persentase lulusan tepat waktu untuk Program Doktor (Merujuk pada Perban PT no 20 tahun 2019)',
                'kriteriaBM' => '< 50%',
                'kriteriaM' => '=  50 %',
                'kriteriaML' => '> 50 %',
                'jabatans' => ['Dekan', 'WD I'],
                'jenisPertanyaan' => $jenisPertanyaan['number']
            ],
            [
                'pernyataan' => 'UPPS melakukan pengukuran kepuasan pengguna lulusan yang mencakup 5 aspek sebagai berikut: 
                a.	Pelaksanaan tracer study terkoordinasi di tingkat PT,
                b.	Kegiatan tracer study dilakukan secara reguler setiap tahun dan terdokumentasi,
                c.	Isi kuesioner mencakup seluruh pertanyaan inti tracer study DIKTI.
                d.	Ditargetkan pada seluruh populasi ( untuk sarjana :lulusan TS-4 s.d. TS-2; untuk diploma TS-3 sd TS-2; untuk magister; TS-4-TS-2. untuk profesi :  TS-1……; untuk doktor  TS-4-TS-2 ),
                e.	Hasilnya disosialisasikan dan digunakan untuk pengembangan kurikulum dan pembelajaran.',
                'kriteriaBM' => 'Pelaksanaan tracer studi  mencakup < 5 aspek',
                'kriteriaM' => 'Pelaksanaan tracer studi  mencakup 5 aspek',
                'kriteriaML' => '-',
                'jabatans' => ['Dekan', 'WD III'],
                'jenisPertanyaan' => $jenisPertanyaan['text']
            ],
        ];

        foreach ($instrumenDataUppsPDL as $data) {
            $existingInstrumenCount = Instrumen::where('kategori_id', $kategori['PDL']->id)->count();
            $instrumen = Instrumen::create([
                'peraturan_id' => $peraturan->id,
                'standar_id' => $standar['Pendidikan']->id,
                'kategori_id' => $kategori['PDL']->id,
                'level_id' => $level['upps']->id,
                'jenis_pertanyaan_id' => $data['jenisPertanyaan'],
                'pernyataan' => $data['pernyataan'],
                'indikator' => $data['pernyataan'],

            ]);

            $kode = $standar['Pendidikan']->kode . $kategori['PDL']->kode . '-' . ($existingInstrumenCount + 1);

            $instrumen->kode = $kode;
            $instrumen->save();

            $kriteriaBM->instrumen()->attach($instrumen->id, ['isi' => $data['kriteriaBM']]);
            $kriteriaM->instrumen()->attach($instrumen->id, ['isi' => $data['kriteriaM']]);
            $kriteriaML->instrumen()->attach($instrumen->id, ['isi' => $data['kriteriaML']]);

            foreach ($data['jabatans'] as $jabatan) {
                $jabatanUpps[$jabatan]->instrumen()->attach($instrumen->id);
            }
        }

        // UPPS Standar Pendidikan Proses
        $instrumenDataUppsPDP = [
            [
                'pernyataan' => 'Tersedia dokumen Rencana Pembelajaran Semester (RPS) mata kuliah yang mencakup tiga aspek:
                a.	perencanaan proses pembelajaran;
                b.	pelaksanaan proses pembelajaran; dan
                c.	penilaian proses pembelajaran.',
                'kriteriaBM' => 'Tersedia dokumen Rencana Pembelajaran Semester (RPS) mata kuliah yang mencakup kurang dari tiga aspek:
                a.	perencanaan proses pembelajaran;
                b.	pelaksanaan proses pembelajaran; dan
                c.	penilaian proses pembelajaran.',
                'kriteriaM' => 'Tersedia dokumen Rencana Pembelajaran Semester (RPS) mata kuliah yang mencakup tiga aspek:
                a.	perencanaan proses pembelajaran;
                b.	pelaksanaan proses pembelajaran; dan
                c.	penilaian proses pembelajaran.',
                'kriteriaML' => 'Tersedianya dokumen  Rencana Pembelajaran Semester (RPS)  yang mencakup lebih dari 3 aspek antara lain target capaian pembelajaran, bahan kajian, metode pembelajaran,  waktu dan tahapan asesmen, hasil capaian pembelajaran, disosialisasikan, ditinjau dan disesuaikan secara berkala dan dapat diakses oleh mahasiswa',
                'jabatans' => ['Dekan', 'WD I'],
                'jenisPertanyaan' => $jenisPertanyaan['text']
            ],
            [
                'pernyataan' => 'Tersedia dokumen hasil survey kepuasan mahasiswa terhadap penyelenggaraan pembelajaran berdasarkan 4 aspek:
                a.	menciptakan suasana belajar yang menyenangkan, inklusif, kolaboratif, kreatif, dan efektif;
                b.	memberikan kesempatan belajar yang sama tanpa membedakan latar belakang pendidikan, sosial, ekonomi, budaya, bahasa, jalur penerimaan mahasiswa, dan kebutuhan khusus mahasiswa;
                c.	menjamin keamanan, kenyamanan, dan kesejahteraan hidup sivitas akademika;
                d.	memberikan fleksibilitas dalam proses pendidikan untuk memfasilitasi pendidikan berkelanjutan sepanjang hayat.',
                'kriteriaBM' => 'Tersedia dokumen hasil survey kepuasan mahasiswa terhadap penyelenggaraan pembelajaran <4 aspek',
                'kriteriaM' => 'Tersedia dokumen hasil survey kepuasan mahasiswa terhadap penyelenggaraan pembelajaran berdasarkan 4 aspek',
                'kriteriaML' => 'Tersedia dokumen hasil survey kepuasan mahasiswa terhadap penyelenggaraan pembelajaran berdasarkan 4   aspek dan disosialisasikan melalui berbagai media sosial yang dimiliki UPPS',
                'jabatans' => ['Dekan', 'WD I'],
                'jenisPertanyaan' => $jenisPertanyaan['text']
            ],
            [
                'pernyataan' => 'Tersedia dokumen penetapan beban belajar dalam sistem blok, modul, atau bentuk lain sesuai kebutuhan pemenuhan capaian pembelajaran lulusan',
                'kriteriaBM' => 'Tidak tersedia dokumen penetapan beban belajar dalam sistem blok, modul, atau bentuk lain sesuai kebutuhan pemenuhan capaian pembelajaran lulusan.',
                'kriteriaM' => 'Tersedia dokumen penetapan beban belajar dalam sistem blok, modul, atau bentuk lain sesuai kebutuhan pemenuhan capaian pembelajaran lulusan.',
                'kriteriaML' => 'Tersedia dokumen penetapan beban belajar dalam sistem blok, modul, atau bentuk lain sesuai kebutuhan pemenuhan capaian pembelajaran lulusan yang disosialisasikan dan ditinjau secara berkala.',
                'jabatans' => ['Dekan', 'WD I'],
                'jenisPertanyaan' => $jenisPertanyaan['text']
            ],
            [
                'pernyataan' => 'Tersedia dokumen kebijakan MBKM',
                'kriteriaBM' => 'Tidak tersedia tidak tersedia dokumen kebijakan MBKM ',
                'kriteriaM' => 'Tersedia dokumen kebijakan MBKM yang meliputi dokumen pada level universitas ',
                'kriteriaML' => 'Tersedia dokumen kebijakan MBKM yang meliputi dokumen pada level universitas dan UPPS yang disosialisasikan  media sosial fakultas/prodi  dan ditinjau secara berkala',
                'jabatans' => ['Dekan', 'WD I'],
                'jenisPertanyaan' => $jenisPertanyaan['text']
            ],
            [
                'pernyataan' => 'Tersedia dokumen kebijakan penetapan beban belajar, masa tempuh kurikulum, dan distribusi beban belajar pada program penuh waktu dan paruh waktu pada seluruh program studi',
                'kriteriaBM' => 'Tidak tersedia dokumen kebijakan penetapan beban belajar, masa tempuh kurikulum, dan distribusi beban belajar pada program penuh waktu dan paruh waktu pada seluruh program studi',
                'kriteriaM' => 'Tersedia dokumen kebijakan penetapan beban belajar, masa tempuh kurikulum, dan distribusi beban belajar pada program penuh waktu dan paruh waktu pada seluruh  program studi',
                'kriteriaML' => 'Tersedia dokumen kebijakan penetapan beban belajar, masa tempuh kurikulum, dan distribusi beban belajar pada program penuh waktu dan paruh waktu pada seluruh  program studi yang disosialisasikan dan direview secara berkala (4 tahun minimal untuk S1 dan 2 tahun minimal untuk S2)',
                'jabatans' => ['Dekan', 'WD I'],
                'jenisPertanyaan' => $jenisPertanyaan['text']
            ],
            [
                'pernyataan' => 'Tersedia dokumen kebijakan program khusus seperti
                percepatan, transfer kredit, perolehan kredit, double degree, atau
                micro-credentials',
                'kriteriaBM' => 'Tidak tersedia dokumen kebijakan program khusus seperti
                percepatan, transfer kredit, perolehan kredit, double degree, atau
                micro-credentials',
                'kriteriaM' => 'ersedia dokumen kebijakan program khusus seperti
                percepatan, transfer kredit, perolehan kredit, double degree, atau
                micro-credentials',
                'kriteriaML' => 'Tersedia dokumen kebijakan program khusus seperti
                percepatan, transfer kredit, perolehan kredit, double degree, atau
                micro-credentials yang disosialisasikan dan ditinjau secara berkala.',
                'jabatans' => ['Dekan', 'WD I'],
                'jenisPertanyaan' => $jenisPertanyaan['text']
            ],
            [
                'pernyataan' => 'Program Studi melaksanakan program khusus (percepatan/ transfer
                kredit/ perolehan kredit/ double degree/ micro-credentials)?',
                'kriteriaBM' => 'Program Studi tidak melaksanakan program khusus (percepatan/ transfer kredit/ perolehan kredit/ double degree/ micro-credentials)',
                'kriteriaM' => 'Program Studi melaksanakan program khusus (percepatan/ transfer
                kredit/ perolehan kredit/ double degree/ micro-credentials) dengan mitra dalam negeri',
                'kriteriaML' => 'Program Studi melaksanakan program khusus (percepatan/ transfer
                kredit/ perolehan kredit/ double degree/ micro-credentials) dengan mitra dalam negeri dan mitra luar negeri',
                'jabatans' => ['Dekan', 'WD I'],
                'jenisPertanyaan' => $jenisPertanyaan['text']
            ],
            [
                'pernyataan' => 'Tersedia dokumen kebijakan penetapan kewajiban magang di dunia usaha, dunia industri, dan dunia kerja yang relevan pada program vokasi.',
                'kriteriaBM' => 'Tidak tersedia Dokumen Kebijakan Penetapan kewajiban magang di dunia usaha, dunia industri, dan dunia kerja yang relevan pada program vokasi',
                'kriteriaM' => 'Tersedianya Dokumen Kebijakan Penetapan kewajiban magang di dunia usaha, dunia industri, dan dunia kerja yang relevan pada program vokasi',
                'kriteriaML' => 'Tersedianya Dokumen Kebijakan Penetapan kewajiban magang di dunia usaha, dunia industri, dan dunia kerja yang relevan pada program vokasi dan dievaluasi secara berkala',
                'jabatans' => ['Dekan', 'WD I'],
                'jenisPertanyaan' => $jenisPertanyaan['text']
            ],
            [
                'pernyataan' => 'Tersedia dokumen pedoman tugas akhir yang sesuai dengan program pendidikan (termasuk jenis dan pengakuannya)?',
                'kriteriaBM' => 'Tidak Tersedia Dokumen Pedoman tugas akhir yang sesuai dengan program pendidikan (termasuk jenis dan pengakuannya)',
                'kriteriaM' => 'Tersedianya Dokumen Pedoman tugas akhir yang sesuai dengan program pendidikan (termasuk jenis dan pengakuannya)',
                'kriteriaML' => 'Dokumen Pedoman tugas akhir yang sesuai dengan program pendidikan (termasuk jenis dan pengakuannya) di sosialisasikan yang di media sosial fakultas/Prodi dan di evaluasi secara berkala',
                'jabatans' => ['Dekan', 'WD I'],
                'jenisPertanyaan' => $jenisPertanyaan['text']
            ],
            [
                'pernyataan' => 'Tersedia dokumen hasil evaluasi proses pembelajaran yang memenuhi aspek berikut: (pasal 24 dan 25 PerMen 53 th 2023)
                a.	aktivitas pembelajaran pada setiap angkatan;
                b.	jumlah mahasiswa aktif pada setiap angkatan;
                c.	Masa Tempuh Kurikulum;
                d.	masa penyelesaian studi mahasiswa; dan 
                e.	tingkat serapan lulusan mahasiswa di dunia kerja.',
                'kriteriaBM' => 'Tersedia dokumen Hasil evaluasi proses pembelajaran namun kurang dari 2 (dua) aspek',
                'kriteriaM' => 'Tersedia dokumen Hasil evaluasi proses pembelajaran telah memenuhi 2 (dua) aspek',
                'kriteriaML' => 'Tersedia dokumen Hasil evaluasi proses pembelajaran telah memenuhi lebih dari 2 (dua) aspek',
                'jabatans' => ['Dekan', 'WD I'],
                'jenisPertanyaan' => $jenisPertanyaan['text']
            ],
            [
                'pernyataan' => 'Tersedia pelaporan hasil penilaian sumatif dari masing-masing program studi ke PD Dikti yang paling lambat dilaksanakan 2 bulan setelah akhir semester',
                'kriteriaBM' => 'Tidak dilakukan  Pelaporan hasil penilaian sumatif dari masing-masing program studi ke PD Dikti paling lambat dilaksanakan 2 bulan setelah akhir semester.',
                'kriteriaM' => 'Pelaporan hasil penilaian sumatif dari masing-masing program studi ke PD Dikti paling lambat dilaksanakan 2 bulan setelah akhir semester.',
                'kriteriaML' => 'Pelaporan hasil penilaian sumatif dari masing-masing program studi ke PD Dikti paling lambat dilaksanakan 2 bulan setelah akhir semester dan dilakukan evaluasi  secara berkala terhadap hasil penilaian sumatif.',
                'jabatans' => ['Dekan', 'WD I'],
                'jenisPertanyaan' => $jenisPertanyaan['text']
            ],
            [
                'pernyataan' => 'Tersedia bukti dokumen penetapan syarat dan predikat kelulusan sesuai program Pendidikan',
                'kriteriaBM' => 'Tidak tersedia dokumen penetapan syarat dan predikat kelulusan sesuai program pendidikan.',
                'kriteriaM' => 'Tersedia dokumen penetapan syarat dan predikat kelulusan sesuai program pendidikan.',
                'kriteriaML' => 'Tersedia dokumen penetapan syarat dan predikat kelulusan sesuai program Pendidikan yang disosialisasikan di media sosial fakultas/prodi dan ditinjau secara berkala.',
                'jabatans' => ['Dekan', 'WD I'],
                'jenisPertanyaan' => $jenisPertanyaan['text']
            ],
            [
                'pernyataan' => 'Tersedia bukti dokumen hasil evaluasi penyelenggaraan program pendidikan di fakultas (LAKIP)',
                'kriteriaBM' => 'Tidak tersedia bukti dokumen hasil evaluasi penyelenggaraan program pendidikan di fakultas.',
                'kriteriaM' => 'Tersedia bukti dokumen hasil evaluasi penyelenggaraan program pendidikan di fakultas.',
                'kriteriaML' => 'Tersedia bukti dokumen hasil evaluasi penyelenggaraan program pendidikan di fakultas yang disosialisasikan di media sosial fakultas/prodi dan ditinjau secara berkala.',
                'jabatans' => ['Dekan', 'WD I'],
                'jenisPertanyaan' => $jenisPertanyaan['text']
            ],
            [
                'pernyataan' => 'Tersedia dokumen rencana pengembangan jangka panjang, jangka menengah dan jangka pendek untuk peningkatan proses dan hasil belajar secara berkelanjutan yang dievaluasi secara berkala (Renstra)',
                'kriteriaBM' => 'Tidak tersedia dokumen rencana pengembangan jangka panjang, jangka menengah dan jangka pendek.',
                'kriteriaM' => 'Tersedia dokumen rencana pengembangan jangka panjang, jangka menengah dan jangka pendek.',
                'kriteriaML' => 'Tersedia dokumen rencana pengembangan jangka panjang, jangka menengah dan jangka pendek yang ditindaklanjuti dan disosialisasikan secara berkala di media sosial fakultas.',
                'jabatans' => ['Dekan', 'WD I'],
                'jenisPertanyaan' => $jenisPertanyaan['text']
            ],
            [
                'pernyataan' => 'Tersedia dokumen peraturan tentang etika akademik',
                'kriteriaBM' => 'Tidak tersedia dokumen peraturan tentang etika akademik.',
                'kriteriaM' => 'Tersedia dokumen peraturan tentang etika akademik.',
                'kriteriaML' => 'Tersedia dokumen peraturan tentang etika akademik dan disosialisasikan di media sosial fakultas/prodi.',
                'jabatans' => ['Dekan', 'WD I'],
                'jenisPertanyaan' => $jenisPertanyaan['text']
            ],
            [
                'pernyataan' => 'Tersedia dokumen laporan hasil penegakan etika akademik',
                'kriteriaBM' => 'Tidak tersedia laporan hasil penegakan etika akademik.',
                'kriteriaM' => 'Tersedia dokumen laporan hasil penegakan etika akademik.',
                'kriteriaML' => 'Tersedia dokumen laporan hasil penegakan etika akademik dan dilakukan monitoring dan evaluasi secara berkala.',
                'jabatans' => ['Dekan', 'WD I'],
                'jenisPertanyaan' => $jenisPertanyaan['text']
            ],
            [
                'pernyataan' => 'Tersedia dokumen laporan hasil AMAI bidang akademik dan non akademik',
                'kriteriaBM' => 'Tidak tersedia laporan hasil AMAI bidang akademik dan non akademik.',
                'kriteriaM' => 'Tersedia laporan hasil AMAI bidang akademik dan non akademik.',
                'kriteriaML' => 'Tersedia laporan hasil AMAI bidang akademik dan non akademik dilakukan sosialisasi dan ditindaklanjuti secara berkala.',
                'jabatans' => ['Dekan', 'WD I'],
                'jenisPertanyaan' => $jenisPertanyaan['text']
            ],
        ];

        foreach ($instrumenDataUppsPDP as $data) {
            $existingInstrumenCount = Instrumen::where('kategori_id', $kategori['PDP']->id)->count();
            $instrumen = Instrumen::create([
                'peraturan_id' => $peraturan->id,
                'standar_id' => $standar['Pendidikan']->id,
                'kategori_id' => $kategori['PDP']->id,
                'level_id' => $level['upps']->id,
                'jenis_pertanyaan_id' => $data['jenisPertanyaan'],
                'pernyataan' => $data['pernyataan'],
                'indikator' => $data['pernyataan'],

            ]);

            $kode = $standar['Pendidikan']->kode . $kategori['PDP']->kode . '-' . ($existingInstrumenCount + 1);

            $instrumen->kode = $kode;
            $instrumen->save();

            $kriteriaBM->instrumen()->attach($instrumen->id, ['isi' => $data['kriteriaBM']]);
            $kriteriaM->instrumen()->attach($instrumen->id, ['isi' => $data['kriteriaM']]);
            $kriteriaML->instrumen()->attach($instrumen->id, ['isi' => $data['kriteriaML']]);

            foreach ($data['jabatans'] as $jabatan) {
                $jabatanUpps[$jabatan]->instrumen()->attach($instrumen->id);
            }
        }

        // UPPS Standar Pendidikan Masukan
        $instrumenDataUppsPDM = [
            [
                'pernyataan' => 'Jumlah dosen tersertifikasi dosen (serdos)',
                'kriteriaBM' => '<63%',
                'kriteriaM' => '=63%',
                'kriteriaML' => '>63%',
                'jabatans' => ['Dekan', 'WD II'],
                'jenisPertanyaan' => $jenisPertanyaan['number']
            ],
            [
                'pernyataan' => 'Dosen pada pendidikan vokasi yang berasal dari praktisi dunia usaha, dunia industri, dan dunia kerja.',
                'kriteriaBM' => 'Tidak ada dosen praktisi dari dunia usaha, dunia industri atau dunia kerja',
                'kriteriaM' => 'Terdapat dosen praktisi dari dunia usaha, dunia industri dan dunia kerja minimal satu orang per semester',
                'kriteriaML' => 'Terdapat dosen praktisi dari dunia usaha, dunia industri dan dunia kerja minimal 2 orang per semester',
                'jabatans' => ['Dekan', 'WD I'],
                'jenisPertanyaan' => $jenisPertanyaan['text']
            ],
            [
                'pernyataan' => 'Tenaga kependidikan yang kompeten dengan kualifikasi akademik yang sesuai kebutuhan, tugas dan fungsinya',
                'kriteriaBM' => 'Jumlah tenaga kependidikan kurang dan berpendidikan kurang dari D3',
                'kriteriaM' => 'Semua tenaga pendidikan kompeten dengan pendidikan minimal D3',
                'kriteriaML' => 'Semua tenaga pendidikan kompeten dengan pendidikan minimal S1',
                'jabatans' => ['Dekan', 'WD II'],
                'jenisPertanyaan' => $jenisPertanyaan['text']
            ],
            [
                'pernyataan' => 'Tersedia sarana dan prasarana teknologi informasi dan sarana pembelajaran yang memenuhi 4 kriteria sebagai berikut:
                    1) mengakomodasi kebutuhan pendidikan mahasiswa;
                    2) mengakomodasi pelaksanaan tugas dosen, tutor, instruktur, asisten, dan pembimbing sesuai dengan bidang keahlian dan tenaga kependidikan;
                    3) ramah terhadap mahasiswa, dosen, dan tenaga kependidikan yang berkebutuhan khusus; dan
                    4) memadai untuk menyelenggarakan pendidikan dan manajemen pendidikan tinggi sesuai kebutuhan penyelenggaraan dan rencana pengembangan pendidikan.',
                'kriteriaBM' => 'Tidak tersedia sarana dan prasarana teknologi informasi dan sarana pembelajaran yang memenuhi 4 kriteria',
                'kriteriaM' => 'Tersedia sarana dan prasarana teknologi informasi dan sarana pembelajaran yang memenuhi 4 kriteria.',
                'kriteriaML' => '1) Tersedia data sarana dan prasarana teknologi informasi dan sarana pembelajaran yang memenuhi 4 kriteria 
                                2) Dilakukan monitoring dan evaluasi secara rutin setiap tahun untuk menjamin keberlanjutan',
                'jabatans' => ['Dekan', 'WD II'],
                'jenisPertanyaan' => $jenisPertanyaan['text']
            ],
            [
                'pernyataan' => 'Tersedia sarana dan prasarana yang memenuhi standar K3 (standar keamanan dan keselamatan kerja)',
                'kriteriaBM' => 'Tidak tersedia sarana dan prasarana yang memenuhi standar keamanan dan keselamatan kerja (K3)',
                'kriteriaM' => 'Tersedia sarana dan prasarana yang memenuhi standar keamanan dan keselamatan kerja (K3)',
                'kriteriaML' => 'Tersedia sarana dan prasarana mutakhir yang memenuhi standar keamanan dan keselamatan kerja (K3)  dilakukan monitoring evaluasi secara berkala',
                'jabatans' => ['Dekan', 'WD II'],
                'jenisPertanyaan' => $jenisPertanyaan['text']
            ],
            [
                'pernyataan' => 'Kemudahan akses terhadap sarana dan prasarana yang memenuhi standar K3 (standar keamanan dan keselamatan kerja)',
                'kriteriaBM' => 'Tidak dapat mengakses sarana dan prasarana yang memenuhi standar K3 (standar keamanan dan keselamatan kerja)',
                'kriteriaM' => 'Kemudahan akses sarana dan prasarana yang memenuhi standar K3 (standar keamanan dan keselamatan kerja) dengan tingkat kepuasan minimal 80%',
                'kriteriaML' => 'Kemudahan akses sarana dan prasarana yang memenuhi standar K3 (standar keamanan dan keselamatan kerja) dengan tingkat kepuasan minimal 80% dan dilakukan evaluasi secara rutin setiap tahun untuk menjamin keberlanjutan',
                'jabatans' => ['Dekan', 'WD II'],
                'jenisPertanyaan' => $jenisPertanyaan['text']
            ],

            [
                'pernyataan' => 'Tersedia sumber pembelajaran terbuka (materi pembelajaran, jurnal, buku, LMS) yang dapat diakses dengan mudah',
                'kriteriaBM' => 'Tidak tersedia sumber pembelajaran terbuka (materi pembelajaran, jurnal, buku, LMS) yang dapat diakses dengan mudah',
                'kriteriaM' => 'Tersedia sumber pembelajaran terbuka (materi pembelajaran, jurnal, buku, LMS) yang dapat diakses dengan mudah',
                'kriteriaML' => '1) Tersedia sumber pembelajaran terbuka (materi pembelajaran, jurnal, buku, LMS) yang dapat diakses dengan mudah dan dievaluasi secara rutin setiap tahun
                                2) Tingkat kepuasan terhadap akses sumber pembelajaran terbuka minimal 80%',
                'jabatans' => ['Dekan'],
                'jenisPertanyaan' => $jenisPertanyaan['text']
            ],
            [
                'pernyataan' => 'Tersedia dokumen rancangan anggaran pendapatan dan belanja yang dimiliki oleh UPPS',
                'kriteriaBM' => 'Tidak tersedia dokumen rancangan anggaran pendapatan dan belanja',
                'kriteriaM' => 'Tersedia dokumen rancangan anggaran pendapatan dan belanja',
                'kriteriaML' => 'Tersedia dokumen rancangan anggaran pendapatan dan belanja serta dilakukan monitoring dan evaluasi secara berkala',
                'jabatans' => ['Dekan', 'WD II'],
                'jenisPertanyaan' => $jenisPertanyaan['text']
            ],
        ];

        foreach ($instrumenDataUppsPDM as $data) {
            $existingInstrumenCount = Instrumen::where('kategori_id', $kategori['PDM']->id)->count();
            $instrumen = Instrumen::create([
                'peraturan_id' => $peraturan->id,
                'standar_id' => $standar['Pendidikan']->id,
                'kategori_id' => $kategori['PDM']->id,
                'level_id' => $level['upps']->id,
                'jenis_pertanyaan_id' => $data['jenisPertanyaan'],
                'pernyataan' => $data['pernyataan'],
                'indikator' => $data['pernyataan'],

            ]);

            $kode = $standar['Pendidikan']->kode . $kategori['PDM']->kode . '-' . ($existingInstrumenCount + 1);

            $instrumen->kode = $kode;
            $instrumen->save();

            $kriteriaBM->instrumen()->attach($instrumen->id, ['isi' => $data['kriteriaBM']]);
            $kriteriaM->instrumen()->attach($instrumen->id, ['isi' => $data['kriteriaM']]);
            $kriteriaML->instrumen()->attach($instrumen->id, ['isi' => $data['kriteriaML']]);

            foreach ($data['jabatans'] as $jabatan) {
                $jabatanUpps[$jabatan]->instrumen()->attach($instrumen->id);
            }
        }

        // UPPS PNP
        $instrumenDataUppsPNP = [
            [
                'pernyataan' => 'Hasil penelitian dosen diintegrasikan ke dalam proses pembelajaran.',
                'kriteriaBM' => 'Belum semua hasil penelitian dosen dijadikan materi ajar yang tertuang dalam RPS mata kuliah yang diajarkan.',
                'kriteriaM' => 'Semua hasil penelitian dosen dijadikan materi ajar yang tertuang dalam RPS mata kuliah yang diajarkan.',
                'kriteriaML' => 'Semua hasil penelitian dosen dijadikan materi ajar yang tertuang dalam RPS mata kuliah yang diajarkan dan dilakukan monitoring dan evaluasi secara berkala.',
                'jabatans' => ['Dekan', 'WD I'],
                'jenisPertanyaan' => $jenisPertanyaan['text']
            ],

            [
                'pernyataan' => 'Fakultas memiliki dokumen rencana strategis (renstra) penelitian yang berisi roadmap dan tema penelitian unggulan untuk mendukung pencapaian visi Unsoed.',
                'kriteriaBM' => 'Tidak memiliki dokumen rencana strategis (renstra) penelitian yang berisi roadmap dan tema penelitian unggulan untuk mendukung pencapaian visi Unsoed.',
                'kriteriaM' => 'Memiliki dokumen rencana strategis (renstra) penelitian yang berisi roadmap dan tema penelitian unggulan untuk mendukung pencapaian visi Unsoed.',
                'kriteriaML' => 'Memiliki dokumen rencana strategis (renstra) penelitian yang berisi roadmap dan tema penelitian unggulan untuk mendukung pencapaian visi Unsoed secara berkala.',
                'jabatans' => ['Dekan'],
                'jenisPertanyaan' => $jenisPertanyaan['text']
            ],
            [
                'pernyataan' => 'Dosen melakukan penelitian dengan mengacu pada dokumen rencana strategis (renstra) penelitian.',
                'kriteriaBM' => 'Belum semua dosen melakukan penelitian dengan mengacu pada dokumen rencana strategis (renstra) penelitian.',
                'kriteriaM' => 'Semua dosen melakukan penelitian dengan mengacu pada dokumen rencana strategis (renstra) penelitian.',
                'kriteriaML' => '-',
                'jabatans' => ['Dekan'],
                'jenisPertanyaan' => $jenisPertanyaan['text']
            ],
        ];

        foreach ($instrumenDataUppsPNP as $data) {
            $existingInstrumenCount = Instrumen::where('kategori_id', $kategori['PNP']->id)->count();
            $instrumen = Instrumen::create([
                'peraturan_id' => $peraturan->id,
                'standar_id' => $standar['Penelitian']->id,
                'kategori_id' => $kategori['PNP']->id,
                'level_id' => $level['upps']->id,
                'jenis_pertanyaan_id' => $data['jenisPertanyaan'],
                'pernyataan' => $data['pernyataan'],
                'indikator' => $data['pernyataan'],

            ]);

            $kode = $standar['Penelitian']->kode . $kategori['PNP']->kode . '-' . ($existingInstrumenCount + 1);

            $instrumen->kode = $kode;
            $instrumen->save();

            $kriteriaBM->instrumen()->attach($instrumen->id, ['isi' => $data['kriteriaBM']]);
            $kriteriaM->instrumen()->attach($instrumen->id, ['isi' => $data['kriteriaM']]);
            $kriteriaML->instrumen()->attach($instrumen->id, ['isi' => $data['kriteriaML']]);

            foreach ($data['jabatans'] as $jabatan) {
                $jabatanUpps[$jabatan]->instrumen()->attach($instrumen->id);
            }
        }

        // UPPS PNM
        $instrumenDataUppsPNM = [
            [
                'pernyataan' => 'UPPS memiliki dosen dan/atau peneliti yang tergabung dalam kelompok riset sesuai dengan prioritas/topik penelitian, kualifikasi akademik, dan rekam jejak penelitian.',
                'kriteriaBM' => 'Tidak setiap dosen tergabung dalam kelompok riset sesuai dengan prioritas/topik penelitian, kualifikasi akademik, dan rekam jejak penelitian.',
                'kriteriaM' => 'Setiap dosen tergabung dalam kelompok riset sesuai dengan prioritas/topik penelitian, kualifikasi akademik, dan rekam jejak penelitian.',
                'kriteriaML' => '-',
                'jabatans' => ['Dekan'],
                'jenisPertanyaan' => $jenisPertanyaan['text']
            ],
        ];

        foreach ($instrumenDataUppsPNM as $data) {
            $existingInstrumenCount = Instrumen::where('kategori_id', $kategori['PNM']->id)->count();
            $instrumen = Instrumen::create([
                'peraturan_id' => $peraturan->id,
                'standar_id' => $standar['Penelitian']->id,
                'kategori_id' => $kategori['PNM']->id,
                'level_id' => $level['upps']->id,
                'jenis_pertanyaan_id' => $data['jenisPertanyaan'],
                'pernyataan' => $data['pernyataan'],
                'indikator' => $data['pernyataan'],

            ]);

            $kode = $standar['Penelitian']->kode . $kategori['PNM']->kode . '-' . ($existingInstrumenCount + 1);

            $instrumen->kode = $kode;
            $instrumen->save();

            $kriteriaBM->instrumen()->attach($instrumen->id, ['isi' => $data['kriteriaBM']]);
            $kriteriaM->instrumen()->attach($instrumen->id, ['isi' => $data['kriteriaM']]);
            $kriteriaML->instrumen()->attach($instrumen->id, ['isi' => $data['kriteriaML']]);

            foreach ($data['jabatans'] as $jabatan) {
                $jabatanUpps[$jabatan]->instrumen()->attach($instrumen->id);
            }
        }

        // UPPS PKMP
        $instrumenDataUppsPKMP = [
            [
                'pernyataan' => 'Dosen terlibat dalam kegiatan PkM baik sebagai ketua atau anggota dengan melakukan transfer ilmu dan teknologi kepada Masyarakat.',
                'kriteriaBM' => 'Tidak semua dosen terlibat dalam kegiatan PKM baik sebagai ketua atau anggota dengan melakukan transfer ilmu dan teknologi kepada Masyarakat',
                'kriteriaM' => 'Semua dosen terlibat dalam kegiatan PKM baik sebagai ketua atau anggota dengan melakukan transfer ilmu dan teknologi kepada Masyarakat',
                'kriteriaML' => '-',
                'jabatans' => ['Dekan'],
                'jenisPertanyaan' => $jenisPertanyaan['text']
            ],
            [
                'pernyataan' => 'Dosen melaksanakan kegiatan PkM (Pengabdian Kepada Masyarakat) sesuai dengan roadmap PKM.',
                'kriteriaBM' => 'Belum semua dosen melaksanakan PkM sesuai dengan roadmap Unsoed.',
                'kriteriaM' => 'Semua dosen melaksanan PkM sesuai dengan roadmap Unsoed.',
                'kriteriaML' => 'Semua PkM yang dilaksanakan sesuai dengan roadmap Unsoed dan menghasilkan luaran wajib dan luaran tambahan.',
                'jabatans' => ['Dekan'],
                'jenisPertanyaan' => $jenisPertanyaan['text']
            ]
        ];

        foreach ($instrumenDataUppsPKMP as $data) {
            $existingInstrumenCount = Instrumen::where('kategori_id', $kategori['PKMP']->id)->count();
            $instrumen = Instrumen::create([
                'peraturan_id' => $peraturan->id,
                'standar_id' => $standar['PKM']->id,
                'kategori_id' => $kategori['PKMP']->id,
                'level_id' => $level['upps']->id,
                'jenis_pertanyaan_id' => $data['jenisPertanyaan'],
                'pernyataan' => $data['pernyataan'],
                'indikator' => $data['pernyataan'],

            ]);

            $kode = $standar['PKM']->kode . $kategori['PKMP']->kode . '-' . ($existingInstrumenCount + 1);

            $instrumen->kode = $kode;
            $instrumen->save();

            $kriteriaBM->instrumen()->attach($instrumen->id, ['isi' => $data['kriteriaBM']]);
            $kriteriaM->instrumen()->attach($instrumen->id, ['isi' => $data['kriteriaM']]);
            $kriteriaML->instrumen()->attach($instrumen->id, ['isi' => $data['kriteriaML']]);

            foreach ($data['jabatans'] as $jabatan) {
                $jabatanUpps[$jabatan]->instrumen()->attach($instrumen->id);
            }
        }

        // UPPS PKMM
        $instrumenDataUppsPKMM = [
            [
                'pernyataan' => 'Tersediannya anggaran PkM setiap dosen minimal 5 juta per tahun.',
                'kriteriaBM' => '< 5 juta',
                'kriteriaM' => '= 5 juta',
                'kriteriaML' => '> 5 juta',
                'jabatans' => ['Dekan'],
                'jenisPertanyaan' => $jenisPertanyaan['text']
            ],
        ];

        foreach ($instrumenDataUppsPKMM as $data) {
            $existingInstrumenCount = Instrumen::where('kategori_id', $kategori['PKMM']->id)->count();
            $instrumen = Instrumen::create([
                'peraturan_id' => $peraturan->id,
                'standar_id' => $standar['PKM']->id,
                'kategori_id' => $kategori['PKMM']->id,
                'level_id' => $level['upps']->id,
                'jenis_pertanyaan_id' => $data['jenisPertanyaan'],
                'pernyataan' => $data['pernyataan'],
                'indikator' => $data['pernyataan'],

            ]);

            $kode = $standar['PKM']->kode . $kategori['PKMM']->kode . '-' . ($existingInstrumenCount + 1);

            $instrumen->kode = $kode;
            $instrumen->save();

            $kriteriaBM->instrumen()->attach($instrumen->id, ['isi' => $data['kriteriaBM']]);
            $kriteriaM->instrumen()->attach($instrumen->id, ['isi' => $data['kriteriaM']]);
            $kriteriaML->instrumen()->attach($instrumen->id, ['isi' => $data['kriteriaML']]);

            foreach ($data['jabatans'] as $jabatan) {
                $jabatanUpps[$jabatan]->instrumen()->attach($instrumen->id);
            }
        }

        // UPPS Non Kerjasama
        $instrumenDataUppsNAK = [
            [
                'pernyataan' => 'Terdapat survey untuk mengetahui kepuasan mitra kerjasama yang diukur dengan instrumen yang sahih',
                'kriteriaBM' => 'Tidak dilakukan survey',
                'kriteriaM' => 'Dilakukan survey dan tersedia laporan hasil survey',
                'kriteriaML' => 'Dilakukan survey dan tersedia laporan hasil survey yang ditindaklanjuti',
                'jabatans' => ['Dekan'],
                'jenisPertanyaan' => $jenisPertanyaan['text']
            ]
        ];

        foreach ($instrumenDataUppsNAK as $data) {
            $existingInstrumenCount = Instrumen::where('kategori_id', $kategori['NAK']->id)->count();
            $instrumen = Instrumen::create([
                'peraturan_id' => $peraturan->id,
                'standar_id' => $standar['Non']->id,
                'kategori_id' => $kategori['NAK']->id,
                'level_id' => $level['upps']->id,
                'jenis_pertanyaan_id' => $data['jenisPertanyaan'],
                'pernyataan' => $data['pernyataan'],
                'indikator' => $data['pernyataan'],

            ]);

            $kode = $standar['Non']->kode . $kategori['NAK']->kode . '-' . ($existingInstrumenCount + 1);

            $instrumen->kode = $kode;
            $instrumen->save();

            $kriteriaBM->instrumen()->attach($instrumen->id, ['isi' => $data['kriteriaBM']]);
            $kriteriaM->instrumen()->attach($instrumen->id, ['isi' => $data['kriteriaM']]);
            $kriteriaML->instrumen()->attach($instrumen->id, ['isi' => $data['kriteriaML']]);

            foreach ($data['jabatans'] as $jabatan) {
                $jabatanUpps[$jabatan]->instrumen()->attach($instrumen->id);
            }
        }

        // UPPS Non Tata Kelola
        $instrumenDataUppsNAT = [
            [
                'pernyataan' => 'Terdapat bukti/pengakuan yang sahih bahwa pimpinan UPPS/Prodi memiliki karakter kepemimpinan (kepemimpinan organisasi,  kepemimpinan operasional  dan kepemimpinan publik)',
                'kriteriaBM' => 'Tidak memiliki bukti 3 karakter kepemimpinan (kepemimpinan organisasi,  kepemimpinan operasional  dan kepemimpinan publik) ',
                'kriteriaM' => 'Terdapat bukti/pengakuan  memiliki bukti 3 karakter kepemimpinan (kepemimpinan organisasi,  kepemimpinan operasional  dan kepemimpinan publik)',
                'kriteriaML' => '-',
                'jabatans' => ['Dekan'],
                'jenisPertanyaan' => $jenisPertanyaan['text']
            ],
            [
                'pernyataan' => 'Terdapat bukti/pengakuan kapabilitas pimpinan UPPS/Prodi mencakup 5 aspek:
                1)	kredibel
                2)	transparan
                3)	akuntanbel
                4)	bertanggung jawab
                5)	adil ',
                'kriteriaBM' => 'Tidak memiliki bukti 5 aspek  bukti/pengakuan kapabilitas pimpinan UPPS/Prodi',
                'kriteriaM' => 'Terdapat bukti 5 aspek  bukti/pengakuan kapabilitas pimpinan UPPS/Prodi',
                'kriteriaML' => '-',
                'jabatans' => ['Dekan'],
                'jenisPertanyaan' => $jenisPertanyaan['text']
            ]
        ];

        foreach ($instrumenDataUppsNAT as $data) {
            $existingInstrumenCount = Instrumen::where('kategori_id', $kategori['NAT']->id)->count();
            $instrumen = Instrumen::create([
                'peraturan_id' => $peraturan->id,
                'standar_id' => $standar['Non']->id,
                'kategori_id' => $kategori['NAT']->id,
                'level_id' => $level['upps']->id,
                'jenis_pertanyaan_id' => $data['jenisPertanyaan'],
                'pernyataan' => $data['pernyataan'],
                'indikator' => $data['pernyataan'],

            ]);

            $kode = $standar['Non']->kode . $kategori['NAT']->kode . '-' . ($existingInstrumenCount + 1);

            $instrumen->kode = $kode;
            $instrumen->save();

            $kriteriaBM->instrumen()->attach($instrumen->id, ['isi' => $data['kriteriaBM']]);
            $kriteriaM->instrumen()->attach($instrumen->id, ['isi' => $data['kriteriaM']]);
            $kriteriaML->instrumen()->attach($instrumen->id, ['isi' => $data['kriteriaML']]);

            foreach ($data['jabatans'] as $jabatan) {
                $jabatanUpps[$jabatan]->instrumen()->attach($instrumen->id);
            }
        }

        // UPPS Non Kemahasiswaan = 
        $instrumenDataUppsNAKEM = [
            [
                'pernyataan' => 'Tersedia rencana program kegiatan mahasiswa',
                'kriteriaBM' => 'Tidak tersedia rencana program kegiatan mahasiswa',
                'kriteriaM' => 'Tersedia rencana program kegiatan mahasiswa',
                'kriteriaML' => 'Tersedia rencana program kegiatan mahasiswa dan ditindaklanjuti',
                'jabatans' => ['WD III'],
                'jenisPertanyaan' => $jenisPertanyaan['text']
            ],
            [
                'pernyataan' => 'Tersedia dokumen pelaksanaan kegiatan kemahasiswaan',
                'kriteriaBM' => 'Tidak tersedia dokumen pelaksanaan kegiatan kemahasiswaan',
                'kriteriaM' => 'Tersedia dokumen pelaksanaan kegiatan kemahasiswaan',
                'kriteriaML' => 'Tersedia dokumen pelaksanaan kegiatan kemahasiswaan dan dilakukan monitoring dan evaluasi',
                'jabatans' => ['WD III'],
                'jenisPertanyaan' => $jenisPertanyaan['text']
            ],
            [
                'pernyataan' => 'Tersedia dokumen hasil monitoring evaluasi kepuasan mahasiswa terhadap layanan kemahasiswaan (beasiswa, kesehatan, asrama mahasiswa, fasilitas olahraga) dengan menggunakan instrumen yang sahih',
                'kriteriaBM' => 'Tidak tersedia dokumen hasil monitoring evaluasi kepuasan mahasiswa terhadap layanan',
                'kriteriaM' => 'Tersedia dokumen hasil monitoring evaluasi kepuasan mahasiswa terhadap layanan',
                'kriteriaML' => 'Tersedia dokumen hasil monitoring evaluasi kepuasan mahasiswa terhadap layanan yang dilaksanakan secara konsisten dan ditindaklanjuti secara berkala',
                'jabatans' => ['WD III'],
                'jenisPertanyaan' => $jenisPertanyaan['text']
            ],
        ];

        foreach ($instrumenDataUppsNAKEM as $data) {
            $existingInstrumenCount = Instrumen::where('kategori_id', $kategori['NAKEM']->id)->count();
            $instrumen = Instrumen::create([
                'peraturan_id' => $peraturan->id,
                'standar_id' => $standar['Non']->id,
                'kategori_id' => $kategori['NAKEM']->id,
                'level_id' => $level['upps']->id,
                'jenis_pertanyaan_id' => $data['jenisPertanyaan'],
                'pernyataan' => $data['pernyataan'],
                'indikator' => $data['pernyataan'],

            ]);

            $kode = $standar['Non']->kode . $kategori['NAKEM']->kode . '-' . ($existingInstrumenCount + 1);

            $instrumen->kode = $kode;
            $instrumen->save();

            $kriteriaBM->instrumen()->attach($instrumen->id, ['isi' => $data['kriteriaBM']]);
            $kriteriaM->instrumen()->attach($instrumen->id, ['isi' => $data['kriteriaM']]);
            $kriteriaML->instrumen()->attach($instrumen->id, ['isi' => $data['kriteriaML']]);

            foreach ($data['jabatans'] as $jabatan) {
                $jabatanUpps[$jabatan]->instrumen()->attach($instrumen->id);
            }
        }

        // UPPS Non SARPRAS = 
        $instrumenDataUppsNASAP = [
            [
                'pernyataan' => 'Tersedia sarana prasarana umum di lingkungan fakultas (Laboratorium, Kantin, parkir, dan ruang UKM)',
                'kriteriaBM' => 'Tidak tersedia sarana prasarana umum di lingkungan fakultas',
                'kriteriaM' => 'Tersedia sarana prasarana umum di lingkungan fakultas',
                'kriteriaML' => 'Tersedia sarana prasarana umum di lingkungan fakultas dan dilakukan monitoring dan evaluasi secara berkala',
                'jabatans' => ['WD II'],
                'jenisPertanyaan' => $jenisPertanyaan['text']
            ],
            [
                'pernyataan' => 'Tersedia dokumen inventarisasi Barang Milik Negara (BMN)',
                'kriteriaBM' => 'Tidak tersedia dokumen keberadaan sarana prasarana umum di lingkungan universitas',
                'kriteriaM' => 'Tersedia dokumen keberadaan sarana prasarana umum di lingkungan universitas',
                'kriteriaML' => 'Tersedia dokumen keberadaan sarana prasarana umum di lingkungan universitas dan disosialisasikan secara berkala serta dipublikasikan',
                'jabatans' => ['WD II'],
                'jenisPertanyaan' => $jenisPertanyaan['text']
            ],
            [
                'pernyataan' => 'Tersedia dokumen hasil audit dan monitoring dan evaluasi sarana dan prasarana umum',
                'kriteriaBM' => 'Tidak tersedia dokumen hasil audit dan monitoring dan evaluasi sarana dan prasarana umum',
                'kriteriaM' => 'Tersedia dokumen hasil audit dan monitoring dan evaluasi sarana dan prasarana umum',
                'kriteriaML' => 'Tersedia dokumen hasil audit dan monitoring dan evaluasi sarana dan prasarana umum dan ditindak lanjuti secara berkala',
                'jabatans' => ['Dekan'],
                'jenisPertanyaan' => $jenisPertanyaan['text']
            ]
        ];

        foreach ($instrumenDataUppsNASAP as $data) {
            $existingInstrumenCount = Instrumen::where('kategori_id', $kategori['NASAP']->id)->count();
            $instrumen = Instrumen::create([
                'peraturan_id' => $peraturan->id,
                'standar_id' => $standar['Non']->id,
                'kategori_id' => $kategori['NASAP']->id,
                'level_id' => $level['upps']->id,
                'jenis_pertanyaan_id' => $data['jenisPertanyaan'],
                'pernyataan' => $data['pernyataan'],
                'indikator' => $data['pernyataan'],

            ]);

            $kode = $standar['Non']->kode . $kategori['NASAP']->kode . '-' . ($existingInstrumenCount + 1);

            $instrumen->kode = $kode;
            $instrumen->save();

            $kriteriaBM->instrumen()->attach($instrumen->id, ['isi' => $data['kriteriaBM']]);
            $kriteriaM->instrumen()->attach($instrumen->id, ['isi' => $data['kriteriaM']]);
            $kriteriaML->instrumen()->attach($instrumen->id, ['isi' => $data['kriteriaML']]);

            foreach ($data['jabatans'] as $jabatan) {
                $jabatanUpps[$jabatan]->instrumen()->attach($instrumen->id);
            }
        }

        // Universitas PDP
        $instrumenUniversitasPDP = [
            [
                'pernyataan' => 'Tersedia dokumen laporan dan evaluasi penerimaan mahasiswa baru',
                'kriteriaBM' => 'Tidak tersedia dokumen laporan dan evaluasi penerimaan mahasiswa baru.',
                'kriteriaM' => 'Tersedia dokumen laporan dan evaluasi penerimaan mahasiswa baru.',
                'kriteriaML' => 'Tersedia dokumen laporan dan evaluasi penerimaan mahasiswa baru yang disosialisasikan dan ditindaklanjuti secara berkala.',
                'jabatans' => ['WR I', 'Kabauk'],
                'units' => ['WR', 'Biro Keuangan'],
                'jenisPertanyaan' => $jenisPertanyaan['text']
            ],
            [
                'pernyataan' => 'Tersedia dokumen kebijakan Rekognisi Pembelajaran Lampau (RPL) di Unsoed yang sesuai dengan ketentuan peraturan perundang-undangan',
                'kriteriaBM' => 'Tidak tersedia dokumen kebijakan RPL di Unsoed.',
                'kriteriaM' => 'Tersedia dokumen kebijakan RPL di Unsoed.',
                'kriteriaML' => 'Tersedia dokumen kebijakan RPL di Unsoed yang disosialisasikan dan dilakukan monitoring dan evaluasi secara berkala.',
                'jabatans' => ['Ketua LP3M'],
                'units' => ['LP3M'],
                'jenisPertanyaan' => $jenisPertanyaan['text']
            ],
            [
                'pernyataan' => 'Tersedia dokumen kebijakan serta laporan PKKM dan PKKMB',
                'kriteriaBM' => 'Tidak tersedia dokumen kebijakan serta laporan PKKM dan PKKMB.',
                'kriteriaM' => 'Tersedia dokumen kebijakan serta laporan PKKM dan PKKMB.',
                'kriteriaML' => 'Tersedia dokumen kebijakan serta laporan PKKM dan PKKMB yang disosialisasikan dan dilakukan monitoring dan evaluasi secara berkala.',
                'jabatans' => ['WR I', 'Kabauk'],
                'units' => ['WR', 'Biro Keuangan'],
                'jenisPertanyaan' => $jenisPertanyaan['text']
            ],
            [
                'pernyataan' => 'Persentase mahasiswa yang puas terhadap layanan administrasi, layanan akademik, layanan konseling, kesehatan dan kebutuhan dasar untuk mahasiswa berkebutuhan khusus (Merujuk pada LAM Teknik dan BAN PT)',
                'kriteriaBM' => '<75%',
                'kriteriaM' => '75%',
                'kriteriaML' => '>75%',
                'jabatans' => ['WR I', 'Kabauk'],
                'units' => ['WR', 'Biro Keuangan'],
                'jenisPertanyaan' => $jenisPertanyaan['number']
            ],
            [
                'pernyataan' => 'Tersedia sistem informasi terkait data pendidikan',
                'kriteriaBM' => 'Belum memiliki sistem informasi terkait data pendidikan.',
                'kriteriaM' => 'Tersedia sistem informasi terkait data pendidikan.',
                'kriteriaML' => 'Tersedia dan dilakukan monitoring dan evaluasi secara berkala.',
                'jabatans' => ['WR I'],
                'units' => ['WR'],
                'jenisPertanyaan' => $jenisPertanyaan['text']
            ]
        ];

        foreach ($instrumenUniversitasPDP as $data) {
            $existingInstrumenCount = Instrumen::where('kategori_id', $kategori['PDP']->id)->count();
            $instrumen = Instrumen::create([
                'peraturan_id' => $peraturan->id,
                'standar_id' => $standar['Pendidikan']->id,
                'kategori_id' => $kategori['PDP']->id,
                'level_id' => $level['universitas']->id,
                'jenis_pertanyaan_id' => $data['jenisPertanyaan'],
                'pernyataan' => $data['pernyataan'],
                'indikator' => $data['pernyataan'],

            ]);

            $kode = $standar['Pendidikan']->kode . $kategori['PDP']->kode . '-' . ($existingInstrumenCount + 1);

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

        // Universitas PDM
        $instrumenUniversitasPDM = [
            [
                'pernyataan' => 'Tersedia dokumen kebijakan tata kelola teknologi informasi yang memudahkan pengambilan keputusan dan penyebaran ilmu pengetahuan',
                'kriteriaBM' => 'Tidak tersedia dokumen kebijakan tata kelola teknologi informasi',
                'kriteriaM' => 'Tersedia dokumen kebijakan tata kelola teknologi informasi',
                'kriteriaML' => 'Tersedia dokumen kebijakan tata kelola teknologi informasi yang dievaluasi secara rutin setiap tahun',
                'jabatans' => ['Ketua LPTSI'],
                'units' => ['LPTSI'],
                'jenisPertanyaan' => $jenisPertanyaan['text']
            ],
            [
                'pernyataan' => 'Unsoed memiliki rencana strategis keuangan yang memuat perencanaan anggaran keuangan',
                'kriteriaBM' => 'Tidak tersedia rencana strategis keuangan yang memuat perencanaan anggaran keuangan',
                'kriteriaM' => 'Tersedia rencana strategis keuangan yang memuat perencanaan anggaran keuangan',
                'kriteriaML' => 'Rencana strategis keuangan yang memuat perencanaan anggaran keuangan dan dievaluasi secara berkala',
                'jabatans' => ['WR II'],
                'units' => ['WR'],
                'jenisPertanyaan' => $jenisPertanyaan['text']
            ],
            [
                'pernyataan' => 'Tersedia dokumen hasil audit keuangan oleh auditor dengan opini wajar tanpa pengecualian',
                'kriteriaBM' => 'Tidak tersedia dokumen hasil audit keuangan oleh auditor dengan opini wajar tanpa pengecualian',
                'kriteriaM' => 'Tersedia dokumen hasil audit keuangan oleh auditor dengan opini wajar tanpa pengecualian',
                'kriteriaML' => '-',
                'jabatans' => ['WR II'],
                'units' => ['WR'],
                'jenisPertanyaan' => $jenisPertanyaan['text']
            ],
            [
                'pernyataan' => 'Tersedia dokumen kebijakan bantuan biaya pendidikan',
                'kriteriaBM' => 'Tidak tersedia dokumen kebijakan bantuan biaya Pendidikan',
                'kriteriaM' => 'Tersedia dokumen kebijakan bantuan biaya Pendidikan dan terdapat mahasiswa yang memperoleh bantuan biaya pendidikan',
                'kriteriaML' => 'Tersedia dokumen kebijakan bantuan biaya pendidikan, terdapat mahasiswa yang memperoleh bantuan biaya Pendidikan dan disosialisasikan di sosial media secara berkala.',
                'jabatans' => ['WR I'],
                'units' => ['WR'],
                'jenisPertanyaan' => $jenisPertanyaan['text']
            ],
            [
                'pernyataan' => 'Terdapat data mahasiswa penerima bantuan biaya Pendidikan',
                'kriteriaBM' => 'Tidak terdapat data mahasiswa penerima bantuan biaya pendidikan',
                'kriteriaM' => 'Terdapat data mahasiswa penerima bantuan biaya pendidikan',
                'kriteriaML' => 'Terdapat data mahasiswa penerima bantuan biaya Pendidikan dan dipublikasikan secara berkala',
                'jabatans' => ['WR I'],
                'units' => ['WR'],
                'jenisPertanyaan' => $jenisPertanyaan['text']
            ],
        ];

        foreach ($instrumenUniversitasPDM as $data) {
            $existingInstrumenCount = Instrumen::where('kategori_id', $kategori['PDM']->id)->count();
            $instrumen = Instrumen::create([
                'peraturan_id' => $peraturan->id,
                'standar_id' => $standar['Pendidikan']->id,
                'kategori_id' => $kategori['PDM']->id,
                'level_id' => $level['universitas']->id,
                'jenis_pertanyaan_id' => $data['jenisPertanyaan'],
                'pernyataan' => $data['pernyataan'],
                'indikator' => $data['pernyataan'],

            ]);

            $kode = $standar['Pendidikan']->kode . $kategori['PDM']->kode . '-' . ($existingInstrumenCount + 1);

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

        // Universitas PNL
        $instrumenUniversitasPNL = [
            [
                'pernyataan' => 'Hasil penelitian dosen memiliki luaran penelitian yang bermutu, relevan, dan bermanfaat, serta mendukung pelaksanaan misi dan pencapaian visi serta target dampak Unsoed.',
                'kriteriaBM' => 'Tidak semua hasil penelitian dosen memiliki luaran penelitian yang bermutu, relevan, dan bermanfaat, serta mendukung pelaksanaan misi dan pencapaian visi  serta terpublikasi pada jurnal nasional (SINTA 1 – 6). ',
                'kriteriaM' => 'Semua hasil penelitian dosen memiliki luaran penelitian yang bermutu, relevan, dan bermanfaat, serta mendukung pelaksanaan misi dan pencapaian visi serta terpublikasi pada jurnal nasional (SINTA 1 – 6). ',
                'kriteriaML' => 'Semua hasil penelitian dosen memiliki luaran penelitian yang bermutu, relevan, dan bermanfaat, serta mendukung pelaksanaan misi dan pencapaian visi serta terpublikasi pada jurnal nasional (SINTA 1 – 6) serta sebagian terpublikasi dalam jurnal internasional bereputasi',
                'jabatans' => ['Ketua LPPM'],
                'units' => ['LPPM'],
                'jenisPertanyaan' => $jenisPertanyaan['text']
            ],
            [
                'pernyataan' => 'Hasil penelitian dosen dapat diakses dan dimanfaatkan oleh Masyarakat melalui jurnal yang memiliki oleh Unsoed',
                'kriteriaBM' => 'Unsoed belum memiliki jurnal yang menjadi media untuk mempublikasikan hasil penelitian dosen',
                'kriteriaM' => 'Unsoed sudah  memiliki jurnal yang menjadi media untuk mempublikasikan hasil penelitian dosen',
                'kriteriaML' => 'Masing-masing rumpun bidang ilmu di Unsoed sudah  memiliki jurnal yang menjadi media untuk mempublikasikan hasil penelitian dosen',
                'jabatans' => ['Ketua LPPM'],
                'units' => ['LPPM'],
                'jenisPertanyaan' => $jenisPertanyaan['text']
            ],
        ];

        foreach ($instrumenUniversitasPNL as $data) {
            $existingInstrumenCount = Instrumen::where('kategori_id', $kategori['PNL']->id)->count();
            $instrumen = Instrumen::create([
                'peraturan_id' => $peraturan->id,
                'standar_id' => $standar['Penelitian']->id,
                'kategori_id' => $kategori['PNL']->id,
                'level_id' => $level['universitas']->id,
                'jenis_pertanyaan_id' => $data['jenisPertanyaan'],
                'pernyataan' => $data['pernyataan'],
                'indikator' => $data['pernyataan'],

            ]);

            $kode = $standar['Penelitian']->kode . $kategori['PNL']->kode . '-' . ($existingInstrumenCount + 1);

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

        // Universitas PNP
        $instrumenUniversitasPNP = [
            [
                'pernyataan' => 'LPPM memiliki dokumen tata kelola di bidang penelitian berupa RIP, Renstra, Renop dan Roadmap penelitian.',
                'kriteriaBM' => 'LPPM memiliki sebagian dokumen tata kelola perguruan tinggi di bidang penelitian antara lain berupa: RIP, Renstra, Renop dan roadmap penelitian.',
                'kriteriaM' => 'LPPM memiliki semua dokumen tata kelola perguruan tinggi di bidang penelitian antara lain berupa: RIP, Renstra, Renop dan roadmap penelitian.',
                'kriteriaML' => 'LPPM memiliki semua dokumen tata kelola perguruan tinggi di bidang penelitian antara lain berupa: RIP, Renstra, Renop dan roadmap penelitian, dievaluasi secara berkala.',
                'jabatans' => ['Ketua LPPM'],
                'units' => ['LPPM'],
                'jenisPertanyaan' => $jenisPertanyaan['text']
            ],
            [
                'pernyataan' => 'LPPM memiliki pedoman pelaksanaan penelitian yang mencakup kode etik, tata kelola HKI, ketentuan kerjasama dan publikasi hasil penelitian.',
                'kriteriaBM' => 'Belum memiliki dokumen pedoman pelaksanaan penelitian yang mencakup: kode etik, tata kelola HKI, ketentuan kerjasama dan publikasi hasil penelitian.',
                'kriteriaM' => 'LPPM memiliki dokumen pedoman pelaksanaan penelitian yang mencakup: kode etik, tata kelola HKI, ketentuan kerjasama dan publikasi hasil penelitian.',
                'kriteriaML' => 'LPPM memiliki dokumen pedoman pelaksanaan penelitian yang mencakup: kode etik, tata kelola HKI, ketentuan kerjasama dan publikasi hasil penelitian serta dilakukan monitoring dan evaluasi secara berkala.',
                'jabatans' => ['Ketua LPPM'],
                'units' => ['LPPM'],
                'jenisPertanyaan' => $jenisPertanyaan['text']
            ],
            [
                'pernyataan' => 'LPPM melakukan penilaian penelitian secara sistematis, obyektif, transparan, dan akuntabel.',
                'kriteriaBM' => 'Tidak semua kegiatan penelitian dosen dilakukan melalui proses seleksi yang kompetitif, monev secara sistematis, obyektif, transparan, dan akuntabel.',
                'kriteriaM' => 'Semua kegiatan penelitian dosen dilakukan melalui proses seleksi yang kompetitif, monev secara sistematis, obyektif, transparan, dan akuntabel.',
                'kriteriaML' => 'Semua kegiatan penelitian dosen dilakukan melalui proses seleksi yang kompetitif, monev secara sistematis, obyektif, transparan, dan akuntabel dan dapat diakses.',
                'jabatans' => ['Ketua LPPM'],
                'units' => ['LPPM'],
                'jenisPertanyaan' => $jenisPertanyaan['text']
            ],
            [
                'pernyataan' => 'LPPM memiliki dokumen penjaminan mutu penelitian.',
                'kriteriaBM' => 'Tidak memiliki dokumen penjaminan mutu penelitian.',
                'kriteriaM' => 'Memiliki dokumen penjaminan mutu penelitian.',
                'kriteriaML' => 'Memiliki dokumen penjaminan mutu penelitian serta dilakukan monitoring dan evaluasi secara berkala.',
                'jabatans' => ['Ketua LPPM'],
                'units' => ['LPPM'],
                'jenisPertanyaan' => $jenisPertanyaan['text']
            ],
            [
                'pernyataan' => 'LPPM melaksanakan penjaminan mutu penelitian sesuai dengan dokumen penjaminan mutu yang ditetapkan.',
                'kriteriaBM' => 'Melaksanakan penjaminan mutu yang tidak sesuai dengan dokumen standar mutu penelitian yang ditetapkan.',
                'kriteriaM' => 'Melaksanakan penjaminan mutu yang sesuai dengan dokumen standar mutu penelitian yang ditetapkan.',
                'kriteriaML' => 'Melaksanakan penjaminan mutu yang sesuai dengan dokumen standar mutu penelitian yang ditetapkan dan melakukan upaya peningkatan mutu penelitian.',
                'jabatans' => ['Ketua LPPM'],
                'units' => ['LPPM'],
                'jenisPertanyaan' => $jenisPertanyaan['text']
            ],
            [
                'pernyataan' => 'LPPM memiliki dokumen laporan kinerja bidang penelitian yang dilakukan secara akuntabel dan tepat waktu.',
                'kriteriaBM' => 'Tidak memiliki dokumen laporan kinerja bidang penelitian yang dilakukan secara akuntabel dan tepat waktu.',
                'kriteriaM' => 'Memiliki dokumen laporan kinerja bidang penelitian yang dilakukan secara akuntabel dan tepat waktu.',
                'kriteriaML' => 'Memiliki dokumen laporan kinerja bidang penelitian yang dilakukan secara akuntabel dan tepat waktu dan dipublikasikan.',
                'jabatans' => ['Ketua LPPM'],
                'units' => ['LPPM'],
                'jenisPertanyaan' => $jenisPertanyaan['text']
            ],
            [
                'pernyataan' => 'LPPM memberikan akses kepada Fakultas/UPPS terkait laporan kinerja bidang penelitian.',
                'kriteriaBM' => 'Fakultas/UPPS tidak dapat mengakses dokumen laporan kinerja bidang penelitian.',
                'kriteriaM' => 'Fakultas/UPPS dapat mengakses dokumen laporan kinerja bidang penelitian.',
                'kriteriaML' => 'Fakultas/UPPS dapat mengakses dokumen laporan kinerja bidang penelitian dan dapat mengunduh laporan kinerja bidang penelitian.',
                'jabatans' => ['Ketua LPPM'],
                'units' => ['LPPM'],
                'jenisPertanyaan' => $jenisPertanyaan['text']
            ],
            [
                'pernyataan' => 'LPPM memiliki dokumen hasil evaluasi pelaksanaan dan hasil penelitian yang mencakup prinsip kemanfaatan, kemutakhiran, serta antisipasi terhadap kebutuhan masa depan untuk mendukung kepentingan nasional dan sesuai dengan Roadmap Penelitian Unsoed.',
                'kriteriaBM' => 'Tidak memiliki dokumen hasil evaluasi pelaksanaan dan hasil penelitian yang mencakup prinsip kemanfaatan, kemutakhiran, serta antisipasi terhadap kebutuhan masa depan untuk mendukung kepentingan nasional dan sesuai dengan Roadmap Penelitian Unsoed.',
                'kriteriaM' => 'Memiliki dokumen hasil evaluasi pelaksanaan dan hasil penelitian yang mencakup prinsip kemanfaatan, kemutakhiran, serta antisipasi terhadap kebutuhan masa depan untuk mendukung kepentingan nasional dan sesuai dengan Roadmap Penelitian Unsoed.',
                'kriteriaML' => 'Memiliki dokumen hasil evaluasi pelaksanaan dan hasil penelitian yang mencakup prinsip kemanfaatan, kemutakhiran, serta antisipasi terhadap kebutuhan masa depan untuk mendukung kepentingan nasional dan sesuai dengan Roadmap Penelitian Unsoed dan dipublikasikan.',
                'jabatans' => ['Ketua LPPM'],
                'units' => ['LPPM'],
                'jenisPertanyaan' => $jenisPertanyaan['text']
            ]
        ];

        foreach ($instrumenUniversitasPNP as $data) {
            $existingInstrumenCount = Instrumen::where('kategori_id', $kategori['PNP']->id)->count();
            $instrumen = Instrumen::create([
                'peraturan_id' => $peraturan->id,
                'standar_id' => $standar['Penelitian']->id,
                'kategori_id' => $kategori['PNP']->id,
                'level_id' => $level['universitas']->id,
                'jenis_pertanyaan_id' => $data['jenisPertanyaan'],
                'pernyataan' => $data['pernyataan'],
                'indikator' => $data['pernyataan'],

            ]);

            $kode = $standar['Penelitian']->kode . $kategori['PNP']->kode . '-' . ($existingInstrumenCount + 1);

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

        // Universitas PNM
        $instrumenUniversitasPNM = [
            [
                'pernyataan' => 'Unsoed memiliki sarana dan prasarana penelitian; mekanisme penugasan dan ketersediaan sistem informasi manajemen yang tangguh untuk menjamin keberlanjutan dan peningkatan kompetensi dosen dalam hal penelitian.',
                'kriteriaBM' => 'Unsoed belum memiliki keterbatasan sarana dan prasarana penelitian',
                'kriteriaM' => 'Unsoed memiliki sarana dan prasarana penelitian',
                'kriteriaML' => 'Unsoed memiliki sarana dan prasarana penelitian serta dimonitoring secara rutin.',
                'jabatans' => ['Ketua LPPM'],
                'units' => ['LPPM'],
                'jenisPertanyaan' => $jenisPertanyaan['text']
            ],
            [
                'pernyataan' => 'Unsoed menyediakan akses sarana dan prasarana penelitian; mekanisme penugasan dan ketersediaan sistem informasi manajemen yang tangguh untuk menjamin keberlanjutan dan peningkatan kompetensi dosen dalam hal penelitian.',
                'kriteriaBM' => 'Unsoed memiliki keterbatasan akses sarana dan prasarana penelitian.',
                'kriteriaM' => 'Unsoed menyediakan akses sarana dan prasarana penelitian.',
                'kriteriaML' => 'Unsoed menyediakan akses sarana dan prasarana penelitian serta dilakukan monitoring dan evaluasi secara berkala.',
                'jabatans' => ['Ketua LPPM'],
                'units' => ['LPPM'],
                'jenisPertanyaan' => $jenisPertanyaan['text']
            ],
            [
                'pernyataan' => 'Unsoed memiliki mekanisme penugasan dan peningkatan kompetensi dosen dalam hal penelitian.',
                'kriteriaBM' => 'Tidak tersedia mekanisme penugasan dan peningkatan kompetensi dosen dalam hal penelitian.',
                'kriteriaM' => 'Tersedia mekanisme penugasan dan peningkatan kompetensi dosen dalam hal penelitian.',
                'kriteriaML' => 'Tersedia mekanisme penugasan dan peningkatan kompetensi dosen dalam hal penelitian serta dilakukan monitoring dan evaluasi secara berkala.',
                'jabatans' => ['Ketua LPPM'],
                'units' => ['LPPM'],
                'jenisPertanyaan' => $jenisPertanyaan['text']
            ],
            [
                'pernyataan' => 'Unsoed memiliki sistem informasi manajemen penelitian (Sinelitabmas) yang tangguh untuk menjamin keberlanjutan penelitian.',
                'kriteriaBM' => 'Tidak tersedia sistem informasi manajemen penelitian (Sinelitabmas) yang tangguh untuk menjamin keberlanjutan penelitian.',
                'kriteriaM' => 'Tersedia sistem informasi manajemen penelitian (Sinelitabmas) yang tangguh untuk menjamin keberlanjutan penelitian.',
                'kriteriaML' => 'Tersedia sistem informasi manajemen penelitian (Sinelitabmas) yang tangguh untuk menjamin keberlanjutan penelitian yang dimonitoring secara berkala.',
                'jabatans' => ['Ketua LPPM'],
                'units' => ['LPPM'],
                'jenisPertanyaan' => $jenisPertanyaan['text']
            ],
            [
                'pernyataan' => 'Unsoed mengalokasikan dana penelitian paling sedikit 15% dari DIPA PNBP',
                'kriteriaBM' => 'Alokasi dana penelitian kurang dari 15%',
                'kriteriaM' => 'Alokasi dana penelitian sebesar 15%',
                'kriteriaML' => 'Alokasi dana penelitian lebih dari 15%',
                'jabatans' => ['WR II'],
                'units' => ['WR'],
                'jenisPertanyaan' => $jenisPertanyaan['text']
            ],
        ];

        foreach ($instrumenUniversitasPNM as $data) {
            $existingInstrumenCount = Instrumen::where('kategori_id', $kategori['PNM']->id)->count();
            $instrumen = Instrumen::create([
                'peraturan_id' => $peraturan->id,
                'standar_id' => $standar['Penelitian']->id,
                'kategori_id' => $kategori['PNM']->id,
                'level_id' => $level['universitas']->id,
                'jenis_pertanyaan_id' => $data['jenisPertanyaan'],
                'pernyataan' => $data['pernyataan'],
                'indikator' => $data['pernyataan'],

            ]);

            $kode = $standar['Penelitian']->kode . $kategori['PNM']->kode . '-' . ($existingInstrumenCount + 1);

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

        $instrumenUniversitasPKML = [
            [
                'pernyataan' => 'Dosen memiliki luaran wajib PkM minimal luaran wajib yang telah ditentukan oleh LPPM pada masing-masing skim pengabdian.',
                'kriteriaBM' => 'Tidak semua dosen memiliki luaran wajib PKM minimal luaran wajib yang telah ditentukan oleh LPPM pada masing-masing skim pengabdian',
                'kriteriaM' => 'Semua dosen memiliki luaran wajib PKM minimal luaran wajib yang telah ditentukan oleh LPPM pada masing-masing skim pengabdian',
                'kriteriaML' => 'Semua dosen memiliki luaran wajib PKM dan luaran tambahan pada masing-masing skim pengabdian',
                'jabatans' => ['Ketua LPPM'],
                'units' => ['LPPM'],
                'jenisPertanyaan' => $jenisPertanyaan['text']
            ],
        ];

        foreach ($instrumenUniversitasPKML as $data) {
            $existingInstrumenCount = Instrumen::where('kategori_id', $kategori['PKML']->id)->count();
            $instrumen = Instrumen::create([
                'peraturan_id' => $peraturan->id,
                'standar_id' => $standar['PKM']->id,
                'kategori_id' => $kategori['PKML']->id,
                'level_id' => $level['universitas']->id,
                'jenis_pertanyaan_id' => $data['jenisPertanyaan'],
                'pernyataan' => $data['pernyataan'],
                'indikator' => $data['pernyataan'],

            ]);

            $kode = $standar['PKM']->kode . $kategori['PKML']->kode . '-' . ($existingInstrumenCount + 1);

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

        // Universtias PKMP
        $instrumenUniversitasPKMP = [
            [
                'pernyataan' => 'Tersedia dokumen/sistem Pengelolaan PkM oleh LPPM dengan prinsip tata kelola perguruan tinggi yang baik.',
                'kriteriaBM' => 'Tidak tersedia dokumen/sistem Pengelolaan PkM oleh LPPM dengan prinsip tata kelola perguruan tinggi yang baik',
                'kriteriaM' => 'Tersedia dokumen/sistem Pengelolaan PkM oleh LPPM dengan prinsip tata kelola perguruan tinggi yang baik',
                'kriteriaML' => 'Tersedia dokumen/sistem Pengelolaan PkM oleh LPPM dengan prinsip tata kelola perguruan tinggi yang baik, disosialisasikan dan dilakukan monitoring evaluasi secara berkala.',
                'jabatans' => ['Ketua LPPM'],
                'units' => ['LPPM'],
                'jenisPertanyaan' => $jenisPertanyaan['text']
            ],
            [
                'pernyataan' => 'Dosen terlibat dalam kegiatan PkM baik sebagai ketua atau anggota dengan melakukan transfer ilmu dan teknologi kepada Masyarakat.',
                'kriteriaBM' => 'Tidak semua dosen terlibat dalam kegiatan PKM baik sebagai ketua atau anggota dengan melakukan transfer ilmu dan teknologi kepada Masyarakat',
                'kriteriaM' => 'Semua dosen terlibat dalam kegiatan PKM baik sebagai ketua atau anggota dengan melakukan transfer ilmu dan teknologi kepada Masyarakat',
                'kriteriaML' => '-',
                'jabatans' => ['Ketua LPPM'],
                'units' => ['LPPM'],
                'jenisPertanyaan' => $jenisPertanyaan['text']
            ],
            [
                'pernyataan' => 'Tersedia dokumen kode etik PkM sesuai dengan ketentuan Peraturan perundang-undangan.',
                'kriteriaBM' => 'Tidak tersedia dokumen kode etik PkM sesuai dengan ketentuan Peraturan perundang-undangan',
                'kriteriaM' => 'Tersedia dokumen kode etik PkM sesuai dengan ketentuan Peraturan perundang-undangan',
                'kriteriaML' => 'Tersedia dokumen kode etik PkM sesuai dengan ketentuan Peraturan perundang-undangan dan disosialisasikan.',
                'jabatans' => ['Ketua LPPM'],
                'units' => ['LPPM'],
                'jenisPertanyaan' => $jenisPertanyaan['text']
            ],
            [
                'pernyataan' => 'Tersedia dokumen pengelolaan dan kepemilikan hak atas kekayaan intelektual sesuai dengan ketentuan peraturan perundang-undangan.',
                'kriteriaBM' => 'Tidak tersedia dokumen pengelolaan dan kepemilikan hak atas kekayaan intelektual sesuai dengan ketentuan peraturan perundang-undangan',
                'kriteriaM' => 'Tersedia dokumen pengelolaan dan kepemilikan hak atas kekayaan intelektual sesuai dengan ketentuan peraturan perundang-undangan',
                'kriteriaML' => 'Tersedia dokumen pengelolaan dan kepemilikan hak atas kekayaan intelektual sesuai dengan ketentuan peraturan perundang-undangan dan disosialisasikan',
                'jabatans' => ['Ketua LPPM'],
                'units' => ['LPPM'],
                'jenisPertanyaan' => $jenisPertanyaan['text']
            ],
            [
                'pernyataan' => 'Tersedia dokumen ketentuan dalam kerja sama PkM.',
                'kriteriaBM' => 'Tidak tersedia dokumen ketentuan dalam kerja sama PkM',
                'kriteriaM' => 'Tersedia dokumen ketentuan dalam kerja sama PkM',
                'kriteriaML' => 'Tersedia dokumen ketentuan dalam kerja sama PkM dan disosialisasikan.',
                'jabatans' => ['Ketua LPPM'],
                'units' => ['LPPM'],
                'jenisPertanyaan' => $jenisPertanyaan['text']
            ],
            [
                'pernyataan' => 'Tersedia dokumen persyaratan untuk publikasi hasil PKM dan ketentuan penulisannya.',
                'kriteriaBM' => 'Tidak tersedia dokumen persyaratan untuk publikasi hasil PkM dan ketentuan penulisannya',
                'kriteriaM' => 'Tersedia dokumen persyaratan untuk publikasi hasil PkM dan ketentuan penulisannya',
                'kriteriaML' => 'Tersedia dokumen persyaratan untuk publikasi hasil PkM dan ketentuan penulisannya dan disosialisasikan.',
                'jabatans' => ['Ketua LPPM'],
                'units' => ['LPPM'],
                'jenisPertanyaan' => $jenisPertanyaan['text']
            ],
            [
                'pernyataan' => 'Terdapat dokumen proses penilaian PkM secara sistematis, obyektif, transparan, dan akuntabel yang dilakukan LPPM',
                'kriteriaBM' => 'Tidak terdapat dokumen proses penilaian PkM secara sistematis, obyektif, transparan, dan akuntabel yang dilakukan LPPM',
                'kriteriaM' => 'Terdapat dokumen proses penilaian PkM secara sistematis, obyektif, transparan, dan akuntabel yang dilakukan LPPM',
                'kriteriaML' => 'Terdapat dokumen proses penilaian PkM secara sistematis, obyektif, transparan, dan akuntabel yang dilakukan LPPM dan bisa diakses',
                'jabatans' => ['Ketua LPPM'],
                'units' => ['LPPM'],
                'jenisPertanyaan' => $jenisPertanyaan['text']
            ],
            [
                'pernyataan' => 'Tersedia dokumen renstra LPPM terkait yang berisi roadmap dan tema PkM unggulan untuk mendukung pencapaian visi Unsoed.',
                'kriteriaBM' => 'Tidak tersedia dokumen renstra LPPM yang berisi roadmap dan tema PkM unggulan untuk mendukung pencapaian visi Unsoed.',
                'kriteriaM' => 'Tersedia dokumen renstra LPPM yang berisi roadmap dan tema PkM unggulan untuk mendukung pencapaian visi Unsoed.',
                'kriteriaML' => 'Tersedia dokumen renstra LPPM yang berisi roadmap dan tema PkM unggulan untuk mendukung pencapaian visi Unsoed dan disosialisasikan.',
                'jabatans' => ['Ketua LPPM'],
                'units' => ['LPPM'],
                'jenisPertanyaan' => $jenisPertanyaan['text']
            ],
            [
                'pernyataan' => 'Tersedia dokumen proses seleksi, monitoring dan evaluasi yang dilakukan oleh LPPM untuk menjamin mutu kegiatan PkM yang dilaksanakan setiap tahun.',
                'kriteriaBM' => 'Tidak tersedia dokumen proses seleksi, monitoring dan evaluasi yang dilakukan oleh LPPM untuk menjamin mutu kegiatan PkM yang dilaksanakan setiap tahun.',
                'kriteriaM' => 'Tersedia dokumen proses seleksi, monitoring dan evaluasi yang dilakukan oleh LPPM untuk menjamin mutu kegiatan PkM yang dilaksanakan setiap tahun.',
                'kriteriaML' => 'Tersedia dokumen proses seleksi, monitoring dan evaluasi yang dilakukan oleh LPPM untuk menjamin mutu kegiatan PkM yang dilaksanakan setiap tahun dan disosialisasikan.',
                'jabatans' => ['Ketua LPPM'],
                'units' => ['LPPM'],
                'jenisPertanyaan' => $jenisPertanyaan['text']
            ],
            [
                'pernyataan' => 'Tersedia dokumen pelaksanaan forum pelaporan kinerja LPPM terkait program.',
                'kriteriaBM' => 'Tidak tersedia dokumen pelaksanaan forum pelaporan kinerja LPPM terkait program.',
                'kriteriaM' => 'Tersedia dokumen pelaksanaan forum pelaporan kinerja LPPM terkait program.',
                'kriteriaML' => 'Tersedia dokumen pelaksanaan forum pelaporan kinerja LPPM terkait program dan dievaluasi secara berkala.',
                'jabatans' => ['Ketua LPPM'],
                'units' => ['LPPM'],
                'jenisPertanyaan' => $jenisPertanyaan['text']
            ],
            [
                'pernyataan' => 'Dosen melaksanakan kegiatan PkM (Pengabdian Kepada Masyarakat) sesuai dengan roadmap PKM.',
                'kriteriaBM' => 'Belum semua dosen melaksanakan PkM sesuai dengan roadmap Unsoed.',
                'kriteriaM' => 'Semua dosen melaksanan PkM sesuai dengan roadmap Unsoed.',
                'kriteriaML' => 'Semua PkM yang dilaksanakan sesuai dengan roadmap Unsoed dan menghasilkan luaran wajib dan luaran tambahan.',
                'jabatans' => ['Ketua LPPM'],
                'units' => ['LPPM'],
                'jenisPertanyaan' => $jenisPertanyaan['text']
            ]
        ];

        foreach ($instrumenUniversitasPKMP as $data) {
            $existingInstrumenCount = Instrumen::where('kategori_id', $kategori['PKMP']->id)->count();
            $instrumen = Instrumen::create([
                'peraturan_id' => $peraturan->id,
                'standar_id' => $standar['PKM']->id,
                'kategori_id' => $kategori['PKMP']->id,
                'level_id' => $level['universitas']->id,
                'jenis_pertanyaan_id' => $data['jenisPertanyaan'],
                'pernyataan' => $data['pernyataan'],
                'indikator' => $data['pernyataan'],

            ]);

            $kode = $standar['PKM']->kode . $kategori['PKMP']->kode . '-' . ($existingInstrumenCount + 1);

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

        // Universtias PKMM
        $instrumenUniversitasPKMM = [
            [
                'pernyataan' => 'Unsoed memiliki sarana dan prasarana PKM',
                'kriteriaBM' => 'Unsoed belum memiliki keterbatasan sarana dan prasarana PKM',
                'kriteriaM' => 'Unsoed memiliki sarana dan prasarana PKM',
                'kriteriaML' => 'Unsoed memiliki sarana dan prasarana PKM serta dimonitoring secara rutin.',
                'jabatans' => ['Ketua LPPM'],
                'units' => ['LPPM'],
                'jenisPertanyaan' => $jenisPertanyaan['text']
            ],
            [
                'pernyataan' => 'Unsoed menyediakan akses ke sarana dan prasarana PKM',
                'kriteriaBM' => 'Unsoed memiliki keterbatasan akses sarana dan prasarana PKM',
                'kriteriaM' => 'Unsoed menyediakan akses sarana dan prasarana PKM',
                'kriteriaML' => 'Unsoed menyediakan akses sarana dan prasarana PKM serta dilakukan monitoring dan evaluasi secara berkala.',
                'jabatans' => ['Ketua LPPM'],
                'units' => ['LPPM'],
                'jenisPertanyaan' => $jenisPertanyaan['text']
            ],
            [
                'pernyataan' => 'Unsoed memiliki mekanisme penugasan dan peningkatan kompetensi dosen dalam hal PKM.',
                'kriteriaBM' => 'Tidak tersedia mekanisme penugasan dan peningkatan kompetensi dosen dalam melakukan PKM.',
                'kriteriaM' => 'Tersedia mekanisme penugasan dan peningkatan kompetensi dosen dalam melakukan PKM.',
                'kriteriaML' => 'Tersedia mekanisme penugasan dan peningkatan kompetensi dosen dalam melakukan PKM serta dilakukan monitoring dan evaluasi secara berkala.',
                'jabatans' => ['Ketua LPPM'],
                'units' => ['LPPM'],
                'jenisPertanyaan' => $jenisPertanyaan['text']
            ],
            [
                'pernyataan' => 'Unsoed memiliki sistem informasi manajemen penelitian (Sinelitabmas) yang tangguh untuk menjamin keberlanjutan PKM.',
                'kriteriaBM' => 'Tidak tersedia sistem informasi manajemen penelitian (Sinelitabmas) yang tangguh untuk menjamin keberlanjutan PKM.',
                'kriteriaM' => 'Tersedia sistem informasi manajemen penelitian (Sinelitabmas) yang tangguh untuk menjamin keberlanjutan PKM.',
                'kriteriaML' => 'Tersedia sistem informasi manajemen penelitian (Sinelitabmas) yang tangguh untuk menjamin keberlanjutan PKM yang dimonitoring secara berkala.',
                'jabatans' => ['Ketua LPPM'],
                'units' => ['LPPM'],
                'jenisPertanyaan' => $jenisPertanyaan['text']
            ],

            [
                'pernyataan' => 'Tersedia sistem informasi dan komunikasi yang andal untuk mendokumentasikan, mengevaluasi, melaporkan, dan menyebarluaskan proses dan hasil PkM.',
                'kriteriaBM' => 'Tidak tersedia sistem informasi dan komunikasi yang andal untuk mendokumentasikan, mengevaluasi, melaporkan, dan menyebarluaskan proses dan hasil PkM.',
                'kriteriaM' => 'Tersedia sistem informasi dan komunikasi yang andal untuk mendokumentasikan, mengevaluasi, melaporkan, dan menyebarluaskan proses dan hasil PkM.',
                'kriteriaML' => 'Tersedia sistem informasi dan komunikasi yang andal untuk mendokumentasikan, mengevaluasi, melaporkan, dan menyebarluaskan proses dan hasil PkM serta ada upaya pemeliharaan dan perbaikan.',
                'jabatans' => ['Ketua LPPM'],
                'units' => ['LPPM'],
                'jenisPertanyaan' => $jenisPertanyaan['text']
            ],
            [
                'pernyataan' => 'Luaran PkM disebarluarkan kepada masyarakat dalam bentuk hasil PkM.',
                'kriteriaBM' => 'Luaran PkM tidak disebarluaskan',
                'kriteriaM' => 'Luaran PkM disajikan dalam seminar nasional, buku TTG, buku ajar, HKI, dll.',
                'kriteriaML' => 'Luaran PkM disajikan dalam bentuk paten atau paten sederhana.',
                'jabatans' => ['Ketua LPPM'],
                'units' => ['LPPM'],
                'jenisPertanyaan' => $jenisPertanyaan['text']
            ],
            [
                'pernyataan' => 'Unsoed mengalokasikan dana PkM paling sedikit 5% dari DIPA PNBP.',
                'kriteriaBM' => 'Kurang dari 5% dari DIPA PNBP',
                'kriteriaM' => '5% dari DIPA PNBP',
                'kriteriaML' => 'Lebih besar dari 5% dari DIPA PNBP',
                'jabatans' => ['WR II'],
                'units' => ['WR'],
                'jenisPertanyaan' => $jenisPertanyaan['text']
            ],
            [
                'pernyataan' => 'UPPS memiliki kelompok pelaksana PKM sesuai dengan prioritas/topik PkM, kualifikasi akademik, dan rekam jejak PkM melalui skim yang ada.',
                'kriteriaBM' => 'Tidak memiliki kelompok pelaksana PkM sesuai dengan prioritas/topik PkM, kualifikasi akademik, dan rekam jejak PkM melalui skim yang ada.',
                'kriteriaM' => 'Memiliki kelompok pelaksana PkM sesuai dengan prioritas/topik PkM, kualifikasi akademik, dan rekam jejak PkM melalui skim yang ada.',
                'kriteriaML' => 'Memiliki kelompok pelaksana PkM sesuai dengan prioritas/topik PkM, kualifikasi akademik, dan rekam jejak PkM melalui skim yang ada dan berkelanjutan.',
                'jabatans' => ['Ketua LPPM'],
                'units' => ['LPPM'],
                'jenisPertanyaan' => $jenisPertanyaan['text']
            ]
        ];

        foreach ($instrumenUniversitasPKMM as $data) {
            $existingInstrumenCount = Instrumen::where('kategori_id', $kategori['PKMM']->id)->count();
            $instrumen = Instrumen::create([
                'peraturan_id' => $peraturan->id,
                'standar_id' => $standar['PKM']->id,
                'kategori_id' => $kategori['PKMM']->id,
                'level_id' => $level['universitas']->id,
                'jenis_pertanyaan_id' => $data['jenisPertanyaan'],
                'pernyataan' => $data['pernyataan'],
                'indikator' => $data['pernyataan'],

            ]);

            $kode = $standar['PKM']->kode . $kategori['PKMM']->kode . '-' . ($existingInstrumenCount + 1);

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

        // Universitas NON Kerjasama
        $instrumenUniversitasNAK = [
            [
                'pernyataan' => 'Terdapat dokumen kerjasama tingkat lokal, nasional atau internasional baik dalam bidang pendidikan, penelitian dan atau pengabdian',
                'kriteriaBM' => 'Tidak semua dokumen kerjasama lengkap',
                'kriteriaM' => 'Semua kerjasama dipayungi oleh dokumen MoU atau PKS dengan mitra dari dalam negeri',
                'kriteriaML' => 'Semua kerjasama dipayungi oleh dokumen MoU atau PKS dengan mitra dari dalam negeri dan luar negeri',
                'jabatans' => ['WR IV'],
                'units' => ['WR'],
                'jenisPertanyaan' => $jenisPertanyaan['text']
            ],
            [
                'pernyataan' => 'Tersedia dokumen kebijakan kerjasama di tingkat universitas',
                'kriteriaBM' => 'Tidak tersedia dokumen kebijakan kerjasama di tingkat universitas',
                'kriteriaM' => 'Tersedia dokumen kebijakan kerjasama di tingkat universitas',
                'kriteriaML' => 'Tersedia dokumen kebijakan kerjasama di tingkat universitas dan dalam pelaksanaannya dilakukan monitoring dan evaluasi secara rutin setiap tahun',
                'jabatans' => ['WR IV'],
                'units' => ['WR'],
                'jenisPertanyaan' => $jenisPertanyaan['text']
            ],
            [
                'pernyataan' => 'Tersedia dokumen SOP Kerjasama di tingkat universitas',
                'kriteriaBM' => 'Tidak tersedia dokumen SOP Kerjasama di tingkat universitas',
                'kriteriaM' => 'Tersedia dokumen SOP Kerjasama di tingkat universitas',
                'kriteriaML' => 'Tersedia dokumen SOP Kerjasama di tingkat universitas dan dalam pelaksanaannya dilakukan monitor dan evaluasi secara rutin setiap tahun',
                'jabatans' => ['WR IV'],
                'units' => ['WR'],
                'jenisPertanyaan' => $jenisPertanyaan['text']
            ],
            [
                'pernyataan' => 'Tersedia dokumen rencana pengembangan kerjasama di tingkat universitas',
                'kriteriaBM' => 'Tidak tersedia rencana pengembangan kerjasama di tingkat universitas',
                'kriteriaM' => 'Tersedia rencana pengembangan kerjasama di tingkat universitas',
                'kriteriaML' => 'Tersedia rencana pengembangan kerjasama di tingkat universitas dan dalam pelaksanaannya dilakukan monitor dan evaluasi secara rutin setiap tahun',
                'jabatans' => ['WR IV'],
                'units' => ['WR'],
                'jenisPertanyaan' => $jenisPertanyaan['text']
            ],
            [
                'pernyataan' => 'Tersedia dokumen monitoring dan evaluasi kerjasama yang terdiri atas jumlah, lingkup, relevansi, hasil dan kemanfaatan kerjasama',
                'kriteriaBM' => 'Tidak tersedia dokumen monitoring dan evaluasi kerjasama yang terdiri atas jumlah, lingkup, relevansi, hasil dan kemanfaatan kerjasama',
                'kriteriaM' => 'Tersedia dokumen monitoring dan evaluasi kerjasama yang terdiri atas jumlah, lingkup, relevansi, hasil dan kemanfaatan kerjasama',
                'kriteriaML' => 'Tersedia dokumen monitoring dan evaluasi kerjasama yang terdiri atas jumlah, lingkup, relevansi, hasil dan kemanfaatan kerjasama serta adanya upaya tindak lanjut',
                'jabatans' => ['WR IV'],
                'units' => ['WR'],
                'jenisPertanyaan' => $jenisPertanyaan['text']
            ],
            [
                'pernyataan' => 'Terdapat survey untuk mengetahui kepuasan mitra kerjasama yang diukur dengan instrumen yang sahih',
                'kriteriaBM' => 'Tidak dilakukan survey',
                'kriteriaM' => 'Dilakukan survey dan tersedia laporan hasil survey',
                'kriteriaML' => 'Dilakukan survey dan tersedia laporan hasil survey yang ditindaklanjuti',
                'jabatans' => ['Ketua LPPM'],
                'units' => ['LPPM'],
                'jenisPertanyaan' => $jenisPertanyaan['text']
            ]
        ];

        foreach ($instrumenUniversitasNAK as $data) {
            $existingInstrumenCount = Instrumen::where('kategori_id', $kategori['NAK']->id)->count();
            $instrumen = Instrumen::create([
                'peraturan_id' => $peraturan->id,
                'standar_id' => $standar['Non']->id,
                'kategori_id' => $kategori['NAK']->id,
                'level_id' => $level['universitas']->id,
                'jenis_pertanyaan_id' => $data['jenisPertanyaan'],
                'pernyataan' => $data['pernyataan'],
                'indikator' => $data['pernyataan'],

            ]);

            $kode = $standar['Non']->kode . $kategori['NAK']->kode . '-' . ($existingInstrumenCount + 1);

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

        // Universitas NON Layanan
        $instrumenUniversitasNAL = [
            [
                'pernyataan' => 'Tersedia Unit Layanan Terpadu',
                'kriteriaBM' => 'Tidak tersedia dokumen yang menunjukkan keberadaan Unit Layanan Terpadu',
                'kriteriaM' => 'Tersedia dokumen yang menunjukkan keberadaan Unit Layanan Terpadu',
                'kriteriaML' => 'Tersedia dokumen yang menunjukkan keberadaan unit layanan terpadu',
                'jabatans' => ['Ketua ULT'],
                'units' => ['ULT'],
                'jenisPertanyaan' => $jenisPertanyaan['text']
            ],
            [
                'pernyataan' => 'Pelayanan terlaksana secara transparan, akuntabel, dan sesuai dengan peraturan perundang-undangan',
                'kriteriaBM' => 'Belum terlaksana pelayanan secara transparan, akuntabel, dan sesuai dengan peraturan perundang-undangan',
                'kriteriaM' => 'Terlaksana pelayanan secara transparan, akuntabel, dan sesuai dengan peraturan perundang-undangan',
                'kriteriaML' => 'Terlaksana pelayanan secara transparan, akuntabel, dan sesuai dengan peraturan perundang-undangan serta dilakukan monitoring dan evaluasi secara berkala',
                'jabatans' => ['Ketua ULT'],
                'units' => ['ULT'],
                'jenisPertanyaan' => $jenisPertanyaan['text']
            ],
            [
                'pernyataan' => 'Tersedia dokumen SOP layanan',
                'kriteriaBM' => 'Tidak tersedia dokumen SOP layanan',
                'kriteriaM' => 'Tersedia dokumen SOP layanan',
                'kriteriaML' => 'Tersedia dokumen SOP layanan dan ditinjau secara berkala',
                'jabatans' => ['Ketua ULT'],
                'units' => ['ULT'],
                'jenisPertanyaan' => $jenisPertanyaan['text']
            ],
            [
                'pernyataan' => 'Dilaksanakannya survei kepuasan layanan',
                'kriteriaBM' => 'Tidak tersedia survey kepuasan layanan',
                'kriteriaM' => 'Tersedia survey kepuasan layanan dan terdapat laporan hasil survey yang dapat diakses publik',
                'kriteriaML' => 'Tersedia survey kepuasan layanan dan terdapat laporan hasil survey yang dapat diakses publik serta ditindaklanjuti',
                'jabatans' => ['Ketua ULT'],
                'units' => ['ULT'],
                'jenisPertanyaan' => $jenisPertanyaan['text']
            ]
        ];

        foreach ($instrumenUniversitasNAL as $data) {
            $existingInstrumenCount = Instrumen::where('kategori_id', $kategori['NAL']->id)->count();
            $instrumen = Instrumen::create([
                'peraturan_id' => $peraturan->id,
                'standar_id' => $standar['Non']->id,
                'kategori_id' => $kategori['NAL']->id,
                'level_id' => $level['universitas']->id,
                'jenis_pertanyaan_id' => $data['jenisPertanyaan'],
                'pernyataan' => $data['pernyataan'],
                'indikator' => $data['pernyataan'],

            ]);

            $kode = $standar['Non']->kode . $kategori['NAL']->kode . '-' . ($existingInstrumenCount + 1);

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

        // Universitas NON Tata Kelola
        $instrumenUniversitasNAT = [
            [
                'pernyataan' => 'Terdapat dokumen kebijakan tata pamong pengelolaan pendidikan tinggi berasaskan pada prinsip efektifitas, efisiensi dan produktifitas',
                'kriteriaBM' => 'Tidak terdapat dokumen kebijakan tata pamong pengelolaan pendidikan tinggi  berasaskan pada prinsip efektifitas, efisiensi dan produktifitas',
                'kriteriaM' => 'Terdapat dokumen kebijakan tata pamong pengelolaan pendidikan tinggi yang memuat dokumen formal struktur organisasi dan tata kerja yang dilengkapi tugas dan fungsinya',
                'kriteriaML' => 'Terdapat dokumen kebijakan tata pamong pengelolaan pendidikan tinggi yang memuat dokumen formal struktur organisasi dan tata kerja yang dilengkapi tugas dan fungsinya, telah berjalan secara konsisten dan menjamin tata pamong yang baik serta berjalan efektif dan efisien.',
                'jabatans' => ['WR II'],
                'units' => ['WR'],
                'jenisPertanyaan' => $jenisPertanyaan['text']
            ],
        ];

        foreach ($instrumenUniversitasNAT as $data) {
            $existingInstrumenCount = Instrumen::where('kategori_id', $kategori['NAT']->id)->count();
            $instrumen = Instrumen::create([
                'peraturan_id' => $peraturan->id,
                'standar_id' => $standar['Non']->id,
                'kategori_id' => $kategori['NAT']->id,
                'level_id' => $level['universitas']->id,
                'jenis_pertanyaan_id' => $data['jenisPertanyaan'],
                'pernyataan' => $data['pernyataan'],
                'indikator' => $data['pernyataan'],

            ]);

            $kode = $standar['Non']->kode . $kategori['NAT']->kode . '-' . ($existingInstrumenCount + 1);

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

        // Universitas Non Kemahasiswaan
        $instrumenUniversitasNAKEM = [
            [
                'pernyataan' => 'Tersedia dokumen SOP untuk proses penerimaan mahasiswa baru',
                'kriteriaBM' => 'Tidak tersedia dokumen SOP untuk proses penerimaan mahasiswa baru',
                'kriteriaM' => 'Tersedia dokumen SOP untuk proses penerimaan mahasiswa baru',
                'kriteriaML' => 'Tersedia dokumen SOP untuk proses penerimaan mahasiswa baru Dievaluasi secara rutin setiap tahun untuk perbaikan keberlanjutan',
                'jabatans' => ['WR I'],
                'units' => ['WR'],
                'jenisPertanyaan' => $jenisPertanyaan['text']
            ],
            [
                'pernyataan' => 'Tersedia dokumen audit penerimaan mahasiswa baru',
                'kriteriaBM' => 'Tidak tersedia dokumen audit penerimaan mahasiswa baru',
                'kriteriaM' => 'Tersedia dokumen audit penerimaan mahasiswa baru',
                'kriteriaML' => 'Tersedia dokumen audit penerimaan mahasiswa baru Dievaluasi secara rutin setiap tahun untuk perbaikan keberlanjutan',
                'jabatans' => ['WR I'],
                'units' => ['WR'],
                'jenisPertanyaan' => $jenisPertanyaan['text']
            ],
            [
                'pernyataan' => 'Tersedia dokumen kebijakan kegiatan mahasiswa',
                'kriteriaBM' => 'Tidak tersedia dokumen kebijakan kegiatan mahasiswa',
                'kriteriaM' => 'Tersedia dokumen kebijakan kegiatan mahasiswa',
                'kriteriaML' => 'Tersedia dokumen kebijakan kegiatan mahasiswa dan disosialisasikan secara berkala serta dipublikasikan',
                'jabatans' => ['WR III'],
                'units' => ['WR'],
                'jenisPertanyaan' => $jenisPertanyaan['text']
            ],
            [
                'pernyataan' => 'Tersedia dokumen kebijakan terkait prestasi mahasiswa',
                'kriteriaBM' => 'Tidak tersedia dokumen kebijakan terkait prestasi mahasiswa',
                'kriteriaM' => 'Tersedia dokumen kebijakan terkait prestasi mahasiswa',
                'kriteriaML' => 'Tersedia dokumen kebijakan terkait prestasi mahasiswa dan disosialisasikan secara berkala serta dipublikasikan',
                'jabatans' => ['WR III'],
                'units' => ['WR'],
                'jenisPertanyaan' => $jenisPertanyaan['text']
            ]
        ];

        foreach ($instrumenUniversitasNAKEM as $data) {
            $existingInstrumenCount = Instrumen::where('kategori_id', $kategori['NAKEM']->id)->count();
            $instrumen = Instrumen::create([
                'peraturan_id' => $peraturan->id,
                'standar_id' => $standar['Non']->id,
                'kategori_id' => $kategori['NAKEM']->id,
                'level_id' => $level['universitas']->id,
                'jenis_pertanyaan_id' => $data['jenisPertanyaan'],
                'pernyataan' => $data['pernyataan'],
                'indikator' => $data['pernyataan'],

            ]);

            $kode = $standar['Non']->kode . $kategori['NAKEM']->kode . '-' . ($existingInstrumenCount + 1);

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

        // Universitas NON Keuangan
        $instrumenUniversitasNAKEU = [
            [
                'pernyataan' => 'Tersedia dokumen rancangan anggaran',
                'kriteriaBM' => 'Tidak tersedia dokumen rancangan anggaran',
                'kriteriaM' => 'Tersedia dokumen rancangan anggaran',
                'kriteriaML' => 'Tersedia dokumen rancangan anggaran dan dilakukan monitoring serta evaluasi secara berkala',
                'jabatans' => ['WR II'],
                'units' => ['WR'],
                'jenisPertanyaan' => $jenisPertanyaan['text']
            ],
            [
                'pernyataan' => 'Tersedia dokumen kebijakan pembiayaan mahasiswa yang berpotensi secara akademik dan kurang mampu',
                'kriteriaBM' => 'Tidak tersedia dokumen kebijakan pembiayaan mahasiswa yang berpotensi secara akademik dan kurang mampu',
                'kriteriaM' => 'Tersedia dokumen kebijakan pembiayaan mahasiswa yang berpotensi secara akademik dan kurang mampu',
                'kriteriaML' => 'Tersedia dokumen kebijakan pembiayaan mahasiswa yang berpotensi secara akademik dan kurang mampu dan disosialisasikan secara berkala serta dipublikasikan',
                'jabatans' => ['WR I'],
                'units' => ['WR'],
                'jenisPertanyaan' => $jenisPertanyaan['text']
            ],
            [
                'pernyataan' => 'Tersedia Unit Pengendali dan Monitoring Rencana Anggaran (SPI)',
                'kriteriaBM' => 'Tidak tersedia Unit Pengendali dan Monitoring Rencana Anggaran (SPI)',
                'kriteriaM' => 'Tersedia Unit Pengendali dan Monitoring Rencana Anggaran (SPI)',
                'kriteriaML' => '',
                'jabatans' => ['WR II'],
                'units' => ['WR'],
                'jenisPertanyaan' => $jenisPertanyaan['text']
            ],
            [
                'pernyataan' => 'Tersedia dokumen hasil audit, monitoring dan evaluasi keuangan',
                'kriteriaBM' => 'Tidak tersedia dokumen hasil audit, monitoring dan evaluasi keuangan',
                'kriteriaM' => 'Tersedia dokumen hasil audit, monitoring dan evaluasi keuangan',
                'kriteriaML' => 'Tersedia dokumen hasil audit, monitoring dan evaluasi keuangan dan ditindak lanjuti secara berkala',
                'jabatans' => ['WR II'],
                'units' => ['WR'],
                'jenisPertanyaan' => $jenisPertanyaan['text']
            ]
        ];

        foreach ($instrumenUniversitasNAKEU as $data) {
            $existingInstrumenCount = Instrumen::where('kategori_id', $kategori['NAKEU']->id)->count();
            $instrumen = Instrumen::create([
                'peraturan_id' => $peraturan->id,
                'standar_id' => $standar['Non']->id,
                'kategori_id' => $kategori['NAKEU']->id,
                'level_id' => $level['universitas']->id,
                'jenis_pertanyaan_id' => $data['jenisPertanyaan'],
                'pernyataan' => $data['pernyataan'],
                'indikator' => $data['pernyataan'],

            ]);

            $kode = $standar['Non']->kode . $kategori['NAKEU']->kode . '-' . ($existingInstrumenCount + 1);

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

        // Universitas NON SARPRAS
        $instrumenUniversitasNASAP = [
            [
                'pernyataan' => 'Tersedia sarana prasarana umum di lingkungan universitas (guest house, masjid, asrama mahasiswa, GOR, poli klinik, Bis, Koperasi, dan Perpustakaan)',
                'kriteriaBM' => 'Tidak tersedia sarana prasarana umum di lingkungan universitas',
                'kriteriaM' => 'Tersedia sarana prasarana umum di lingkungan universitas',
                'kriteriaML' => 'Tersedia sarana prasarana umum di lingkungan universitas dan dilakukan monitoring dan evaluasi secara berkala',
                'jabatans' => ['WR II'],
                'units' => ['WR'],
                'jenisPertanyaan' => $jenisPertanyaan['text']
            ],
        ];

        foreach ($instrumenUniversitasNASAP as $data) {
            $existingInstrumenCount = Instrumen::where('kategori_id', $kategori['NASAP']->id)->count();
            $instrumen = Instrumen::create([
                'peraturan_id' => $peraturan->id,
                'standar_id' => $standar['Non']->id,
                'kategori_id' => $kategori['NASAP']->id,
                'level_id' => $level['universitas']->id,
                'jenis_pertanyaan_id' => $data['jenisPertanyaan'],
                'pernyataan' => $data['pernyataan'],
                'indikator' => $data['pernyataan'],

            ]);

            $kode = $standar['Non']->kode . $kategori['NASAP']->kode . '-' . ($existingInstrumenCount + 1);

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
    }
}
