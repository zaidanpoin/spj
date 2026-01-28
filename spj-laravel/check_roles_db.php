<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "=== ROLES TABLE ===\n";
foreach (\Spatie\Permission\Models\Role::all() as $r) {
    echo "{$r->id} | {$r->name} | {$r->guard_name}\n";
}

echo "\n=== MODEL_HAS_ROLES ===\n";
$rows = \DB::table('model_has_roles')->get();
foreach ($rows as $row) {
    echo "role_id={$row->role_id} model_type={$row->model_type} model_id={$row->model_id}\n";
}

echo "\n=== SUPERADMIN USER CHECK ===\n";
$user = \App\Models\User::where('email', 'superadmin@spj.go.id')->first();
if (!$user) {
    echo "Superadmin user not found\n";
    exit;
}

echo "User: {$user->id} | {$user->name} | email: {$user->email}\n";
echo "User->role (legacy column): " . ($user->role ?? 'NULL') . "\n";
echo "Assigned Spatie roles: " . ($user->roles->pluck('name')->implode(', ') ?: 'none') . "\n";
echo "hasRole('super-admin')? " . ($user->hasRole('super-admin') ? 'YES' : 'NO') . "\n";

echo "\n=== PERMISSIONS COUNT ===\n";
echo \Spatie\Permission\Models\Permission::count() . " permissions in DB\n";
