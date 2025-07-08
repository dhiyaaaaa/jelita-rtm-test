<?php

namespace Tests\Browser;

use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class LampiranUploadTest extends DuskTestCase
{
    /**
     * A Dusk test example.
     */
    protected static $bladeTimes = [];
    protected static $livewireTimes = [];

    public function testFileUpload()
    {
        $this->browse(function (Browser $browser) {
            // Buat user dengan role admin
            $user = User::factory()->create();
            $user->assignRole('pusjamu');
            
            $browser->loginAs($user)
                ->visit('/admin/rtm-univ')
                ->assertSee('Agenda RTM')
                ->screenshot('page-loaded')
                ->console()->dump();

            // Test Blade
            try {
                $browser->waitFor('.lampiran-btn', 15)
                    ->screenshot('before-blade-click')
                    ->click('.lampiran-btn')
                    ->waitFor('#lampiranModal', 15)
                    ->screenshot('blade-modal-shown');
                    
                // Jika modal muncul, lanjutkan upload
                $browser->attach('#undangan', storage_path('test-files/test_undangan.pdf'))
                    ->attach('#presensi', storage_path('test-files/test_presensi.pdf'))
                    ->attach('#dokumentasi', storage_path('test-files/test_dokumentasi.pdf'))
                    ->press('Simpan')
                    ->waitForText('Lampiran berhasil disimpan!', 20);
                    
            } catch (\Exception $e) {
                $browser->screenshot('error-blade');
                throw $e;
            }

                $browser->visit('/admin/rtm-univ/livewire')
                ->waitFor('.lampiran-btn', 10)
                ->screenshot('before-livewire-click')
                ->click('.lampiran-btn')
                ->waitFor('#livewireLampiranModal', 10)
                ->screenshot('livewire-modal-shown')
                ->attach('input[wire\\:model="undangan"]', storage_path('test-files/test_undangan.pdf'))
                ->attach('input[wire\\:model="presensi"]', storage_path('test-files/test_presensi.pdf'))
                ->attach('input[wire\\:model="dokumentasi"]', storage_path('test-files/test_dokumentasi.pdf'))
                ->press('Simpan')
                ->waitForText('Lampiran berhasil disimpan!', 15);
        });
    }

    
    public static function tearDownAfterClass(): void
    {
        // Hitung statistik
        $bladeAvg = array_sum(self::$bladeTimes) / count(self::$bladeTimes);
        $livewireAvg = array_sum(self::$livewireTimes) / count(self::$livewireTimes);
        
        $results = [
            'Blade' => [
                'Average' => $bladeAvg,
                'Min' => min(self::$bladeTimes),
                'Max' => max(self::$bladeTimes),
                'All Times' => self::$bladeTimes
            ],
            'Livewire' => [
                'Average' => $livewireAvg,
                'Min' => min(self::$livewireTimes),
                'Max' => max(self::$livewireTimes),
                'All Times' => self::$livewireTimes
            ],
            'Conclusion' => $bladeAvg < $livewireAvg ? 
                'Blade lebih cepat rata-rata' : 
                'Livewire lebih cepat rata-rata'
        ];
        
        Storage::put('performance_results.json', json_encode($results, JSON_PRETTY_PRINT));
        parent::tearDownAfterClass();
    }
}
