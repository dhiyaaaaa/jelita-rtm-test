<?php

namespace Database\Seeders;

use App\Models\Menu;
use App\Models\Role;
use App\Models\Submenu;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $menus = [
            'Dashboard' => [],
            'Audit' => [
                'Instrumen',
                'Jadwal Audit',
                'Hasil Audit',
            ],
            'User' => [
                'User',
                'Role',
                'Jabatan',
                'Auditor',
                'Auditan',
            ],
            'Dokumen' => [
                'Peraturan',
                'Standar',
                'Level',
                'Kriteria',
                'Jenis Pertanyaan',
            ],
            'Assessment' => ['Pertanyaan', 'Hasil'],

            'Akademik' => [
                'Jenjang',
                'Fakultas',
                'Prodi',
                'Unit',
            ],
            'Menu' => [],
        ];

        $roles = [
            'pusjamu',
            'gkm',
            'gpm',
            'auditor',
            'pj_universitas',
            'pj_fakultas',
            'pj_prodi',
        ];

        $menuInstances = [];

        foreach ($menus as $menuName => $submenus) {
            $menu = Menu::create([
                'menu' => $menuName,
                'status' => 1,
            ]);

            $menuInstances[$menuName] = $menu;

            foreach ($submenus as $submenuName) {
                Submenu::create([
                    'submenu' => $submenuName,
                    'status' => 1,
                    'menu_id' => $menu->id,
                ]);
            }
        }

        $dashboard = Menu::where('menu', 'Dashboard')->first();
        foreach ($roles as $roleName) {
            $role = Role::findByName($roleName);
            $role->menu()->attach($dashboard->id);
            $dashboard->role()->attach($role->id);
        }

        // Auditee
        $auditee = Menu::create([
            'menu' => 'Auditan',
            'status' => 1,
        ]);
        $roleAuditee = Role::whereIn('name', ['pj_prodi', 'pj_universitas', 'gkm', 'gpm', 'pj_fakultas'])->get();
        foreach ($roleAuditee as $roleA) {
            $roleA->menu()->attach($auditee->id);
            $auditee->role()->attach($roleA->id);
        }

        $submenuAuditeeDokumen = Submenu::create([
            'submenu' => 'Dokumen',
            'status' => 1,
            'menu_id' => $auditee->id
        ]);
        foreach ($roleAuditee as $roleA) {
            $roleA->submenu()->attach($submenuAuditeeDokumen->id);
            $auditee->role()->attach($roleA->id);
        }
        $submenuAuditeeLapangan = Submenu::create([
            'submenu' => 'Lapangan',
            'status' => 1,
            'menu_id' => $auditee->id
        ]);
        foreach ($roleAuditee as $roleA) {
            $roleA->submenu()->attach($submenuAuditeeLapangan->id);
            $auditee->role()->attach($roleA->id);
        }


        // Auditor
        $auditor = Menu::create([
            'menu' => 'Auditor',
            'status' => 1,
        ]);
        $roleAuditor = Role::where('name', 'auditor')->first();
        $roleAuditor->menu()->attach($auditor->id);
        $auditor->role()->attach($roleAuditor->id);

        $menuAssessment = Menu::create([
            'menu' => 'Peer Assessment',
            'status' => 1,
        ]);
        $roleAuditor->menu()->attach($menuAssessment->id);
        $menuAssessment->role()->attach($roleAuditor->id);


        $auditDokumen = Submenu::create([
            'submenu' => 'Dokumen',
            'status' => 1,
            'menu_id' => $auditor->id,
        ]);
        $roleAuditor->submenu()->attach($auditDokumen->id);
        $auditor->role()->attach($roleAuditor->id);
        $auditLapangan = Submenu::create([
            'submenu' => 'Lapangan',
            'status' => 1,
            'menu_id' => $auditor->id,
        ]);
        $roleAuditor->submenu()->attach($auditLapangan->id);
        $auditor->role()->attach($roleAuditor->id);

        $admin = Role::findByName('pusjamu');
        $adminMenus = ['Dashboard', 'Dokumen', 'Menu', 'User', 'Akademik', 'Audit', 'Assessment'];

        foreach ($adminMenus as $menuName) {
            $menu = $menuInstances[$menuName];
            $admin->menu()->attach($menu->id);
            $menu->role()->attach($admin->id);

            foreach ($menu->submenu as $submenu) {
                $admin->submenu()->attach($submenu->id);
                $submenu->role()->attach($admin->id);
            }
        }
    }
}
