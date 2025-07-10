<?php

namespace Database\Factories;

use App\Models\RtmCatatan;
use App\Models\RtmJadwal;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\RtmCatatan>
 */
class RtmCatatanFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * 
     */

    protected $model = RtmCatatan::class;

    public function definition()
    {
        return [
            'rtm_jadwal_id' => RtmJadwal::factory(),
            'user_id' => User::factory(),
            'judul' => $this->faker->sentence(4),
            'isi' => $this->faker->paragraphs(3, true),
        ];
    }
}
