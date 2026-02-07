@extends('layouts.app')

@section('title', 'Detail Jenis Pembayaran')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Detail Jenis Pembayaran</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item">
                        <a href="{{ route('bendahara.dashboard') }}">Dashboard</a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{ route('bendahara.jenis-pembayaran.index') }}">Jenis Pembayaran</a>
                    </li>
                    <li class="breadcrumb-item active">Detail</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        {{-- Alert Section --}}
        @if(session('success'))
            <div class="alert alert-success alert-dismissible">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                {{ session('error') }}
            </div>
        @endif

        <div class="row">
            <!-- Informasi Jenis Pembayaran -->
            <div class="col-md-4">
                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title">Informasi Pembayaran</h3>
                    </div>
                    <div class="card-body">
                        @if(isset($jenisPembayaran) && $jenisPembayaran)
                            <table class="table table-bordered">
                                <tr>
                                    <th width="40%">Nama Pembayaran</th>
                                    <td>{{ $jenisPembayaran->nama }}</td>
                                </tr>
                                <tr>
                                    <th>Nominal</th>
                                    <td class="font-weight-bold text-success">
                                        Rp {{ number_format($jenisPembayaran->nominal, 0, ',', '.') }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>Kategori</th>
                                    <td>
                                        <span class="badge badge-{{ $jenisPembayaran->kategori == 'SPP' ? 'primary' : ($jenisPembayaran->kategori == 'Gedung' ? 'success' : ($jenisPembayaran->kategori == 'Praktikum' ? 'info' : 'warning')) }}">
                                            {{ $jenisPembayaran->kategori }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Status</th>
                                    <td>
                                        @if($jenisPembayaran->status)
                                            <span class="badge badge-success">Aktif</span>
                                        @else
                                            <span class="badge badge-danger">Nonaktif</span>
                                        @endif
                                    </td>
                                </tr>
                                @if($jenisPembayaran->keterangan)
                                <tr>
                                    <th>Keterangan</th>
                                    <td>{{ $jenisPembayaran->keterangan }}</td>
                                </tr>
                                @endif
                                <tr>
                                    <th>Dibuat Pada</th>
                                    <td>{{ $jenisPembayaran->created_at->format('d/m/Y H:i') }}</td>
                                </tr>
                            </table>
                        @else
                            <div class="alert alert-danger">
                                <i class="fas fa-exclamation-triangle"></i> Data jenis pembayaran tidak ditemukan.
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Statistik Card -->
                <div class="card card-info">
                    <div class="card-header">
                        <h3 class="card-title">Statistik</h3>
                    </div>
                    <div class="card-body">
                        @if(isset($jenisPembayaran) && $jenisPembayaran)
                            <div class="text-center">
                                <div class="mb-3">
                                    <h4 class="text-primary">Rp {{ number_format($totalPemasukan, 0, ',', '.') }}</h4>
                                    <small class="text-muted">Total Pemasukan</small>
                                </div>
                                <div class="mb-3">
                                    <h4 class="text-success">{{ $totalBayar ?? 0 }}</h4>
                                    <small class="text-muted">Jumlah Pembayar</small>
                                </div>
                                <div class="mb-3">
                                    <h4 class="text-info">{{ $totalSiswa ?? 0 }}</h4>
                                    <small class="text-muted">Total Siswa</small>
                                </div>
                                @if($totalSiswa > 0)
                                <div class="progress mb-3">
                                    <div class="progress-bar bg-success" role="progressbar" 
                                         style="width: {{ ($totalBayar / $totalSiswa) * 100 }}%"
                                         aria-valuenow="{{ ($totalBayar / $totalSiswa) * 100 }}" 
                                         aria-valuemin="0" 
                                         aria-valuemax="100">
                                        {{ number_format(($totalBayar / $totalSiswa) * 100, 1) }}%
                                    </div>
                                </div>
                                <small class="text-muted">Persentase Pembayaran</small>
                                @endif
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Daftar Pembayaran -->
            <div class="col-md-8">
                <div class="card card-secondary">
                    <div class="card-header">
                        <h3 class="card-title">Riwayat Pembayaran</h3>
                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                <i class="fas fa-minus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        @if(isset($jenisPembayaran) && $jenisPembayaran && $jenisPembayaran->pembayarans->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped table-hover" id="table-pembayaran">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Nama Siswa</th>
                                            <th>NIS</th>
                                            <th>Kelas</th>
                                            <th>Tanggal Bayar</th>
                                            <th>Status</th>
                                            <th>Nominal</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($jenisPembayaran->pembayarans as $index => $pembayaran)
                                            @if($pembayaran && $pembayaran->user)
                                            <tr>
                                                <td>{{ $index + 1 }}</td>
                                                <td>{{ $pembayaran->user->name ?? 'N/A' }}</td>
                                                <td>{{ $pembayaran->user->nis ?? 'N/A' }}</td>
                                                <td>{{ $pembayaran->user->kelas ?? 'N/A' }} {{ $pembayaran->user->jurusan ?? '' }}</td>
                                                <td>{{ $pembayaran->created_at->format('d/m/Y H:i') }}</td>
                                                <td>
                                                    <span class="badge badge-success">Approved</span>
                                                </td>
                                                <td class="text-success font-weight-bold">
                                                    Rp {{ number_format($jenisPembayaran->nominal, 0, ',', '.') }}
                                                </td>
                                            </tr>
                                            @endif
                                        @endforeach
                                    </tbody>
                                    @if($jenisPembayaran->pembayarans->count() > 0)
                                    <tfoot>
                                        <tr class="bg-light">
                                            <td colspan="6" class="text-right font-weight-bold">Total Pemasukan:</td>
                                            <td class="text-success font-weight-bold">
                                                Rp {{ number_format($totalPemasukan, 0, ',', '.') }}
                                            </td>
                                        </tr>
                                    </tfoot>
                                    @endif
                                </table>
                            </div>
                        @else
                            <div class="alert alert-info text-center">
                                <i class="fas fa-info-circle"></i> 
                                Belum ada riwayat pembayaran untuk jenis pembayaran ini.
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Informasi Tambahan -->
                <div class="card card-warning">
                    <div class="card-header">
                        <h3 class="card-title">Informasi Tambahan</h3>
                    </div>
                    <div class="card-body">
                        @if(isset($jenisPembayaran) && $jenisPembayaran)
                            <div class="row text-center">
                                <div class="col-4">
                                    <div class="info-box bg-light">
                                        <div class="info-box-content">
                                            <span class="info-box-text">Siswa Belum Bayar</span>
                                            <span class="info-box-number">
                                                {{ max(0, $totalSiswa - $totalBayar) }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="info-box bg-light">
                                        <div class="info-box-content">
                                            <span class="info-box-text">Potensi Pemasukan</span>
                                            <span class="info-box-number text-warning">
                                                Rp {{ number_format(max(0, $totalSiswa - $totalBayar) * $jenisPembayaran->nominal, 0, ',', '.') }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="info-box bg-light">
                                        <div class="info-box-content">
                                            <span class="info-box-text">Rata-rata per Siswa</span>
                                            <span class="info-box-number text-info">
                                                @if($totalBayar > 0)
                                                    Rp {{ number_format($totalPemasukan / $totalBayar, 0, ',', '.') }}
                                                @else
                                                    Rp 0
                                                @endif
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Back Button -->
        <div class="row">
            <div class="col-12">
                <a href="{{ route('bendahara.jenis-pembayaran.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Kembali ke Daftar
                </a>
            </div>
        </div>
    </div>
</section>
@endsection

@push('styles')
<style>
    .info-box {
        min-height: 80px;
        border-radius: 5px;
        padding: 10px;
    }
    .info-box-text {
        font-size: 0.9em;
        font-weight: 600;
    }
    .info-box-number {
        font-size: 1.2em;
        font-weight: bold;
    }
    .table th {
        background-color: #f8f9fa;
        font-weight: 600;
    }
</style>
@endpush

@push('scripts')
<script>
    $(document).ready(function() {
        // Initialize DataTable for pembayaran table
        $('#table-pembayaran').DataTable({
            "paging": true,
            "lengthChange": true,
            "searching": true,
            "ordering": true,
            "info": true,
            "autoWidth": false,
            "responsive": true,
            "language": {
                "url": "//cdn.datatables.net/plug-ins/1.10.25/i18n/Indonesian.json"
            },
            "order": [[4, 'desc']] // Order by tanggal bayar descending
        });

        // Auto-hide alerts
        setTimeout(function() {
            $('.alert').fadeOut('slow');
        }, 5000);
    });
</script>
@endpush
