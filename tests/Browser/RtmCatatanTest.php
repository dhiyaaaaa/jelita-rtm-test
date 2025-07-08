<?php

namespace Tests\Browser;

use App\Models\RtmJadwal;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class RtmCatatanTest extends DuskTestCase
{
    /**
     * A Dusk test example.
     */

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
    
        $this->adminUser->roles()->create(['name' => 'pusjamu']);

        $this->rtmJadwal = RtmJadwal::factory()->create();
    }

    public function testCreateRtmCatatan()
    {
        $this->browse(function (Browser $browser) {
            $testJudul = 'Dusk Test Catatan Judul ' . uniqid();
            $testIsi = '<p>Dusk Test Isi Narasi ' . uniqid() . '</p>';

            $browser->loginAs($this->adminUser)
                    ->visit(route('admin.rtm-catatan.form', ['rtmJadwal' => $this->rtmJadwal->id]))
                    ->waitForText('Narasi Laporan RTM', 10)
                    ->type('input[name="judul"]', $testJudul) // Menggunakan selektor name
                    // Summernote interaction with existing structure
                    ->script("document.querySelector('textarea[name=\"isi\"]').closest('.note-editor').querySelector('.note-editable').innerHTML = '{$testIsi}';");

            $browser->press('button[type="submit"].btn-primary') // Menggunakan selektor tipe dan kelas
                    ->waitForText('Data berhasil disimpan.', 10)
                    ->assertSee($testJudul);

            $this->assertDatabaseHas('rtm_catatan', [
                'rtm_jadwal_id' => $this->rtmJadwal->id,
                'judul' => $testJudul,
                'isi' => $testIsi,
            ]);
        });
    }

}
