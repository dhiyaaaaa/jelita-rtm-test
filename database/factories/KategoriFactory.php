<?php

namespace Database\Factories;

use App\Models\Kategori;
use App\Models\Standar;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Kategori>
 */
class KategoriFactory extends Factory
{
    protected $model = Kategori::class;

    public function definition(): array
    {
        return [
            'standar_id' => Standar::factory(),
            'nama' => $this->faker->sentence(4),
            'kode' => $this->faker->sentence(4),
        ];
    }
}
