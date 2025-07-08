<?php

namespace Database\Factories;

use App\Models\JadwalAudit;
use App\Models\RtmJadwal;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\RtmJadwal>
 */
class RtmJadwalFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * 
     */
    protected $model = RtmJadwal::class;

    public function definition()
    {
        return [
            'jadwal_audit_id' => JadwalAudit::factory(), 
            'tanggal' => $this->faker->date(),
            'tempat' => $this->faker->address,
            'agenda' => $this->faker->sentence,
            'jam_mulai' => '10:00',
            'jam_selesai' => '12:00',
            'pimpinan' => $this->faker->name,
            'peserta' => $this->faker->numberBetween(5, 20),
        ];
        
    }
}
