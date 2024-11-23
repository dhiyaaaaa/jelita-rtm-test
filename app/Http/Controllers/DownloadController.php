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

class DownloadController extends Controller
{
    // Ambil Unit
    private function get_unit($query)
    {
        $unit = null;
        $unitName = null;
        $unitJenjang = null;
        $type = null;

        if ($query->prodi_id) {
            $unit = Prodi::where('id', $query->prodi_id)->first();
            $unitName = $unit ? 'Program Studi ' . $unit->nama  . ' ' . $unit->jenjang->nama : null;
            $unitJenjang = $unit ? $unit->nama  . ' ' . $unit->jenjang->nama : null;
            $type = "Program Studi";
        } elseif ($query->fakultas_id) {
            $unit = Fakultas::where('id', $query->fakultas_id)->first();
            $unitName = $unit ? 'Fakultas ' . $unit->nama : null;
            $type = "Fakultas";
        } elseif ($query->unit_id) {
            $unit = Unit::where('id', $query->unit_id)->first();
            $unitName = $unit ? 'Unit ' . $unit->nama : null;
            $type = "Unit";
        }

        return ['unit' => $unit, 'unitName' => $unitName, 'type' => $type, 'unitJenjang' => $unitJenjang];
    }

    // Auditee
    private function auditee($templateProcessor, $auditee, $unit, $titleDokumen)
    {
        if ($auditee && $auditee->approve == 1 || $auditee->approve == true) {
            $createdTime = \Carbon\Carbon::parse($auditee->updated_at);
            $auditeeData = $titleDokumen . " " . ($unit ? $unit['unitName'] : '') .
                " telah ditandatangani oleh " . $auditee->auditee->user->name .
                " | " . $createdTime->format('H:i:s') . " | " . $createdTime->isoFormat('D MMMM YYYY');

            $this->set_barcode($templateProcessor, 'image_auditee', $auditeeData, $auditee->auditee->id);
        } else {
            $templateProcessor->setValue('image_auditee', '');
        }
        $templateProcessor->setValue('auditee', $auditee ? $auditee->auditee->user->name : '');
    }

    // Auditor
    private function auditors($templateProcessor, $auditors, $unit, $titleDokumen)
    {
        foreach ($auditors as $no => $auditor) {
            if ($auditor->approve) {
                $createdTime = \Carbon\Carbon::parse($auditor->updated_at);
                $auditorData = $titleDokumen . " " . ($unit ? $unit['unitName'] : '') .
                    " telah ditandatangani oleh " . $auditor->auditor->user->name .
                    " | " . $createdTime->format('H:i:s') . " | " . $createdTime->isoFormat('D MMMM YYYY');

                $this->set_barcode($templateProcessor, 'image_auditor#' . ($no + 1), $auditorData, $auditor->auditor->id);
            } else {
                $templateProcessor->setValue('image_auditor#' . ($no + 1), '');
            }
            $templateProcessor->setValue('nomor#' . ($no + 1), ($no + 1) . '. ');
            $templateProcessor->setValue('no_auditor#' . ($no + 1), 'Auditor ' . ($no + 1) . ',');
            $templateProcessor->setValue('auditor#' . ($no + 1), $auditor->auditor->user->name);
        }

        if (count($auditors) <= 2) {
            $templateProcessor->setValues(['nomor#2' => '', 'no_auditor#2' => '', 'auditor#2' => '', 'image_auditor#2' => '']);
            $templateProcessor->setValues(['nomor#3' => '', 'no_auditor#3' => '', 'auditor#3' => '', 'image_auditor#3' => '']);
        } else if (count($auditors) <= 3) {
            $templateProcessor->setValues(['nomor#3' => '', 'no_auditor#3' => '', 'auditor#3' => '', 'image_auditor#3' => '']);
        }
    }

    // QrCode
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

    // File Name
    private function nama_file($templateProcessor, $unit, $dateTime, $title)
    {
        $fileName = $title . ' ' . ($unit ? $unit['unitName'] : '') . ' ' . $dateTime->isoFormat('D MMMM YYYY') . '.docx';
        $filePath = storage_path('app/public/' . $fileName);
        $templateProcessor->saveAs($filePath);

        return response()->download($filePath)->deleteFileAfterSend(true);
    }

    // Download Berita Acara
    public function download_berita_acara(BeritaAcara $beritaAcara)
    {
        $title = "Berita Acara";
        $auditee = BeritaAcaraAuditee::where('berita_acara_id', $beritaAcara->id)->with(['auditee.user'])->first();
        $auditors = BeritaAcaraAuditor::where('berita_acara_id', $beritaAcara->id)->with(['auditor.user'])->groupBy('auditor_id', 'created_at', 'id')->orderBy('created_at', 'ASC')->get();

        $unitData = $this->get_unit($beritaAcara);
        $date = \Carbon\Carbon::parse($beritaAcara->tgl);

        $templateProcessor = new \PhpOffice\PhpWord\TemplateProcessor(storage_path('app/public/template/template_berita_acara.docx'));

        \Carbon\Carbon::setLocale('id');
        $templateProcessor->setValues([
            'hari' => $date->isoFormat('dddd'),
            'tanggal' => $date->format('d'),
            'bulan' => $date->isoFormat('MMMM'),
            'tahun' => $date->format('Y'),
            'date' => $date->isoFormat('D MMMM YYYY'),
            'unit' => $unitData ? ($unitData['type'] == 'Program Studi' ? $unitData['unitJenjang'] : $unitData['unit']->nama) : '',
            'type' => $unitData ? $unitData['type'] : '',
        ]);

        $this->auditee($templateProcessor, $auditee, $unitData, $title);
        $this->auditors($templateProcessor, $auditors, $unitData, $title);

        return $this->nama_file($templateProcessor, $unitData, $date, $title);
    }

    // Download PTK
    public function download_ptk(Ptk $ptk)
    {
        $title = "Temuan Negatif";
        $ptkForm = PtkForm::where('ptk_id', $ptk->id)->with(['form.instrumen'])->get();
        $auditors = PtkAuditor::where('ptk_id', $ptk->id)->with(['auditor.user'])->groupBy('auditor_id', 'created_at', 'id')->orderBy('created_at', 'ASC')->get();
        $auditee = PtkAuditee::where('ptk_id', $ptk->id)->with(['auditee.user'])->first();

        $unitData = $this->get_unit($ptk);

        $templateProcessor = new \PhpOffice\PhpWord\TemplateProcessor(storage_path('app/public/template/template_ptk.docx'));

        \Carbon\Carbon::setLocale('id');
        $date = \Carbon\Carbon::parse($ptk->tgl);
        $templateProcessor->setValues([
            'date' => $date->isoFormat('D MMMM YYYY'),
            'unit' => $unitData ? $unitData['unit']->nama : ''
        ]);

        // Referensi Butir Mutu
        $referensi = [];
        foreach ($ptkForm as $form) {
            $referensi[] = $form->form->instrumen->kode;
        }
        $referensiString = implode(", ", $referensi);
        $templateProcessor->setValue('referensi', $referensiString);


        // Form - Deskripsi
        $groupedDescriptions = [];
        $no = 1;

        foreach ($ptkForm as $index => $form) {
            $instrumenKode = $form->form->instrumen->kode;
            $deskripsis = PtkFormDeskripsi::where('ptk_id', $ptk->id)
                ->where('form_id', $form->form_id)
                ->get();

            foreach ($deskripsis as $deskripsi) {
                if (!isset($groupedDescriptions[$instrumenKode])) {
                    $groupedDescriptions[$instrumenKode] = [
                        'noDeskripsi' => $no++ . '. ',
                        'kodeDeskripsi' => 'Instrumen ' . $instrumenKode,
                        'deskripsi' => []
                    ];
                }
                $groupedDescriptions[$instrumenKode]['deskripsi'][] = '- ' . htmlspecialchars($deskripsi->deskripsi, ENT_QUOTES, 'UTF-8');
            }
        }

        $valuesFormDeskripsi = [];
        foreach ($groupedDescriptions as $group) {
            $valuesFormDeskripsi[] = [
                'noDeskripsi' => $group['noDeskripsi'] . '. ',
                'kodeDeskripsi' => 'Instrumen ' . $group['kodeDeskripsi'],
                'deskripsi' => implode("<w:br/>", $group['deskripsi'])
            ];
        }

        if (!empty($valuesFormDeskripsi)) {
            $templateProcessor->cloneRowAndSetValues('noDeskripsi', $valuesFormDeskripsi);
        } else {
            $templateProcessor->cloneRowAndSetValues('noDeskripsi', [['noDeskripsi' => '', 'kodeDeskripsi' => '', 'deskripsi' => '']]);
        }


        // Form - Analisis
        $valuesFormAnalisis = [];
        $noAnalisis = 1;
        foreach ($ptkForm as $index => $form) {
            $valuesFormAnalisis[] = [
                'noAnalisis' => $noAnalisis++ . '. ',
                'kodeAnalisis' => 'Instrumen ' . $form->form->instrumen->kode,
                'analisis' => htmlspecialchars($form->analisis, ENT_QUOTES, 'UTF-8'),
            ];
        }
        $templateProcessor->cloneRowAndSetValues('noAnalisis', $valuesFormAnalisis);

        // Form - Akibat
        $valuesFormAkibat = [];
        $noAkibat = 1;
        foreach ($ptkForm as $index => $form) {
            $valuesFormAkibat[] = [
                'noAkibat' => $noAkibat++ . '. ',
                'kodeAkibat' => 'Instrumen ' . $form->form->instrumen->kode,
                'akibat' => htmlspecialchars($form->akibat, ENT_QUOTES, 'UTF-8'),
            ];
        }
        $templateProcessor->cloneRowAndSetValues('noAkibat', $valuesFormAkibat);

        // Temuan
        if ($ptk) {
            $valuesTemuan = [];
            $noTemuan = 1;

            foreach ($ptkForm as $jawabans) {
                $jawabanTemuanDeskripsi = PtkFormDeskripsi::where('ptk_id', $ptk->id)->where('form_id', $jawabans->form_id)->get();

                foreach ($jawabanTemuanDeskripsi as $jawaban) {
                    if ($jawaban && !empty($jawaban->deskripsi)) {
                        $observasi = $jawabans->kategori_temuan == 'observasi' ? '✔' : '';
                        $minor = $jawabans->kategori_temuan == 'minor' ? '✔' : '';
                        $mayor = $jawabans->kategori_temuan == 'mayor' ? '✔' : '';

                        $valuesTemuan[] = [
                            'noTemuan' => $noTemuan++,
                            'temuan' => $jawaban->deskripsi,
                            'observasi' => $observasi,
                            'minor' => $minor,
                            'mayor' => $mayor,
                        ];
                    }
                }
            }

            if (!empty($valuesTemuan)) {
                $templateProcessor->cloneRowAndSetValues('noTemuan', $valuesTemuan);
            } else {
                $valuesTemuan[] = [
                    'noTemuan' => 1,
                    'temuan' => '',
                    'observasi' => '',
                    'minor' => '',
                    'mayor' => '',
                ];
                $templateProcessor->cloneRowAndSetValues('noTemuan', $valuesTemuan);
            }
        } else {
            $valuesTemuan[] = [
                'noTemuan' => 1,
                'temuan' => '',
                'observasi' => '',
                'minor' => '',
                'mayor' => '',
            ];
            $templateProcessor->cloneRowAndSetValues('noTemuan', $valuesTemuan);
        }

        // Form - Rencana
        $groupedRencanas = [];
        $noRencana = 1;

        foreach ($ptkForm as $index => $form) {
            $instrumenKode = $form->form->instrumen->kode;
            $rencanas = PtkFormRencana::where('ptk_id', $ptk->id)
                ->where('form_id', $form->form_id)
                ->get();

            foreach ($rencanas as $rencana) {
                if (!isset($groupedRencanas[$instrumenKode])) {
                    $groupedRencanas[$instrumenKode] = [
                        'noRencana' => $noRencana++ . '. ',
                        'kodeRencana' => 'Instrumen ' . $instrumenKode,
                        'rencana' => []
                    ];
                }
                $groupedRencanas[$instrumenKode]['rencana'][] = '- ' . htmlspecialchars($rencana->rencana, ENT_QUOTES, 'UTF-8');
            }
        }

        $valuesFormRencana = [];
        foreach ($groupedRencanas as $group) {
            $valuesFormRencana[] = [
                'noRencana' => $group['noRencana'],
                'kodeRencana' => $group['kodeRencana'],
                'rencana' => implode("<w:br/>", $group['rencana'])
            ];
        }

        if (!empty($valuesFormRencana)) {
            $templateProcessor->cloneRowAndSetValues('noRencana', $valuesFormRencana);
        } else {
            $templateProcessor->cloneRowAndSetValues('noRencana', [['noRencana' => '', 'kodeRencana' => '', 'rencana' => '']]);
        }

        // Form - Target
        $valuesFormTarget = [];
        $noTarget = 1;
        foreach ($ptkForm as $index => $form) {
            $valuesFormTarget[] = [
                'noTarget' => $noTarget++ . '. ',
                'kodeTarget' => 'Instrumen ' . $form->form->instrumen->kode,
                'target' => htmlspecialchars($form->target, ENT_QUOTES, 'UTF-8'),
            ];
        }

        $templateProcessor->cloneBlock('block_name', 0, true, false, $valuesFormTarget);

        // Form - PIC
        $valuesFormPic = [];
        $noPic = 1;
        foreach ($ptkForm as $index => $form) {
            $valuesFormPic[] = [
                'noPic' => $noPic++ . '. ',
                'kodePic' => 'Instrumen ' . $form->form->instrumen->kode,
                'pic' => htmlspecialchars($form->pic, ENT_QUOTES, 'UTF-8'),
            ];
        }

        $templateProcessor->cloneBlock('block_pic', 0, true, false, $valuesFormPic);

        $this->auditee($templateProcessor, $auditee, $unitData, $title);
        $this->auditors($templateProcessor, $auditors, $unitData, $title);

        return $this->nama_file($templateProcessor, $unitData, $date, $title);
    }

    // Download Laporan
    public function download_laporan(Laporan $laporan)
    {
        $title = "Temuan Positif";
        $auditors = LaporanAuditor::where('laporan_id', $laporan->id)->with(['auditor.user'])->groupBy('auditor_id', 'created_at', 'id')->orderBy('created_at', 'ASC')->get();
        $auditee = LaporanAuditee::where('laporan_id', $laporan->id)->with(['auditee.user'])->first();
        $laporanForm = LaporanForm::where('laporan_id', $laporan->id)->get();

        $unitData = $this->get_unit($laporan);

        $templateProcessor = new \PhpOffice\PhpWord\TemplateProcessor(storage_path('app/public/template/template_laporan.docx'));

        \Carbon\Carbon::setLocale('id');
        $date = \Carbon\Carbon::parse($laporan->tgl);
        $templateProcessor->setValues([
            'date' => $date->isoFormat('D MMMM YYYY'),
            'unit' => $unitData ? $unitData['unit']->nama : ''
        ]);

        // Isi
        // Jawaban Positif
        $valuesPositif = [];
        $no = 1;
        foreach ($laporanForm as $jawaban) {
            if ($jawaban && !empty($jawaban->kelebihan) || $jawaban && !empty($jawaban->ruang_peningkatan)) {
                $valuesPositif[] = [
                    'no' => $no++,
                    'kelebihan' => htmlspecialchars($jawaban->kelebihan, ENT_QUOTES, 'UTF-8'),
                    'ruang' => htmlspecialchars($jawaban->ruang_peningkatan, ENT_QUOTES, 'UTF-8'),
                ];
            }
        }
        if (!empty($valuesPositif)) {
            $templateProcessor->cloneRowAndSetValues('no', $valuesPositif);
        } else {
            $valuesPositif[] = [
                'no' => 1,
                'kelebihan' => null,
                'ruang' => null,
            ];
            $templateProcessor->cloneRowAndSetValues('no', $valuesPositif);
        }


        $this->auditee($templateProcessor, $auditee, $unitData, $title);
        $this->auditors($templateProcessor, $auditors, $unitData, $title);

        return $this->nama_file($templateProcessor, $unitData, $date, $title);
    }

    // Download All Instrumen
    public function download_instrumen()
    {
        $instrumen = Instrumen::with(['kriteria', 'jenjang', 'prodi', 'unit', 'level', 'standar', 'kategori'])
            ->orderBy('level_id', 'asc')
            ->orderBy('standar_id', 'asc')
            ->orderBy('kategori_id', 'asc')
            ->orderByRaw("REGEXP_REPLACE(kode, '[^0-9]', '', 'g')::int NULLS FIRST, REGEXP_REPLACE(kode, '[0-9]', '', 'g') ASC")
            ->get();
        $kriterias = Kriteria::all();

        $templateProcessor = new \PhpOffice\PhpWord\TemplateProcessor(storage_path('app/public/template/template_instrumen.docx'));

        $kriteriaArray = [];
        foreach ($kriterias as $kriteria) {
            $kriteriaArray[] = $kriteria->nama;
        }

        for ($i = 0; $i < count($kriteriaArray); $i++) {
            $templateProcessor->setValue('kriteria#' . ($i + 1), $kriteriaArray[$i]);
        }

        // Instrumen
        $valuesInstrumen = [];
        $no = 1;
        foreach ($instrumen as $item) {
            // dd($item->standar->KategoriController);
            $instrumenData = [
                // 'no' => $no++,
                'no' => $item->standar->nama . ' ' . $item->kategori->nama,
                'kode' => $item->kode,
                'indikator' => $item->indikator,
                'level' => $item->level->nama,
            ];

            $jenjangNames = [];
            if ($item->jenjang->isNotEmpty()) {
                foreach ($item->jenjang as $jenjang) {
                    $jenjangNames[] = $jenjang->nama;
                }
            } elseif ($item->prodi->isNotEmpty()) {
                foreach ($item->prodi as $prodi) {
                    $jenjangNames[] = $prodi->nama;
                }
            } elseif ($item->jabatan->isNotEmpty()) {
                foreach ($item->jabatan as $jabatan) {
                    $jenjangNames[] = $jabatan->nama;
                }
            }

            foreach ($item->kriteria as $i => $kriteria) {
                if (isset($kriteria->pivot->isi)) {
                    $instrumenData['instrumenKriteria' . ($i + 1)] = htmlspecialchars($kriteria->pivot->isi, ENT_QUOTES, 'UTF-8');
                } else {
                    $instrumenData['instrumenKriteria' . ($i + 1)] = '-';
                }
            }

            $instrumenData['jenjang'] = implode(', ', $jenjangNames);

            $valuesInstrumen[] = $instrumenData;
        }

        // dd($valuesInstrumen);

        $templateProcessor->cloneRowAndSetValues('no', $valuesInstrumen);

        $fileName = 'Instrumen Pengukuran Standar DIKTI Universitas Jenderal Soedirman' . '.docx';
        $filePath = storage_path('app/public/' . $fileName);
        $templateProcessor->saveAs($filePath);

        return response()->download($filePath)->deleteFileAfterSend(true);
    }

    // Download Daftar Tilik
    public function download_daftar_tilik(string $jadwalId, string $unitId, string $type)
    {
        $title = "Daftar Tilik";
        $jawaban_auditor = JawabanAuditor::where('jadwal_audit_id', $jadwalId)
            ->where('daftar_tilik', 1)
            ->where(get_type($type), $unitId)
            ->with(['form.instrumen'])
            ->orderBy('created_at', 'ASC')->get();

        if ($jawaban_auditor->isEmpty()) {
            return back()->with('error', 'Daftar Tilik masih kosong.');
        }

        $unitData = $this->get_unit($jawaban_auditor->first());

        $auditors = AuditeeAuditor::where(['jadwal_audit_id' => $jadwalId, get_type($type) => $unitId])
            ->with('auditor.user')
            ->get()
            ->pluck('auditor.user.name')
            ->unique()->values();

        $templateProcessor = new \PhpOffice\PhpWord\TemplateProcessor(storage_path('app/public/template/template_daftar_tilik.docx'));

        \Carbon\Carbon::setLocale('id');
        $status = StatusAuditAuditor::where(['jadwal_audit_id' => $jadwalId, get_type($type) => $unitId])->pluck('updated_at')->first();
        $date = \Carbon\Carbon::parse($status);

        $templateProcessor->setValues([
            'date' => $date->isoFormat('D MMMM YYYY'),
            'unit' => $unitData ? $unitData['unitName'] : ''
        ]);

        // Auditor
        foreach ($auditors as $index => $auditor) {
            $templateProcessor->setValue('auditor#' . ($index + 1), ($index + 1) . '. ' . $auditor);
        }

        if (count($auditors) <= 2) {
            $templateProcessor->setValues(['auditor#2' => '']);
            $templateProcessor->setValues(['auditor#3' => '']);
        } else if (count($auditors) <= 3) {
            $templateProcessor->setValues(['auditor#3' => '']);
        }

        // Isi
        $values = [];
        $no = 1;
        foreach ($jawaban_auditor as $jawaban) {
            $values[] = [
                'no' => $no++,
                'kode' => $jawaban->form->instrumen->kode,
                'pernyataan' => $jawaban->form->instrumen->pernyataan,
                'catatan' => $jawaban->catatan,
            ];
        }

        if (!empty($values)) {
            $templateProcessor->cloneRowAndSetValues('no', $values);
        } else {
            $values[] = [
                'no' => 1,
                'kode' => null,
                'pernyataan' => null,
                'catatan' => null,
            ];
            $templateProcessor->cloneRowAndSetValues('no', $values);
        }

        $fileName = $title . ' ' . strtoupper($unitData ? $unitData['unitName'] : '') . ' ' . $date->isoFormat('D MMMM YYYY') . '.docx';
        $filePath = storage_path('app/public/' . $fileName);
        $templateProcessor->saveAs($filePath);

        return response()->download($filePath)->deleteFileAfterSend(true);
    }

    // Download Isian Auditee
    public function download_isi_audit_auditee(string $jadwalId, string $unitId, string $type)
    {
        $title = "Hasil Pengisian Audit";
        $jawabanAuditee = JawabanAuditee::where('jadwal_audit_id', $jadwalId)
            ->where(get_type($type), $unitId)
            ->join('form', 'jawaban_auditee.form_id', '=', 'form.id')
            ->join('instrumen', 'form.instrumen_id', '=', 'instrumen.id')
            ->with(['form.instrumen'])
            ->orderBy('instrumen.standar_id', 'asc')
            ->orderBy('instrumen.kategori_id', 'asc')
            ->orderByRaw("REGEXP_REPLACE(instrumen.kode, '[^0-9]', '', 'g')::int NULLS FIRST, REGEXP_REPLACE(instrumen.kode, '[0-9]', '', 'g') ASC")
            ->select('jawaban_auditee.*')
            ->get();

        if (!$jawabanAuditee || $jawabanAuditee->isEmpty()) {
            return back()->with('error', 'Jawaban masih kosong.');
        }

        $unitData = $this->get_unit($jawabanAuditee->first());

        $auditors = AuditeeAuditor::where(['jadwal_audit_id' => $jadwalId, get_type($type) => $unitId])
            ->with('auditor.user')
            ->get()
            ->pluck('auditor.user.name')
            ->unique()->values();

        $templateProcessor = new \PhpOffice\PhpWord\TemplateProcessor(storage_path('app/public/template/template_isi_audit_auditee.docx'));

        \Carbon\Carbon::setLocale('id');

        $status = StatusAuditAuditee::where(['jadwal_audit_id' => $jadwalId, get_type($type) => $unitId])->pluck('updated_at')->first();

        $date = \Carbon\Carbon::parse($status);

        $templateProcessor->setValues([
            'date' => $date->isoFormat('D MMMM YYYY'),
            'unit' => $unitData ? $unitData['unitName'] : ''
        ]);

        // Auditor
        if ($auditors->isEmpty()) {
            $templateProcessor->setValues(['auditor#1' => '']);
            $templateProcessor->setValues(['auditor#2' => '']);
            $templateProcessor->setValues(['auditor#3' => '']);
        } else {
            foreach ($auditors as $index => $auditor) {
                $templateProcessor->setValue('auditor#' . ($index + 1), ($index + 1) . '. ' . $auditor);
            }

            if (count($auditors) <= 2) {
                $templateProcessor->setValues(['auditor#2' => '']);
                $templateProcessor->setValues(['auditor#3' => '']);
            } else if (count($auditors) <= 3) {
                $templateProcessor->setValues(['auditor#3' => '']);
            }
        }

        // Isi
        $groupedValues = [];
        $no = 1;
        foreach ($jawabanAuditee as $index => $form) {
            $instrumenKode = $form->form->instrumen->kode;
            $links = Link::where('jadwal_audit_id', $jadwalId)
                ->where(function ($query) use ($unitId, $type) {
                    $query->where(get_type($type), $unitId);
                })
                ->where('form_id', $form->form_id)
                ->with(['form.instrumen'])
                ->get();

            if (!isset($groupedValues[$instrumenKode])) {
                $groupedValues[$instrumenKode] = [
                    'no' => $no++ . '. ',
                    'kode' => $instrumenKode,
                    'pernyataan' => $form->form->instrumen->pernyataan,
                    'jawaban' => $form->jawaban,
                    'link' => []
                ];
            }
            foreach ($links as $link) {
                $trimLink = htmlspecialchars($link->link);
                $groupedValues[$instrumenKode]['link'][] = '- ' . $trimLink;
            }
        }

        $values = [];
        foreach ($groupedValues as $group) {
            $values[] = [
                'no' => $group['no'],
                'kode' => $group['kode'],
                'pernyataan' => $group['pernyataan'],
                'jawaban' => $group['jawaban'],
                'link' => implode("<w:br/>", $group['link'])
            ];
        }

        if (empty($values) && $jawabanAuditee->isNotEmpty()) {
            foreach ($jawabanAuditee as $index => $form) {
                $values[] = [
                    'no' => $no++ . '. ',
                    'kode' => $form->form->instrumen->kode,
                    'pernyataan' => $form->form->instrumen->pernyataan,
                    'jawaban' => $form->jawaban,
                    'link' => ''
                ];
            }

            $templateProcessor->cloneRowAndSetValues('no', $values);
        } elseif (!empty($values)) {
            $templateProcessor->cloneRowAndSetValues('no', $values);
        } else {
            $templateProcessor->cloneRowAndSetValues('no', [['no' => '', 'kode' => '', 'pernyataan' => '', 'jawaban' => '', 'link' => '']]);
        }

        $fileName = $title . ' ' . ($unitData ? $unitData['unitName'] : '') . ' Tanggal ' . $date->isoFormat('D MMMM YYYY') . '.docx';
        $filePath = storage_path('app/public/' . $fileName);
        $templateProcessor->saveAs($filePath);

        return response()->download($filePath)->deleteFileAfterSend(true);
    }

    public function download_auditee_auditor(JadwalAudit $jadwalAudit)
    {
        $title = "Daftar Auditee dan Auditor";
        $prodi = Prodi::with(['jenjang', 'fakultas'])->get();
        $fakultas = Fakultas::all();
        $unit = Unit::all();

        foreach ($fakultas as $f) {
            $f->nama = 'Fakultas ' . $f->nama;
        }

        $mergedUnit = $prodi->concat($fakultas)->concat($unit);

        $templateProcessor = new \PhpOffice\PhpWord\TemplateProcessor(storage_path('app/public/template/template_daftar_auditor.docx'));

        \Carbon\Carbon::setLocale('id');
        $date = \Carbon\Carbon::parse($jadwalAudit->created_at);

        $templateProcessor->setValues([
            'tahun' => $date->isoFormat('YYYY'),
        ]);

        $no = 1;
        $groupedValues = [];

        foreach ($mergedUnit as $u) {
            $auditorIds = AuditeeAuditor::where('jadwal_audit_id', $jadwalAudit->id)
                ->where(function ($query) use ($u) {
                    $query->where('prodi_id', $u->id)
                        ->orWhere('fakultas_id', $u->id)
                        ->orWhere('unit_id', $u->id);
                })
                ->distinct('auditor_id')
                ->pluck('auditor_id');

            $auditors = Auditor::where('jadwal_audit_id', $jadwalAudit->id)->whereIn('id', $auditorIds)
                ->with('user')
                ->get();

            $nama = $auditors->map(function ($auditor) {
                return '- ' . $auditor->user->name;
            })->implode(PHP_EOL);


            $groupedValues[] = [
                'no' => $no++,
                'auditor' => $nama ? $nama : 'Belum ada auditor',
                'auditee' => optional($u->jenjang)->nama ? $u->nama . ' ' . $u->jenjang->nama : $u->nama,
                'fakultas' => optional($u->fakultas)->nama ? $u->fakultas->nama : '-',
            ];
        }

        if (!empty($groupedValues)) {
            $templateProcessor->cloneRowAndSetValues('no', $groupedValues);
        } else {
            $groupedValues[] = [
                'no' => 1,
                'auditor' => null,
                'auditee' => null,
                'fakultas' => null,
            ];
            $templateProcessor->cloneRowAndSetValues('no', $groupedValues);
        }

        $fileName = $title . ' ' . $date->isoFormat('YYYY') . '.docx';
        $filePath = storage_path('app/public/' . $fileName);
        $templateProcessor->saveAs($filePath);

        return response()->download($filePath)->deleteFileAfterSend(true);
    }
}
