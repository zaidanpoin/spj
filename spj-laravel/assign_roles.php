<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== ASSIGNING ROLES TO USERS ===\n\n";

// Super Admin
$superAdmin = \App\Models\User::where('email', 'superadmin@spj.go.id')->first();
if ($superAdmin) {
    $superAdmin->assignRole('super-admin');
    echo "✓ Assigned 'super-admin' role to {$superAdmin->name}\n";
}

// Admin
$admin = \App\Models\User::where('email', 'admin@spj.go.id')->first();
if ($admin) {
    $admin->assignRole('admin');
    echo "✓ Assigned 'admin' role to {$admin->name}\n";
}

// Staff
$staff = \App\Models\User::where('email', 'budi@spj.go.id')->first();
if ($staff) {
    $staff->assignRole('staff');
    echo "✓ Assigned 'staff' role to {$staff->name}\n";
}

// Viewer
$viewer = \App\Models\User::where('email', 'siti@spj.go.id')->first();
if ($viewer) {
    $viewer->assignRole('viewer');
    echo "✓ Assigned 'viewer' role to {$viewer->name}\n";
}

echo "\n=== VERIFICATION ===\n";
$users = \App\Models\User::with('roles')->get();
foreach ($users as $user) {
    echo "{$user->name}: " . $user->roles->pluck('name')->implode(', ') . "\n";
}

echo "\n✅ Done! All users have been assigned their roles.\n";
