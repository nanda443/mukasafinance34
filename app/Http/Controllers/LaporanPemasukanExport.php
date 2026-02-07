<?php
// app/Exports/LaporanPemasukanExport.php
namespace App\Exports;

use App\Models\Pembayaran;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class LaporanPemasukanExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithTitle
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
            'NO',
            'NAMA SISWA',
            'NIS',
            'KELAS',
            'JURUSAN',
            'JENIS PEMBAYARAN',
            'NOMINAL',
            'TANGGAL BAYAR',
            'STATUS'
        ];
    }

    public function map($pembayaran): array
    {
        static $number = 1;
        return [
            $number++,
            $pembayaran->user->name ?? '-',
            $pembayaran->user->nis ?? '-',
            $pembayaran->user->kelas ?? '-',
            $pembayaran->user->jurusan ?? '-',
            $pembayaran->jenisPembayaran->nama ?? '-',
            $pembayaran->jenisPembayaran->nominal ?? 0,
            $pembayaran->tanggal_bayar ? $pembayaran->tanggal_bayar->format('d/m/Y') : '-',
            'DISETUJUI'
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $count = $this->collection()->count() + 1;
        
        return [
            // Style the first row as bold text
            1 => [
                'font' => ['bold' => true],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['argb' => 'FFD3D3D3']
                ]
            ],
            // Set border for all cells
            'A1:I' . $count => [
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    ],
                ],
            ],
            // Set number format for nominal column
            'G2:G' . $count => [
                'numberFormat' => [
                    'formatCode' => '#,##0'
                ]
            ],
        ];
    }

    public function title(): string
    {
        return 'Laporan Pemasukan';
    }
}