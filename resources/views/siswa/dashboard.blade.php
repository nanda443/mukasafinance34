{{-- resources/views/siswa/dashboard.blade.php --}}
@extends('layouts.app')

@section('title', 'Dashboard Siswa')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Dashboard Siswa</h1>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        <div class="d-md-none student-mobile-only">
            <h2 class="student-mobile-title">Welcome, {{ Str::of(Auth::user()->name)->before(' ') }}</h2>
            <p class="student-mobile-subtitle">Manage your school expenses</p>

            <div class="student-mobile-hero">
                <div class="student-mobile-hero-label">Total Dibayar</div>
                <div class="student-mobile-hero-amount">Rp {{ number_format($totalBayar, 0, ',', '.') }}</div>
                <div class="student-mobile-hero-meta">
                    <span class="student-mobile-hero-date">{{ now()->translatedFormat('d F Y') }}</span>
                    <a href="{{ route('siswa.tagihan.index') }}" class="student-mobile-pay-btn">Pay Now</a>
                </div>
            </div>

            <div class="student-mobile-section-head">
                <h3>Students</h3>
                <a href="{{ route('siswa.profile.index') }}">View All</a>
            </div>
            <div class="student-mobile-card p-3 d-flex align-items-center justify-content-between">
                <div>
                    <strong class="d-block">{{ Auth::user()->name }}</strong>
                    <small class="text-muted">{{ Auth::user()->kelas }} {{ Auth::user()->jurusan }}</small>
                </div>
                <a href="{{ route('siswa.profile.index') }}" class="text-muted"><i class="fas fa-chevron-right"></i></a>
            </div>

            <div class="student-mobile-section-head">
                <h3>Recent Transactions</h3>
                <a href="{{ route('siswa.riwayat.index') }}">History</a>
            </div>
            <div class="student-mobile-list mb-3">
                @forelse($pembayaranTerbaru as $pembayaran)
                    <a href="{{ route('siswa.tagihan.show', $pembayaran->id) }}" class="student-mobile-item text-decoration-none">
                        <span class="student-mobile-icon"><i class="fas fa-file-invoice"></i></span>
                        <div class="student-mobile-main">
                            <strong>{{ $pembayaran->jenisPembayaran->nama }}</strong>
                            <span>{{ $pembayaran->tanggal_bayar ? $pembayaran->tanggal_bayar->format('d M Y') : 'Belum ada tanggal bayar' }}</span>
                        </div>
                        <div class="student-mobile-side">
                            Rp {{ number_format($pembayaran->jenisPembayaran->nominal, 0, ',', '.') }}
                            <div>{!! $pembayaran->status_badge !!}</div>
                        </div>
                    </a>
                @empty
                    <div class="p-3 text-center text-muted small">Belum ada riwayat pembayaran.</div>
                @endforelse
            </div>
        </div>

        <!-- Info boxes -->
        <div class="row d-none d-md-flex">
            <div class="col-12 col-sm-6 col-md-4">
                <div class="info-box">
                    <span class="info-box-icon bg-info elevation-1"><i class="fas fa-user-graduate"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Kelas & Jurusan</span>
                        <span class="info-box-number">{{ Auth::user()->kelas }} {{ Auth::user()->jurusan }}</span>
                    </div>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-md-4">
                <div class="info-box mb-3">
                    <span class="info-box-icon bg-success elevation-1"><i class="fas fa-money-bill"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Total Dibayar</span>
                        <span class="info-box-number">Rp {{ number_format($totalBayar, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-md-4">
                <div class="info-box mb-3">
                    <span class="info-box-icon bg-warning elevation-1"><i class="fas fa-clock"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Menunggu Konfirmasi</span>
                        <span class="info-box-number">{{ $pendingCount }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pembayaran Terbaru -->
        <div class="row d-none d-md-flex">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Pembayaran Terbaru</h3>
                    </div>
                    <div class="card-body">
                        @if($pembayaranTerbaru->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Jenis Pembayaran</th>
                                        <th>Tanggal</th>
                                        <th>Nominal</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($pembayaranTerbaru as $pembayaran)
                                    <tr>
                                        <td>{{ $pembayaran->jenisPembayaran->nama }}</td>
                                        <td>{{ $pembayaran->tanggal_bayar !== null ? $pembayaran->tanggal_bayar->format('d/m/Y') : '-' }}</td>
                                        <td>{{ $pembayaran->jenisPembayaran->nominal_formatted }}</td>
                                        <td>{!! $pembayaran->status_badge !!}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @else
                        <p class="text-muted">Belum ada riwayat pembayaran.</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection