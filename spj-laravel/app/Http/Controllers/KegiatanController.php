<?php

namespace App\Http\Controllers;

use App\Models\Kegiatan;
use App\Models\Unor;
use App\Models\UnitKerja;
use Illuminate\Http\Request;

class KegiatanController extends Controller
{
    /**
     * Display a listing of kegiatan.
     */
    public function index(Request $request)
    {
        $query = Kegiatan::with(['unor', 'unitKerja', 'user']);

        // Filter by user's unit kerja (except super admin)
        $user = auth()->user();
        if ($user && !$user->hasRole('super-admin') && $user->id_unker) {
            $query->where('unit_kerja_id', $user->id_unker);
        }

        // Search
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where('nama_kegiatan', 'like', "%{$search}%");
        }

        // Filter by Unor
        if ($request->has('unor_id') && $request->unor_id != '') {
            $query->where('unor_id', $request->unor_id);
        }

        // Filter by Periode (month)
        if ($request->has('periode') && $request->periode != '') {
            $periode = $request->periode; // Format: YYYY-MM
            $query->whereYear('tanggal_mulai', substr($periode, 0, 4))
                ->whereMonth('tanggal_mulai', substr($periode, 5, 2));
        }

        $kegiatans = $query->latest()->paginate(10);
        $unors = Unor::orderBy('nama_unor')->get();

        return view('kegiatan.index', compact('kegiatans', 'unors'));
    }

    /**
     * Show the form for creating a new kegiatan.
     */
    public function create()
    {
        $user = auth()->user();
        $isSuperAdmin = $user->hasRole('super-admin');

        $unitKerjas = UnitKerja::with('unor')->orderBy('nama_unit')->get();
        $makData = \App\Models\MAK::orderBy('tahun', 'desc')->orderBy('nama')->get();
        $ppkData = \App\Models\PPK::orderBy('nama')->get();
        $bendaharaData = \App\Models\Bendahara::where('is_active', true)->orderBy('nama')->get();
        $provinsiData = \App\Models\SatuanBiayaKonsumsiProvinsi::orderBy('nama_provinsi')->get();
        return view('kegiatan.create', compact('unitKerjas', 'makData', 'ppkData', 'bendaharaData', 'provinsiData', 'user', 'isSuperAdmin'));
    }

    /**
     * Store a newly created kegiatan in storage.
     */
    public function store(Request $request)
    {
        $messages = [
            'tanggal_mulai.required' => 'Silakan isi Tanggal Mulai kegiatan.',
            'tanggal_mulai.date' => 'Format Tanggal Mulai tidak valid.',
            'tanggal_selesai.required' => 'Silakan isi Tanggal Selesai kegiatan.',
            'tanggal_selesai.date' => 'Format Tanggal Selesai tidak valid.',
            'tanggal_selesai.after_or_equal' => 'Tanggal Selesai harus sama atau setelah Tanggal Mulai.',
        ];

        $validated = $request->validate([
            'nama_kegiatan' => 'required|string|max:255',
            'uraian_kegiatan' => 'nullable|string',
            'unit_kerja_id' => 'required|exists:unit_kerjas,id',
            'mak_id' => 'required|exists:mak,id',
            'ppk_id' => 'required|exists:ppk,id',
            'bendahara_id' => 'nullable|exists:bendaharas,id',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'nomor_bukti_konsumsi' => 'nullable|string|max:255',
            'nomor_bukti_profesi' => 'nullable|string|max:255',
            'jumlah_peserta' => 'required|integer|min:1',
            'provinsi_id' => 'required|exists:satuan_biaya_konsumsi_provinsi,id',
            'detail_lokasi' => 'required|string|max:255',
            'file_laporan' => 'nullable|file|mimes:pdf,doc,docx|max:10240',
        ], $messages);

        if ($request->hasFile('file_laporan')) {
            $validated['file_laporan'] = $request->file('file_laporan')
                ->store('laporan_kegiatan', 'public');
        }

        // set created_by if authenticated
        if (auth()->check()) {
            $validated['created_by'] = auth()->id();
        }

        // Auto-populate unor_id from unit_kerja relationship
        $unitKerja = UnitKerja::findOrFail($validated['unit_kerja_id']);
        $validated['unor_id'] = $unitKerja->unor_id;

        $kegiatan = Kegiatan::create($validated);

        return redirect()->route('kegiatan.pilih-detail', $kegiatan->id)
            ->with('success', 'Kegiatan berhasil dibuat! Silakan pilih jenis detail.');
    }

    /**
     * Display the specified kegiatan.
     */
    public function show(string $id)
    {
        $kegiatan = Kegiatan::with(['unor', 'unitKerja', 'konsumsis', 'kwitansiBelanjas'])->findOrFail($id);

        // Check authorization: user can only access their own unit kerja's data (except super admin)
        $user = auth()->user();
        if ($user && !$user->hasRole('super-admin') && $user->id_unker && $kegiatan->unit_kerja_id != $user->id_unker) {
            abort(403, 'Anda tidak memiliki akses ke kegiatan ini.');
        }

        return view('kegiatan.show', compact('kegiatan'));
    }

    /**
     * Halaman detail kegiatan: Menampilkan konsumsi dan form input
     */
    public function pilihDetail(string $id)
    {
        $kegiatan = Kegiatan::with(['unor', 'unitKerja'])->findOrFail($id);

        // Check authorization: user can only access their own unit kerja's data (except super admin)
        $user = auth()->user();
        if ($user && !$user->hasRole('super-admin') && $user->id_unker && $kegiatan->unit_kerja_id != $user->id_unker) {
            abort(403, 'Anda tidak memiliki akses ke kegiatan ini.');
        }

        // Get konsumsi grouped by kategori
        // Exclude draft items from the detail view
        $snacks = \App\Models\Konsumsi::where('kegiatan_id', $id)
            ->where('kategori', 'snack')
            ->where('status', '!=', 'draft')
            ->get();
        $makanans = \App\Models\Konsumsi::where('kegiatan_id', $id)
            ->where('kategori', 'makanan')
            ->where('status', '!=', 'draft')
            ->get();
        $barangs = \App\Models\Konsumsi::where('kegiatan_id', $id)
            ->where('kategori', 'barang')
            ->where('status', '!=', 'draft')
            ->get();

        // Get narasumber (exclude drafts)
        $narasumbers = \App\Models\Narasumber::where('kegiatan_id', $id)
            ->where('status', '!=', 'draft')
            ->get();

        // Get SPPD (exclude drafts)
        $sppds = \App\Models\SPPD::where('kegiatan_id', $id)
            ->where('status', '!=', 'draft')
            ->get();

        // Calculate totals
        $totalSnack = $snacks->sum(fn($item) => $item->jumlah * $item->harga);
        $totalMakanan = $makanans->sum(fn($item) => $item->jumlah * $item->harga);
        $totalBarang = $barangs->sum(fn($item) => $item->jumlah * $item->harga);
        $totalHonorarium = $narasumbers->sum('honorarium_netto');

        // Group barang items by vendor name (fallback to 'Tanpa Vendor')
        $vendorGroups = $barangs->groupBy(function ($item) {
            return optional($item->vendor)->nama_vendor ?? 'Tanpa Vendor';
        });

        // Per-vendor totals (sum of jumlah * harga)
        $vendorTotals = $vendorGroups->map(function ($items) {
            return $items->sum(fn($it) => $it->jumlah * $it->harga);
        });

        // Apply PPN tax for vendor groups (skip 'Tanpa Vendor')
        // Use vendor's ppn value from database (default 11%)
        $vendorTaxes = $vendorGroups->mapWithKeys(function ($items, $vendorName) use ($vendorTotals) {
            if (trim((string) $vendorName) === '' || $vendorName === 'Tanpa Vendor') {
                return [$vendorName => 0];
            }

            // Get vendor's PPN percentage from the first item (all items in group have same vendor)
            $vendor = $items->first()->vendor;
            $ppnPercent = $vendor ? ($vendor->ppn ?? 11) : 11;
            $total = $vendorTotals[$vendorName];

            return [$vendorName => (int) round($total * ($ppnPercent / 100))];
        });

        $vendorTotalsWithTax = $vendorTotals->map(function ($total, $vendorName) use ($vendorTaxes) {
            return $total + ($vendorTaxes[$vendorName] ?? 0);
        });

        $vendorTaxTotal = $vendorTaxes->sum();

        $grandTotal = $totalSnack + $totalMakanan + $totalBarang + $totalHonorarium;
        $grandTotalWithVendorTax = $grandTotal + $vendorTaxTotal;

        return view('kegiatan.pilih-detail', compact(
            'kegiatan',
            'snacks',
            'makanans',
            'barangs',
            'narasumbers',
            'sppds',
            'totalSnack',
            'totalMakanan',
            'totalBarang',
            'totalHonorarium',
            'grandTotal',
            'vendorGroups',
            'vendorTotals',
            'vendorTaxes',
            'vendorTotalsWithTax',
            'vendorTaxTotal',
            'grandTotalWithVendorTax'
        ));
    }
    /**
     * Show the form for editing the specified kegiatan.
     */
    public function edit(string $id)
    {
        $kegiatan = Kegiatan::findOrFail($id);

        // Check authorization: user can only edit their own unit kerja's data (except super admin)
        $user = auth()->user();
        if ($user && !$user->hasRole('super-admin') && $user->id_unker && $kegiatan->unit_kerja_id != $user->id_unker) {
            abort(403, 'Anda tidak memiliki akses untuk mengedit kegiatan ini.');
        }

        $isSuperAdmin = $user->hasRole('super-admin');
        $unors = Unor::all();
        $unitKerjas = UnitKerja::all();
        $makData = \App\Models\MAK::orderBy('tahun', 'desc')->orderBy('nama')->get();
        $ppkData = \App\Models\PPK::orderBy('nama')->get();
        $bendaharaData = \App\Models\Bendahara::where('is_active', true)->orderBy('nama')->get();
        $provinsiData = \App\Models\SatuanBiayaKonsumsiProvinsi::orderBy('nama_provinsi')->get();

        return view('kegiatan.edit', compact('kegiatan', 'unors', 'unitKerjas', 'makData', 'ppkData', 'bendaharaData', 'provinsiData', 'user', 'isSuperAdmin'));
    }

    /**
     * Update the specified kegiatan in storage.
     */
    public function update(Request $request, string $id)
    {
        $kegiatan = Kegiatan::findOrFail($id);

        // Check authorization: user can only update their own unit kerja's data (except super admin)
        $user = auth()->user();
        if ($user && !$user->hasRole('super-admin') && $user->id_unker && $kegiatan->unit_kerja_id != $user->id_unker) {
            abort(403, 'Anda tidak memiliki akses untuk mengupdate kegiatan ini.');
        }

        $messages = [
            'tanggal_mulai.required' => 'Silakan isi Tanggal Mulai kegiatan.',
            'tanggal_mulai.date' => 'Format Tanggal Mulai tidak valid.',
            'tanggal_selesai.required' => 'Silakan isi Tanggal Selesai kegiatan.',
            'tanggal_selesai.date' => 'Format Tanggal Selesai tidak valid.',
            'tanggal_selesai.after_or_equal' => 'Tanggal Selesai harus sama atau setelah Tanggal Mulai.',
        ];

        $validated = $request->validate([
            'nama_kegiatan' => 'required|string|max:255',
            'uraian_kegiatan' => 'nullable|string',
            'unit_kerja_id' => 'required|exists:unit_kerjas,id',
            'mak_id' => 'required|exists:mak,id',
            'ppk_id' => 'required|exists:ppk,id',
            'bendahara_id' => 'nullable|exists:bendaharas,id',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'nomor_bukti_konsumsi' => 'nullable|string|max:255',
            'nomor_bukti_profesi' => 'nullable|string|max:255',
            'jumlah_peserta' => 'required|integer|min:1',
            'provinsi_id' => 'required|exists:satuan_biaya_konsumsi_provinsi,id',
            'detail_lokasi' => 'required|string|max:255',
            'file_laporan' => 'nullable|file|mimes:pdf,doc,docx|max:10240',
        ], $messages);

        if ($request->hasFile('file_laporan')) {
            $validated['file_laporan'] = $request->file('file_laporan')
                ->store('laporan_kegiatan', 'public');
        }

        // Auto-populate unor_id from unit_kerja relationship
        $unitKerja = UnitKerja::findOrFail($validated['unit_kerja_id']);
        $validated['unor_id'] = $unitKerja->unor_id;

        $kegiatan->update($validated);

        return redirect()->route('kegiatan.index')
            ->with('success', 'Kegiatan berhasil diupdate!');
    }

    /**
     * Remove the specified kegiatan from storage.
     */
    public function destroy(string $id)
    {
        $kegiatan = Kegiatan::findOrFail($id);

        // Check authorization: user can only delete their own unit kerja's data (except super admin)
        $user = auth()->user();
        if ($user && !$user->hasRole('super-admin') && $user->id_unker && $kegiatan->unit_kerja_id != $user->id_unker) {
            abort(403, 'Anda tidak memiliki akses untuk menghapus kegiatan ini.');
        }

        $kegiatan->delete();

        return redirect()->route('kegiatan.index')
            ->with('success', 'Kegiatan berhasil dihapus!');
    }
}
