<?php

namespace App\Http\Controllers;

use App\Models\Narasumber;
use App\Models\SBMHonorariumNarasumber;
use App\Models\Kegiatan;
use Illuminate\Http\Request;

class NarasumberController extends Controller
{
    /**
     * Show form to create narasumber for a specific kegiatan
     */
    public function create($kegiatan_id)
    {
        $kegiatan = Kegiatan::findOrFail($kegiatan_id);

        // Load all SBM Honorarium tarif
        $sbmHonorarium = SBMHonorariumNarasumber::orderBy('tarif_honorarium', 'desc')->get();

        // Load draft data if exists
        $draftData = Narasumber::where('kegiatan_id', $kegiatan_id)
            ->where('status', 'draft')
            ->get();

        // Check if there's any draft
        $hasDraft = $draftData->count() > 0;

        return view('narasumber.create', compact('kegiatan', 'sbmHonorarium', 'draftData', 'hasDraft'));
    }

    /**
     * Store narasumber with SBM validation & PPh21 calculation
     * Supports multiple narasumber submission
     */
    public function store(Request $request)
    {
        // Determine status (draft or final)
        $saveAsDraft = $request->input('save_as_draft');
        $status = ($saveAsDraft === '1' || $saveAsDraft === 1) ? 'draft' : 'final';

        \Log::info("Narasumber save - Draft: " . var_export($saveAsDraft, true) . " - Status: {$status}");

        // Validation for multiple narasumber
        $validated = $request->validate([
            'kegiatan_id' => 'required|exists:kegiatans,id',
            'narasumber' => 'required|array|min:1',
            'narasumber.*.jenis' => 'required|in:narasumber,moderator,pembawa_acara,panitia',
            'narasumber.*.golongan_jabatan' => 'required|string',
            'narasumber.*.nama_narasumber' => 'required|string|max:255',
            'narasumber.*.npwp' => 'required|string|max:20',
            'narasumber.*.jumlah_jam' => 'required|integer|min:1',
            'narasumber.*.tarif_pph21' => 'required|in:0,5,6,15',
            'narasumber.*.honorarium_bruto' => 'required|integer|min:0',
        ]);

        $kegiatan_id = $validated['kegiatan_id'];

        // Delete existing draft data before saving new
        Narasumber::where('kegiatan_id', $kegiatan_id)
            ->where('status', 'draft')
            ->delete();

        $savedCount = 0;
        $errors = [];

        foreach ($request->narasumber as $index => $item) {
            // Skip empty entries
            if (empty($item['nama_narasumber'])) {
                continue;
            }

            // Get SBM tarif for validation
            $sbm = SBMHonorariumNarasumber::where('golongan_jabatan', $item['golongan_jabatan'])->first();

            if (!$sbm) {
                $errors[] = "Narasumber #{$index}: Golongan jabatan tidak valid.";
                continue;
            }

            // HARD VALIDATION: Honorarium bruto TIDAK BOLEH > tarif SBM
            if ($item['honorarium_bruto'] > $sbm->tarif_honorarium) {
                $errors[] = "Narasumber '{$item['nama_narasumber']}': Honorarium melebihi SBM (Max: Rp " . number_format($sbm->tarif_honorarium, 0, ',', '.') . ")";
                continue;
            }

            // Calculate PPh21
            $tarif_persen = (int) $item['tarif_pph21'];
            $jumlahJam = (int) ($item['jumlah_jam'] ?? 1);
            $tarifPerJam = $item['honorarium_bruto']; // Tarif per OJ = honorarium_bruto / jumlah_jam atau langsung input
            $totalHonorarium = $tarifPerJam * $jumlahJam;
            $pph21 = ($totalHonorarium * $tarif_persen) / 100;
            $honorarium_netto = $totalHonorarium - $pph21;

            // Save narasumber with status
            Narasumber::create([
                'kegiatan_id' => $kegiatan_id,
                'nama_narasumber' => $item['nama_narasumber'],
                'jenis' => $item['jenis'],
                'golongan_jabatan' => $item['golongan_jabatan'],
                'npwp' => $item['npwp'],
                'jumlah_jam' => $jumlahJam,
                'tarif_per_jam' => $tarifPerJam,
                'tarif_pph21' => $item['tarif_pph21'],
                'honorarium_bruto' => $totalHonorarium,
                'pph21' => $pph21,
                'honorarium_netto' => $honorarium_netto,
                'status' => $status,
            ]);
            $savedCount++;
        }

        if ($savedCount === 0) {
            return back()->withErrors(['narasumber' => 'Tidak ada narasumber yang berhasil disimpan. ' . implode(' ', $errors)])->withInput();
        }

        $statusMessage = $status === 'draft' ? 'draft' : 'final dan siap divalidasi';
        $message = "Berhasil menyimpan {$savedCount} narasumber sebagai {$statusMessage}.";
        if (!empty($errors)) {
            $message .= " Beberapa item tidak tersimpan: " . implode('; ', $errors);
        }

        return redirect()->route('kegiatan.pilih-detail', $kegiatan_id)
            ->with('success', $message);
    }

    /**
     * Delete narasumber
     */
    public function destroy($id)
    {
        $narasumber = Narasumber::findOrFail($id);
        $kegiatan_id = $narasumber->kegiatan_id;

        $narasumber->delete();

        return redirect()->route('kegiatan.pilih-detail', $kegiatan_id)
            ->with('success', 'Narasumber berhasil dihapus');
    }

    /**
     * Generate Daftar Hadir Narasumber (Attendance Sheet)
     */
    public function daftarHadir($kegiatan_id)
    {
        $kegiatan = Kegiatan::with(['unor', 'unitKerja'])->findOrFail($kegiatan_id);
        $narasumbers = Narasumber::where('kegiatan_id', $kegiatan_id)->get();

        return view('narasumber.daftar-hadir', compact('kegiatan', 'narasumbers'));
    }

    /**
     * Generate Daftar Honorarium (Payment List)
     */
    public function daftarHonorarium($kegiatan_id)
    {
        $kegiatan = Kegiatan::with(['unor', 'unitKerja'])->findOrFail($kegiatan_id);
        $narasumbers = Narasumber::where('kegiatan_id', $kegiatan_id)->get();
        $totalHonorarium = $narasumbers->sum('honorarium_netto');

        return view('narasumber.daftar-honorarium', compact('kegiatan', 'narasumbers', 'totalHonorarium'));
    }
}
