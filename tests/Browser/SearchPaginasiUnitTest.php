<?php

namespace Tests\Browser;

use App\Models\Fakultas;
use App\Models\RtmJadwal;
use App\Models\Unit;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class SearchPaginasiUnitTest extends DuskTestCase
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
        $fakultas1 = Fakultas::factory()->create(['nama' => 'Fakultas Teknik']);
        $fakultas2 = Fakultas::factory()->create(['nama' => 'Fakultas Ekonomi']);
        $unit1 = Unit::factory()->create(['nama' => 'Unit Lp3m']);
        $unit2 = Unit::factory()->create(['nama' => 'Unit Lppm']);

        $this->browse(function (Browser $browser) use ($rtmJadwal) {
            $browser->loginAs($this->adminUser)
                ->visit(route('admin.rtm-rtl.show', $rtmJadwal->id))
                ->waitForText('Rapat Tinjauan Manajemen', 60)
                ->pause(2000)

                ->type('@search-input', 'Teknik')
                ->click('@search-button')
                ->pause(1000)
                ->assertSee('Fakultas Teknik')
                ->assertDontSee('Fakultas Ekonomi')
                ->assertDontSee('Unit IT')

                ->type('@search-input', 'Tidak Ada')
                ->click('@search-button')
                ->pause(1000)
                ->assertSee('Tidak ada data yang ditemukan')

                ->type('@search-input', '')
                ->click('@search-button')
                ->pause(1000)
                ->assertSee('Fakultas Teknik')
                ->assertSee('Fakultas Ekonomi');
        });

    }

    public function testPaginasiUnit()
{
    $rtmJadwal = RtmJadwal::factory()->create();
    $fakultas = Fakultas::factory()->count(15)->create();
    $units = Unit::factory()->count(15)->create();

    $this->browse(function (Browser $browser) use ($rtmJadwal) {
        $browser->loginAs($this->adminUser)
            ->visit(route('admin.rtm-rtl.show', $rtmJadwal->id))
            ->waitForText('Rapat Tinjauan Manajemen', 60)
            ->pause(2000)

            // First verify pagination exists
            ->assertPresent('[dusk="pagination"]')
            ->pause(1000)
            ->screenshot('pagination-page-1')
            ->assertSee('Menampilkan 1 - 10 dari')
            
            // Navigate to page 2 using the next button
            ->click('@pagination-next')
            ->pause(1000)
            ->assertSee('Menampilkan 11 - 20 dari') // Adjusted for 15 items
            ->screenshot('pagination-page-2')
            
            // Go back to page 1 using page link
            ->click('[dusk="pagination-page-1"]')
            ->pause(1000)
            ->assertSee('Menampilkan 1 - 10 dari');
    });        
}
}
