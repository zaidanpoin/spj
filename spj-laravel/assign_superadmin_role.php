<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$user = \App\Models\User::where('email', 'superadmin@spj.go.id')->first();
if (!$user) {
    echo "Superadmin user not found\n";
    exit(1);
}

try {
    $user->assignRole('super-admin');
    echo "Assigned role 'super-admin' to user {$user->email}\n";
} catch (Exception $e) {
    echo "Error assigning role: " . $e->getMessage() . "\n";
    exit(1);
}

// Clear permission cache
app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
echo "Permission cache cleared.\n";
