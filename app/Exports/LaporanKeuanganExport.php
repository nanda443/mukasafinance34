<?php

namespace App\Exports;

use App\Models\Pembayaran;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class LaporanKeuanganExport implements FromCollection, WithHeadings, WithMapping
{
    protected $request;

    public function __construct($request)
    {
        $this->request = $request;
    }

    public function collection()
    {
        $query = Pembayaran::with(['user', 'jenisPembayaran'])
            ->where('status', 'approved');

        if ($this->request->bulan) {
            $query->whereMonth('tanggal_bayar', $this->request->bulan);
        }

        if ($this->request->tahun) {
            $query->whereYear('tanggal_bayar', $this->request->tahun);
        }

        if ($this->request->jenis_pembayaran) {
            $query->where('jenis_pembayaran_id', $this->request->jenis_pembayaran);
        }

        return $query->orderBy('tanggal_bayar', 'desc')->get();
    }

    public function headings(): array
    {
        return [
            'No',
            'Tanggal Bayar',
            'Nama Siswa',
            'Kelas',
            'Jenis Pembayaran',
            'Jumlah Bayar',
            'Status'
        ];
    }

    public function map($pembayaran): array
    {
        return [
            $pembayaran->id,
            $pembayaran->tanggal_bayar ? $pembayaran->tanggal_bayar->format('d/m/Y') : '-',
            $pembayaran->user->name ?? 'N/A',
            $pembayaran->user->kelas ?? 'N/A',
            $pembayaran->jenisPembayaran->nama ?? 'N/A',
            $pembayaran->jumlah_bayar,
            $pembayaran->status
        ];
    }
}