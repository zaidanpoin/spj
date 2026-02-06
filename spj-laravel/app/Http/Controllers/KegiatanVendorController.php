<?php

namespace App\Http\Controllers;

use App\Models\Kegiatan;
use App\Models\Vendor;
use Illuminate\Http\Request;

class KegiatanVendorController extends Controller
{
    /**
     * Tampilkan halaman manajemen vendor untuk kegiatan tertentu
     */
    public function index($kegiatanId)
    {
        $kegiatan = Kegiatan::with(['vendors', 'unitKerja'])->findOrFail($kegiatanId);
        $allVendors = Vendor::orderBy('nama_vendor')->get();

        return view('kegiatan.vendor.index', compact('kegiatan', 'allVendors'));
    }

    /**
     * Simpan vendor ke kegiatan dengan nomor surat
     */
    public function store(Request $request, $kegiatanId)
    {
        $validated = $request->validate([
            'vendor_id' => 'required|exists:vendors,id',
            'nomor_berita_acara' => 'nullable|string|max:255',
            'nomor_bast' => 'nullable|string|max:255',
            'nomor_berita_pembayaran' => 'nullable|string|max:255',
            // Data detail vendor
            'nama_direktur' => 'nullable|string|max:255',
            'jabatan' => 'nullable|string|max:255',
            'npwp' => 'nullable|string|max:255',
            'alamat' => 'nullable|string',
            'bank' => 'nullable|string|max:255',
            'rekening' => 'nullable|string|max:255',
            'ppn' => 'nullable|string|max:10',
        ], [
            'vendor_id.required' => 'Vendor harus dipilih',
            'vendor_id.exists' => 'Vendor tidak ditemukan',
        ]);

        $kegiatan = Kegiatan::findOrFail($kegiatanId);

        // Cek apakah vendor sudah ada di kegiatan ini
        if ($kegiatan->vendors()->where('vendor_id', $validated['vendor_id'])->exists()) {
            return redirect()->back()
                ->with('error', 'Vendor sudah ditambahkan ke kegiatan ini!')
                ->withInput();
        }

        // Attach vendor dengan nomor surat dan data detail
        $kegiatan->vendors()->attach($validated['vendor_id'], [
            'nomor_berita_acara' => $validated['nomor_berita_acara'] ?? null,
            'nomor_bast' => $validated['nomor_bast'] ?? null,
            'nomor_berita_pembayaran' => $validated['nomor_berita_pembayaran'] ?? null,
            'nama_direktur' => $validated['nama_direktur'] ?? null,
            'jabatan' => $validated['jabatan'] ?? null,
            'npwp' => $validated['npwp'] ?? null,
            'alamat' => $validated['alamat'] ?? null,
            'bank' => $validated['bank'] ?? null,
            'rekening' => $validated['rekening'] ?? null,
            'ppn' => $validated['ppn'] ?? null,
        ]);

        $vendor = Vendor::find($validated['vendor_id']);

        return redirect()->back()
            ->with('success', "Vendor {$vendor->nama_vendor} berhasil ditambahkan ke kegiatan!");
    }

    /**
     * Update nomor surat untuk vendor di kegiatan
     */
    public function update(Request $request, $kegiatanId, $vendorId)
    {
        $validated = $request->validate([
            'nomor_berita_acara' => 'nullable|string|max:255',
            'nomor_bast' => 'nullable|string|max:255',
            'nomor_berita_pembayaran' => 'nullable|string|max:255',
            // Data detail vendor
            'nama_direktur' => 'nullable|string|max:255',
            'jabatan' => 'nullable|string|max:255',
            'npwp' => 'nullable|string|max:255',
            'alamat' => 'nullable|string',
            'bank' => 'nullable|string|max:255',
            'rekening' => 'nullable|string|max:255',
            'ppn' => 'nullable|string|max:10',
        ]);

        $kegiatan = Kegiatan::findOrFail($kegiatanId);

        // Cek apakah vendor ada di kegiatan ini
        if (!$kegiatan->vendors()->where('vendor_id', $vendorId)->exists()) {
            return redirect()->back()
                ->with('error', 'Vendor tidak ditemukan di kegiatan ini!');
        }

        // Update pivot data
        $kegiatan->vendors()->updateExistingPivot($vendorId, [
            'nomor_berita_acara' => $validated['nomor_berita_acara'] ?? null,
            'nomor_bast' => $validated['nomor_bast'] ?? null,
            'nomor_berita_pembayaran' => $validated['nomor_berita_pembayaran'] ?? null,
            'nama_direktur' => $validated['nama_direktur'] ?? null,
            'jabatan' => $validated['jabatan'] ?? null,
            'npwp' => $validated['npwp'] ?? null,
            'alamat' => $validated['alamat'] ?? null,
            'bank' => $validated['bank'] ?? null,
            'rekening' => $validated['rekening'] ?? null,
            'ppn' => $validated['ppn'] ?? null,
        ]);

        $vendor = Vendor::find($vendorId);

        return redirect()->back()
            ->with('success', "Nomor surat untuk vendor {$vendor->nama_vendor} berhasil diupdate!");
    }

    /**
     * Hapus vendor dari kegiatan
     */
    public function destroy($kegiatanId, $vendorId)
    {
        $kegiatan = Kegiatan::findOrFail($kegiatanId);
        $vendor = Vendor::find($vendorId);

        // Cek apakah vendor digunakan di konsumsi
        $usedInKonsumsi = $kegiatan->konsumsis()
            ->where('vendor_id', $vendorId)
            ->exists();

        if ($usedInKonsumsi) {
            return redirect()->back()
                ->with('error', "Vendor {$vendor->nama_vendor} tidak dapat dihapus karena sudah digunakan di data konsumsi!");
        }

        $kegiatan->vendors()->detach($vendorId);

        return redirect()->back()
            ->with('success', "Vendor {$vendor->nama_vendor} berhasil dihapus dari kegiatan!");
    }
}
