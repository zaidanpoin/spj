<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\MAK;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class MAKController extends Controller
{
    public function index(Request $request)
    {
        $query = MAK::query();

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('kode', 'like', "%{$search}%")
                    ->orWhere('nama', 'like', "%{$search}%")
                    ->orWhere('satker', 'like', "%{$search}%");
            });
        }

        // Filter by tahun
        if ($request->filled('tahun')) {
            $query->where('tahun', $request->tahun);
        }

        $makData = $query->latest()->paginate(15);
        $tahunList = MAK::selectRaw('DISTINCT tahun')->orderBy('tahun', 'desc')->pluck('tahun');

        return view('master.mak.index', compact('makData', 'tahunList'));
    }

    public function create()
    {
        return view('master.mak.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'tahun' => 'required|integer|min:2020|max:2100',
            'nama' => 'required|string|max:255',
            'kode' => 'required|string|max:255|unique:mak,kode',
            'satker' => 'required|string|max:255',
            'akun' => 'required|string|max:255',
            'paket' => 'required|string|max:255',
        ]);

        MAK::create($validated);

        return redirect()->route('master.mak.index')
            ->with('success', 'Data MAK berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $mak = MAK::findOrFail($id);
        return view('master.mak.edit', compact('mak'));
    }

    public function update(Request $request, $id)
    {
        $mak = MAK::findOrFail($id);

        $validated = $request->validate([
            'tahun' => 'required|integer|min:2020|max:2100',
            'nama' => 'required|string|max:255',
            'kode' => 'required|string|max:255|unique:mak,kode,' . $id,
            'satker' => 'required|string|max:255',
            'akun' => 'required|string|max:255',
            'paket' => 'required|string|max:255',
        ]);

        $mak->update($validated);

        return redirect()->route('master.mak.index')
            ->with('success', 'Data MAK berhasil diupdate!');
    }

    public function destroy($id)
    {
        $mak = MAK::findOrFail($id);
        $mak->delete();

        return redirect()->route('master.mak.index')
            ->with('success', 'Data MAK berhasil dihapus!');
    }

    /**
     * Sync MAK data from IEMON API
     */
    public function sync()
    {
        try {
            $url = env('IEMON_PPK_URL');
            $thang = env('IEMON_PPK_THANG', '2026');
            $sat = env('IEMON_PPK_SAT', '12694431');

            $fullUrl = "{$url}?thang={$thang}&sat={$sat}";

            Log::info('MAK Sync: Fetching from IEMON API', ['url' => $fullUrl]);

            $response = Http::timeout(30)->get($fullUrl);

            if (!$response->successful()) {
                Log::error('MAK Sync: API request failed', ['status' => $response->status()]);
                return redirect()->route('master.mak.index')
                    ->with('error', 'Gagal mengambil data dari API IEMON. Status: ' . $response->status());
            }

            $data = $response->json();

            if (empty($data)) {
                return redirect()->route('master.mak.index')
                    ->with('error', 'Tidak ada data yang diterima dari API IEMON');
            }

            $syncCount = 0;
            $updateCount = 0;
            $processedKodes = [];

            foreach ($data as $item) {
                // Skip if no kode
                if (empty($item['kode'])) {
                    continue;
                }

                $kode = $item['kode'];

                // Skip if already processed (deduplicate)
                if (in_array($kode, $processedKodes)) {
                    continue;
                }
                $processedKodes[] = $kode;

                // Combine nama from nmpaket and nmakun
                $nama = ($item['nmpaket'] ?? '-') . ' - ' . ($item['nmakun'] ?? '-');

                $makData = [
                    'tahun' => (int) $thang,
                    'kode' => $kode,
                    'nama' => $nama,
                    'satker' => $item['nmsatker'] ?? '-',
                    'akun' => $item['kdakun'] ?? '-',
                    'paket' => $item['nmpaket'] ?? '-',
                    'nip_ppk' => $item['nipppk'] ?? null,
                ];

                // Update or create
                $existing = MAK::where('kode', $makData['kode'])->first();

                if ($existing) {
                    $existing->update($makData);
                    $updateCount++;
                } else {
                    MAK::create($makData);
                    $syncCount++;
                }
            }

            Log::info('MAK Sync: Completed', ['new' => $syncCount, 'updated' => $updateCount]);

            return redirect()->route('master.mak.index')
                ->with('success', "Sync berhasil! {$syncCount} data baru, {$updateCount} data diupdate.");

        } catch (\Exception $e) {
            Log::error('MAK Sync: Exception', ['message' => $e->getMessage()]);
            return redirect()->route('master.mak.index')
                ->with('error', 'Error saat sync: ' . $e->getMessage());
        }
    }
}
