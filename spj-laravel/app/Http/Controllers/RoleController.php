<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use App\Models\Permission;
use App\Helpers\BreadcrumbHelper;

class RoleController extends Controller
{
    /**
     * Display a listing of roles
     */
    public function index()
    {
        $roles = Role::with('permissions')->paginate(10);
        $breadcrumbs = BreadcrumbHelper::generate();
        return view('roles.index', compact('roles', 'breadcrumbs'));
    }

    /**
     * Show the form for creating a new role
     */
    public function create()
    {
        $permissions = Permission::all()->groupBy('category');
        $breadcrumbs = BreadcrumbHelper::generate();
        return view('roles.create', compact('permissions', 'breadcrumbs'));
    }

    /**
     * Store a newly created role
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:roles,name',
            'permissions' => 'array',
        ]);

        $role = Role::create(['name' => $request->name]);

        if ($request->has('permissions')) {
            // Convert permission IDs to Permission models
            $permissions = Permission::whereIn('id', $request->permissions)->get();
            $role->syncPermissions($permissions);
        }

        return redirect()->route('roles.index')
            ->with('success', 'Role created successfully.');
    }

    /**
     * Show the form for editing the role
     */
    public function edit($id)
    {
        $role = Role::findOrFail($id);
        $permissions = Permission::all()->groupBy('category');
        $rolePermissions = $role->permissions->pluck('id')->toArray();

        $breadcrumbs = BreadcrumbHelper::generate();
        return view('roles.edit', compact('role', 'permissions', 'rolePermissions', 'breadcrumbs'));
    }

    /**
     * Update the role
     */
    public function update(Request $request, $id)
    {
        $role = Role::findOrFail($id);

        $request->validate([
            'name' => 'required|unique:roles,name,' . $id,
            'permissions' => 'array',
        ]);

        $role->update(['name' => $request->name]);

        // Convert permission IDs to Permission models
        if ($request->has('permissions')) {
            $permissions = Permission::whereIn('id', $request->permissions)->get();
            $role->syncPermissions($permissions);
        } else {
            $role->syncPermissions([]);
        }

        return redirect()->route('roles.index')
            ->with('success', 'Role updated successfully.');
    }

    /**
     * Remove the role
     */
    public function destroy($id)
    {
        $role = Role::findOrFail($id);

        // Prevent deletion of super-admin role
        if ($role->name === 'super-admin') {
            return redirect()->route('roles.index')
                ->with('error', 'Cannot delete super-admin role.');
        }

        $role->delete();

        return redirect()->route('roles.index')
            ->with('success', 'Role deleted successfully.');
    }
}
