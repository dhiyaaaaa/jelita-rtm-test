<?php

namespace Tests\Browser;

use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use App\Models\RtmJadwal;
use App\Models\JadwalAudit;
use App\Models\User;
use Carbon\Carbon;

class RtmJadwalTest extends DuskTestCase
{
    protected $adminUser;
    protected $jadwalAudit;
    protected $rtmData;
    protected $rtmEditData;

    protected function setUp(): void
    {
        parent::setUp();

        $this->artisan('migrate:fresh');

        // Buat user admin dengan role yang sesuai
        $this->adminUser = User::factory()->create([
            'email' => 'admin@example.com',
            'password' => bcrypt('password')
        ]);
        
        // Beri role pusjamu (sesuai middleware)
        $this->adminUser->roles()->create(['name' => 'pusjamu']);

        // Buat data jadwal audit
        $this->jadwalAudit = JadwalAudit::create([
            'jadwal' => 'Periode Audit Test',
            'tgl_mulai' => Carbon::now(),
            'tgl_selesai' => Carbon::now()->addMonth()
        ]);

        // Data untuk test
        $this->rtmData = [
            'agenda' => 'Rapat Dusk Test',
            'tanggal' => Carbon::tomorrow()->format('Y-m-d'),
            'tempat' => 'Ruangan Test',
            'jam_mulai' => '10:00',
            'jam_selesai' => '12:00',
            'pimpinan' => 'Pimpinan Test',
            'peserta' => 10,
            'jadwal_audit_id' => $this->jadwalAudit->id
        ];

        $this->rtmEditData = [
            'jam_mulai' => '11:00',
            'jam_selesai' => '13.00'
        ];
    }

    public function testCreateRtmJadwal()
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->adminUser)
                    ->visit(route('admin.rtm-univ.index'))
                    ->clickLink('Buat Agenda RTM')
                    ->waitForText('Agenda Rapat Tinjauan Manajemen')
                    ->type('agenda', $this->rtmData['agenda'])
                    ->type('tanggal', $this->rtmData['tanggal'])
                    ->type('tempat', $this->rtmData['tempat'])
                    ->type('jam_mulai', $this->rtmData['jam_mulai'])
                    ->type('jam_selesai', $this->rtmData['jam_selesai'])
                    ->type('pimpinan', $this->rtmData['pimpinan'])
                    ->type('peserta', $this->rtmData['peserta'])
                    ->select('jadwal_audit_id', $this->jadwalAudit->id)
                    ->press('Simpan')
                    ->waitForText('Jadwal RTM berhasil disimpan')
                    ->assertSee($this->rtmData['agenda'])
                    ->screenshot('after-create');
        });
    }

    public function testEditRtmJadwal()
    {
        // Buat data dulu
        $rtm = RtmJadwal::create($this->rtmData);

        $this->browse(function (Browser $browser) use ($rtm) {
            $browser->loginAs($this->adminUser)
                    ->visit(route('admin.rtm-univ.index'))
                    ->waitFor('@dropdown-actions-'.$rtm->id)
                    ->with('@dropdown-actions-'.$rtm->id, function($dropdown) {
                        $dropdown->click('.dropdown-toggle')
                                ->waitFor('.dropdown-menu.show');
                    })
                    ->waitFor('@view-agenda-'.$rtm->id)
                    ->click('@view-agenda-'.$rtm->id)
                    ->waitForLocation(route('admin.rtm-univ.show', $rtm->id))
                    ->screenshot('before-edit-click')
                    ->waitFor('@edit-jadwal-btn')
                    ->assertVisible('@edit-jadwal-btn')
                    ->scrollTo('@edit-jadwal-btn')
                    ->pause(1000)
                    ->click('@edit-jadwal-btn')
                    ->pause(2000)
                    ->waitUntil('window.location.href.includes("edit=true")', 20)
                    ->waitFor('input[name="agenda"]')
                    ->type('jam_mulai', $this->rtmEditData['jam_mulai'])
                    ->type('jam_selesai', $this->rtmEditData['jam_selesai'])
                    ->screenshot('before-saving')
                    
                    // Handle form submission and redirect
                    ->press('Simpan Perubahan')
                    ->waitForLocation(route('admin.rtm-univ.show', $rtm->id))
                    ->waitFor('.swal2-container', 15)
                    ->assertSee('Data RTM berhasil diperbarui')
                    ->screenshot('after-edit');
        });
    }

    public function testDeleteRtmJadwal()
    {
        // Buat data dulu
        $rtm = RtmJadwal::create($this->rtmData);

        $this->browse(function (Browser $browser) use ($rtm) {
            $browser->loginAs($this->adminUser)
                    ->visit(route('admin.rtm-univ.index'))
                    ->waitFor('@dropdown-actions-'.$rtm->id)
                    ->with('@dropdown-actions-'.$rtm->id, function($dropdown) {
                        $dropdown->click('.dropdown-toggle')
                                ->waitFor('.dropdown-menu.show');
                    })
                    ->waitFor('@delete-rtm-id-'.$rtm->id)
                    ->click('@delete-rtm-id-'.$rtm->id)
                    ->waitFor('.swal2-container', 10) // Increased timeout to 10 seconds
                    ->screenshot('delete-confirmation')
                    ->assertSee('Apakah Anda yakin ingin menghapus jadwal RTM ini?')
                    ->press('button.swal2-confirm') // More reliable selector for SweetAlert button
                    ->waitForText('Jadwal RTM berhasil dihapus', 10)
                    ->assertDontSee($this->rtmData['agenda'])
                    ->screenshot('after-delete');
        });
    }
}