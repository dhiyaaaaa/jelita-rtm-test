<?php

namespace Database\Factories;

use App\Models\JenisPertanyaan;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\JenisPertanyaan>
 */
class JenisPertanyaanFactory extends Factory
{
    protected $model = JenisPertanyaan::class;

    public function definition(): array
    {
        return [
            'nama' => $this->faker->sentence(4),
        ];
    }
}
