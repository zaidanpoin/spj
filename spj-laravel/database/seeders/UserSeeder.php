<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            [
                'name' => 'Super Administrator',
                'email' => 'superadmin@spj.go.id',
                'password' => Hash::make('super123'),
                'role' => 'super_admin',
                'status' => 'active',
            ],
            [
                'name' => 'Admin Unit',
                'email' => 'admin@spj.go.id',
                'password' => Hash::make('admin123'),
                'role' => 'admin',
                'status' => 'active',
            ],
            [
                'name' => 'Budi Santoso',
                'email' => 'budi@spj.go.id',
                'password' => Hash::make('user123'),
                'role' => 'user',
                'status' => 'active',
            ],
            [
                'name' => 'Siti Rahma',
                'email' => 'siti@spj.go.id',
                'password' => Hash::make('user123'),
                'role' => 'user',
                'status' => 'active',
            ],
        ];

        foreach ($users as $userData) {
            $user = User::updateOrCreate(
                ['email' => $userData['email']],
                [
                    'name' => $userData['name'],
                    'password' => $userData['password'],
                    'role' => $userData['role'],
                    'status' => $userData['status'],
                ]
            );

            // Map legacy role field to Spatie role names
            $roleMap = [
                'super_admin' => 'super-admin',
                'admin' => 'admin',
                'user' => 'staff',
                'viewer' => 'viewer',
            ];

            $spatieRole = $roleMap[$userData['role']] ?? null;
            if ($spatieRole) {
                // assignRole is idempotent
                try {
                    $user->assignRole($spatieRole);
                } catch (\Exception $e) {
                    // If role doesn't exist yet, skip silently (RolePermissionSeeder should create roles)
                }
            }
        }

        $this->command->info('User seeder completed! ' . count($users) . ' users created/updated.');
    }
}
