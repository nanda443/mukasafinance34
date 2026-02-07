<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Pemasukan - SMA Muhammadiyah Kasihan</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.4;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
        }
        .header h2 {
            margin: 0;
            color: #333;
        }
        .header h3 {
            margin: 5px 0;
            color: #666;
        }
        .info {
            margin-bottom: 15px;
        }
        .table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }
        .table th {
            background-color: #f8f9fa;
            border: 1px solid #dee2e6;
            padding: 8px;
            text-align: left;
            font-weight: bold;
        }
        .table td {
            border: 1px solid #dee2e6;
            padding: 8px;
        }
        .table tbody tr:nth-child(even) {
            background-color: #f8f9fa;
        }
        .total {
            font-weight: bold;
            font-size: 14px;
            text-align: right;
            margin-top: 20px;
            padding: 10px;
            background-color: #e9ecef;
            border-radius: 5px;
        }
        .footer {
            margin-top: 30px;
            text-align: right;
            font-size: 10px;
            color: #666;
        }
        .text-right {
            text-align: right;
        }
        .text-center {
            text-align: center;
        }
        .badge {
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 10px;
            font-weight: bold;
        }
        .badge-success {
            background-color: #28a745;
            color: white;
        }
    </style>
</head>
<body>
    <div class="header">
        <h2>SMA MUHAMMADIYAH KASIHAN</h2>
        <h3>LAPORAN PEMASUKAN KEUANGAN</h3>
        <p>Periode: 
            @if($tanggal_awal != '-' && $tanggal_akhir != '-')
                {{ $tanggal_awal }} - {{ $tanggal_akhir }}
            @else
                Semua Periode
            @endif
        </p>
    </div>

    <div class="info">
        <p><strong>Filter yang diterapkan:</strong></p>
        <ul>
            @if($kelas)
            <li>Kelas: {{ $kelas }}</li>
            @endif
            @if($jurusan)
            <li>Jurusan: {{ $jurusan }}</li>
            @endif
            @if($jenis_pembayaran)
            <li>Jenis Pembayaran: {{ $jenis_pembayaran }}</li>
            @endif
        </ul>
    </div>

    <table class="table">
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="15%">Nama Siswa</th>
                <th width="10%">NIS</th>
                <th width="8%">Kelas</th>
                <th width="8%">Jurusan</th>
                <th width="15%">Jenis Pembayaran</th>
                <th width="12%">Nominal</th>
                <th width="10%">Tanggal Bayar</th>
                <th width="8%">Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($laporan as $key => $item)
            <tr>
                <td class="text-center">{{ $key + 1 }}</td>
                <td>{{ $item->user->name }}</td>
                <td>{{ $item->user->nis }}</td>
                <td class="text-center">{{ $item->user->kelas }}</td>
                <td class="text-center">{{ $item->user->jurusan }}</td>
                <td>{{ $item->jenisPembayaran->nama }}</td>
                <td class="text-right">Rp {{ number_format($item->jenisPembayaran->nominal, 0, ',', '.') }}</td>
                <td class="text-center">{{ $item->tanggal_bayar ? $item->tanggal_bayar->format('d/m/Y') : '-' }}</td>
                <td class="text-center">
                    <span class="badge badge-success">DISETUJUI</span>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="total">
        TOTAL PEMASUKAN: Rp {{ number_format($total, 0, ',', '.') }}
    </div>

    <div class="footer">
        <p>Dicetak pada: {{ date('d/m/Y H:i:s') }}</p>
        <p>Oleh: {{ Auth::user()->name }} ({{ Auth::user()->role }})</p>
    </div>
</body>
</html>