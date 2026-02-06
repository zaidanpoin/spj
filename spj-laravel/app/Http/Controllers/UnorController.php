<?php

namespace App\Http\Controllers;

use App\Models\Unor;
use Illuminate\Http\Request;

class UnorController extends Controller
{
    public function index(Request $request)
    {
        $query = Unor::query();

        // Search functionality
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('kode_unor', 'like', "%{$search}%")
                    ->orWhere('nama_unor', 'like', "%{$search}%");
            });
        }

        $unors = $query->orderBy('kode_unor')->paginate(10);

        return view('master.unor.index', compact('unors'));
    }

    public function create()
    {
        return view('master.unor.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'kode_unor' => 'required|unique:unors,kode_unor|max:50',
            'nama_unor' => 'required|max:255',
            'alamat' => 'nullable|string|max:1000',
        ]);

        Unor::create($validated);

        return redirect()->route('master.unor.index')
            ->with('success', 'Unit Organisasi berhasil ditambahkan');
    }

    public function edit(Unor $unor)
    {
        return view('master.unor.edit', compact('unor'));
    }

    public function update(Request $request, Unor $unor)
    {
        $validated = $request->validate([
            'kode_unor' => 'required|max:50|unique:unors,kode_unor,' . $unor->id,
            'nama_unor' => 'required|max:255',
            'alamat' => 'nullable|string|max:1000',
        ]);

        $unor->update($validated);

        return redirect()->route('master.unor.index')
            ->with('success', 'Unit Organisasi berhasil diperbarui');
    }

    public function destroy(Unor $unor)
    {
        $unor->delete();

        return redirect()->route('master.unor.index')
            ->with('success', 'Unit Organisasi berhasil dihapus');
    }
}
