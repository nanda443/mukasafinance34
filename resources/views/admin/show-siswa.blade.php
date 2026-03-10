@extends('layouts.app')

@section('title', 'Detail Siswa - ' . $siswa->name)

@section('content')
<div class="show-siswa-page">
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Detail Siswa</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <!-- PERBAIKAN: Gunakan route yang benar berdasarkan role user -->
                    @if(Auth::user()->role == 'bendahara')
                        <li class="breadcrumb-item"><a href="{{ route('bendahara.data-siswa.index') }}">Data Siswa</a></li>
                    @elseif(Auth::user()->role == 'admin')
                        <li class="breadcrumb-item"><a href="{{ route('admin.data-siswa.index') }}">Data Siswa</a></li>
                    @else
                        <li class="breadcrumb-item"><a href="{{ url('/') }}">Dashboard</a></li>
                    @endif
                    <li class="breadcrumb-item active">Detail Siswa</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        <div class="row">
            <!-- Informasi Siswa -->
            <div class="col-md-4">
                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title">Informasi Siswa</h3>
                    </div>
                    <div class="card-body">
                        <div class="text-center mb-3">
                            <div class="d-inline-flex align-items-center justify-content-center" style="width: 100px; height: 100px;">
                                @if($siswa->foto)
                                    <img src="{{ asset('storage/' . $siswa->foto) }}" class="img-circle elevation-2" style="width: 100px; height: 100px; object-fit: cover;">
                                @else
                                    <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center" style="width: 100px; height: 100px;">
                                        <i class="fas fa-user fa-3x text-white"></i>
                                    </div>
                                @endif
                            </div>
                        </div>
                        
                        <table class="table table-borderless">
                            <tr>
                                <td width="40%"><strong>NIS</strong></td>
                                <td>{{ $siswa->nis }}</td>
                            </tr>
                            <tr>
                                <td><strong>Nama</strong></td>
                                <td>{{ $siswa->name }}</td>
                            </tr>
                            <tr>
                                <td><strong>Email</strong></td>
                                <td>{{ $siswa->email }}</td>
                            </tr>
                            <tr>
                                <td><strong>Kelas</strong></td>
                                <td>Kelas {{ $siswa->kelas }}</td>
                            </tr>
                            <tr>
                                <td><strong>Jurusan</strong></td>
                                <td><span class="badge badge-info">{{ $siswa->jurusan }}</span></td>
                            </tr>
                            <tr>
                                <td><strong>Tanggal Daftar</strong></td>
                                <td>{{ $siswa->created_at->format('d/m/Y') }}</td>
                            </tr>
                        </table>
                    </div>
                </div>

                <!-- Ringkasan Pembayaran -->
                <div class="card card-success">
                    <div class="card-header">
                        <h3 class="card-title">Ringkasan Pembayaran</h3>
                    </div>
                    <div class="card-body">
                        <div class="row text-center">
                            <div class="col-6">
                                <div class="border-right">
                                    <h4 class="text-success">Rp {{ number_format($totalPembayaran, 0, ',', '.') }}</h4>
                                    <small class="text-muted">Total Dibayar</small>
                                </div>
                            </div>
                            <div class="col-6">
                                <h4 class="text-primary">Rp {{ number_format($totalTagihan, 0, ',', '.') }}</h4>
                                <small class="text-muted">Total Tagihan</small>
                            </div>
                        </div>
                        
                        <div class="progress mt-3" style="height: 20px;">
                            @php
                                $progressPercentage = $totalTagihan > 0 ? ($totalPembayaran / $totalTagihan) * 100 : 0;
                                $progressClass = $progressPercentage == 100 ? 'bg-success' : ($progressPercentage >= 50 ? 'bg-warning' : 'bg-danger');
                            @endphp
                            <div class="progress-bar {{ $progressClass }} progress-bar-striped" 
                                 role="progressbar" 
                                 style="width: {{ $progressPercentage }}%"
                                 aria-valuenow="{{ $progressPercentage }}" 
                                 aria-valuemin="0" 
                                 aria-valuemax="100">
                                {{ number_format($progressPercentage, 1) }}%
                            </div>
                        </div>
                        
                        <div class="text-center mt-2">
                            @if($progressPercentage == 100)
                                <span class="badge badge-success">LUNAS</span>
                            @elseif($progressPercentage > 0)
                                <span class="badge badge-warning">SEBAGIAN</span>
                            @else
                                <span class="badge badge-danger">BELUM BAYAR</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Riwayat Pembayaran & Aksi -->
            <div class="col-md-8">
                <!-- Riwayat Pembayaran -->
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Riwayat Pembayaran</h3>
                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                <i class="fas fa-minus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        @if($siswa->pembayarans->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-hover table-striped">
                                    <thead>
                                        <tr>
                                            <th>Jenis Pembayaran</th>
                                            <th>Nominal</th>
                                            <th>Status</th>
                                            <th>Tanggal</th>
                                            <th>Keterangan</th>
                                            @if(Auth::user()->role == 'bendahara')
                                                <th width="15%">Aksi</th>
                                            @endif
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($siswa->pembayarans as $pembayaran)
                                        <tr>
                                            <td>
                                                <strong>{{ $pembayaran->jenisPembayaran->nama ?? 'Tidak Diketahui' }}</strong>
                                            </td>
                                            <td>
                                                Rp {{ number_format($pembayaran->jenisPembayaran->nominal ?? 0, 0, ',', '.') }}
                                            </td>
                                            <td>
                                                @if($pembayaran->status == 'approved')
                                                    <span class="badge badge-success">DISETUJUI</span>
                                                @elseif($pembayaran->status == 'pending')
                                                    <span class="badge badge-warning">MENUNGGU</span>
                                                @else
                                                    <span class="badge badge-danger">DITOLAK</span>
                                                @endif
                                            </td>
                                            <td>
                                                {{ $pembayaran->created_at->format('d/m/Y H:i') }}
                                            </td>
                                            <td>
                                                @if($pembayaran->status == 'rejected' && $pembayaran->alasan_reject)
                                                    <small class="text-danger">{{ $pembayaran->alasan_reject }}</small>
                                                @elseif($pembayaran->keterangan_admin)
                                                    <small class="text-muted">{{ $pembayaran->keterangan_admin }}</small>
                                                @else
                                                    <small class="text-muted">-</small>
                                                @endif
                                            </td>
                                            @if(Auth::user()->role == 'bendahara')
                                                <td class="text-center">
                                                    @if($pembayaran->status == 'pending')
                                                        <div class="btn-group btn-group-sm">
                                                            <button class="btn btn-success btn-approve" 
                                                                    data-id="{{ $pembayaran->id }}"
                                                                    data-jenis="{{ $pembayaran->jenisPembayaran->nama ?? 'Pembayaran' }}"
                                                                    title="Setujui">
                                                                <i class="fas fa-check"></i>
                                                            </button>
                                                            <button class="btn btn-danger btn-reject" 
                                                                    data-id="{{ $pembayaran->id }}"
                                                                    data-jenis="{{ $pembayaran->jenisPembayaran->nama ?? 'Pembayaran' }}"
                                                                    title="Tolak">
                                                                <i class="fas fa-times"></i>
                                                            </button>
                                                        </div>
                                                    @else
                                                        <span class="text-muted small">Tidak ada aksi</span>
                                                    @endif
                                                </td>
                                            @endif
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center py-4">
                                <i class="fas fa-receipt fa-3x text-muted mb-3"></i>
                                <p class="text-muted">Belum ada riwayat pembayaran</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Tagihan yang Harus Dibayar -->
                <div class="card card-warning mt-3">
                    <div class="card-header">
                        <h3 class="card-title">Tagihan yang Harus Dibayar</h3>
                    </div>
                    <div class="card-body">
                        @if($jenisPembayaran->count() > 0)
                            <div class="row">
                                @foreach($jenisPembayaran as $jenis)
                                    @php
                                        $isPaid = $siswa->pembayarans
                                            ->where('jenis_pembayaran_id', $jenis->id)
                                            ->where('status', 'approved')
                                            ->count() > 0;
                                        $pendingPayment = $siswa->pembayarans
                                            ->where('jenis_pembayaran_id', $jenis->id)
                                            ->where('status', 'pending')
                                            ->first();
                                    @endphp
                                    <div class="col-md-6 mb-3">
                                        <div class="card {{ $isPaid ? 'border-success' : 'border-warning' }}">
                                            <div class="card-body">
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <div>
                                                        <h6 class="card-title mb-1">{{ $jenis->nama }}</h6>
                                                        <p class="card-text text-muted mb-1">
                                                            Rp {{ number_format($jenis->nominal, 0, ',', '.') }}
                                                        </p>
                                                        @if($jenis->keterangan)
                                                            <small class="text-muted">{{ $jenis->keterangan }}</small>
                                                        @endif
                                                        @if($pendingPayment)
                                                            <small class="text-warning d-block mt-1">
                                                                <i class="fas fa-clock"></i> Menunggu approval
                                                            </small>
                                                        @endif
                                                    </div>
                                                    <div>
                                                        @if($isPaid)
                                                            <span class="badge badge-success">
                                                                <i class="fas fa-check"></i> LUNAS
                                                            </span>
                                                        @else
                                                            <span class="badge badge-warning">
                                                                <i class="fas fa-clock"></i> BELUM
                                                            </span>
                                                        @endif
                                                    </div>
                                                </div>
                                                @if(!$isPaid && !$pendingPayment && Auth::user()->role == 'bendahara')
                                                    <div class="mt-2">
                                                        <button class="btn btn-sm btn-outline-danger btn-hapus-tagihan"
                                                                data-jenis-id="{{ $jenis->id }}"
                                                                data-jenis-nama="{{ $jenis->nama }}"
                                                                data-siswa-id="{{ $siswa->id }}"
                                                                data-siswa-nama="{{ $siswa->name }}">
                                                            <i class="fas fa-trash"></i> Hapus Tagihan
                                                        </button>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-3">
                                <i class="fas fa-file-invoice-dollar fa-2x text-muted mb-2"></i>
                                <p class="text-muted">Tidak ada tagihan yang tersedia</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Statistik Pembayaran -->
                <div class="card card-info mt-3">
                    <div class="card-header">
                        <h3 class="card-title">Statistik Pembayaran</h3>
                    </div>
                    <div class="card-body">
                        <div class="row text-center">
                            <div class="col-4">
                                <div class="border-right">
                                    <h4 class="text-info">{{ $siswa->pembayarans->where('status', 'approved')->count() }}</h4>
                                    <small class="text-muted">Disetujui</small>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="border-right">
                                    <h4 class="text-warning">{{ $siswa->pembayarans->where('status', 'pending')->count() }}</h4>
                                    <small class="text-muted">Menunggu</small>
                                </div>
                            </div>
                            <div class="col-4">
                                <h4 class="text-danger">{{ $siswa->pembayarans->where('status', 'rejected')->count() }}</h4>
                                <small class="text-muted">Ditolak</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="row mt-3">
            <div class="col-12">
                <div class="card">
                    <div class="card-body text-center">
                        <!-- PERBAIKAN: Gunakan route yang benar berdasarkan role user -->
                        @if(Auth::user()->role == 'bendahara')
                            <a href="{{ route('bendahara.data-siswa.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Kembali ke Data Siswa
                            </a>
                        @elseif(Auth::user()->role == 'admin')
                            <a href="{{ route('admin.data-siswa.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Kembali ke Data Siswa
                            </a>
                        @else
                            <a href="{{ url('/') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Kembali
                            </a>
                        @endif
                        
                        <!-- PERBAIKAN: Hanya bendahara yang bisa hapus siswa -->
                        @if($siswa->pembayarans->count() == 0 && Auth::user()->role == 'bendahara')
                            <button class="btn btn-danger" id="btnDeleteSiswa" data-id="{{ $siswa->id }}">
                                <i class="fas fa-trash"></i> Hapus Siswa
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Modal Approve -->
<div class="modal fade" id="modalApprove">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Setujui Pembayaran</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <form id="formApprove">
                @csrf
                <input type="hidden" name="pembayaran_id" id="approve_pembayaran_id">
                <div class="modal-body">
                    <p>Anda akan menyetujui pembayaran untuk:</p>
                    <p><strong id="approve_jenis_pembayaran"></strong></p>
                    <div class="form-group">
                        <label>Keterangan (Opsional)</label>
                        <textarea name="keterangan_admin" class="form-control" rows="3" placeholder="Tambahkan keterangan jika diperlukan..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success">Setujui</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Reject -->
<div class="modal fade" id="modalReject">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Tolak Pembayaran</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <form id="formReject">
                @csrf
                <input type="hidden" name="pembayaran_id" id="reject_pembayaran_id">
                <div class="modal-body">
                    <p>Anda akan menolak pembayaran untuk:</p>
                    <p><strong id="reject_jenis_pembayaran"></strong></p>
                    <div class="form-group">
                        <label>Alasan Penolakan <span class="text-danger">*</span></label>
                        <textarea name="alasan_reject" class="form-control" rows="3" placeholder="Berikan alasan penolakan..." required></textarea>
                    </div>
                    <div class="form-group">
                        <label>Keterangan (Opsional)</label>
                        <textarea name="keterangan_admin" class="form-control" rows="2" placeholder="Tambahkan keterangan..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger">Tolak</button>
                </div>
            </form>
        </div>
    </div>
</div>
</div>
@endsection

@section('styles')
<style>
    .progress-bar {
        font-weight: bold;
    }
    .card-primary .bg-primary {
        background-color: #007bff !important;
    }
    .border-right {
        border-right: 1px solid #dee2e6;
    }
    
    .btn-group-sm > .btn {
        padding: 0.25rem 0.5rem;
        font-size: 0.75rem;
    }

    .show-siswa-page .table td,
    .show-siswa-page .table th {
        vertical-align: middle;
    }
    
    @media (max-width: 768px) {
        .show-siswa-page .content-header h1 {
            font-size: 1.25rem;
        }

        .show-siswa-page .breadcrumb {
            float: none !important;
            margin-top: .45rem;
            font-size: .82rem;
        }

        .show-siswa-page .card-body {
            padding: .85rem;
        }

        .show-siswa-page .card-title {
            font-size: 1rem;
        }

        .show-siswa-page .btn-group {
            width: 100%;
            display: flex;
        }

        .show-siswa-page .btn-group .btn {
            flex: 1;
        }

        .show-siswa-page .btn-hapus-tagihan {
            width: 100%;
        }

        .border-right {
            border-right: none;
            border-bottom: 1px solid #dee2e6;
            padding-bottom: 1rem;
            margin-bottom: 1rem;
        }
        
        .modal-dialog {
            margin: 0.5rem;
        }
        
        .table-responsive {
            font-size: 0.875rem;
        }

        .show-siswa-page .modal-footer {
            display: flex;
            flex-direction: column-reverse;
            gap: .5rem;
        }

        .show-siswa-page .modal-footer .btn {
            width: 100%;
            margin: 0 !important;
        }
    }
</style>
@endsection

@section('scripts')
<script>
$(document).ready(function() {
    // Setup CSRF token untuk semua AJAX request
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    // Approve Pembayaran - Hanya untuk bendahara
    @if(Auth::user()->role == 'bendahara')
    $(document).on('click', '.btn-approve', function() {
        var pembayaranId = $(this).data('id');
        var jenisPembayaran = $(this).data('jenis');
        
        $('#approve_pembayaran_id').val(pembayaranId);
        $('#approve_jenis_pembayaran').text(jenisPembayaran);
        $('#modalApprove').modal('show');
    });

    // Reject Pembayaran - Hanya untuk bendahara
    $(document).on('click', '.btn-reject', function() {
        var pembayaranId = $(this).data('id');
        var jenisPembayaran = $(this).data('jenis');
        
        $('#reject_pembayaran_id').val(pembayaranId);
        $('#reject_jenis_pembayaran').text(jenisPembayaran);
        $('#modalReject').modal('show');
    });

    // Hapus Tagihan - Hanya untuk bendahara
    $(document).on('click', '.btn-hapus-tagihan', function() {
        var jenisId = $(this).data('jenis-id');
        var jenisNama = $(this).data('jenis-nama');
        var siswaId = $(this).data('siswa-id');
        var siswaNama = $(this).data('siswa-nama');
        
        Swal.fire({
            title: 'Hapus Tagihan?',
            html: `
                <p>Anda akan menghapus tagihan:</p>
                <p><strong>${jenisNama}</strong></p>
                <p>untuk siswa: <strong>${siswaNama}</strong></p>
                <p class="text-danger">Tindakan ini tidak dapat dibatalkan!</p>
            `,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: '<i class="fas fa-trash"></i> Ya, Hapus!',
            cancelButtonText: '<i class="fas fa-times"></i> Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                // Show loading
                Swal.fire({
                    title: 'Menghapus...',
                    text: 'Sedang menghapus tagihan',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                $.ajax({
                    url: "{{ url('bendahara/hapus-tagihan') }}",
                    method: "POST",
                    data: {
                        _token: "{{ csrf_token() }}",
                        jenis_pembayaran_id: jenisId,
                        user_id: siswaId
                    },
                    success: function(response) {
                        if (response.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Terhapus!',
                                text: response.message,
                                timer: 2000,
                                showConfirmButton: false
                            }).then(() => {
                                location.reload();
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: response.message,
                                confirmButtonText: 'OK'
                            });
                        }
                    },
                    error: function(xhr) {
                        let errorMessage = 'Gagal menghapus tagihan';
                        
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMessage = xhr.responseJSON.message;
                        }
                        
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: errorMessage,
                            confirmButtonText: 'OK'
                        });
                    }
                });
            }
        });
    });

    // Form Approve - Hanya untuk bendahara
    $('#formApprove').submit(function(e) {
        e.preventDefault();
        var formData = $(this).serialize();
        var pembayaranId = $('#approve_pembayaran_id').val();
        
        // Show loading state
        var submitBtn = $(this).find('button[type="submit"]');
        var originalText = submitBtn.html();
        submitBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Menyimpan...');

        $.ajax({
            url: "{{ url('bendahara/pembayaran') }}/" + pembayaranId + "/approve",
            method: "POST",
            data: formData,
            success: function(response) {
                if (response.success) {
                    $('#modalApprove').modal('hide');
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: response.message,
                        timer: 2000,
                        showConfirmButton: false
                    }).then(() => {
                        location.reload();
                    });
                } else {
                    submitBtn.prop('disabled', false).html(originalText);
                    Swal.fire('Error', response.message || 'Gagal menyetujui pembayaran', 'error');
                }
            },
            error: function(xhr) {
                submitBtn.prop('disabled', false).html(originalText);
                let errorMessage = 'Terjadi kesalahan saat menyetujui pembayaran';
                
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message;
                }
                
                Swal.fire('Error', errorMessage, 'error');
            }
        });
    });

    // Form Reject - Hanya untuk bendahara
    $('#formReject').submit(function(e) {
        e.preventDefault();
        var formData = $(this).serialize();
        var pembayaranId = $('#reject_pembayaran_id').val();
        
        // Show loading state
        var submitBtn = $(this).find('button[type="submit"]');
        var originalText = submitBtn.html();
        submitBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Menyimpan...');

        $.ajax({
            url: "{{ url('bendahara/pembayaran') }}/" + pembayaranId + "/reject",
            method: "POST",
            data: formData,
            success: function(response) {
                if (response.success) {
                    $('#modalReject').modal('hide');
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: response.message,
                        timer: 2000,
                        showConfirmButton: false
                    }).then(() => {
                        location.reload();
                    });
                } else {
                    submitBtn.prop('disabled', false).html(originalText);
                    Swal.fire('Error', response.message || 'Gagal menolak pembayaran', 'error');
                }
            },
            error: function(xhr) {
                submitBtn.prop('disabled', false).html(originalText);
                let errorMessage = 'Terjadi kesalahan saat menolak pembayaran';
                
                if (xhr.responseJSON && xhr.responseJSON.errors) {
                    errorMessage = '';
                    $.each(xhr.responseJSON.errors, function(key, value) {
                        errorMessage += value + '<br>';
                    });
                } else if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message;
                }
                
                Swal.fire('Error', errorMessage, 'error');
            }
        });
    });
    @endif

    // Delete Siswa - Hanya untuk bendahara
    @if(Auth::user()->role == 'bendahara')
    $('#btnDeleteSiswa').click(function() {
        var id = $(this).data('id');
        var siswaName = "{{ $siswa->name }}";
        
        Swal.fire({
            title: 'Hapus Siswa?',
            html: `
                <p>Anda akan menghapus siswa: <strong>${siswaName}</strong></p>
                <p class="text-danger">Data yang dihapus tidak dapat dikembalikan!</p>
            `,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: '<i class="fas fa-trash"></i> Ya, Hapus!',
            cancelButtonText: '<i class="fas fa-times"></i> Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                // Show loading
                Swal.fire({
                    title: 'Menghapus...',
                    text: 'Sedang menghapus data siswa',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                $.ajax({
                    // PERBAIKAN: Gunakan route yang benar
                    url: "{{ url('bendahara/data-siswa') }}/" + id,
                    method: "DELETE",
                    data: {
                        _token: "{{ csrf_token() }}"
                    },
                    success: function(response) {
                        if (response.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Terhapus!',
                                text: response.message,
                                timer: 2000,
                                showConfirmButton: false
                            }).then(() => {
                                // PERBAIKAN: Gunakan route yang benar
                                window.location.href = "{{ route('bendahara.data-siswa.index') }}";
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: response.message,
                                confirmButtonText: 'OK'
                            });
                        }
                    },
                    error: function(xhr) {
                        let errorMessage = 'Gagal menghapus data siswa';
                        
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMessage = xhr.responseJSON.message;
                        }
                        
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: errorMessage,
                            confirmButtonText: 'OK'
                        });
                    }
                });
            }
        });
    });
    @endif

    // Reset modal forms when closed
    $('#modalApprove, #modalReject').on('hidden.bs.modal', function() {
        $(this).find('form')[0].reset();
        $(this).find('button[type="submit"]').prop('disabled', false).html(function() {
            return $(this).hasClass('btn-success') ? 'Setujui' : 'Tolak';
        });
    });
});
</script>
@endsection