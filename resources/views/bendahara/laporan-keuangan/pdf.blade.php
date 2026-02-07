<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Keuangan</title>
    <style>
        body { font-family: Arial, sans-serif; }
        .header { text-align: center; margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #000; padding: 8px; text-align: left; }
        th { background-color: #f0f0f0; }
        .total { font-weight: bold; background-color: #e6ffe6; }
        .text-right { text-align: right; }
    </style>
</head>
<body>
    <div class="header">
        <h1>LAPORAN KEUANGAN</h1>
        <h3>SMA Muhammadiyah Kasihan</h3>
        @if($bulan && $tahun)
            <p>Periode: {{ DateTime::createFromFormat('!m', $bulan)->format('F') }} {{ $tahun }}</p>
        @else
            <p>Periode: Semua Waktu</p>
        @endif
        <p>Total Pemasukan: Rp {{ number_format($totalPemasukan, 0, ',', '.') }}</p>
    </div>
    
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Tanggal Bayar</th>
                <th>Nama Siswa</th>
                <th>Kelas</th>
                <th>Jenis Pembayaran</th>
                <th class="text-right">Jumlah Bayar</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($pembayaran as $key => $item)
            <tr>
                <td>{{ $key + 1 }}</td>
                <td>{{ $item->tanggal_bayar ? $item->tanggal_bayar->format('d/m/Y') : '-' }}</td>
                <td>{{ $item->user->name ?? 'N/A' }}</td>
                <td>{{ $item->user->kelas ?? 'N/A' }}</td>
                <td>{{ $item->jenisPembayaran->nama ?? 'N/A' }}</td>
                <td class="text-right">Rp {{ number_format($item->jumlah_bayar, 0, ',', '.') }}</td>
                <td>{{ strtoupper($item->status) }}</td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr class="total">
                <td colspan="5">TOTAL PEMASUKAN</td>
                <td class="text-right">Rp {{ number_format($totalPemasukan, 0, ',', '.') }}</td>
                <td></td>
            </tr>
        </tfoot>
    </table>

    <div style="margin-top: 30px; text-align: right;">
        <p>Kasihan, {{ date('d F Y') }}</p>
        <br><br><br>
        <p>Bendahara</p>
    </div>
</body>
</html>