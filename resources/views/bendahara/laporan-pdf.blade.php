<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Keuangan</title>
    <style>
        body { 
            font-family: 'DejaVu Sans', Arial, sans-serif; 
            font-size: 12px;
            line-height: 1.4;
        }
        .header { 
            text-align: center; 
            margin-bottom: 20px;
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
        }
        .header h1 { 
            margin: 0; 
            color: #333;
            font-size: 18px;
        }
        .header p { 
            margin: 5px 0; 
            color: #666;
        }
        table { 
            width: 100%; 
            border-collapse: collapse; 
            margin-top: 15px;
            font-size: 10px;
        }
        th, td { 
            border: 1px solid #ddd; 
            padding: 6px; 
            text-align: left; 
        }
        th { 
            background-color: #f5f5f5; 
            font-weight: bold; 
        }
        .total-row { 
            background-color: #f5f5f5; 
            font-weight: bold; 
        }
        .text-right { 
            text-align: right; 
        }
        .text-center { 
            text-align: center; 
        }
        .summary-box { 
            background: #f8f9fa; 
            padding: 10px; 
            border: 1px solid #ddd;
            margin-bottom: 15px;
            border-radius: 3px;
        }
        .footer {
            margin-top: 20px;
            text-align: right;
            font-size: 10px;
            color: #666;
        }
        .page-break {
            page-break-after: always;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>LAPORAN KEUANGAN SEKOLAH</h1>
        <p>Periode: {{ $startDate->format('d/m/Y') }} - {{ $endDate->format('d/m/Y') }}</p>
        <p>Dicetak pada: {{ \Carbon\Carbon::now()->format('d/m/Y H:i') }}</p>
    </div>

    <div class="summary-box">
        <h3 style="margin: 0 0 10px 0;">Ringkasan</h3>
        <p style="margin: 2px 0;"><strong>Total Pemasukan:</strong> Rp {{ number_format($totalPemasukan, 0, ',', '.') }}</p>
        <p style="margin: 2px 0;"><strong>Total Transaksi:</strong> {{ $pembayaran->count() }}</p>
        <p style="margin: 2px 0;"><strong>Rata-rata per Transaksi:</strong> Rp {{ number_format($pembayaran->count() > 0 ? $totalPemasukan / $pembayaran->count() : 0, 0, ',', '.') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="12%">Tanggal</th>
                <th width="18%">Nama Siswa</th>
                <th width="10%">NIS</th>
                <th width="8%">Kelas</th>
                <th width="10%">Jurusan</th>
                <th width="17%">Jenis Pembayaran</th>
                <th width="20%" class="text-right">Nominal</th>
            </tr>
        </thead>
        <tbody>
            @foreach($pembayaran as $index => $item)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td>{{ $item->updated_at->format('d/m/Y H:i') }}</td>
                <td>{{ $item->user->name ?? '-' }}</td>
                <td>{{ $item->user->nis ?? '-' }}</td>
                <td class="text-center">{{ $item->user->kelas ?? '-' }}</td>
                <td class="text-center">{{ $item->user->jurusan ?? '-' }}</td>
                <td>{{ $item->jenisPembayaran->nama ?? '-' }}</td>
                <td class="text-right">Rp {{ number_format($item->nominal, 0, ',', '.') }}</td>
            </tr>
            @endforeach
            
            @if($pembayaran->count() == 0)
            <tr>
                <td colspan="8" class="text-center">Tidak ada data transaksi</td>
            </tr>
            @endif
        </tbody>
        <tfoot>
            <tr class="total-row">
                <td colspan="7" class="text-right"><strong>TOTAL PEMASUKAN:</strong></td>
                <td class="text-right"><strong>Rp {{ number_format($totalPemasukan, 0, ',', '.') }}</strong></td>
            </tr>
        </tfoot>
    </table>

    <div class="footer">
        <p>Dicetak oleh: {{ Auth::user()->name ?? 'System' }}</p>
        <p>Halaman 1</p>
    </div>
</body>
</html>