<?php

namespace Database\Factories;

use App\Models\Jabatan;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Jabatan>
 */
class JabatanFactory extends Factory
{
    protected $model = Jabatan::class;

    public function definition(): array
    {
        return [
            'nama' => $this->faker->unique()->word,
            'slug' => $this->faker->unique()->word, // Generate a unique slug from the name
            'type' => $this->faker->randomElement(['prodi', 'fakultas', 'universitas']), // Ensure it matches the enum values
            'unik' => $this->faker->boolean(),
        ];
    }

}
