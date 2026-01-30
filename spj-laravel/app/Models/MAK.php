<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MAK extends Model
{
    use HasFactory;

    protected $table = 'mak';

    protected $fillable = [
        'tahun',
        'nama',
        'kode',
        'satker',
        'akun',
        'paket',
        'nip_ppk',
    ];

    /**
     * Get the PPK that owns the MAK
     */
    public function ppk()
    {
        return $this->belongsTo(PPK::class, 'nip_ppk', 'nip');
    }
}
