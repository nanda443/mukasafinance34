<?php
// app/Exports/LaporanPemasukanExport.php

namespace App\Exports;

use App\Models\Pembayaran;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class LaporanPemasukanExport implements FromCollection, WithHeadings, WithMapping
{
    protected $request;

    public function __construct($request)
    {
        $this->request = $request;
    }

    public function collection()
    {
        return Pembayaran::with(['user', 'jenisPembayaran'])
            ->where('pembayarans.status', 'approved')
            ->whereHas('user')
            ->whereHas('jenisPembayaran')
            ->when($this->request->tanggal_awal, function($query) {
                return $query->whereDate('pembayarans.tanggal_bayar', '>=', $this->request->tanggal_awal);
            })
            ->when($this->request->tanggal_akhir, function($query) {
                return $query->whereDate('pembayarans.tanggal_bayar', '<=', $this->request->tanggal_akhir);
            })
            ->when($this->request->kelas, function($query) {
                return $query->whereHas('user', function($q) {
                    $q->where('kelas', $this->request->kelas);
                });
            })
            ->when($this->request->jurusan, function($query) {
                return $query->whereHas('user', function($q) {
                    $q->where('jurusan', $this->request->jurusan);
                });
            })
            ->when($this->request->jenis_pembayaran, function($query) {
                return $query->where('jenis_pembayaran_id', $this->request->jenis_pembayaran);
            })
            ->orderBy('pembayarans.tanggal_bayar', 'desc')
            ->get();
    }

    public function headings(): array
    {
        return [
            'No',
            'Nama Siswa',
            'NIS',
            'Kelas',
            'Jurusan',
            'Jenis Pembayaran',
            'Nominal',
            'Tanggal Bayar',
            'Status'
        ];
    }

    public function map($pembayaran): array
    {
        return [
            $pembayaran->id,
            $pembayaran->user->name ?? 'N/A',
            $pembayaran->user->nis ?? 'N/A',
            $pembayaran->user->kelas ?? 'N/A',
            $pembayaran->user->jurusan ?? 'N/A',
            $pembayaran->jenisPembayaran->nama ?? 'N/A',
            $pembayaran->jenisPembayaran->nominal ?? 0,
            $pembayaran->tanggal_bayar ? $pembayaran->tanggal_bayar->format('d/m/Y') : $pembayaran->created_at->format('d/m/Y'),
            'DISETUJUI'
        ];
    }
}