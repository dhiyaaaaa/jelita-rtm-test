<?php

namespace Database\Seeders;

use App\Models\AssessmentPertanyaan;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AssessmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $pertanyaan = [
            'Rekan auditor memenuhi kode etik (integritas, obyektifitas, kerahasiaan, kompeten) selama bertugas audit mutu internal.',
            'Rekan auditor telah melakukan audit dokumen sebelum melakukan audit lapangan.',
            'Rekan auditor mampu berkomunikasi dengan baik kepada pihak yang diaudit dan tim auditor lainnya.',
            'Rekan auditor berkontribusi dan mampu bekerja sama dalam tim audit mutu internal.',
            'Rekan auditor menunjukkan kedisiplinan dalam menjalankan tugas audit mutu internal.',
            'Rekan auditor berpenampilan yang baik, rapi, dan sopan.',
        ];

        foreach($pertanyaan as $item){
            AssessmentPertanyaan::create([
                'pertanyaan' => $item
            ]);
        }
    }
}
