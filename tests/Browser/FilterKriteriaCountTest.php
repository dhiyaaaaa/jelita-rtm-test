<?php

namespace Tests\Browser;

use App\Models\Fakultas;
use App\Models\Instrumen;
use App\Models\JadwalAudit;
use App\Models\JawabanAuditor;
use App\Models\Kriteria;
use App\Models\RtmJadwal;
use App\Models\RtmRtl;
use App\Models\Unit;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class FilterKriteriaCountTest extends DuskTestCase
{
    use DatabaseMigrations;

    // Deklarasikan semua properti yang akan digunakan
    protected $adminUser;
    protected $rtmJadwal;
    protected $kriteria1;
    protected $kriteria2;
    protected $kriteria3;
    protected $fakultas1;
    protected $fakultas2;
    protected $unit1;
    protected $unit2;
    protected $rtmRtlFakultas1;
    protected $rtmRtlFakultas2;

    protected function setUp(): void
    {
        parent::setUp();

        $this->adminUser = User::factory()->create([
            'email' => 'admin@example.com',
            'password' => bcrypt('password')
        ]);
        $this->adminUser->roles()->create(['name' => 'pusjamu']);

        $instrumen = Instrumen::create([
            'pernyataan' => 'Pernyataan test',
            'indikator' => 'Indikator test',
            'kode' => 'TEST-001'
        ]);

        $this->kriteria1 = Kriteria::create(['nama' => 'Kriteria X']);
        $this->kriteria2 = Kriteria::create(['nama' => 'Kriteria Y']);
        $this->kriteria3 = Kriteria::create(['nama' => 'Kriteria Z']);

        $instrumen->kriteria()->attach([
            $this->kriteria1->id => ['isi' => 'Isi kriteria X'],
            $this->kriteria2->id => ['isi' => 'Isi kriteria Y'],
            $this->kriteria3->id => ['isi' => 'Isi kriteria Z'],
        ]);

        $this->fakultas1 = Fakultas::create(['nama' => 'Fakultas Teknik']);
        $this->fakultas2 = Fakultas::create(['nama' => 'Fakultas Ekonomi']);
        $this->unit1 = Unit::create(['nama' => 'Unit X']);
        $this->unit2 = Unit::create(['nama' => 'Unit Y']);

        $jadwalAudit = JadwalAudit::create([
            'nama' => 'Jadwal Test',
            'tahun' => date('Y'),
            'semester' => 1
        ]);

        $this->rtmJadwal = RtmJadwal::create([
            'agenda' => 'Agenda Uji',
            'pimpinan' => 'Pimpinan Uji',
            'jadwal_audit_id' => $jadwalAudit->id,
        ]);

        JawabanAuditor::insert([
            [
                'jadwal_audit_id' => $jadwalAudit->id,
                'fakultas_id' => $this->fakultas1->id,
                'kriteria_id' => $this->kriteria1->id,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'jadwal_audit_id' => $jadwalAudit->id,
                'fakultas_id' => $this->fakultas1->id,
                'kriteria_id' => $this->kriteria1->id,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'jadwal_audit_id' => $jadwalAudit->id,
                'fakultas_id' => $this->fakultas1->id,
                'kriteria_id' => $this->kriteria2->id,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'jadwal_audit_id' => $jadwalAudit->id,
                'unit_id' => $this->unit1->id,
                'kriteria_id' => $this->kriteria1->id,
                'created_at' => now(),
                'updated_at' => now()
            ]
        ]);
    }

    public function testFilterBerdasarkanKriteria()
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->adminUser)
                ->visit(route('admin.rtm-rtl.show', $this->rtmJadwal->id))
                ->assertSee('Rapat Tinjauan Manajemen');
                
            // Verifikasi data awal
            $browser->assertSee('Fakultas Teknik')
                ->assertSee('Unit X');
                
            // Verifikasi hitungan awal
            $browser->with('table#rtm tbody tr:nth-child(1)', function($row) {
                $row->assertSee('3'); // 3 temuan Fakultas Teknik
            });
            
            // Test filter kriteria1
            $browser->check('input[name="kriteria[]"][value="'.$this->kriteria1->id.'"]')
                ->press('Terapkan Filter')
                ->waitFor('table#rtm');
                
            $browser->with('table#rtm tbody tr:nth-child(1)', function($row) {
                $row->assertSee('2'); 
            });
        });
    }
    
    public function testTampilanHitungan()
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->adminUser) 
                   ->visit("/admin/rtm-univ/{$this->rtmJadwal->id}")
                   ->assertSee('Rapat Tinjauan Manajemen');
                   
           
            $browser->with('table#rtm tbody tr:nth-child(1) td:nth-child(4)', function($cell) {
                $cell->assertSeeIn('span.badge', '3');
            });
            
            $browser->with('table#rtm tbody tr:nth-child(3) td:nth-child(4)', function($cell) {
                $cell->assertSeeIn('span.badge', '1');
            });
        });
    }
}