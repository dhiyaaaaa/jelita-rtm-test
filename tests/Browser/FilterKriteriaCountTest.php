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
use Facebook\WebDriver\WebDriverBy;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class FilterKriteriaCountTest extends DuskTestCase
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

        // Create audit schedule
        $this->jadwalAudit = JadwalAudit::factory()->create([
            'jadwal' => 'Audit Contoh',
            'tgl_mulai' => now(),
            'tgl_selesai' => now()->addDays(7)
        ]);

        // Create admin user
        $this->adminUser = User::factory()->create([
            'email' => 'admin@example.com',
            'password' => bcrypt('password')
        ]);

        // Create role and permission
        $this->adminUser->roles()->create(['name' => 'pusjamu']);

        // Create RTM schedule
        $this->rtmJadwal = RtmJadwal::factory()->create([
            'jadwal_audit_id' => $this->jadwalAudit->id,
            'agenda' => 'Agenda Contoh',
            'pimpinan' => 'Pimpinan Contoh',
            'tempat' => 'Ruang Contoh'
        ]);

        // Create specific faculties
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

        // Create criteria
        $this->kriteria = [
            Kriteria::factory()->create(['nama' => 'Kriteria PTK']), // Kriteria khusus
            Kriteria::factory()->create(),
            Kriteria::factory()->create()
        ];

        // Create specific units
        $this->units[] = Unit::factory()->create(['nama' => 'Unit Testing 1']);
        $this->units[] = Unit::factory()->create(['nama' => 'Unit Testing 2']);

        // Create auditor
        $auditor = Auditor::factory()->create();
        $auditee = Auditee::factory()->create();
        $user = User::factory()->create();
        $jabatan = Jabatan::factory()->create();

        // Create answers for faculty 1
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

        // Create answers for unit 1
        // Unit 1 should have 3 forms, each with a different kriteria, one of which is PTK/daftar tilik
        $formsUnit1 = Form::factory()->count(3)->create();
        foreach ($formsUnit1 as $i => $form) {
            JawabanAuditor::factory()->create([
                'form_id' => $form->id,
                'unit_id' => $this->units[0]->id,
                'kriteria_id' => $this->kriteria[$i]->id, // Use $i to assign kriteria 0, 1, 2
                'jadwal_audit_id' => $this->jadwalAudit->id,
                'auditor_id' => $auditor->id,
                'ptk' => ($i == 0), // PTK true only for kriteria[0]
                'daftar_tilik' => ($i == 0) // Daftar tilik true only for kriteria[0]
            ]);
        }
        
        // Create answer for unit 2 - only kriteria[0]
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
                ->visit(route('admin.rtm-rtl.show', ['rtmJadwal' => $this->rtmJadwal->id]))
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
        // 1. Navigate to page
        $browser->loginAs($this->adminUser)
            ->visit(route('admin.rtm-rtl.show', ['rtmJadwal' => $this->rtmJadwal->id]))
            ->waitForText('Rapat Tinjauan Manajemen', 60)
            ->pause(2000);

        // 2. Add Dusk selectors to table rows in your Blade template
        $unit1RowSelector = "@unit-row-{$this->units[0]->id}";
        $unit2RowSelector = "@unit-row-{$this->units[1]->id}";

        $browser->waitFor($unit1RowSelector)
                ->waitFor($unit2RowSelector);

        // 3. Get initial counts
        $unit1InitialCount = (int)$browser->text("{$unit1RowSelector} td:nth-child(4) span");
        $unit2InitialCount = (int)$browser->text("{$unit2RowSelector} td:nth-child(4) span");

        $this->assertEquals(3, $unit1InitialCount, 'Unit Testing 1 should have 3 findings initially');
        $this->assertEquals(1, $unit2InitialCount, 'Unit Testing 2 should have 1 finding initially');

        // 4. Apply PTK criteria filter - click the label instead of the checkbox
        $kriteriaPTK = $this->kriteria[0];
        $browser->click("label[for='kriteria_{$kriteriaPTK->id}']") // Click the label instead
                ->press('Terapkan Filter')
                ->waitForText('Rapat Tinjauan Manajemen', 30)
                ->pause(3000);

        // 5. Get counts after filtering
        $unit1FilteredCount = (int)$browser->text("{$unit1RowSelector} td:nth-child(4) span");
        $unit2FilteredCount = (int)$browser->text("{$unit2RowSelector} td:nth-child(4) span");

        $this->assertEquals(1, $unit1FilteredCount, 'Unit Testing 1 should have 1 PTK finding');
        $this->assertEquals(1, $unit2FilteredCount, 'Unit Testing 2 should have 1 PTK finding');

        // 6. Reset filter
        $browser->clickLink('Reset')
                ->waitForText('Rapat Tinjauan Manajemen', 30)
                ->pause(2000);

        // 7. Apply multiple criteria filter
        $browser->click("label[for='kriteria_{$this->kriteria[0]->id}']")
                ->click("label[for='kriteria_{$this->kriteria[1]->id}']")
                ->press('Terapkan Filter')
                ->waitForText('Rapat Tinjauan Manajemen', 30)
                ->pause(3000);

        // 8. Get counts after multiple filters
        $unit1MultiFilterCount = (int)$browser->text("{$unit1RowSelector} td:nth-child(4) span");
        $unit2MultiFilterCount = (int)$browser->text("{$unit2RowSelector} td:nth-child(4) span");

        $this->assertEquals(2, $unit1MultiFilterCount, 'Unit Testing 1 should have 2 findings with multiple filters');
        $this->assertEquals(1, $unit2MultiFilterCount, 'Unit Testing 2 should have 1 finding with multiple filters');
    });
}
}