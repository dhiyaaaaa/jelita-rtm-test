<?php

namespace Tests\Browser;

use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use App\Models\RtmJadwal;
use App\Models\JadwalAudit;
use App\Models\User;
use Carbon\Carbon;

class RtmJadwalLivewireTest extends DuskTestCase
{
    protected $adminUser;
    protected $jadwalAudit;
    protected $rtmData;
    protected $rtmEditData;

    protected function setUp(): void
    {
        parent::setUp();

        $this->artisan('migrate:fresh');

        $this->adminUser = User::factory()->create([
            'email' => 'admin@example.com',
            'password' => bcrypt('password')
        ]);
        
        $this->adminUser->roles()->create(['name' => 'pusjamu']);

        $this->jadwalAudit = JadwalAudit::create([
            'jadwal' => 'AIMA 2025',
            'tgl_mulai' => Carbon::now(),
            'tgl_selesai' => Carbon::now()->addMonth()
        ]);

        $this->rtmData = [
            'agenda' => 'Rapat Tinjauan Manajemen',
            'tanggal' => Carbon::tomorrow()->format('Y-m-d'),
            'tempat' => 'Rektorat',
            'jam_mulai' => '10:00',
            'jam_selesai' => '12:00',
            'pimpinan' => 'Rektor',
            'peserta' => 15,
            'jadwal_audit_id' => $this->jadwalAudit->id
        ];

        $this->rtmEditData = [
            'agenda' => 'Updated Rapat Tinjauan Manajemen',
            'jam_mulai' => '11:00',
            'jam_selesai' => '13:00'
        ];
    }

    public function testCreateRtmJadwalLivewire()
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->adminUser)
                    ->visit(route('admin.rtm-univ.create-livewire'))
                    ->waitForText('Agenda Rapat Tinjauan Manajemen', 15)
                    ->waitFor('[dusk="agenda-input"]', 15)
                    ->screenshot('before-filling-form')
                    
                    ->type('[dusk="agenda-input"]', $this->rtmData['agenda'])
                    ->type('[dusk="tanggal-input"]', $this->rtmData['tanggal'])
                    ->type('[dusk="tempat-input"]', $this->rtmData['tempat'])
                    ->type('[dusk="jam_mulai-input"]', $this->rtmData['jam_mulai'])
                    ->type('[dusk="jam_selesai-input"]', $this->rtmData['jam_selesai'])
                    ->type('[dusk="pimpinan-input"]', $this->rtmData['pimpinan'])
                    ->type('[dusk="peserta-input"]', $this->rtmData['peserta'])
                    ->select('[dusk="jadwal_audit_id-input"]', $this->jadwalAudit->id)
                    ->screenshot('after-filling-form')
                    
                    ->press('[dusk="simpan-button"]')
                    ->waitForLocation(route('admin.rtm-univ.index-livewire'), 15)
                    ->waitForText('Jadwal RTM berhasil disimpan', 15)
                    ->assertSee($this->rtmData['agenda'])
                    ->screenshot('after-success');
        });
    }

    public function testEditRtmJadwalLivewire()
    {
        $rtm = RtmJadwal::create($this->rtmData);

        $this->browse(function (Browser $browser) use ($rtm) {
            $browser->loginAs($this->adminUser)
                    ->visit(route('admin.rtm-univ.show-livewire', $rtm))
                    ->waitForText($this->rtmData['agenda'], 15)
                    ->screenshot('before-edit')

                    ->click('[dusk="edit-button"]')
                    ->waitFor('[dusk="edit-form"]', 15)
                    ->screenshot('edit-form-opened')

                    ->type('[dusk="agenda-input"]', $this->rtmEditData['agenda'])
                    ->type('[dusk="jam_mulai-input"]', $this->rtmEditData['jam_mulai'])
                    ->type('[dusk="jam_selesai-input"]', $this->rtmEditData['jam_selesai'])

                    ->screenshot('form-filled')

                    ->press('[dusk="update-button"]')
                    ->waitForText('Jadwal RTM berhasil diperbarui', 15)
                    ->assertSee($this->rtmEditData['agenda'])
                    ->screenshot('after-edit-success');
        });
    }

    public function testDeleteRtmJadwalLivewire()
    {
        $rtm = RtmJadwal::create($this->rtmData);

        $this->browse(function (Browser $browser) use ($rtm) {
            $browser->loginAs($this->adminUser)
                    ->visit(route('admin.rtm-univ.index-livewire'))
                    ->waitFor('table')
                    ->screenshot('before-delete')
                    
                    ->with('@dropdown-actions-'.$rtm->id, function($dropdown) use ($rtm) {
                        $dropdown->click('@dropdown-toggle-'.$rtm->id)
                                ->waitFor('@dropdown-menu-'.$rtm->id)
                                ->click('@delete-rtm-id-'.$rtm->id);
                    })
                    ->waitForDialog(15)
                    ->assertDialogOpened('Apakah Anda yakin ingin menghapus jadwal RTM ini?')
                    ->acceptDialog()
                    ->waitForText('Jadwal RTM berhasil dihapus', 15)
                    ->assertDontSee($this->rtmData['agenda'])
                    ->screenshot('after-delete-success');
        });
    }

    // public function testValidationCreateRtmJadwalLivewire()
    // {
    //     $this->browse(function (Browser $browser) {
    //         $browser->loginAs($this->adminUser)
    //                 ->visit(route('admin.rtm-univ.create-livewire'))
    //                 ->waitForText('Agenda Rapat Tinjauan Manajemen')
    //                 ->press('@simpan-button')
    //                 ->waitForText('Agenda wajib diisi.')
    //                 ->assertSee('Tanggal wajib diisi.')
    //                 ->assertSee('Tempat wajib diisi.')
    //                 ->assertSee('Jam mulai wajib diisi.')
    //                 ->assertSee('Jam selesai wajib diisi.')
    //                 ->assertSee('Pimpinan rapat wajib diisi.')
    //                 ->assertSee('Periode audit wajib dipilih.')
    //                 ->assertSee('Jumlah peserta wajib diisi.')
    //                 ->screenshot('livewire-validation-errors');
    //     });
    // }

    
}