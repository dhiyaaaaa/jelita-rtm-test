<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            JabatanSeeder::class,
            RoleSeeder::class,
            MenuSeeder::class,
            ProdiSeeder::class,
            PeraturanSeeder::class,
            JabatanUserSeeder::class,
            InstrumenSeeder::class,
            InstrumenSamaSeeder::class,
            JadwalSeeder::class,
            AssessmentSeeder::class,
        ]);
    }
}
