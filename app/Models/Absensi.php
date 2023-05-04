<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class Absensi extends Model
{
    use HasFactory;

    protected $table = 'absensi';

    protected $fillable = [
        'user_id',
        'menit_terlambat',
        'menit_lembur'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function logMasuk()
    {
        return $this->hasOne(LogAbsensi::class, 'absensi_id')->where('tipe', 'MASUK');
    }

    public function logKeluar()
    {
        return $this->hasOne(LogAbsensi::class, 'absensi_id')->where('tipe', 'KELUAR');
    }

    public function izin()
    {
        return $this->hasOne(Perizinan::class, 'absensi_id');
    }
}
