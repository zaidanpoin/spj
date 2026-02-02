<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Narasumber extends Model
{
    use HasFactory;

    protected $table = 'narasumbers'; // FIX: plural

    protected $fillable = [
        'kegiatan_id',
        'nama_narasumber',
        'jenis',
        'golongan_jabatan',
        'npwp',
        'jumlah_jam',
        'tarif_per_jam',
        'tarif_pph21',
        'honorarium_bruto',
        'pph21',
        'honorarium_netto',
        'status',
    ];

    protected $casts = [
        'tarif_pph21' => 'string',
        'jumlah_jam' => 'integer',
        'tarif_per_jam' => 'integer',
        'honorarium_bruto' => 'integer',
        'pph21' => 'integer',
        'honorarium_netto' => 'integer',
    ];

    /**
     * Relationship: Narasumber belongs to Kegiatan
     */
    public function kegiatan()
    {
        return $this->belongsTo(Kegiatan::class);
    }

    /**
     * Relationship: Narasumber belongs to SBM Honorarium
     */
    public function sbmHonorarium()
    {
        return $this->belongsTo(SBMHonorariumNarasumber::class, 'golongan_jabatan', 'golongan_jabatan');
    }
}
