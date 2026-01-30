<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pegawai extends Model
{
    protected $fillable = [
        'nip',
        'nama',
        'jabatan',
        'unor',
        'kd_unor',
        'unker',
        'kd_unker',
        'pangkat',
        'golongan',
    ];

    /**
     * Get the user associated with this pegawai
     */
    public function user()
    {
        return $this->hasOne(User::class, 'nip', 'nip');
    }
}
