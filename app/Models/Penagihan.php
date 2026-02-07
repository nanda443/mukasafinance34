<?php
// app/Models/Penagihan.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Penagihan extends Model
{
    use HasFactory;

    protected $table = 'penagihans';


   protected $fillable = [
        'judul',
        'deskripsi',
        'nominal',
        'jenis',
        'target',
        'kelas',
        'jurusan',
        'target_siswa',
        'tenggat_waktu',
        'status',
        'created_by'
    ];

    protected $casts = [
        'tenggat_waktu' => 'date',
        'status' => 'boolean',
        'nominal' => 'decimal:2'
    ];

    public function getSiswaTarget()
    {
        if ($this->target === 'individu') {
            return User::whereIn('id', $this->target_siswa ?? [])
                ->where('role', 'siswa')
                ->get();
        } else {
            $query = User::where('role', 'siswa');
            
            if ($this->kelas) {
                $query->where('kelas', $this->kelas);
            }
            
            if ($this->jurusan) {
                $query->where('jurusan', $this->jurusan);
            }
            
            return $query->get();
        }
    }

    public function jenisPembayaran()
    {
        return $this->hasOne(JenisPembayaran::class, 'penagihan_id');
    }

    public function pembayarans()
    {
        return $this->hasMany(Pembayaran::class);
    }

    // Accessor untuk nominal formatted
    public function getNominalFormattedAttribute()
    {
        return 'Rp ' . number_format($this->nominal, 0, ',', '.');
    }

    // Scope untuk penagihan aktif
    public function scopeAktif($query)
    {
        return $query->where('status', true);
    }

    // Scope untuk penagihan bulanan
    public function scopeBulanan($query)
    {
        return $query->where('jenis', 'bulanan');
    }

    // Scope untuk penagihan tahunan
    public function scopeTahunan($query)
    {
        return $query->where('jenis', 'tahunan');
    }
}