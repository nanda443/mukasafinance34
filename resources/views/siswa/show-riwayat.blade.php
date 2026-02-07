@extends('layouts.app')

@section('title', 'Detail Riwayat - ' . $pembayaran->jenisPembayaran->nama)

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Detail Riwayat Pembayaran</h1>
            </div>
            <div class="col-sm-6">
                <a href="{{ route('siswa.riwayat.index') }}" class="btn btn-secondary float-right">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-6">
                <!-- Informasi Tagihan -->
                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title">Informasi Pembayaran</h3>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered">
                            <tr>
                                <th width="40%">Jenis Pembayaran</th>
                                <td>{{ $pembayaran->jenisPembayaran->nama }}</td>
                            </tr>
                            <tr>
                                <th>Kategori</th>
                                <td>{{ $pembayaran->jenisPembayaran->kategori }}</td>
                            </tr>
                            <tr>
                                <th>Nominal</th>
                                <td class="font-weight-bold text-success">
                                    Rp {{ number_format($pembayaran->jenisPembayaran->nominal, 0, ',', '.') }}
                                </td>
                            </tr>
                            <tr>
                                <th>Tanggal Bayar</th>
                                <td>{{ $pembayaran->tanggal_bayar ? $pembayaran->tanggal_bayar->format('d/m/Y') : '-' }}</td>
                            </tr>
                            <tr>
                                <th>Tenggat Waktu</th>
                                <td>
                                    @if($pembayaran->tenggat_waktu)
                                        {{ $pembayaran->tenggat_waktu->format('d/m/Y') }}
                                    @else
                                        -
                                    @endif
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>

                <!-- Status Pembayaran -->
                <div class="card card-{{ $pembayaran->status == 'approved' ? 'success' : ($pembayaran->status == 'rejected' ? 'danger' : 'warning') }}">
                    <div class="card-header">
                        <h3 class="card-title">Status Pembayaran</h3>
                    </div>
                    <div class="card-body">
                        <div class="text-center">
                            <h3>{!! $pembayaran->status_badge !!}</h3>
                            
                            @if($pembayaran->status == 'pending')
                            <div class="alert alert-warning mt-3">
                                <i class="fas fa-clock mr-2"></i>
                                Pembayaran Anda sedang menunggu persetujuan dari bendahara.
                            </div>
                            @elseif($pembayaran->status == 'rejected')
                            <div class="alert alert-danger mt-3">
                                <i class="fas fa-times-circle mr-2"></i>
                                <strong>Pembayaran Ditolak</strong><br>
                                <strong>Alasan:</strong> {{ $pembayaran->alasan_reject }}
                            </div>
                            @elseif($pembayaran->status == 'approved')
                            <div class="alert alert-success mt-3">
                                <i class="fas fa-check-circle mr-2"></i>
                                Pembayaran Anda telah disetujui dan diterima.
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <!-- Informasi Upload -->
                <div class="card card-info">
                    <div class="card-header">
                        <h3 class="card-title">Informasi Upload</h3>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered">
                            <tr>
                                <th width="40%">Keterangan Siswa</th>
                                <td>{{ $pembayaran->keterangan ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th>Keterangan Admin</th>
                                <td>{{ $pembayaran->keterangan_admin ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th>Tanggal Upload</th>
                                <td>{{ $pembayaran->created_at->format('d/m/Y H:i') }}</td>
                            </tr>
                            <tr>
                                <th>Terakhir Diupdate</th>
                                <td>{{ $pembayaran->updated_at->format('d/m/Y H:i') }}</td>
                            </tr>
                        </table>
                    </div>
                </div>

                <!-- Bukti Pembayaran -->
                <div class="card card-success">
                    <div class="card-header">
                        <h3 class="card-title">Bukti Pembayaran</h3>
                    </div>
                    <div class="card-body text-center">
                        @if($pembayaran->bukti)
                            @if($pembayaran->isImage)
                                <img src="{{ asset('storage/' . $pembayaran->bukti) }}" 
                                     alt="Bukti Pembayaran" 
                                     class="img-fluid rounded border" 
                                     style="max-height: 300px;">
                                <br><br>
                                <a href="{{ asset('storage/' . $pembayaran->bukti) }}" 
                                   target="_blank" 
                                   class="btn btn-outline-primary">
                                    <i class="fas fa-expand"></i> Lihat Full Size
                                </a>
                            @elseif($pembayaran->isPdf)
                                <div class="p-4 border rounded">
                                    <i class="fas fa-file-pdf fa-3x text-danger mb-3"></i>
                                    <br>
                                    <a href="{{ asset('storage/' . $pembayaran->bukti) }}" 
                                       target="_blank" 
                                       class="btn btn-danger">
                                        <i class="fas fa-download"></i> Download Bukti PDF
                                    </a>
                                </div>
                            @else
                                <div class="p-4 border rounded">
                                    <i class="fas fa-file fa-3x text-info mb-3"></i>
                                    <br>
                                    <a href="{{ asset('storage/' . $pembayaran->bukti) }}" 
                                       target="_blank" 
                                       class="btn btn-info">
                                        <i class="fas fa-download"></i> Download File
                                    </a>
                                </div>
                            @endif
                        @else
                            <p class="text-muted">Tidak ada bukti pembayaran</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
