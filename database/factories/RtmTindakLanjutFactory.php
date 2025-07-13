<?php

namespace Database\Factories;

use App\Models\Auditee;
use App\Models\Form;
use App\Models\Jabatan;
use App\Models\JadwalAudit;
use App\Models\Kriteria;
use App\Models\RtmRtl;
use App\Models\RtmTindakLanjut;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\RtmTindakLanjut>
 */
class RtmTindakLanjutFactory extends Factory
{
    protected $model = RtmTindakLanjut::class;

    public function definition(): array
    {
        return [
            'tindakan' => $this->faker->paragraph,
            'waktu' => $this->faker->paragraph,
            'rtm_rtl_id' => RtmRtl::factory(),
            'form_id' => Form::factory(),
            'jabatan_id' => Jabatan::factory(),
            'user_id' => User::factory(),
            'auditee_id' => Auditee::factory(),
            'kriteria_id' => Kriteria::factory(),
        ];
    }
}
