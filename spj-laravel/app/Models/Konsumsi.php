<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Konsumsi extends Model
{
    use HasFactory;

    protected $table = 'konsumsis';

    protected $fillable = [
        'kegiatan_id',
        'kategori',
        'status',
        'waktu_konsumsi_id',
        'nama_konsumsi',
        'no_kwitansi',
        'jumlah',
        'harga',
        'tanggal_pembelian',
        'vendor_id',
    ];

    protected $casts = [
        'tanggal_pembelian' => 'datetime',
    ];

    public function kegiatan()
    {
        return $this->belongsTo(Kegiatan::class, 'kegiatan_id');
    }

    public function waktuKonsumsi()
    {
        return $this->belongsTo(WaktuKonsumsi::class, 'waktu_konsumsi_id');
    }

    public function vendor()
    {
        return $this->belongsTo(Vendor::class, 'vendor_id');
    }

    // Accessor untuk subtotal (Porsi x Harga)
    public function getSubtotalAttribute()
    {
        return $this->jumlah * $this->harga;
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
}
