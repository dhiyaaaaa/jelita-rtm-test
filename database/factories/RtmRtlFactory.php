<?php

namespace Database\Factories;

use App\Models\Fakultas;
use App\Models\JadwalAudit;
use App\Models\RtmRtl;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\RtmRtl>
 */
class RtmRtlFactory extends Factory
{
    protected $model = RtmRtl::class;
    public function definition(): array
    {
        return [
            'jadwal_audit_id' => JadwalAudit::factory(),
            'rtm_jadwal_id' => JadwalAudit::factory(),
            'fakultas_id' => Fakultas::factory(),
            'unit_id' => null,
            'tgl' => $this->faker->date()
        ];
    }
}
