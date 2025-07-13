<?php

namespace Database\Factories;

use App\Models\Peraturan;
use App\Models\Standar;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Standar>
 */
class StandarFactory extends Factory
{
    protected $model = Standar::class;

    public function definition(): array
    {
        return [
            'peraturan_id' => Peraturan::factory(),
            'nama' => $this->faker->sentence(4),
            'kode' => $this->faker->sentence(4),
        ];
    }
}
