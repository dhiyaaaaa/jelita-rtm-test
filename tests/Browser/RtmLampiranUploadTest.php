<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use Illuminate\Support\Facades\Storage;
use App\Models\RtmJadwal;
use App\Models\RtmLampiran;
use App\Models\User;

class RtmLampiranUploadTest extends DuskTestCase
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

            $dummyPdfContent = "%PDF-1.4\n1 0 obj<</Type/Catalog/Pages 2 0 R>>endobj 2 0 obj<</Type/Pages/Count 0>>endobj\nxref\n0 3\n0000000000 65535 f\n0000000009 00000 n\n0000000055 00000 n\ntrailer<</Size 3/Root 1 0 R>>startxref\n104\n%%EOF";

            file_put_contents("{$tempDir}/undangan.pdf", $dummyPdfContent);
            file_put_contents("{$tempDir}/presensi.pdf", $dummyPdfContent);
            file_put_contents("{$tempDir}/dokumentasi.pdf", $dummyPdfContent);

            $browser->loginAs($this->adminUser)
                ->visit(route('admin.rtm-rtl.show', $rtmJadwal->id))
                ->assertSee('Upload Lampiran RTM')
                ->screenshot('before_upload');

            $browser->attach('@input-undangan', "{$tempDir}/undangan.pdf")
                ->attach('@input-presensi', "{$tempDir}/presensi.pdf")
                ->attach('@input-dokumentasi', "{$tempDir}/dokumentasi.pdf")
                ->pause(1000)
                ->press('button[type="submit"].btn-primary')
                ->screenshot('after_submit');

            $browser->waitForLocation(route('admin.rtm-rtl.show', $rtmJadwal->id), 30)
                ->screenshot('after_reload');

            $browser->waitFor('.swal2-container', 30)
                ->screenshot('sweetalert_visible')
                ->waitForText('Lampiran berhasil disimpan.', 15)
                ->assertSeeIn('.swal2-title', 'Sukses!');
        });
    }
}