<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Kegiatan extends Model
{
    use HasFactory, LogsActivity;

    protected $table = 'kegiatans';

    protected $fillable = [
        'unor_id',
        'unker_id',
        'unit_kerja_id',
        'mak_id',
        'ppk_id',
        'bendahara_id',
        'created_by',
        'nama_kegiatan',
        'uraian_kegiatan',
        'tanggal_mulai',
        'tanggal_selesai',
        'jumlah_peserta',
        'provinsi_id',
        'detail_lokasi',
        'file_laporan',
    ];

    protected $casts = [
        'tanggal_mulai' => 'date',
        'tanggal_selesai' => 'date',
    ];

    public function unor()
    {
        return $this->belongsTo(Unor::class, 'unor_id');
    }

    public function unitKerja()
    {
        return $this->belongsTo(UnitKerja::class, 'unit_kerja_id');
    }

    public function mak()
    {
        return $this->belongsTo(MAK::class, 'mak_id');
    }

    public function ppk()
    {
        return $this->belongsTo(PPK::class, 'ppk_id');
    }

    public function bendahara()
    {
        return $this->belongsTo(Bendahara::class, 'bendahara_id');
    }

    public function provinsi()
    {
        return $this->belongsTo(\App\Models\SatuanBiayaKonsumsiProvinsi::class, 'provinsi_id');
    }

    public function konsumsis()
    {
        return $this->hasMany(Konsumsi::class, 'kegiatan_id');
    }

    public function kwitansiBelanjas()
    {
        return $this->hasMany(KwitansiBelanja::class, 'kegiatan_id');
    }

    public function creator()
    {
        return $this->belongsTo(\App\Models\User::class, 'created_by');
    }

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class, 'created_by');
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['nama_kegiatan', 'uraian_kegiatan', 'unit_kerja_id', 'unor_id', 'tanggal_mulai', 'tanggal_selesai', 'jumlah_peserta', 'provinsi_id', 'detail_lokasi'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs()
            ->setDescriptionForEvent(fn(string $eventName) => "Kegiatan {$eventName}");
    }
}
