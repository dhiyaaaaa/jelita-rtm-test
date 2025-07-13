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

        // Buat user admin dengan role yang sesuai
        $this->adminUser = User::factory()->create([
            'email' => 'admin@example.com',
            'password' => bcrypt('password')
        ]);
        
        // Beri role pusjamu (sesuai middleware)
        $this->adminUser->roles()->create(['name' => 'pusjamu']);

        // Buat data jadwal audit
        $this->jadwalAudit = JadwalAudit::create([
            'jadwal' => 'Periode Audit Livewire Test',
            'tgl_mulai' => Carbon::now(),
            'tgl_selesai' => Carbon::now()->addMonth()
        ]);

        // Data untuk test
        $this->rtmData = [
            'agenda' => 'Rapat Livewire Dusk Test',
            'tanggal' => Carbon::tomorrow()->format('Y-m-d'),
            'tempat' => 'Ruangan Livewire Test',
            'jam_mulai' => '10:00',
            'jam_selesai' => '12:00',
            'pimpinan' => 'Pimpinan Livewire Test',
            'peserta' => 15,
            'jadwal_audit_id' => $this->jadwalAudit->id
        ];

        $this->rtmEditData = [
            'agenda' => 'Updated Rapat Livewire Dusk Test',
            'jam_mulai' => '11:00',
            'jam_selesai' => '13:00'
        ];
    }

    public function testCreateRtmJadwalLivewire()
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->adminUser)
                    ->visit(route('admin.rtm-univ.create-livewire'))
                    ->waitForText('Agenda Rapat Tinjauan Manajemen')
                    // Tambahkan wait untuk memastikan form terload sepenuhnya
                    ->waitFor('[dusk="agenda-input"]')
                    ->screenshot('before-filling-form')
                    
                    // Isi form
                    ->type('[dusk="agenda-input"]', $this->rtmData['agenda'])
                    ->type('[dusk="tanggal-input"]', $this->rtmData['tanggal'])
                    ->type('[dusk="tempat-input"]', $this->rtmData['tempat'])
                    ->type('[dusk="jam_mulai-input"]', $this->rtmData['jam_mulai'])
                    ->type('[dusk="jam_selesai-input"]', $this->rtmData['jam_selesai'])
                    ->type('[dusk="pimpinan-input"]', $this->rtmData['pimpinan'])
                    ->type('[dusk="peserta-input"]', $this->rtmData['peserta'])
                    ->select('[dusk="jadwal_audit_id-input"]', $this->jadwalAudit->id)
                    ->screenshot('after-filling-form')
                    
                    // Submit form
                    ->press('[dusk="simpan-button"]')
                    ->waitForLivewire() // Tunggu Livewire selesai memproses
                    ->screenshot('after-submit')
                    
                    // Tunggu lebih lama dan cek beberapa kemungkinan
                    ->waitForText('Jadwal RTM berhasil disimpan', 15) // Timeout 15 detik
                    ->assertSee($this->rtmData['agenda'])
                    ->assertPathIs(route('admin.rtm-univ.index-livewire'))
                    ->screenshot('after-success');
        });
    }

    public function testValidationCreateRtmJadwalLivewire()
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->adminUser)
                    ->visit(route('admin.rtm-univ.create-livewire'))
                    ->waitForText('Agenda Rapat Tinjauan Manajemen')
                    ->press('@simpan-button')
                    ->waitForText('Agenda wajib diisi.')
                    ->assertSee('Tanggal wajib diisi.')
                    ->assertSee('Tempat wajib diisi.')
                    ->assertSee('Jam mulai wajib diisi.')
                    ->assertSee('Jam selesai wajib diisi.')
                    ->assertSee('Pimpinan rapat wajib diisi.')
                    ->assertSee('Periode audit wajib dipilih.')
                    ->assertSee('Jumlah peserta wajib diisi.')
                    ->screenshot('livewire-validation-errors');
        });
    }

    public function testEditRtmJadwalLivewire()
    {
        // Buat data dulu
        $rtm = RtmJadwal::create($this->rtmData);

        $this->browse(function (Browser $browser) use ($rtm) {
            $browser->loginAs($this->adminUser)
                    ->visit(route('admin.rtm-univ.show-livewire', $rtm))
                    ->waitForText($this->rtmData['agenda'])
                    ->click('[dusk="edit-button"]') // Pastikan ada di view
                    ->waitFor('[dusk="edit-form"]')
                    ->type('[dusk="agenda-input"]', $this->rtmEditData['agenda'])
                    ->press('[dusk="update-button"]')
                    ->waitForText('Jadwal RTM berhasil diperbarui')
                    ->assertSee($this->rtmEditData['agenda'])
                    ->screenshot('livewire-after-edit');
        });
    }

    public function testDeleteRtmJadwalLivewire()
    {
        // Buat data dulu
        $rtm = RtmJadwal::create($this->rtmData);

        $this->browse(function (Browser $browser) use ($rtm) {
            $browser->loginAs($this->adminUser)
                    ->visit(route('admin.rtm-univ.index-livewire'))
                    ->waitFor('@dropdown-actions-'.$rtm->id)
                    ->within('@dropdown-actions-'.$rtm->id, function($dropdown) use ($rtm) {
                        $dropdown->click('@dropdown-toggle')
                                ->waitFor('@dropdown-menu')
                                ->click('@delete-rtm-id-'.$rtm->id);
                    })
                    ->waitForDialog()
                    ->assertDialogOpened('Apakah Anda yakin ingin menghapus jadwal RTM ini?')
                    ->acceptDialog()
                    ->waitForText('Jadwal RTM berhasil dihapus')
                    ->assertDontSee($this->rtmData['agenda'])
                    ->screenshot('after-delete-action');
        });
    }

    // public function testCancelDeleteRtmJadwalLivewire()
    // {
    //     // Buat data dulu
    //     $rtm = RtmJadwal::create($this->rtmData);

    //     $this->browse(function (Browser $browser) use ($rtm) {
    //         $browser->loginAs($this->adminUser)
    //                 ->visit(route('admin.rtm-univ.show-livewire', $rtm))
    //                 ->waitForText($this->rtmData['agenda'])
    //                 ->click('@delete-button')
    //                 ->waitFor('.swal2-container')
    //                 ->assertSee('Apakah Anda yakin ingin menghapus jadwal RTM ini?')
    //                 ->press('button.swal2-cancel')
    //                 ->waitUntilMissing('.swal2-container')
    //                 ->assertSee($this->rtmData['agenda'])
    //                 ->screenshot('livewire-cancel-delete');
    //     });
    // }
}