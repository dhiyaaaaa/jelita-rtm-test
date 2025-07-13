<?php

namespace Database\Factories;

use App\Models\Instrumen;
use App\Models\JenisPertanyaan;
use App\Models\Kategori;
use App\Models\Level;
use App\Models\Peraturan;
use App\Models\Standar;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Instrumen>
 */
class InstrumenFactory extends Factory
{
    protected $model = Instrumen::class;

    public function definition()
    {
        return [
            'standar_id' => Standar::factory(),
            'peraturan_id' => Peraturan::factory(),
            'kategori_id' => Kategori::factory(),
            'level_id' => Level::factory(),
            'jenis_pertanyaan_id' => JenisPertanyaan::factory(),
            'pernyataan' => $this->faker->sentence(4),
            'indikator' => $this->faker->sentence(4),
            'kode' => $this->faker->sentence(4),
        ];
    }
}
