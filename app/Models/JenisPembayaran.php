<?php
// app/Models/JenisPembayaran.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class JenisPembayaran extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'nama',
        'nominal',
        'kategori',
        'keterangan',
        'status'
    ];

    protected $casts = [
        'nominal' => 'integer',
        'status' => 'boolean'
    ];

    /**
     * Relasi ke pembayaran
     */
    public function pembayarans()
    {
        return $this->hasMany(Pembayaran::class, 'jenis_pembayaran_id');
    }

    /**
     * Scope untuk jenis pembayaran aktif
     */
    public function scopeAktif($query)
    {
        return $query->where('status', true);
    }

    /**
     * Accessor untuk format nominal
     */
    public function getNominalFormattedAttribute()
    {
        return 'Rp ' . number_format($this->nominal, 0, ',', '.');
    }

    /**
     * Accessor untuk status label
     */
    public function getStatusLabelAttribute()
    {
        return $this->status ? 'Aktif' : 'Nonaktif';
    }

    /**
     * Accessor untuk badge status
     */
    public function getStatusBadgeAttribute()
    {
        return $this->status 
            ? '<span class="badge badge-success">Aktif</span>'
            : '<span class="badge badge-danger">Nonaktif</span>';
    }
}