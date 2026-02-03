<?php

namespace App\Http\Controllers;

use App\Models\Kegiatan;
use App\Models\SPPD;
use App\Models\PPK;
use App\Models\Bendahara;
use App\Models\Pegawai;
use Illuminate\Http\Request;

class SPPDController extends Controller
{
    /**
     * Show the form for creating SPPD.
     */
    public function create($kegiatan_id)
    {
        $kegiatan = Kegiatan::with(['unor', 'ppk', 'bendahara'])->findOrFail($kegiatan_id);

        // Load draft data if exists
        $draftData = SPPD::where('kegiatan_id', $kegiatan_id)
            ->where('status', 'draft')
            ->get();

        // Get PPK and Bendahara list for dropdown
        $ppkList = PPK::all();
        $bendaharaList = Bendahara::all();
        $pegawaiList = Pegawai::all();

        return view('sppd.create', compact('kegiatan', 'draftData', 'ppkList', 'bendaharaList', 'pegawaiList'));
    }

    /**
     * Store a newly created SPPD in storage.
     */
    public function store(Request $request)
    {
        // Determine status (draft or final)
        $saveAsDraft = $request->input('save_as_draft');
        $status = ($saveAsDraft === '1' || $saveAsDraft === 1) ? 'draft' : 'final';

        // Delete existing draft data
        SPPD::where('kegiatan_id', $request->kegiatan_id)
            ->where('status', 'draft')
            ->delete();

        $validated = $request->validate([
            'kegiatan_id' => 'required|exists:kegiatans,id',
            'sppd' => 'nullable|array',
            'sppd.*.lembar' => 'nullable|string|max:50',
            'sppd.*.kode_no' => 'nullable|string|max:50',
            'sppd.*.no' => 'nullable|string|max:50',
            'sppd.*.nospd' => 'nullable|string|max:50',
            'sppd.*.tgl' => 'nullable|date',
            'sppd.*.tglspd' => 'nullable|date',
            'sppd.*.nama' => 'nullable|string|max:70',
            'sppd.*.nip' => 'nullable|string|max:30',
            'sppd.*.pangkat_gol' => 'nullable|string|max:100',
            'sppd.*.jabatan' => 'nullable|string|max:500',
            'sppd.*.eselon' => 'nullable|string|max:20',
            'sppd.*.maksud' => 'nullable|string|max:255',
            'sppd.*.kendaraan' => 'nullable|string|max:100',
            'sppd.*.tujuan' => 'nullable|string|max:255',
            'sppd.*.tgl_brkt' => 'nullable|date',
            'sppd.*.tgl_kbl' => 'nullable|date',
            'sppd.*.catatan' => 'nullable|string|max:255',
            'sppd.*.perintah_nama' => 'nullable|string|max:50',
            'sppd.*.perintah_nip' => 'nullable|string|max:20',
            'sppd.*.perintah_jabatan' => 'nullable|string|max:800',
            'sppd.*.ppk_nama' => 'nullable|string|max:100',
            'sppd.*.ppk_nip' => 'nullable|string|max:30',
            'sppd.*.bendahara_nama' => 'nullable|string|max:70',
            'sppd.*.bendahara_nip' => 'nullable|string|max:30',
            'sppd.*.pembuat_nama' => 'nullable|string|max:70',
            'sppd.*.pembuat_nip' => 'nullable|string|max:30',
            'sppd.*.tingkat_biaya' => 'nullable|string|max:100',
            'sppd.*.alat_angkut' => 'nullable|string|max:255',
            'sppd.*.tempat_berangkat' => 'nullable|string|max:100',
            'sppd.*.akun' => 'nullable|string|max:20',
            'sppd.*.kembali_baru' => 'nullable|string|max:100',
            'sppd.*.jenis_tujuan' => 'nullable|integer',
            'sppd.*.is_pns' => 'nullable|boolean',
        ]);

        $totalSaved = 0;

        // Save SPPD items
        if ($request->has('sppd') && is_array($request->sppd)) {
            foreach ($request->sppd as $index => $item) {
                // Minimal harus ada nama pelaksana
                if (!empty($item['nama']) && trim($item['nama']) !== '') {
                    try {
                        SPPD::create([
                            'kegiatan_id' => $request->kegiatan_id,
                            'status' => $status,
                            'lembar' => $item['lembar'] ?? '',
                            'kode_no' => $item['kode_no'] ?? '',
                            'no' => $item['no'] ?? '',
                            'nospd' => $item['nospd'] ?? '',
                            'tgl' => $item['tgl'] ?? null,
                            'tglspd' => $item['tglspd'] ?? null,
                            'nama' => $item['nama'],
                            'nip' => $item['nip'] ?? '',
                            'pangkat_gol' => $item['pangkat_gol'] ?? '',
                            'jabatan' => $item['jabatan'] ?? '',
                            'eselon' => $item['eselon'] ?? '',
                            'maksud' => $item['maksud'] ?? '',
                            'kendaraan' => $item['kendaraan'] ?? '',
                            'tujuan' => $item['tujuan'] ?? '',
                            'tgl_brkt' => $item['tgl_brkt'] ?? null,
                            'tgl_kbl' => $item['tgl_kbl'] ?? null,
                            'catatan' => $item['catatan'] ?? '',
                            'perintah_nama' => $item['perintah_nama'] ?? '',
                            'perintah_nip' => $item['perintah_nip'] ?? '',
                            'perintah_jabatan' => $item['perintah_jabatan'] ?? '',
                            'ppk_nama' => $item['ppk_nama'] ?? '',
                            'ppk_nip' => $item['ppk_nip'] ?? '',
                            'bendahara_nama' => $item['bendahara_nama'] ?? '',
                            'bendahara_nip' => $item['bendahara_nip'] ?? '',
                            'pembuat_nama' => $item['pembuat_nama'] ?? '',
                            'pembuat_nip' => $item['pembuat_nip'] ?? '',
                            'tingkat_biaya' => $item['tingkat_biaya'] ?? '',
                            'alat_angkut' => $item['alat_angkut'] ?? '',
                            'tempat_berangkat' => $item['tempat_berangkat'] ?? '',
                            'akun' => $item['akun'] ?? '',
                            'kembali_baru' => $item['kembali_baru'] ?? '',
                            'jenis_tujuan' => $item['jenis_tujuan'] ?? 0,
                            'is_pns' => $item['is_pns'] ?? 0,
                            'update_date' => now(),
                            'update_user' => auth()->id() ?? 0,
                        ]);
                        $totalSaved++;
                    } catch (\Exception $e) {
                        \Log::error("Error saving SPPD: " . $e->getMessage());
                    }
                }
            }
        }

        if ($totalSaved == 0) {
            return back()->with('error', 'Tidak ada SPPD yang disimpan. Pastikan nama pelaksana sudah diisi!');
        }

        $statusMessage = $status === 'draft' ? 'draft' : 'final';
        return redirect()->route('kegiatan.pilih-detail', $request->kegiatan_id)
            ->with('success', "Berhasil menyimpan {$totalSaved} SPPD sebagai {$statusMessage}!");
    }

    /**
     * Show the form for editing SPPD.
     */
    public function edit($id)
    {
        $sppd = SPPD::with('kegiatan')->findOrFail($id);
        $kegiatan = $sppd->kegiatan;

        // Get PPK and Bendahara list for dropdown
        $ppkList = PPK::all();
        $bendaharaList = Bendahara::all();
        $pegawaiList = Pegawai::all();

        return view('sppd.edit', compact('sppd', 'kegiatan', 'ppkList', 'bendaharaList', 'pegawaiList'));
    }

    /**
     * Update the specified SPPD in storage.
     */
    public function update(Request $request, $id)
    {
        $sppd = SPPD::findOrFail($id);

        $validated = $request->validate([
            'lembar' => 'nullable|string|max:50',
            'kode_no' => 'nullable|string|max:50',
            'no' => 'nullable|string|max:50',
            'nospd' => 'nullable|string|max:50',
            'tgl' => 'nullable|date',
            'tglspd' => 'nullable|date',
            'nama' => 'required|string|max:70',
            'nip' => 'nullable|string|max:30',
            'pangkat_gol' => 'nullable|string|max:100',
            'jabatan' => 'nullable|string|max:500',
            'eselon' => 'nullable|string|max:20',
            'maksud' => 'nullable|string|max:255',
            'kendaraan' => 'nullable|string|max:100',
            'tujuan' => 'nullable|string|max:255',
            'tgl_brkt' => 'nullable|date',
            'tgl_kbl' => 'nullable|date',
            'catatan' => 'nullable|string|max:255',
            'perintah_nama' => 'nullable|string|max:50',
            'perintah_nip' => 'nullable|string|max:20',
            'perintah_jabatan' => 'nullable|string|max:800',
            'ppk_nama' => 'nullable|string|max:100',
            'ppk_nip' => 'nullable|string|max:30',
            'bendahara_nama' => 'nullable|string|max:70',
            'bendahara_nip' => 'nullable|string|max:30',
            'pembuat_nama' => 'nullable|string|max:70',
            'pembuat_nip' => 'nullable|string|max:30',
            'tingkat_biaya' => 'nullable|string|max:100',
            'alat_angkut' => 'nullable|string|max:255',
            'tempat_berangkat' => 'nullable|string|max:100',
            'akun' => 'nullable|string|max:20',
            'kembali_baru' => 'nullable|string|max:100',
            'jenis_tujuan' => 'nullable|integer',
            'is_pns' => 'nullable|boolean',
            // Tahap perjalanan fields
            'I_dari' => 'nullable|string|max:50',
            'I_ke' => 'nullable|string|max:50',
            'I_tgl' => 'nullable|date',
            'I_instansi_nama' => 'nullable|string|max:300',
            'I_nama' => 'nullable|string|max:50',
            'I_nip' => 'nullable|string|max:18',
        ]);

        $validated['update_date'] = now();
        $validated['update_user'] = auth()->id() ?? 0;

        $sppd->update($validated);

        return redirect()->route('kegiatan.pilih-detail', $sppd->kegiatan_id)
            ->with('success', 'SPPD berhasil diperbarui!');
    }

    /**
     * Remove the specified SPPD from storage.
     */
    public function destroy($id)
    {
        $sppd = SPPD::findOrFail($id);
        $kegiatan_id = $sppd->kegiatan_id;

        $sppd->delete();

        return redirect()->route('kegiatan.pilih-detail', $kegiatan_id)
            ->with('success', 'SPPD berhasil dihapus!');
    }

    /**
     * Preview SPPD.
     */
    public function preview($id)
    {
        $sppd = SPPD::with(['kegiatan', 'ppk', 'bendahara'])->findOrFail($id);

        return view('sppd.preview', compact('sppd'));
    }
}
