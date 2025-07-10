<?php

namespace Database\Factories;

use App\Models\Kriteria;
use Illuminate\Database\Eloquent\Factories\Factory;

class KriteriaFactory extends Factory
{
    protected $model = Kriteria::class;

    public function definition()
    {
        return [
            'nama' => 'Kriteria ' . $this->faker->unique()->word,
        ];
    }
}