<?php

namespace Database\Factories;

use App\Models\Peraturan;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Peraturan>
 */
class PeraturanFactory extends Factory
{
    protected $model = Peraturan::class;

    public function definition(): array
    {
        return [
            'status' => $this->faker->randomElement(['true', 'false']),
            'nama' => $this->faker->sentence(4),
            'tahun' => $this->faker->year(),
        ];
    }
}
