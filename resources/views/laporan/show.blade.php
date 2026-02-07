{{-- resources/views/laporan/show.blade.php --}}
@extends('layouts.app')

@section('title', 'Detail Transaksi')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Detail Transaksi</h1>
            </div>
            <div class="col-sm-6">
                <a href="{{ route('laporan.index') }}" class="btn btn-secondary float-right">
                    <i class="fas fa-arrow-left"></i> Kembali ke Laporan
                </a>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-6">
                <!-- Informasi Pembayaran -->
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
                                <th>Nominal</th>
                                <td class="font-weight-bold text-success">
                                    Rp {{ number_format($pembayaran->jenisPembayaran->nominal, 0, ',', '.') }}
                                </td>
                            </tr>
                            <tr>
                                <th>Tanggal Bayar</th>
                                <td>{{ $pembayaran->tanggal_bayar->format('d/m/Y') }}</td>
                            </tr>
                            <tr>
                                <th>Status</th>
                                <td>
                                    <span class="badge badge-success">DISETUJUI</span>
                                </td>
                            </tr>
                            <tr>
                                <th>Keterangan</th>
                                <td>{{ $pembayaran->keterangan ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th>Tanggal Disetujui</th>
                                <td>{{ $pembayaran->updated_at->format('d/m/Y H:i') }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <!-- Informasi Siswa -->
                <div class="card card-info">
                    <div class="card-header">
                        <h3 class="card-title">Informasi Siswa</h3>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered">
                            <tr>
                                <th width="40%">Nama Siswa</th>
                                <td>{{ $pembayaran->user->name }}</td>
                            </tr>
                            <tr>
                                <th>NIS</th>
                                <td>{{ $pembayaran->user->nis }}</td>
                            </tr>
                            <tr>
                                <th>Kelas</th>
                                <td>{{ $pembayaran->user->kelas }}</td>
                            </tr>
                            <tr>
                                <th>Jurusan</th>
                                <td>{{ $pembayaran->user->jurusan }}</td>
                            </tr>
                            <tr>
                                <th>Email</th>
                                <td>{{ $pembayaran->user->email ?? '-' }}</td>
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
                                     style="max-height: 200px;">
                                <br><br>
                                <a href="{{ asset('storage/' . $pembayaran->bukti) }}" 
                                   target="_blank" 
                                   class="btn btn-outline-primary btn-sm">
                                    <i class="fas fa-expand"></i> Lihat Full Size
                                </a>
                            @elseif($pembayaran->isPdf)
                                <div class="p-3 border rounded">
                                    <i class="fas fa-file-pdf fa-3x text-danger mb-3"></i>
                                    <br>
                                    <a href="{{ asset('storage/' . $pembayaran->bukti) }}" 
                                       target="_blank" 
                                       class="btn btn-danger btn-sm">
                                        <i class="fas fa-download"></i> Download PDF
                                    </a>
                                </div>
                            @else
                                <p class="text-muted">File bukti pembayaran</p>
                                <a href="{{ asset('storage/' . $pembayaran->bukti) }}" 
                                   target="_blank" 
                                   class="btn btn-info btn-sm">
                                    <i class="fas fa-download"></i> Download File
                                </a>
                            @endif
                        @else
                            <p class="text-muted">Tidak ada bukti pembayaran</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body text-center">
                        <a href="{{ route('laporan.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Kembali
                        </a>
                        <button onclick="window.print()" class="btn btn-primary">
                            <i class="fas fa-print"></i> Cetak
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@section('scripts')
<script>
    // Print styling
    function printDetail() {
        window.print();
    }
</script>

<style>
    @media print {
        .main-header, .main-sidebar, .content-header, .card-header .card-tools, .btn {
            display: none !important;
        }
        .content-wrapper {
            margin: 0 !important;
        }
        .card {
            border: none !important;
            box-shadow: none !important;
        }
        .card-header {
            background: white !important;
            color: black !important;
            border-bottom: 2px solid #000 !important;
        }
    }
</style>
@endsection