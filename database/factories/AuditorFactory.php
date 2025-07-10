<?php

namespace Database\Factories;

use App\Models\Auditor;
use App\Models\User;
use App\Models\JadwalAudit;
use Illuminate\Database\Eloquent\Factories\Factory;

class AuditorFactory extends Factory
{
    protected $model = Auditor::class;

    public function definition()
    {
        return [
            'user_id' => User::factory(),
            'jadwal_audit_id' => JadwalAudit::factory(),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }

}