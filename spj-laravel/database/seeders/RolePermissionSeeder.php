<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use App\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Define permission categories and their permissions
        $permissionsByCategory = [
            'users' => [
                'view-users',
                'create-users',
                'edit-users',
                'delete-users',
                'suspend-users',
                'activate-users',
            ],
            'roles' => [
                'view-roles',
                'create-roles',
                'edit-roles',
                'delete-roles',
            ],
            'permissions' => [
                'view-permissions',
                'create-permissions',
                'edit-permissions',
                'delete-permissions',
            ],
            'kegiatan' => [
                'view-kegiatan',
                'create-kegiatan',
                'edit-kegiatan',
                'delete-kegiatan',
                'approve-kegiatan',
            ],
            'konsumsi' => [
                'view-konsumsi',
                'create-konsumsi',
                'edit-konsumsi',
                'delete-konsumsi',
                'validasi-konsumsi',
            ],
            'narasumber' => [
                'view-narasumber',
                'create-narasumber',
                'edit-narasumber',
                'delete-narasumber',
            ],
            'kwitansi' => [
                'view-kwitansi',
                'create-kwitansi',
                'edit-kwitansi',
                'delete-kwitansi',
                'approve-kwitansi',
                'download-kwitansi',
            ],
            'master-data' => [
                'view-master-data',
                'create-master-data',
                'edit-master-data',
                'delete-master-data',
            ],
            'unor' => [
                'view-unor',
                'create-unor',
                'edit-unor',
                'delete-unor',
            ],
            'unit-kerja' => [
                'view-unit-kerja',
                'create-unit-kerja',
                'edit-unit-kerja',
                'delete-unit-kerja',
            ],
            'sbm-konsumsi' => [
                'view-sbm-konsumsi',
                'create-sbm-konsumsi',
                'edit-sbm-konsumsi',
                'delete-sbm-konsumsi',
            ],
            'sbm-honorarium' => [
                'view-sbm-honorarium',
                'create-sbm-honorarium',
                'edit-sbm-honorarium',
                'delete-sbm-honorarium',
            ],
            'waktu-konsumsi' => [
                'view-waktu-konsumsi',
                'create-waktu-konsumsi',
                'edit-waktu-konsumsi',
                'delete-waktu-konsumsi',
            ],
            'mak' => [
                'view-mak',
                'create-mak',
                'edit-mak',
                'delete-mak',
            ],
            'ppk' => [
                'view-ppk',
                'create-ppk',
                'edit-ppk',
                'delete-ppk',
            ],
            'bendahara' => [
                'view-bendahara',
                'create-bendahara',
                'edit-bendahara',
                'delete-bendahara',
            ],
            'barang' => [
                'view-barang',
                'create-barang',
                'edit-barang',
                'delete-barang',
            ],
            'honorarium' => [
                'view-honorarium',
                'create-honorarium',
                'edit-honorarium',
                'delete-honorarium',
            ],
            'reports' => [
                'view-reports',
                'export-reports',
            ],
        ];

        // Create permissions with categories
        foreach ($permissionsByCategory as $category => $permissions) {
            foreach ($permissions as $permission) {
                Permission::firstOrCreate(
                    ['name' => $permission, 'guard_name' => 'web'],
                    ['category' => $category]
                );
            }
        }

        $this->command->info('Permissions created/updated successfully!');

        // Create roles and assign permissions (skip if already exists)

        // Super Admin - has all permissions
        $superAdmin = Role::firstOrCreate(['name' => 'super-admin']);
        $superAdmin->syncPermissions(Permission::all());

        // Admin - has most permissions except role/permission management
        $admin = Role::firstOrCreate(['name' => 'admin']);
        $admin->givePermissionTo([
            // Users
            'view-users',
            'create-users',
            'edit-users',
            'delete-users',
            // Kegiatan
            'view-kegiatan',
            'create-kegiatan',
            'edit-kegiatan',
            'delete-kegiatan',
            'approve-kegiatan',
            // Konsumsi
            'view-konsumsi',
            'create-konsumsi',
            'edit-konsumsi',
            'delete-konsumsi',
            'validasi-konsumsi',
            // Narasumber
            'view-narasumber',
            'create-narasumber',
            'edit-narasumber',
            'delete-narasumber',
            // Kwitansi
            'view-kwitansi',
            'create-kwitansi',
            'edit-kwitansi',
            'delete-kwitansi',
            'approve-kwitansi',
            'download-kwitansi',
            // Master Data
            'view-master-data',
            'create-master-data',
            'edit-master-data',
            'delete-master-data',
            // Reports
            'view-reports',
            'export-reports',
        ]);

        // Staff - can create and view, limited editing
        $staff = Role::firstOrCreate(['name' => 'staff']);
        $staff->syncPermissions([
            // Kegiatan
            'view-kegiatan',
            'create-kegiatan',
            'edit-kegiatan',
            // Konsumsi
            'view-konsumsi',
            'create-konsumsi',
            'edit-konsumsi',
            // Narasumber
            'view-narasumber',
            'create-narasumber',
            'edit-narasumber',
            // Kwitansi
            'view-kwitansi',
            'create-kwitansi',
            // Master Data (view only)
            'view-master-data',
            // Reports
            'view-reports',
        ]);

        // Viewer - read-only access
        $viewer = Role::firstOrCreate(['name' => 'viewer']);
        $viewer->syncPermissions([
            'view-kegiatan',
            'view-konsumsi',
            'view-narasumber',
            'view-kwitansi',
            'view-master-data',
            'view-reports',
        ]);

        $this->command->info('Roles and permissions seeded successfully!');
    }
}
