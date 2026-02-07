<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class SiswaTemplateExport implements FromArray, WithHeadings, ShouldAutoSize, WithStyles
{
    public function array(): array
    {
        return [
            ['Ahmad Santoso', '12345', 'ahmad@contoh.com', '10', 'IPA'],
            ['Budi Raharjo', '12346', 'budi@contoh.com', '11', 'IPS'],
        ];
    }

    public function headings(): array
    {
        return [
            'Nama',
            'NIS',
            'Email',
            'Kelas',
            'Jurusan',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            // Style the first row as bold text.
            1    => ['font' => ['bold' => true]],
        ];
    }
}
