<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UnitKerja;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * Display a listing of users
     */
    public function index(Request $request)
    {
        $query = User::query();

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Filter by role
        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Admin can only see users (future: from their unit)
        // Super Admin can see all
        if (auth()->user()->role === 'admin') {
            // Future: filter by unit
        }

        $users = $query->with('unitKerja.unor')->latest()->paginate(15);

        // Get role counts for stats cards
        $totalUsers = User::count();
        $superAdminCount = User::where('role', 'super_admin')->count();
        $adminCount = User::where('role', 'admin')->count();
        $userCount = User::where('role', 'user')->count();
        $activeCount = User::where('status', 'active')->count();

        return view('users.index', compact('users', 'totalUsers', 'superAdminCount', 'adminCount', 'userCount', 'activeCount'));
    }

    /**
     * Show the form for creating a new user
     */
    public function create()
    {
        $unitKerjas = UnitKerja::with('unor')->orderBy('nama_unit')->get();
        return view('users.create', compact('unitKerjas'));
    }

    /**
     * Store a newly created user
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8|confirmed',
            'role' => ['required', Rule::in($this->getAllowedRoles())],
            'status' => 'required|in:active,suspended',
            'id_unker' => 'nullable|exists:unit_kerjas,id',
        ]);

        $validated['password'] = Hash::make($validated['password']);

        User::create($validated);

        return redirect()->route('users.index')
            ->with('success', 'User berhasil ditambahkan!');
    }

    /**
     * Show the form for editing the specified user
     */
    public function edit($id)
    {
        $user = User::findOrFail($id);
        $unitKerjas = UnitKerja::with('unor')->orderBy('nama_unit')->get();
        return view('users.edit', compact('user', 'unitKerjas'));
    }

    /**
     * Update the specified user
     */
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('users')->ignore($id)],
            'role' => ['required', Rule::in($this->getAllowedRoles())],
            'status' => 'required|in:active,suspended',
            'id_unker' => 'nullable|exists:unit_kerjas,id',
        ]);

        // Only update password if provided
        if ($request->filled('password')) {
            $request->validate([
                'password' => 'min:8|confirmed',
            ]);
            $validated['password'] = Hash::make($request->password);
        }

        $user->update($validated);

        return redirect()->route('users.index')
            ->with('success', 'User berhasil diupdate!');
    }

    /**
     * Remove the specified user
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);

        // Prevent deleting yourself
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Tidak dapat menghapus akun sendiri!');
        }

        $user->delete();

        return redirect()->route('users.index')
            ->with('success', 'User berhasil dihapus!');
    }

    /**
     * Suspend user (Super Admin only)
     */
    public function suspend($id)
    {
        $user = User::findOrFail($id);

        if ($user->id === auth()->id()) {
            return back()->with('error', 'Tidak dapat suspend akun sendiri!');
        }

        $user->update(['status' => 'suspended']);

        return back()->with('success', 'User berhasil di-suspend!');
    }

    /**
     * Activate user (Super Admin only)
     */
    public function activate($id)
    {
        $user = User::findOrFail($id);

        $user->update(['status' => 'active']);

        return back()->with('success', 'User berhasil diaktifkan!');
    }

    /**
     * Reset user password (Super Admin only)
     */
    public function resetPassword($id)
    {
        $user = User::findOrFail($id);

        // Generate random password
        $newPassword = 'Reset' . rand(1000, 9999);
        $user->update(['password' => Hash::make($newPassword)]);

        return back()->with('success', "Password berhasil direset! Password baru: {$newPassword}");
    }

    /**
     * Get allowed roles based on current user
     */
    private function getAllowedRoles(): array
    {
        if (auth()->user()->role === 'super_admin') {
            return ['user', 'admin', 'super_admin'];
        }

        // Admin can only create regular users
        return ['user'];
    }
}
