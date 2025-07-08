<?php

namespace Database\Factories;

use App\Models\JadwalAudit;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\JadwalAudit>
 */
class JadwalAuditFactory extends Factory
{
    protected $model = JadwalAudit::class;

    public function definition()
    {
        return [
            'jadwal' => $this->faker->sentence(3),
            'tgl_mulai' => $this->faker->date(),
            'tgl_selesai' => $this->faker->date(),
        ];
    }
}
