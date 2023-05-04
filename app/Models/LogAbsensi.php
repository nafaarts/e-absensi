<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LogAbsensi extends Model
{
    use HasFactory;

    protected $table = 'log_absensi';

    protected $fillable = [
        'absensi_id',
        'tipe',
        'latitude',
        'longitude',
        'foto'
    ];

    public function absensi()
    {
        return $this->belongsTo(Absensi::class, 'absensi_id');
    }
}
