<?php

namespace App\Http\Controllers;

use App\Models\Kegiatan;
use App\Models\Konsumsi;
use App\Models\SatuanBiayaKonsumsiProvinsi;
use Illuminate\Http\Request;

class KonsumsiController extends Controller
{
    /**
     * Show the form for creating konsumsi.
     */
    public function create($kegiatan_id)
    {
        $kegiatan = Kegiatan::with(['unor', 'provinsi'])->findOrFail($kegiatan_id);
        $waktuKonsumsi = \App\Models\WaktuKonsumsi::all();

        // Get SBM tarif from kegiatan's provinsi
        $tarifSBM = null;
        if ($kegiatan->provinsi) {
            $tarifSBM = [
                'provinsi' => $kegiatan->provinsi->nama_provinsi,
                'makan' => $kegiatan->provinsi->harga_max_makan,
                'snack' => $kegiatan->provinsi->harga_max_snack,
            ];
        }

        // Load draft data if exists
        $draftData = [
            'snack' => Konsumsi::where('kegiatan_id', $kegiatan_id)
                ->where('kategori', 'snack')
                ->where('status', 'draft')
                ->get(),
            'makanan' => Konsumsi::where('kegiatan_id', $kegiatan_id)
                ->where('kategori', 'makanan')
                ->where('status', 'draft')
                ->get(),
            'barang' => Konsumsi::where('kegiatan_id', $kegiatan_id)
                ->where('kategori', 'barang')
                ->where('status', 'draft')
                ->get(),
        ];

        return view('konsumsi.create', compact('kegiatan', 'waktuKonsumsi', 'tarifSBM', 'draftData'));
    }

    /**
     * Store a newly created konsumsi in storage.
     */
    public function store(Request $request)
    {
        // DEBUG: Log incoming request
        \Log::info('Konsumsi Store Request:', $request->all());

        // Determine status (draft or final)
        $status = $request->input('save_as_draft') ? 'draft' : 'final';

        // Delete existing draft data
        // - If saving as draft: replace old draft with new one
        // - If saving as final: remove draft since it's being finalized
        Konsumsi::where('kegiatan_id', $request->kegiatan_id)
            ->where('status', 'draft')
            ->delete();

        $validated = $request->validate([
            'kegiatan_id' => 'required|exists:kegiatans,id',
            'snack' => 'nullable|array',
            'snack.*.nama' => 'nullable|string|max:255',
            'snack.*.no_kwitansi' => 'nullable|string|max:255',
            'snack.*.waktu_konsumsi_id' => 'nullable|exists:waktu_konsumsi,id',
            'snack.*.jumlah' => 'nullable|integer|min:1',
            'snack.*.harga' => 'nullable|integer|min:0',
            'makanan' => 'nullable|array',
            'makanan.*.nama' => 'nullable|string|max:255',
            'makanan.*.no_kwitansi' => 'nullable|string|max:255',
            'makanan.*.waktu_konsumsi_id' => 'nullable|exists:waktu_konsumsi,id',
            'makanan.*.jumlah' => 'nullable|integer|min:1',
            'makanan.*.harga' => 'nullable|integer|min:0',
            'barang' => 'nullable|array',
            'barang.*.nama' => 'nullable|string|max:255',
            'barang.*.no_kwitansi' => 'nullable|string|max:255',
            'barang.*.jumlah' => 'nullable|integer|min:1',
            'barang.*.harga' => 'nullable|integer|min:0',
        ]);


        $totalSaved = 0;

        // Save Snack items
        if ($request->has('snack') && is_array($request->snack)) {
            foreach ($request->snack as $index => $item) {
                \Log::info("Processing snack item {$index}:", $item);
                if (!empty($item['nama']) && trim($item['nama']) !== '') {
                    try {
                        Konsumsi::create([
                            'kegiatan_id' => $request->kegiatan_id,
                            'kategori' => 'snack',
                            'status' => $status,
                            'nama_konsumsi' => $item['nama'],
                            'no_kwitansi' => $item['no_kwitansi'] ?? null,
                            'waktu_konsumsi_id' => $item['waktu_konsumsi_id'] ?? null,
                            'jumlah' => $item['jumlah'] ?? 1,
                            'harga' => $item['harga'] ?? 0,
                            'tanggal_pembelian' => now(),
                        ]);
                        $totalSaved++;
                        \Log::info("Snack saved: {$item['nama']}");
                    } catch (\Exception $e) {
                        \Log::error("Error saving snack: " . $e->getMessage());
                    }
                }
            }
        }

        // Save Makanan items
        if ($request->has('makanan') && is_array($request->makanan)) {
            foreach ($request->makanan as $index => $item) {
                \Log::info("Processing makanan item {$index}:", $item);
                if (!empty($item['nama']) && trim($item['nama']) !== '') {
                    try {
                        Konsumsi::create([
                            'kegiatan_id' => $request->kegiatan_id,
                            'kategori' => 'makanan',
                            'status' => $status,
                            'nama_konsumsi' => $item['nama'],
                            'no_kwitansi' => $item['no_kwitansi'] ?? null,
                            'waktu_konsumsi_id' => $item['waktu_konsumsi_id'] ?? null,
                            'jumlah' => $item['jumlah'] ?? 1,
                            'harga' => $item['harga'] ?? 0,
                            'tanggal_pembelian' => now(),
                        ]);
                        $totalSaved++;
                        \Log::info("Makanan saved: {$item['nama']}");
                    } catch (\Exception $e) {
                        \Log::error("Error saving makanan: " . $e->getMessage());
                    }
                }
            }
        }

        // Save Barang items
        if ($request->has('barang') && is_array($request->barang)) {
            foreach ($request->barang as $index => $item) {
                \Log::info("Processing barang item {$index}:", $item);
                if (!empty($item['nama']) && trim($item['nama']) !== '') {
                    try {
                        Konsumsi::create([
                            'kegiatan_id' => $request->kegiatan_id,
                            'kategori' => 'barang',
                            'status' => $status,
                            'nama_konsumsi' => $item['nama'],
                            'no_kwitansi' => $item['no_kwitansi'] ?? null,
                            'waktu_konsumsi_id' => null, // Barang tidak punya waktu konsumsi
                            'jumlah' => $item['jumlah'] ?? 1,
                            'harga' => $item['harga'] ?? 0,
                            'tanggal_pembelian' => now(),
                        ]);
                        $totalSaved++;
                        \Log::info("Barang saved: {$item['nama']}");
                    } catch (\Exception $e) {
                        \Log::error("Error saving barang: " . $e->getMessage());
                    }
                }
            }
        }

        \Log::info("Total saved: {$totalSaved}");

        if ($totalSaved == 0) {
            return back()->with('error', 'Tidak ada item yang disimpan. Pastikan nama item sudah diisi!');
        }

        $statusMessage = $status === 'draft' ? 'draft' : 'final dan siap divalidasi';
        return redirect()->route('kegiatan.pilih-detail', $request->kegiatan_id)
            ->with('success', "Berhasil menyimpan {$totalSaved} item konsumsi sebagai {$statusMessage}!");
    }

    /**
     * Validasi kesesuaian SBM.
     */
    public function validasiSBM($id)
    {
        $konsumsi = Konsumsi::with('kegiatan.unor')->findOrFail($id);

        // Ambil SBM berdasarkan provinsi kegiatan
        $sbm = SatuanBiayaKonsumsiProvinsi::where('id_provinsi', $konsumsi->kegiatan->unor->id_provinsi ?? 1)
            ->where('tahun_anggaran', date('Y'))
            ->first();

        $sesuai_sbm = false;
        $pesan = 'Data SBM tidak ditemukan';

        if ($sbm) {
            // Cek apakah harga per porsi sesuai SBM
            if ($konsumsi->harga <= $sbm->harga_max_makan) {
                $sesuai_sbm = true;
                $pesan = 'Harga konsumsi sesuai dengan SBM';
            } else {
                $pesan = 'Harga konsumsi melebihi batas maksimal SBM (Rp ' . number_format($sbm->harga_max_makan, 0, ',', '.') . ')';
            }
        }

        return view('konsumsi.validasi', compact('konsumsi', 'sbm', 'sesuai_sbm', 'pesan'));
    }

    /**
     * Show the form for koreksi konsumsi.
     */
    public function koreksi($id)
    {
        $konsumsi = Konsumsi::with('kegiatan')->findOrFail($id);

        // Ambil SBM untuk referensi
        $sbm = SatuanBiayaKonsumsiProvinsi::where('id_provinsi', $konsumsi->kegiatan->unor->id_provinsi ?? 1)
            ->where('tahun_anggaran', date('Y'))
            ->first();

        return view('konsumsi.koreksi', compact('konsumsi', 'sbm'));
    }

    /**
     * Update konsumsi setelah koreksi.
     */
    public function updateKoreksi(Request $request, $id)
    {
        $konsumsi = Konsumsi::findOrFail($id);

        $validated = $request->validate([
            'jumlah' => 'required|integer|min:1',
            'harga' => 'required|integer|min:0',
        ]);

        $konsumsi->update($validated);

        return redirect()->route('konsumsi.validasi', $konsumsi->id)
            ->with('success', 'Data konsumsi berhasil dikoreksi! Silakan validasi ulang.');
    }

    /**
     * Remove the specified konsumsi from storage.
     */
    public function destroy($id)
    {
        $konsumsi = Konsumsi::findOrFail($id);
        $kegiatan_id = $konsumsi->kegiatan_id;

        $konsumsi->delete();

        return redirect()->route('kegiatan.pilih-detail', $kegiatan_id)
            ->with('success', 'Item konsumsi berhasil dihapus!');
    }
}
