<?php

namespace Tests\Browser;

use App\Models\Fakultas;
use App\Models\RtmJadwal;
use App\Models\Unit;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
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
        Unit::factory()->create(['nama' => 'Unit IT']);

        $this->browse(function (Browser $browser) use ($rtmJadwal) {
            $browser->loginAs($this->adminUser)
                ->visit(route('admin.rtm-rtl-univ.show-livewire', $rtmJadwal->id))
                ->waitForText('Rapat Tinjauan Manajemen', 60)
                ->pause(2000); // Beri waktu ekstra untuk halaman dimuat sepenuhnya

            // Test search for 'Teknik'
            $browser->type('@search-input', 'Teknik')
                ->pause(1500); // Tingkatkan pause lagi menjadi 1.5 detik. Sangat penting untuk memberi waktu Livewire dan browser.

            // Assertions for 'Teknik' search
            $browser->assertSee('Fakultas Teknik')
                    ->assertDontSee('Fakultas Ekonomi')
                    ->assertDontSee('Unit IT');

            // // Test search for 'Tidak Ada'
            // $browser->type('@search-input', 'Tidak Ada')
            //         ->pause(1500) // Tingkatkan pause
            //         ->assertSee('Tidak ada data yang ditemukan'); // Ini asumsikan bahwa ketika tidak ada hasil, pesan ini muncul

            // Test clearing search
            $browser->type('@search-input', '')
                    ->pause(1500) // Tingkatkan pause
                    ->assertSee('Fakultas Teknik')
                    ->assertSee('Fakultas Ekonomi')
                    ->assertSee('Unit IT'); // Pastikan semua kembali terlihat setelah search di-clear
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
                ->assertSee('Showing 1 to 10 of 30 results')

                // ke halaman 2
                ->click('[aria-label="Next &raquo;"]')
                ->pause(1000)
                ->assertSee('Showing 11 to 20 of 30 results') 
                ->screenshot('pagination-page-2')

                //kembali ke halaman 1
                ->click('a[aria-label="Go to page 1"]')
                ->pause(1000)
                ->assertSee('Showing 1 to 10 of 30 results');
        });
    }
}