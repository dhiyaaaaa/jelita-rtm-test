<?php

namespace Tests\Browser;

use App\Models\Fakultas;
use App\Models\RtmJadwal;
use App\Models\Unit;
use App\Models\User;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class SearchPaginasiUnitLivewireTest extends DuskTestCase
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

    public function testPencarianUnit()
    {
        $rtmJadwal = RtmJadwal::factory()->create();
        Fakultas::factory()->create(['nama' => 'Fakultas Teknik']);
        Fakultas::factory()->create(['nama' => 'Fakultas Ekonomi']);
        Unit::factory()->create(['nama' => 'Unit Lp3m']);

        $this->browse(function (Browser $browser) use ($rtmJadwal) {
            $browser->loginAs($this->adminUser)
                ->visit(route('admin.rtm-rtl-univ.show-livewire', $rtmJadwal->id))
                ->waitForText('Rapat Tinjauan Manajemen', 60);

            // Pastikan semua data muncul awal
            $browser->assertSee('Fakultas Teknik')
                ->assertSee('Fakultas Ekonomi');

            // Test search for 'Teknik'
            $browser->type('@search-input', 'Teknik')
                ->keys('@search-input', '{enter}')
                ->waitUntilMissingText('Fakultas Ekonomi', 15)
                ->assertSee('Fakultas Teknik')
                ->assertDontSee('Fakultas Ekonomi')
                ->assertDontSee('Unit Lp3m');
            
            $browser->type('@search-input', 'Tidak Ada')
                ->keys('@search-input', '{enter}')
                ->waitForText('Tidak ada data yang ditemukan', 10)
                ->assertSee('Tidak ada data yang ditemukan');

            // Clear search
            $browser->type('@search-input', ' ')
                ->keys('@search-input', '{backspace}')
                ->pause(1000)
                ->waitForText('Fakultas Ekonomi', 10)
                ->assertSee('Fakultas Teknik');
        });
    }

    public function testPaginasiUnit()
    {
        $rtmJadwal = RtmJadwal::factory()->create();
        $fakultas = Fakultas::factory()->count(15)->create();
        $units = Unit::factory()->count(15)->create();

        $this->browse(function (Browser $browser) use ($rtmJadwal) {
            $browser->loginAs($this->adminUser)
                ->visit(route('admin.rtm-rtl-univ.show-livewire', $rtmJadwal->id))
                ->waitForText('Rapat Tinjauan Manajemen', 60)
                ->pause(2000)

                // halaman 1
                ->assertPresent('[dusk="pagination"]')
                ->pause(1000)
                ->screenshot('pagination-page-1')
                ->assertSee('Menampilkan 1 - 10 dari')
                
                // ke halaman 2
                ->click('@pagination-next')
                ->pause(1000)
                ->assertSee('Menampilkan 11 - 20 dari')
                ->screenshot('pagination-page-2')
                
                //kembali ke halaman 1
                ->click('[dusk="pagination-page-1"]')
                ->pause(1000)
                ->assertSee('Menampilkan 1 - 10 dari');
        });        
    }
}