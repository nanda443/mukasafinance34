{{-- resources/views/siswa/show-tagihan.blade.php --}}
@extends('layouts.app')

@section('title', 'Detail Tagihan - ' . $pembayaran->jenisPembayaran->nama)

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Detail Tagihan</h1>
            </div>
            <div class="col-sm-6">
                <a href="{{ route('siswa.tagihan.index') }}" class="btn btn-secondary float-right">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        <div class="d-md-none student-mobile-only mb-3">
            <div class="invoice-mobile-wrap">
                <div class="invoice-mobile-head">
                    <div>
                        <small>Invoice</small>
                        <h3>INV-{{ str_pad($pembayaran->id, 4, '0', STR_PAD_LEFT) }}-{{ $pembayaran->created_at->format('ym') }}</h3>
                    </div>
                    <a href="{{ route('siswa.tagihan.index') }}" class="invoice-mobile-back"><i class="fas fa-arrow-left"></i></a>
                </div>

                <div class="invoice-mobile-status">
                    {!! $pembayaran->status_badge !!}
                    @if($pembayaran->tenggat_waktu)
                        <small>Due by {{ $pembayaran->tenggat_waktu->format('d M Y') }}</small>
                    @endif
                </div>

                <div class="invoice-mobile-title">{{ $pembayaran->jenisPembayaran->nama }}</div>
                <div class="invoice-mobile-amount">Rp {{ number_format($pembayaran->jenisPembayaran->nominal, 0, ',', '.') }}</div>

                <div class="invoice-mobile-meta">
                    <div>
                        <span>Kategori</span>
                        <strong>{{ $pembayaran->jenisPembayaran->kategori }}</strong>
                    </div>
                    <div>
                        <span>Tanggal Bayar</span>
                        <strong>{{ $pembayaran->tanggal_bayar ? $pembayaran->tanggal_bayar->format('d/m/Y') : '-' }}</strong>
                    </div>
                    <div>
                        <span>Upload</span>
                        <strong>{{ $pembayaran->created_at->format('d/m/Y H:i') }}</strong>
                    </div>
                </div>

                <div class="invoice-mobile-note">
                    @if($pembayaran->status == 'pending')
                        Pembayaran Anda sedang menunggu persetujuan bendahara.
                    @elseif($pembayaran->status == 'rejected')
                        Pembayaran ditolak. {{ $pembayaran->alasan_reject ? 'Alasan: ' . $pembayaran->alasan_reject : '' }}
                    @elseif($pembayaran->status == 'approved')
                        Pembayaran sudah diverifikasi dan diterima.
                    @endif
                </div>

                <div class="invoice-mobile-actions">
                    @if($pembayaran->bukti)
                        <a href="{{ asset('storage/' . $pembayaran->bukti) }}" target="_blank" class="btn btn-outline-primary btn-block">
                            <i class="fas fa-file-alt mr-1"></i> Lihat Bukti Pembayaran
                        </a>
                    @endif

                    @if($pembayaran->status == 'rejected')
                        <a href="{{ route('siswa.tagihan.index') }}" class="btn btn-primary btn-block">
                            <i class="fas fa-credit-card mr-1"></i> Bayar Ulang
                        </a>
                    @endif
                </div>
            </div>
        </div>

        <div class="row d-none d-md-flex">
            <div class="col-md-6">
                <!-- Informasi Tagihan -->
                <div class="card card-primary student-mobile-card">
                    <div class="card-header">
                        <h3 class="card-title">Informasi Tagihan</h3>
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
                                        @if($pembayaran->hari_tersisa !== null)
                                            <br>
                                            <small class="text-{{ $pembayaran->hari_tersisa < 0 ? 'danger' : ($pembayaran->hari_tersisa <= 3 ? 'warning' : 'success') }}">
                                                @if($pembayaran->hari_tersisa < 0)
                                                    Terlambat {{ abs($pembayaran->hari_tersisa) }} hari
                                                @elseif($pembayaran->hari_tersisa == 0)
                                                    Hari ini tenggat
                                                @else
                                                    {{ $pembayaran->hari_tersisa }} hari lagi
                                                @endif
                                            </small>
                                        @endif
                                    @else
                                        -
                                    @endif
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>

                <!-- Status Pembayaran -->
                <div class="card card-{{ $pembayaran->status == 'approved' ? 'success' : ($pembayaran->status == 'rejected' ? 'danger' : 'warning') }} student-mobile-card">
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
                            <a href="{{ route('siswa.tagihan.index') }}" class="btn btn-primary">
                                <i class="fas fa-credit-card mr-2"></i> Bayar Ulang
                            </a>
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
                <div class="card card-info student-mobile-card">
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
                <div class="card card-success student-mobile-card">
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

                <!-- Timeline Status -->
                <div class="card student-mobile-card">
                    <div class="card-header">
                        <h3 class="card-title">Timeline Status</h3>
                    </div>
                    <div class="card-body">
                        <div class="timeline">
                            <div class="time-label">
                                <span class="bg-primary">{{ $pembayaran->created_at->format('d M Y') }}</span>
                            </div>
                            <div>
                                <i class="fas fa-upload bg-blue"></i>
                                <div class="timeline-item">
                                    <span class="time"><i class="fas fa-clock"></i> {{ $pembayaran->created_at->format('H:i') }}</span>
                                    <h3 class="timeline-header">Upload Bukti Pembayaran</h3>
                                    <div class="timeline-body">
                                        Bukti pembayaran diupload oleh siswa
                                    </div>
                                </div>
                            </div>

                            @if($pembayaran->status != 'pending')
                            <div class="time-label">
                                <span class="bg-{{ $pembayaran->status == 'approved' ? 'success' : 'danger' }}">{{ $pembayaran->updated_at->format('d M Y') }}</span>
                            </div>
                            <div>
                                <i class="fas fa-{{ $pembayaran->status == 'approved' ? 'check' : 'times' }} bg-{{ $pembayaran->status == 'approved' ? 'success' : 'danger' }}"></i>
                                <div class="timeline-item">
                                    <span class="time"><i class="fas fa-clock"></i> {{ $pembayaran->updated_at->format('H:i') }}</span>
                                    <h3 class="timeline-header">Pembayaran {{ $pembayaran->status == 'approved' ? 'Disetujui' : 'Ditolak' }}</h3>
                                    <div class="timeline-body">
                                        @if($pembayaran->status == 'rejected')
                                        <strong>Alasan:</strong> {{ $pembayaran->alasan_reject }}
                                        @else
                                        Pembayaran telah diverifikasi dan disetujui
                                        @endif
                                    </div>
                                </div>
                            </div>
                            @endif

                            <div>
                                <i class="fas fa-clock bg-gray"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@section('styles')
<style>
.timeline {
    position: relative;
    margin: 0 0 30px 0;
    padding: 0;
    list-style: none;
}
.timeline:before {
    content: '';
    position: absolute;
    top: 0;
    bottom: 0;
    width: 4px;
    background: #ddd;
    left: 31px;
    margin: 0;
    border-radius: 2px;
}
.timeline > .time-label > span {
    font-weight: 600;
    padding: 5px 10px;
    display: inline-block;
    background-color: #e9ecef;
    border-radius: 4px;
}
.timeline > div {
    position: relative;
    margin-bottom: 20px;
}
.timeline > div:before,
.timeline > div:after {
    content: " ";
    display: table;
}
.timeline > div:after {
    clear: both;
}
.timeline > div > .timeline-item {
    box-shadow: 0 1px 1px rgba(0,0,0,0.1);
    border-radius: 3px;
    margin-top: 0;
    background: #fff;
    color: #444;
    margin-left: 60px;
    margin-right: 15px;
    padding: 0;
    position: relative;
}
.timeline > div > .fa,
.timeline > div > .fas {
    width: 30px;
    height: 30px;
    font-size: 15px;
    line-height: 30px;
    position: absolute;
    color: #666;
    background: #d2d6de;
    border-radius: 50%;
    text-align: center;
    left: 18px;
    top: 0;
}

@media (max-width: 767.98px) {
    body.role-siswa .invoice-mobile-wrap {
        background: #fff;
        border-radius: 18px;
        border: 1px solid rgba(14, 116, 144, .18);
        box-shadow: 0 12px 28px rgba(15, 23, 42, .08);
        overflow: hidden;
    }

    body.role-siswa .invoice-mobile-head {
        background: linear-gradient(145deg, #0a5b7a 0%, #1f7da0 54%, #2f8eb2 100%);
        color: #fff;
        padding: .85rem .9rem;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    body.role-siswa .invoice-mobile-head small {
        display: block;
        opacity: .85;
        font-size: .7rem;
        margin-bottom: .1rem;
    }

    body.role-siswa .invoice-mobile-head h3 {
        margin: 0;
        font-size: .92rem;
        font-weight: 700;
        letter-spacing: .02em;
    }

    body.role-siswa .invoice-mobile-back {
        color: #fff;
        width: 30px;
        height: 30px;
        border-radius: 9px;
        background: rgba(255, 255, 255, .2);
        display: inline-flex;
        align-items: center;
        justify-content: center;
        text-decoration: none;
    }

    body.role-siswa .invoice-mobile-status,
    body.role-siswa .invoice-mobile-title,
    body.role-siswa .invoice-mobile-amount,
    body.role-siswa .invoice-mobile-meta,
    body.role-siswa .invoice-mobile-note,
    body.role-siswa .invoice-mobile-actions {
        padding-left: .9rem;
        padding-right: .9rem;
    }

    body.role-siswa .invoice-mobile-status {
        padding-top: .7rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: .5rem;
    }

    body.role-siswa .invoice-mobile-status small {
        color: #0369a1;
        font-size: .69rem;
    }

    body.role-siswa .invoice-mobile-status .badge {
        background: #0284c7;
        color: #fff;
    }

    body.role-siswa .invoice-mobile-title {
        color: #0f172a;
        margin-top: .3rem;
        font-weight: 700;
        font-size: 1.02rem;
    }

    body.role-siswa .invoice-mobile-amount {
        font-size: 2rem;
        font-weight: 800;
        line-height: 1.1;
        color: #0369a1;
        margin: .25rem 0 .55rem;
    }

    body.role-siswa .invoice-mobile-meta {
        display: grid;
        grid-template-columns: 1fr;
        gap: .45rem;
        padding-bottom: .7rem;
        border-bottom: 1px dashed rgba(14, 116, 144, .35);
    }

    body.role-siswa .invoice-mobile-meta span {
        display: block;
        font-size: .68rem;
        color: #64748b;
        margin-bottom: .05rem;
    }

    body.role-siswa .invoice-mobile-meta strong {
        font-size: .8rem;
        color: #0f172a;
    }

    body.role-siswa .invoice-mobile-note {
        padding-top: .65rem;
        font-size: .76rem;
        color: #0f4c64;
        background: #f0f9ff;
        border: 1px solid rgba(14, 116, 144, .2);
        border-radius: 10px;
        margin: .45rem .9rem 0;
        padding: .55rem .6rem;
    }

    body.role-siswa .invoice-mobile-actions {
        padding-top: .6rem;
        padding-bottom: .85rem;
    }

    body.role-siswa .invoice-mobile-actions .btn {
        border-radius: 10px;
        font-size: .78rem;
        font-weight: 700;
    }

    body.role-siswa .invoice-mobile-actions .btn-outline-primary {
        color: #0369a1;
        border-color: rgba(14, 116, 144, .35);
        background: #f0f9ff;
    }

    body.role-siswa .invoice-mobile-actions .btn-primary {
        background: linear-gradient(145deg, #0a5b7a 0%, #1f7da0 54%, #2f8eb2 100%);
        border-color: #0a5b7a;
    }
}
</style>
@endsection