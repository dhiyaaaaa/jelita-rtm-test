<?php

namespace Database\Factories;

use App\Models\JawabanAuditor;
use App\Models\Form;
use App\Models\JadwalAudit;
use App\Models\Auditor;
use App\Models\Kriteria;
use App\Models\Prodi;
use App\Models\Fakultas;
use App\Models\Unit;
use Illuminate\Database\Eloquent\Factories\Factory;

class JawabanAuditorFactory extends Factory
{
    protected $model = JawabanAuditor::class;

    public function definition()
    {
        return [
            'catatan' => $this->faker->paragraph,
            'daftar_tilik' => $this->faker->randomElement(['true', 'false']),
            'form_id' => Form::factory(),
            'jadwal_audit_id' => JadwalAudit::factory(),
            'prodi_id' => null,
            'fakultas_id' => Fakultas::factory(),
            'unit_id' => Unit::factory(),
            'auditor_id' => Auditor::factory(),
            'kriteria_id' => Kriteria::factory(),
            'ptk' => $this->faker->randomElement(['true', 'false']),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}