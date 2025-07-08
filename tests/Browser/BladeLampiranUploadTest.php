<?php

namespace Tests\Browser;

use App\Models\RtmJadwal;
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Log;

class BladeLampiranUploadTest extends DuskTestCase
{
    use DatabaseMigrations;

    protected $times = [];
    
    public function testBladeUploadPerformance()
    {
        $this->browse(function (Browser $browser) {
            // Login sebagai admin
            $browser->loginAs($this->createAdminUser());
            
            // Buat data RTM untuk testing
            $rtm = RtmJadwal::factory()->create();
            
            // Jalankan test 10x
            for ($i = 1; $i <= 10; $i++) {
                $startTime = microtime(true);
                
                $browser->visit('/admin/rtm-univ')
                    ->click('.lampiran-btn')
                    ->waitFor('#lampiranModal')
                    ->attach('#undangan', storage_path('testing/undangan.pdf'))
                    ->attach('#presensi', storage_path('testing/presensi.pdf'))
                    ->attach('#dokumentasi', storage_path('testing/dokumentasi.pdf'))
                    ->click('#btnSimpan')
                    ->waitForText('Lampiran berhasil disimpan!');
                
                $endTime = microtime(true);
                $this->times[] = $endTime - $startTime;
                
                $browser->pause(1000); // Jeda antara pengujian
            }
            
            // Output hasil
            $this->outputPerformanceResults('Blade');
        });
    }
    
    protected function outputPerformanceResults($type)
    {
        $average = array_sum($this->times) / count($this->times);
        $min = min($this->times);
        $max = max($this->times);
        
        echo "\n\n$type Upload Performance (10 tests):\n";
        echo "Average: " . number_format($average, 3) . "s\n";
        echo "Min: " . number_format($min, 3) . "s\n";
        echo "Max: " . number_format($max, 3) . "s\n";
        
        // Simpan ke log untuk analisis lebih lanjut
        Log::info("$type Upload Performance", [
            'times' => $this->times,
            'average' => $average,
            'min' => $min,
            'max' => $max
        ]);
    }
}