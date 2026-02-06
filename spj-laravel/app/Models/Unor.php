<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Unor extends Model
{
    use HasFactory;

    protected $table = 'unors';

    protected $fillable = [
        'kode_unor',
        'nama_unor',
        'alamat',
    ];

    public function unitKerjas()
    {
        return $this->hasMany(UnitKerja::class, 'unor_id');
    }

    public function kegiatans()
    {
        return $this->hasMany(Kegiatan::class, 'unor_id');
    }
}
