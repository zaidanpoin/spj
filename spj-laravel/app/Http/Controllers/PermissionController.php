<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Permission;
use App\Helpers\BreadcrumbHelper;

class PermissionController extends Controller
{
    /**
     * Display a listing of permissions
     */
    public function index()
    {
        $permissions = Permission::orderBy('category')->orderBy('name')->paginate(20);
        $groupedPermissions = Permission::all()->groupBy('category');

        $breadcrumbs = BreadcrumbHelper::generate();
        return view('permissions.index', compact('permissions', 'groupedPermissions', 'breadcrumbs'));
    }

    /**
     * Show the form for creating a new permission
     */
    public function create()
    {
        $categories = Permission::distinct()->pluck('category');
        $breadcrumbs = BreadcrumbHelper::generate();
        return view('permissions.create', compact('categories', 'breadcrumbs'));
    }

    /**
     * Store a newly created permission
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:permissions,name',
            'category' => 'required|string|max:255',
        ]);

        Permission::create([
            'name' => $request->name,
            'category' => $request->category,
        ]);

        return redirect()->route('permissions.index')
            ->with('success', 'Permission created successfully.');
    }

    /**
     * Show the form for editing the permission
     */
    public function edit($id)
    {
        $permission = Permission::findOrFail($id);
        $categories = Permission::distinct()->pluck('category');

        $breadcrumbs = BreadcrumbHelper::generate();
        return view('permissions.edit', compact('permission', 'categories', 'breadcrumbs'));
    }

    /**
     * Update the permission
     */
    public function update(Request $request, $id)
    {
        $permission = Permission::findOrFail($id);

        $request->validate([
            'name' => 'required|unique:permissions,name,' . $id,
            'category' => 'required|string|max:255',
        ]);

        $permission->update([
            'name' => $request->name,
            'category' => $request->category,
        ]);

        return redirect()->route('permissions.index')
            ->with('success', 'Permission updated successfully.');
    }

    /**
     * Remove the permission
     */
    public function destroy($id)
    {
        $permission = Permission::findOrFail($id);
        $permission->delete();

        return redirect()->route('permissions.index')
            ->with('success', 'Permission deleted successfully.');
    }
}
