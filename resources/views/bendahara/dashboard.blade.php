{{-- resources/views/bendahara/dashboard.blade.php --}}
@extends('layouts.app')

@section('title', 'Dashboard Bendahara')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Dashboard Bendahara</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item active">Dashboard</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        <!-- Info Boxes -->
        <div class="row">
            <div class="col-lg-3 col-6">
                <div class="small-box bg-info">
                    <div class="inner">
                        <h3>Rp {{ number_format($totalPemasukan, 0, ',', '.') }}</h3>
                        <p>Total Pemasukan</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-money-bill-wave"></i>
                    </div>
                    <a href="{{ route('bendahara.laporan-keuangan.index') }}" class="small-box-footer">
                        More info <i class="fas fa-arrow-circle-right"></i>
                    </a>
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <div class="small-box bg-warning">
                    <div class="inner">
                        <h3>{{ $pendingCount }}</h3>
                        <p>Menunggu Approval</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-clock"></i>
                    </div>
                    <a href="{{ route('bendahara.approval-pembayaran.index') }}" class="small-box-footer">
                        More info <i class="fas fa-arrow-circle-right"></i>
                    </a>
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <div class="small-box bg-success">
                    <div class="inner">
                        <h3>{{ $approvedCount }}</h3>
                        <p>Pembayaran Disetujui</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <a href="{{ route('bendahara.laporan-keuangan.index') }}" class="small-box-footer">
                        More info <i class="fas fa-arrow-circle-right"></i>
                    </a>
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <div class="small-box bg-danger">
                    <div class="inner">
                        <h3>{{ $rejectedCount }}</h3>
                        <p>Pembayaran Ditolak</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-times-circle"></i>
                    </div>
                    <a href="{{ route('bendahara.laporan-keuangan.index') }}" class="small-box-footer">
                        More info <i class="fas fa-arrow-circle-right"></i>
                    </a>
                </div>
            </div>
        </div>

        <!-- Alert Boxes -->
        <div class="row">
            @if($pembayaranMendekatiTenggat > 0)
            <div class="col-md-6">
                <div class="alert alert-warning alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <h5><i class="icon fas fa-exclamation-triangle"></i> Peringatan!</h5>
                    Terdapat <strong>{{ $pembayaranMendekatiTenggat }}</strong> pembayaran yang mendekati tenggat waktu.
                    <a href="{{ route('bendahara.approval-pembayaran.index') }}" class="alert-link">Lihat detail</a>
                </div>
            </div>
            @endif

            @if($pembayaranTerlambat > 0)
            <div class="col-md-6">
                <div class="alert alert-danger alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <h5><i class="icon fas fa-ban"></i> Perhatian!</h5>
                    Terdapat <strong>{{ $pembayaranTerlambat }}</strong> pembayaran yang sudah melewati tenggat waktu.
                    <a href="{{ route('bendahara.approval-pembayaran.index') }}" class="alert-link">Lihat detail</a>
                </div>
            </div>
            @endif
        </div>

        <!-- Charts & Statistics -->
        <div class="row">
            <!-- Chart Pemasukan 6 Bulan Terakhir -->
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Pemasukan 6 Bulan Terakhir</h3>
                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                <i class="fas fa-minus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <canvas id="pemasukanChart" height="250"></canvas>
                    </div>
                </div>
            </div>

            <!-- Status Pembayaran -->
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Status Pembayaran</h3>
                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                <i class="fas fa-minus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <canvas id="statusChart" height="250"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions & Recent Activities -->
        <div class="row">
            <!-- Quick Actions -->
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Aksi Cepat</h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <a href="{{ route('bendahara.approval-pembayaran.index') }}" class="btn btn-primary btn-block">
                                    <i class="fas fa-check-circle mr-2"></i>
                                    Approval Pembayaran
                                </a>
                            </div>
                            <div class="col-md-6 mb-3">
                                <a href="{{ route('bendahara.data-siswa.index') }}" class="btn btn-success btn-block">
                                    <i class="fas fa-users mr-2"></i>
                                    Data Siswa
                                </a>
                            </div>
                            <div class="col-md-6 mb-3">
                                <a href="{{ route('bendahara.jenis-pembayaran.index') }}" class="btn btn-info btn-block">
                                    <i class="fas fa-money-bill-wave mr-2"></i>
                                    Jenis Pembayaran
                                </a>
                            </div>
                            <div class="col-md-6 mb-3">
                                <a href="{{ route('bendahara.laporan-keuangan.index') }}" class="btn btn-warning btn-block">
                                    <i class="fas fa-chart-bar mr-2"></i>
                                    Laporan Keuangan
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Activities -->
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Aktivitas Terbaru</h3>
                        <div class="card-tools">
                            <span class="badge badge-primary">Updated Just Now</span>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <ul class="nav nav-pills flex-column">
                            @if($chartData['top_siswa']->count() > 0)
                                @foreach($chartData['top_siswa'] as $siswa)
                                <li class="nav-item">
                                    <a href="{{ route('bendahara.data-siswa.show', $siswa->id) }}" class="nav-link">
                                        <i class="fas fa-user-graduate mr-2"></i>
                                        {{ $siswa->name }}
                                        <span class="float-right text-success">
                                            <i class="fas fa-arrow-up"></i>
                                            Rp {{ number_format($siswa->total_pembayaran, 0, ',', '.') }}
                                        </span>
                                    </a>
                                </li>
                                @endforeach
                            @else
                                <li class="nav-item">
                                    <a href="#" class="nav-link">
                                        <i class="fas fa-info-circle mr-2"></i>
                                        Belum ada aktivitas pembayaran
                                    </a>
                                </li>
                            @endif
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <!-- Additional Statistics -->
        <div class="row">
            <!-- Jenis Pembayaran Terpopuler -->
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Jenis Pembayaran Terpopuler</h3>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>Jenis Pembayaran</th>
                                        <th>Jumlah Pembayaran</th>
                                        <th>Total Pemasukan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($chartData['jenis_pembayaran'] as $jenis)
                                    <tr>
                                        <td>{{ $jenis['nama'] }}</td>
                                        <td class="text-center">{{ $jenis['jumlah'] }}</td>
                                        <td class="text-right">Rp {{ number_format($jenis['total'], 0, ',', '.') }}</td>
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

            <!-- Statistik Siswa -->
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Statistik Siswa</h3>
                    </div>
                    <div class="card-body">
                        <div class="row text-center">
                            <div class="col-4">
                                <div class="info-box bg-light">
                                    <div class="info-box-content">
                                        <span class="info-box-text text-center text-muted">Total Siswa</span>
                                        <span class="info-box-number text-center text-primary">
                                            {{ $chartData['status_siswa']['total'] }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="info-box bg-light">
                                    <div class="info-box-content">
                                        <span class="info-box-text text-center text-muted">Lunas</span>
                                        <span class="info-box-number text-center text-success">
                                            {{ $chartData['status_siswa']['lunas'] }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="info-box bg-light">
                                    <div class="info-box-content">
                                        <span class="info-box-text text-center text-muted">Belum Lunas</span>
                                        <span class="info-box-number text-center text-danger">
                                            {{ $chartData['status_siswa']['belum_lunas'] }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="progress mt-3">
                            @php
                                $persentaseLunas = $chartData['status_siswa']['total'] > 0 ? 
                                    ($chartData['status_siswa']['lunas'] / $chartData['status_siswa']['total']) * 100 : 0;
                            @endphp
                            <div class="progress-bar bg-success" style="width: {{ $persentaseLunas }}%">
                                {{ number_format($persentaseLunas, 1) }}%
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
$(document).ready(function() {
    // Chart Pemasukan 6 Bulan Terakhir
    var pemasukanCtx = document.getElementById('pemasukanChart').getContext('2d');
    var pemasukanChart = new Chart(pemasukanCtx, {
        type: 'line',
        data: {
            labels: @json($chartData['months']),
            datasets: [{
                label: 'Pemasukan (Rp)',
                data: @json($chartData['pemasukan']),
                backgroundColor: 'rgba(54, 162, 235, 0.2)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 2,
                tension: 0.4,
                fill: true
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    display: true,
                    position: 'top'
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return 'Rp ' + context.parsed.y.toLocaleString('id-ID');
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return 'Rp ' + value.toLocaleString('id-ID');
                        }
                    }
                }
            }
        }
    });

    // Chart Status Pembayaran
    var statusCtx = document.getElementById('statusChart').getContext('2d');
    var statusChart = new Chart(statusCtx, {
        type: 'doughnut',
        data: {
            labels: ['Disetujui', 'Menunggu', 'Ditolak'],
            datasets: [{
                data: [
                    {{ $chartData['status']['approved'] }},
                    {{ $chartData['status']['pending'] }},
                    {{ $chartData['status']['rejected'] }}
                ],
                backgroundColor: [
                    'rgba(40, 167, 69, 0.8)',
                    'rgba(255, 193, 7, 0.8)',
                    'rgba(220, 53, 69, 0.8)'
                ],
                borderColor: [
                    'rgba(40, 167, 69, 1)',
                    'rgba(255, 193, 7, 1)',
                    'rgba(220, 53, 69, 1)'
                ],
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });

    // Auto refresh charts on window resize
    $(window).resize(function() {
        pemasukanChart.resize();
        statusChart.resize();
    });
});
</script>
@endsection