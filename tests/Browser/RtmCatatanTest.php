<?php

namespace Tests\Browser;

use App\Models\RtmCatatan;
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

        $this->artisan('migrate:fresh');

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
                    ->type('input[name="judul"]', $testJudul)
                    ->waitFor('.note-editor')
                    ->script([
                        "document.querySelector('.note-editable').innerHTML = '{$testIsi}';",
                        "document.querySelector('textarea[name=\"isi\"]').value = '{$testIsi}';"
                    ]);

            $browser->press('button[type="submit"].btn-primary')
                    ->waitForText('Data berhasil disimpan.', 10)
                    ->assertSee($testJudul);

            $this->assertDatabaseHas('rtm_catatan', [
                'rtm_jadwal_id' => $this->rtmJadwal->id,
                'judul' => $testJudul,
                'isi' => $testIsi,
            ]);
        });   
    }

    public function testReadRtmCatatan()
    {
        $catatan = RtmCatatan::factory()->create([
            'rtm_jadwal_id' => $this->rtmJadwal->id,
            'user_id' => $this->adminUser->id,
            'judul' => 'Test Judul Catatan',
            'isi' => '<p> Test isi catatan</p>'
        ]);

        $this->browse(function (Browser $browser) use ($catatan) {
            $browser->loginAs($this->adminUser)
                    ->visit(route('admin.rtm-catatan.form', ['rtmJadwal' => $this->rtmJadwal->id]))
                    ->waitForText('Daftar Narasi RTM', 10)
                    ->assertSee($catatan->judul)
                    ->assertSee('Test isi catatan');
        });
    }

    public function testUpdateRtmCatatan()
    {
        $catatan = RtmCatatan::factory()->create([
            'rtm_jadwal_id' => $this->rtmJadwal->id,
            'user_id' => $this->adminUser->id,
            'judul' => 'Judul Awal',
            'isi' => '<p>Isi Awal</p>'
        ]);

        $this->browse(function (Browser $browser) use ($catatan) {
            $updatedJudul = 'Updated Judul ' . uniqid();
            $updatedIsi = '<p> Updated Judul ' . uniqid() . '</p>';

            $browser->loginAs($this->adminUser)
                    ->visit(route('admin.rtm-catatan.form', ['rtmJadwal' => $this->rtmJadwal->id]))
                    ->waitForText('Daftar Narasi RTM', 10)
                    ->click('@edit-catatan-' . $catatan->id)
                    ->waitForText('Simpan Perubahan', 10)
                    ->type('input[name="judul"]', $updatedJudul)
                    ->waitfor('.note-editor')
                    ->script([
                         "document.querySelector('.note-editable').innerHTML = '{$updatedIsi}';",
                        "document.querySelector('textarea[name=\"isi\"]').value = '{$updatedIsi}';"
                    ]);
            $browser->press('button[type="submit"].btn-primary')
                    ->waitForText('Catatan Narasi berhasil diperbarui.', 10)
                    ->assertSee($updatedJudul);

            $this->assertDatabaseHas('rtm_catatan', [
                'id' => $catatan->id,
                'judul' => $updatedJudul,
                'isi' => $updatedIsi,
            ]);
        });
    }

    public function testDeleteRtmCatatan()
    {
        $catatan = RtmCatatan::factory()->create([
            'rtm_jadwal_id' => $this->rtmJadwal->id,
            'user_id' => $this->adminUser->id,
            'judul' => 'Hapus Judul ' . uniqid(),
            'isi' => '<p>Isi Awal</p>'
        ]);

        $this->browse(function (Browser $browser) use ($catatan) {
            $browser->loginAs($this->adminUser)
                    ->visit(route('admin.rtm-catatan.form', ['rtmJadwal' => $this->rtmJadwal->id]))
                    ->waitForText('Daftar Narasi RTM', 10)
                    ->assertSee($catatan->judul)
                    ->waitFor('@delete-catatan-' . $catatan->id)
                    ->click('@delete-catatan-' . $catatan->id)
                    ->waitFor('.swal2-container', 10) 
                    ->assertSee('Hapus Catatan Narasi RTM?')
                    ->press('button.swal2-confirm')
                    ->waitForText('Catatan Narasi berhasil dihapus', 10)
                    ->assertDontSee($catatan->judul);

            $this->assertDatabaseMissing('rtm_catatan', [
                'id' => $catatan->id
            ]);
        });
    }
}
