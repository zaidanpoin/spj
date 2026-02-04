<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SBM extends Model
{
    protected $table = 'sbm';

    protected $fillable = [
        'jenis',
        'kelas',
        'item',
        'satuan_sing',
        'satuan_desk',
        'nilai',
        'update_date',
        'update_user',
        'thang',
    ];

    protected $casts = [
        'update_date' => 'date',
        'thang' => 'integer',
    ];
}
