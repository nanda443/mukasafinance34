{{-- resources/views/admin/dashboard.blade.php --}}
@extends('layouts.app')

@section('title', 'Dashboard Admin')

@section('content')
<style>
    .dashboard-admin .info-box-number {
        word-break: break-word;
    }

    @media (max-width: 767.98px) {
        .dashboard-admin .content-header h1 {
            font-size: 1.3rem;
        }

        .dashboard-admin .info-box {
            min-height: 78px;
            margin-bottom: .75rem !important;
        }

        .dashboard-admin .info-box-icon {
            width: 60px;
            font-size: 1.25rem;
        }

        .dashboard-admin .info-box-number {
            font-size: 1rem;
            line-height: 1.2;
        }

        .dashboard-admin .card-header {
            padding: .65rem .9rem;
        }

        .dashboard-admin .card-title {
            font-size: .95rem;
            margin: 0;
        }

        .dashboard-admin .card-body {
            padding: .75rem;
        }
    }
</style>

<div class="dashboard-admin">
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Dashboard Admin</h1>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        <!-- Info boxes -->
        <div class="row">
            <div class="col-12 col-sm-6 col-md-3">
                <div class="info-box mb-3">
                    <span class="info-box-icon bg-info elevation-1"><i class="fas fa-users"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Total Siswa</span>
                        <span class="info-box-number">{{ $totalSiswa ?? 0 }}</span>
                    </div>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-md-3">
                <div class="info-box mb-3">
                    <span class="info-box-icon bg-success elevation-1"><i class="fas fa-money-bill"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Total Pemasukan</span>
                        <span class="info-box-number">Rp {{ number_format($totalPembayaran ?? 0, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-md-3">
                <div class="info-box mb-3">
                    <span class="info-box-icon bg-warning elevation-1"><i class="fas fa-clock"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Menunggu Approve</span>
                        <span class="info-box-number">{{ $pendingPembayaran ?? 0 }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Chart -->
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Grafik Pemasukan Bulanan</h3>
                    </div>
                    <div class="card-body">
                        <canvas id="pemasukanChart" height="100"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const chartCanvas = document.getElementById('pemasukanChart');
        
        if (!chartCanvas) {
            console.error('Chart canvas not found');
            return;
        }
        
        const ctx = chartCanvas.getContext('2d');
        const chartData = [{{ implode(', ', $chartData ?? []) }}];
        
        if (typeof Chart === 'undefined') {
            console.error('Chart.js library not loaded');
            return;
        }
        
        const chart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'],
                datasets: [{
                    label: 'Pemasukan (Rp)',
                    data: chartData,
                    borderColor: 'rgb(75, 192, 192)',
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    borderWidth: 2,
                    tension: 0.3,
                    fill: true,
                    pointBackgroundColor: 'rgb(75, 192, 192)',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return 'Rp ' + value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
                            }
                        }
                    }
                },
                plugins: {
                    legend: {
                        display: true,
                        position: 'top'
                    },
                    tooltip: {
                        mode: 'index',
                        intersect: false,
                        callbacks: {
                            label: function(context) {
                                let label = context.dataset.label || '';
                                if (label) {
                                    label += ': ';
                                }
                                const value = context.parsed.y || 0;
                                label += 'Rp ' + value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
                                return label;
                            }
                        }
                    }
                }
            }
        });
    });
</script>
@endsection
