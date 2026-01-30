<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\PPK;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PPKController extends Controller
{
    public function index(Request $request)
    {
        $query = PPK::query();

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                    ->orWhere('nip', 'like', "%{$search}%")
                    ->orWhere('satker', 'like', "%{$search}%")
                    ->orWhere('kdppk', 'like', "%{$search}%");
            });
        }

        $ppkData = $query->latest()->paginate(15);

        return view('master.ppk.index', compact('ppkData'));
    }

    public function create()
    {
        return view('master.ppk.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'nip' => 'required|string|max:20|unique:ppk,nip',
            'satker' => 'required|string|max:255',
            'kdppk' => 'required|string|max:10',
        ]);

        PPK::create($validated);

        return redirect()->route('master.ppk.index')
            ->with('success', 'Data PPK berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $ppk = PPK::findOrFail($id);
        return view('master.ppk.edit', compact('ppk'));
    }

    public function update(Request $request, $id)
    {
        $ppk = PPK::findOrFail($id);

        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'nip' => 'required|string|max:20|unique:ppk,nip,' . $id,
            'satker' => 'required|string|max:255',
            'kdppk' => 'required|string|max:10',
        ]);

        $ppk->update($validated);

        return redirect()->route('master.ppk.index')
            ->with('success', 'Data PPK berhasil diupdate!');
    }

    public function destroy($id)
    {
        $ppk = PPK::findOrFail($id);
        $ppk->delete();

        return redirect()->route('master.ppk.index')
            ->with('success', 'Data PPK berhasil dihapus!');
    }

    /**
     * Sync PPK data from IEMON API
     */
    public function sync()
    {
        try {
            $url = env('IEMON_PPK_URL');
            $thang = env('IEMON_PPK_THANG', '2026');
            $sat = env('IEMON_PPK_SAT', '12694431');

            $fullUrl = "{$url}?thang={$thang}&sat={$sat}";

            Log::info('PPK Sync: Fetching from IEMON API', ['url' => $fullUrl]);

            $response = Http::timeout(30)->get($fullUrl);

            if (!$response->successful()) {
                Log::error('PPK Sync: API request failed', ['status' => $response->status()]);
                return redirect()->route('master.ppk.index')
                    ->with('error', 'Gagal mengambil data dari API IEMON. Status: ' . $response->status());
            }

            $data = $response->json();

            if (empty($data)) {
                return redirect()->route('master.ppk.index')
                    ->with('error', 'Tidak ada data yang diterima dari API IEMON');
            }

            $syncCount = 0;
            $updateCount = 0;
            $processedNips = [];

            foreach ($data as $item) {
                // Skip if no NIP PPK
                if (empty($item['nipppk'])) {
                    continue;
                }

                $nip = $item['nipppk'];

                // Skip if already processed (deduplicate)
                if (in_array($nip, $processedNips)) {
                    continue;
                }
                $processedNips[] = $nip;

                $ppkData = [
                    'nama' => $item['nmppk'] ?? '-',
                    'nip' => $nip,
                    'satker' => $item['nmsatker'] ?? '-',
                    'kdppk' => $item['kdppk'] ?? '-',
                ];

                // Update or create
                $existing = PPK::where('nip', $ppkData['nip'])->first();

                if ($existing) {
                    $existing->update($ppkData);
                    $updateCount++;
                } else {
                    PPK::create($ppkData);
                    $syncCount++;
                }
            }

            Log::info('PPK Sync: Completed', ['new' => $syncCount, 'updated' => $updateCount]);

            return redirect()->route('master.ppk.index')
                ->with('success', "Sync berhasil! {$syncCount} data baru, {$updateCount} data diupdate.");

        } catch (\Exception $e) {
            Log::error('PPK Sync: Exception', ['message' => $e->getMessage()]);
            return redirect()->route('master.ppk.index')
                ->with('error', 'Error saat sync: ' . $e->getMessage());
        }
    }
}
