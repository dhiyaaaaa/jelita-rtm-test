<?php

namespace Tests\Browser;

use App\Models\RtmCatatan;
use App\Models\RtmJadwal;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class RtmCatatanTestLivewire extends DuskTestCase
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
        $this->rtmJadwal = RtmJadwal::factory()->create();
    }

    protected function fillSummernote(Browser $browser, $content)
    {
        $browser->waitFor('.note-editable', 15)
                ->scrollTo('.note-editable')
                ->click('.note-editable')
                ->script("
                    document.querySelector('.note-editable').innerHTML = `{$content}`;
                    document.querySelector('textarea.summernote').value = `{$content}`;
                    document.querySelector('textarea.summernote').dispatchEvent(new Event('input'));
                ");
    }

    public function testCreateRtmCatatan()
    {
        $this->browse(function (Browser $browser) {
            $testJudul = 'Test Catatan Judul ' . uniqid();
            $testIsi = 'Test Isi Narasi ' . uniqid(); // Plain text instead of HTML

            $browser->loginAs($this->adminUser)
                    ->visit(route('admin.rtm-catatan.form-livewire', ['rtmJadwal' => $this->rtmJadwal->id]))
                    ->waitForText('Narasi Laporan RTM', 15)
                    ->type('@judul-input', $testJudul)
                    ->pause(2000)
                    ->scrollTo('.summernote')
                    ->waitFor('.note-editable', 15);
            
            $this->fillSummernote($browser, $testIsi);
            
            $browser->scrollTo('#submit-button')
                    ->pause(1000)
                    ->click('#submit-button')
                    ->waitForText('catatan berhasil disimpan', 25)
                    ->assertSee($testJudul);

            $this->assertDatabaseHas('rtm_catatan', [
                'rtm_jadwal_id' => $this->rtmJadwal->id,
                'judul' => $testJudul,
                'isi' => $testIsi, // Match the plain text version
                'user_id' => $this->adminUser->id
            ]);
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
            $updatedIsi = '<p>Updated Isi ' . uniqid() . '</p>';

            $browser->loginAs($this->adminUser)
                    ->visit(route('admin.rtm-catatan.form-livewire', ['rtmJadwal' => $this->rtmJadwal->id]))
                    ->waitForText('Daftar Narasi RTM', 15)
                    ->scrollTo('@edit-catatan-' . $catatan->id)
                    ->click('@edit-catatan-' . $catatan->id)
                    ->waitForText('Update', 15)
                    ->type('@judul-input', $updatedJudul)
                    ->pause(2000) // Allow Summernote to initialize
                    ->scrollTo('.summernote')
                    ->waitFor('.note-editable', 15);
            
            $this->fillSummernote($browser, $updatedIsi);

            $browser->pause(3000);
            
            $browser->scrollTo('#submit-button')
                    ->click('#submit-button')
                    ->waitForText('Catatan berhasil diperbarui', 25) // Increased timeout
                    ->assertSee($updatedJudul);

            $this->assertDatabaseHas('rtm_catatan', [
                'id' => $catatan->id,
                'judul' => $updatedJudul,
                'isi' => $updatedIsi, // Use the decoded content for assertion
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
                    ->visit(route('admin.rtm-catatan.form-livewire', ['rtmJadwal' => $this->rtmJadwal->id]))
                    ->waitForText('Daftar Narasi RTM', 15)
                    ->assertSee($catatan->judul)
                    ->assertSee('Test isi catatan');
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
                    ->disableFitOnFailure()
                    ->visit(route('admin.rtm-catatan.form-livewire', ['rtmJadwal' => $this->rtmJadwal->id]))
                    ->waitForText('Daftar Narasi RTM', 15)
                    ->assertSee($catatan->judul)
                    ->scrollTo('@delete-catatan-' . $catatan->id)
                    ->click('@delete-catatan-' . $catatan->id)
                    ->waitFor('.swal2-container', 15)
                    ->assertSee('Hapus Catatan Narasi RTM?')
                    ->press('button.swal2-confirm')
                    ->waitForText('Catatan narasi berhasil dihapus', 25)
                    ->assertDontSee($catatan->judul);

            $this->assertDatabaseMissing('rtm_catatan', [
                'id' => $catatan->id
            ]);
        });
    }
}