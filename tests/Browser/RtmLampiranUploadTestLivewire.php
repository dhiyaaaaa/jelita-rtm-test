<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use Illuminate\Support\Facades\Storage;
use App\Models\RtmJadwal;
use App\Models\RtmLampiran;
use App\Models\User;

class RtmLampiranUploadTestLivewire extends DuskTestCase
{
    protected $adminUser;
    protected $rtmJadwal;

    protected function setUp(): void
    {
        parent::setUp();
        $this->artisan('migrate:fresh');

        $this->adminUser = User::factory()->create([
            'email' => 'admin@example.com',
            'password' => bcrypt('password')
        ]);

        $this->adminUser->roles()->create(['name' => 'pusjamu']);
    }

    public function testUploadLampiran()
{
    $rtmJadwal = RtmJadwal::factory()->create();

    RtmLampiran::factory()->create([
        'rtm_jadwal_id' => $rtmJadwal->id,
        'undangan' => 'temp/undangan.pdf',
        'presensi' => 'temp/presensi.pdf',
        'dokumentasi' => 'temp/dokumentasi.pdf',
    ]);

    $this->browse(function (Browser $browser) use ($rtmJadwal) {
        $tempDir = storage_path('app/public/testing');
        if (!file_exists($tempDir)) {
            mkdir($tempDir, 0755, true);
        }

        // File PDF dummy yang sangat kecil
        $dummyPdfContent = "%PDF-1.4\n1 0 obj<</Type/Catalog/Pages 2 0 R>>endobj 2 0 obj<</Type/Pages/Count 0>>endobj\nxref\n0 3\n0000000000 65535 f\n0000000009 00000 n\n0000000055 00000 n\ntrailer<</Size 3/Root 1 0 R>>startxref\n104\n%%EOF";

        file_put_contents("{$tempDir}/undangan.pdf", $dummyPdfContent);
        file_put_contents("{$tempDir}/presensi.pdf", $dummyPdfContent);
        file_put_contents("{$tempDir}/dokumentasi.pdf", $dummyPdfContent);

        $browser->loginAs($this->adminUser)
            ->visit(route('admin.rtm-rtl-univ.show-livewire', $rtmJadwal->id))
            ->assertSee('Upload Lampiran RTM')
            ->screenshot('before_upload');

        // Upload file dengan waiting antara setiap operasi
        $browser->attach('@input-undangan', "{$tempDir}/undangan.pdf")
            ->pause(5000)
            ->attach('@input-presensi', "{$tempDir}/presensi.pdf")
            ->pause(5000)
            ->attach('@input-dokumentasi', "{$tempDir}/dokumentasi.pdf")
            ->pause(5000);
        
        // Submit form and wait for alert
        $browser->press('button[type="submit"].btn-primary')
            ->waitForText('Lampiran berhasil disimpan', 15) // Increased timeout to 15 seconds
            ->assertSee('Lampiran berhasil disimpan');
    });
}
}