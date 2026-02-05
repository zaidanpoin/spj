<?php

namespace App\Http\Controllers;

use App\Models\Kegiatan;
use App\Models\Konsumsi;
use App\Models\Vendor;
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
                ->with('vendor')
                ->get(),
        ];

        // Load existing vendors for dropdown
        $vendors = Vendor::orderBy('nama_vendor')->get();

        // Prepare simple vendor data map (keyed by nama_vendor) for JS autofill
        $vendorsData = $vendors->mapWithKeys(function ($v) {
            return [
                $v->nama_vendor => [
                    'nama_direktur' => $v->nama_direktur,
                    'jabatan' => $v->jabatan,
                    'npwp' => $v->npwp,
                    'alamat' => $v->alamat,
                    'bank' => $v->bank ?? null,
                    'rekening' => $v->rekening ?? null,
                ],
            ];
        })->toArray();

        // Collect vendor data from draft barang items (if vendor relation present)
        $vendorDataFromDraft = [];
        foreach ($draftData['barang'] as $item) {
            if ($item->vendor && $item->vendor->nama_vendor) {
                $nama = $item->vendor->nama_vendor;
                if (!isset($vendorDataFromDraft[$nama])) {
                    $vendorDataFromDraft[$nama] = [
                        'nama_direktur' => $item->vendor->nama_direktur,
                        'jabatan' => $item->vendor->jabatan,
                        'npwp' => $item->vendor->npwp,
                        'alamat' => $item->vendor->alamat,
                        'bank' => $item->vendor->bank ?? null,
                        'rekening' => $item->vendor->rekening ?? null,
                    ];
                }
            }
        }

        return view('konsumsi.create', compact('kegiatan', 'waktuKonsumsi', 'tarifSBM', 'draftData', 'vendors', 'vendorsData', 'vendorDataFromDraft'));
    }

    /**
     * Store a newly created konsumsi in storage.
     */
    public function store(Request $request)
    {
        // DEBUG: Log incoming request
        \Log::info('Konsumsi Store Request:', $request->all());

        // Determine status (draft or final)
        // Note: save_as_draft comes as "0" or "1" from form buttons
        $saveAsDraft = $request->input('save_as_draft');
        $status = ($saveAsDraft === '1' || $saveAsDraft === 1) ? 'draft' : 'final';

        \Log::info("Save as draft value: " . var_export($saveAsDraft, true) . " - Status: {$status}");

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
            'barang.*.vendor_nama' => 'nullable|string|max:255',
            'barang.*.vendor_id' => 'nullable|exists:vendors,id',
            // Vendor detail fields
            'vendor_data' => 'nullable|array',
            'vendor_data.*.nama_vendor' => 'nullable|string|max:255',
            'vendor_data.*.nama_direktur' => 'nullable|string|max:255',
            'vendor_data.*.jabatan' => 'nullable|string|max:255',
            'vendor_data.*.npwp' => 'nullable|string|max:30',
            'vendor_data.*.alamat' => 'nullable|string',
            'vendor_data.*.bank' => 'nullable|string|max:255',
            'vendor_data.*.rekening' => 'nullable|string|max:255',
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

        // First, process vendor data if provided
        \Log::info("Vendor data received:", ['vendor_data' => $request->input('vendor_data', 'NOT SET')]);
        $vendorMap = []; // Map vendor_nama to vendor_id
        if ($request->has('vendor_data') && is_array($request->vendor_data)) {
            foreach ($request->vendor_data as $vendorNama => $vendorInfo) {
                if (!empty($vendorNama)) {
                    $attrs = [
                        'nama_direktur' => $vendorInfo['nama_direktur'] ?? null,
                        'jabatan' => $vendorInfo['jabatan'] ?? null,
                        'npwp' => $vendorInfo['npwp'] ?? null,
                        'alamat' => $vendorInfo['alamat'] ?? null,
                        'bank' => $vendorInfo['bank'] ?? null,
                        'rekening' => $vendorInfo['rekening'] ?? null,
                    ];
                    // remove keys with null so we don't overwrite existing DB values with null
                    $attrs = array_filter($attrs, function ($v) {
                        return !is_null($v);
                    });
                    $vendor = Vendor::updateOrCreate(
                        ['nama_vendor' => $vendorNama],
                        $attrs
                    );
                    $vendorMap[$vendorNama] = $vendor->id;
                    \Log::info("Vendor saved/updated: {$vendorNama}", $vendor->toArray());
                }
            }
        }

        // Calculate total per vendor for validation
        $vendorTotals = [];
        if ($request->has('barang') && is_array($request->barang)) {
            foreach ($request->barang as $item) {
                if (!empty($item['nama']) && trim($item['nama']) !== '') {
                    $vendorNama = $item['vendor_nama'] ?? 'Tanpa Vendor';
                    $subtotal = ($item['jumlah'] ?? 1) * ($item['harga'] ?? 0);
                    if (!isset($vendorTotals[$vendorNama])) {
                        $vendorTotals[$vendorNama] = 0;
                    }
                    $vendorTotals[$vendorNama] += $subtotal;
                }
            }
        }

        // Validate vendors with total >= 10 million must have complete data
        $threshold = 10000000; // 10 juta
        $incompleteVendors = [];
        foreach ($vendorTotals as $vendorNama => $total) {
            if ($total >= $threshold && $vendorNama !== 'Tanpa Vendor') {
                // Check if vendor data is complete
                $vendor = Vendor::where('nama_vendor', $vendorNama)->first();
                if (!$vendor || !$vendor->isComplete()) {
                    $incompleteVendors[] = $vendorNama . ' (Rp ' . number_format($total, 0, ',', '.') . ')';
                }
            }
        }

        // If saving as final and there are incomplete vendors, return error
        if ($status === 'final' && !empty($incompleteVendors)) {
            return back()->withInput()->with(
                'error',
                'Vendor dengan total belanja ≥ Rp 10.000.000 wajib melengkapi data (Direktur, Jabatan, NPWP, Alamat): ' .
                implode(', ', $incompleteVendors)
            );
        }

        // Save Barang items
        if ($request->has('barang') && is_array($request->barang)) {
            foreach ($request->barang as $index => $item) {
                \Log::info("Processing barang item {$index}:", $item);
                if (!empty($item['nama']) && trim($item['nama']) !== '') {
                    try {
                        $vendorNama = $item['vendor_nama'] ?? null;
                        $vendorId = null;

                        // Get or create vendor if name provided
                        if (!empty($vendorNama)) {
                            if (isset($vendorMap[$vendorNama])) {
                                $vendorId = $vendorMap[$vendorNama];
                            } else {
                                // Check if vendor_data was submitted for this vendor
                                $vendorInfo = $request->input("vendor_data.{$vendorNama}", []);
                                $attrs = [
                                    'nama_direktur' => $vendorInfo['nama_direktur'] ?? null,
                                    'jabatan' => $vendorInfo['jabatan'] ?? null,
                                    'npwp' => $vendorInfo['npwp'] ?? null,
                                    'alamat' => $vendorInfo['alamat'] ?? null,
                                    'bank' => $vendorInfo['bank'] ?? null,
                                    'rekening' => $vendorInfo['rekening'] ?? null,
                                ];
                                $attrs = array_filter($attrs, function ($v) {
                                    return !is_null($v);
                                });
                                $vendor = Vendor::updateOrCreate(
                                    ['nama_vendor' => $vendorNama],
                                    $attrs
                                );
                                $vendorId = $vendor->id;
                                $vendorMap[$vendorNama] = $vendorId;
                                \Log::info("Vendor created/updated in barang loop: {$vendorNama}", $vendor->toArray());
                            }
                        }

                        Konsumsi::create([
                            'kegiatan_id' => $request->kegiatan_id,
                            'kategori' => 'barang',
                            'status' => $status,
                            'nama_konsumsi' => $item['nama'],
                            'no_kwitansi' => $item['no_kwitansi'] ?? null,
                            'vendor_id' => $vendorId,
                            'waktu_konsumsi_id' => null, // Barang tidak punya waktu konsumsi
                            'jumlah' => $item['jumlah'] ?? 1,
                            'harga' => $item['harga'] ?? 0,
                            'tanggal_pembelian' => now(),
                        ]);
                        $totalSaved++;
                        \Log::info("Barang saved: {$item['nama']} with vendor_id: {$vendorId}");
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

    /**
     * Update vendor bank and PPN information.
     */
    public function updateVendorBank(Request $request, $vendorId)
    {
        $validated = $request->validate([
            'bank' => 'required|string|max:255',
            'rekening' => 'required|string|max:100',
            'ppn' => 'required|numeric|min:0|max:100'
        ]);

        $vendor = Vendor::findOrFail($vendorId);
        $vendor->update($validated);

        return response()->json([
            'success' => true,
            'vendor' => $vendor,
            'message' => 'Data vendor berhasil diperbarui'
        ]);
    }
}
