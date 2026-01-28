# Spatie Laravel Permission - Implementation Guide

## Overview
This system implements role-based access control (RBAC) using Spatie's Laravel Permission package with **category-based permission grouping**.

## Features Implemented

### 1. **Permission Categories**
Permissions are organized into logical categories for better management:
- `users` - User management permissions
- `roles` - Role management permissions  
- `permissions` - Permission management permissions
- `kegiatan` - Activity management permissions
- `konsumsi` - Consumption management permissions
- `narasumber` - Speaker/presenter management permissions
- `kwitansi` - Receipt management permissions
- `master-data` - Master data management permissions
- `reports` - Report viewing and exporting permissions

### 2. **Default Roles**

#### Super Admin
- Full access to all permissions
- Can manage roles and permissions
- Cannot be deleted

#### Admin
- Can manage users, kegiatan, konsumsi, narasumber, kwitansi, master data
- Can approve and validate
- **Cannot** manage roles and permissions

#### Staff
- Can create and edit kegiatan, konsumsi, narasumber
- Can view kwitansi and master data
- Limited deletion rights

#### Viewer
- Read-only access to all modules
- No create, edit, or delete permissions

## Usage Examples

### In Controllers

```php
// Check if user has specific permission
if (auth()->user()->can('create-kegiatan')) {
    // User can create kegiatan
}

// Check if user has specific role
if (auth()->user()->hasRole('admin')) {
    // User is admin
}

// Check if user has any of the roles
if (auth()->user()->hasAnyRole(['admin', 'super-admin'])) {
    // User is admin or super-admin
}
```

### In Routes (Middleware)

```php
// Require specific permission
Route::get('/kegiatan/create', [KegiatanController::class, 'create'])
    ->middleware('permission:create-kegiatan');

// Require specific role
Route::get('/admin/dashboard', [AdminController::class, 'index'])
    ->middleware('role:admin');

// Require either role or permission
Route::get('/reports', [ReportController::class, 'index'])
    ->middleware('role_or_permission:view-reports');
```

### In Blade Templates

```blade
{{-- Check permission --}}
@can('create-kegiatan')
    <a href="{{ route('kegiatan.create') }}" class="btn btn-primary">
        Create Kegiatan
    </a>
@endcan

{{-- Check role --}}
@role('admin')
    <a href="{{ route('users.index') }}">Manage Users</a>
@endrole

{{-- Check multiple permissions --}}
@canany(['edit-kegiatan', 'delete-kegiatan'])
    <div class="admin-actions">
        <!-- Admin actions here -->
    </div>
@endcanany
```

### Assigning Roles and Permissions

```php
// Assign role to user
$user->assignRole('admin');

// Assign multiple roles
$user->assignRole(['staff', 'viewer']);

// Remove role
$user->removeRole('staff');

// Sync roles (removes old ones, adds new ones)
$user->syncRoles(['admin']);

// Give permission directly to user (bypass role)
$user->givePermissionTo('create-kegiatan');

// Revoke permission
$user->revokePermissionTo('create-kegiatan');

// Check if user has permission (via role or direct)
if ($user->hasPermissionTo('edit-kegiatan')) {
    // User can edit kegiatan
}
```

### Managing Roles

```php
use Spatie\Permission\Models\Role;
use App\Models\Permission;

// Create new role
$role = Role::create(['name' => 'editor']);

// Assign permissions to role
$role->givePermissionTo(['view-kegiatan', 'edit-kegiatan']);

// Get all permissions for a role
$permissions = $role->permissions;

// Sync permissions (replace all)
$role->syncPermissions(['view-kegiatan', 'create-kegiatan', 'edit-kegiatan']);
```

### Creating Permissions

```php
use App\Models\Permission;

// Create permission with category
Permission::create([
    'name' => 'approve-budget',
    'category' => 'finance',
    'guard_name' => 'web',
]);

// Get all permissions in a category
$financePermissions = Permission::where('category', 'finance')->get();

// Group permissions by category
$grouped = Permission::all()->groupBy('category');
```

## Admin Panel Routes

All management routes are protected and require appropriate roles:

### Role Management (Super Admin only)
- `GET /roles` - List all roles
- `GET /roles/create` - Create new role form
- `POST /roles` - Store new role
- `GET /roles/{id}/edit` - Edit role form
- `PUT /roles/{id}` - Update role
- `DELETE /roles/{id}` - Delete role

### Permission Management (Super Admin only)
- `GET /permissions` - List all permissions (grouped by category)
- `GET /permissions/create` - Create new permission form
- `POST /permissions` - Store new permission
- `GET /permissions/{id}/edit` - Edit permission form
- `PUT /permissions/{id}` - Update permission
- `DELETE /permissions/{id}` - Delete permission

## Seeding Data

To populate initial roles and permissions:

```bash
php artisan db:seed --class=RolePermissionSeeder
```

This will create:
- 4 default roles (super-admin, admin, staff, viewer)
- All category-based permissions
- Proper role-permission assignments

## Cache Management

Spatie Permission caches permissions for performance. Clear cache after changes:

```bash
# Clear permission cache
php artisan permission:cache-reset

# Or clear all cache
php artisan cache:clear
php artisan config:clear
```

## Best Practices

1. **Use Categories**: Always assign a category when creating permissions
2. **Naming Convention**: Use kebab-case for permission names (e.g., `view-users`, `create-kegiatan`)
3. **Check Permissions**: Use `@can` directives in views and `can()` method in controllers
4. **Role Hierarchy**: Structure roles from most to least privileged (super-admin > admin > staff > viewer)
5. **Direct Permissions**: Avoid giving direct permissions to users; use roles instead
6. **Testing**: Always test permission changes in development before deploying

## Troubleshooting

### Permission not working after creation
```bash
php artisan permission:cache-reset
```

### User doesn't have expected permissions
```php
// Check user's roles
dd(auth()->user()->roles);

// Check user's permissions
dd(auth()->user()->getAllPermissions());

// Check specific permission
dd(auth()->user()->hasPermissionTo('create-kegiatan'));
```

### Migration issues
```bash
# Rollback and re-run migrations
php artisan migrate:rollback --step=1
php artisan migrate
php artisan db:seed --class=RolePermissionSeeder
```

## Next Steps

1. Update your existing middleware to use Spatie's permission checks
2. Add permission checks to your controllers
3. Update views to show/hide elements based on permissions
4. Assign roles to existing users
5. Test all permission scenarios

## References

- [Spatie Laravel Permission Documentation](https://spatie.be/docs/laravel-permission)
- [Laravel Authorization](https://laravel.com/docs/authorization)
