<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Permissions
        // $permissionsAdmin = [
        //     'Audit' => [
        //         'Instrumen',
        //         'Jadwal Audit',
        //         'Hasil Audit',
        //     ],
        //     'User' => [
        //         'User',
        //         'Role',
        //         'Jabatan',
        //         'Auditor',
        //         'Auditee',
        //     ],
        //     'Dokumen' => [
        //         'Peraturan',
        //         'Standar',
        //         'Level',
        //         'Kriteria',
        //         'Jenis Pertanyaan',
        //     ],
        //     'Assessment' => ['Pertanyaan', 'Hasil'],

        //     'Akademik' => [
        //         'Jenjang',
        //         'Fakultas',
        //         'Prodi',
        //         'Unit',
        //     ],
        //     'Menu' => [],
        // ];

        // $permissionsAuditee = [
        //     'Auditee' => [
        //         'Dokumen',
        //         'Lapangan',
        //     ]
        // ];

        // $permissionsAuditor = [
        //     'Auditor' => [
        //         'Dokumen',
        //         'Lapangan',
        //     ],
        //     'Peer Assessment' => [],
        // ];

        // // Create permissions
        // $dashboard = Permission::firstOrCreate([
        //     'name' => 'dashboard',
        //     'group' => 'dashboard',
        // ]);

        // function createPermissions($permissions)
        // {
        //     foreach ($permissions as $group => $groupPermissions) {
        //         $permissionGroup = strtolower(str_replace(' ', '-', $group));

        //         if (empty($groupPermissions)) {
        //             Permission::firstOrCreate([
        //                 'name' => $permissionGroup,
        //                 'group' => $permissionGroup,
        //             ]);
        //         } else {
        //             foreach ($groupPermissions as $permission) {
        //                 $implode = strtolower(str_replace(' ', '-', $permission));
        //                 $permissionName = strtolower(implode('-', [$group, $implode]));
        //                 Permission::firstOrCreate([
        //                     'name' => $permissionName,
        //                     'group' => $permissionGroup,
        //                 ]);
        //             }
        //         }
        //     }
        // }

        // createPermissions($permissionsAdmin);
        // createPermissions($permissionsAuditee);
        // createPermissions($permissionsAuditor);

        // $roles = ['pusjamu', 'gkm', 'gpm', 'auditor', 'pj_universitas', 'pj_fakultas', 'pj_prodi'];

        // $groups = Permission::all()->groupBy('group');

        // foreach ($roles as $role) {
        //     $newRole = Role::create(['name' => $role]);
        //     $newRole->givePermissionTo($dashboard->name);

        //     if ($role === 'pusjamu') {
        //         $allPermissions = Permission::all();
        //         foreach ($allPermissions as $permission) {
        //             $newRole->givePermissionTo($permission->name);
        //         }
        //     } elseif ($role === 'auditor') {
        //         $groupsAuditor = $groups->filter(function ($permissions, $group) {
        //             return in_array($group, ['auditor', 'peer-assessment']);
        //         });
        //         foreach ($groupsAuditor as $permissions) {
        //             foreach ($permissions as $permission) {
        //                 $newRole->givePermissionTo($permission->name);
        //             }
        //         }
        //     } elseif (in_array($role, ['pj_universitas', 'pj_fakultas', 'pj_prodi', 'gkm', 'gpm'])) {
        //         $groupsAuditee = $groups->filter(function ($permissions, $group) {
        //             return in_array($group, ['auditee']);
        //         });
        //         foreach ($groupsAuditee as $permissions) {
        //             foreach ($permissions as $permission) {
        //                 $newRole->givePermissionTo($permission->name);
        //             }
        //         }
        //     }
        // }

        $roles = ['pusjamu', 'gkm', 'gpm', 'auditor', 'pj_universitas', 'pj_fakultas', 'pj_prodi'];
        
        foreach ($roles as $role) {
            Role::create(['name' => $role]);
        }

        $users = [
            [
                'name' => 'Admin',
                'email' => 'admin@example.com',
                'role' => 'pusjamu',
            ],
        ];

        foreach ($users as $userData) {
            $user = User::create([
                'name' => $userData['name'],
                'email' => $userData['email'],
                'password' => Hash::make('123123'),
            ]);
            $user->assignRole($userData['role']);
        }

        // Auditor
        for ($i = 1; $i <= 20; $i++) {
            $user = User::create([
                'name' => 'Auditor ' . $i,
                'email' => 'auditor' . $i . '@example.com',
                'password' => Hash::make('123123'),
            ]);
            $user->assignRole('auditor');
        }
    }
}
