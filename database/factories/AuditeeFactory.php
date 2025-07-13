<?php

namespace Database\Factories;

use App\Models\Auditee;
use App\Models\Fakultas;
use App\Models\Jabatan;
use App\Models\JadwalAudit;
use App\Models\Unit;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Auditee>
 */
class AuditeeFactory extends Factory
{
    protected $model = Auditee::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'jadwal_audit_id' => JadwalAudit::factory(),
            'jabatan_id' => Jabatan::factory(),
            'fakultas_id' => Fakultas::factory(),
            'unit_id' => Unit::factory(),
            'prodi_id' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
