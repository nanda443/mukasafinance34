<?php
// app/Models/Pembayaran.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Pembayaran extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'jenis_pembayaran_id',
        'tanggal_bayar',
        'tenggat_waktu',
        'bukti',
        'keterangan',
        'keterangan_admin',
        'status',
        'alasan_reject'
    ];

    protected $casts = [
        'tanggal_bayar' => 'date',
        'tenggat_waktu' => 'date'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function jenisPembayaran()
    {
        return $this->belongsTo(JenisPembayaran::class);
    }

    public function getStatusBadgeAttribute()
    {
        $statuses = [
            'pending' => 'warning',
            'approved' => 'success',
            'rejected' => 'danger'
        ];

        $statusText = [
            'pending' => 'Menunggu',
            'approved' => 'Disetujui',
            'rejected' => 'Ditolak'
        ];

        return '<span class="badge badge-' . $statuses[$this->status] . '">' . $statusText[$this->status] . '</span>';
    }

    public function getBuktiUrlAttribute()
    {
        return $this->bukti ? asset('storage/' . $this->bukti) : null;
    }

    public function getIsImageAttribute()
    {
        if (!$this->bukti) return false;
        
        $extension = pathinfo($this->bukti, PATHINFO_EXTENSION);
        return in_array(strtolower($extension), ['jpg', 'jpeg', 'png', 'gif']);
    }

    public function getIsPdfAttribute()
    {
        if (!$this->bukti) return false;
        
        $extension = pathinfo($this->bukti, PATHINFO_EXTENSION);
        return strtolower($extension) === 'pdf';
    }

    public function getStatusTenggatAttribute()
    {
        if (!$this->tenggat_waktu) return null;
        
        $now = Carbon::now();
        $tenggat = Carbon::parse($this->tenggat_waktu);
        
        if ($this->status === 'approved') {
            return 'lunas';
        } elseif ($tenggat->isPast()) {
            return 'terlambat';
        } elseif ($tenggat->diffInDays($now) <= 3) {
            return 'mendekati';
        } else {
            return 'normal';
        }
    }

    public function getTenggatBadgeAttribute()
    {
        if (!$this->tenggat_waktu) return '-';

        $status = $this->status_tenggat;
        $tenggat = Carbon::parse($this->tenggat_waktu)->format('d/m/Y');
        
        $badges = [
            'lunas' => 'success',
            'terlambat' => 'danger',
            'mendekati' => 'warning',
            'normal' => 'info'
        ];

        $texts = [
            'lunas' => 'Lunas',
            'terlambat' => 'Terlambat',
            'mendekati' => 'Mendekati Tenggat',
            'normal' => 'Normal'
        ];

        return '<span class="badge badge-' . $badges[$status] . '">' . $texts[$status] . ' (' . $tenggat . ')</span>';
    }

    public function getHariTersisaAttribute()
    {
        if (!$this->tenggat_waktu) return null;
        
        $now = Carbon::now();
        $tenggat = Carbon::parse($this->tenggat_waktu);
        
        if ($tenggat->isPast()) {
            return -$tenggat->diffInDays($now);
        } else {
            return $now->diffInDays($tenggat, false);
        }
    }
}