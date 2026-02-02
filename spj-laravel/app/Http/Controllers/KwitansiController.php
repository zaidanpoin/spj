<?php

namespace App\Http\Controllers;

use App\Models\Kegiatan;
use App\Models\Konsumsi;
use App\Models\Narasumber;
use Illuminate\Http\Request;
use Carbon\Carbon;

class KwitansiController extends Controller
{
    /**
     * Show kwitansi preview page
     */
    public function generate(Request $request, $kegiatan_id = null, $jenis = null)
    {
        // Support both route parameter and query string
        $kegiatanId = $kegiatan_id ?? $request->get('kegiatan_id');
        $jenis = $type = $request->query('type');



        $kegiatan = Kegiatan::with(['unor', 'unitKerja', 'mak', 'ppk', 'bendahara', 'konsumsis.waktuKonsumsi'])->findOrFail($kegiatanId);

        // Check if honorarium type
        $isHonorarium = (strtolower($jenis) === 'honorarium');
        // dd($isHonorarium);
        if ($isHonorarium) {
            // Get narasumber data for honorarium
            $narasumbers = Narasumber::where('kegiatan_id', $kegiatanId)->get();
            $totalHonorarium = $narasumbers->sum('honorarium_bruto');
            $konsumsis = collect(); // Empty collection for compatibility
            $totalKonsumsi = $totalHonorarium;

        } else {
            // Get konsumsi data
            $konsumsis = Konsumsi::with('waktuKonsumsi')->where('kegiatan_id', $kegiatanId)->get();
            $totalKonsumsi = $konsumsis->sum(fn($item) => $item->jumlah * $item->harga);
            $narasumbers = collect(); // Empty collection for compatibility
        }

        // Generate terbilang
        $terbilang = $this->terbilang($totalKonsumsi);

        // Tanggal dokumen
        $tanggalDokumen = Carbon::now()->locale('id')->translatedFormat('j F Y');
        $tanggalDokumen = 'Jakarta, ' . $tanggalDokumen;

        // Pembuat daftar (current user)
        $pembuatDaftar = auth()->user();

        // Handle different jenis kwitansi
        if ($jenis === 'LS' || $jenis === 'ls') {
            return view('kwitansi.preview-ls', compact('kegiatan', 'konsumsis', 'totalKonsumsi', 'terbilang', 'jenis', 'narasumbers'));
        }

        if ($jenis === 'pembayaran-up' || $jenis === 'pembayaran_up') {
            return view('kwitansi.pembayaran-up', compact('kegiatan', 'konsumsis', 'totalKonsumsi', 'terbilang', 'jenis', 'tanggalDokumen', 'pembuatDaftar', 'narasumbers'));
        }

        // Default UP kwitansi (or honorarium)
        return view('kwitansi.preview', compact('kegiatan', 'konsumsis', 'totalKonsumsi', 'terbilang', 'jenis', 'narasumbers'));
    }

    /**
     * Download kwitansi as PDF
     */
    public function download($kegiatan_id, $jenis)
    {
        // TODO: Implement PDF generation
        // For now, redirect back with message
        return back()->with('info', 'Fitur download PDF dalam pengembangan');
    }

    /**
     * Show daftar hadir form
     */
    public function daftarHadir($id)
    {
        $kegiatan = Kegiatan::with(['unor', 'unitKerja'])->findOrFail($id);
        return view('kwitansi.daftar-hadir', compact('kegiatan'));
    }

    /**
     * Convert number to words in Indonesian
     */
    private function terbilang($angka)
    {
        $angka = abs($angka);
        $huruf = ["", "satu", "dua", "tiga", "empat", "lima", "enam", "tujuh", "delapan", "sembilan", "sepuluh", "sebelas"];
        $temp = "";

        if ($angka < 12) {
            $temp = " " . $huruf[$angka];
        } else if ($angka < 20) {
            $temp = $this->terbilang($angka - 10) . " belas";
        } else if ($angka < 100) {
            $temp = $this->terbilang($angka / 10) . " puluh" . $this->terbilang($angka % 10);
        } else if ($angka < 200) {
            $temp = " seratus" . $this->terbilang($angka - 100);
        } else if ($angka < 1000) {
            $temp = $this->terbilang($angka / 100) . " ratus" . $this->terbilang($angka % 100);
        } else if ($angka < 2000) {
            $temp = " seribu" . $this->terbilang($angka - 1000);
        } else if ($angka < 1000000) {
            $temp = $this->terbilang($angka / 1000) . " ribu" . $this->terbilang($angka % 1000);
        } else if ($angka < 1000000000) {
            $temp = $this->terbilang($angka / 1000000) . " juta" . $this->terbilang($angka % 1000000);
        } else if ($angka < 1000000000000) {
            $temp = $this->terbilang($angka / 1000000000) . " milyar" . $this->terbilang(fmod($angka, 1000000000));
        }

        return $temp;
    }
}
