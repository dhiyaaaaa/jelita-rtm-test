<?php

namespace App\Http\Controllers;

use App\Models\Fakultas;
use App\Models\JadwalAudit;
use App\Models\RtmJadwal;
use App\Models\RtmTindakLanjut;
use App\Models\RtmLampiran;
use App\Models\JawabanAuditor;
use App\Models\Kriteria;
use App\Models\Laporan;
use App\Models\PtkForm;
use App\Models\LaporanForm;
use App\Models\Prodi;
use App\Models\RtmRtl;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;
use Error;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\Settings;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;


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

        // Title
        $title = [
            2 => "Daftar GKM (JELITA)",
            3 => "Daftar GPM (JELITA)",
            5 => "Daftar Pimpinan Universitas (JELITA)",
            4 => "Daftar Auditor (JELITA)",
            6 => "Daftar Dekan dan Wakil Dekan (JELITA)",
            7 => "Daftar Ketua Program Studi (JELITA)",
        ];

        // Isi value template
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
                        ->join('ptk_form', function ($join) use ($prodi) {
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
        // $this->writeInChunks($mpdf, $isi);

        // Download
        $namaFile = "Laporan Hasil Audit Fakultas " . $fakultas->nama . " Tahun " . $dataCover['tahun'] . ".pdf";

        return response($mpdf->Output($namaFile, \Mpdf\Output\Destination::STRING_RETURN), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => "attachment; filename=\"$namaFile\"",
            'X-Filename' => $namaFile,
        ]);
    }

    private function writeInChunks(\Mpdf\Mpdf $mpdf, $html, $chunkSize = 5000)
    {
        $length = strlen($html);
        for ($start = 0; $start < $length; $start += $chunkSize) {
            $mpdf->WriteHTML(substr($html, $start, $chunkSize));
        }
    }

    // Generate Zip
    private function generate_zip(string $jadwalAudit, string $unitId, string $type)
    {
        // Jadwal Audit
        $jadwal = DB::table('jadwal_audit')->where('id', $jadwalAudit)->select('id', 'tgl_mulai')->first();

        // Daftar Tilik
        $daftarTilik = $this->daftar_tilik($jadwal->id, $unitId, $type);
        $daftarTilikFile = $this->zip($daftarTilik['templateProcessor'], $daftarTilik['unitData'], $daftarTilik['date'], $daftarTilik['title']);

        // Jawaban Auditan
        $jawabanAuditan = $this->jawaban_auditan($jadwal->id, $unitId, $type);
        $jawabanAuditanFile = $this->zip($jawabanAuditan['templateProcessor'], $jawabanAuditan['unitData'], $jawabanAuditan['date'], $jawabanAuditan['title']);

        // Temuan Negatif
        $tn = DB::table('ptk')->where('jadwal_audit_id', $jadwal->id)->where(get_type($type), $unitId)->select('id')->first();
        $temuanNegatif = $tn ? $this->temuan_negatif($tn->id) : null;
        $temuanNegatifFile = $temuanNegatif ? $this->zip($temuanNegatif['templateProcessor'], $temuanNegatif['unitData'], $temuanNegatif['date'], $temuanNegatif['title']) : null;

        // Temuan Positif
        $tp = DB::table('laporan')->where('jadwal_audit_id', $jadwal->id)->where(get_type($type), $unitId)->select('id')->first();
        $temuanPositif = $tp ? $this->temuan_positif($tp->id) : null;
        $temuanPositifFile = $tp ? $this->zip($temuanPositif['templateProcessor'], $temuanPositif['unitData'], $temuanPositif['date'], $temuanPositif['title']) : null;

        // Berita Acara
        $ba = DB::table('berita_acara')->where('jadwal_audit_id', $jadwal->id)->where(get_type($type), $unitId)->select('id')->first();
        $beritaAcara = $ba ? $this->berita_acara($ba->id) : null;
        $beritaAcaraFile = $ba ? $this->zip($beritaAcara['templateProcessor'], $beritaAcara['unitData'], $beritaAcara['date'], $beritaAcara['title']) : null;

        return [
            'year' => \Carbon\Carbon::parse($jadwal->tgl_mulai)->isoFormat('YYYY'),
            'files' => [
                [
                    'title' => $daftarTilik['title'],
                    'file' => $daftarTilikFile,
                ],
                [
                    'title' => $jawabanAuditan['title'],
                    'file' => $jawabanAuditanFile,
                ],
                $temuanNegatifFile ? [
                    'title' => $temuanNegatif['title'],
                    'file' => $temuanNegatifFile,
                ] : null,
                $temuanPositifFile ? [
                    'title' => $temuanPositif['title'],
                    'file' => $temuanPositifFile,
                ] : null,
                $beritaAcaraFile ? [
                    'title' => $beritaAcara['title'],
                    'file' => $beritaAcaraFile,
                ] : null,
            ],
        ];
    }
    // Laporan Hasil Zip Per Unit
    public function zip_per_unit(string $jadwalAudit, string $unitId, string $type)
    {
        try {
            // Generate Zip
            $files = $this->generate_zip($jadwalAudit, $unitId, $type);

            // Get Unit
            $unit = DB::table($type === 'universitas' ? 'unit' : $type)
                ->where('id', $unitId)
                ->select("id as " . ($type === 'universitas' ? 'unit_id' : "{$type}_id"))
                ->first();

            $unitData = $this->get_unit($unit);

            // Buat ZIP 
            $zipFileName = 'Hasil Audit ' . ($unitData ? $unitData['unitName'] : '') . ' ' . $files['year'] . '.zip';
            $zipFilePath = storage_path('app/public/' . $zipFileName);

            $zip = new \ZipArchive();
            if ($zip->open($zipFilePath, \ZipArchive::CREATE) === TRUE) {
                foreach ($files['files'] as $file) {
                    if ($file && isset($file['file']['filePath'], $file['file']['fileName'])) {
                        $zip->addFile($file['file']['filePath'], basename($file['file']['fileName']));
                    }
                }
                $zip->close();
            } else {
                return back()->with('error', 'Terjadi kesalahan saat mengunduh file!');
            }

            // Hapus file Word setelah dimasukkan ke dalam ZIP
            foreach ($files['files'] as $file) {
                if ($file && isset($file['file']['filePath'])) {
                    unlink($file['file']['filePath']);
                }
            }

            return response()->download($zipFilePath, $zipFileName)->deleteFileAfterSend(true);
        } catch (\Exception $e) {
            // return back()->with('error', $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan saat mengunduh file!');
        }
    }

    // Laporan Hasil Zip per fakultas
    public function zip_per_fakultas(Request $request, string $jadwalAudit)
    {
        try {
            $request->validate([
                'fakultas' => 'required|uuid|exists:fakultas,id'
            ]);

            $fakultas = $request->fakultas;

            // Ambil semua prodi berdasarkan fakultas
            $prodi = DB::table('fakultas')
                ->join('prodi', 'fakultas.id', '=', 'prodi.fakultas_id')
                ->join('jenjang', 'prodi.jenjang_id', '=', 'jenjang.id')
                ->where('fakultas.id', $fakultas)
                ->select('prodi.id as id',  DB::raw("CONCAT(prodi.nama, ' ', jenjang.nama) as nama"))
                ->get();

            $allFiles = [];
            $year = null;

            // Looping prodi generate zip 
            foreach ($prodi as $item) {
                $unitId = $item->id;

                $files = $this->generate_zip($jadwalAudit, $unitId, 'prodi');

                if (!$year && isset($files['year'])) {
                    $year = $files['year'];
                }

                // Merge
                if (isset($files['files'])) {
                    $allFiles[] = [
                        'prodi_name' => $item->nama,
                        'files' => $files['files'],
                    ];
                }
            }

            // Get Fakultas
            $unit = DB::table('fakultas')
                ->where('id', $fakultas)
                ->select('id as fakultas_id')
                ->first();

            $unitData = $this->get_unit($unit);

            // Buat ZIP
            $zipFileName = 'Hasil Audit ' . ($unitData ? $unitData['unitName'] : 'Fakultas') . ' ' . ($year ?? '') . '.zip';
            $zipFilePath = storage_path('app/public/' . $zipFileName);

            $zip = new \ZipArchive();
            if ($zip->open($zipFilePath, \ZipArchive::CREATE) === TRUE) {
                foreach ($allFiles as $prodiData) {
                    $prodiName = $prodiData['prodi_name'];
                    foreach ($prodiData['files'] as $file) {
                        if ($file && isset($file['file']['filePath'], $file['file']['fileName'])) {
                            $zip->addFile(
                                $file['file']['filePath'],
                                "Program Studi {$prodiName}/" . basename($file['file']['fileName'])
                            );
                        }
                    }
                }
                $zip->close();
            } else {
                return back()->with('error', 'Terjadi kesalahan saat mengunduh file!');
            }

            // Hapus file Word setelah dimasukkan ke dalam ZIP
            foreach ($allFiles as $prodiData) {
                foreach ($prodiData['files'] as $file) {
                    if ($file && isset($file['file']['filePath'])) {
                        unlink($file['file']['filePath']);
                    }
                }
            }

            return response()->download($zipFilePath, $zipFileName)->deleteFileAfterSend(true);
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan saat mengunduh file!');
        }
    }

    // Zip PS
    public function zip_ps(string $jadwalAudit)
    {
        try {
            // Ambil semua fakultas
            $fakultasList = DB::table('fakultas')->select('id', 'nama')->get();

            $allFiles = [];
            $year = null;

            // Looping setiap fakultas untuk generate zip
            foreach ($fakultasList as $fakultas) {
                $fakultasId = $fakultas->id;

                // Ambil semua prodi berdasarkan fakultas
                $prodi = DB::table('fakultas')
                    ->join('prodi', 'fakultas.id', '=', 'prodi.fakultas_id')
                    ->join('jenjang', 'prodi.jenjang_id', '=', 'jenjang.id')
                    ->where('fakultas.id', $fakultasId)
                    ->select('prodi.id as id', DB::raw("CONCAT(prodi.nama, ' ', jenjang.nama) as nama"))
                    ->get();

                // Looping prodi untuk generate zip
                foreach ($prodi as $item) {
                    $unitId = $item->id;

                    // Generate zip untuk tiap prodi
                    $files = $this->generate_zip($jadwalAudit, $unitId, 'prodi');

                    if (!$year && isset($files['year'])) {
                        $year = $files['year'];
                    }

                    // Merge files ke dalam allFiles
                    if (isset($files['files'])) {
                        $allFiles[] = [
                            'fakultas_name' => $fakultas->nama,
                            'prodi_name' => $item->nama,
                            'files' => $files['files'],
                        ];
                    }
                }
            }

            // Nama file ZIP yang akan dihasilkan
            $zipFileName = 'Hasil Audit PS ' . ($year ?? '') . '.zip';
            $zipFilePath = storage_path('app/public/' . $zipFileName);

            $zip = new \ZipArchive();
            if ($zip->open($zipFilePath, \ZipArchive::CREATE) === TRUE) {
                // Menambahkan file ke dalam ZIP berdasarkan fakultas dan prodi
                foreach ($allFiles as $fakultasData) {
                    $fakultasName = $fakultasData['fakultas_name'];
                    $prodiName = $fakultasData['prodi_name'];
                    foreach ($fakultasData['files'] as $file) {
                        if ($file && isset($file['file']['filePath'], $file['file']['fileName'])) {
                            $zip->addFile(
                                $file['file']['filePath'],
                                "Fakultas {$fakultasName}/Program Studi {$prodiName}/" . basename($file['file']['fileName'])
                            );
                        }
                    }
                }
                $zip->close();
            } else {
                return back()->with('error', 'Terjadi kesalahan saat mengunduh file!');
            }

            // Hapus file Word setelah dimasukkan ke dalam ZIP
            foreach ($allFiles as $fakultasData) {
                foreach ($fakultasData['files'] as $file) {
                    if ($file && isset($file['file']['filePath'])) {
                        unlink($file['file']['filePath']);
                    }
                }
            }

            // Kembalikan file ZIP untuk diunduh
            return response()->download($zipFilePath, $zipFileName)->deleteFileAfterSend(true);
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan saat mengunduh file!');
        }
    }

    // Zip UPPS
    public function zip_upps(string $jadwalAudit)
    {
        try {
            // Ambil semua fakultas
            $fakultas = DB::table('fakultas')
                ->select('id', 'nama')
                ->get()
                ->map(function ($item) {
                    $item->type = 'fakultas';
                    return $item;
                });

            $unit = DB::table('unit')
                ->select('id', 'nama')
                ->get()
                ->map(function ($item) {
                    $item->type = 'universitas';
                    return $item;
                });

            $merge = $fakultas->merge($unit);

            $allFiles = [];
            $year = null;

            // Looping setiap fakultas untuk generate zip
            foreach ($merge as $item) {
                // Generate zip
                $files = $this->generate_zip($jadwalAudit, $item->id, $item->type);

                if (!$year && isset($files['year'])) {
                    $year = $files['year'];
                }

                // Merge files ke dalam allFiles
                if (isset($files['files'])) {
                    $allFiles[] = [
                        'unit_name' => ($item->type == 'fakultas' ? "Fakultas {$item->nama}" : $item->nama),
                        'files' => $files['files'],
                    ];
                }
            }

            // Nama file ZIP yang akan dihasilkan
            $zipFileName = 'Hasil Audit UPPS ' . ($year ?? '') . '.zip';
            $zipFilePath = storage_path('app/public/' . $zipFileName);

            $zip = new \ZipArchive();
            if ($zip->open($zipFilePath, \ZipArchive::CREATE) === TRUE) {
                foreach ($allFiles as $data) {
                    $unitName = $data['unit_name'];
                    foreach ($data['files'] as $file) {
                        if ($file && isset($file['file']['filePath'], $file['file']['fileName'])) {
                            $zip->addFile(
                                $file['file']['filePath'],
                                "{$unitName}/" . basename($file['file']['fileName'])
                            );
                        }
                    }
                }
                $zip->close();
            } else {
                return back()->with('error', 'Terjadi kesalahan saat mengunduh file!');
            }

            // Hapus file Word setelah dimasukkan ke dalam ZIP
            foreach ($allFiles as $data) {
                foreach ($data['files'] as $file) {
                    if ($file && isset($file['file']['filePath'])) {
                        unlink($file['file']['filePath']);
                    }
                }
            }

            // Kembalikan file ZIP untuk diunduh
            return response()->download($zipFilePath, $zipFileName)->deleteFileAfterSend(true);
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan saat mengunduh file!');
        }
    }

    //Laporan RTM
    public function download_rtm_fakultas(RtmJadwal $rtmJadwal)
    {
        // Data Cover
        $dataCover = [
            'tahun' => \Carbon\Carbon::parse($rtmJadwal->tanggal)->translatedFormat('Y'),
            'fakultas' => $rtmJadwal->fakultas->nama ?? null,
            'unit' => $rtmJadwal->unit->nama ?? null, 
        ];

        // Ambil data RtmJadwal dengan relasi yang diperlukan
        $rtmJadwal = RtmJadwal::with(['rtm_rtl.rtm_tindak_lanjut', 'fakultas', 'unit', 'rtm_lampiran'])
            ->findOrFail($rtmJadwal->id);

        // Ambil lampiran jika ada
        $lampiran = RtmLampiran::where('rtm_jadwal_id', $rtmJadwal->id)->first();

        $temuanFakultas = JawabanAuditor::where('jadwal_audit_id', $rtmJadwal->jadwal_audit_id)
            ->whereHas('form', function ($query) {
                $query->whereHas('instrumen.jabatan'); 
            })
            ->when($rtmJadwal->fakultas_id, function ($query) use ($rtmJadwal) {
                return $query->whereHas('form.instrumen.jabatan', function ($q) use ($rtmJadwal) {
                    $q->where('fakultas_id', $rtmJadwal->fakultas_id);
                });
            })
            ->when($rtmJadwal->unit_id, function ($query) use ($rtmJadwal) {
                return $query->whereHas('form.instrumen.jabatan', function ($q) use ($rtmJadwal) {
                    $q->where('unit_id', $rtmJadwal->unit_id);
                });
            })
            ->with([
                'form.instrumen.jabatan',
                'form.jawaban_auditee',
                'kriteria',
                'form.ptk_form_deskripsi',
                'form.laporan_form',
            ])
            ->get();

        $prodiList = $rtmJadwal->fakultas ? Prodi::where('fakultas_id', $rtmJadwal->fakultas_id)->pluck('id') : collect([]);

        $temuanProdi = $rtmJadwal->fakultas
            ? JawabanAuditor::select('jawaban_auditor.*')
                ->join('form', 'jawaban_auditor.form_id', '=', 'form.id')
                ->join('instrumen', 'form.instrumen_id', '=', 'instrumen.id')
                ->where('jawaban_auditor.jadwal_audit_id', $rtmJadwal->jadwal_audit_id)
                ->whereIn('jawaban_auditor.prodi_id', $prodiList)
                ->with(['prodi', 'form.instrumen', 'kriteria', 'form.ptk_form_deskripsi', 'form.laporan_form'])
                ->orderBy('instrumen.kode', 'asc')
                ->orderBy('jawaban_auditor.kriteria_id', 'asc')
                ->get()
            : collect([]);

        $jawabanTindakLanjut = RtmTindakLanjut::whereIn('rtm_rtl_id', $rtmJadwal->rtm_rtl->pluck('id'))->get();

        $dataJadwal = [
            'rtmJadwal' => $rtmJadwal,
            'fakultas' => $rtmJadwal->fakultas->nama ?? null,
            'unit' => $rtmJadwal->unit->nama ?? null, 

        ];

        $dataIsi = [
            'rtmJadwal' => $rtmJadwal,
            'lampiran' => $lampiran,
            'prodiList' => $prodiList,
            'temuanFakultas' => $temuanFakultas,
            'temuanProdi' => $temuanProdi,
            'jawabanTindakLanjut' => $jawabanTindakLanjut,
            'fakultas' => $rtmJadwal->fakultas->nama ?? null,
            'unit' => $rtmJadwal->unit->nama ?? null, 
        ];

        // MPDF
        $mpdf = new \Mpdf\Mpdf([
            'tempDir' => storage_path('app/tmp'),
        ]);

        // Halaman Cover (Portrait)
        $cover = view('pdf.rtm_fakultas.cover', $dataCover)->render();
        $mpdf->AddPage('P');
        $mpdf->WriteHTML($cover);

        // Hilangkan footer pada cover
        $mpdf->SetFooter('');

        // Tambahkan halaman baru untuk isi laporan
        $mpdf->AddPage('P'); // Portrait untuk isi
        $mpdf->SetFooter('Halaman {PAGENO} dari {nbpg}');

        // Halaman Isi 
        $jadwal = view('pdf.rtm_fakultas.jadwal', $dataJadwal)->render();
        $mpdf->WriteHTML($jadwal);

        // Tambahkan halaman baru untuk isi laporan
        $mpdf->AddPage('L'); // Landscape untuk isi
        $mpdf->SetFooter('Halaman {PAGENO} dari {nbpg}');

        // Halaman Isi 
        $isi = view('pdf.rtm_fakultas.isi', $dataIsi)->render();
        $mpdf->WriteHTML($isi);

        // Tambahkan Lampiran Jika Ada
        if ($lampiran) {
            $lampiranFiles = [
                'undangan' => $lampiran->undangan,
                'presensi' => $lampiran->presensi,
                'dokumentasi' => $lampiran->dokumentasi,
            ];

            foreach ($lampiranFiles as $fileKey => $filePath) {
                if ($filePath) {
                    $pdfPath = storage_path("app/$filePath");

                    // Pastikan file ada sebelum diproses
                    if (file_exists($pdfPath) && is_readable($pdfPath)) {
                        try {
                            $pageCount = $mpdf->SetSourceFile($pdfPath);

                            for ($i = 1; $i <= $pageCount; $i++) {
                                $tplId = $mpdf->ImportPage($i);
                                $mpdf->AddPage();
                                $mpdf->UseTemplate($tplId);
                            }
                        } catch (\Exception $e) {
                            Log::error("Gagal menambahkan lampiran $fileKey: " . $e->getMessage());
                        }
                    } else {
                        Log::warning("Lampiran $fileKey tidak ditemukan atau tidak dapat dibaca di path: $pdfPath");
                    }
                }
            }
        }

        // Penamaan file berdasarkan fakultas atau unit
        if ($rtmJadwal->fakultas) {
            $namaFile = "Laporan RTM Fakultas " . $rtmJadwal->fakultas->nama . " Tahun " . $dataCover['tahun'] . ".pdf";
        } else {
            $namaFile = "Laporan RTM Unit " . $rtmJadwal->unit->nama . " Tahun " . $dataCover['tahun'] . ".pdf";
        }

        // Download
        return response($mpdf->Output($namaFile, \Mpdf\Output\Destination::STRING_RETURN), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => "attachment; filename=\"$namaFile\"",
            'X-Filename' => $namaFile,
        ]);
    }
    
    //RTM FAKULTAS 
    private function rtmRtl($id)
    {
        $title = "Laporan RTM Fakultas";
            // 1. Ambil data utama
            $rtmRtl = RtmRtl::with(['rtm_jadwal', 'rtm_tindak_lanjut'])->find($id);
            if (!$rtmRtl) {
                throw new \Exception('Data RTM tidak ditemukan');
            }

            $rtmJadwal = $rtmRtl->rtm_jadwal;
            if (!$rtmJadwal) {
                throw new \Exception('Data jadwal RTM tidak ditemukan');
            }

            // 2. Ambil data unit/fakultas
            $unitData = $this->get_unit($rtmJadwal);
            if (!$unitData) {
                throw new \Exception('Data unit tidak ditemukan');
            }

            // 3. Query data temuan dan tindakan
            $tindakLanjutItems = DB::table('rtm_tindak_lanjut')
                ->where('rtm_rtl_id', $id)
                ->join('kriteria', 'rtm_tindak_lanjut.kriteria_id', '=', 'kriteria.id')
                ->join('form', 'rtm_tindak_lanjut.form_id', '=', 'form.id')
                ->join('instrumen', 'form.instrumen_id', '=', 'instrumen.id')
                ->join('standar', 'instrumen.standar_id', '=', 'standar.id')
                ->leftJoin('kategori', 'instrumen.kategori_id', '=', 'kategori.id')
                ->leftJoin('jabatan', 'rtm_tindak_lanjut.jabatan_id', '=', 'jabatan.id')
                ->leftJoin('jabatan_user', function($join) {
                    $join->on('rtm_tindak_lanjut.user_id', '=', 'jabatan_user.user_id')
                        ->on('rtm_tindak_lanjut.jabatan_id', '=', 'jabatan_user.jabatan_id');
                })
                ->leftJoin('fakultas', 'jabatan_user.fakultas_id', '=', 'fakultas.id')
                ->leftJoin('unit', 'jabatan_user.unit_id', '=', 'unit.id')
                ->leftJoin('prodi', 'jabatan_user.prodi_id', '=', 'prodi.id')
                ->select([
                    'standar.kode as kode_standar',
                    'standar.nama as nama_standar',
                    'kategori.nama as nama_kategori',
                    'kriteria.id as kriteria_id',
                    'instrumen.kode as kode_instrumen',
                    'instrumen.pernyataan as pernyataan',
                    'rtm_tindak_lanjut.tindakan',
                    'rtm_tindak_lanjut.waktu',
                    'rtm_tindak_lanjut.form_id',
                    'jabatan.nama as jabatan_nama',
                    'fakultas.nama as fakultas_nama',
                    'unit.nama as unit_nama',
                    'prodi.nama as prodi_nama'
                ])
                ->orderBy('standar.nama')
                ->orderBy('kategori.nama')
                ->orderBy('instrumen.kode')
                ->orderBy('kriteria.id')
                ->get();

            // 4. Group data by standar dan pernyataan
            $groupedData = [];
            
            foreach ($tindakLanjutItems as $item) {
                $standarKategoriKey = $item->nama_standar . '|' . $item->nama_kategori;
                $formKey = $item->kode_instrumen . '|' . $item->pernyataan;

                if (!isset($groupedData[$standarKategoriKey])) {
                    $groupedData[$standarKategoriKey] = [
                        'nama_standar' => $item->nama_standar,
                        'nama_kategori' => $item->nama_kategori,
                        'items' => []
                    ];
                }
                
                if (!isset($groupedData[$standarKategoriKey]['items'][$formKey])) {
                    $groupedData[$standarKategoriKey]['items'][$formKey] = [
                        'kode_instrumen' => $item->kode_instrumen,
                        'pernyataan' => $item->pernyataan,
                        'kriteria_items' => []
                    ];
                }

                $kriteriaFound = false;
                foreach ($groupedData[$standarKategoriKey]['items'][$formKey]['kriteria_items'] as &$kriteriaItem) {
                    if ($kriteriaItem['kriteria_id'] == $item->kriteria_id) {
                        $kriteriaItem['tindak_lanjut'][] = $this->formatRencana($item->tindakan, $item->waktu, $item->jabatan_nama, 
                        $item->fakultas_nama, $item->unit_nama, $item->prodi_nama);
                        $kriteriaFound = true;
                        break;
                    }
                }
                
                if (!$kriteriaFound) {
                    $groupedData[$standarKategoriKey]['items'][$formKey]['kriteria_items'][] = [
                        'kriteria_id' => $item->kriteria_id,
                        'kriteria_nama' => $this->getKriteriaName($item->kriteria_id),
                        'temuan' => $this->formatTemuan($item->form_id, $item->kriteria_id),
                        'tindak_lanjut' => [$this->formatRencana($item->tindakan, $item->waktu, $item->jabatan_nama, 
                        $item->fakultas_nama, $item->unit_nama, $item->prodi_nama)]
                    ];
                }
            }

            // Auditan
            $auditan = DB::table('rtm_rtl_approve as rtm_rtl_auditan')
                ->where('rtm_rtl_auditan.rtm_rtl_id', $id)
                ->leftJoin('auditee', 'rtm_rtl_auditan.auditee_id', '=', 'auditee.id')
                ->leftJoin('users', 'auditee.user_id', '=', 'users.id')
                ->select(
                    'auditee.id as id',
                    'users.name as nama',
                    'rtm_rtl_auditan.approve as approve',
                    'rtm_rtl_auditan.updated_at as updated_at'
                )
                ->first();

            // 5. Generate Word Document
            $templateProcessor = new \PhpOffice\PhpWord\TemplateProcessor(
                storage_path('app/public/template/template_rtm_fakultas.docx')
            );

            // Set header dokumen
            \Carbon\Carbon::setLocale('id');
            $date = \Carbon\Carbon::parse($rtmRtl->tgl);
            
            $templateProcessor->setValues([
                'unit' => $rtmJadwal->fakultas->nama,
                'tahun' => $date->isoFormat('YYYY'),
                'date' => $date->isoFormat('dddd, D MMMM YYYY'),
                'tempat' => $rtmJadwal->tempat,
                'waktu' => $rtmJadwal->jam_mulai . ' - ' . $rtmJadwal->jam_selesai,
                'jadwal' => $rtmJadwal->jadwal_audit->jadwal,
                'pimpinan' => $rtmJadwal->pimpinan,
                'peserta' => $rtmJadwal->peserta,
            ]);

            // 6. Isi data standar ke template
            $standarKategori = null;
            $no = 1;

            foreach ($groupedData as $group) {
                $templateProcessor->setValue('standar_kategori', 
                    "STANDAR: {$group['nama_standar']} | {$group['nama_kategori']}");
            
            $rows = [];
            foreach ($group['items'] as $item) {
                $firstKriteria = true;

                foreach ($item['kriteria_items'] as $kriteria) {
                    $mergedRencana = implode("\n\n", $kriteria['tindak_lanjut']);

                    $rows[] = [
                        'no' => $firstKriteria ? $no : '',
                        'kode_instrumen' => $firstKriteria ? $item['kode_instrumen'] : '',
                        'pernyataan' => $firstKriteria ? $item['pernyataan'] : '',
                        'kriteria' => $kriteria['kriteria_nama'],
                        'temuan' => $kriteria['temuan'],
                        'rencana' => $mergedRencana
                    ];
                    $firstKriteria = false;
                }
                $no++;
            }
            $templateProcessor->cloneRow('row_no', count($rows));
                
            foreach ($rows as $i => $row) {
                $j = $i + 1;
                    
                $templateProcessor->setValue("row_no#{$j}", $row['no']);
                $templateProcessor->setValue("row_kode_instrumen#{$j}", $row['kode_instrumen']);
                $templateProcessor->setValue("row_pernyataan#{$j}", $row['pernyataan']);
                $templateProcessor->setValue("row_kriteria#{$j}", $row['kriteria']);
                $templateProcessor->setValue("row_temuan#{$j}", $row['temuan']);
                $templateProcessor->setValue("row_rencana#{$j}", $row['rencana']);
            }
        }
                
        $this->auditan($templateProcessor, $auditan, $unitData, $title);
            
        return [
            'templateProcessor' => $templateProcessor,
            'unitData' => $unitData,
            'date' => $date,
            'title' => $title
        ];
    }

    private function formatTemuan($formId, $kriteriaId)
    { 
        // Format temuan fakultas
        $temuan = '';

        if ($kriteriaId == 1) {
            // Belum Memenuhi
                $ptkForm = PtkForm::with(['ptk.prodi', 'ptk.fakultas', 'ptk.unit'])
                    ->where('form_id', $formId)
                    ->first();

                if ($ptkForm) {
                    $ptk = $ptkForm->ptk;

                    $unitNama = $ptk->prodi?->nama
                                ? "Prodi {$ptk->prodi->nama}" 
                                :($ptk->fakultas?->nama
                                    ? "Fakultas {$ptk->fakultas->nama}" 
                                    :($ptk->unit?->nama
                                        ? "Unit {$ptk->unit->nama}" 
                                        : "Unit tidak diketahui"));
                    
                    return "{$unitNama } : {$ptkForm->analisis} (Kategori {$ptkForm->kategori_temuan})";
                } 
        }
                
        if ($kriteriaId == 3) {
            // Belum Memenuhi
                $laporanForm = LaporanForm::with(['laporan.prodi', 'laporan.fakultas', 'laporan.unit'])
                    ->where('form_id', $formId)
                    ->first();

                if ($laporanForm) {
                    $laporan = $laporanForm->laporan;

                    $unitNama = $laporan->prodi?->nama
                                ? "Prodi {$laporan->prodi->nama}" 
                                :($laporan->fakultas?->nama
                                    ? "Fakultas {$laporan->fakultas->nama}" 
                                    :($laporan->unit?->nama
                                        ? "Unit {$laporan->unit->nama}" 
                                        : "Unit tidak diketahui"));
                    
                    return "{$unitNama} : {$laporanForm->ruang_peningkatan}";
                } 
        }
        //Jawaban Auditor
        $jawabanAuditor = JawabanAuditor::with(['prodi', 'fakultas', 'unit'])
            ->where('form_id', $formId)
            ->where('kriteria_id', $kriteriaId)
            ->first();

            if ($jawabanAuditor) {
                $unitNama = $jawabanAuditor->prodi?->nama
                            ? "Prodi {$jawabanAuditor->prodi->nama}" 
                            :($jawabanAuditor->fakultas?->nama
                                ? "Fakultas {$jawabanAuditor->fakultas->nama}" 
                                :($jawabanAuditor->unit?->nama
                                    ? "Unit {$jawabanAuditor->unit->nama}" 
                                    : "Unit tidak diketahui"));
                    
                    return "{$unitNama} : " . ($jawabanAuditor->catatan ?? 'Tidak ada temuan auditor');
            }
        return $temuan;
    }

    private function formatRencana($tindakan, $waktu, $jabatan, $fakultas, $unit, $prodi)
    {
        $picInfo = $jabatan;
        if ($jabatan) {
            if ($fakultas) {
                $picInfo .= ' - ' . $fakultas;
            } elseif ($unit) {
                $picInfo .= ' - ' . $unit;
            } elseif ($prodi) {
                $picInfo .= ' - ' . $prodi;
            }
        }

        $tindakanArr = explode(';', $tindakan ?? '');
        $waktuArr = explode(';', $waktu ?? '');
        $result = [];
        
        foreach ($tindakanArr as $i => $t) {
            if (trim($t)) {
                $item = ($i + 1) . ". " . trim($t);
                if ($picInfo) $item .= " \n PIC: $picInfo";
                if (!empty($waktuArr[$i])) $item .= " \n Waktu: " . trim($waktuArr[$i]) . "\n";
                $result[] = $item;
            }
        }
        
        return !empty($result) ? implode("\n", $result) : 'Tidak ada rencana';
    }

    private function getKriteriaName($kriteriaId)
    {
        return match($kriteriaId) {
            1 => 'Belum Memenuhi',
            2 => 'Memenuhi',
            3 => 'Melampaui',
            default => 'Tidak Diketahui'
        };
    }

    public function rtm_rtl_word(string $rtmRtl)
    {
        try {
            $tp = $this->rtmRtl($rtmRtl);
            return $this->word($tp['templateProcessor'], $tp['unitData'], $tp['date'], $tp['title']);
        } catch (\Exception $e) {
            Log::error('Error generating RTM RTL Word: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan saat mengunduh RTL!');
        }
    }

    //Form 6 tindak lanjut koreksi (RTL)
    private function rtl($id)
    {
        // Title
        $title = "Formulir Isian Tindak Lanjut";

        // RTL
        $rtl = DB::table('rtl')->where('id', $id)->first();

        // RTL Form
        $forms = DB::table('rtl_form')
            ->where('rtl_form.rtl_id', $rtl->id)
            ->join('kriteria', 'rtl_form.kriteria_id', '=', 'kriteria.id')
            ->join('form', 'rtl_form.form_id', '=', 'form.id')
            ->join('instrumen', 'form.instrumen_id', '=', 'instrumen.id')
            ->leftjoin('rtm_tindak_lanjut', function($join) {
                $join->on('rtl_form.form_id', '=', 'rtm_tindak_lanjut.form_id')
                     ->on('rtl_form.kriteria_id', '=', 'rtm_tindak_lanjut.kriteria_id');     
            })
            ->leftJoin('ptk_form_deskripsi', 'rtl_form.form_id', '=', 'ptk_form_deskripsi.form_id')
            ->leftJoin('jawaban_auditor', 'rtl_form.form_id', '=', 'jawaban_auditor.form_id')
            ->leftJoin('laporan_form', 'rtl_form.form_id', '=', 'laporan_form.form_id')
            ->select([
                'kriteria.id as kriteria_id',
                'kriteria.nama as nama_kriteria',
                'instrumen.kode as kode_instrumen',
                'instrumen.pernyataan as pernyataan',
                'rtl_form.tindakan as tindakan_pelaksanaan',
                'rtl_form.bukti as bukti_pelaksanaan',
                'rtm_tindak_lanjut.tindakan as rencana_tindakan',
                'rtm_tindak_lanjut.pic',
                'rtm_tindak_lanjut.waktu',
                'ptk_form_deskripsi.deskripsi as deskripsi',
                'jawaban_auditor.catatan as catatan',
                'laporan_form.kelebihan as kelebihan'
            ])
            ->distinct()
            ->get();
            Log::info('Jumlah data form RTL: ' . $forms->count());


        if ($forms->isEmpty()) {
            throw new \Exception('Form RTL tidak memiliki data.');
        }

        $groupedForms = [];

        foreach ($forms as $form) {
            // kode
            $key = $form->kode_instrumen . '|' . $form->pernyataan;

            if (!isset($groupedForms[$key])) {
                $groupedForms[$key] = [
                    'kode_instrumen' => $form->kode_instrumen,
                    'pernyataan' => $form->pernyataan,
                    'kriteria_id' => $form->kriteria_id,
                    'deskripsi' => [],
                    'catatan' => [],
                    'kelebihan' => [],
                    'rencana_tindakan' => [],
                    'pic' => [],
                    'waktu' => [],
                    'tindakan_pelaksanaan' => [],
                    'bukti_pelaksanaan' => [],
                ];
            }

            // Gabungkan data temuan
            if ($form->deskripsi) $groupedForms[$key]['deskripsi'][] = $form->deskripsi;
            if ($form->catatan) $groupedForms[$key]['catatan'][] = $form->catatan;
            if ($form->kelebihan) $groupedForms[$key]['kelebihan'][] = $form->kelebihan;

            // Gabungkan rencana
            $groupedForms[$key]['rencana_tindakan'] = array_merge($groupedForms[$key]['rencana_tindakan'], explode(';', $form->rencana_tindakan ?? ''));
            $groupedForms[$key]['pic'] = array_merge($groupedForms[$key]['pic'], explode(';', $form->pic ?? ''));
            $groupedForms[$key]['waktu'] = array_merge($groupedForms[$key]['waktu'], explode(';', $form->waktu ?? ''));

            // Gabungkan tindakan pelaksanaan
            $groupedForms[$key]['tindakan_pelaksanaan'] = array_merge($groupedForms[$key]['tindakan_pelaksanaan'], explode(';', $form->tindakan_pelaksanaan ?? ''));
            $groupedForms[$key]['bukti_pelaksanaan'] = array_merge($groupedForms[$key]['bukti_pelaksanaan'], explode(';', $form->bukti_pelaksanaan ?? ''));
        }

        $kategoriData = [
            'bm' => [],
            'm' => [],
            'ml' => []
        ];

        $index = 0;
        foreach ($groupedForms as $data) {
            $index++;

            // Format rencana tindakan
            $rencanaList = [];
            foreach ($data['rencana_tindakan'] as $i => $item) {
                $parts = [];
                if ($item) $parts[] = trim($item);
                if (!empty($data['pic'][$i])) $parts[] = 'PIC: ' . trim($data['pic'][$i]);
                if (!empty($data['waktu'][$i])) $parts[] = 'Waktu: ' . trim($data['waktu'][$i]);
                if (!empty($parts)) {
                    $rencanaList[] = ($i + 1) . '. ' . implode(' | ', $parts);
                }
            }

            // Format tindakan & bukti pelaksanaan
            $tindakanList = array_filter($data['tindakan_pelaksanaan']);
            foreach ($tindakanList as $i => &$t) {
                $t = ($i + 1) . '. ' . trim($t);
            }

            $buktiList = array_filter($data['bukti_pelaksanaan']);
            foreach ($buktiList as $i => &$t) {
                $t = ($i + 1) . '. ' . trim($t);
            }

            // Format temuan
            $temuan = match($data['kriteria_id']) {
                1 => implode("\n- ", array_unique($data['deskripsi'])) ?: 'Tidak ada deskripsi',
                2 => implode("\n- ", array_unique($data['catatan'])) ?: 'Tidak ada catatan',
                3 => implode("\n- ", array_unique($data['kelebihan'])) ?: 'Tidak ada kelebihan',
                default => 'Tidak ada data temuan'
            };

            $item = [
                'no' => $index,
                'kode_pernyataan' => $data['kode_instrumen'] . ' - ' . $data['pernyataan'],
                'temuan' => $temuan ?: 'Tidak Ada Temuan',
                'rencana_tindakan' => !empty($rencanaList) ? implode("\n", $rencanaList) : 'Tidak ada rencana tindakan',
                'tindakan_pelaksanaan' => !empty($tindakanList) ? implode("\n", $tindakanList) : 'Tidak ada tindakan pelaksanaan',
                'bukti_pelaksanaan' => !empty($buktiList) ? implode("\n", $buktiList) : 'Tidak ada bukti pelaksanaan',
            ];

            // Masukkan ke kategori
            switch ($data['kriteria_id']) {
                case 1: $kategoriData['bm'][] = $item; break;
                case 2: $kategoriData['m'][] = $item; break;
                case 3: $kategoriData['ml'][] = $item; break;
                default: $kategoriData['bm'][] = $item;
            }
        }

        // Ambil data auditan
        $auditan = DB::table('rtl_auditee as rtl_auditan')
            ->where('rtl_auditan.rtl_id', $id)
            ->leftJoin('auditee', 'rtl_auditan.auditee_id', '=', 'auditee.id')
            ->leftJoin('users', 'auditee.user_id', '=', 'users.id')
            ->select(
                'auditee.id as id',
                'users.name as nama',
                'rtl_auditan.approve as approve',
                'rtl_auditan.updated_at as updated_at'
            )
            ->first();
        
            if (!$auditan) {
                $auditan = (object) [
                    'id' => null,
                    'nama' => 'Auditan Tidak Ditemukan',
                    'approve' => null,
                    'updated_at' => null,
                ];
            }

        // Dekan
        // $dekan = DB::table('rtl_dekan as rtl_dekan')
        //     ->where('rtl_dekan.rtl_id', $id)
        //     ->leftJoin('auditee', 'rtl_dekan.auditee_id', '=', 'auditee.id')
        //     ->leftJoin('users', 'auditee.user_id', '=', 'users.id')
        //     ->select(
        //         'auditee.id as id',
        //         'users.name as nama',
        //         'rtl_dekan.approve as approve',
        //         'rtl_dekan.created_at as created_at',
        //         'rtl_dekan.updated_at as updated_at'
        //     )
        //     ->orderBy('rtl_dekan.created_at', 'asc')
        //     ->get();

        // Unit Data
        $unitData = $this->get_unit($rtl);
        if (!$unitData) {
            throw new \Exception('Data Unit tidak ditemukan.');
        }


        // Template
        $templateProcessor = new \PhpOffice\PhpWord\TemplateProcessor(storage_path('app/public/template/template_form6.docx'));

        \Carbon\Carbon::setLocale('id');
        $date = \Carbon\Carbon::parse($rtl->tgl);

        $templateProcessor->setValues([
            'date' => $date->isoFormat('D MMMM YYYY'),
            'unit' => $unitData['unitName'] ?? 'Unit tidak ditemukan'
        ]);

        // if (in_array('bm_no', $templateProcessor->getVariables())) {
        //     $templateProcessor->cloneRow('bm_no', count($kategoriData['bm']));
        // } else {
        //     Log::warning('Tag bm_no tidak ditemukan di template Word!');
        // }
        
        // Prepare values for the template
        foreach ($kategoriData as $prefix => $data) {
            if (empty($data)) {
                $templateProcessor->setValue($prefix.'_no', 'Tidak ada data');
                $templateProcessor->setValue($prefix.'_kode_pernyataan', 'Tidak ada data');
                $templateProcessor->setValue($prefix.'_temuan', 'Tidak ada data');
                $templateProcessor->setValue($prefix.'_rencana_tindakan', 'Tidak ada data');
                $templateProcessor->setValue($prefix.'_tindakan_pelaksanaan', 'Tidak ada data');
                $templateProcessor->setValue($prefix.'_bukti_pelaksanaan', 'Tidak ada data');
                continue;
            }

            $templateProcessor->cloneRow($prefix.'_no', count($data));
            foreach ($data as $index => $item) {
                $rowNumber = $index + 1;
                $templateProcessor->setValue($prefix.'_no#'.$rowNumber, $item['no']);
                $templateProcessor->setValue($prefix.'_kode_pernyataan#'.$rowNumber, $item['kode_pernyataan']);
                $templateProcessor->setValue($prefix.'_temuan#'.$rowNumber, $item['temuan']);
                $templateProcessor->setValue($prefix.'_rencana_tindakan#'.$rowNumber, $item['rencana_tindakan']);
                $templateProcessor->setValue($prefix.'_tindakan_pelaksanaan#'.$rowNumber, $item['tindakan_pelaksanaan']);
                $templateProcessor->setValue($prefix.'_bukti_pelaksanaan#'.$rowNumber, $item['bukti_pelaksanaan']);
            }
        }
        
       
        $this->auditan($templateProcessor, $auditan, $unitData, $title);

        // Return template and other data
        return compact('templateProcessor', 'unitData', 'date', 'title');
    }

    //RTL word
    public function rtl_word(string $rtl)
    {
        try {
            $tp = $this->rtl($rtl);
            return $this->word($tp['templateProcessor'], $tp['unitData'], $tp['date'], $tp['title']);
        } catch (\Exception $e) {
            Log::error('Error generating RTL Word: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan saat mengunduh RTL!');
        }
    }
    

    //Form7 Monitoring RTL 
    private function monitoring($id)
    {
        // Title
        $title = "Formulir Monitoring Tindak Lanjut atas PTK";

        $monitoring = DB::table('monitoring')->where('id', $id)->first();

        // Monitoring Form
        $forms = DB::table('monitoring_form')
            ->where('monitoring_form.monitoring_id', $monitoring->id)
            ->join('kriteria', 'monitoring_form.kriteria_id', '=', 'kriteria.id')
            ->join('form', 'monitoring_form.form_id', '=', 'form.id')
            ->join('instrumen', 'form.instrumen_id', '=', 'instrumen.id')
            ->leftJoin('monitoring_form_status', 'monitoring_form.form_id', '=', 'monitoring_form_status.form_id')
            ->leftjoin('rtm_tindak_lanjut', function($join) {
                $join->on('monitoring_form.form_id', '=', 'rtm_tindak_lanjut.form_id')
                     ->on('monitoring_form.kriteria_id', '=', 'rtm_tindak_lanjut.kriteria_id');     
            })
            ->leftjoin('rtl_form', function($join) {
                $join->on('monitoring_form.form_id', '=', 'rtl_form.form_id')
                     ->on('monitoring_form.kriteria_id', '=', 'rtl_form.kriteria_id');     
            })
            ->select([
                'kriteria.id as kriteria_id',
                'kriteria.nama as nama_kriteria',
                'instrumen.kode as kode_instrumen',
                'instrumen.pernyataan as pernyataan',
                'rtl_form.tindakan as tindakan',
                'rtl_form.bukti as bukti',
                'rtm_tindak_lanjut.tindakan as rencana_tindakan',
                'rtm_tindak_lanjut.pic',
                'rtm_tindak_lanjut.waktu',
                'monitoring_form_status.status as status',
                'monitoring_form.catatan as catatan',
            ])
            ->distinct()
            ->get();
            Log::info('Jumlah data form: ' . $forms->count());

        if ($forms->isEmpty()) {
            throw new \Exception('Form RTL tidak memiliki data.');
        }

        $groupedForms = [];
        foreach ($forms as $form) {
            // kode
            $key = $form->kode_instrumen . '|' . $form->pernyataan;

            if (!isset($groupedForms[$key])) {
                $groupedForms[$key] = [
                    'kode_instrumen' => $form->kode_instrumen,
                    'pernyataan' => $form->pernyataan,
                    'kriteria_id' => $form->kriteria_id,
                    'rencana_tindakan' => [],
                    'pic' => [],
                    'waktu' => [],
                    'tindakan' => [],
                    'bukti' => [],
                    'status' => $form->status,
                    'catatan' => []
                ];
            }

            // Gabungkan rencana RTM
            $groupedForms[$key]['rencana_tindakan'] = array_merge($groupedForms[$key]['rencana_tindakan'], explode(';', $form->rencana_tindakan ?? ''));
            $groupedForms[$key]['pic'] = array_merge($groupedForms[$key]['pic'], explode(';', $form->pic ?? ''));
            $groupedForms[$key]['waktu'] = array_merge($groupedForms[$key]['waktu'], explode(';', $form->waktu ?? ''));

            // Gabungkan tindakan pelaksanaan
            $groupedForms[$key]['tindakan'] = array_merge($groupedForms[$key]['tindakan'], explode(';', $form->tindakan ?? ''));
            $groupedForms[$key]['bukti'] = array_merge($groupedForms[$key]['bukti'], explode(';', $form->bukti ?? ''));
           
            //Catatan Auditor
            $groupedForms[$key]['catatan'][] = $form->catatan;

            //status
            $groupedForms[$key]['statuses'][] = $form->status;

        }

        $kategoriData = [
            'bm' => [],
            'm' => [],
            'ml' => []
        ];

        $index = 0;
        foreach ($groupedForms as $data) {
            $index++;

            // Format rencana tindakan
            $rencanaList = [];
            foreach ($data['rencana_tindakan'] as $i => $item) {
                $parts = [];
                if ($item) $parts[] = trim($item);
                if (!empty($data['pic'][$i])) $parts[] = 'PIC: ' . trim($data['pic'][$i]);
                if (!empty($data['waktu'][$i])) $parts[] = 'Waktu: ' . trim($data['waktu'][$i]);
                if (!empty($parts)) {
                    $rencanaList[] = ($i + 1) . '. ' . implode(' | ', $parts);
                }
            }

            // Format tindakan pelaksanaan
            $tindakanList = [];
            foreach ($data['tindakan'] as $i => $item) {
                $parts = [];
                if ($item) $parts[] = trim($item);
                if (!empty($data['bukti'][$i])) $parts[] = 'bukti: ' . trim($data['bukti'][$i]);
                if (!empty($parts)) {
                    $tindakanList[] = ($i + 1) . '. ' . implode(' | ', $parts);
                }
            }

            //Status
            $selesai = '';
            $proses = '';
            $belum = '';

            switch ($data['status']) {
                case 'selesai':
                    $selesai = '✔';
                    break;
                case 'proses':
                    $proses = '✔';
                    break;
                case 'belum_dilaksanakan':
                    $belum = '✔';
                    break;
            }

            $item = [
                'no' => $index,
                'kode_pernyataan' => $data['kode_instrumen'] . ' - ' . $data['pernyataan'],
                'rencana_tindakan' => !empty($rencanaList) ? implode("\n", $rencanaList) : 'Tidak ada rencana tindakan',
                'tindakan' => !empty($tindakanList) ? implode("\n", $tindakanList) : 'Tidak ada tindakan pelaksanaan',
                'catatan' => !empty($data['catatan']) ? implode("\n-", $data['catatan']) : 'Tidak ada tindakan pelaksanaan',
                'status' => $data['status'] ?? '-',
                's' => $selesai,
                'p' => $proses,
                'b' => $belum,
            ];

            // Masukkan ke kategori
            switch ($data['kriteria_id']) {
                case 1: $kategoriData['bm'][] = $item; break;
                case 2: $kategoriData['m'][] = $item; break;
                case 3: $kategoriData['ml'][] = $item; break;
                default: $kategoriData['bm'][] = $item;
            }
        }
        
        // Ambil data auditan
        $auditan = DB::table('monitoring_auditee as monitoring_auditan')
            ->where('monitoring_auditan.monitoring_id', $id)
            ->leftJoin('auditee', 'monitoring_auditan.auditee_id', '=', 'auditee.id')
            ->leftJoin('users', 'auditee.user_id', '=', 'users.id')
            ->select(
                'auditee.id as id',
                'users.name as nama',
                'monitoring_auditan.approve as approve',
                'monitoring_auditan.updated_at as updated_at'
            )
            ->first();
            if (!$auditan) {
                $auditan = (object) [
                    'id' => null,
                    'nama' => 'Auditan',
                    'approve' => null,
                    'updated_at' => null,
                ];
            }

        // Auditor
        $auditors = DB::table('monitoring_auditor as monitoring_auditor')
            ->where('monitoring_auditor.monitoring_id', $id)
            ->leftJoin('auditor', 'monitoring_auditor.auditor_id', '=', 'auditor.id')
            ->leftJoin('users', 'auditor.user_id', '=', 'users.id')
            ->select(
                'auditor.id as id',
                'users.name as nama',
                'monitoring_auditor.approve as approve',
                'monitoring_auditor.created_at as created_at',
                'monitoring_auditor.updated_at as updated_at'
            )
            ->orderBy('monitoring_auditor.created_at', 'asc')
            ->get();

            if (!$auditors) {
                $auditors = (object) [
                    'id' => null,
                    'nama' => 'Auditors',
                    'approve' => null,
                    'updated_at' => null,
                ];
            }

        // Unit Data
        $unitData = $this->get_unit($monitoring);
        if (!$unitData) {
            throw new \Exception('Data Unit tidak ditemukan.');
        }

        // Template
        $templateProcessor = new \PhpOffice\PhpWord\TemplateProcessor(storage_path('app/public/template/template_form7.docx'));


        \Carbon\Carbon::setLocale('id');
        $date = \Carbon\Carbon::parse($monitoring->tgl);
        $templateProcessor->setValues([
            'date' => $date->isoFormat('D MMMM YYYY'),
            'unit' => $unitData ? $unitData['unit']->nama : ''
        ]);
        
        // Prepare values for the template
        foreach ($kategoriData as $prefix => $data) {
            if (empty($data)) {
                $templateProcessor->setValue($prefix.'_no', '');
                $templateProcessor->setValue($prefix.'_kode_pernyataan', 'Tidak ada data');
                $templateProcessor->setValue($prefix.'_rencana_tindakan', 'Tidak ada data');
                $templateProcessor->setValue($prefix.'_tindakan', 'Tidak ada data');
                $templateProcessor->setValue($prefix.'_catatan', 'Tidak ada data');
                $templateProcessor->setValue($prefix.'_s', '');
                $templateProcessor->setValue($prefix.'_p', '');
                $templateProcessor->setValue($prefix.'_b', '');

                continue;
            }

            $templateProcessor->cloneRow($prefix.'_no', count($data));
            foreach ($data as $index => $item) {
                $rowNumber = $index + 1;
                $templateProcessor->setValue($prefix.'_no#'.$rowNumber, $item['no']);
                $templateProcessor->setValue($prefix.'_kode_pernyataan#'.$rowNumber, $item['kode_pernyataan']);
                $templateProcessor->setValue($prefix.'_rencana_tindakan#'.$rowNumber, $item['rencana_tindakan']);
                $templateProcessor->setValue($prefix.'_tindakan#'.$rowNumber, $item['tindakan']);
                $templateProcessor->setValue($prefix.'_catatan#'.$rowNumber, $item['catatan']);
                $templateProcessor->setValue($prefix.'_s#'.$rowNumber, $item['s']);
                $templateProcessor->setValue($prefix.'_p#'.$rowNumber, $item['p']);
                $templateProcessor->setValue($prefix.'_b#'.$rowNumber, $item['b']);

            }
        }

        $this->auditan($templateProcessor, $auditan, $unitData, $title);
        $this->auditors($templateProcessor, $auditors, $unitData, $title);

        // Return template and other data
        return compact('templateProcessor', 'unitData', 'date', 'title');
    }

    public function monitoring_rtl_word(string $monitoring)
    {
        try {
            $tp = $this->monitoring($monitoring);
            return $this->word($tp['templateProcessor'], $tp['unitData'], $tp['date'], $tp['title']);
        } catch (\Exception $e) {
            // Log error for debugging
            Log::error('Error generating RTL Word: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan saat mengunduh Form Monitoring Tindak Lanjut!');
        }
    }

    //RTM Univ
    public function download_rtm_univ(RtmJadwal $rtmJadwal)
    {
        // Data Cover
        $dataCover = [
            'tahun' => \Carbon\Carbon::parse($rtmJadwal->tanggal)->translatedFormat('Y'),
        ];

        $rtmJadwal = RtmJadwal::with([
            'rtm_rtl.rtm_tindak_lanjut',
            'rtm_rtl.fakultas', 
            'rtm_rtl.unit',     
            'fakultas',
            'unit',
            'rtm_lampiran'
        ])->findOrFail($rtmJadwal->id);
        
        
        // Ambil lampiran jika ada
        $lampiran = RtmLampiran::where('rtm_jadwal_id', $rtmJadwal->id)->first();

        $rtmUniv = collect($rtmJadwal->rtm_rtl)
        ->groupBy(function ($rtl_rtm) {
            return $rtl_rtm->fakultas_id ? 'fakultas_' . $rtl_rtm->fakultas_id : 'unit_' . $rtl_rtm->unit_id;
        });

        $temuanAudit = [];

        foreach ($rtmUniv as $key => $rtmRtlGroup) {
            foreach ($rtmRtlGroup as $hasilRtmRtl) {
        
        
                $temuan = JawabanAuditor::where('jadwal_audit_id', $rtmJadwal->jadwal_audit_id)
                    ->whereHas('form.instrumen.jabatan', function ($q) use ($hasilRtmRtl) {
                        if ($hasilRtmRtl->fakultas_id) {
                            $q->where('fakultas_id', $hasilRtmRtl->fakultas_id);
                        } elseif ($hasilRtmRtl->unit_id) {
                            $q->where('unit_id', $hasilRtmRtl->unit_id);
                        }
                    })
                    ->with([
                        'form.instrumen.jabatan',
                        'form.jawaban_auditee',
                        'kriteria',
                        'form.ptk_form_deskripsi',
                        'form.laporan_form',
                    ])
                    ->get();
        
                $temuanAudit[] = [
                    'nama' => $hasilRtmRtl->fakultas->nama ?? $hasilRtmRtl->unit->nama ?? 'Tidak diketahui',
                    'temuan' => $temuan,
                    'rtm_rtl' => $hasilRtmRtl,
                ];
            }
        }
        
        
        $jawabanTindakLanjut = RtmTindakLanjut::whereIn('rtm_rtl_id', $rtmJadwal->rtm_rtl->pluck('id'))->get();

        $dataJadwal = [
            'rtmJadwal' => $rtmJadwal,
        ];

        $dataIsi = [
            'rtmJadwal' => $rtmJadwal,
            'lampiran' => $lampiran,
            'temuanAudit' => $temuanAudit,
            'jawabanTindakLanjut' => $jawabanTindakLanjut,
        ];

        // MPDF
        $mpdf = new \Mpdf\Mpdf([
            'tempDir' => storage_path('app/tmp'),
        ]);

        // Halaman Cover (Portrait)
        $cover = view('pdf.rtm_univ.cover', $dataCover)->render();
        $mpdf->AddPage('P');
        $mpdf->WriteHTML($cover);

        // Hilangkan footer pada cover
        $mpdf->SetFooter('');

        // Tambahkan halaman baru untuk isi laporan
        $mpdf->AddPage('P'); // Portrait untuk isi
        $mpdf->SetFooter('Halaman {PAGENO} dari {nbpg}');

        // Halaman Isi 
        $jadwal = view('pdf.rtm_univ.jadwal', $dataJadwal)->render();
        $mpdf->WriteHTML($jadwal);

        // Tambahkan halaman baru untuk isi laporan
        $mpdf->AddPage('L'); // Landscape untuk isi
        $mpdf->SetFooter('Halaman {PAGENO} dari {nbpg}');

        // Halaman Isi 
        $isi = view('pdf.rtm_univ.isi', $dataIsi)->render();
        $mpdf->WriteHTML($isi);

        // Tambahkan Lampiran Jika Ada
        if ($lampiran) {
            $lampiranFiles = [
                'undangan' => $lampiran->undangan,
                'presensi' => $lampiran->presensi,
                'dokumentasi' => $lampiran->dokumentasi,
            ];

            foreach ($lampiranFiles as $fileKey => $filePath) {
                if ($filePath) {
                    $pdfPath = storage_path("app/$filePath");

                    // Pastikan file ada sebelum diproses
                    if (file_exists($pdfPath) && is_readable($pdfPath)) {
                        try {
                            $pageCount = $mpdf->SetSourceFile($pdfPath);

                            for ($i = 1; $i <= $pageCount; $i++) {
                                $tplId = $mpdf->ImportPage($i);
                                $mpdf->AddPage();
                                $mpdf->UseTemplate($tplId);
                            }
                        } catch (\Exception $e) {
                            Log::error("Gagal menambahkan lampiran $fileKey: " . $e->getMessage());
                        }
                    } else {
                        Log::warning("Lampiran $fileKey tidak ditemukan atau tidak dapat dibaca di path: $pdfPath");
                    }
                }
            }
        }

        // Penamaan file berdasarkan fakultas atau unit
        $namaFile = "Laporan RTM Unsoed " . " Tahun " . $dataCover['tahun'] . ".pdf";
        
        // Download
        return response($mpdf->Output($namaFile, \Mpdf\Output\Destination::STRING_RETURN), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => "attachment; filename=\"$namaFile\"",
            'X-Filename' => $namaFile,
        ]);
    }
    
}
