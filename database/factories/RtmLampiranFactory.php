<?php

namespace Database\Factories;

use App\Models\RtmJadwal;
use App\Models\RtmLampiran;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\RtmLampiran>
 */
class RtmLampiranFactory extends Factory
{
    protected $model = RtmLampiran::class;

    public function definition(): array
    {
        return [
            'rtm_jadwal_id' => RtmJadwal::factory(),
            'undangan' => 'lampiran/undangan.pdf',
            'presensi' => 'lampiran/presensi.pdf',
            'dokumentasi' => 'lampiran/dokumentasi.pdf',
        ];
    }
}
