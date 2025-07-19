<?php

namespace Tests\Browser;

use App\Models\Auditee;
use App\Models\Auditor;
use App\Models\Fakultas;
use App\Models\Form;
use App\Models\Instrumen;
use App\Models\Jabatan;
use App\Models\JadwalAudit;
use App\Models\JawabanAuditor;
use App\Models\Kriteria;
use App\Models\RtmJadwal;
use App\Models\RtmRtl;
use App\Models\RtmTindakLanjut;
use App\Models\Unit;
use App\Models\User;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class FilterKriteriaCountLivewireTest extends DuskTestCase
{
    protected $adminUser;
    protected $rtmJadwal;
    protected $kriteria;
    protected $fakultas;
    protected $units = [];
    protected $jadwalAudit;
    protected $rtmRtl;

    protected function setUp(): void
    {
        parent::setUp();

        $this->artisan('migrate:fresh');
        Instrumen::factory()->create();

        $this->jadwalAudit = JadwalAudit::factory()->create([
            'jadwal' => 'Audit Contoh',
            'tgl_mulai' => now(),
            'tgl_selesai' => now()->addDays(7)
        ]);

        $this->adminUser = User::factory()->create([
            'email' => 'admin@example.com',
            'password' => bcrypt('password')
        ]);

        $this->adminUser->roles()->create(['name' => 'pusjamu']);

        $this->rtmJadwal = RtmJadwal::factory()->create([
            'jadwal_audit_id' => $this->jadwalAudit->id,
            'agenda' => 'Agenda Contoh',
            'pimpinan' => 'Pimpinan Contoh',
            'tempat' => 'Ruang Contoh'
        ]);

        $this->fakultas = Fakultas::factory()->count(2)->create([
            'nama' => 'Fakultas Test'
        ]);

        $this->rtmRtl = RtmRtl::factory()->create([
            'jadwal_audit_id' => $this->jadwalAudit->id,
            'rtm_jadwal_id' => $this->rtmJadwal->id,
            'fakultas_id' => $this->fakultas[0]->id,
            'unit_id' => null,
            'tgl' => now()->addDays(7)
        ]);

        $this->kriteria = [
            Kriteria::factory()->create(['nama' => 'Kriteria PTK']), 
            Kriteria::factory()->create(),
            Kriteria::factory()->create()
        ];

        $this->units[] = Unit::factory()->create(['nama' => 'Unit Testing 1']);
        $this->units[] = Unit::factory()->create(['nama' => 'Unit Testing 2']);

        $auditor = Auditor::factory()->create();
        $auditee = Auditee::factory()->create();
        $user = User::factory()->create();
        $jabatan = Jabatan::factory()->create();

        $formsFakultas1 = Form::factory()->count(2)->create();
        foreach ($formsFakultas1 as $form) {
            RtmTindakLanjut::factory()->create([
                'rtm_rtl_id' => $this->rtmRtl->id,
                'form_id' => $form->id,
                'auditee_id' => $auditee->id,
                'kriteria_id' => $this->kriteria[0]->id,
                'user_id' => $user->id,
                'jabatan_id' => $jabatan->id,
            ]);
        }

        $formsUnit1 = Form::factory()->count(3)->create();
        foreach ($formsUnit1 as $i => $form) {
            JawabanAuditor::factory()->create([
                'form_id' => $form->id,
                'unit_id' => $this->units[0]->id,
                'kriteria_id' => $this->kriteria[$i]->id, 
                'jadwal_audit_id' => $this->jadwalAudit->id,
                'auditor_id' => $auditor->id,
                'ptk' => ($i == 0), 
                'daftar_tilik' => ($i == 0) 
            ]);
        }

        $formUnit2 = Form::factory()->create();
        JawabanAuditor::factory()->create([
            'form_id' => $formUnit2->id,
            'unit_id' => $this->units[1]->id,
            'kriteria_id' => $this->kriteria[0]->id,
            'jadwal_audit_id' => $this->jadwalAudit->id,
            'auditor_id' => $auditor->id,
            'ptk' => true,
            'daftar_tilik' => true
        ]);
    }


    public function testShowHalamanRtmRtl()
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->adminUser)
                ->visit(route('admin.rtm-rtl-univ.show-livewire', ['rtmJadwalId' => $this->rtmJadwal->id])) 
                ->waitForText('Rapat Tinjauan Manajemen', 60)
                ->pause(2000)
                ->assertSee('Fakultas Test')
                ->assertSee('Unit Testing 1')
                ->assertSee('Unit Testing 2')
                ->assertSee('Jumlah Temuan')
                ->assertSee('Rencana Tindak Lanjut');
        });
    }

    public function testFilterDanHitungTemuanBerdasarkanKriteria()
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->adminUser)
                ->visit(route('admin.rtm-rtl-univ.show-livewire', ['rtmJadwalId' => $this->rtmJadwal->id]))
                ->waitForText('Rapat Tinjauan Manajemen', 60)
                ->pause(2000);
                
            $unit1RowSelector = "[dusk='unit-row-{$this->units[0]->id}']";
            $unit2RowSelector = "[dusk='unit-row-{$this->units[1]->id}']";

            $browser->waitFor($unit1RowSelector, 60)
                ->waitFor($unit2RowSelector, 60);

            $unit1InitialCount = (int)$browser->text("{$unit1RowSelector} td:nth-child(4) span");
            $unit2InitialCount = (int)$browser->text("{$unit2RowSelector} td:nth-child(4) span");

            $this->assertEquals(3, $unit1InitialCount, 'Unit Testing 1 memiliki 3 temuan');
            $this->assertEquals(1, $unit2InitialCount, 'Unit Testing 2 memiliki 1 temuan');

            $kriteriaPTK = $this->kriteria[0];
            $browser->click("label[for='kriteria_{$kriteriaPTK->id}']")
                    
                    ->waitForTextIn("{$unit1RowSelector} td:nth-child(4) span", '1', 30);

            $unit1FilteredCount = (int)$browser->text("{$unit1RowSelector} td:nth-child(4) span");
            $unit2FilteredCount = (int)$browser->text("{$unit2RowSelector} td:nth-child(4) span");

            $this->assertEquals(1, $unit1FilteredCount, 'Unit Testing 1 memiliki 1 temuan setelah filter Kriteria PTK');
            $this->assertEquals(1, $unit2FilteredCount, 'Unit Testing 2 memiliki 1 temuan setelah filter Kriteria PTK');

            $browser->press('Reset Filter')
                
                ->waitForTextIn("{$unit1RowSelector} td:nth-child(4) span", '3', 30);

            $browser->click("label[for='kriteria_{$this->kriteria[0]->id}']")
                
                ->click("label[for='kriteria_{$this->kriteria[1]->id}']")
                
                ->waitForTextIn("{$unit1RowSelector} td:nth-child(4) span", '2', 30);

            $unit1MultiFilterCount = (int)$browser->text("{$unit1RowSelector} td:nth-child(4) span");
            $unit2MultiFilterCount = (int)$browser->text("{$unit2RowSelector} td:nth-child(4) span");

            $this->assertEquals(2, $unit1MultiFilterCount, 'Unit Testing 1 memiliki 2 temuan setelah multi-filter');
            $this->assertEquals(1, $unit2MultiFilterCount, 'Unit Testing 2 memiliki 1 temuan setelah multi-filter');
        });
    }
}