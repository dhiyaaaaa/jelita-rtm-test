<?php

namespace Tests\Browser;

use App\Models\RtmJadwal;
use App\Models\RtmLampiran;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Http\UploadedFile; // Import ini
use Illuminate\Support\Facades\Storage;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class RtmLampiranUploadTest extends DuskTestCase
{
    protected $adminUser;
    protected $rtmJadwal;
    protected $rtmLampiran;

    protected function setUp(): void
    {
        parent::setUp();

        // Pastikan database di-refresh untuk setiap tes
        $this->artisan('migrate:fresh');

        // Buat user admin dengan role yang sesuai
        $this->adminUser = User::factory()->create([
            'email' => 'admin@example.com',
            'password' => bcrypt('password')
        ]);
        
        // Asumsi role 'pusjamu' sudah ada atau dibuat di seeder
        // Jika belum ada, Anda perlu memastikan role ini dibuat terlebih dahulu
        // atau gunakan User::factory()->admin()->create() jika ada factory admin
        $this->adminUser->roles()->create(['name' => 'pusjamu']);

        $this->rtmJadwal = RtmJadwal::factory()->create();
    }

    public function testUploadLampiran()
{
    Storage::fake('lampiran_rtm');
    
    $this->browse(function (Browser $browser) {
        // Buat file dummy dengan ukuran lebih kecil untuk testing
        $undanganPath = tempnam(sys_get_temp_dir(), 'undangan') . '.pdf';
        file_put_contents($undanganPath, str_repeat('A', 500)); // 500KB
        
        $presensiPath = tempnam(sys_get_temp_dir(), 'presensi') . '.pdf';
        file_put_contents($presensiPath, str_repeat('B', 500)); // 500KB
        
        $dokumentasiPath = tempnam(sys_get_temp_dir(), 'dokumentasi') . '.pdf';
        file_put_contents($dokumentasiPath, str_repeat('C', 1000)); // 1MB

        $browser->loginAs($this->adminUser)
                ->visit(route('admin.rtm-univ.index'))
                ->waitFor('.lampiran-btn')
                ->click('.lampiran-btn')
                ->waitFor('#lampiranModal')
                ->within('#lampiranModal', function(Browser $modal) use ($undanganPath, $presensiPath, $dokumentasiPath) {
                    // Pastikan ID terisi dengan cara yang lebih reliable
                    $modal->value('#rtm_jadwal_id', $this->rtmJadwal->id);
                    
                    // Tambahkan pause dan screenshot
                    $modal->pause(1000)
                          ->screenshot('before-attach')
                          ->attach('input[name="undangan"]', $undanganPath)
                          ->attach('input[name="presensi"]', $presensiPath)
                          ->attach('input[name="dokumentasi"]', $dokumentasiPath)
                          ->pause(1000)
                          ->screenshot('before-submit')
                          ->press('Simpan');
                })
                // Tunggu modal menghilang terlebih dahulu
                
                // Tunggu teks sukses dengan timeout lebih lama
                ->waitForText('Lampiran berhasil disimpan!', 30)
                ->assertSee('Lampiran berhasil disimpan!')
                ->waitUntilMissing('#lampiranModal', 30)
                ->screenshot('after-success');
        
        // Verifikasi database
        $this->assertDatabaseHas('rtm_lampiran', [
            'rtm_jadwal_id' => $this->rtmJadwal->id
        ]);
        
        // Hapus file dummy
        unlink($undanganPath);
        unlink($presensiPath);
        unlink($dokumentasiPath);
    });
}

public function testUploadLampiranValidation()
{
    $this->browse(function (Browser $browser) {
        $browser->loginAs($this->adminUser)
                ->visit(route('admin.rtm-univ.index'))
                ->screenshot('before-click')
                ->waitFor('.lampiran-btn')
                ->click('.lampiran-btn')
                ->waitFor('#lampiranModal')
                ->screenshot('modal-shown')
                ->within('#lampiranModal', function(Browser $modal) {
                    // Set ID dengan cara yang lebih reliable
                    $modal->value('#rtm_jadwal_id', $this->rtmJadwal->id);
                    
                    // Submit tanpa file
                    $modal->press('Simpan')
                          ->pause(1000)
                          ->screenshot('after-submit');
                    
                    // Tunggu pesan validasi dengan selector yang lebih spesifik
                    $modal->waitFor('div.invalid-feedback', 30)
                          ->assertSee('The undangan field is required')
                          ->assertSee('The presensi field is required')
                          ->assertSee('The dokumentasi field is required');
                })
                ->screenshot('validation-errors');
    });
}
}
   