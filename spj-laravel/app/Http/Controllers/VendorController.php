<?php

namespace App\Http\Controllers;

use App\Models\Vendor;
use Illuminate\Http\Request;

class VendorController extends Controller
{
    public function index()
    {
        $vendors = Vendor::orderBy('nama_vendor')->paginate(20);
        return view('vendor.index', compact('vendors'));
    }

    public function create()
    {
        return view('vendor.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nama_vendor' => 'required|string|max:255',
            'nama_direktur' => 'nullable|string|max:255',
            'jabatan' => 'nullable|string|max:255',
            'npwp' => 'nullable|string|max:255',
            'alamat' => 'nullable|string',
            'bank' => 'nullable|string|max:255',
            'rekening' => 'nullable|string|max:255',
            'ppn' => 'nullable|numeric',
        ]);

        // Coerce PPN to a numeric value acceptable by DB (decimal, not null).
        // If user left it empty, use default 11.00 as defined in migration.
        $data['ppn'] = isset($data['ppn']) && $data['ppn'] !== '' ? (float) $data['ppn'] : 11.00;

        Vendor::create($data);
        return redirect()->route('vendor.index')->with('success', 'Vendor berhasil ditambahkan');
    }

    public function edit(Vendor $vendor)
    {
        return view('vendor.edit', compact('vendor'));
    }

    public function update(Request $request, Vendor $vendor)
    {
        $data = $request->validate([
            'nama_vendor' => 'required|string|max:255',
            'nama_direktur' => 'nullable|string|max:255',
            'jabatan' => 'nullable|string|max:255',
            'npwp' => 'nullable|string|max:255',
            'alamat' => 'nullable|string',
            'bank' => 'nullable|string|max:255',
            'rekening' => 'nullable|string|max:255',
            'ppn' => 'nullable|numeric',
        ]);

        // Ensure PPN is not null when updating (DB column is non-nullable decimal)
        $data['ppn'] = isset($data['ppn']) && $data['ppn'] !== '' ? (float) $data['ppn'] : 11.00;

        $vendor->update($data);
        return redirect()->route('vendor.index')->with('success', 'Vendor berhasil diperbarui');
    }

    public function destroy(Vendor $vendor)
    {
        $vendor->delete();
        return redirect()->route('vendor.index')->with('success', 'Vendor dihapus');
    }
}
