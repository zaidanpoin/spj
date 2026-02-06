<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vendor extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_vendor',
        'nama_direktur',
        'jabatan',
        'npwp',
        'alamat',
        'bank',
        'rekening',
        'ppn',
    ];

    /**
     * Relationship: One vendor has many konsumsi items
     */
    public function konsumsis()
    {
        return $this->hasMany(Konsumsi::class);
    }

    /**
     * Relationship: Many-to-many with Kegiatan through kegiatan_vendor pivot table
     * Pivot table menyimpan nomor-nomor surat yang berbeda per kombinasi kegiatan-vendor
     * dan data detail vendor yang berbeda per kegiatan
     */
    public function kegiatans()
    {
        return $this->belongsToMany(Kegiatan::class, 'kegiatan_vendor')
            ->withPivot(
                'nomor_berita_acara',
                'nomor_bast',
                'nomor_berita_pembayaran',
                'nama_direktur',
                'jabatan',
                'npwp',
                'alamat',
                'bank',
                'rekening',
                'ppn'
            )
            ->withTimestamps();
    }

    /**
     * Check if vendor has complete data
     */
    public function isComplete()
    {
        return !empty($this->nama_direktur) &&
            !empty($this->jabatan) &&
            !empty($this->npwp) &&
            !empty($this->alamat) &&
            !empty($this->bank) &&
            !empty($this->rekening);
    }
}
