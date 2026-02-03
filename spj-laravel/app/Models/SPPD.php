<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SPPD extends Model
{
    use HasFactory;

    protected $table = 'sppd';

    protected $fillable = [
        'kegiatan_id',
        'lembar',
        'kode_no',
        'no',
        'nospd',
        'tgl',
        'tglspd',
        'nama',
        'nip',
        'pangkat_gol',
        'jabatan',
        'eselon',
        'maksud',
        'kendaraan',
        'tujuan',
        'tgl_brkt',
        'tgl_kbl',
        'catatan',
        'perintah_nama',
        'perintah_nip',
        'perintah_jabatan',
        'ppk_nama',
        'ppk_nip',
        'kdppk',
        'bendahara_nama',
        'bendahara_nip',
        'pembuat_nama',
        'pembuat_nip',
        'tingkat_biaya',
        'alat_angkut',
        'tempat_berangkat',
        'akun',
        'tgl_kwit',
        'kembali_baru',
        'jenis_tujuan',
        'jenis_board',
        'tgl_selesai',
        'grup',
        'nominatif_id',
        'I_dari',
        'I_ke',
        'I_tgl',
        'I_instansi_nama',
        'I_nama',
        'I_nip',
        'II_tiba',
        'II_tiba_tgl',
        'II_tiba_instansi_nama',
        'II_tiba_nama',
        'II_tiba_nip',
        'II_dari',
        'II_ke',
        'II_tgl',
        'II_instansi_nama',
        'II_nama',
        'II_nip',
        'III_tiba',
        'III_tiba_tgl',
        'III_tiba_instansi_nama',
        'III_tiba_nama',
        'III_tiba_nip',
        'III_dari',
        'III_ke',
        'III_tgl',
        'III_instansi_nama',
        'III_nama',
        'III_nip',
        'IV_tiba',
        'IV_tiba_tgl',
        'IV_tiba_instansi_nama',
        'IV_tiba_nama',
        'IV_tiba_nip',
        'IV_dari',
        'IV_ke',
        'IV_tgl',
        'IV_instansi_nama',
        'IV_nama',
        'IV_nip',
        'V_tiba',
        'V_tiba_tgl',
        'V_tiba_instansi_nama',
        'V_tiba_nama',
        'V_tiba_nip',
        'V_dari',
        'V_ke',
        'V_tgl',
        'V_instansi_nama',
        'V_nama',
        'V_nip',
        'VI_tiba',
        'VI_tiba_tgl',
        'VI_tiba_instansi_nama',
        'VI_tiba_nama',
        'VI_tiba_nip',
        'update_date',
        'update_user',
        'panjar',
        'kdmak',
        'mak',
        'is_pns',
        'status',
    ];

    protected $casts = [
        'tgl' => 'date',
        'tglspd' => 'date',
        'tgl_brkt' => 'date',
        'tgl_kbl' => 'date',
        'tgl_kwit' => 'date',
        'tgl_selesai' => 'date',
        'I_tgl' => 'date',
        'II_tiba_tgl' => 'date',
        'II_tgl' => 'date',
        'III_tiba_tgl' => 'date',
        'III_tgl' => 'date',
        'IV_tiba_tgl' => 'date',
        'IV_tgl' => 'date',
        'V_tiba_tgl' => 'date',
        'V_tgl' => 'date',
        'VI_tiba_tgl' => 'date',
        'update_date' => 'date',
    ];

    public function kegiatan()
    {
        return $this->belongsTo(Kegiatan::class, 'kegiatan_id');
    }

    public function ppk()
    {
        return $this->belongsTo(PPK::class, 'ppk_nip', 'nip');
    }

    public function bendahara()
    {
        return $this->belongsTo(Bendahara::class, 'bendahara_nip', 'nip');
    }

    // Scope untuk filter status
    public function scopeDraft($query)
    {
        return $query->where('status', 'draft');
    }

    public function scopeFinal($query)
    {
        return $query->where('status', 'final');
    }

    // Helper untuk menghitung lama perjalanan (dalam hari)
    public function getLamaPerjananAttribute()
    {
        if ($this->tgl_brkt && $this->tgl_kbl) {
            return $this->tgl_brkt->diffInDays($this->tgl_kbl) + 1;
        }
        return 0;
    }
}
