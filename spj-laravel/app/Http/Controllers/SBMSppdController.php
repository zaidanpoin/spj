<?php

namespace App\Http\Controllers;

use App\Models\SBM;
use Illuminate\Http\Request;

class SBMSppdController extends Controller
{
    public function index(Request $request)
    {
        $query = SBM::query();

        // search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('item', 'like', "%{$search}%")
                    ->orWhere('jenis', 'like', "%{$search}%")
                    ->orWhere('kelas', 'like', "%{$search}%");
            });
        }

        // filters: thang (year), jenis, sub (satuan_sing)
        if ($request->filled('thang')) {
            $query->where('thang', $request->thang);
        }

        if ($request->filled('jenis')) {
            $query->where('jenis', $request->jenis);
        }

        if ($request->filled('sub')) {
            $query->where('satuan_sing', $request->sub);
        }

        // fetch distinct filter options
        $years = SBM::select('thang')->distinct()->whereNotNull('thang')->orderBy('thang', 'desc')->pluck('thang');
        $jenisList = SBM::select('jenis')->distinct()->where('jenis', '!=', '')->pluck('jenis');
        $subList = SBM::select('satuan_sing')->distinct()->where('satuan_sing', '!=', '')->pluck('satuan_sing');

        $sbm = $query->orderBy('id')->paginate(15)->withQueryString();

        return view('master.sbm-sppd.index', compact('sbm', 'years', 'jenisList', 'subList'));
    }

    public function create()
    {
        return view('master.sbm-sppd.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'jenis' => 'nullable|string|max:50',
            'kelas' => 'nullable|string|max:50',
            'item' => 'required|string|max:200',
            'satuan_sing' => 'nullable|string|max:100',
            'satuan_desk' => 'nullable|string|max:200',
            'nilai' => 'nullable|string|max:50',
            'thang' => 'nullable|integer',
        ]);

        SBM::create($validated);

        return redirect()->route('master.sbm-sppd.index')
            ->with('success', 'Data SBM SPPD berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $sbm = SBM::findOrFail($id);
        return view('master.sbm-sppd.edit', compact('sbm'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'jenis' => 'nullable|string|max:50',
            'kelas' => 'nullable|string|max:50',
            'item' => 'required|string|max:200',
            'satuan_sing' => 'nullable|string|max:100',
            'satuan_desk' => 'nullable|string|max:200',
            'nilai' => 'nullable|string|max:50',
            'thang' => 'nullable|integer',
        ]);

        $sbm = SBM::findOrFail($id);
        $sbm->update($validated);

        return redirect()->route('master.sbm-sppd.index')
            ->with('success', 'Data SBM SPPD berhasil diupdate!');
    }

    public function destroy($id)
    {
        $sbm = SBM::findOrFail($id);
        $sbm->delete();

        return redirect()->route('master.sbm-sppd.index')
            ->with('success', 'Data SBM SPPD berhasil dihapus!');
    }
}
