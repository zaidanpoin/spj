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
    ];

    /**
     * Relationship: One vendor has many konsumsi items
     */
    public function konsumsis()
    {
        return $this->hasMany(Konsumsi::class);
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
