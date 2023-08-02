<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Perizinan extends Model
{
    use HasFactory;

    protected $table = 'perizinan';

    protected $fillable = [
        'absensi_id',
        'surat_izin',
        'kategori_izin',
        'alasan_izin',
        'jumlah_hari',
        'status_izin',
        'di_lihat',
        'created_at',
        'updated_at'
    ];

    public function absensi()
    {
        return $this->belongsTo(Absensi::class, 'absensi_id');
    }
}
