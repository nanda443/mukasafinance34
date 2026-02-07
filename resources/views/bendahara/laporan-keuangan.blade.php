@extends('layouts.app')

@section('title', 'Laporan Keuangan')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Laporan Keuangan</h3>
                </div>
                <div class="card-body">
                    <!-- Filter Form -->
                    <div class="row mb-4">
                        <div class="col-md-12">
                            <form id="filterForm" method="GET" class="form-inline">
                                <div class="form-group mr-3">
                                    <label for="start_date" class="mr-2">Tanggal Mulai:</label>
                                    <input type="date" name="start_date" id="start_date" 
                                           class="form-control" 
                                           value="{{ request('start_date', \Carbon\Carbon::now()->startOfMonth()->format('Y-m-d')) }}">
                                </div>
                                <div class="form-group mr-3">
                                    <label for="end_date" class="mr-2">Tanggal Akhir:</label>
                                    <input type="date" name="end_date" id="end_date" 
                                           class="form-control" 
                                           value="{{ request('end_date', \Carbon\Carbon::now()->endOfMonth()->format('Y-m-d')) }}">
                                </div>
                                <div class="form-group mr-3">
                                    <label for="kelas" class="mr-2">Kelas:</label>
                                    <select name="kelas" id="kelas" class="form-control">
                                        <option value="">Semua Kelas</option>
                                        @foreach($kelasList as $k)
                                            <option value="{{ $k }}" {{ request('kelas') == $k ? 'selected' : '' }}>
                                                {{ $k }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group mr-3">
                                    <label for="jurusan" class="mr-2">Jurusan:</label>
                                    <select name="jurusan" id="jurusan" class="form-control">
                                        <option value="">Semua Jurusan</option>
                                        @foreach($jurusanList as $j)
                                            <option value="{{ $j }}" {{ request('jurusan') == $j ? 'selected' : '' }}>
                                                {{ $j }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group mr-3">
                                    <label for="jenis_pembayaran_id" class="mr-2">Jenis Pembayaran:</label>
                                    <select name="jenis_pembayaran_id" id="jenis_pembayaran_id" class="form-control">
                                        <option value="">Semua Jenis</option>
                                        @foreach($jenisPembayaranList as $jenis)
                                            <option value="{{ $jenis->id }}" {{ request('jenis_pembayaran_id') == $jenis->id ? 'selected' : '' }}>
                                                {{ $jenis->nama }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <button type="submit" class="btn btn-primary mr-2">
                                    <i class="fas fa-filter"></i> Filter
                                </button>
                                <button type="button" id="exportExcel" class="btn btn-success mr-2">
                                    <i class="fas fa-file-excel"></i> Excel
                                </button>
                                <button type="button" id="exportPdf" class="btn btn-danger mr-2">
                                    <i class="fas fa-file-pdf"></i> PDF
                                </button>
                            </form>
                        </div>
                    </div>

                    <!-- Summary Cards -->
                    <div class="row mb-4">
                        <div class="col-md-4">
                            <div class="info-box bg-success">
                                <span class="info-box-icon"><i class="fas fa-money-bill-wave"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Total Pemasukan</span>
                                    <span class="info-box-number">Rp {{ number_format($totalPemasukan, 0, ',', '.') }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="info-box bg-info">
                                <span class="info-box-icon"><i class="fas fa-list"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Total Transaksi</span>
                                    <span class="info-box-number">{{ $pembayaran->count() }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="info-box bg-warning">
                                <span class="info-box-icon"><i class="fas fa-calendar"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Periode</span>
                                    <span class="info-box-number">
                                        {{ $startDate->format('d/m/Y') }} - {{ $endDate->format('d/m/Y') }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Summary Tables -->
                    <div class="row mb-4">
                        <!-- Ringkasan per Jenis Pembayaran -->
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title">Ringkasan per Jenis Pembayaran</h4>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-striped">
                                            <thead>
                                                <tr>
                                                    <th>Jenis Pembayaran</th>
                                                    <th>Jumlah</th>
                                                    <th>Total</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse($ringkasanJenis as $ringkasan)
                                                    <tr>
                                                        <td>{{ $ringkasan['nama'] }}</td>
                                                        <td>{{ $ringkasan['jumlah'] }}</td>
                                                        <td>Rp {{ number_format($ringkasan['total'], 0, ',', '.') }}</td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="3" class="text-center">Tidak ada data</td>
                                                    </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Ringkasan per Kelas -->
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title">Ringkasan per Kelas</h4>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-striped">
                                            <thead>
                                                <tr>
                                                    <th>Kelas</th>
                                                    <th>Jumlah</th>
                                                    <th>Total</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse($ringkasanKelas as $ringkasan)
                                                    <tr>
                                                        <td>Kelas {{ $ringkasan['kelas'] }}</td>
                                                        <td>{{ $ringkasan['jumlah'] }}</td>
                                                        <td>Rp {{ number_format($ringkasan['total'], 0, ',', '.') }}</td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="3" class="text-center">Tidak ada data</td>
                                                    </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Data Table -->
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Detail Transaksi</h4>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped" id="laporanTable">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Tanggal</th>
                                            <th>Nama Siswa</th>
                                            <th>NIS</th>
                                            <th>Kelas/Jurusan</th>
                                            <th>Jenis Pembayaran</th>
                                            <th>Nominal</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($pembayaran as $index => $item)
                                            <tr>
                                                <td>{{ $index + 1 }}</td>
                                                <td>{{ $item->updated_at->format('d/m/Y H:i') }}</td>
                                                <td>{{ $item->user->name ?? '-' }}</td>
                                                <td>{{ $item->user->nis ?? '-' }}</td>
                                                <td>{{ ($item->user->kelas ?? '-') . ' ' . ($item->user->jurusan ?? '-') }}</td>
                                                <td>{{ $item->jenisPembayaran->nama ?? '-' }}</td>
                                                <td>Rp {{ number_format($item->nominal, 0, ',', '.') }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr class="bg-light">
                                            <td colspan="6" class="text-right"><strong>Total:</strong></td>
                                            <td><strong>Rp {{ number_format($totalPemasukan, 0, ',', '.') }}</strong></td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
$(document).ready(function() {
    // Export Excel
    $('#exportExcel').click(function() {
        exportLaporan('excel');
    });

    // Export PDF
    $('#exportPdf').click(function() {
        exportLaporan('pdf');
    });

    function exportLaporan(format) {
        // Get current filter values
        const startDate = $('#start_date').val();
        const endDate = $('#end_date').val();
        const kelas = $('#kelas').val();
        const jurusan = $('#jurusan').val();
        const jenisPembayaranId = $('#jenis_pembayaran_id').val();
        
        // Create export URL
        let exportUrl = '{{ route("bendahara.laporan-keuangan.export") }}?export=' + format;
        
        if (startDate) exportUrl += '&start_date=' + startDate;
        if (endDate) exportUrl += '&end_date=' + endDate;
        if (kelas) exportUrl += '&kelas=' + kelas;
        if (jurusan) exportUrl += '&jurusan=' + jurusan;
        if (jenisPembayaranId) exportUrl += '&jenis_pembayaran_id=' + jenisPembayaranId;
        
        // Redirect to export URL
        window.location.href = exportUrl;
    }

    // Initialize DataTable
    if ($.fn.DataTable.isDataTable('#laporanTable')) {
        $('#laporanTable').DataTable().destroy();
    }
    
    $('#laporanTable').DataTable({
        "paging": true,
        "lengthChange": true,
        "searching": true,
        "ordering": true,
        "info": true,
        "autoWidth": false,
        "responsive": true,
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.10.25/i18n/Indonesian.json"
        }
    });
});
</script>
@endsection