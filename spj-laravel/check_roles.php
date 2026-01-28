<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== ROLES IN DATABASE ===\n";
$roles = \Spatie\Permission\Models\Role::all(['id', 'name']);
foreach ($roles as $role) {
    echo "ID: {$role->id}, Name: '{$role->name}'\n";
}

echo "\n=== USERS AND THEIR ROLES ===\n";
$users = \App\Models\User::with('roles')->get();
foreach ($users as $user) {
    echo "User: {$user->name} (ID: {$user->id})\n";
    echo "  Email: {$user->email}\n";
    echo "  Roles: " . ($user->roles->pluck('name')->implode(', ') ?: 'No roles assigned') . "\n";
    echo "  Has 'super-admin' role? " . ($user->hasRole('super-admin') ? 'YES' : 'NO') . "\n\n";
}
