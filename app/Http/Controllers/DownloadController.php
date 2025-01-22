<?php

namespace App\Http\Controllers;

use App\Models\AuditeeAuditor;
use App\Models\Auditor;
use App\Models\BeritaAcara;
use App\Models\BeritaAcaraAuditee;
use App\Models\BeritaAcaraAuditor;
use App\Models\Fakultas;
use App\Models\Instrumen;
use App\Models\JadwalAudit;
use App\Models\JawabanAuditee;
use App\Models\JawabanAuditor;
use App\Models\Kriteria;
use App\Models\Laporan;
use App\Models\LaporanAuditee;
use App\Models\LaporanAuditor;
use App\Models\LaporanForm;
use App\Models\Link;
use App\Models\Prodi;
use App\Models\Ptk;
use App\Models\PtkAuditee;
use App\Models\PtkAuditor;
use App\Models\PtkForm;
use App\Models\PtkFormDeskripsi;
use App\Models\PtkFormRencana;
use App\Models\StatusAuditAuditee;
use App\Models\StatusAuditAuditor;
use App\Models\Unit;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DownloadController extends Controller
{
    // Global
    public function __construct()
    {
        // Format Tgl
        \Carbon\Carbon::setLocale('id');
    }


    // Helper
    // Get Unit
    private function get_unit($query)
    {
        $unit = null;
        $unitName = null;
        $unitJenjang = null;
        $type = null;

        if (isset($query->prodi_id)) {
            $unit = DB::table('prodi')
                ->where('prodi.id', $query->prodi_id)
                ->leftJoin('jenjang', 'prodi.jenjang_id', '=', 'jenjang.id')
                ->select('prodi.nama as nama', 'jenjang.nama as jenjang')
                ->first();
            if ($unit) {
                $unitName = "Program Studi {$unit->nama} {$unit->jenjang}";
                $unitJenjang = "{$unit->nama} {$unit->jenjang}";
                $type = "Program Studi";
            }
        } elseif (isset($query->fakultas_id)) {
            $unit = DB::table('fakultas')->find($query->fakultas_id);
            if ($unit) {
                $unitName = "Fakultas {$unit->nama}";
                $type = "Fakultas";
            }
        } elseif (isset($query->unit_id)) {
            $unit = DB::table('unit')->find($query->unit_id);
            if ($unit) {
                $unitName = $unit->nama;
                $type = "Unit";
            }
        }

        return compact('unit', 'unitName', 'unitJenjang', 'type');
    }

    // Auditan
    private function auditan($templateProcessor, $auditan, $unitData, $title)
    {
        // Auditan
        if ($auditan && $auditan->approve) {
            $auditanData = $auditan->approve
                ? $title . " " . ($unitData ? $unitData['unitName'] : '') .
                " telah ditandatangani oleh " . $auditan->nama .
                " | " . \Carbon\Carbon::parse($auditan->updated_at)->format('H:i:s') .
                " | " . \Carbon\Carbon::parse($auditan->updated_at)->isoFormat('D MMMM YYYY')
                : '';

            $this->set_barcode($templateProcessor, 'image_auditee', $auditanData, $auditan->id);
            $templateProcessor->setValue('auditee', $auditan->nama);
        } else {
            $templateProcessor->setValue('image_auditee', '');
            $templateProcessor->setValue('auditee', $auditan->nama);
        }
    }

    // Auditor
    private function auditors($templateProcessor, $auditors, $unitData, $title)
    {
        foreach ($auditors as $index => $auditor) {
            $key = $index + 1;

            if ($auditor->approve) {
                $createdTime = \Carbon\Carbon::parse($auditor->updated_at);
                $auditorData = $title . " " . ($unitData ? $unitData['unitName'] : '') .
                    " telah ditandatangani oleh " . $auditor->nama .
                    " | " . $createdTime->format('H:i:s') . " | " . $createdTime->isoFormat('D MMMM YYYY');

                $this->set_barcode($templateProcessor, "image_auditor#$key", $auditorData, $auditor->id);
            } else {
                $templateProcessor->setValue("image_auditor#$key", '');
            }

            $templateProcessor->setValue("nomor#$key", "$key. ");
            $templateProcessor->setValue("no_auditor#$key", "Auditor $key,");
            $templateProcessor->setValue("auditor#$key", $auditor->nama);
        }

        $totalAuditors = count($auditors);
        for ($key = $totalAuditors + 1; $key <= 3; $key++) {
            $templateProcessor->setValues([
                "nomor#$key" => '',
                "no_auditor#$key" => '',
                "auditor#$key" => '',
                "image_auditor#$key" => '',
            ]);
        }
    }

    // Set QrCode
    private function set_barcode($templateProcessor, $field, $data, $id)
    {
        $writer = new PngWriter();
        $qrCode = QrCode::create($data)->setEncoding(new Encoding('UTF-8'))->setErrorCorrectionLevel(ErrorCorrectionLevel::Low);
        $qrCodeBinary = $writer->write($qrCode)->getString();

        $tempImagePath = tempnam(sys_get_temp_dir(), 'qrcode_' . $id) . '.png';
        file_put_contents($tempImagePath, $qrCodeBinary);

        $templateProcessor->setImageValue($field, $tempImagePath);
        unlink($tempImagePath);
    }

    // Return Download Word
    private function word($templateProcessor, $unit, $date, $title)
    {
        $fileName = $title . ' ' . ($unit ? $unit['unitName'] : '') . ' ' . ($date ? $date->isoFormat('D MMMM YYYY') : '') . '.docx';
        $filePath = storage_path('app/public/' . $fileName);
        $templateProcessor->saveAs($filePath);

        return response()->download($filePath)->deleteFileAfterSend(true);
    }

    // Return Download Zip
    private function zip($templateProcessor, $unit, $date, $title)
    {
        $fileName = $title . ' ' . ($unit ? $unit['unitName'] : '') . ' ' . ($date ? $date->isoFormat('D MMMM YYYY') : '') . '.docx';
        $filePath = storage_path('app/public/' . $fileName);
        $templateProcessor->saveAs($filePath);

        return [
            'filePath' => $filePath,
            'fileName' => $fileName,
        ];
    }

    // Berita Acara 
    private function berita_acara($id)
    {
        // Title
        $title = "Berita Acara";

        // Berita Acara
        $beritaAcara = DB::table('berita_acara')->where('id', $id)->first();

        // Auditan
        $auditan = DB::table('berita_acara_auditee as ba_auditan')
            ->where('ba_auditan.berita_acara_id', $id)
            ->leftJoin('auditee', 'ba_auditan.auditee_id', '=', 'auditee.id')
            ->leftJoin('users', 'auditee.user_id', '=', 'users.id')
            ->select(
                'auditee.id as id',
                'users.name as nama',
                'ba_auditan.approve as approve',
                'ba_auditan.updated_at as updated_at'
            )
            ->first();

        // Auditors
        $auditors = DB::table('berita_acara_auditor as ba_auditor')
            ->where('ba_auditor.berita_acara_id', $id)
            ->leftJoin('auditor', 'ba_auditor.auditor_id', '=', 'auditor.id')
            ->leftJoin('users', 'auditor.user_id', '=', 'users.id')
            ->select(
                'auditor.id as id',
                'users.name as nama',
                'ba_auditor.approve as approve',
                'ba_auditor.created_at as created_at',
                'ba_auditor.updated_at as updated_at',
            )
            ->orderBy('ba_auditor.created_at', 'asc')
            ->get();

        // Unit Data
        $unitData = $this->get_unit($beritaAcara);
        $date = \Carbon\Carbon::parse($beritaAcara->tgl);

        // Template
        $templateProcessor = new \PhpOffice\PhpWord\TemplateProcessor(storage_path('app/public/template/template_berita_acara.docx'));

        // Isi value template
        $templateProcessor->setValues([
            'hari' => $date->isoFormat('dddd'),
            'tanggal' => $date->format('d'),
            'bulan' => $date->isoFormat('MMMM'),
            'tahun' => $date->format('Y'),
            'date' => $date->isoFormat('D MMMM YYYY'),
            'unit' => $unitData ? ($unitData['type'] == 'Program Studi' ? $unitData['unitJenjang'] : $unitData['unit']->nama) : '',
            'type' => $unitData ? $unitData['type'] : '',
        ]);

        $this->auditan($templateProcessor, $auditan, $unitData, $title);
        $this->auditors($templateProcessor, $auditors, $unitData, $title);

        return compact('templateProcessor', 'unitData', 'date', 'title');
    }

    // Berita Acara Word
    public function berita_acara_word(string $id)
    {
        try {
            $ba = $this->berita_acara($id);

            return $this->word($ba['templateProcessor'], $ba['unitData'], $ba['date'], $ba['title']);
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan saat mengunduh Berita Acara!');
        }
    }

    // Temuan Negatif
    private function temuan_negatif($id)
    {
        // Title
        $title = "Temuan Negatif";

        // Ptk
        $ptk = DB::table('ptk')->where('id', $id)->first();

        // Ptk Form
        $form = DB::table('ptk_form')
            ->where('ptk_form.ptk_id', $ptk->id)
            ->leftJoin('ptk_form_deskripsi', 'ptk_form.form_id', '=', 'ptk_form_deskripsi.form_id')
            ->leftJoin('ptk_form_rencana', 'ptk_form.ptk_id', '=', 'ptk_form_rencana.ptk_id')
            ->leftJoin('form', 'ptk_form.form_id', '=', 'form.id')
            ->leftJoin('instrumen', 'form.instrumen_id', '=', 'instrumen.id')
            ->select(
                'instrumen.kode as kode',
                'ptk_form_deskripsi.deskripsi as deskripsi',
                'ptk_form_rencana.rencana as rencana',
                'ptk_form.analisis as analisis',
                'ptk_form.akibat as akibat',
                'ptk_form.kategori_temuan as kategori_temuan',
                'ptk_form.target as target',
                'ptk_form.pic as pic'
            )
            ->get();

        // Auditan
        $auditan = DB::table('ptk_auditee as ptk_auditan')
            ->where('ptk_auditan.ptk_id', $id)
            ->leftJoin('auditee', 'ptk_auditan.auditee_id', '=', 'auditee.id')
            ->leftJoin('users', 'auditee.user_id', '=', 'users.id')
            ->select(
                'auditee.id as id',
                'users.name as nama',
                'ptk_auditan.approve as approve',
                'ptk_auditan.updated_at as updated_at'
            )
            ->first();

        // Auditors
        $auditors = DB::table('ptk_auditor as ptk_auditor')
            ->where('ptk_auditor.ptk_id', $id)
            ->leftJoin('auditor', 'ptk_auditor.auditor_id', '=', 'auditor.id')
            ->leftJoin('users', 'auditor.user_id', '=', 'users.id')
            ->select(
                'auditor.id as id',
                'users.name as nama',
                'ptk_auditor.approve as approve',
                'ptk_auditor.created_at as created_at',
                'ptk_auditor.updated_at as updated_at',
            )
            ->orderBy('ptk_auditor.created_at', 'asc')
            ->get();

        // Group PTK Form
        $group = [];

        foreach ($form as $row) {
            $key = $row->kode;

            if (!isset($group[$key])) {
                $group[$key] = [
                    'kode' => $row->kode,
                    'deskripsi' => [],
                    'rencana' => [],
                    'analisis' => htmlspecialchars($row->analisis, ENT_QUOTES, 'UTF-8'),
                    'akibat' => htmlspecialchars($row->akibat, ENT_QUOTES, 'UTF-8'),
                    'kategori_temuan' => $row->kategori_temuan,
                    'target' => htmlspecialchars($row->target, ENT_QUOTES, 'UTF-8'),
                    'pic' => htmlspecialchars($row->pic, ENT_QUOTES, 'UTF-8'),
                ];
            }

            // Gabungkan deskripsi dan rencana ke dalam array
            if ($row->deskripsi && !in_array($row->deskripsi, $group[$key]['deskripsi'])) {
                $group[$key]['deskripsi'][] = htmlspecialchars($row->deskripsi, ENT_QUOTES, 'UTF-8');
            }

            if ($row->rencana && !in_array($row->rencana, $group[$key]['rencana'])) {
                $group[$key]['rencana'][] = htmlspecialchars($row->rencana, ENT_QUOTES, 'UTF-8');
            }
        }

        // Unit Data
        $unitData = $this->get_unit($ptk);

        // Template
        $templateProcessor = new \PhpOffice\PhpWord\TemplateProcessor(storage_path('app/public/template/template_ptk.docx'));

        \Carbon\Carbon::setLocale('id');
        $date = \Carbon\Carbon::parse($ptk->tgl);
        $templateProcessor->setValues([
            'date' => $date->isoFormat('D MMMM YYYY'),
            'unit' => $unitData ? $unitData['unit']->nama : ''
        ]);

        // Isi Value di template
        $noDesc = 1;
        $noRencana = 1;
        $noAnalisis = 1;
        $noAkibat = 1;
        $noTarget = 1;
        $noPic = 1;
        $valueDesc = [];
        $valueRencana = [];
        $valueAnalisis = [];
        $valueAkibat = [];
        $valueTarget = [];
        $valuePic = [];
        $valueTemuan = [];
        $referensi = [];

        foreach ($group as $key => $item) {
            // Referensi
            $referensi[] = $key;

            // Temuan
            $observasi = $item['kategori_temuan'] == 'observasi' ? '✔' : '';
            $minor = $item['kategori_temuan'] == 'minor' ? '✔' : '';
            $mayor = $item['kategori_temuan'] == 'mayor' ? '✔' : '';

            foreach ($item['deskripsi'] as $index => $row) {
                $valueTemuan[] = [
                    'noTemuan' => $index + 1,
                    'temuan' => $row,
                    'observasi' => $observasi,
                    'minor' => $minor,
                    'mayor' => $mayor,
                ];
            }

            // Desc
            $valueDesc[] = [
                'noDeskripsi' => ($noDesc++) . '. ',
                'kodeDeskripsi' => 'Instrumen ' . $key,
                'deskripsi' => '- ' . implode("<w:br/>", $item['deskripsi'])
            ];

            // Analisis
            $valueAnalisis[] = [
                'noAnalisis' => ($noAnalisis++) . '. ',
                'kodeAnalisis' => 'Instrumen ' . $key,
                'analisis' => $item['analisis'],
            ];

            // Akibat
            $valueAkibat[] = [
                'noAkibat' => ($noAkibat++) . '. ',
                'kodeAkibat' => 'Instrumen ' . $key,
                'akibat' => $item['akibat'],
            ];

            // Rencana
            $valueRencana[] = [
                'noRencana' => ($noRencana++) . '. ',
                'kodeRencana' => 'Instrumen ' . $key,
                'rencana' => '- ' . implode("<w:br/>", $item['rencana'])
            ];

            // Target
            $valueTarget[] = [
                'noTarget' => ($noTarget++) . '. ',
                'kodeTarget' => 'Instrumen ' . $key,
                'target' => $item['target'],
            ];

            // PIC
            $valuePic[] = [
                'noPic' => ($noPic++) . '. ',
                'kodePic' => 'Instrumen ' . $key,
                'pic' => $item['pic'],
            ];
        }

        // Referensi
        $templateProcessor->setValue('referensi', implode(", ", $referensi));

        // Temuan
        if (!empty($valueTemuan)) {
            $templateProcessor->cloneRowAndSetValues('noTemuan', $valueTemuan);
        } else {
            $templateProcessor->cloneRowAndSetValues('noTemuan', [[
                'noTemuan' => 1,
                'temuan' => '',
                'observasi' => '',
                'minor' => '',
                'mayor' => '',
            ]]);
        }

        // Deskripsi
        if (!empty($valueDesc)) {
            $templateProcessor->cloneRowAndSetValues('noDeskripsi', $valueDesc);
        } else {
            $templateProcessor->cloneRowAndSetValues('noDeskripsi', [['noDeskripsi' => '', 'kodeDeskripsi' => '', 'deskripsi' => '']]);
        }

        // Analisis
        $templateProcessor->cloneRowAndSetValues('noAnalisis', $valueAnalisis);

        // Akibat
        $templateProcessor->cloneRowAndSetValues('noAkibat', $valueAkibat);

        // Rencana
        if (!empty($valueRencana)) {
            $templateProcessor->cloneRowAndSetValues('noRencana', $valueRencana);
        } else {
            $templateProcessor->cloneRowAndSetValues('noRencana', [['noRencana' => '', 'kodeRencana' => '', 'rencana' => '']]);
        }

        // Target
        $templateProcessor->cloneBlock('block_name', 0, true, false, $valueTarget);

        // Pic
        $templateProcessor->cloneBlock('block_pic', 0, true, false, $valuePic);

        $this->auditan($templateProcessor, $auditan, $unitData, $title);
        $this->auditors($templateProcessor, $auditors, $unitData, $title);

        return compact('templateProcessor', 'unitData', 'date', 'title');
    }

    // Temuan Negatif Word
    public function temuan_negatif_word(string $id)
    {
        try {
            $tn = $this->temuan_negatif($id);

            return $this->word($tn['templateProcessor'], $tn['unitData'], $tn['date'], $tn['title']);
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan saat mengunduh Temuan Negatif!');
        }
    }

    // Temuan Positif
    private function temuan_positif($id)
    {
        // Title
        $title = "Temuan Positif";

        // Berita Acara
        $tp = DB::table('laporan')->where('id', $id)->first();

        // Laporan Form
        $form = DB::table('laporan_form')->where('laporan_id', $id)->get();

        // Auditan
        $auditan = DB::table('laporan_auditee as laporan_auditan')
            ->where('laporan_auditan.laporan_id', $id)
            ->leftJoin('auditee', 'laporan_auditan.auditee_id', '=', 'auditee.id')
            ->leftJoin('users', 'auditee.user_id', '=', 'users.id')
            ->select(
                'auditee.id as id',
                'users.name as nama',
                'laporan_auditan.approve as approve',
                'laporan_auditan.updated_at as updated_at'
            )
            ->first();

        // Auditors
        $auditors = DB::table('laporan_auditor as laporan_auditor')
            ->where('laporan_auditor.laporan_id', $id)
            ->leftJoin('auditor', 'laporan_auditor.auditor_id', '=', 'auditor.id')
            ->leftJoin('users', 'auditor.user_id', '=', 'users.id')
            ->select(
                'auditor.id as id',
                'users.name as nama',
                'laporan_auditor.approve as approve',
                'laporan_auditor.created_at as created_at',
                'laporan_auditor.updated_at as updated_at',
            )
            ->orderBy('laporan_auditor.created_at', 'asc')
            ->get();

        // Unit Data
        $unitData = $this->get_unit($tp);
        $date = \Carbon\Carbon::parse($tp->tgl);

        // Template
        $templateProcessor = new \PhpOffice\PhpWord\TemplateProcessor(storage_path('app/public/template/template_laporan.docx'));

        // Isi value template
        $templateProcessor->setValues([
            'date' => $date->isoFormat('D MMMM YYYY'),
            'unit' => $unitData ? $unitData['unit']->nama : ''
        ]);

        $no = 1;
        $values = [];
        foreach ($form as $item) {
            $values[] = [
                'no' => $no++,
                'kelebihan' => htmlspecialchars($item->kelebihan, ENT_QUOTES, 'UTF-8'),
                'ruang' => htmlspecialchars($item->ruang_peningkatan, ENT_QUOTES, 'UTF-8'),
            ];
        }

        if (!empty($valuesPositif)) {
            $templateProcessor->cloneRowAndSetValues('no', $valuesPositif);
        } else {
            $templateProcessor->cloneRowAndSetValues('no', [[
                'no' => 1,
                'kelebihan' => null,
                'ruang' => null,
            ]]);
        }

        $this->auditan($templateProcessor, $auditan, $unitData, $title);
        $this->auditors($templateProcessor, $auditors, $unitData, $title);

        return compact('templateProcessor', 'unitData', 'date', 'title');
    }

    // Temuan Positif Word
    public function temuan_positif_word(string $laporan)
    {
        try {
            $tp = $this->temuan_positif($laporan);

            return $this->word($tp['templateProcessor'], $tp['unitData'], $tp['date'], $tp['title']);
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan saat mengunduh Temuan Positif!');
        }
    }


    // Daftar Tilik
    private function daftar_tilik($jadwalId, $unitId, $type)
    {
        // Title
        $title = "Daftar Tilik";

        $returnType = get_type($type);

        // Jawaban Auditor
        $jawaban = DB::table('jawaban_auditor')
            ->where('jawaban_auditor.jadwal_audit_id', $jadwalId)
            ->where('jawaban_auditor.daftar_tilik', 1)
            ->where('jawaban_auditor.' . $returnType, $unitId)
            ->leftJoin('form', 'jawaban_auditor.form_id', '=', 'form.id')
            ->leftJoin('instrumen', 'form.instrumen_id', '=', 'instrumen.id')
            ->select(
                'instrumen.kode as kode',
                'instrumen.pernyataan as pernyataan',
                'jawaban_auditor.catatan as catatan',
                'jawaban_auditor.prodi_id as prodi_id',
                'jawaban_auditor.fakultas_id as fakultas_id',
                'jawaban_auditor.unit_id as unit_id',
                'jawaban_auditor.created_at as created_at',
            )
            ->orderBy('jawaban_auditor.created_at', 'ASC')
            ->get();

        // Unit Data
        $unitData = $jawaban->isNotEmpty()
            ? $this->get_unit($jawaban->first())
            : $this->get_unit(
                DB::table($type === 'universitas' ? 'unit' : $type)
                    ->where('id', $unitId)
                    ->select("id as " . ($type === 'universitas' ? 'unit_id' : "{$type}_id"))
                    ->first()
            );

        // Date diambil dr status
        $status = DB::table('status_audit_auditor')->where('jadwal_audit_id', $jadwalId)->where($returnType, $unitId)->select('updated_at')->first();

        $date = $status ? \Carbon\Carbon::parse($status->updated_at) : null;

        // Template
        $templateProcessor = new \PhpOffice\PhpWord\TemplateProcessor(storage_path('app/public/template/template_daftar_tilik.docx'));

        // Isi auditor template
        // Auditor
        $auditors = DB::table('auditee_auditor')
            ->where('auditee_auditor.jadwal_audit_id', $jadwalId)
            ->where('auditee_auditor.' . get_type($type), $unitId)
            ->leftJoin('auditor', 'auditee_auditor.auditor_id', '=', 'auditor.id')
            ->leftJoin('users', 'auditor.user_id', '=', 'users.id')
            ->select(
                'users.id as id',
                'users.name as nama',
                'auditee_auditor.created_at as created_at',
            )
            ->orderBy('auditee_auditor.created_at', 'asc')
            ->get()
            ->unique('id')
            ->values();

        if ($auditors->isNotEmpty()) {
            foreach (range(1, 3) as $index) {
                $auditor = $auditors->get($index - 1);
                $templateProcessor->setValue(
                    'auditor#' . $index,
                    $auditor ? $index . '. ' . $auditor->nama : ''
                );
            }
        } else {
            // Jika tidak ada auditor
            foreach (range(1, 3) as $index) {
                $templateProcessor->setValue('auditor#' . $index, $index === 1 ? '-' : '');
            }
        }

        // Isi value template
        $templateProcessor->setValues([
            'date' => $date ? $date->isoFormat('D MMMM YYYY') : '-',
            'unit' => $unitData['unitName'] ?? ''
        ]);

        $no = 1;
        $values = [];
        foreach ($jawaban as $item) {
            $values[] = [
                'no' => $no++,
                'kode' => $item->kode,
                'pernyataan' => $item->pernyataan,
                'catatan' => $item->catatan,
            ];
        }

        if (!empty($values)) {
            $templateProcessor->cloneRowAndSetValues('no', $values);
        } else {
            $templateProcessor->cloneRowAndSetValues('no', [[
                'no' => null,
                'kode' => null,
                'pernyataan' => null,
                'catatan' => null,
            ]]);
        }

        return compact('templateProcessor', 'unitData', 'date', 'title');
    }
    // Daftar Tilik Word
    public function daftar_tilik_word(string $jadwalId, string $unitId, string $type)
    {
        try {
            $daftar_tilik = $this->daftar_tilik($jadwalId, $unitId, $type);

            return $this->word($daftar_tilik['templateProcessor'], $daftar_tilik['unitData'], $daftar_tilik['date'], $daftar_tilik['title']);
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan saat mengunduh Daftar Tilik!');
        }
    }

    // Jawaban Auditan
    private function jawaban_auditan($jadwalId, $unitId, $type)
    {
        // Title
        $title = "Hasil Pengisian Audit";

        $returnType = get_type($type);

        // Jawaban Auditan
        $jawaban = DB::table('jawaban_auditee')
            ->join('form', function ($join) use ($jadwalId) {
                $join->on('jawaban_auditee.form_id', '=', 'form.id')
                    ->where('form.jadwal_id', '=', $jadwalId);
            })
            ->leftJoin('link', function ($join) use ($jadwalId, $unitId, $returnType) {
                $join->on('jawaban_auditee.form_id', '=', 'link.form_id')
                    ->where('link.jadwal_audit_id', '=', $jadwalId)
                    ->where('link.' . $returnType, $unitId);
            })
            ->join('instrumen', 'form.instrumen_id', '=', 'instrumen.id')
            ->where('jawaban_auditee.jadwal_audit_id', $jadwalId)
            ->where('jawaban_auditee.' . $returnType, $unitId)
            ->orderBy('instrumen.standar_id', 'asc')
            ->orderBy('instrumen.kategori_id', 'asc')
            ->orderByRaw("REGEXP_REPLACE(instrumen.kode, '[^0-9]', '', 'g')::int NULLS FIRST, REGEXP_REPLACE(instrumen.kode, '[0-9]', '', 'g') ASC")
            ->select(
                'instrumen.kode as kode',
                'instrumen.pernyataan as pernyataan',
                'jawaban_auditee.jawaban as jawaban',
                'link.link as link',
                'jawaban_auditee.prodi_id as prodi_id',
                'jawaban_auditee.fakultas_id as fakultas_id',
                'jawaban_auditee.unit_id as unit_id',
            )
            ->get();

        // Group link
        $group = [];
        foreach ($jawaban as $row) {
            $key = $row->kode;

            if (!isset($group[$key])) {
                $group[$key] = [
                    'kode' => $row->kode,
                    'pernyataan' => htmlspecialchars($row->pernyataan, ENT_QUOTES, 'UTF-8'),
                    'jawaban' => htmlspecialchars($row->jawaban, ENT_QUOTES, 'UTF-8'),
                    'link' => [],
                    'prodi_id' => $row->prodi_id,
                    'fakultas_id' => $row->fakultas_id,
                    'unit_id' => $row->unit_id,
                ];
            }

            // Gabungkan link ke dalam array
            if ($row->link && !in_array($row->link, $group[$key]['link'])) {
                $group[$key]['link'][] = '- ' . htmlspecialchars($row->link, ENT_QUOTES, 'UTF-8');
            }
        }

        // Unit Data
        $unitData = $jawaban->isNotEmpty()
            ? $this->get_unit($jawaban->first())
            : $this->get_unit(
                DB::table($type === 'universitas' ? 'unit' : $type)
                    ->where('id', $unitId)
                    ->select("id as " . ($type === 'universitas' ? 'unit_id' : "{$type}_id"))
                    ->first()
            );

        // Template
        $templateProcessor = new \PhpOffice\PhpWord\TemplateProcessor(storage_path('app/public/template/template_isi_audit_auditee.docx'));

        // Date diambil dr status
        $status = DB::table('status_audit_auditee')->where('jadwal_audit_id', $jadwalId)->where($returnType, $unitId)->select('updated_at')->first();
        $date = $status ? \Carbon\Carbon::parse($status->updated_at) : null;

        // Isi value template
        $templateProcessor->setValues([
            'date' => $date ? $date->isoFormat('D MMMM YYYY') : '-',
            'unit' => $unitData['unitName'] ?? ''
        ]);

        // Isi auditor template
        // Auditor
        $auditors = DB::table('auditee_auditor')
            ->where('auditee_auditor.jadwal_audit_id', $jadwalId)
            ->where('auditee_auditor.' . get_type($type), $unitId)
            ->leftJoin('auditor', 'auditee_auditor.auditor_id', '=', 'auditor.id')
            ->leftJoin('users', 'auditor.user_id', '=', 'users.id')
            ->select(
                'users.id as id',
                'users.name as nama',
                'auditee_auditor.created_at as created_at',
            )
            ->orderBy('auditee_auditor.created_at', 'asc')
            ->get()
            ->unique('id')
            ->values();

        // Isi nilai auditor dalam template
        if ($auditors->isNotEmpty()) {
            foreach (range(1, 3) as $index) {
                $auditor = $auditors->get($index - 1);
                $templateProcessor->setValue(
                    'auditor#' . $index,
                    $auditor ? $index . '. ' . $auditor->nama : ''
                );
            }
        } else {
            // Jika tidak ada auditor
            foreach (range(1, 3) as $index) {
                $templateProcessor->setValue('auditor#' . $index, $index === 1 ? '-' : '');
            }
        }

        // Isi
        $no = 1;
        $values = [];

        foreach ($group as $key => $item) {
            $values[] = [
                'no' => $no++,
                'kode' => $item['kode'],
                'pernyataan' => $item['pernyataan'],
                'jawaban' => $item['jawaban'],
                'link' => implode("<w:br/>", $item['link'])
            ];
        }

        // Jika belum ada link tp ada jawaban
        if (empty($values) && $jawaban->isNotEmpty()) {
            foreach ($group as $key => $item) {
                $values[] = [
                    'no' => $no++,
                    'kode' => $item['kode'],
                    'pernyataan' => $item['pernyataan'],
                    'jawaban' => $item['jawaban'],
                    'link' => ''
                ];
            }

            $templateProcessor->cloneRowAndSetValues('no', $values);
        } elseif (!empty($values)) {
            $templateProcessor->cloneRowAndSetValues('no', $values);
        } else {
            $templateProcessor->cloneRowAndSetValues('no', [['no' => '', 'kode' => '', 'pernyataan' => '', 'jawaban' => '', 'link' => '']]);
        }

        return compact('templateProcessor', 'unitData', 'date', 'title');
    }

    // Jawaban Auditan Word
    public function jawaban_auditan_word(string $jadwalId, string $unitId, string $type)
    {
        $jawaban_auditan = $this->jawaban_auditan($jadwalId, $unitId, $type);

        return $this->word($jawaban_auditan['templateProcessor'], $jawaban_auditan['unitData'], $jawaban_auditan['date'], $jawaban_auditan['title']);
    }

    // Download All Instrumen
    public function download_instrumen()
    {
        // Instrumen
        $instrumen = DB::table('instrumen')
            ->leftJoin('standar', 'instrumen.standar_id', '=', 'standar.id')
            ->leftJoin('kategori', 'instrumen.standar_id', '=', 'kategori.standar_id')
            ->leftJoin('instrumen_kriteria', 'instrumen.id', '=', 'instrumen_kriteria.instrumen_id')
            ->leftJoin('kriteria', 'instrumen_kriteria.kriteria_id', '=', 'kriteria.id')
            ->leftJoin('instrumen_jenjang', 'instrumen.id', '=', 'instrumen_jenjang.instrumen_id')
            ->leftJoin('prodi', 'instrumen_jenjang.prodi_id', '=', 'prodi.id')
            ->leftJoin('unit', 'instrumen_jenjang.unit_id', '=', 'unit.id')
            ->leftJoin('jenjang', 'instrumen_jenjang.jenjang_id', '=', 'jenjang.id')
            ->leftJoin('instrumen_jabatan', 'instrumen.id', '=', 'instrumen_jabatan.instrumen_id')
            ->leftJoin('jabatan', 'instrumen_jabatan.jabatan_id', '=', 'jabatan.id')
            ->leftJoin('level', 'instrumen.level_id', '=', 'level.id')
            ->orderBy('instrumen.level_id', 'asc')
            ->orderBy('instrumen.standar_id', 'asc')
            ->orderBy('instrumen.kategori_id', 'asc')
            ->orderByRaw("REGEXP_REPLACE(instrumen.kode, '[^0-9]', '', 'g')::int NULLS FIRST, REGEXP_REPLACE(instrumen.kode, '[0-9]', '', 'g') ASC")
            ->select(
                'instrumen.kode as kode',
                DB::raw("CONCAT(standar.nama, ' ', kategori.nama) as standar"),
                'instrumen.indikator as indikator',
                'level.nama as level',
                'kriteria.nama as jenis_kriteria',
                'instrumen_kriteria.isi as isi_kriteria',
                'jenjang.nama as jenjang',
                'prodi.nama as prodi',
                'unit.nama as unit',
                'jabatan.nama as jabatan',
            )
            ->get();

        // Group Instrumen
        $group = $instrumen->groupBy('kode')->map(function ($items, $kode) {
            return [
                'kode' => $kode,
                'standar' => $items->first()->standar,
                'indikator' => $items->first()->indikator,
                'level' => $items->first()->level,
                'prodi' => $items->first()->prodi,
                'unit' => $items->first()->unit,
                'jabatan' => $items->first()->jabatan,
                'kriteria' => [
                    'Belum Memenuhi' => $items->where('jenis_kriteria', 'Belum Memenuhi')->pluck('isi_kriteria')->first(),
                    'Memenuhi' => $items->where('jenis_kriteria', 'Memenuhi')->pluck('isi_kriteria')->first(),
                    'Melampaui' => $items->where('jenis_kriteria', 'Melampaui')->pluck('isi_kriteria')->first(),
                ],
                'jenjang_audience' => [
                    'jenjang' => $items->pluck('jenjang')->filter()->unique()->values()->toArray(),
                    'prodi' => $items->pluck('prodi')->filter()->unique()->values()->toArray(),
                    'unit' => $items->pluck('unit')->filter()->unique()->values()->toArray(),
                    'jabatan' => $items->pluck('jabatan')->filter()->unique()->values()->toArray(),
                ],
            ];
        })->values();

        // Template
        $templateProcessor = new \PhpOffice\PhpWord\TemplateProcessor(storage_path('app/public/template/template_instrumen.docx'));

        // Kriteria
        $kriteria = DB::table('kriteria')->select('id', 'nama')->orderBy('id')->get();

        $no = 1;
        foreach ($kriteria as $item) {
            $templateProcessor->setValue('kriteria#' . $no, $item->nama);
            $no++;
        }

        // Isi value template
        $values = [];
        $no = 1;

        foreach ($group as $item) {
            // Data
            $data = [
                'no' => htmlspecialchars($item['standar'], ENT_QUOTES, 'UTF-8'),
                'kode' => htmlspecialchars($item['kode'], ENT_QUOTES, 'UTF-8'),
                'indikator' => htmlspecialchars($item['indikator'], ENT_QUOTES, 'UTF-8'),
                'level' => htmlspecialchars($item['level'], ENT_QUOTES, 'UTF-8'),
            ];

            // Kriteria
            $noKriteria = 1;
            foreach ($item['kriteria'] as $key => $kriteria) {
                if ($kriteria) {
                    $data['instrumenKriteria' . $noKriteria] = htmlspecialchars($kriteria, ENT_QUOTES, 'UTF-8');
                } else {
                    $data['instrumenKriteria' . $noKriteria] = '-';
                }
                $noKriteria++;
            }

            // Jenjang
            foreach ($item['jenjang_audience'] as $key => $jenjang) {
                if (!empty($jenjang)) {
                    $data['jenjang'][] = implode(', ', $jenjang);
                }
            }

            if (!empty($data['jenjang'])) {
                $data['jenjang'] = implode(' | ', $data['jenjang']);
            } else {
                $data['jenjang'] = '-';
            }


            $values[] = $data;
        }

        $templateProcessor->cloneRowAndSetValues('no', $values);

        // Nama File
        $fileName = 'Instrumen Pengukuran Standar DIKTI Universitas Jenderal Soedirman' . '.docx';
        $filePath = storage_path('app/public/' . $fileName);
        $templateProcessor->saveAs($filePath);

        return response()->download($filePath)->deleteFileAfterSend(true);

        dd($group);


        return view('login_pusjamu');
    }

    // Download Daftar Auditan dan Auditor
    public function download_auditan_auditor(string $jadwalAudit)
    {
        // Title
        $title = "Daftar Auditee dan Auditor";

        // Jadwal Audit
        $jadwalAudit = DB::table('jadwal_audit')->where('id', $jadwalAudit)->first();

        // Prodi
        $auditorProdi = DB::table('prodi')
            ->join('jenjang', 'prodi.jenjang_id', '=', 'jenjang.id')
            ->join('fakultas', 'prodi.fakultas_id', '=', 'fakultas.id')
            ->leftJoin('auditee_auditor', 'prodi.id', '=', 'auditee_auditor.prodi_id')
            ->leftJoin('auditor', 'auditee_auditor.auditor_id', '=', 'auditor.id')
            ->leftJoin('users', 'auditor.user_id', '=', 'users.id')
            ->select(
                'prodi.id as id',
                DB::raw("CONCAT(prodi.nama, ' ', jenjang.nama) as nama"),
                'fakultas.nama as fakultas',
                'users.id as auditor_id',
                'users.name as auditor',
                'auditee_auditor.created_at as auditor_created_at'
            )
            ->orderBy('prodi.created_at')
            ->orderBy('auditee_auditor.created_at')
            ->get()
            ->groupBy('id')
            ->map(function ($items) {
                return [
                    'auditan' => $items->first()->nama,
                    'auditors' => $items->unique('auditor_id')->map(function ($auditor) {
                        return [
                            'auditor_id' => $auditor->auditor_id,
                            'auditor_name' => $auditor->auditor,
                            'created_at' => $auditor->auditor_created_at,
                        ];
                    })->values(),
                    'fakultas' => $items->first()->fakultas,
                ];
            });

        // Fakultas
        $auditorFakultas = DB::table('fakultas')
            ->leftJoin('auditee_auditor', 'fakultas.id', '=', 'auditee_auditor.fakultas_id')
            ->leftJoin('auditor', 'auditee_auditor.auditor_id', '=', 'auditor.id')
            ->leftJoin('users', 'auditor.user_id', '=', 'users.id')
            ->select(
                'fakultas.id as id',
                DB::raw("CONCAT('Fakultas ', fakultas.nama) as nama"),
                'users.id as auditor_id',
                'users.name as auditor',
                'auditee_auditor.created_at as auditor_created_at'
            )
            ->orderBy('fakultas.created_at')
            ->orderBy('auditee_auditor.created_at')
            ->get()
            ->groupBy('id')
            ->map(function ($items) {
                return [
                    'auditan' => $items->first()->nama,
                    'auditors' => $items->unique('auditor_id')->map(function ($auditor) {
                        return [
                            'auditor_id' => $auditor->auditor_id,
                            'auditor_name' => $auditor->auditor,
                            'created_at' => $auditor->auditor_created_at,
                        ];
                    })->values(),
                    'fakultas' => null,
                ];
            });

        // Unit
        $auditorUnit = DB::table('unit')
            ->leftJoin('auditee_auditor', 'unit.id', '=', 'auditee_auditor.unit_id')
            ->leftJoin('auditor', 'auditee_auditor.auditor_id', '=', 'auditor.id')
            ->leftJoin('users', 'auditor.user_id', '=', 'users.id')
            ->select(
                'unit.id as id',
                'unit.nama as nama',
                'users.id as auditor_id',
                'users.name as auditor',
                'auditee_auditor.created_at as auditor_created_at'
            )
            ->orderBy('unit.created_at')
            ->orderBy('auditee_auditor.created_at')
            ->get()
            ->groupBy('id')
            ->map(function ($items) {
                return [
                    'auditan' => $items->first()->nama,
                    'auditors' => $items->unique('auditor_id')->map(function ($auditor) {
                        return [
                            'auditor_id' => $auditor->auditor_id,
                            'auditor_name' => $auditor->auditor,
                            'created_at' => $auditor->auditor_created_at,
                        ];
                    })->values(),
                    'fakultas' => null,
                ];
            });

        // Merge 
        $merge = $auditorProdi
            ->merge($auditorFakultas)
            ->merge($auditorUnit)
            ->values();


        // Template
        $templateProcessor = new \PhpOffice\PhpWord\TemplateProcessor(storage_path('app/public/template/template_daftar_auditor.docx'));

        // Date
        $date = \Carbon\Carbon::parse($jadwalAudit->created_at);

        $templateProcessor->setValues([
            'tahun' => $date->isoFormat('YYYY'),
        ]);

        // Isi value template
        $no = 1;
        $values = [];
        foreach ($merge as $item) {
            // Mapping Auditor
            $nama_auditor = $item['auditors']->map(function ($auditor) {
                return $auditor['auditor_name'];
            })->implode(PHP_EOL);

            // Values
            $values[] = [
                'no' => $no++,
                'auditor' => $nama_auditor ? '- ' . str_replace(PHP_EOL, PHP_EOL . '- ', $nama_auditor) : 'Belum ada auditor',
                'auditee' => $item['auditan'] ? $item['auditan'] : 'Belum ada Auditor',
                'fakultas' => $item['fakultas'] ? $item['fakultas'] : '-',
            ];
        }

        // Cek jika kosong
        if (!empty($values)) {
            $templateProcessor->cloneRowAndSetValues('no', $values);
        } else {
            $templateProcessor->cloneRowAndSetValues('no', [[
                'no' => 1,
                'auditor' => null,
                'auditee' => null,
                'fakultas' => null,
            ]]);
        }

        // Nama File
        $fileName = $title . ' ' . $date->isoFormat('YYYY') . '.docx';
        $filePath = storage_path('app/public/' . $fileName);
        $templateProcessor->saveAs($filePath);

        return response()->download($filePath)->deleteFileAfterSend(true);
    }

    // Download user berdasarkan role
    public function download_user(Request $request)
    {
        // Validasi
        $request->validate([
            'role' => 'required|exists:roles,id'
        ]);

        // Tgl
        \Carbon\Carbon::setLocale('id');

        $jam = \Carbon\Carbon::now()->translatedFormat('H:i');
        $tgl = \Carbon\Carbon::now()->translatedFormat('d F Y');

        // Nama File
        $fileNames = [
            2 => "Daftar GKM (JELITA)" . $tgl . ".xls",
            3 => "Daftar GPM (JELITA) " . $tgl . ".xls",
            5 => "Daftar Pimpinan Universitas (JELITA) " . $tgl . ".xls",
            4 => "Daftar Auditor (JELITA) " . $tgl . ".xls",
            6 => "Daftar Dekan dan Wakil Dekan (JELITA) " . $tgl . ".xls",
            7 => "Daftar Ketua Program Studi (JELITA) " . $tgl . ".xls",
        ];

        $fileName = $fileNames[$request->role] ?? "Daftar Pengguna (JELITA) " . $tgl . ".xls";

        // Query
        $query = DB::table('model_has_roles')
            ->join('users', 'model_has_roles.model_id', '=', 'users.id')
            ->leftJoin('jabatan_user', 'jabatan_user.user_id', '=', 'users.id')
            ->leftJoin('jabatan', 'jabatan_user.jabatan_id', '=', 'jabatan.id')
            ->where('model_has_roles.role_id', $request->role)
            ->select('users.name', 'users.email', 'users.no_telepon', 'jabatan.nama as jabatan');

        // Join berdasarkan role
        if ($request->role == 7 || $request->role == 2 || $request->role == 4 || $request->role == 3) {
            $query->leftJoin('prodi', 'jabatan_user.prodi_id', '=', 'prodi.id')
                ->leftJoin('jenjang', 'prodi.jenjang_id', '=', 'jenjang.id')
                ->addSelect(DB::raw("CASE WHEN jabatan.id IS NULL THEN NULL ELSE CONCAT('Program Studi ', prodi.nama, ' ', jenjang.nama) END as prodi"));
        } elseif ($request->role == 6) {
            $query->join('fakultas', 'jabatan_user.fakultas_id', '=', 'fakultas.id')
                ->addSelect(DB::raw("CONCAT('Fakultas ', fakultas.nama) as fakultas"));
        } elseif ($request->role == 5) {
            $query->join('unit', 'jabatan_user.unit_id', '=', 'unit.id')
                ->addSelect('unit.nama as unit');
        }

        $users = $query->get();

        // PHP Spreadsheet
        $templatePath = storage_path('app/public/template/template_user.xlsx');
        $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($templatePath);

        $worksheet = $spreadsheet->getActiveSheet();

        $title = [
            2 => "Daftar GKM (JELITA)",
            3 => "Daftar GPM (JELITA)",
            5 => "Daftar Pimpinan Universitas (JELITA)",
            4 => "Daftar Auditor (JELITA)",
            6 => "Daftar Dekan dan Wakil Dekan (JELITA)",
            7 => "Daftar Ketua Program Studi (JELITA)",
        ];

        $worksheet->getCell('A1')->setValue($title[$request->role]);
        $worksheet->getCell('A2')->setValue("Diunduh pada pukul " . $jam . " tanggal " . $tgl);

        $baris = 5;
        $no = 1;
        foreach ($users as $user) {
            $worksheet->getCell('A' . $baris)->setValue($no++);
            $worksheet->getCell('B' . $baris)->setValue($user->name);
            $worksheet->getCell('C' . $baris)->setValue($user->email);
            $worksheet->getCell('D' . $baris)->setValue($user->jabatan ?? '-');
            $worksheet->getCell('E' . $baris)->setValue($user->prodi ?? $user->fakultas ?? $user->unit ?? '-');
            $worksheet->getCell('F' . $baris)->setValue($user->no_telepon ?? '-');
            $baris++;
        }

        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xls');
        $writer->save($fileName);

        return response()->download($fileName)->deleteFileAfterSend(true);
    }

    // Laporan Hasil PDF
    public function download_laporan_hasil(JadwalAudit $jadwalAudit, Fakultas $fakultas)
    {
        // Data Cover
        $dataCover = [
            'tahun' => \Carbon\Carbon::parse($jadwalAudit->tgl_mulai)->year,
        ];

        // Data Isi
        $dataIsi = [
            'prodis' => DB::table('prodi')
                ->join('jenjang', 'prodi.jenjang_id', '=', 'jenjang.id')
                ->leftJoin('berita_acara', function ($join) use ($jadwalAudit) {
                    $join->on('prodi.id', '=', 'berita_acara.prodi_id')
                        ->where('berita_acara.jadwal_audit_id', $jadwalAudit->id);
                })
                ->leftJoin('auditee', function ($join) use ($jadwalAudit) {
                    $join->on('prodi.id', '=', 'auditee.prodi_id')
                        ->where('auditee.jadwal_audit_id', $jadwalAudit->id);
                })
                ->leftJoin('users as auditee_users', 'auditee.user_id', '=', 'auditee_users.id')
                ->leftJoin('auditee_auditor', function ($join) use ($jadwalAudit) {
                    $join->on('prodi.id', '=', 'auditee_auditor.prodi_id')
                        ->where('auditee_auditor.jadwal_audit_id', $jadwalAudit->id);
                })
                ->leftJoin('auditor', 'auditee_auditor.auditor_id', '=', 'auditor.id')
                ->leftJoin('users as auditor_users', 'auditor.user_id', '=', 'auditor_users.id')
                ->where('prodi.fakultas_id', $fakultas->id)
                ->select([
                    'prodi.id as id',
                    'prodi.nama as nama',
                    'jenjang.id as jenjang_id',
                    'jenjang.nama as jenjang',
                    'berita_acara.tgl as tgl_audit',
                    DB::raw('STRING_AGG(DISTINCT auditee_users.name, \'|\') as auditan'),
                    DB::raw('STRING_AGG(DISTINCT auditor_users.name, \'|\') as auditor'),
                    DB::raw('STRING_AGG(DISTINCT TO_CHAR(auditee_auditor.created_at, \'YYYY-MM-DD HH24:MI:SS\'), \'|\') as auditors_created_at'),
                ])
                ->groupBy('prodi.id', 'jenjang.id', 'berita_acara.tgl')
                ->orderBy('auditors_created_at', 'asc')
                ->get()
                ->map(function ($prodi) use ($jadwalAudit) {
                    // Instrumen Form
                    $instrumen = DB::table('form')
                        ->join('instrumen', 'form.instrumen_id', '=', 'instrumen.id')
                        ->join('instrumen_jenjang', 'instrumen.id', '=', 'instrumen_jenjang.instrumen_id')
                        ->leftJoin('jawaban_auditee', function ($join) use ($prodi) {
                            $join->on('jawaban_auditee.form_id', '=', 'form.id')
                                ->where('jawaban_auditee.prodi_id', $prodi->id);
                        })
                        ->leftJoin('jawaban_auditor', function ($join) use ($prodi) {
                            $join->on('jawaban_auditor.form_id', '=', 'form.id')
                                ->where('jawaban_auditor.prodi_id', $prodi->id);
                        })
                        ->leftJoin('ptk', function ($join) use ($prodi) {
                            $join->on('ptk.jadwal_audit_id', '=', 'form.jadwal_id')
                                ->where('ptk.prodi_id', $prodi->id);
                        })
                        ->leftJoin('ptk_form', function ($join) use ($prodi) {
                            $join->on('ptk_form.ptk_id', '=', 'ptk.id')
                                ->on('ptk_form.form_id', '=', 'form.id');
                        })
                        ->where('form.jadwal_id', $jadwalAudit->id)
                        ->where('instrumen_jenjang.jenjang_id', $prodi->jenjang_id)
                        ->orWhere('instrumen_jenjang.prodi_id', $prodi->id)
                        ->select(
                            'instrumen.pernyataan as pernyataan',
                            'jawaban_auditee.jawaban as jawaban',
                            'jawaban_auditor.catatan as catatan',
                            'ptk_form.kategori_temuan as kategori_temuan',
                            'ptk_form.analisis as analisis',
                        )
                        ->get();


                    $prodi->instrumen = $instrumen;

                    return $prodi;
                }),
        ];

        // MPDF
        $mpdf = new \Mpdf\Mpdf([
            'tempDir' => storage_path('app/tmp'),
        ]);

        // Halaman Cover (Portrait)
        $cover = view('pdf.laporan_fakultas.cover', $dataCover)->render();
        $mpdf->AddPage('P');
        $mpdf->WriteHTML($cover);

        // Page Number
        $mpdf->PageNumSubstitutions[] = [
            'from' => 2,
            'reset' => 1,
            'type' => '1',
            'suppress' => 'off',
        ];

        // Tambahkan halaman baru dengan nomor halaman dimulai dari 1
        $mpdf->AddPage('L'); // landscape
        $mpdf->SetFooter('Halaman {PAGENO} dari {nbpg}');

        // Halaman Isi 
        $isi = view('pdf.laporan_fakultas.isi', $dataIsi)->render();
        $mpdf->WriteHTML($isi);

        // Download
        $namaFile = "Laporan Hasil Audit Fakultas " . $fakultas->nama . " Tahun " . $dataCover['tahun'] . ".pdf";

        return response($mpdf->Output($namaFile, \Mpdf\Output\Destination::STRING_RETURN), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => "attachment; filename=\"$namaFile\"",
            'X-Filename' => $namaFile,
        ]);
    }
}
