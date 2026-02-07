<?php
// app/Models/User.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;
    
    protected $dates = ['deleted_at'];
    
    protected $fillable = [
        'name',
        'email',
        'nis',
        'kelas',
        'jurusan',
        'role',
        'password',
        'foto',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function pembayarans()
    {
        return $this->hasMany(Pembayaran::class);
    }

    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function isBendahara()
    {
        return $this->role === 'bendahara';
    }

    public function isSiswa()
    {
        return $this->role === 'siswa';
    }

    public function getKelasJurusanAttribute()
    {
        return $this->kelas . ' ' . $this->jurusan;
    }

    // Scope untuk filter role
    public function scopeAdmin($query)
    {
        return $query->where('role', 'admin');
    }

    public function scopeBendahara($query)
    {
        return $query->where('role', 'bendahara');
    }

    public function scopeSiswa($query)
    {
        return $query->where('role', 'siswa');
    }

    public function scopeStaff($query)
    {
        return $query->whereIn('role', ['admin', 'bendahara']);
    }
}