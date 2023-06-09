<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nama',
        'nip',
        'jabatan',
        'email',
        'password',
        'hak_akses'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function scopeFilter($query)
    {
        $query->where('nama', 'LIKE', '%' . request("cari") . '%')
            ->orWhere('nip', 'LIKE', '%' . request("cari") . '%')
            ->orWhere('jabatan', 'LIKE', '%' . request("cari") . '%')
            ->orWhere('email', 'LIKE', '%' . request("cari") . '%');
    }

    public function logAktifitas()
    {
        return $this->hasMany(LogAktivitas::class);
    }

    public function absensi()
    {
        return $this->hasMany(Absensi::class);
    }
}
